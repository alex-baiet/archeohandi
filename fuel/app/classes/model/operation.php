<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Operation extends Model {
	#region Values
	private $idSite;
	private $idUser;
	private $nomOp;
	private $aRevoir;
	private $annee;
	private $idCommune;
	private $adresse;
	private $x;
	private $y;
	private $idOrganisme;
	private $idTypeOp;
	private $EA;
	private $OA;
	private $patriarche;
	private $numeroOperation;
	private $arretePrescription;
	private $responsableOp;
	private $anthropologue;
	private $paleopathologiste;
	private $bibliographie;

	/** @var Commune|undefined */
	private $commune;
	/** @var Typeoperation|undefined */
	private $typeOp;
	/** @var Organisme|undefined */
	private $organisme;
	#endregion

	/** Construit l'Operation depuis la liste des données. */
	public function __construct(array $data) {
		$this->idSite = $data["id_site"];
		$this->idUser = $data["id_user"];
		$this->nomOp = $data["nom_op"];
		$this->aRevoir = $data["a_revoir"];
		$this->annee = $data["annee"];
		$this->idCommune = $data["id_commune"];
		$this->adresse = $data["adresse"];
		$this->x = $data["X"];
		$this->y = $data["Y"];
		$this->idOrganisme = $data["id_organisme"];
		$this->idTypeOp = $data["id_type_op"];
		$this->EA = $data["EA"];
		$this->OA = $data["OA"];
		$this->patriarche = $data["patriarche"];
		$this->numeroOperation = $data["numero_operation"];
		$this->arretePrescription = $data["arrete_prescription"];
		$this->responsableOp = $data["responsable_op"];
		$this->anthropologue = $data["anthropologue"];
		$this->paleopathologiste = $data["paleopathologiste"];
		$this->bibliographie = $data["bibliographie"];
	}

	/**
	 * Récupère l'opération correspondant à l'id.
	 * 
	 * @param int $id Identifiant de l'opération.
	 * @return Operation|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM operations WHERE id_site=$id");
		if ($res === null) return null;

		$obj = new Operation($res);
		return $obj;
	}

	public function getIdSite() { return $this->idSite; }
	public function getIdUser() { return $this->idUser; }
	public function getNomOp() { return $this->nomOp; }
	public function getARevoir() { return $this->aRevoir; }
	public function getAnnee() { return $this->annee; }
	public function getIdCommune() { return $this->idCommune; }
	public function getAdresse() { return $this->adresse; }
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
	public function getIdOrganisme() { return $this->idOrganisme; }
	public function getIdTypeOp() { return $this->idTypeOp; }
	public function getEA() { return $this->EA; }
	public function getOA() { return $this->OA; }
	public function getPatriarche() { return $this->patriarche; }
	public function getNumeroOperation() { return $this->numeroOperation; }
	public function getArretePrescription() { return $this->arretePrescription; }
	public function getResponsableOp() { return $this->responsableOp; }
	public function getAnthropologue() { return $this->anthropologue; }
	public function getPaleopathologiste() { return $this->paleopathologiste; }
	public function getBibliographie() { return $this->bibliographie; }
	
	/**
	 * @return Commune|null
	 */
	public function getCommune() {
		if (!isset($this->commune)) $this->commune = Commune::fetchSingle($this->idCommune);
		return $this->commune;
	}
	/**
	 * @return Typeoperation|null
	 */
	public function getTypeOperation() {
		if (!isset($this->typeOp)) $this->typeOp = Typeoperation::fetchSingle($this->idTypeOp);
		return $this->typeOp;
	}
	/**
	 * @return Organisme|null
	 */
	public function getOrganisme() {
		if (!isset($this->organisme)) $this->organisme = Organisme::fetchSingle($this->idOrganisme);
		return $this->organisme;
	}
	
}
