<?php

namespace Model;

/**
 * Classe pour envoyer des donner post a Nakala. Un échec...
 * @deprecated Utilisez Helper::postQuery pour envoyer des données POST.
 */
class Requesthandler {
	private static string $apiKey = '6f916df0-ba8c-3d5a-f1c3-df4bbaa0b396';

	public static function curlMethod($url, array $values) {
		//Server url
		// $url = "https://api.nakala.fr/datas/uploads";
		$headers = array(
			'X-API-KEY: '.Requesthandler::$apiKey
		);
		// Send request to Server
		$ch = curl_init($url);
		// To save response in a variable from server, set headers;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $values);
		// Get response
		$response = curl_exec($ch);
		return $response;
		// Decode
		//$result = json_decode($response);
	}
}
