<?php

namespace Model;

use Fuel\Core\Form;
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

	public function getId() { return $this->id; }
	public function getPrenom() { return $this->prenom; }
	public function getNom() { return $this->nom; }
}