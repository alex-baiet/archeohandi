<?php

use Fuel\Core\Controller_Template;
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
	/** Token de sécurité devant être validé pour pouvoir créer un compte. */
	private const TOKEN = "c7e626f1f507f3798570649c91ff9a5e";

	/** Création d'un compte. */
	public function action_creation() {
		Compte::checkPermissionRedirect("Vous êtes déjà connecté.", Compte::PERM_DISCONNECTED);

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
			if (empty($lastName)) {
				$error = true;
				Messagehandler::prepareAlert("Indiquez votre nom.", "danger");
			}
			if (!$error && empty(Compte::generateLogin($firstName, $lastName))) {
				$error = true;
				Messagehandler::prepareAlert("Les prénom et nom donné ne permettent pas de créer un login. Ecrivez-les en alphabet latin.");
			}
			if (Compte::emailExist($email)) {
				$error = true;
				Messagehandler::prepareAlert("Un compte avec le mail donné existe déjà.", "danger");
			}

			if (!$error) {
				// Les données sont valides
				$to = Controller_Compte::DEBUG === true ? "alex.baiet3@gmail.com" : "cyrille.le-forestier@inrap.fr, valerie.delattre@inrap.fr";
				$result = Controller_Compte::sendMail(
					// "alex.baiet3@gmail.com",
					$to,
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
		Compte::checkPermissionRedirect("Vous êtes déjà connecté.", Compte::PERM_DISCONNECTED);

		if (isset($_POST["login"]) && isset($_POST["mdp"])) {
			$login = $_POST["login"];
			$mdp = $_POST["mdp"];
			if (Compte::connect($login, $mdp)) {
				// Connexion réussi
				Response::redirect("/accueil");
			} else {
				Messagehandler::prepareAlert("Votre login ou/et mot de passe est incorrect.", "danger");
			}
		}

		$data = array();
		$this->template->title = 'Accueil';
		$this->template->content = View::forge('compte/connexion', $data);
	}

	/** @deprecated La redirection n'est plus utilisé. */
	public function action_creation_redirection() {

		Helper::startSession();

		if (!isset($_POST["email"])) Response::redirect("/accueil");
		$_SESSION["email"] = $_POST["email"];
		$_SESSION["prenom"] = $_POST["prenom"];
		$_SESSION["nom"] = $_POST["nom"];

		Response::redirect("/compte/creation_confirmation");
	}

	/** Page admin uniquement : permet de créer le compte */
	public function action_creation_confirmation() {

		if (!isset($_POST["token"])) Response::redirect("/accueil");
		$email = $_POST["email"];
		$firstName = $_POST["prenom"];
		$lastName = $_POST["nom"];
		$token = $_POST["token"];

		$login = null;
		$pw = null;

		$error = false;
		
		if ($token !== Controller_Compte::TOKEN) {
			$error = true;
			Messagehandler::prepareAlert("Impossible de créer le compte : le token donné ne corresponde pas.");
		}

		if (Compte::emailExist($email)) {
			$error = true;
			Messagehandler::prepareAlert("Un compte avec le mail indiqué existe déjà. Le compte n'a donc pas été créé.", "danger");
		}
		
		if (!$error) {
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

	/** Envoie un mail. */
	private static function sendMail(string $to, string $title, string $content): bool {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: noreply@archeologieduhandicap\r\n";

		return mail($to, $title, $content, $headers);
	}

}
