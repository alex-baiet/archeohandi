<?php

namespace Model\Db;

use Fuel\Core\Model;
use Model\Helper;

/**
 * Représentation de la table "commune" de la BDD.
 */
class Commune extends Model {
	private $id;
	private $x;
	private $y;
	private $z;
	private $codePostal;
	private $nom;
	private $departement;
	private $region;
	private $pays;
	private $superficie;
	private $population;
	private string $insee = "";

	public function __construct(array $values) {
		$this->id = Helper::arrayGetString("id", $values);
		$this->x = Helper::arrayGetString("x", $values);
		$this->y = Helper::arrayGetString("y", $values);
		$this->z = Helper::arrayGetString("z", $values);
		$this->codePostal = Helper::arrayGetString("code_postal", $values);
		$this->nom = Helper::arrayGetString("nom", $values);
		$this->departement = Helper::arrayGetString("departement", $values);
		$this->region = Helper::arrayGetString("region", $values);
		$this->pays = Helper::arrayGetString("pays", $values);
		$this->superficie = Helper::arrayGetString("superficie", $values);
		$this->population = Helper::arrayGetString("population", $values);
		Archeo::mergeValue($this->insee, $values, "insee");
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