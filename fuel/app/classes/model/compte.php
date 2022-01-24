<?php

namespace Model;

use Fuel\Core\Cookie;
use Fuel\Core\DB;

class Compte {
	public const PERM_ADMIN = "admin";
	public const PERM_WRITE = "write";

	/** Durée de vie de la connexion en secondes. */
	private const CONNECT_LIFE = 300;

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
	
	// private static function fetchSingle(string $login): ?Compte {
	// 	if (empty($login)) return null;
	// 	$res = Helper::querySelectSingle("SELECT * FROM compte WHERE login='$login';");
	// 	if ($res === null) return null;
	// 	return new Compte($res);
	// }

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

	/** Créer un nouveau compte dans la BDD. */
	public static function create(string $firstName, string $lastName, string $email) {

	}

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

	public function getLogin(): string { return $this->login; }
	public function getPermission(): ?string { return $this->permission; }
	public function getPrenom(): ?string { return $this->prenom; }
	public function getNom(): ?string { return $this->nom; }
	public function getEmail(): ?string { return $this->email; }

}