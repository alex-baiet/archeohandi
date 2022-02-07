<?php

namespace Model\Db;

use Fuel\Core\Model;
use Model\Helper;

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

	public static function fetchOptions($idSelected = -1): string {
		$valueRecover = function ($data) { return $data["id"]; };
		$textRecover = function ($data) { return $data["nom"]; };
		return Archeo::fetchOptions("organisme", $valueRecover, $textRecover, $idSelected, false);
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}