<?php

namespace Model;

use Fuel\Core\Form;
use Fuel\Core\FuelException;
use Fuel\Core\Model;

/** Représentation d'une personne dans la base de données. */
class Personne extends Model {
	private $id;
	private $nom;
	private $prenom;

	/**
	 * Créer l'objet à partir des données en paramètre.
	 */
	public function __construct(array $data) {
		$this->id = Helper::arrayGetInt("id", $data);
		$this->nom = Helper::arrayGetString("nom", $data);
		$this->prenom = Helper::arrayGetString("prenom", $data);
	}

	/**
	 * Récupère la personne correspondant à l'id.
	 * 
	 * @param int $id Identifiant de la personne.
	 * @return Personne|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM personne WHERE id=$id;");
		if ($res === null) return null;

		$obj = new Personne($res);
		return $obj;
	}

	/**
	 * Créer un <select> à partir de toutes les personnes.
	 * @deprecated Une methode javascript existe pour faire une autocomplétion meilleure.
	 * 
	 * @param string $field Valeur du "name" du select.
	 * @param string $label Nom du label du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée.
	 */
	public static function generateSelect(string $field = "personne", string $label = "Personne", $idSelected = ""): string {
		// Récupération de tous les objects
		/** @var Personne[] */
		$objects = array();
		$results = Helper::querySelect("SELECT * FROM personne;");
		foreach ($results as $result) {
			$objects[] = new Personne($result);
		}
		
		// Création des options
		$options = array();
		$options[""] = "Sélectionner";
		foreach ($objects as $obj) {
			$options[$obj->getId()] = "{$obj->getPrenom()} {$obj->getNom()}";
		}
		
		// Création du code HTML
		$html = '<div class="form-floating">';
		$html .= Form::select(
			$field,
			$idSelected,
			$options,
			array("class" => "form-select my-2")
		);
		$html .= Form::label($label, $field);
		$html .= '</div>';
		return $html;
	}

	/**
	 * Récupère l'id à partir du nom de la personne.
	 * 
	 * @param string Le nom de la personne, au format "Prénom NOM".
	 * @return int id de la personne en cas de succès.
	 * @return false Si aucune personne ne correspond au paramètre donné.
	 */
	public static function nameToId(string $name) {
		// Test du format (à compléter)
		if (strpos($name, " ") === false) {
			throw new FuelException("Le nom \"$name\" n'est pas au bon format.");
		}

		$names = explode(" ", $name);

		$res = Helper::querySelectSingle("SELECT id FROM personne WHERE prenom=\"{$names[0]}\" AND nom=\"$names[1]\"");
		if ($res === null) return false;
		return intval($res["id"]);
	}

	public function getId() { return $this->id; }
	public function getPrenom() { return $this->prenom; }
	public function getNom() { return $this->nom; }
}