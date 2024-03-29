<?php

namespace Model\Db;

use Closure;
use Fuel\Core\DB;
use Fuel\Core\Form;
use Fuel\Core\FuelException;
use Model\Helper;

/** Contient des fonctions facilitant la gestion des modèles de la bases de données. */
class Archeo {
	const MERGE_STRING = "string";
	const MERGE_INT = "int";
	const MERGE_FLOAT = "float";
	const MERGE_BOOL = "bool";
	const MERGE_ARRAY = "array";

	/**
	 * Retourne le code html des options pour construire un select.
	 * 
	 * @param string $table Nom de la table dans la BDD où récupérer les données.
	 * @param Closure $valueRecover Lambda permettant de récupérer la "value" pour les options à partir d'une donnée.
	 * @param Closure $valueRecover Lambda permettant de récupérer le texte à afficher pour les options à partir d'une donnée.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée.
	 * @param ?string $emptyValue Ajoute une valeur vide avec comme value="" si non null.
	 */
	public static function fetchOptions(string $table, Closure $valueRecover, Closure $textRecover, $idSelected, ?string $emptyValue = null): string {
		// Récupération de tous les objects
		$results = Helper::querySelect("SELECT * FROM $table;");

		// Création des options
		$options = array();
		if ($emptyValue !== null) $options[""] = $emptyValue;
		foreach ($results as $result) {
			$options[$valueRecover($result)] = $textRecover($result);
		}
		asort($options);

		// Ecriture en html des options
		$html = "";
		foreach ($options as $value => $text) {
			$html .= "<option value='$value'".($idSelected == $value ? " selected" : "").">$text</option>\n";
		}

		return $html;
	}

	/**
	 * Créer un <select> à partir de toutes les données d'une table.
	 * 
	 * @param string $field Valeur du "name" du select.
	 * @param string $label Nom du label du select.
	 * @param mixed $idSelected Identifiant de la valeur sélectionnée.
	 * @param string $table Nom de la table dans la BDD où récupérer les données.
	 * @param Closure $valueRecover Lambda permettant de récupérer la "value" pour les options à partir d'une donnée.
	 * @param Closure $valueRecover Lambda permettant de récupérer le texte à afficher pour les options à partir d'une donnée.
	 * @deprecated Utilisez Archeo::fetchSelectOptions pour construire un select.
	 */
	public static function generateSelect(string $field, string $label, $idSelected, string $table, Closure $valueRecover, Closure $textRecover, bool $formFloating = true, bool $addEmptyValue = true): string {
		// Récupération de tous les objects
		$results = Helper::querySelect("SELECT * FROM $table;");

		// Création des options
		$options = array();
		if ($addEmptyValue) $options[""] = "Sélectionner";
		foreach ($results as $result) {
			$options[$valueRecover($result)] = $textRecover($result);
		}
		asort($options);

		// Création des attributs
		$attr = array();
		if ($formFloating) {
			$attr["class"] = "form-select";
		} else {
			$attr["class"] = "form-select custom-select my-1 mr-2";
			$attr["style"] = "width: 15em;";
		}
		if (!empty($title)) $attr["title"] = $title;
		
		// Création du code HTML
		if ($formFloating) {
			$html = '<div class="form-floating">';
			$html .= Form::select(
				$field,
				$idSelected,
				$options,
				$attr
			);
			$html .= Form::label($label, $field);
			$html .= '</div>';
		} else {
			$html = "<div class='form-check form-check-inline'>"; // form-check ???
			$html .= Form::label($label, $field);
			$html .= Form::select($field, $idSelected, $options, $attr);
			$html .= "</div>";
		}

		return $html;
	}

	/**
	 * Récupère les données d'une table correspondant à l'id.
	 * 
	 * @param int $id L'identifiant des données.
	 * @param string $table Table où rechercher.
	 * @param Closure $dataFormater Transforme les données dans un autre format de votre choix.
	 * @return mixed Revoie les données transformées.
	 * @return null En cas d'échec.
	 */
	public static function fetchSingle(?int $id, string $table, Closure $dataReformat) {
		if ($id === null) return null;
		$res = Helper::querySelectSingle("SELECT * FROM $table WHERE id=$id;");
		if ($res === null) return null;
		return $dataReformat($res);
	}

	/**
	 * Récupère toutes les données d'une table.
	 * 
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
	 * 
	 * @param mixed &$value Valeur à réassigner.
	 * @param mixed $key Nom de la valeur dans $data.
	 * @param array $key Liste des noms possible de la valeur dans $data.
	 * @param array $data Contient toutes les données.
	 */
	public static function mergeValue(&$value, array &$data, $key, $type = Archeo::MERGE_STRING, $nullable = false) {
		if (!is_array($key)) $key = array($key);
		foreach ($key as $k) {
			if (isset($data[$k])) {
				if ($nullable && ($data[$k] === "" || $data[$k] === null)) {
					// Assignation d'un null
					$value = null;
					break;
				}
				switch ($type) {
					case 'string':
						$value = $data[$k];
						break;
					case 'int':
						$value = intval($data[$k]);
						break;
					case 'float':
						$value = floatval($data[$k]);
						break;
					case 'bool':
						$value = $data[$k] == true;
						break;
					case 'array':
						if (is_array($data[$k])) $value = $data[$k];
						break;
					default:
						throw new FuelException("Le type \"$type\" n'est pas un type valide.");
						break;
				}
				return;
			}
		}
	}

	/**
	 * Met à jour les données d'une table de relation entre deux autres tables.
	 * 
	 * @param string $table Table cible dans la BDD
	 * @param string $where Expression du where pour la suppression des anciennes données.
	 * @param array $toInsert Liste des données à intégrer dans la table.
	 * @param Closure $valueTransform Transforme chaque valeur de $toInsert en un array pour permettre l'insertion.
	 */
	public static function updateOnDB(string $table, string $where, array $toInsert, Closure $valueTransform) {
		// Deletion des anciennes valeurs de la BDD
		DB::delete($table)
			->where(DB::expr($where))
			->execute();
		// Ajout des nouvelles valeurs dans la BDD
		foreach ($toInsert as $obj) {
			DB::insert($table)
				->set($valueTransform($obj))
				->execute();
		}
	}

	/**
	 * Renvoie le code html pour afficher l'icone correspondant à l'état.
	 */
	public static function getCompleteIcon(bool $isComplete): string {
		if ($isComplete) return '<i class="bi bi-check-circle-fill" title="La fiche est complète"></i>';
		else return '<i class="bi bi-exclamation-diamond-fill" title="La fiche n\'est pas complète"></i>';
	}

}