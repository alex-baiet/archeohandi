<?php

namespace Model;

use Fuel\Core\Form;
use Fuel\Core\Model;

/** Représentation d'un type d'opération dans la base de données. */
class Typeoperation extends Model {
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
	 * Récupère le type d'opération correspondant à l'id.
	 * 
	 * @param int $id Identifiant du type de l'opération.
	 * @return Typeoperation|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM type_operation WHERE id=$id;");
		if ($res === null) return null;

		$obj = new Typeoperation($res);
		return $obj;
	}

	/**
	 * Créer un <select> à partir de tous les types d'opération.
	 * 
	 * @param string $field Valeur du "name" du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée.
	 */
	public static function generateSelect(string $field = "organisme", $idSelected = ""): string {
		// Récupération de tous les type d'opérations
		/** @var Typeoperation[] */
		$types = array();
		$results = Helper::querySelect("SELECT * FROM type_operation;");
		foreach ($results as $result) {
			$types[] = new Typeoperation($result);
		}
		
		// Création des options
		$options = array();
		$options[""] = "Sélectionner";
		foreach ($types as $typeOp) {
			$options[$typeOp->getId()] = $typeOp->getNom();
		}
		
		// Création du code HTML
		$html = '<div class="form-floating">';
		$html .= Form::select(
			$field,
			$idSelected,
			$options,
			array("class" => "form-select my-4")
		);
		$html .= Form::label("Type d'opération", $field);
		$html .= '</div>';
		return $html;
	}
	
	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}