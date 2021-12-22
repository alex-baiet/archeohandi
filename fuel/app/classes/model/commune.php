<?php

namespace Model;

use Fuel\Core\Model;

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
	}

	/**
	 * Retourne la commune correspondant à l'id donné.
	 * 
	 * @param $id Identifiant de la commune.
	 * @return Commune|null Le résultat est null si aucune commune ne correspond à l'id donné.
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;

		$res = Helper::querySelectSingle("SELECT * FROM commune WHERE id=$id;");
		if ($res === null) return null;
		return new Commune($res);
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
}