<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Diagnostic;
use Model\Db\Operation;
use Model\Db\Sujethandicape;
use Model\Helper;
use Model\Messagehandler;
use Model\Searchresult;

class Controller_Recherche extends Controller_Template {
	/** Page de recherche */
	public function action_index() {
		Compte::checkPermissionRedirect("Vous devez vous connecter pour accéder à cette page.", Compte::PERM_WRITE);

		$data = array();

		if (isset($_POST["keepOptions"])) {
			// Récupération de la recherche précédente
			Helper::startSession();
			$options = $_SESSION["searchOptions"];
			$data["options"] = $options;
		}

		$this->template->title = 'Recherche';
		$this->template->content = View::forge('recherche/index', $data);
	}

	/** Page d'affichage des résultats de recherche */
	public function action_resultat() {
		Compte::checkPermissionRedirect("Vous devez vous connecter pour accéder à cette page.", Compte::PERM_WRITE);
		if (!isset($_POST["search"])) Response::redirect("/recherche");

		$data = array();

		// Récupération des infos selon la recherche
		$json = Helper::postQuery("https://archeohandi.huma-num.fr/public/recherche/api", $_POST);
		$results = array();
		if ($json == false) {
			Messagehandler::prepareAlert("Un problème est survenu lors de la recherche des résultats.", "danger");
		} else {
			$jsonArray = json_decode($json, true);
			foreach ($jsonArray as $key => $searchResultArray) {
				$results[$key] = Searchresult::fromArray($searchResultArray);
			}
		}
		
		// Stockage des options de recherche en cas de retour à la page de choix de la recherche
		Helper::startSession();
		$_SESSION["searchOptions"] = $_POST;

		$data["results"] = $results;

		$this->template->title = 'Résultat recherche';
		$this->template->content = View::forge('recherche/resultat', $data);
	}

	/** Page d'affichage des résultats de recherche */
	public function action_api() {
		// if (!Compte::checkPermission(Compte::PERM_WRITE)) return Response::forge("401", 401);
		if (empty($_POST)) return Response::forge("403", 0);

		// Définition des modèles de recherche
		$refOp = new Operation($_POST);
		$refSubject = new Sujethandicape($_POST);
		$results = array();

		// Récupération des infos selon la recherche
		$resOp = $this->searchOperations($refOp);
		foreach ($resOp as $op) {
			$searchRes = new Searchresult();
			$searchRes->operation = $op;
			$resSu = $this->searchSubjects($refSubject, $op);
			if (!empty($resSu)) {
				$searchRes->subjects = $resSu;
				$results[$op->getId()] = $searchRes->toArray();
			}
		}

		$json = json_encode($results, JSON_PRETTY_PRINT);
		return Response::forge($json);
	}

	/** 
	 * Récupère toutes les opérations correspondant à l'opération de recherche donné.
	 * @return Operation[]
	 */
	private function searchOperations(Operation $refOp): array {
		$query = DB::select(
			"operations.id", "annee", "id_commune", "adresse", "operations.X", "operations.Y", "id_organisme", "id_type_op", "EA", "OA", "patriarche",
			"numero_operation", "arrete_prescription", "responsable", "anthropologues", "paleopathologistes", "bibliographie", "date_ajout", "complet"
		)->from("operations");

		if ($refOp->getId() !== null) $query->where("operations.id", "=", $refOp->getId());
		if ($refOp->getIdCommune() !== null) $query->where("id_commune", "=", $refOp->getIdCommune());
		if (!empty($_POST["insee"])) {
			$query->join("commune")->on("commune.id", "=", "id_commune")->where("insee", "=", $_POST["insee"]);
		}
		if (!empty($refOp->getAdresse())) $query->where("adresse", "LIKE", "%{$refOp->getAdresse()}%");
		if (!empty($_POST["annee_min"])) $query->where("annee", ">=", $_POST["annee_min"]);
		if (!empty($_POST["annee_max"])) $query->where("annee", "<=", $_POST["annee_max"]);
		// if ($refOp->getOrganisme() === null) echo "OUEEEEEE";
		// Helper::varDump($refOp->getOrganisme());

		$result = $query->execute()->as_array();
		/** @var Operation[] */
		$operations = array();
		foreach ($result as $op) {
			$operations[] = new Operation($op);
		}

		// Tri en fonction de la position (trop compliqué a intégré directement dans SQL)
		if ($refOp->getX() !== null && $refOp->getY() !== null && !empty($_POST["radius"])) {
			/** @var float Rayon de recherche en mètres. */
			$radius = floatval($_POST["radius"]) * 1000;
			for ($i = count($operations) -1; $i >= 0; $i--) {
				$op = $operations[$i];
				if ($op->getX() === null || $op->getY() === null) {
					unset($operations[$i]);
					continue;
				}
				if (Helper::worldDistance($refOp->getY(), $refOp->getX(), $op->getY(), $op->getX()) > $radius) {
					unset($operations[$i]);
				}
			}
		}

		return $operations;
	}

