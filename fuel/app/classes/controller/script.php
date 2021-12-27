<?php

use Fuel\Core\Controller;
use Fuel\Core\DB;
use Model\Helper;

class Controller_Script extends Controller {
	private const ACTIVE = false;

	/** Met au bon format les données de datation des sujets handicapés. */
	public function action_datationreformat() {
		if (Controller_Script::ACTIVE === false) {
			echo "Les scripts sont actuellement désactivés."; 
			return;
		}
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
}