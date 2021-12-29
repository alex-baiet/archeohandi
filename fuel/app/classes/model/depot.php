<?php

namespace Model;

use Fuel\Core\DB;
use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
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
			$validation = $this->validation;
			if (Commune::fetchSingle($this->idCommune) === null) $validation->invalidate("La commune n'est pas valide.");
		});
	}

	public function saveOnDB() {
		if (!$this->validate()) return false;
		$arr = $this->toArray();

		list($insertId, $rowAffected) = DB::insert("depot")
		->set($arr)
		->execute();
		if ($rowAffected == 0) {
			$this->validation->invalidate("Une erreur inconnu est survenu lors de l'ajout du dépôt.");
			return false;
		}
		$this->id = $insertId;

		return true;
	}
}
