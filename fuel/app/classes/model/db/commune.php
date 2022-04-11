<?php

namespace Model\Db;

use Fuel\Core\Model;
use Model\Helper;

/**
 * Représentation de la table "commune" de la BDD.
 */
class Commune extends Model {
	private ?int $id = null;
	private ?float $x = null;
	private ?float $y = null;
	private ?float $z = null;
	private ?string $codePostal = null;
	private ?string $nom = null;
	private ?string $departement = null;
	private ?string $region = null;
	private ?string $pays = null;
	private ?float $superficie = null;
	private ?int $population = null;
	private string $insee = "";

	public function __construct(array $values) {
		Archeo::mergeValue($this->id, $values, "id", Archeo::MERGE_STRING, false);
		Archeo::mergeValue($this->x, $values, "x", Archeo::MERGE_FLOAT, false);
		Archeo::mergeValue($this->y, $values, "y", Archeo::MERGE_FLOAT, false);
		Archeo::mergeValue($this->z, $values, "z", Archeo::MERGE_FLOAT, false);
		Archeo::mergeValue($this->codePostal, $values, "code_postal", Archeo::MERGE_STRING, true);
		Archeo::mergeValue($this->nom, $values, "nom", Archeo::MERGE_STRING, true);
		Archeo::mergeValue($this->departement, $values, "departement", Archeo::MERGE_STRING, true);
		Archeo::mergeValue($this->region, $values, "region", Archeo::MERGE_STRING, true);
		Archeo::mergeValue($this->pays, $values, "pays", Archeo::MERGE_STRING, true);
		Archeo::mergeValue($this->superficie, $values, "superficie", Archeo::MERGE_FLOAT, true);
		Archeo::mergeValue($this->population, $values, "population", Archeo::MERGE_INT, true);
		Archeo::mergeValue($this->insee, $values, "insee", Archeo::MERGE_STRING, true);
	}

	/** Retourne la commune correspondant à l'id donné. */
	public static function fetchSingle(?int $id): ?Commune {
		return Archeo::fetchSingle($id, "commune", function ($data) { return new Commune($data); });
	}

	/**
	 * Récupère l'id de la commune à partie du nom donné.
	 * 
	 * @param string $name Nom de la commune au format "Nom-de-la-Commune, Département"
	 * @return int Identifiant de la commune
	 * @return null En cas d'échec.
	 */
	public static function nameToId(string $name): ?int {
		// Vérification du format du nom
		if (empty($name) || $name === ", ") return null;
		if (strpos($name, ", ") === false) return null;

		// Récupération de l'id
		$names = explode(", ", $name);
		$res = Helper::querySelectSingle("SELECT id FROM commune WHERE nom=\"{$names[0]}\" AND departement=\"{$names[1]}\"");

		if ($res === null || count($res) === 0) return null;
		return $res["id"];
	}

	public function getId() { return $this->id; }
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
	public function getZ() { return $this->z; }
	public function getCodePostal() { return $this->codePostal; }
	public function getNom() { return $this->nom; }
	public function getDepartement() { return $this->departement; }
	public function getRegion() { return $this->region; }
	public function getPays() { return $this->pays; }
	public function getSuperficie() { return $this->superficie; }
	public function getPopulation() { return $this->population; }
	public function getInsee() { return $this->insee; }

	public function fullName(): string { return "{$this->nom}, {$this->departement}"; }
}