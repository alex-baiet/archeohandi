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
	/** Page de choix de la recherche. */
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

	/** Page des résultats de recherche. */
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
			if ($jsonArray == null && $json !== "[]") {
				return Response::forge($json, 500);
			}
			foreach ($jsonArray as $key => $searchResultArray) {
				// echo $searchResultArray["test"];
				$results[$key] = Searchresult::fromArray($searchResultArray);
			}
		}
		krsort($results);

		// Stockage des options de recherche en cas de retour à la page de choix de la recherche
		Helper::startSession();
		$_SESSION["searchOptions"] = $_POST;

		$data["results"] = $results;

		$this->template->title = 'Résultat de recherche';
		$this->template->content = View::forge('recherche/resultat', $data);
	}

	/** Page des résultats de recherche au format JSON. */
	public function action_api() {
		// if (!Compte::checkPermission(Compte::PERM_WRITE)) return Response::forge("401", 401);
		if (empty($_POST)) return Response::forge("0", 403);

		// Définition des modèles de recherche
		$refOp = new Operation($_POST);
		$refSubject = new Sujethandicape($_POST);
		$results = array();

		// Récupération des infos selon la recherche
		$resOp = $this->searchOperations($refOp);
		$searchSubjects = $this->searchingSubject($_POST);
		foreach ($resOp as $op) {
			$searchRes = new Searchresult();
			$searchRes->operation = $op;
			$resSu = $this->searchSubjects($refSubject, $op);
			if (!(empty($resSu) && $searchSubjects)) {
				$searchRes->subjects = $resSu;
				$results[$op->getId()] = $searchRes->toArray();
			}
		}

		$json = json_encode($results, JSON_PRETTY_PRINT);
		return Response::forge($json);
	}

	/** 
	 * Récupère toutes les opérations correspondant à l'opération de recherche donné et aux données POST.
	 * @return Operation[]
	 */
	private function searchOperations(Operation $refOp): array {
		$query = DB::select(
			"operation.id", "annee", "id_commune", "adresse", "operation.longitude", "operation.latitude", "id_organisme", "id_type_op", "ea", "oa", "patriarche",
			"numero_operation", "arrete_prescription", "bibliographie", "date_ajout", "complet",)
			->from("operation");

		if ($refOp->getId() !== null) $query->where("operation.id", "=", $refOp->getId());
		if (!empty($_POST["insee"]) || !empty($_POST["commune"]) || !empty($_POST["departement"])) {
			$query->join("commune")->on("commune.id", "=", "id_commune");
			
			if (!empty($_POST["insee"])) $query->where("insee", "=", $_POST["insee"]);
			if (!empty($_POST["commune"])) $query->where("commune.nom", "=", $_POST["commune"]);
			if (!empty($_POST["departement"])) $query->where("departement", "=", $_POST["departement"]);
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
		if ($refOp->getLongitude() !== null && $refOp->getLatitude() !== null && !empty($_POST["radius"])) {
			/** @var float Rayon de recherche en mètres. */
			$radius = floatval($_POST["radius"]) * 1000;
			for ($i = count($operations) -1; $i >= 0; $i--) {
				$op = $operations[$i];
				if ($op->getLongitude() === null || $op->getLatitude() === null) {
					unset($operations[$i]);
					continue;
				}
				if (Helper::worldDistance($refOp->getLatitude(), $refOp->getLongitude(), $op->getLatitude(), $op->getLongitude()) > $radius) {
					unset($operations[$i]);
				}
			}
		}

		return $operations;
	}

	/** Vérifie dans les données passé en argument si il y a recherche sur les sujets ou non. */
	private function searchingSubject($data): bool {
		if (!empty($data["id_sujet"])
			|| !empty($data["id_sujet_handicape"])
			|| !empty($data["id_chronologie"])
			|| !empty($data["sexe"])
			|| !empty($data["age_min"])
			|| !empty($data["age_max"])
			|| !empty($data["date_min"])
			|| !empty($data["date_max"])
			|| !empty($data["id_type_depot"])
			|| !empty($data["id_sepulture"])
			|| !empty($data["contexte_normatif"])
			|| !empty($data["milieu_vie"])
			|| !empty($data["contexte"])
			|| !empty($data["pathologies"])
		) {
			return true;
		}
		foreach (Diagnostic::fetchAll() as $dia) {
			if (isset($data["diagnostic_{$dia->getId()}"])) return true;
		}
		return false;
	}

	/**
	 * Récupère tous les sujets correspondant au sujet de recherche donné et aux données POST.
	 * @param Operation $opParent Operation parent au sujet.
	 * @return Sujethandicape[]
	 */
	private function searchSubjects(Sujethandicape $refSubject, Operation $opParent): array {
		$query = DB::select("sujet.id", "id_sujet_handicape", "age_min", "age_max", "sexe", "date_min", "date_max", "milieu_vie", "contexte", "contexte_normatif",
		                    "comment_contexte", "comment_diagnostic", "description_mobilier", "id_type_depot", "id_sepulture", "id_depot", "id_groupe")
			->from(array("sujet_handicape", "sujet"))
			->join(array("groupe", "groupe"))
			->on("sujet.id_groupe", "=", "groupe.id")
			->where("groupe.id_operation", "=", $opParent->getId());

		if ($refSubject->getId() !== null) $query->where("sujet.id", "=", $refSubject->getId());
		if (!empty($refSubject->getIdSujetHandicape())) $query->where("id_sujet_handicape", "=", $refSubject->getIdSujetHandicape());
		if (!empty($refSubject->getSexe())) $query->where("sexe", "=", $refSubject->getSexe());
		if (!empty($_POST["id_chronologie"])) $query->where("groupe.id_chronologie", "=", $_POST["id_chronologie"]);
		if ($refSubject->getAgeMin() !== null) $query->where("age_max", ">=", $refSubject->getAgeMin());
		if ($refSubject->getAgeMax() !== null) $query->where("age_min", "<=", $refSubject->getAgeMax());
		if ($refSubject->getDateMin() !== null) $query->where("date_max", ">=", $refSubject->getDateMin());
		if ($refSubject->getDateMax() !== null) $query->where("date_min", "<=", $refSubject->getDateMax());
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
