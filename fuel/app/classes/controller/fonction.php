<?php

use Fuel\Core\Controller;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Commune;
use Model\Helper;

class Controller_Fonction extends Controller {

	/** @return Commune[] */
	private static function autoCompleteCommune(string $input): array {
		$results = Helper::querySelect("SELECT id, nom, departement FROM commune WHERE nom LIKE \"$input%\";");
		$arr = array();
		foreach ($results as $res) {
			$arr[] = new Commune($res);
		}
		return $arr;
	}

	/** Affiche une page de tous les mots permettant de compléter le début de mot "query" passé en POST. */
	public function action_action() {
		if (Input::method() !== "POST") Response::redirect("/accueil");

		// Initialisation des valeurs
		/** Identifiant du champ */
		$id = Input::post("id");
		/** Type de la demande */
		$type = Input::post("type");
		/** Debut de texte a autocompléter */
		$input = Input::post("input");
		/** @var int */
		$maxResultCount = Input::post("max_result_count") !== null ? Input::post("max_result_count") : 10;

		$data = array(
			"id" => $id,
			"maxResultCount" => $maxResultCount
		);

		if ($type === "commune") {
			$data["communes"] = Controller_Fonction::autoCompleteCommune($input);
		}

		return Response::forge(View::forge('fonction/action', $data));
	}
}
