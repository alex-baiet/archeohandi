<?php

namespace Model;

use Fuel\Core\Form;
use Fuel\Core\Model;

/** Représentation d'une chronologie dans la base de données. */
class Chronologie extends Model {
	private int $id;
	private string $nom;
	private int $start;
	private int $end;

	/** Créer l'objet à partir des données en paramètre. */
	public function __construct(array $data) {
		$this->id = Helper::arrayGetInt("id_chronologie", $data);
		$this->nom = Helper::arrayGetString("nom_chronologie", $data);
		$this->start = Helper::arrayGetInt("date_debut", $data);
		$this->end = Helper::arrayGetInt("date_fin", $data);
	}

	/**
	 * Récupère les données correspondant à l'id.
	 * 
	 * @param int $id Identifiant.
	 * @return Chronologie|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM chronologie_site WHERE id_chronologie=$id;");
		if ($res === null) return null;

		$obj = new Chronologie($res);
		return $obj;
	}

	/**
	 * Créer un <select> à partir de toutes les chronologies.
	 * @param string $field Valeur du "name" du select.
	 * @param string $label Texte du label du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée par défaut.
	 */
	public static function generateSelect(string $field = "chronologie", string $label = "Chronologie", $idSelected = 18): string {
		// Récupération de tous les objects
		/** @var Chronologie[] */
		$objects = array();
		$results = Helper::querySelect("SELECT * FROM chronologie_site;");
		foreach ($results as $result) {
			$objects[] = new Chronologie($result);
		}
		
		// Création des options
		$options = array();
		foreach ($objects as $obj) {
			$options[$obj->getId()] = $obj->getNom();
		}
		
		// Création du code HTML
		$html = '<div class="form-floating">';
		$html .= Form::select(
			$field,
			$idSelected,
			$options,
			array("class" => "form-select")
		);
		$html .= Form::label($label, $field);
		$html .= '</div>';
		return $html;
	}

	/**
	 * Récupère l'id à partir du nom de la chronologie.
	 * 
	 * @return int id de la chronologie en cas de succès.
	 * @return null Si aucune chronologie ne correspond au paramètre donné.
	 */
	public static function nameToId(string $name) {
		if (empty($name)) return null;
		$res = Helper::querySelectSingle("SELECT id_chronologie FROM chronologie_site WHERE nom_chronologie=\"{$name}\"");
		return $res === null ? null : intval($res["id"]);
	}

	public function getId() { return $this->id; }
	public function getNom() { return $this->nom; }
	public function getStart() { return $this->start; }
	public function getEnd() { return $this->end; }

}