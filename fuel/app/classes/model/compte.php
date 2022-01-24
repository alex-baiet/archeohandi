<?php

namespace Model;

use Fuel\Core\Cookie;
use Fuel\Core\DB;
use Fuel\Core\Response;

class Compte {
	public const PERM_ADMIN = "admin";
	public const PERM_WRITE = "write";

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

	private function __construct(array $data) {
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

	public static function emailExist(string $email): bool {
		// Test un compte existe déjà ?
		$results = DB::select()->from("compte")->where("email", "=", $email)->execute()->as_array();
		return !empty($results);
	}

	#region Création de compte
	/** Créer un nouveau compte dans la BDD. */
	public static function create(string $firstName, string $lastName, string $email, &$newLogin, &$newPassword): bool {
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
	private static function generatePassword(): string {
		$pw = "";
		for ($i = 0; $i < 8; $i++) {
			$pw .= Compte::ALLOWED_PASSWORD[rand(0, strlen(Compte::ALLOWED_PASSWORD) - 1)];
		}
		return $pw;
	}

	/** Génère un nouveau login unique. */
	private static function generateLogin(string $firstName, string $lastName): string {
		$login = strtolower($firstName[0] . $lastName);
		$login = str_replace(array(" ", "-", "'"), "", $login);

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

	/**
	 * Connecte l'utilisateur au compte correspondant.
	 */
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

	public static function disconnect(): void {
		Cookie::delete("login");
		Cookie::delete("mdp");
		if (isset(Compte::$instance)) unset(Compte::$instance);
	}

	/** Vérifie que l'utilisateur a les permissions indiqués, et redirige vers l'accueil en cas de problème. */
	public static function checkPermission(?string $requiredPermission = null) {
		// Aucune contrainte demandé
		if ($requiredPermission === null) return;
		
		// Connection requise mais pas de compte : redirection
		$instance = Compte::getInstance();
		if ($instance === null) Compte::checkPermError("La page que vous vouliez accéder nécessite de se connecter à un compte.");

		// Utilisateur et permission admin
		if ($instance->getPermission() === Compte::PERM_ADMIN) return;
		if ($requiredPermission === Compte::PERM_ADMIN) Compte::checkPermError("La page que vous vouliez accéder est réservé aux administrateurs.");

		// Utilisateur et permission write
		if ($instance->getPermission() === Compte::PERM_WRITE && $requiredPermission === Compte::PERM_WRITE) return;

		Compte::checkPermError("Une erreur est servenu lors de la vérification des droits d'accès à la page.");
	}

	private static function checkPermError(string $msg) {
		Messagehandler::prepareAlert($msg, "danger");
		Redirect::redirectBack();
	}

	public function getLogin(): string { return $this->login; }
	public function getPermission(): ?string { return $this->permission; }
	public function getPrenom(): ?string { return $this->prenom; }
	public function getNom(): ?string { return $this->nom; }
	public function getEmail(): ?string { return $this->email; }

}