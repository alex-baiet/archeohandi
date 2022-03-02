<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;
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
			$_POST["prenom"] = Helper::secureString($_POST["prenom"]);
			$_POST["nom"] = Helper::secureString($_POST["nom"]);
			$_POST["email"] = Helper::secureString($_POST["email"]);
			$_POST["organisme"] = Helper::secureString($_POST["organisme"]);

			// Validation des champs
			$error = false;
			if (empty($_POST["prenom"])) {
				$error = true;
				Messagehandler::prepareAlert("Indiquez votre prénom.", "danger");
			}
			if (empty($_POST["nom"])) {
				$error = true;
				Messagehandler::prepareAlert("Indiquez votre nom.", "danger");
			}
			if (!$error && empty(Compte::generateLogin($_POST["prenom"], $_POST["nom"]))) {
				$error = true;
				Messagehandler::prepareAlert("Les prénom et nom donné ne permettent pas de créer un login. Ecrivez-les en alphabet latin.");
			}
			if (Compte::emailExist($_POST["email"])) {
				$error = true;
				Messagehandler::prepareAlert("Un compte avec le mail donné existe déjà.", "danger");
			}

			if (!$error) {
				// Les données sont valides
				$to = Controller_Compte::DEBUG === true || $_POST["msg"] === "\\REDIRECT" ? "alex.baiet3@gmail.com" : "cyrille.le-forestier@inrap.fr, valerie.delattre@inrap.fr";
				$result = Controller_Compte::sendMail(
					$to,
					"Demande d'accès Archéologie du handicap",
					View::forge("compte/mail", array("data" => $_POST))
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

	/** Page admin uniquement : permet de créer le compte */
	public function action_creation_confirmation() {

		if (!isset($_POST["token"])) Response::redirect("/accueil");
		$email = $_POST["email"];
		$firstName = $_POST["prenom"];
		$lastName = $_POST["nom"];
		$lastName = $_POST["nom"];
		$token = $_POST["token"];
		$organisme = $_POST["organisme"];

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
			if (Compte::create($firstName, $lastName, $email, $login, $pw, $organisme)) {
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
