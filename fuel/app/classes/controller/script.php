<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Helper;
use Model\Script\Import;

class Controller_Script extends Controller_Template {
	private const ACTIVE = false;

	/** Vérifie que les scripts peuvent être exécutés. */
	private function checkPermission(): bool {
		Compte::checkPermissionRedirect("Cette page est reservé aux administrateurs.", Compte::PERM_ADMIN);

		if (Controller_Script::ACTIVE === true) {
			echo "Les scripts sont actuellement désactivés."; 
			return false;
		}
		return true;
	}

	/** Met au bon format les données de datation des sujets handicapés. */
	public function action_datationreformat() {
		if (!$this->checkPermission()) return;
		
		$subjects = Helper::querySelect("SELECT * FROM sujet_handicape");

		foreach ($subjects as $subj) {
			$datings = explode(";", str_replace("]", "", str_replace("[", "",  $subj["datation"])));
			$dating_min = is_numeric($datings[0]) ? intval($datings[0]) : NULL;
			$dating_max = is_numeric($datings[1]) ? intval($datings[1]) : NULL;

			Helper::varDump(array($dating_min, $dating_max));
			DB::update("sujet_handicape")
				->set(array("dating_min" => $dating_min, "dating_max" => $dating_max))
				->where("id", "=", $subj["id"])
				->execute();
		}
	}

	public function action_import_csv() {
		if (!$this->checkPermission()) return;

		$data = array();
		
		$this->template->title = 'Import CSV';
		$this->template->content = View::forge('script/import_csv', $data, false);
	}

	public function action_import_csv_result() {
		if (!$this->checkPermission()) return;

		$data = array();

		// Récupérations des opérations
		$resultsOp = array();
		if (isset($_FILES["file_operation"]) && $_FILES["file_operation"]["error"] === 0) {
			$resultsOp = Import::importFileOperations(file_get_contents($_FILES["file_operation"]["tmp_name"]));
		}
		$data["resultsOp"] = $resultsOp;
		
		$this->template->title = 'Import CSV | Résultats';
		$this->template->content = View::forge('script/import_csv_result', $data, false);
	}
}