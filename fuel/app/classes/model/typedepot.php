<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'un type de dépôt dans la base de données. */
class Typedepot extends Model {
	private int $id;
	private string $nom;

	/**
	 * Créer l'objet à partir des données en paramètre.
	 */
	public function __construct(array $data) {
		$this->id = $data["id"];
		$this->nom = $data["nom"];
	}

	/**
	 * Récupère le type de dépôt correspondant à l'id.
	 * 
	 * @param int $id Identifiant du type de dépôt.
	 * @return Typedepot|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM type_depot WHERE id=$id;");
		if ($res === null) return null;

		return new Typedepot($res);
	}

	/**
	 * Créer un <select> à partir de tous les types de dépôt.
	 * 
	 * @param string $field Valeur du "name" du select.
	 * @param string $label Nom du label du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée.
	 */
	public static function generateSelect(string $field = "id_type_depot", string $label = "Type de dépôt", int $idSelected = 4): string {
		$valueRecover = function (array $data) { return $data["id"]; };
		$textRecover = function (array $data) { return $data["nom"]; };
		return Archeo::generateSelect($field, $label, $idSelected, "type_depot", $valueRecover, $textRecover);
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}