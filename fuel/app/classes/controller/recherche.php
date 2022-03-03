<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Sujethandicape;
use Model\Helper;
use Model\Searchresult;

class Controller_Recherche extends Controller_Template {
	public function action_index() {
		Compte::checkPermissionRedirect("La page n'est pas encore disponible pour le public.", Compte::PERM_ADMIN);

		$data = array();

		$this->template->title = 'Recherche';
		$this->template->content = View::forge('recherche/index', $data);
	}

	public function action_resultat() {
		Compte::checkPermissionRedirect("La page n'est pas encore disponible pour le public.", Compte::PERM_ADMIN);

		$data = array();

		if (isset($_GET["search"])) {
			$refOp = new Operation($_GET);
			$refSubject = new Sujethandicape($_GET);
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
		if (!empty($_GET["annee_min"])) $query->where("annee", ">=", $_GET["annee_min"]);
		if (!empty($_GET["annee_max"])) $query->where("annee", "<=", $_GET["annee_max"]);
		// if ($refOp->getOrganisme() === null) echo "OUEEEEEE";
		// Helper::varDump($refOp->getOrganisme());

		$result = $query->execute()->as_array();
		/** @var Operation[] */
		$operations = array();
		foreach ($result as $op) {
			$operations[] = new Operation($op);
		}

		// Tri en fonction de la position (trop compliqué a intégré directement dans SQL)
		if ($refOp->getX() !== null && $refOp->getY() !== null && !empty($_GET["radius"])) {
			/** @var float Rayon de recherche en mètres. */
			$radius = floatval($_GET["radius"]) * 1000;
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
		$query = DB::select("sujet.id", "id_sujet_handicape", "sexe", "dating_min", "dating_max", "milieu_vie", "id_type_depot", "id_sepulture")->from(array("sujet_handicape", "sujet"))
			->join(array("groupe_sujets", "groupe"))
			->on("sujet.id_groupe_sujets", "=", "groupe.id")
			->where("groupe.id_operation", "=", $opParent->getId());

		if (!empty($refSubject->getSexe())) $query->where("sexe", "=", $refSubject->getSexe());
		
		$result = $query->execute()->as_array();
		$subjects = array();
		foreach ($result as $sub) {
			$subjects[] = new Sujethandicape($sub);
		}
		return $subjects;
	}
}
