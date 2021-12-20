<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Groupesujet extends Model {
	#region Values
	private $id;
	private $idChronologie;
	private $idOperation;
	private $nmi;
	#endregion

	/** Construit le GroupeSujet depuis la liste des données. */
	public function __construct(array $data) {
		$this->id = $data["id_groupe_sujets"];
		$this->idChronologie = $data["id_chronologie"];
		$this->idOperation = $data["id_operation"];
		$this->nmi = $data["NMI"];
	}

	/**
	 * Récupère le groupe correspondant à l'id.
	 * 
	 * @param int $id Identifiant du groupe.
	 * @return Groupesujet|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM groupe_sujets WHERE id_groupe_sujets=$id;");
		if ($res === null) return null;

		$obj = new Groupesujet($res);
		return $obj;
	}

	public function getId() { return $this->id; }
	public function getIdChronologie() { return $this->idChronologie; }
	public function getIdOperation() { return $this->idOperation; }
	public function getNMI() { return $this->nmi; }
}
