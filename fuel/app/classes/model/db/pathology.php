<?php

namespace Model\Db;

use Fuel\Core\Model;

/** Représentation d'une chronologie dans la base de données. */
class Pathology extends Model {
	private ?int $id = null;
	private string $name = "";

	/** Créer l'objet à partir des données en paramètre. */
	public function __construct(array $data) {
		Archeo::mergeValue($this->id, $data, "id", "int");
		Archeo::mergeValue($this->name, $data, "nom");
	}

	/**
	 * Récupère les données correspondant à l'id depuis la base de données.
	 * @param int $id Identifiant.
	 */
	public static function fetchSingle(int $id): ?Pathology {
		return Archeo::fetchSingle($id, "pathologie", function ($data) { return new Pathology($data); });
	}

	/** @return Pathology[] */
	public static function fetchAll(): array {
		return Archeo::fetchAll("pathologie", function ($data) { return new Pathology($data); });
	}

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
}