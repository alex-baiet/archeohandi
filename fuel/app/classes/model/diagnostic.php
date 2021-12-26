<?php

namespace Model;

use Fuel\Core\Model;

class Diagnostic extends Model {
	private int $id;
	private string $nom;

	public function __construct(array $values) {
		$this->id = Helper::arrayGetInt("id", $values);
		$this->nom = Helper::arrayGetString("nom", $values);
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
	public static function fetchAll(): array {
		return Archeo::fetchAll("diagnostic", function ($data) { return new Diagnostic($data); });
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}