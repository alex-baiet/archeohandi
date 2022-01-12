<?php

namespace Model;

class Messagehandler {
	/** Prepare l'affichage d'une alert qui sera afficher lors du prochain chargement de page. */
	public static function prepareAlert(string $msg, string $type = "primary") {
		Messagehandler::startSession();
		
		if (empty($msg)) return;
		$_SESSION["msg"] = $msg;
		$_SESSION["type"] = $type;
	}

	/** Affiche l'alert préparé avec prepareAlert(). Utilisé au chargement de chaque page. */
	public static function echoAlert() {
		Messagehandler::startSession();

		if (empty($_SESSION["msg"])) return;
		Helper::alertBootstrap($_SESSION["msg"], $_SESSION["type"]);
		unset($_SESSION["msg"]);
		unset($_SESSION["type"]);
	}

	private static function startSession() {
		if (session_status() !== PHP_SESSION_ACTIVE) session_start();
	} 
}