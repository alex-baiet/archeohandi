<?php

namespace Model\Db;

use Fuel\Core\Model;
use Model\Helper;

/** Représentation de la table "mobilier" de la BDD. */
class Mobilier extends Model {
	private int $id;
	private string $nom;

	/** Créer l'objet à partir des données en paramètre. */
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
		$res = Helper::querySelectSingle("SELECT * FROM mobilier WHERE id=$id;");
		if ($res === null) return null;

		return new Mobilier($res);
	}

	/**
	 * Renvoie tous les mobiliers.
	 * @return Mobilier[]
	 */
	public static function fetchAll(): array {
		$results = Helper::querySelect("SELECT * FROM mobilier;");
		$objects = array();
		foreach ($results as $res) {
			$objects[] = new Mobilier($res);
		}
		return $objects;
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}