	/**
	 * Récupère tous les sujets correspondant au sujet de recherche donné.
	 * @param Operation $opParent Operation parent au sujet.
	 * @return Sujethandicape[]
	 */
	private function searchSubjects(Sujethandicape $refSubject, Operation $opParent): array {
		$query = DB::select("sujet.id", "id_sujet_handicape", "age_min", "age_max", "sexe", "dating_min", "dating_max", "milieu_vie", "contexte", "contexte_normatif",
		                    "comment_contexte", "comment_diagnostic", "description_mobilier", "id_type_depot", "id_sepulture", "id_depot", "id_groupe_sujets")
			->from(array("sujet_handicape", "sujet"))
			->join(array("groupe_sujets", "groupe"))
			->on("sujet.id_groupe_sujets", "=", "groupe.id")
			->where("groupe.id_operation", "=", $opParent->getId());

		if ($refSubject->getId() !== null) $query->where("sujet.id", "=", $refSubject->getId());
		if (!empty($refSubject->getIdSujetHandicape())) $query->where("id_sujet_handicape", "=", $refSubject->getIdSujetHandicape());
		if (!empty($refSubject->getSexe())) $query->where("sexe", "=", $refSubject->getSexe());
		if (!empty($_POST["id_chronologie"])) $query->where("groupe.id_chronologie", "=", $_POST["id_chronologie"]);
		if ($refSubject->getAgeMin() !== null) $query->where("age_max", ">=", $refSubject->getAgeMin());
		if ($refSubject->getAgeMax() !== null) $query->where("age_min", "<=", $refSubject->getAgeMax());
		if ($refSubject->getDatingMin() !== null) $query->where("dating_max", ">=", $refSubject->getDatingMin());
		if ($refSubject->getDatingMax() !== null) $query->where("dating_min", "<=", $refSubject->getDatingMax());
		if ($refSubject->getIdTypeDepot() !== null) $query->where("id_type_depot", "=", $refSubject->getIdTypeDepot());
		if ($refSubject->getIdTypeSepulture() !== null) $query->where("id_sepulture", "=", $refSubject->getIdTypeSepulture());
		if (!empty($refSubject->getContexteNormatif())) $query->where("contexte_normatif", "=", $refSubject->getContexteNormatif());
		if (!empty($refSubject->getMilieuVie())) $query->where("milieu_vie", "=", $refSubject->getMilieuVie());
		if (!empty($refSubject->getContexte())) $query->where("contexte", "=", $refSubject->getContexte());

		// Recherche par pathologie
		if (!empty($_POST["pathologies"])) {
			// $query->join(array("atteinte_pathologie", "ap"))->on("ap.id_sujet", "=", "sujet.id");

			$i=0;
			$where = "";
			foreach ($_POST["pathologies"] as $pathology) {
				if ($i++ === 0) $where = "ap.id_pathologie=$pathology";
				else $where .= " OR ap.id_pathologie=$pathology";
			}
			$query->where(DB::expr(
				"(
					SELECT COUNT(copy.id)
					FROM sujet_handicape AS copy
					JOIN atteinte_pathologie AS ap
					ON ap.id_sujet=copy.id
					WHERE copy.id=sujet.id
					AND ($where)
				)=$i"
			));
		}

		// Recherche par atteinte invalidante
		if (!empty($_POST["pathologies"])) {
			// $query->join(array("atteinte_pathologie", "ap"))->on("ap.id_sujet", "=", "sujet.id");

			$i=0;
			$where = "";
			foreach ($_POST["pathologies"] as $pathology) {
				if ($i++ === 0) $where = "ap.id_pathologie=$pathology";
				else $where .= " OR ap.id_pathologie=$pathology";
			}
			$query->where(DB::expr(
				"(
					SELECT COUNT(copy.id)
					FROM sujet_handicape AS copy
					JOIN atteinte_pathologie AS ap
					ON ap.id_sujet=copy.id
					WHERE copy.id=sujet.id
					AND ($where)
				)=$i"
			));
		}
		
		$result = $query->execute()->as_array();
		/** @var Sujethandicape[] */
		$subjects = array();
		foreach ($result as $sub) {
			$subjects[] = new Sujethandicape($sub);
		}

		// Recherche des diagnostics sans passer par SQL pcq c trop compliqué
		foreach (Diagnostic::fetchAll() as $dia) {
			$diaId = $dia->getId();
			$name = "diagnostic_$diaId";
			if (isset($_POST[$name])) {
				// Diagnostic coché
				if (isset($_POST["diagnostics"][$diaId])) {
					// Localisation coché
					$localisations = $_POST["diagnostics"][$diaId];
					foreach ($subjects as $i => $sub) {
						if (!$sub->hasDiagnosis($diaId)) {
							unset($subjects[$i]);
							continue;
						}
						$subDia = $sub->getDiagnosis($diaId);
						foreach ($localisations as $loc) {
							if (!$subDia->isLocatedFromId($loc)) {
								unset($subjects[$i]);
								break;
							}
						}
					}
				} else {
					// Localisation pas coché
					foreach ($subjects as $i => $sub) {
						if (!$sub->hasDiagnosis($diaId)) unset($subjects[$i]);
					}
				}
			}
		}

		return $subjects;
	}
}
