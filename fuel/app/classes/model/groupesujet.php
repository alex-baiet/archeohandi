<?php

namespace Model;

use Fuel\Core\FuelException;
use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Groupesujet extends Model {
	#region Values
	private ?int $id = null;
	private int $idChronology = -1;
	private int $idOperation = -1;
	private int $nmi = 0;

	/** @var Chronology|null|unset */
	private $chronology;
	#endregion

	/** Construit le GroupeSujet depuis la liste des données. */
	public function __construct(array $data) {
		Archeo::mergeValue($this->id, $data, "id", "int");
		Archeo::mergeValue($this->idChronology, $data, "id_chronology", "int");
		Archeo::mergeValue($this->idOperation, $data, "id_operation", "int");
		Archeo::mergeValue($this->nmi, $data, "NMI", "int");

		if (isset($data["chronology"])) $this->idChronology = Chronology::nameToId($data["chronology"]);
	}

	/**
	 * Récupère le groupe correspondant à l'id.
	 * @param int $id Identifiant du groupe.
	 */
	public static function fetchSingle(int $id): ?Groupesujet {
		return Archeo::fetchSingle($id, "groupe_sujets", function ($data) { return new Groupesujet($data); });
	}

	public function getId() { return $this->id; }
	public function getIdChronology() { return $this->idChronology; }
	public function getIdOperation() { return $this->idOperation; }
	public function getNMI() { return $this->nmi; }

	public function getChronology() {
		if (!isset($this->chronology)) $this->chronology = Chronology::fetchSingle($this->idChronology);
		return $this->chronology;
	}

}
