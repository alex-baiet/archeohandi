<?php

namespace Model;

use Fuel\Core\Response;
use Fuel\Core\Uri;

/** Permet de gérer les redirections. */
class Redirect {
	public static function redirect(string $url, $method = "location", $code = 302) {
		Helper::startSession();

		$_SESSION["previous_page"] = Uri::current();
		Response::redirect($url, $method, $code);
	}

	public static function redirectBack() {
		Helper::startSession();

		if (isset($_SESSION["previous_page"])) {
			$previousPage = $_SESSION["previous_page"];
			Response::redirect(($previousPage));
		} else {
			Response::redirect("/accueil");
		}
	}
}