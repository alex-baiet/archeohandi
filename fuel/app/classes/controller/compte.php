<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Compte;
use Model\Helper;
use Model\Messagehandler;
use Model\Redirect;

/**
 * Gestion des pages des connexion et de création ici
 */
class Controller_Compte extends Controller_Template {
	private const DEBUG = false;

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
				$result = Controller_Compte::sendMail(
					"alex.baiet3@gmail.com",
					"Demande d'accès Archéologie du handicap",
					View::forge("compte/mail", array(
						"firstName" => $firstName,
						"lastName" => $lastName,
						"email" => $email,
						"msg" => $_POST["msg"]))
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
		//Compte::checkPermission(Compte::PERM_ADMIN);

		if (!isset($_POST["email"])) Response::redirect("/accueil");
		$email = $_POST["email"];
		$firstName = $_POST["prenom"];
		$lastName = $_POST["nom"];

		if (Controller_Compte::DEBUG === true) {
			// Suppression de l'ancien compte
			DB::delete("compte")->where("email", "=", $email)->execute();
		}

		$login = null;
		$pw = null;
		if (Compte::emailExist($email)) {
			Messagehandler::prepareAlert("Un compte avec le mail indiqué existe déjà. Le compte n'a donc pas été créé.", "danger");
		} else {	
			// Création du compte
			if (Compte::create($firstName, $lastName, $email, $login, $pw)) {
				Messagehandler::prepareAlert("Compte créé !", "success");

				// Mail de confirmation de la création de compte
				Controller_Compte::sendMail(
					$email,
					"Demande d'accès Archéologie du handicap",
					View::forge("compte/mail_confirmed", array(
						"firstName" => $firstName,
						"lastName" => $lastName,
						"login" => $login,
						"password" => $pw
					))
				);
			} else {
				Messagehandler::prepareAlert("Le compte n'a pas pû être créé.", "danger");
			}
		}

		$data = array();
		$this->template->title = 'Confirmation création';
		$this->template->content = View::forge('compte/creation_confirmation', $data);
	}
	
	/** Page admin uniquement : permet de créer le compte */
	public function action_deconnexion() {
		Compte::disconnect();

		if (isset($_POST["previous_page"])) Response::redirect($_POST["previous_page"]);
		else Redirect::redirectBack();
	}

	private static function sendMail(string $to, string $title, string $content): bool {		
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: noreply@archeologieduhandicap\r\n";

		return mail($to, $title, $content, $headers);
	}

}
