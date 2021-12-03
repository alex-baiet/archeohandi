<?php

use Fuel\Core\Database_Exception;
use Fuel\Core\Database_Result;
use Fuel\Core\DB;

/** Classe proposant des fonctions statiques divers. */
class Helper {

	/**
	 * Permet de vérifier si ce qui est envoyé correspond à des caractères valides.
	 */
	static function verif_alpha($str, $type) {
		//Enlève les espaces, tabulations, etc au début et fin de la chaine de caractère
		trim($str);
		strip_tags($str);

		if ($type == "alpha") preg_match('/([^A-Za-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ ])/', $str, $result);
		if ($type == "alphatout") preg_match('/([^A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ ])/', $str, $result);
		if ($type == "alphanum") preg_match('/([^A-Za-z0-9,-;()\/ ])/', $str, $result);
		if (!empty($result)) return false;
		else return $str;
	}

	/**
	 * Fait la requête SELECT donnée et renvoie le résultat directement sous forme d'un array.
	 * 
	 * @param string $sql
	 * @return array
	 */
	public static function querySelect($sql) {
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

}