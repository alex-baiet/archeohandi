<?php

namespace Model;

use Closure;
use Fuel\Core\Form;
use Fuel\Core\FuelException;

/** Contient des fonctions facilitant la gestion des modèles de la bases de données. */
class Archeo {

	/**
	 * Créer un <select> à partir de toutes les données d'une table.
	 * 
	 * @param string $field Valeur du "name" du select.
	 * @param string $label Nom du label du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée.
	 * @param string $table Nom de la table dans la BDD où récupérer les données.
	 * @param Closure $valueRecover Lambda permettant de récupérer la "value" pour les options à partir d'une donnée.
	 * @param Closure $valueRecover Lambda permettant de récupérer le texte à afficher pour les options à partir d'une donnée.
	 */
	public static function generateSelect(string $field, string $label, $idSelected, string $table, Closure $valueRecover, Closure $textRecover): string {
		// Récupération de tous les objects
		$results = Helper::querySelect("SELECT * FROM $table;");

		// Création des options
		$options = array();
		$options[""] = "Sélectionner";
		foreach ($results as $result) {
			$options[$valueRecover($result)] = $textRecover($result);
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
	 * Récupère les données d'une table correspondant à l'id.
	 * @param int $id L'identifiant des données.
	 * @param string $table Table où rechercher.
	 * @param Closure $dataFormater Transforme les données dans un autre format de votre choix.
	 * @return mixed Revoie les données transformées.
	 * @return null En cas d'échec.
	 */
	public static function fetchSingle(int $id, string $table, Closure $dataReformat) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM $table WHERE id=$id;");
		if ($res === null) return null;
		return $dataReformat($res);
	}

	/**
	 * Récupère toutes les données d'une table.
	 * @param string $table Table où récupérer les données.
	 * @param Closure $dataFormater Transforme chaque donnée dans un autre format de votre choix.
	 */
	public static function fetchAll(string $table, Closure $dataReformat): array {
		$results = Helper::querySelect("SELECT * FROM $table");
		$objects = array();
		foreach ($results as $res) {
			$objects[] = $dataReformat($res);
		}
		return $objects;
	}

	/**
	 * Réassigne $value avec une donnée dans data, si elle existe. 
	 * @param mixed &$value Valeur à réassigner.
	 * @param array $array Contient toutes les données.
	 * @param mixed $key Nom de la valeur dans $data.
	 */
	public static function mergeValue(&$value, array &$data, $key, $type = "string") {
		if (isset($data[$key])) {
			switch ($type) {
				case 'string':
					$value = $data[$key];
					break;
				case 'int':
					$value = intval($data[$key]);
					break;				
				default:
					throw new FuelException("Le type \"$type\" n'est pas un type valide.");
					break;
			}
		}
	}

}