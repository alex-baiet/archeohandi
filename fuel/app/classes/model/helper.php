<?php

namespace Model;

use Fuel\Core\Database_Exception;
use Fuel\Core\Database_Result;
use Fuel\Core\DB;

/** Classe proposant des fonctions statiques divers, juste pour faciliter la vie. */
class Helper {

	/**
	 * Permet de vérifier si ce qui est envoyé correspond à des caractères valides.
	 * @return string|false Renvoie false uniquement si le texte n'est pas conforme,
	 * ou renvoie le texte corrigé (si il est corrigeable).
	 */
	static function verif_alpha($str, $type = "alphatout") {
		if (empty($str)) return "";
		//Enlève les espaces, tabulations, etc au début et fin de la chaine de caractère
		trim($str);
		strip_tags($str);

		if ($type == "alpha") preg_match('/([^A-Za-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ ])/', $str, $result);
		if ($type == "alphatout") preg_match('/([^A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ ])/', $str, $result);
		if ($type == "alphanum") preg_match('/([^A-Za-z0-9,-;()\/ ])/', $str, $result);
		if (!empty($result)) return false;
		return $str;
	}

	/** Vérifie que la valeur représente un nombre entier. */
	static function stringIsInt(string $value) {
		return is_numeric($value) && strpos($value, '.') === false;
	}

	/** Renvoie la chaîne dans un format plus sécurisé en supprimant les tags HTML et les caractères inutiles. */
	static function secureString(string $value): string {
		return trim(strip_tags($value));
	}

	/**
	 * Renvoie une valeur de l'array, ou null si la valeur n'existe pas.
	 * @return string La valeur est retourné si elle existe, sinon un string vide est retourné.
	 */
	static function arrayGetString($key, array $array): string {
		if (array_key_exists($key, $array)) return $array[$key] !== null ? $array[$key] : "";
		return "";
	}

	/**
	 * Renvoie une valeur de l'array, ou null si la valeur n'existe pas.
	 * @return string La valeur est retourné si elle existe, sinon un string vide est retourné.
	 */
	static function arrayGetInt($key, array $array): int {
		if (array_key_exists($key, $array)) return $array[$key] !== null ? intval($array[$key]) : 0;
		return 0;
	}

	/**
	 * Renvoie une valeur de l'array, ou null si la valeur n'existe pas.
	 * @return array|null La valeur est retourné si elle existe, sinon un null est retourné.
	 */
	static function arrayGetArray($key, array $array) {
		if (array_key_exists($key, $array)) return $array[$key];
		return null;
	}

	/**
	 * Fait la requête SELECT donnée et renvoie le résultat directement sous forme d'un array.
	 * 
	 * @param string $sql
	 * @return array
	 */
	public static function querySelect($sql): array {
		$query = DB::query($sql);
		/** @var Database_Result */
		$dbRes = $query->execute();
		if (!($dbRes instanceof Database_Result)) {
			throw new Database_Exception("La requête ne correspond pas à un SELECT, ou contient une erreur.");
		}
		$res = $dbRes->as_array();
		return $res;
	}

	/**
	 * Fait la requête SQL donnée et renvoie le premier résultat.
	 * 
	 * @param string $sql
	 * @return array|null
	 */
	public static function querySelectSingle($sql) {
		$res = Helper::querySelect($sql);
		if (count($res) === 0) return null;
		return $res[0];
	}

	/** 
	 * Similaire à querySelect, mais sous un format plus simple pour les SELECT de une seule colonne
	 * 
	 * @param string $sql
	 * @param string $keyName Nom de la colonne sélectionnée.
	 * @return array Array des résultats sous forme d'une liste. ex: array("resultat 1", "resultat 2", ...)
	 */
	public static function querySelectList($sql) {
		// Requete SQL
		$resQuery = Helper::querySelect($sql);
		
		// Transformation de l'array
		$resFinal = array();
		foreach ($resQuery as $value) {
			$key = array_key_first($value);
			// echo "<pre>"; print_r($value); echo "</pre>";
			$resFinal[] = $value[$key];
		}

		return $resFinal;
	}

	public static function arrayValuesAreKeys($array) {
		$res = array();
		foreach ($array as $key => $value) {
			$res[$value] = $value;
		}
		return $res;
	}

	/** Raccourci pour faire plus rapidement un var_dump entouré de <pre>. */
	public static function varDump($value) {
		echo "<pre>";
		var_dump($value);
		echo "</pre>";
	}

	/** Affiche une alert bootstrap. */
	public static function alertBootstrap(string $text, string $color) {
		$text = str_replace("<br>", "fjsqlkfjmldsjklqs", $text);
		echo '
			<div class="alert alert-' . $color . ' alert-dismissible text-center my-2 fade show" role="alert">
				' . $text . '
				<button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
			</div>';
	}
}