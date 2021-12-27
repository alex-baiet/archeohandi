<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Sujethandicape extends Model {
	#region Values
	private int $id = -1;
	private string $idSujetHandicape = "";
	private int $ageMin = 0;
	private int $ageMax = 0;
	private string $sexe = "Indéterminé";
	private int $datingMin = 0;
	private int $datingMax = 0;
	private string $milieuVie = "";
	private string $contexte = "";
	private string $contexteNormatif = "";
	private string $commentaireContexte = "";
	private string $urlIllustration = "";
	private int $idTypeDepot = -1;
	private int $idSepulture = -1;
	private int $idDepot = -1;
	private int $idGroupeSujet = -1;

	/** @param Groupesujet|null|unset */
	private $group;
	/** @param Typedepot|null|unset */
	private $typeDepot;
	/** @param Typesepulture|null|unset */
	private $typeSepulture;
	/** @param Mobilier[]|unset */
	private $furnitures;
	/** @param Depot|null|unset */
	private $depot;
	#endregion

	/** Construit le Sujethandicape depuis la liste des données. */
	public function __construct(array $data) {
		$this->mergeValues($data);
	}

	/**
	 * Ajoute les données dans l'objet.
	 */
	public function mergeValues(array $data) {
		Archeo::mergeValue($this->id, $data, "id", "int");
		Archeo::mergeValue($this->idSujetHandicape, $data, "id_sujet_handicape");
		Archeo::mergeValue($this->ageMin, $data, "age_min", "int");
		Archeo::mergeValue($this->ageMax, $data, "age_max", "int");
		Archeo::mergeValue($this->sexe, $data, "sexe");
		Archeo::mergeValue($this->datingMin, $data, "dating_min", "int");
		Archeo::mergeValue($this->datingMax, $data, "dating_max", "int");
		Archeo::mergeValue($this->milieuVie, $data, "milieu_vie");
		Archeo::mergeValue($this->contexte, $data, "contexte");
		Archeo::mergeValue($this->contexteNormatif, $data, "contexte_normatif");
		Archeo::mergeValue($this->commentaireContexte, $data, "commentaire_contexte");
		Archeo::mergeValue($this->urlIllustration, $data, "url_illustration");
		Archeo::mergeValue($this->idTypeDepot, $data, "id_type_depot", "int");
		Archeo::mergeValue($this->idSepulture, $data, "id_sepulture", "int");
		Archeo::mergeValue($this->idDepot, $data, "id_depot", "int");
		Archeo::mergeValue($this->idGroupeSujet, $data, "id_groupe_sujets", "int");
	}

	/**
	 * Récupère le sujet handicapé correspondant à l'id.
	 * 
	 * @param int $id Identifiant du sujet handicapé.
	 * @return Sujethandicape|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM sujet_handicape WHERE id=$id");
		if ($res === null) return null;

		$obj = new Sujethandicape($res);
		return $obj;
	}

	public function getId() { return $this->id; }
	public function getIdSujetHandicape() { return $this->idSujetHandicape; }
	public function getAgeMin() { return $this->ageMin; }
	public function getAgeMax() { return $this->ageMax; }
	public function getSexe() { return $this->sexe; }
	public function getDatingMin() { return $this->datingMin; }
	public function getDatingMax() { return $this->datingMax; }
	public function getMilieuVie() { return $this->milieuVie; }
	public function getContexte() { return $this->contexte; }
	public function getContexteNormatif() { return $this->contexteNormatif; }
	public function getCommentaireContexte() { return $this->commentaireContexte; }
	public function getUrlIllustration() { return $this->urlIllustration; }
	public function getIdTypeDepot() { return $this->idTypeDepot; }
	public function getIdTypeSepulture() { return $this->idSepulture; }
	public function getIdDepot() { return $this->idDepot; }
	public function getIdGroupeSujet() { return $this->idGroupeSujet; }

	public function getGroup() {
		if (!isset($this->group)) $this->group = Groupesujet::fetchSingle($this->idGroupeSujet);
		return $this->group;
	}
	
	public function getTypeDepot() {
		if (!isset($this->typeDepot)) $this->typeDepot = Typedepot::fetchSingle($this->idTypeDepot);
		return $this->typeDepot;
	}
	
	public function getTypeSepulture() {
		if (!isset($this->typeSepulture)) $this->typeSepulture = Typesepulture::fetchSingle($this->idSepulture);
		return $this->typeSepulture;
	}
	
	public function getDepot() {
		if (!isset($this->depot)) $this->depot = Depot::fetchSingle($this->idDepot);
		return $this->depot;
	}
	
	/** @return Mobilier[] */
	public function getFurnitures() {
		if (!isset($this->furnitures)) {
			$results = Helper::querySelect("SELECT mob.id, mob.nom 
				FROM mobilier_archeologique AS mob 
				JOIN accessoire_sujet AS acc ON mob.id=acc.id_mobilier
				WHERE acc.id_sujet_handicape={$this->id};");
			$this->furnitures = array();

			foreach ($results as $res) {
				$this->furnitures[] = new Mobilier($res);
			}
		}
		return $this->furnitures;
	}

	public function getFurnituresId() {
		$furnitures = $this->getFurnitures();
		$ids = array();
		foreach ($furnitures as $fur) {
			$ids[] = $fur->getId();
		}
		return $ids;
	}
}
