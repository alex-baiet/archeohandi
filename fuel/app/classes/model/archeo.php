<?php

namespace Model;

use Closure;
use Fuel\Core\Form;
use Fuel\Core\Model;

/** Représente un appareil compensatoire pour un sujet handicapé. */
class Archeo extends Model {

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
		/** @var Personne[] */
		$objects = array();
		$results = Helper::querySelect("SELECT * FROM $table;");

		// Création des options
		$options = array();
		$options[""] = "Sélectionner";
		foreach ($results as $result) {
			$objects[] = new Personne($result);
			$options[$valueRecover($result)] = $textRecover($result);
		}
		
		// Création du code HTML
		$html = '<div class="form-floating">';
		$html .= Form::select(
			$field,
			$idSelected,
			$options,
			array("class" => "form-select my-2")
		);
		$html .= Form::label($label, $field);
		$html .= '</div>';
		return $html;
	}

}