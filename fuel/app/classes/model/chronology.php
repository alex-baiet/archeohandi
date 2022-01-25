<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une chronologie dans la base de données. */
class Chronology extends Model {
	private int $id;
	private string $name;
	private int $start;
	private int $end;

	/** Créer l'objet à partir des données en paramètre. */
	public function __construct(array $data) {
		$this->id = Helper::arrayGetInt("id", $data);
		$this->name = Helper::arrayGetString("name", $data);
		$this->start = Helper::arrayGetInt("start", $data);
		$this->end = Helper::arrayGetInt("end", $data);
	}

	/**
	 * Récupère les données correspondant à l'id.
	 * @param int $id Identifiant.
	 */
	public static function fetchSingle(int $id): ?Chronology {
		return Archeo::fetchSingle($id, "chronology", function ($data) { return new Chronology($data); });
	}

	/**
	 * Créer un <select> à partir de toutes les chronologies.
	 * @param string $field Valeur du "name" du select.
	 * @param string $label Texte du label du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée par défaut.
	 */
	public static function generateSelect(string $field = "id_chronology", string $label = "Chronologie", $idSelected = 18, $formFloating = true, $addEmptyValue): string {
		$valueRecover = function ($data) { return $data["id"]; };
		$textRecover = function ($data) { return $data["name"]; };
		return Archeo::generateSelect($field, $label, $idSelected, "chronology", $valueRecover, $textRecover, $formFloating, $addEmptyValue);
	}

	/**
	 * Récupère l'id à partir du name de la chronologie.
	 * 
	 * @return int id de la chronologie en cas de succès.
	 * @return null Si aucune chronologie ne correspond au paramètre donné.
	 */
	public static function nameToId(string $name) {
		if (empty($name)) return null;
		$res = Helper::querySelectSingle("SELECT id FROM chronology WHERE name=\"{$name}\"");
		return $res === null ? null : intval($res["id"]);
	}

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getStart() { return $this->start; }
	public function getEnd() { return $this->end; }

}