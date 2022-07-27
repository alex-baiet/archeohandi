<?php

namespace Model;

use Fuel\Core\Database_Exception;
use Fuel\Core\Database_Result;
use Fuel\Core\DB;
use InvalidArgumentException;

/** Classe proposant des fonctions statiques divers, juste pour faciliter la vie. */
class Helper {

	/**
	 * Vérifie que la valeur représente un nombre entier.
	 * @param string Chaîne de caractère à vérifier.
	 */
	static function stringIsInt(string $value): bool {
		return is_numeric($value) && strpos($value, '.') === false;
	}

	/**
	 * Renvoie la chaîne dans un format plus sécurisé en supprimant les tags HTML et les caractères inutiles.
	 * @param string $value Chaîne de caractère a sécuriser.
	 * @return string
	 */
	static function secureString(string $value) {
		if ($value === null) return null;
		return trim(strip_tags($value));
	}

	/**
	 * Renvoie une valeur de l'array, ou null si la valeur n'existe pas.
	 * @return string La valeur est retourné si elle existe, sinon un string vide est retourné.
	 * @deprecated Utilisez Helper::arrayGetValue qui est plus général à la place.
	 */
	static function arrayGetString($key, array &$array): string {
		if (array_key_exists($key, $array)) return $array[$key] !== null ? $array[$key] : "";
		return "";
	}

	/**
	 * Renvoie une valeur de l'array, ou null si la valeur n'existe pas.
	 * @return int La valeur est retourné si elle existe, sinon un int vide est retourné.
	 * @deprecated Utilisez Helper::arrayGetValue qui est plus général à la place.
	 */
	static function arrayGetInt($key, array &$array): int {
		if (array_key_exists($key, $array)) return $array[$key] !== null ? intval($array[$key]) : 0;
		return 0;
	}

	/**
	 * Récupère une valeur depuis l'array, ou $default si la clé n'existe pas.
	 * 
	 * @param mixed $key Clé dans l'array.
	 * @param array &$array Array où chercher les données.
	 * @param mixed $default Valeur retourné si la clé n'existe pas dans l'array.
	 */
	static function arrayGetValue($key, &$array, $default=null) {
		if (array_key_exists($key, $array)) return $array[$key];
		return $default;
	}

	/**
	 * Fait la requête SELECT donnée et renvoie le résultat directement sous forme d'un array.
	 * 
	 * @param string $sql Requête SQL.
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
	 * @param string $sql Requête SQL.
	 * @return array|null
	 */
	public static function querySelectSingle($sql): ?array {
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
	public static function querySelectList($sql): array {
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

	/** Raccourci pour faire plus rapidement un var_dump dans une balise "<pre>". */
	public static function varDump($value, bool $hidden = false) {
		if ($hidden) echo "<pre style='display: none;'>";
		else echo "<pre>";
		var_dump($value);
		echo "</pre>";
	}

	/** Affiche une alert bootstrap. */
	public static function alertBootstrap(string $text, string $color) {
		if (empty($text)) return;
		echo '
			<div class="alert alert-' . $color . ' alert-dismissible text-center my-2 fade show" role="alert">
				' . $text . '
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer">
			</div>';
	}

	/** Active la session si cela n'a pas encore été fait, sinon ne fait rien. */
	public static function startSession() {
		if (session_status() !== PHP_SESSION_ACTIVE) session_start();
	}

	/** Convertit les caractères ayant des accents en caractères sans accents. */
	public static function replaceAccent(string $value) {
		return strtr(utf8_decode($value), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		// Ancienne version n'étant plus fonctionnel JE SAIS PAS POURQUOI GAPZVÇEUOWS
		//return iconv('ISO-8859-1','ASCII//TRANSLIT',$value);
	}

	/** Supprime tout les caractères n'étant pas une lettre alphabétique sans accent. */
	public static function removeNonAlphabet(string $value): string {
		return preg_replace('/[^a-z^A-Z]+/u', '', $value);
	}

	/**
	 * Calculates the great-circle distance between two points, with
	 * the Vincenty formula.
	 * @param float $latitudeFrom Latitude of start point in [deg decimal]
	 * @param float $longitudeFrom Longitude of start point in [deg decimal]
	 * @param float $latitudeTo Latitude of target point in [deg decimal]
	 * @param float $longitudeTo Longitude of target point in [deg decimal]
	 * @param float $earthRadius Mean earth radius in [m]
	 * @return float Distance between points in [m] (same as earthRadius)
	 * @author https://stackoverflow.com/users/575765/martinstoeckli
	 */
	public static function worldDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$lonDelta = $lonTo - $lonFrom;
		$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		$angle = atan2(sqrt($a), $b);
		return $angle * $earthRadius;
	}

	/**
	 * Convertit une date de la BDD en une date au format français.
	 * @param string $date Date au format yyyy-mm-dd
	 * @return string Date au format dd/mm/yyyy
	 */
	public static function dateDBToFrench(string $date): string {
		$arr = explode("-", $date);
		if (count($arr) !== 3) throw new InvalidArgumentException("\"$date\" n'est pas au format yyyy-mm-dd.");
		$result = "{$arr[2]}/{$arr[1]}/{$arr[0]}";

		return $result;
	}

	/**
	 * Récupère le contenu d'une page a l'aide d'une requête POST.
	 * @param string $url Lien de la page.
	 * @param array $postFields Données POST.
	 * @return string Contenu de la page cible.
	 */
	public static function postQuery(string $url, array $postFields): string {
		// url-ify the data for the POST
		$fieldsString = http_build_query($postFields);

		// open connection
		$ch = curl_init();

		// set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);

		// So that curl_exec returns the contents of the cURL; rather than echoing it
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

		// execute post
		return curl_exec($ch);
	}

	/** Envoie un mail. */
	public static function sendMail(string $to, string $title, string $content): bool {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: noreply@archeologieduhandicap\r\n";

		return mail($to, $title, $content, $headers);
	}

	/** Transforme une date MySQL au format français. */
	public static function dbDateBeautify(string $date): string {
		$parts = explode("-", $date);
		if (count($parts) !== 3) return "";
		return "{$parts[2]}/{$parts[1]}/{$parts[0]}";
	}
}