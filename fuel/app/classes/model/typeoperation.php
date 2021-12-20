<?php

namespace Model;

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

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}