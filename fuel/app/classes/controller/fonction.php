<?php

use Fuel\Core\Controller;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Commune;
use Model\Helper;
use Model\Personne;

class Controller_Fonction extends Controller {

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

    /** @var Commune[] */
    $communes = array();
    /** @var Personne[] */
    $people = array();

    if ($type === "commune") {
      $results = Helper::querySelect("SELECT id, nom, departement FROM commune WHERE nom LIKE \"$input%\";");
      foreach ($results as $res) {
        $communes[] = new Commune($res);
      }
    }
    if ($type === "personne") {
      $results = Helper::querySelect("SELECT * FROM personne WHERE nom LIKE \"$input%\" OR prenom LIKE \"$input%\";");
      foreach ($results as $res) {
        $people[] = new Personne($res);
      }
    }

    return Response::forge(View::forge('fonction/action', array(
      "id" => $id,
      "type" => $type,
      "communes" => $communes,
      "people" => $people,
      "maxResultCount" => $maxResultCount
    )));
  }

  /** Ajoute une personne dans la BDD selon les données passé en POST. */
  public function action_add_person() {
    if (!isset($_POST["first_name"]) || !isset($_POST["last_name"])) {
      Response::redirect("/accueil");
    }

    $person = Personne::create($_POST["first_name"], $_POST["last_name"]);
    $success = $person->saveOnDB();
    if ($success) echo "1";
    else echo "0";

    // // Vérification que la personne n'existe pas déjà
    // /** @var array */
    // $results = DB::select()
    //   ->from("personne")
    //   ->where("prenom", "=", $_POST["first_name"])
    //   ->where("nom", "=", $_POST["last_name"])
    //   ->execute()
    //   ->as_array();
    // if (count($results) > 0) {
    //   echo "0";
    //   return;
    // }

    // // Préparation de l'insertion
    // $values = array(
    //   "id" => null,
    //   "prenom" => $_POST["first_name"],
    //   "nom" => $_POST["last_name"]
    // );
    // list($insertId, $rowAffected) = DB::insert("personne")
    //   ->set($values)
    //   ->execute();
    // if ($rowAffected === 1) {
    //   echo "1";
    // } else {
    //   echo "0";
    // }

  }
}
