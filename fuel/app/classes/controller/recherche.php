<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Diagnostic;
use Model\Db\Operation;
use Model\Db\Sujethandicape;
use Model\Helper;
use Model\Searchresult;

class Controller_Recherche extends Controller_Template {
	public function action_index() {
		Compte::checkPermissionRedirect("Vous devez vous connecter pour accéder à cette page.", Compte::PERM_WRITE);

		$data = array();

		$this->template->title = 'Recherche';
		$this->template->content = View::forge('recherche/index', $data);
	}

	public function action_resultat() {
		Compte::checkPermissionRedirect("Vous devez vous connecter pour accéder à cette page.", Compte::PERM_WRITE);

		$data = array();

		if (isset($_POST["search"])) {
			$refOp = new Operation($_POST);
			$refSubject = new Sujethandicape($_POST);
			$results = array();

			$resOp = $this->searchOperations($refOp);
			foreach ($resOp as $op) {
				$searchRes = new Searchresult();
				$searchRes->operation = $op;
				$resSu = $this->searchSubjects($refSubject, $op);
				$searchRes->subjects = $resSu;
				$results[] = $searchRes;
			}

			$data["results"] = $results;
			$data["operation"] = $refOp;
			$data["subject"] = $refSubject;
		}

		$this->template->title = 'Résultat recherche';
		$this->template->content = View::forge('recherche/resultat', $data);
	}

	/** Récupère toutes les opérations correspondant à l'opération de recherche donné. */
	private function searchOperations(Operation $refOp): array {
		$query = DB::select()->from("operations");

		// Filtre commune
		if ($refOp->getIdCommune() !== null) $query->where("id_commune", "=", $refOp->getIdCommune());
		// Filtre adresse
		if (!empty($refOp->getAdresse())) $query->where("adresse", "LIKE", "%{$refOp->getAdresse()}%");
		// Filtre année
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
	 */
	private function searchSubjects(Sujethandicape $refSubject, Operation $opParent): array {
		$query = DB::select("sujet.id", "id_sujet_handicape", "age_min", "age_max", "sexe", "dating_min", "dating_max", "milieu_vie", "contexte", "contexte_normatif",
		                    "comment_contexte", "comment_diagnostic", "description_mobilier", "id_type_depot", "id_sepulture", "id_depot", "id_groupe_sujets")
			->from(array("sujet_handicape", "sujet"))
			->join(array("groupe_sujets", "groupe"))
			->on("sujet.id_groupe_sujets", "=", "groupe.id")
			->where("groupe.id_operation", "=", $opParent->getId());

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
					for ($i=count($subjects)-1; $i>=0; $i--) {
						if (!$subjects[$i]->hasDiagnosis($diaId)) {
							unset($subjects[$i]);
							continue;
						}
						$subDia = $subjects[$i]->getDiagnosis($diaId);
						foreach ($localisations as $loc) {
							if (!$subDia->isLocatedFromId($loc)) {
								unset($subjects[$i]);
								break;
							}
						}
					}
				} else {
					// Localisation pas coché
					for ($i=count($subjects)-1; $i>=0; $i--) {
						if (!$subjects[$i]->hasDiagnosis($diaId)) unset($subjects[$i]);
					}
				}
			}
		}

		return $subjects;
	}
}
