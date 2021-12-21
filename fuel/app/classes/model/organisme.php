<?php

namespace Model;

use Fuel\Core\Form;
use Fuel\Core\Model;

/** Représentation d'un organisme dans la base de données. */
class Organisme extends Model {
	private $id;
	private $nom;

	/**
	 * Créer l'objet à partir des données en paramètre.
	 */
	public function __construct(array $data) {
		$this->id = $data["id"];
		$this->nom = $data["nom"];
	}

	/**
	 * Récupère l'organisme correspondant à l'id.
	 * 
	 * @param int $id Identifiant de l'organisme.
	 * @return Organisme|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM organisme WHERE id=$id;");
		if ($res === null) return null;

		$obj = new Organisme($res);
		return $obj;
	}

	/** Créer un <select> à partir de tous les organismes. */
	public static function generateSelect($field = "organisme", $idSelectedOrga = "") {
		// Récupération de tous les organismes
		/** @var Organisme[] */
		$organismes = array();
		$results = Helper::querySelect("SELECT * FROM organisme;");
		foreach ($results as $result) {
			$organismes[] = new Organisme($result);
		}
		
		// Création des options
		$options = array();
		$options[""] = "Sélectionner";
		foreach ($organismes as $organisme) {
			$options[$organisme->getId()] = $organisme->getNom();
		}
		
		// Création du code HTML
		$html = '<div class="form-floating">';
		$html .= Form::select(
			$field,
			$idSelectedOrga,
			$options,
			array("class" => "form-select my-4")
		);
		$html .= Form::label('Organisme', $field);
		$html .= '</div>';
		return $html;
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}