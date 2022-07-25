<?php

namespace Model\Db;

use Fuel\Core\Model;
use Model\Helper;

/** Représentation de la table "diagnostic" de la BDD. */
class Diagnostic extends Model {
	private int $id;
	private string $nom;

	/** @var array|unset Liste des zones pouvant êtres touchés par le diagnostic au format id_localisation => est_obligatoire */
	private array $spots;

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
	
	/** Récupère tous les localisations possibles. */
	private function getSpots() {
		if (!isset($this->spots)) {
			$results = Helper::querySelect(
				"SELECT *
				FROM diagnostic_zone
				WHERE id_diagnostic = {$this->id};");
			
			$this->spots = array();
			foreach ($results as $res) {
				$this->spots[intval($res["id_localisation"])] = intval($res["obligatoire"]);
			}
		}
		return $this->spots;
	}

	/** True si le diagnostic est à la position indiqué. */
	public function isLocated(int $idSpot): bool {
		return isset($this->getSpots()[$idSpot]);
	}

	/** True si le diagnostic touche obligatoirement la position, quel que soit le sujet. */
	public function isSpotMandatory(int $idSpot): bool {
		if (!$this->isLocated($idSpot)) return false;
		return $this->getSpots()[$idSpot] === 1;
	}

}