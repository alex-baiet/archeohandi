<?php

namespace Model\Db;

use Fuel\Core\Model;
use Model\Helper;

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

	public static function fetchOptions($idSelected = -1, ?string $valueEmpty = null) {
		$valueRecover = function (array $data) { return $data["id"]; };
		$textRecover = function (array $data) { return $data["nom"]; };
		return Archeo::fetchOptions("type_depot", $valueRecover, $textRecover, $idSelected, $valueEmpty);
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}