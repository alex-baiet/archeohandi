<?php

namespace Model;

/** Gère les notifications sur le site. */
class Messagehandler {
	const ALERT_PRIMARY = "primary";
	const ALERT_SECONDARY = "secondary";
	const ALERT_SUCCESS = "success";
	const ALERT_DANGER = "danger";
	const ALERT_WARNING = "warning";
	const ALERT_INFO = "info";
	const ALERT_LIGHT = "light";
	const ALERT_DARK = "dark";

	/** Prepare l'affichage d'une alert qui sera afficher lors du prochain chargement de page. */
	public static function prepareAlert(string $msg, string $type = Messagehandler::ALERT_PRIMARY) {
		Helper::startSession();
		if (empty($msg)) return;

		if (!isset($_SESSION["msg"])) {
			$_SESSION["msg"] = array();
			$_SESSION["type"] = array();
		}

		$_SESSION["msg"][] = $msg;
		$_SESSION["type"][] = $type;
	}

	/** Affiche les alerts préparé avec prepareAlert(). Utilisé au chargement de chaque page. */
	public static function echoAlert() {
		Helper::startSession();

		if (empty($_SESSION["msg"])) return;

		for ($i=0; $i < count($_SESSION["msg"]); $i++) { 
			Helper::alertBootstrap($_SESSION["msg"][$i], $_SESSION["type"][$i]);
		}
		unset($_SESSION["msg"]);
		unset($_SESSION["type"]);
	}
}