<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Sujethandicape extends Model {
	#region Values
	private $id;
	private $idSujetHandicape;
	private $ageMin;
	private $ageMax;
	private $sexe;
	private $datation;
	private $datationEcartType;
	private $milieuVie;
	private $contexte;
	private $contexteNormatif;
	private $commentaireContexte;
	private $urlIllustration;
	private $idTypeDepot;
	private $idSepulture;
	private $idDepot;
	private $idGroupeSujet;
	#endregion

	/** Construit le Sujethandicape depuis la liste des données. */
	public function __construct(array $data) {
		$this->id = $data["id"];
		$this->idSujetHandicape = $data["id_sujet_handicape"];
		$this->ageMin = $data["age_min"];
		$this->ageMax = $data["age_max"];
		$this->sexe = $data["sexe"];
		$this->datation = $data["datation"];
		$this->datationEcartType = $data["datation_ecart_type"];
		$this->milieuVie = $data["milieu_vie"];
		$this->contexte = $data["contexte"];
		$this->contexteNormatif = $data["contexte_normatif"];
		$this->commentaireContexte = $data["commentaire_contexte"];
		$this->urlIllustration = $data["url_illustration"];
		$this->idTypeDepot = $data["id_type_depot"];
		$this->idSepulture = $data["id_sepulture"];
		$this->idDepot = $data["id_depot"];
		$this->idGroupeSujet = $data["id_groupe_sujets"];
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
	public function getDatation() { return $this->datation; }
	public function getDatationEcartType() { return $this->datationEcartType; }
	public function getMilieuVie() { return $this->milieuVie; }
	public function getContexte() { return $this->contexte; }
	public function getContexteNormatif() { return $this->contexteNormatif; }
	public function getCommentaireContexte() { return $this->commentaireContexte; }
	public function getUrlIllustration() { return $this->urlIllustration; }
	public function getIdTypeDepot() { return $this->idTypeDepot; }
	public function getIdSepulture() { return $this->idSepulture; }
	public function getIdDepot() { return $this->idDepot; }
	public function getIdGroupeSujet() { return $this->idGroupeSujet; }
}
