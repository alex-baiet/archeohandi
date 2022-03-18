<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Sujethandicape;
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

	/** Permet de créer un depot propre a chaque sujet pour tous les sujets ayant un depot en commun avec un autre sujet. */
	public function action_split_sujet_depot() {
		if (!$this->checkPermission()) return;

		$data = array();

		// Récupération des sujets ayant un depot en commun avec au moins un autre sujet
		$results = $query = DB::query("SELECT *
			FROM sujet_handicape AS original
			WHERE EXISTS(
				SELECT id
				FROM sujet_handicape AS copy
				WHERE copy.id_depot = original.id_depot
				AND copy.id != original.id
			);"
		)->execute()->as_array();

		foreach ($results as $res) {
			$subject = new Sujethandicape($res);

			// Récupération du depot
			$depot = $subject->getDepot();
			
			// Sauvegarde en tant que nouveau depot
			$depot->saveOnDB(true);
			
			// Ajout du nouvel id de depot au sujet
			$subject->setDepot($depot);
			echo $depot->getId()."<br>";
			echo $subject->getDepot()."<br>";

			// Sauvegarde du sujet
			echo "nbr lignes modifiées : ".DB::query("UPDATE sujet_handicape SET id_depot={$depot->getId()} WHERE id={$subject->getId()}")->execute()."<br>";
			// $subject->saveOnDB();
		}
		
		$this->template->title = 'Import CSV | Résultats';
		$this->template->content = '';
	}

	/** Permet de créer un depot propre a chaque sujet pour tous les sujets ayant un depot en commun avec un autre sujet. */
	public function action_split_sujet_groupe() {
		if (!$this->checkPermission()) return;

		$data = array();

		// Récupération des sujets ayant un depot en commun avec au moins un autre sujet
		$results = $query = DB::query("SELECT *
			FROM sujet_handicape AS original
			WHERE EXISTS(
				SELECT id
				FROM sujet_handicape AS copy
				WHERE copy.id_groupe_sujets = original.id_groupe_sujets
				AND copy.id != original.id
			);"
		)->execute()->as_array();

		foreach ($results as $res) {
			$subject = new Sujethandicape($res);

			// Récupération du depot
			$group = $subject->getGroup();
			
			// Sauvegarde en tant que nouveau depot
			$group->saveOnDB(true);
			
			// Ajout du nouvel id de depot au sujet
			echo $group->getId()."<br>";

			// Sauvegarde du sujet
			echo "nbr lignes modifiées : ".DB::query("UPDATE sujet_handicape SET id_groupe_sujets={$group->getId()} WHERE id={$subject->getId()}")->execute()."<br>";
			// $subject->saveOnDB();
		}
		
		$this->template->title = 'Import CSV | Résultats';
		$this->template->content = '';
	}

	public function action_add_insee_1() {
		if (!$this->checkPermission()) return;

		$iinsee = 0;
		$iname = 13;

		$data = array();

		if (isset($_FILES["file"]) && $_FILES["file"]["error"] === 0) {
			// Récupération du contenu fichier passé en POST
			$file = file_get_contents($_FILES["file"]["tmp_name"]);

			$results = array();

			$lines = explode("\n", $file);

			foreach ($lines as $line) {
				$columns = explode(";", $line);
				$insee = $columns[$iinsee];
				$name = $columns[$iname];

				// DB::select()->from("commune")->where("nom", "=", $name)->where("insee" =>)

				$res = DB::update("commune")->set(array("insee" => $insee))->where("nom", "=", $name)->where("insee", "=", 0)->execute();
				// if ($res > 0) {
				// 	$results[$name] = "#0f08";
				// } else {
				// 	$results[$name] = "#f008";
				// }
			}

			$data["results"] = $results;
			$data["file"] = "";
		}

		$this->template->title = 'Import CSV | Résultats';
		$this->template->content = View::forge('script/add_insee', $data, false);
	}

	private const I_INSEE = 0;
	private const I_NAME = 13;

	public function action_add_insee_2() {
		if (!$this->checkPermission()) return;

		$data = array();

		if (isset($_FILES["file"]) && $_FILES["file"]["error"] === 0) {
			// Récupération du contenu fichier passé en POST
			$file = file_get_contents($_FILES["file"]["tmp_name"]);
			// Lignes non traités (on recupère tout pour éviter des aller-retour nombreux et coûteux)
			$towns = DB::select()->from("commune")->where("insee", "=", "0")->or_where("insee", "=", "2")->execute()->as_array();

			$lines = explode("\n", $file);
			$columns = array();
			foreach ($lines as $line) {
				$columns[] = explode(";", $line);
			}

			$countReplaced = 0;
			$i = -1;
			// Début remplacement
			foreach ($towns as $town) {
				$i++;
				$column = $this->findLine($town["nom"], $columns);
				if ($column != null) {
					$countReplaced++;
					$insee = $column[Controller_Script::I_INSEE];

					$res = DB::update("commune")->set(array("insee" => $insee))->where("nom", "=", $town["nom"])->execute();
				}
			}
			echo "Nombre total de commune à changer : $i<br>";
			echo "Nombre total de remplacement : $countReplaced<br>";
		}

		$this->template->title = 'Import CSV | Résultats';
		$this->template->content = View::forge('script/add_insee', $data, false);
	}

	/** Retourne la colonne correspondant. */
	public function findLine(string $townName, array &$columns): ?array {
		$searchName = $townName;
		$searchName = Helper::replaceAccent($searchName);
		$searchName = str_replace("ŒŒ", "Oe", $searchName);
		$searchName = str_replace("œœ", "oe", $searchName);
		$search2 = str_replace("-", " ", $searchName);
		foreach ($columns as $col) {
			try {
				$name = $col[Controller_Script::I_NAME];
				if ($searchName === $name
					|| "Les $searchName" === $name
					|| "La $searchName" === $name
					|| "Le $searchName" === $name
					|| "{$searchName}s" === $name
					|| $search2 === $name
				) return $col;
			}
			catch (Exception $e) { }
		}

		return null;
	}
}