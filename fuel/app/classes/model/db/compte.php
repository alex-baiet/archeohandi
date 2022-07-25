<?php

namespace Model\Db;

use Fuel\Core\Cookie;
use Fuel\Core\DB;
use Fuel\Core\Response;
use Model\Helper;
use Model\Messagehandler;
use Model\Redirect;

/** Contient des fonctions de gestions des droits, et représentation de la table "compte" de la BDD. */
class Compte {
	public const PERM_ADMIN = "admin";
	public const PERM_WRITE = "write";
	public const PERM_DISCONNECTED = "disconnected";

	/** Liste de tous les charactères pouvant être généré dans un mot de passe. */
	private const ALLOWED_PASSWORD = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	/** Durée de vie de la connexion en secondes. */
	private const CONNECT_LIFE = 60 * 60 * 24 * 7; // une semaine

	private static ?Compte $instance;

	private ?string $login = null;
	private ?string $mdp = null;
	private ?string $permission = null;
	private ?string $prenom = null;
	private ?string $nom = null;
	private ?string $email = null;

	public function __construct(array $data) {
		Archeo::mergeValue($this->login, $data, "login");
		Archeo::mergeValue($this->mdp, $data, "mdp");
		Archeo::mergeValue($this->permission, $data, "permission");
		Archeo::mergeValue($this->prenom, $data, "prenom");
		Archeo::mergeValue($this->nom, $data, "nom");
		Archeo::mergeValue($this->email, $data, "email");
	}
	
	/** Permet de récupérer le compte avec lequel l'utilisateur est actuellement connecté. */
	public static function getInstance(): ?Compte {
		if (!isset(Compte::$instance)) {
			if (!empty(Cookie::get("login")) && !empty(Cookie::get("mdp"))) {
				if (!Compte::connect(Cookie::get("login"), Cookie::get("mdp"), true)) {
					Compte::$instance = null;
				}
			} else {
				Compte::$instance = null;
			}
		}
		return Compte::$instance;
	}

	/** Récupère le Compte correspondant au login depuis la bdd. */
	public static function fetchSingle(string $login): ?Compte {
		$res = Helper::querySelectSingle("SELECT * FROM compte WHERE login=\"$login\";");
		if ($res === null) return null;
		return new Compte($res);
	}

	/** True si un compte avec l'email donné existe déjà dans la bdd. */
	public static function emailExist(string $email): bool {
		// Test un compte existe déjà ?
		$results = DB::select()->from("compte")->where("email", "=", $email)->execute()->as_array();
		return !empty($results);
	}

	#region Création de compte
	/**
	 * Créer un nouveau compte dans la BDD.
	 * 
	 * @param string $firstName 
	 * @param string $lastName
	 * @param string $email
	 * @param string &$newLogin Login fraîchement généré pour le compte.
	 * @param string &$newPassword Nouveau mot de passe généré du compte.
	 * @param string $organisme 
	 */
	public static function create(string $firstName, string $lastName, string $email, ?string &$newLogin, ?string &$newPassword, string $organisme): bool {
		if (Compte::emailExist($email)) return false;

		$login = Compte::generateLogin($firstName, $lastName);
		$pw = Compte::generatePassword();

		list($insertId, $rowAffected) = DB::insert("compte")
			->set(array(
				"login" => $login,
				"mdp" => md5($pw),
				"permission" => Compte::PERM_WRITE,
				"prenom" => $firstName,
				"nom" => $lastName,
				"email" => $email,
				"organisme" => $organisme,
			))
			->execute();

		if ($rowAffected > 0) {
			$newLogin = $login;
			$newPassword = $pw;
			return true;
		} else {
			return false;
		}
	}
	
	/** Génère un mot de passe aléatoire. */
	public static function generatePassword(): string {
		$pw = "";
		for ($i = 0; $i < 8; $i++) {
			$pw .= Compte::ALLOWED_PASSWORD[rand(0, strlen(Compte::ALLOWED_PASSWORD) - 1)];
		}
		return $pw;
	}

	/** Génère un nouveau login unique. */
	public static function generateLogin(string $firstName, string $lastName): string {
		$login = strtolower(Helper::removeNonAlphabet(Helper::replaceAccent($firstName[0] . $lastName)));

		$accounts = DB::select()->from("compte")->where("login", "LIKE", "$login%")->execute()->as_array();
		if (count($accounts) > 0) {
			for ($i=2; $i < PHP_INT_MAX; $i++) {
				$contains = false;
				foreach ($accounts as $accArr) {
					$acc = new Compte($accArr);
					if ($acc->getLogin() === $login . $i) {
						$contains = true;
						break;
					}
				}
				if (!$contains) {
					$login .= $i;
					break;
				}
			}
		}

		return $login;
	}
	#endregion

