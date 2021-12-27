<?php

namespace Model;

use Fuel\Core\Model;

/** Représente un appareil compensatoire pour un sujet handicapé. */
class Appareil extends Model {
	private int $id;
	private string $nom;

	public function __construct(array $values) {
		$this->id = Helper::arrayGetInt("id_appareil_compensatoire", $values);
		$this->nom = Helper::arrayGetString("type_appareil", $values);
	}

	/**
	 * Retourne l'appareil correspondant à l'id donné.
	 * @param int $id Identifiant.
	 * @return Appareil|null Le résultat est null si aucun appareil ne correspond à l'id donné.
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;

		$res = Helper::querySelectSingle("SELECT * FROM appareil_compensatoire WHERE id_appareil_compensatoire=$id;");
		if ($res === null) return null;
		return new Appareil($res);
	}

	/**
	 * Retourne tous les appareils.
	 * @return Appareil[]
	 */
	public static function fetchAll() {
		return Archeo::fetchAll("appareil_compensatoire", function ($data) { return new Appareil($data); });
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
}