<?php

use Fuel\Core\Controller;
use Fuel\Core\DB;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;

class Controller_Fonction extends Controller {

	/**
	 * Affiche une page de tous les mots permettant de compléter le début de mot passé en POST.
	 * "select" Expression de selection.
	 * "table" Table cible de recherche.
	 * "where" array de condition aux format ["champs", "=", "input", "and"].
	 */
	public function action_autocomplete() {
		$data = array();

		$table = $_POST["table"];
		$select = $_POST["select"];
		$where = $_POST["where"];

		// Préparation et exécution de la requête
		$request = DB::select(DB::expr($select))->from($table);
		foreach ($where as $w) {
			if (isset($w[3]) && $w[3] === "or") $request->or_where($w[0], $w[1], $w[2]);
			else $request->and_where($w[0], $w[1], $w[2]);
		}
		$data["results"] = $request->execute()->as_array();
		
		$data["id"] = $_POST["id"];
		$data["maxResultCount"] = 100;

		return Response::forge(View::forge('fonction/autocomplete', $data));
	}

	/** Permet de vérifier qu'une valeur existe dans la BDD. */
	public function action_check_exist() {
		$table = $_POST["table"];
		$where = $_POST["where"];

		// Préparation et exécution de la requête
		$query = DB::select()->from($table);
		foreach ($where as $w) {
			if (isset($w[3]) && $w[3] === "or") $query->or_where($w[0], $w[1], $w[2]);
			else $query->and_where($w[0], $w[1], $w[2]);
		}
		$results = $query->execute()->as_array();

		return empty($results) ? "0" : "1";
	}

	/** Ajoute dans la BDD le nom de l'organisme contenu dans $_POST["name"]. */
	public function action_add_organisme() {
		if (!Compte::checkPermission(Compte::PERM_WRITE)) {
			return new Response("you have no right to create an organisme.", 500);
		}

		/** @var string Nom du nouvelle organisme. */
		$name = $_POST["name"];

		DB::insert("organisme")->set(array("nom" => trim($name)))->execute();

		return "1";
	}

	/** Permet de faire une requête SQL libre à partir de $_POST["query"] et d'afficher le résultat sous forme de JSON. */
	public function action_query() {
		if (!Compte::checkPermission(Compte::PERM_WRITE)) {
			return new Response("You need to have an account.", 500);
		}
		if (!isset($_POST["query"])) {
			return new Response("No query has been passed in post.", 501);
		}
		$result = DB::query($_POST["query"])->execute()->as_array();
		echo json_encode($result);
	}
}
