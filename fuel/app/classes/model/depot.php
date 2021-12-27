<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Depot extends Model {
	#region Values
	private int $id = -1;
	private ?string $numInventaire = null;
	private int $idCommune = -1;
	private string $adresse = "";

	/** @var Commune|null|unset */
	private ?Commune $commune;
	#endregion

	/** Construit le Depot depuis la liste des données. */
	public function __construct(array $data) {
		Archeo::mergeValue($this->id, $data, "id");
		Archeo::mergeValue($this->numInventaire, $data, "num_inventaire");
		Archeo::mergeValue($this->idCommune, $data, "id_commune");
		Archeo::mergeValue($this->adresse, $data, "adresse");
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
}
