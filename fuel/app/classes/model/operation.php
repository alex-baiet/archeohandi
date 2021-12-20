<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Operation extends Model {
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

	/** Construit l'Operation depuis la liste des données. */
	public function __construct(array $operation) {
		$this->idSite = $operation["id_site"];
		$this->idUser = $operation["id_user"];
		$this->nomOp = $operation["nom_op"];
		$this->aRevoir = $operation["a_revoir"];
		$this->annee = $operation["annee"];
		$this->idCommune = $operation["id_commune"];
		$this->adresse = $operation["adresse"];
		$this->x = $operation["X"];
		$this->y = $operation["Y"];
		$this->idOrganisme = $operation["id_organisme"];
		$this->idTypeOp = $operation["id_type_op"];
		$this->EA = $operation["EA"];
		$this->OA = $operation["OA"];
		$this->patriarche = $operation["patriarche"];
		$this->numeroOperation = $operation["numero_operation"];
		$this->arretePrescription = $operation["arrete_prescription"];
		$this->responsableOp = $operation["responsable_op"];
		$this->anthropologue = $operation["anthropologue"];
		$this->paleopathologiste = $operation["paleopathologiste"];
		$this->bibliographie = $operation["bibliographie"];
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

		$operation = new Operation($res);
		return $operation;
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
}
