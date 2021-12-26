<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'un type de sepulture dans la base de données. */
class Typesepulture extends Model {
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
	 * Récupère le type de sepulture correspondant à l'id.
	 * 
	 * @param int $id Identifiant du type de sepulture.
	 * @return Typesepulture|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM type_sepulture WHERE id=$id;");
		if ($res === null) return null;

		$obj = new Typesepulture($res);
		return $obj;
	}

	/**
	 * Créer un <select> à partir de tous les types de sepulture.
	 * 
	 * @param string $field Valeur du "name" du select.
	 * @param string $label Nom du label du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée.
	 */
	public static function generateSelect(string $field = "type_sepulture", string $label = "Type de sepulture", $idSelected = ""): string {
		$valueRecover = function (array $data) { return $data["id"]; };
		$textRecover = function (array $data) { return $data["nom"]; };
		return Archeo::generateSelect($field, $label, $idSelected, "type_sepulture", $valueRecover, $textRecover);
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}