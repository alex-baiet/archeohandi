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

		$this->template->title = 'Consultation du sujet';
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

		if ($refOp->getIdCommune() !== null) {
			//$query->join("commune", "INNER")->on("commune.id", "=", "operations.id_commune");
			$query->where("id_commune", "=", $refOp->getIdCommune());
		}

		$result = $query->execute()->as_array();
		$operations = array();
		echo $query;
		foreach ($result as $op) {
			$operations[] = new Operation($op);
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
		
		$result = $query->execute()->as_array();
		$subjects = array();
		foreach ($result as $sub) {
			$subjects[] = new Sujethandicape($sub);
		}
		return $subjects;
	}
}
