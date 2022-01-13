<?php

namespace Model;

/**
 * Permet de traiter plus facilement les images venant de Nakala.
 */
class Nakalaimg {
	public static function urlIsNakalaImg(string $url) {
		return strpos($url, "https://api.nakala.fr/data") === 0;
	}

	public static function urlImgToUrlNakala(string $url) {
		$urlNakala = str_replace("https://api.nakala.fr/data", "https://nakala.fr", $url);
		$exploded = explode('/', $urlNakala);
		array_pop($exploded);
		$urlNakala = implode('/', $exploded);

		return $urlNakala;
	}
}