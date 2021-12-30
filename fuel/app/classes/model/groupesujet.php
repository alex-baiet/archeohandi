<?php

namespace Model;

use Fuel\Core\DB;
use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Groupesujet extends Model {
	#region Values
	private ?int $id = null;
	private ?int $idChronology = null;
	private ?int $idOperation = null;
	private int $nmi = 0;

	/** @var Chronology|null|unset */
	private ?Chronology $chronology;
	/** @var Operation|null|unset */
	private ?Operation $operation;

	private Validation $validation;
	#endregion

	/** Construit le GroupeSujet depuis la liste des données. */
	public function __construct(array $data) {
		$this->validation = new Validation();

		Archeo::mergeValue($this->id, $data, "id", "int");
		Archeo::mergeValue($this->idChronology, $data, "id_chronologie", "int");
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

	#region Getters
	public function getId() { return $this->id; }
	public function getIdChronology() { return $this->idChronology; }
	public function getIdOperation() { return $this->idOperation; }
	public function getNMI() { return $this->nmi; }

	public function getChronology() {
		if (!isset($this->chronology)) {
			if ($this->idChronology === null) $this->chronology = null;
			else $this->chronology = Chronology::fetchSingle($this->idChronology);
		}
		return $this->chronology;
	}

	public function getOperation(): ?Operation {
		if (!isset($this->operation)) $this->operation = Operation::fetchSingle($this->idOperation);
		return $this->operation;
	}

	public function toArray(): array {
		return array(
			"id" => $this->id,
			"id_chronologie" => $this->idChronology,
			"id_operation" => $this->idOperation,
			"NMI" => $this->nmi,
		);
	}
	#endregion

	public function saveOnDB(): bool {
		if (!$this->validate()) return false;

		// Préparation des valeurs à envoyer à la BDD
		$arr = $this->toArray();

		if ($this->id === null || Groupesujet::fetchSingle($this->id) === null) {
			// Le groupe n'existe pas : on la rajoute à la BDD
			list($insertId, $rowAffected) = DB::insert("groupe_sujets")
				->set($arr)
				->execute();
			if ($rowAffected < 1) {
				$this->validation->invalidate("Une erreur inconnu est survenu lors de l'ajout du groupe du sujet.");
				return false;
			}
			$this->id = $insertId;
		}
		else {
			// Le groupe existe : on met à jour
			$rowAffected = DB::update("groupe_sujets")
				->set($arr)
				->where("id", "=", $this->id)
				->execute();
			if ($rowAffected < 1) {
				$this->validation->invalidate("Une erreur inconnu est survenu lors de la mise à jour des données du groupe.");
				return false;
			}
		}

		return true;
	}

	public function validate() {
		return $this->validation->validate(function () {
			$validation = $this->validation;
			if (Chronology::fetchSingle($this->idChronology) === null) $validation->invalidate("La chronologie n'est pas valide.");
			if (Operation::fetchSingle($this->idOperation) === null) $validation->invalidate("L'opération n'est pas valide.");
			if ($this->nmi < 0) $validation->invalidate("La valeur de NMI doit être positive.");
		});
	}

	public function echoErrors() { $this->validation->echoErrors(); }

	#endregion

}
