<?php

namespace Model;

use Fuel\Core\Model;

class Diagnostic extends Model {
	private int $id;
	private string $nom;
	private string $name;

	public function __construct(array $values) {
		$this->id = Helper::arrayGetInt("id", $values);
		$this->nom = Helper::arrayGetString("nom", $values);
		$this->name = Helper::arrayGetString("name", $values);
	}

	/**
	 * Retourne le diagnostic correspondant à l'id donné.
	 * @param int $id Identifiant du diagnostic.
	 * @return Diagnostic|null Le résultat est null si aucun diagnostic ne correspond à l'id donné.
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;

		$res = Helper::querySelectSingle("SELECT * FROM diagnostic WHERE id=$id;");
		if ($res === null) return null;
		return new Diagnostic($res);
	}

	/**
	 * Retourne tous les diagnostics.
	 * @return Diagnostic[]
	 */
	public static function fetchAll() {
		$results = Helper::querySelect("SELECT * FROM diagnostic;");
		$objects = array();

		foreach ($results as $res) {
			$objects[] = new Diagnostic($res);
		}
		return $objects;
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
	public function getName() { return $this->name; }
}