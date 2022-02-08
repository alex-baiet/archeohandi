<?php

namespace Model;

use Fuel\Core\Response;
use Fuel\Core\Uri;

/** Permet de gérer les redirections. */
class Redirect {
	
	public static function redirectBack() {
		Helper::startSession();

		if (isset($_SESSION["previous_page"])) {
			$previousPage = $_SESSION["previous_page"];
			unset($_SESSION["previous_page"]);
			Response::redirect(($previousPage));
		} else {
			Response::redirect("/accueil");
		}
	}

	/** Met la page actuelle en tant que page précédente pour les futurs redirections. */
	public static function setPreviousPage() {
		Helper::startSession();
		$_SESSION["previous_page"] = Uri::current();
	}

	/** Renvoie l'url de la page précédente, ou null si la page n'est pas défini. */
	public static function getPreviousPage(): ?string {
		Helper::startSession();
		if (isset($_SESSION["previous_page"])) return $_SESSION["previous_page"];
		else return null;
	}
}