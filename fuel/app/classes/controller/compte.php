<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Compte;
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
			$email = Helper::secureString($_POST["email"]);

			// Validation des champs
			$error = false;
			if (empty($firstName)) {
				$error = true;
				Messagehandler::prepareAlert("Indiquez votre prénom.", "danger");
			}
			else $firstName[0] = strtoupper($firstName[0]);

			if (empty($lastName)) {
				$error = true;
				Messagehandler::prepareAlert("Indiquez votre nom.", "danger");
			}
			else $lastName = strtoupper($lastName);

			if (!$error) {
				// Les données sont valides

				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "From: noreply@archeologieduhandicap\r\n";

				// Données valide : envoie du mail
				$result = mail(
					"alex.baiet3@gmail.com",
					"Demande d'accès Archéologie du handicap",
					View::forge("compte/mail", array(
						"firstName" => $firstName,
						"lastName" => $lastName,
						"email" => $email,
						"msg" => $_POST["msg"])),
					$headers
				);
				if ($result) {
					Messagehandler::prepareAlert("La demande de création de compte a été envoyé. Vous recevrez un mail de confirmation avec vos identifiants une fois la création validée par un administrateur.", "success");
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
		if (isset($_POST["login"]) && isset($_POST["mdp"])) {
			$login = $_POST["login"];
			$mdp = $_POST["mdp"];
			if (Compte::connect($login, $mdp)) {
				// Connexion réussi
				Response::redirect("/accueil");
			}
		}

		$data = array();
		$this->template->title = 'Accueil';
		$this->template->content = View::forge('compte/connexion', $data);
	}

	/** Page admin uniquement : permet de créer le compte */
	public function action_creation_confirmation() {
		if (!isset($_POST["email"])) Response::redirect("/accueil");

		$data = array();
		$this->template->title = 'Confirmation création';
		$this->template->content = View::forge('compte/creation_confirmation', $data);
	}
	
	/** Page admin uniquement : permet de créer le compte */
	public function action_deconnexion() {
		Compte::disconnect();

		if (isset($_POST["previous_page"])) Response::redirect($_POST["previous_page"]);
		else Response::redirect("/accueil");
	}
}
