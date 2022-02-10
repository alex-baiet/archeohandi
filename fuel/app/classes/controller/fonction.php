<?php

use Fuel\Core\Controller;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Commune;
use Model\Db\Compte;
use Model\Helper;

class Controller_Fonction extends Controller {

	/** @return Commune[] */
	private static function autoCompleteCommune(string $input): array {
		$results = DB::select("id", "nom", "departement")
			->from("commune")
			->where("nom", "LIKE", "$input%")
			->execute()
			->as_array();
		$arr = array();
		foreach ($results as $res) {
			$arr[] = new Commune($res);
		}
		return $arr;
	}

	/** @return Compte[] */
	private static function autoCompleteCompte(string $input): array {
		$results = DB::select()
			->from("compte")
			->where("login", "LIKE", "$input%")
			->or_where("prenom", "LIKE", "$input%")
			->or_where("nom", "LIKE", "$input%")
			->execute()
			->as_array();
		$arr = array();
		foreach ($results as $res) {
			$arr[] = new Compte($res);
		}
		return $arr;
	}

	public function action_autocomplete() {
		$data = array();

		$table = $_POST["table"];
		$select = $_POST["select"];
		$where = $_POST["where"];
		$input = $_POST["input"];

		// Préparation et exécution de la requête
		$request = DB::select(DB::expr($select))->from($table);
		foreach ($where as $w) {
			$w[2] = str_replace("?", $input, $w[2]);
			$request->or_where($w[0], $w[1], $w[2]);
		}
		$data["results"] = $request->execute()->as_array();
		
		$data["id"] = $_POST["id"];
		$data["maxResultCount"] = 10;

		return Response::forge(View::forge('fonction/autocomplete', $data));
	}

	/**
	 * Affiche une page de tous les mots permettant de compléter le début de mot "query" passé en POST.
	 * @deprecated Utilisez action_autocomplete de préférence.
	 */
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

		if ($type === "compte") {
			$data["comptes"] = Controller_Fonction::autoCompleteCompte($input);
		}

		return Response::forge(View::forge('fonction/action', $data));
	}

	/** Permet de vérifier qu'une valeur existe dans la BDD. */
	public function action_check_exist() {
		$table = $_POST["table"];
		$where = $_POST["where"];

		$results = DB::select()->from($table)->where($where[0], $where[1], $where[2])->execute()->as_array();
		return empty($results) ? "0" : "1";
	}

	public function action_add_organisme() {
		if (!Compte::checkPermission(Compte::PERM_WRITE)) {
			return new Response("you have no right to create an organisme.", 500);
		}

		/** @var string Nom du nouvelle organisme. */
		$name = $_POST["name"];

		DB::insert("organisme")->set(array("nom" => $name))->execute();

		return "1";
	}
}
