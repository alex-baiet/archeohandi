<?php

namespace Model;

use Fuel\Core\Model;

/** Représente un appareil compensatoire pour un sujet handicapé. */
class Appareil extends Model {
	private int $id;
	private string $name;

	public function __construct(array $values) {
		$this->id = Helper::arrayGetInt("id", $values);
		$this->name = Helper::arrayGetString("nom", $values);
	}

	/**
	 * Retourne l'appareil correspondant à l'id donné.
	 * @param int $id Identifiant.
	 * @return Appareil|null Le résultat est null si aucun appareil ne correspond à l'id donné.
	 */
	public static function fetchSingle(int $id) {
		return Archeo::fetchSingle($id, "appareil_compensatoire", function ($data) { return new Appareil($data); });
	}

	/**
	 * Retourne tous les appareils.
	 * @return Appareil[]
	 */
	public static function fetchAll() {
		return Archeo::fetchAll("appareil_compensatoire", function ($data) { return new Appareil($data); });
	}

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
}