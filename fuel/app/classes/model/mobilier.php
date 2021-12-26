<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'un mobilier dans la base de données. */
class Mobilier extends Model {
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
	 * Récupère le mobilier correspondant à l'id.
	 * @param int $id Identifiant.
	 * @return Mobilier|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM mobilier_archeologique WHERE id=$id;");
		if ($res === null) return null;

		return new Mobilier($res);
	}

	/**
	 * Renvoie tous les mobiliers.
	 * @return Mobilier[]
	 */
	public static function fetchAll(): array {
		$results = Helper::querySelect("SELECT * FROM mobilier_archeologique;");
		$objects = array();
		foreach ($results as $res) {
			$objects[] = new Mobilier($res);
		}
		return $objects;
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}