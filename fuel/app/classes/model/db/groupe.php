<?php

namespace Model\Db;

use Fuel\Core\DB;
use Fuel\Core\Model;
use Model\Validation;

/** Représentation de la table "groupe" de la BDD. */
class Groupe extends Model {
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
		Archeo::mergeValue($this->nmi, $data, "nmi", "int");

		if (isset($data["chronologie"])) $this->idChronology = Chronology::nameToId($data["chronologie"]);
	}

	/**
	 * Récupère le groupe correspondant à l'id.
	 * @param int $id Identifiant du groupe.
	 */
	public static function fetchSingle(int $id): ?Groupe {
		return Archeo::fetchSingle($id, "groupe", function ($data) { return new Groupe($data); });
	}

	/** @return Groupe[] */
	public static function fetchAll(): array {
		return Archeo::fetchAll("groupe", function ($data) { return new Groupe($data); });
	}

	/**
	 * Supprime le groupe indiqué de la BDD.
	 * @return string Message d'erreur en cas de problème.
	 * @return null Si tout s'est bien passé.
	 */
	public static function deleteOnDB(int $id): ?string {
		$result = DB::delete("groupe")->where("id", "=", $id)->execute();
		if ($result < 1) return "Le groupe de sujet à supprimer n'existe pas";

		return null;
	}

	#region Getters
	public function getId() { return $this->id; }
	public function getIdChronology() { return $this->idChronology; }
	public function getIdOperation() { return $this->idOperation; }
	public function getNMI() { return $this->nmi; }

	public function getChronology(): ?Chronology {
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
			"nmi" => $this->nmi,
		);
	}
	#endregion

	public function saveOnDB(bool $saveAsNew = false): bool {
		if (!$this->validate()) return false;

		if ($saveAsNew) $this->id = null;
		// Préparation des valeurs à envoyer à la BDD
		$arr = $this->toArray();

		if ($this->id === null || Groupe::fetchSingle($this->id) === null) {
			// Le groupe n'existe pas : on la rajoute à la BDD
			list($insertId, $rowAffected) = DB::insert("groupe")
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
			$rowAffected = DB::update("groupe")
				->set($arr)
				->where("id", "=", $this->id)
				->execute();
		}

		return true;
	}

	/** True si toutes les données sont prêtes à être ajoutées à la bdd. */
	public function validate() {
		return $this->validation->validate(function () {
			$validation = $this->validation;
			if (Chronology::fetchSingle($this->idChronology) === null) $validation->invalidate("La chronologie n'est pas valide.");
			if (Operation::fetchSingle($this->idOperation) === null) $validation->invalidate("L'opération n'est pas valide.");
			if ($this->nmi < 0) $validation->invalidate("La valeur de nmi doit être positive.");
		});
	}

	public function echoErrors() { $this->validation->echoErrors(); }

	#endregion

}
