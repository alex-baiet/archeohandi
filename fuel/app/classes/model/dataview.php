<?php

namespace Model;

use Closure;

class Dataview {

	/**
	 * Renvoie la valeur sous forme de string pour l'afficher sur sujet/description ou operations/view.
	 * @param $value Valeur à afficher.
	 * @param Closure $transformer Fonction pour transformer la valeur si elle n'est pas vide.
	 */
	public static function dataToView($value, Closure $transformer = null, bool $bold = true, string $default = "inconnu"): string {
		if (empty($value)) return "<span class='no-data'>$default</span>";
		if ($value == "Indéterminé" || $value == "Indéterminée") {
			$value[0] = strtolower($value[0]);
			return "<span class='no-data'>$value</span>";
		}
		if ($transformer !== null) $value = $transformer($value);
		return $bold ? "<b>$value</b>" : "$value";
	}

	public static function descriptionToView($text): string {
		if (empty($text)) return "<p class='description no-data'>Vide</p>";
		return "<p class='description'>$text</p>";
	}
}