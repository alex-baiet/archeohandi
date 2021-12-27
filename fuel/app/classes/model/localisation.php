<?php

namespace Model;

use Fuel\Core\Model;

/** Représente une partie du corps. */
class Localisation extends Model {
	private int $id;
	private string $nom;
	private string $urlImg;

	public function __construct(array $values) {
		$this->id = Helper::arrayGetInt("id", $values);
		$this->nom = Helper::arrayGetString("nom", $values);
		$this->urlImg = Helper::arrayGetString("url_img", $values);
	}

	/**
	 * Retourne la localisation correspondant à l'id donné.
	 * @param int $id Identifiant.
	 * @return Localisation|null Le résultat est null si aucune localisation ne correspond à l'id donné.
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;

		$res = Helper::querySelectSingle("SELECT * FROM localisation_atteinte WHERE id=$id;");
		if ($res === null) return null;
		return new Localisation($res);
	}

	/**
	 * Retourne toutes les localisations.
	 * @return Localisation[]
	 */
	public static function fetchAll() {
		return Archeo::fetchAll("localisation_atteinte", function ($data) { return new Localisation($data); });
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
	public function getUrlImg() { return $this->urlImg; }
}