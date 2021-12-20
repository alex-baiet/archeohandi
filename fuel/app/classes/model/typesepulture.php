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

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}