	/** Connecte l'utilisateur au compte correspondant. */
	public static function connect(string $login, string $password, bool $isEncrypted = false): bool {
		$encryptPass = $isEncrypted ? $password : md5($password);

		$result = DB::select()
			->from("compte")
			->where("login", "=", $login)
			->where("mdp", "=", $encryptPass)
			->execute()
			->as_array();

		if (!empty($result)) {
			$account = new Compte($result[0]);
			Compte::$instance = $account;
			Cookie::set("login", $account->login, Compte::CONNECT_LIFE);
			Cookie::set("mdp", $account->mdp, Compte::CONNECT_LIFE);

			return true;
		}

		return false;
	}

	/** Déconnecte l'utilisateur de son compte. */
	public static function disconnect(): void {
		Cookie::delete("login");
		Cookie::delete("mdp");
		if (isset(Compte::$instance)) unset(Compte::$instance);
	}

	/**
	 * Change le mot de passe pour le compte indiqué.
	 * @return string Nouveau mot de passe généré.
	 * @return null Si le compte n'existe pas.
	 */
	public static function redefinePassword(string $login): ?string {
		if (Compte::fetchSingle($login) === null) return null;

		$pw = Compte::generatePassword();
		$result = DB::update("compte")->set(array("mdp" => md5($pw)))->where("login", "=", $login)->execute();
		return $pw;
	}

	/**
	 * Vérifie que l'utilisateur a les permissions indiqués, et redirige vers la page précédente si les droits sont insuffisant.
	 */
	public static function checkPermissionRedirect(string $errorMsg, string $permission, ?int $idOperation = null) {
		Compte::checkTestRedirect($errorMsg, Compte::checkPermission($permission, $idOperation));
	}

	/**
	 * Redirige vers la page précédente si le test est négatif.
	 */
	public static function checkTestRedirect(string $errorMsg, string $bool) {
		if (!$bool) {
			if (Compte::getInstance() === null) {
				// Redirection vers la page de connexion
				Messagehandler::prepareAlert($errorMsg, Messagehandler::ALERT_DANGER);
				Response::redirect("/compte/connexion");
			} else {
				// Redirection vers la page précédente
				Messagehandler::prepareAlert($errorMsg, Messagehandler::ALERT_DANGER);
				Redirect::redirectBack();
			}
		}
	}

	/**
	 * Vérifie que l'utilisateur a les permissions indiqués.
	 * 
	 * @param string $requiredPermission Indique la permission nécessaire pour accéder à la page.
	 * Les différentes permissions sont toutes les constants "PERM_" présents dans Compte.
	 * @param int|null $idOperation Indique l'opération auxquel l'utilisateur doit avoir les droits.
	 * @return true Si la permission est valide.
	 */
	public static function checkPermission(string $permission, ?int $idOperation = null): bool {

		// Aucune contrainte demandé
		if ($permission === null) return true;
		$account = Compte::getInstance();
		// if ($account !== null && $account->getPermission() === Compte::PERM_ADMIN) return true;

		$op = $idOperation !== null ? Operation::fetchSingle($idOperation) : null;

		switch ($permission) {
			case Compte::PERM_DISCONNECTED:
				if ($account !== null) return false;
				return true;

			case Compte::PERM_WRITE:
				if ($account === null) return false;
				if ($account->getPermission() === Compte::PERM_ADMIN) return true;
				if ($op !== null
					&& $account->getPermission() === Compte::PERM_WRITE
					&& $op->accountRights($account->getLogin()) === null
				) {
					return false;
				}
				return true;

			case Compte::PERM_ADMIN:
				if ($account === null) return false;
				if ($account->getPermission() === Compte::PERM_ADMIN) return true;
				if ($op === null && $account->getPermission() !== Compte::PERM_ADMIN) return false;

				if ($op !== null) {
					return $account->getPermission() === Compte::PERM_WRITE
						&& $op->accountRights($account->getLogin()) === Compte::PERM_ADMIN;
				}
				break;

			default;
				return false;
		}

		return false;
	}

	public function getLogin(): ?string { return $this->login; }
	public function getPermission(): ?string { return $this->permission; }
	public function getPrenom(): ?string { return $this->prenom; }
	public function getNom(): ?string { return $this->nom; }
	public function getEmail(): ?string { return $this->email; }
}