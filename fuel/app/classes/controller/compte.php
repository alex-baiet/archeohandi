<?php

use Fuel\Core\DB;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Helper;
use Model\Messagehandler;
use Model\Redirect;
use Model\Template;

/**
 * Gestion des pages des connexion et de création ici
 */
class Controller_Compte extends Template {
	private const DEBUG = false;
	/** Token de sécurité devant être validé pour pouvoir créer un compte. */
	private const TOKEN = "c7e626f1f507f3798570649c91ff9a5e";

	/** Page connexion à un compte existant. */
	public function action_connexion() {
		Compte::checkPermissionRedirect("Vous êtes déjà connecté.", Compte::PERM_DISCONNECTED);

		if (isset($_POST["login"]) && isset($_POST["mdp"])) {
			$login = $_POST["login"];
			$mdp = $_POST["mdp"];
			if (Compte::connect($login, $mdp)) {
				// Connexion réussi
				Response::redirect("/accueil");
			} else {
				Messagehandler::prepareAlert("Votre login ou/et mot de passe est incorrect.", Messagehandler::ALERT_DANGER);
			}
		}

		$data = array();
		$this->title('Connexion');
    $this->css(["form.css"]);
		$this->content(View::forge('compte/connexion', $data));
	}

	/** Reset le mot de passe. */
	public function action_redefinition() {
		Compte::checkPermissionRedirect("Vous êtes déjà connecté.", Compte::PERM_DISCONNECTED);

		if (isset($_POST["login"]) && isset($_POST["email"])) {
			$login = $_POST["login"];
			$email = $_POST["email"];
			$result = DB::select()->from("compte")->where("login", "=", $login)->where("email", "=", $email)->execute()->as_array();
			if (!empty($result)) {
				// Création du nouveau mdp
				$pw = Compte::redefinePassword($login);
				if ($pw === null) {
					Messagehandler::prepareAlert("Impossible de redéfinir le mot de passe.", Messagehandler::ALERT_DANGER);
				} else {
					Helper::sendMail($email, "Changement de mot de passe", View::forge("compte/mail_mdp", array("login" => $login, "pw" => $pw)));
					Messagehandler::prepareAlert("Votre mot de passe a bien été redéfini et a été envoyé dans votre boîte mail.", Messagehandler::ALERT_SUCCESS);
					Response::redirect("/compte/connexion");
				}

			} else {
				Messagehandler::prepareAlert("Le login et/ou compte mail n'est pas valide.", Messagehandler::ALERT_DANGER);
			}
		}

		$data = array();
		$this->title('Redéfinition');
		$this->css(["form.css"]);
		$this->content(View::forge('compte/redefinition', $data));
	}

	/** Page création d'un compte. */
	public function action_creation() {
		Compte::checkTestRedirect("Vous êtes déjà connecté.", Compte::checkPermission(Compte::PERM_DISCONNECTED) || Compte::checkPermission(Compte::PERM_ADMIN));

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
				Messagehandler::prepareAlert("Indiquez votre prénom.", Messagehandler::ALERT_DANGER);
			}
			if (empty($_POST["nom"])) {
				$error = true;
				Messagehandler::prepareAlert("Indiquez votre nom.", Messagehandler::ALERT_DANGER);
			}
			if (!$error && empty(Compte::generateLogin($_POST["prenom"], $_POST["nom"]))) {
				$error = true;
				Messagehandler::prepareAlert("Les prénom et nom donné ne permettent pas de créer un login. Ecrivez-les en alphabet latin.", Messagehandler::ALERT_DANGER);
			}
			if (Compte::emailExist($_POST["email"])) {
				$error = true;
				Messagehandler::prepareAlert("Un compte avec le mail donné existe déjà.", Messagehandler::ALERT_DANGER);
			}

			if (!$error) {
				// Les données sont valides
				if (isset($_POST["immediate"])) {
					// Creation immediate (pour les admins)
					if (Compte::create($_POST["prenom"], $_POST["nom"], $_POST["email"], $login, $pw, $_POST["organisme"])) {
						Messagehandler::prepareAlert("Le compte a bien été créé.", Messagehandler::ALERT_SUCCESS);
						$data["login"] = $login;
						$data["pw"] = $pw;
						$this->template->content = View::forge('compte/creation_admin', $data);
						return;
					} else {
						Messagehandler::prepareAlert("Une erreur est survenu lors de la création du compte.", Messagehandler::ALERT_DANGER);
					}

				} else {
					// Creation par mail
					$to = Controller_Compte::DEBUG === true || $_POST["msg"] === "\\REDIRECT" ? "alex.baiet3@gmail.com" : "cyrille.le-forestier@inrap.fr";//, valerie.delattre@inrap.fr";
					$result = Helper::sendMail(
						$to,
						"Demande d'accès Archéologie du handicap",
						View::forge("compte/mail", array("data" => $_POST))
					);

					if ($result) {
						Messagehandler::prepareAlert("La demande de création de compte a été envoyé. Vous recevrez un mail de confirmation avec vos identifiants une fois la création validée par un administrateur.", Messagehandler::ALERT_SUCCESS);
						Response::redirect("/accueil");
					} else {
						Messagehandler::prepareAlert("La demande de création de compte n'a pas pu être envoyé.", Messagehandler::ALERT_DANGER);
					}
				}
			}
		}

		$this->title('Créer un compte');
		$this->jquery(true);
    $this->css(["form.css"]);
    $this->js(["form.js"]);
		$this->content(View::forge('compte/creation', $data));
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
			Messagehandler::prepareAlert("Impossible de créer le compte : le token donné ne corresponde pas.", Messagehandler::ALERT_DANGER);
		}

		if (Compte::emailExist($email)) {
			$error = true;
			Messagehandler::prepareAlert("Un compte avec le mail indiqué existe déjà. Le compte n'a donc pas été créé.", Messagehandler::ALERT_DANGER);
		}
		
		if (!$error) {
			// Création du compte
			if (Compte::create($firstName, $lastName, $email, $login, $pw, $organisme)) {
				Messagehandler::prepareAlert("Compte créé !", Messagehandler::ALERT_SUCCESS);

				// Mail de confirmation de la création de compte
				Helper::sendMail(
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
				Messagehandler::prepareAlert("Le compte n'a pas pû être créé.", Messagehandler::ALERT_DANGER);
			}
		}

		$data = array();
		$this->title('Confirmation création');
		$this->content(View::forge('compte/creation_confirmation', $data));
	}
	
	/** Page admin uniquement : permet de créer le compte */
	public function action_deconnexion() {
		Compte::disconnect();
		Response::redirect("accueil");
	}

}
