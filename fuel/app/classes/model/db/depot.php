<?php

namespace Model\Db;

use Fuel\Core\DB;
use Fuel\Core\Model;
use Model\Validation;

/** Représentation de la table "depot" de la BDD. */
class Depot extends Model {
	#region Values
	private ?int $id = null;
	private ?string $numInventaire = null;
	private ?int $idCommune = null;
	private string $adresse = "";

	/** @var Commune|null|unset */
	private ?Commune $commune;

	private Validation $validation;
	#endregion

	/** Construit le Depot depuis la liste des données. */
	public function __construct(array $data) {
		$this->validation = new Validation();
		Archeo::mergeValue($this->id, $data, "id");
		Archeo::mergeValue($this->numInventaire, $data, "num_inventaire");
		Archeo::mergeValue($this->idCommune, $data, "id_commune");
		Archeo::mergeValue($this->adresse, $data, "adresse");
		if (isset($data["commune"])) $this->idCommune = Commune::nameToId($data["commune"]);
	}

	/**
	 * Récupère le dépôt correspondant à l'id.
	 * @param int $id Identifiant du dépôt.
	 */
	public static function fetchSingle(int $id): ?Depot {
		return Archeo::fetchSingle($id, "depot", function ($data) { return new Depot($data); });
	}

	/**
	 * Supprime le dépôt indiqué de la BDD.
	 * @return string Message d'erreur en cas de problème.
	 * @return null Si tout s'est bien passé.
	 */
	public static function deleteOnDB(int $id): ?string {
		$result = DB::delete("depot")->where("id", "=", $id)->execute();
		if ($result < 1) return "Le dépôt à supprimer n'existe pas";

		return null;
	}

	public function getId() { return $this->id; }
	public function getNumInventaire() { return $this->numInventaire; }
	public function getIdCommune() { return $this->idCommune; }
	public function getAdresse() { return $this->adresse; }

	public function getCommune(): ?Commune {
		if (!isset($this->commune)) $this->commune = Commune::fetchSingle($this->idCommune);
		return $this->commune;
	}

	public function toArray(): array {
		return array(
			"id" => $this->id,
			"num_inventaire" => $this->numInventaire,
			"id_commune" => $this->idCommune,
			"adresse" => $this->adresse
		);
	}

	public function validate(): bool {
		return $this->validation->validate(function () {
			// $validation = $this->validation;
			// if (Commune::fetchSingle($this->idCommune) === null) $validation->invalidate("La commune n'est pas valide.");
		});
	}

	public function saveOnDB($saveAsNew = false) {
		if (!$this->validate()) return false;

		if ($saveAsNew) $this->id = null;
		$arr = $this->toArray();

		if ($this->id === null || Depot::fetchSingle($this->id) === null) {
			// Ajout du dépôt
			list($insertId, $rowAffected) = DB::insert("depot")
			->set($arr)
			->execute();
			if ($rowAffected == 0) {
				$this->validation->invalidate("Une erreur inconnu est survenu lors de l'ajout du dépôt.");
				return false;
			}
			$this->id = $insertId;
		}
		else {
			// Maj du dépôt
			$rowAffected = DB::update("depot")
				->set($arr)
				->where("id", "=", $this->id)
				->execute();
		}

		return true;
	}

	public function echoErrors() { $this->validation->echoErrors(); }

	public function __toString(): string {
		return "Depot {$this->id}";
	}
}
