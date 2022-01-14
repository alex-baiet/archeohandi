<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Helper;
use Model\Messagehandler;

/**
 * Gestion des pages des connexion et de création ici
 */
class Controller_Compte extends Controller_Template {
	/** Création d'un compte. */
	public function action_creation() {
		$data = array();

		if (isset($_POST["create"])) {
			$firstName = Helper::secureString($_POST["prenom"]);
			$lastName = Helper::secureString($_POST["nom"]);

			// Validation des champs
			$errors = array();
			if (empty($firstName)) $errors[] = "Indiquez votre prénom.";
			else $firstName[0] = strtoupper($firstName[0]);
			if (empty($lastName)) $errors[] = "Indiquez votre prénom.";
			else $lastName = strtoupper($lastName);

			if (!empty($errors)) {
				// Données invalide : Préparation affichage des erreurs
				Helper::varDump($errors);

			} else {
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "From: noreply@archeologieduhandicap\r\n";

				// Données valide : envoie du mail
				$result = mail(
					"aleuxpro@gmail.com",
					"Demande d'accès Archéologie du handicap",
					View::forge("compte/mail", array(
						"firstName" => $firstName,
						"lastName" => $lastName,
						"msg" => $_POST["msg"])),
					$headers
				);
				if ($result) {
					Messagehandler::prepareAlert("La demande de création de compte a été envoyé.", "success");
					Response::redirect("/accueil");
				} else {
					Messagehandler::prepareAlert("La demande de création de compte n'a pas pu être envoyé.", "danger");
				}
			}

		}

		$this->template->title = 'Accueil';
		$this->template->content = View::forge('compte/creation', $data);
	}

	/** Connexion à un compte existant. */
	public function action_connexion() {
		$data = array();
		$this->template->title = 'Accueil';
		$this->template->content = View::forge('compte/connexion', $data);
	}
}
