<?php

namespace Model\Db;

use Fuel\Core\DB;
use Fuel\Core\Model;
use InvalidArgumentException;
use Model\Db\Compte;
use Model\Helper;
use Model\Messagehandler;
use Model\Validation;

/** Représentation de la table "operations" de la BDD. */
class Operation extends Model {
	#region Values
	private ?int $id = null;
	private string $idUser = "";
	private string $aRevoir = "";
	private ?int $annee = null;
	private ?int $idCommune = null;
	private string $adresse = "";
	private ?float $longitude = null;
	private ?float $latitude = null;
	private ?int $idOrganisme = null;
	private ?int $idTypeOp = null;
	private string $ea = "";
	private string $oa = "";
	private string $patriarche = "";
	private string $numeroOperation = "";
	private string $arretePrescription = "";
	private string $bibliographie = "";
	private ?string $dateAjout = null;
	private bool $complet = false;

	/** @var string[]|null */
	private ?array $responsables = null;
	/** @var string[]|null */
	private ?array $anthropologues = null;
	/** @var string[]|null */
	private ?array $paleopathologistes = null;
	/** @var Commune|null */
	private $commune = null;
	/** @var Typeoperation|null */
	private $typeOp = null;
	/** @var Organisme|null */
	private $organisme = null;
	/** @var Sujethandicape[]|unset */
	private array $subjects;
	/** @var Compte|null|unset */
	private ?Compte $accountAdmin;
	/** @var Compte[]|unset */
	private array $accounts;
	/** @var string[]|unset */
	private array $urlsImg;
	/** @var int[]|null Nombre de cas observables pour chaque diagnostics. */
	private ?array $observables = null;
	/** @var string[]|null */
	private ?array $urls = null;

	private Validation $validation;
	#endregion

	/** Construit l'Operation depuis la liste des données. */
	public function __construct(array $data) {
		$this->validation = new Validation();
		$this->mergeValues($data);
	}

	/** Ajoute les données de l'array donnée à l'objet. Pratique pour les POST et GET. */
	public function mergeValues(array $data) {
		$this->validation->resetValidation();
		
		// Suppression des valeurs obsolètes
		$this->commune = null;
		$this->typeOp = null;
		$this->organisme = null;
		unset($this->anthropologues);
		unset($this->paleopathologistes);

		// Fusion des valeurs
		Archeo::mergeValue($this->id, $data, "id", "int", true);
		Archeo::mergeValue($this->id, $data, "id_operation", "int", true);
		Archeo::mergeValue($this->idUser, $data, "id_user");
		Archeo::mergeValue($this->aRevoir, $data, "a_revoir");
		Archeo::mergeValue($this->annee, $data, "annee", "int", true);
		Archeo::mergeValue($this->idCommune, $data, "id_commune", "int");
		Archeo::mergeValue($this->adresse, $data, "adresse");
		Archeo::mergeValue($this->longitude, $data, "longitude", "float", true);
		Archeo::mergeValue($this->latitude, $data, "latitude", "float", true);
		Archeo::mergeValue($this->idOrganisme, $data, "id_organisme", "int");
		Archeo::mergeValue($this->idTypeOp, $data, "id_type_op", "int");
		Archeo::mergeValue($this->ea, $data, "ea");
		Archeo::mergeValue($this->oa, $data, "oa");
		Archeo::mergeValue($this->patriarche, $data, "patriarche");
		Archeo::mergeValue($this->numeroOperation, $data, "numero_operation");
		Archeo::mergeValue($this->arretePrescription, $data, "arrete_prescription");
		Archeo::mergeValue($this->bibliographie, $data, "bibliographie");
		Archeo::mergeValue($this->dateAjout, $data, "date_ajout", "string", true);
		Archeo::mergeValue($this->complet, $data, "complet", "bool");
		if (isset($data["responsable"])) $this->responsables = array($data["responsable"]);
		Archeo::mergeValue($this->responsables, $data, "responsables", "array");
		Archeo::mergeValue($this->anthropologues, $data, "anthropologues", "array");
		Archeo::mergeValue($this->paleopathologistes, $data, "paleopathologistes", "array");

		if (isset($data["compte"])) {
			$this->accounts = array();
			foreach ($data["compte"] as $login) {
				$account = Compte::fetchSingle($login);
				if ($account !== null) $this->accounts[$login] = $account;
			}
		}

		if (isset($data["organisme"])) {
			$this->organisme = Organisme::fetchSingleFromName($data["organisme"], false);
			$this->idOrganisme = $this->organisme !== null ? $this->organisme->getId() : null;
		}

		if (isset($data["commune"]) && isset($data["departement"])) {
			$this->idCommune = Commune::nameToId($data["commune"].', '.$data["departement"]);
		}

		// Récupération des urls d'images
		if (isset($data["urls_img"])) {
			$this->setUrlsImg($data["urls_img"]);
		}

		if (isset($data["urls"])) $this->urls = array_filter($data["urls"]);
	}

	/**
	 * Récupère l'opération correspondant à l'id.
	 * 
	 * @param int $id Identifiant de l'opération.
	 * @return Operation|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM operation WHERE id=$id");
		if ($res === null) return null;

		$obj = new Operation($res);
		return $obj;
	}

	/**
	 * Récupère TOUTES les opérations.
	 * @return Operation[]
	 */
	public static function fetchAll() {
		$results = Helper::querySelect("SELECT * FROM operation");
		$operations = array();
		foreach ($results as $res) {
			$newOp = new Operation($res);
			$operations[$newOp->getId()] = $newOp;
		}
		// Helper::varDump($operations, true);
		return $operations;
	}

	/**
	 * Supprime une opération.
	 * @param int $id Identifiant de l'opération à supprimer.
	 * @return string Message d'erreur en cas d'échec de la suppression.
	 * @return null Si tout s'est bien passé.
	 */
	public static function deleteOnDB(int $id): ?string {
		$op = Operation::fetchSingle($id);
		if ($op === null) return "L'opération à supprimer n'existe pas";

		// Deletion
		foreach ($op->getSubjects() as $subject) {
			$result = Sujethandicape::deleteOnDB($subject->getId());
			if ($result !== null) return "$result (sujet n°{$subject->getId()})";
		}

		$result = DB::delete("operation")->where("id", "=", $id)->execute();
		if ($result < 1) return "L'opération n'a pas pû être supprimé";

		Personne::deleteOperationNames($id);

		DB::delete("droit_compte")->where("id_operation", "=", $id)->execute();
		DB::delete("operation_image")->where("id_operation", "=", $id)->execute();
		DB::delete("observable")->where("id_operation", "=", $id)->execute();
		DB::delete("url_operation")->where("id_operation", "=", $id)->execute();

		// Tous s'est bien passé
		return null;
	}

	public static function generateSelect(string $field = "id_operation", string $label = "Opération", $idSelected = "", bool $formFloating = true) {
		$valueRecover = function ($data) { return $data["id"]; };
		$textRecover = function ($data) {
			$value = new Operation($data);
			return $value->getNomOp();
		};
		return Archeo::generateSelect($field, $label, $idSelected, "operation", $valueRecover, $textRecover, $formFloating);
	}

	#region Getters
	public function getId() { return $this->id; }
	public function getIdUser() { return $this->idUser; }
	public function getARevoir() { return $this->aRevoir; }
	public function getAnnee() { return $this->annee; }
	public function getIdCommune() { return $this->idCommune; }
	public function getAdresse() { return $this->adresse; }
	public function getLongitude() { return $this->longitude; }
	public function getLatitude() { return $this->latitude; }
	public function getIdOrganisme() { return $this->idOrganisme; }
	public function getIdTypeOp() { return $this->idTypeOp; }
	public function getEA() { return $this->ea; }
	public function getOA() { return $this->oa; }
	public function getPatriarche() { return $this->patriarche; }
	public function getNumeroOperation() { return $this->numeroOperation; }
	public function getArretePrescription() { return $this->arretePrescription; }
	public function getBibliographie() { return $this->bibliographie; }
	public function getDateAjout() { return $this->dateAjout; }
	public function getComplet() { return $this->complet; }
	public function getInsee() { return $this->insee; }

	/** Créer un nom pour l'opération */
	public function getNomOp(): string {
		return ($this->getCommune() !== null ? $this->getCommune()->getNom() : "")
			.(!empty($this->adresse) ? ", {$this->adresse}" : "");
	}

	public function getCommune(): ?Commune {
		if ($this->idCommune === null) return null;
		if ($this->commune === null) $this->commune = Commune::fetchSingle($this->idCommune);
		return $this->commune;
	}

	public function getTypeOperation(): ?Typeoperation {
		if ($this->idTypeOp === null) return null;
		if ($this->typeOp === null) $this->typeOp = Typeoperation::fetchSingle($this->idTypeOp);
		return $this->typeOp;
	}

	public function getOrganisme(): ?Organisme {
		if ($this->idOrganisme === null) return null;
		if ($this->organisme === null) $this->organisme = Organisme::fetchSingle($this->idOrganisme);
		return $this->organisme;
	}

	public function getResponsables(): array {
		if ($this->responsables === null) {
			if ($this->getId() === null) $this->responsables = array();
			else $this->responsables = Personne::fetchAllOperation($this->getId(), "etre_responsable");
		}
		return $this->responsables;
	}

	public function getResponsable(): ?string {
		$arr = $this->getResponsables();
		return !empty($arr[0]) ? $arr[0] : null;
	}

	/** @return string[] */
	public function getAnthropologues(): array {
		if ($this->anthropologues === null) {
			if ($this->getId() === null) $this->anthropologues = array();
			else $this->anthropologues = Personne::fetchAllOperation($this->getId(), "etre_anthropologue");
		}
		return $this->anthropologues;
	}

	/** @return string[] */
	public function getPaleopathologistes(): array {
		if ($this->paleopathologistes === null) {
			if ($this->getId() === null) $this->paleopathologistes = array();
			else $this->paleopathologistes = Personne::fetchAllOperation($this->getId(), "etre_paleopathologiste");
		}
		return $this->paleopathologistes;
	}

	/** @return Sujethandicape[] Liste de tous les sujets de l'opération. */
	public function getSubjects(): array {
		if (isset($this->subjects)) return $this->subjects;
		
		// Récupération des groupe
		$idGroups = Helper::querySelectList('SELECT id FROM groupe WHERE id_operation=' . $this->getId());

		// Récupération de tous les sujets handicapé des différents groupes
		$this->subjects = array();
		foreach ($idGroups as $idGroup) {
			$result = Helper::querySelectSingle('SELECT * FROM sujet_handicape WHERE id_groupe=' . $idGroup);
			if ($result !== null) {
				$subject = new Sujethandicape($result);
				$this->subjects[$subject->getId()] = $subject;
			}
		}

		return $this->subjects;
	}

	public function getSubjectsCount(): int { return count($this->getSubjects()); }

	public function getAccountAdmin(): ?Compte {
		if (!isset($this->accountAdmin)) {
			$results = DB::select("login_compte")
				->from("droit_compte")
				->where("id_operation", "=", $this->id)
				->where("droit", "=", Compte::PERM_ADMIN)
				->execute()
				->as_array();
			if (empty($results)) {
				$this->accountAdmin = null;
			} else {
				$this->accountAdmin = Compte::fetchSingle($results[0]["login_compte"]);
			}
		}
		return $this->accountAdmin;
	}

	/** @return Compte[] */
	public function getAccounts(): array {
		if (!isset($this->accounts)) {
			$this->accounts = array();
			$results = DB::select("login_compte")
				->from("droit_compte")
				->where("id_operation", "=", $this->id)
				->where("droit", "=", Compte::PERM_WRITE)
				->execute()
				->as_array();
			foreach ($results as $res) {
				$acc = Compte::fetchSingle($res["login_compte"]);
				if ($acc !== null) $this->accounts[$acc->getLogin()] = $acc;
			}
		}
		return $this->accounts;
	}

	/** Liste des liens d'image attaché à l'opération. */
	public function getUrlsImg(): array {
		if (!isset($this->urlsImg)) {
			if ($this->getId() === null) return array();
			$this->urlsImg = Helper::querySelectList("SELECT url_img FROM operation_image WHERE id_operation={$this->getId()}");
		}
		return $this->urlsImg;
	}

	/** Liste des nombre de cas observables pour chaque diagnostic. */
	public function getObservables(): array {
		if ($this->observables === null) {
			$this->observables = array();
			if ($this->getId() !== null) {
				$results = DB::select()->from("observable")->where("id_operation", "=", $this->getId())->execute()->as_array();
				foreach ($results as $res) {
					$this->observables[intval($res["id_diagnostic"])] = $res["nb_cas"];
				}
			}
		}
		return $this->observables;
	}

	/** Nombre de cas observables pour le diagnostic donné. */
	public function getObservable(int $idDiagnostic): int {
		$observables = $this->getObservables();
		if (!isset($observables[$idDiagnostic])) return 0;
		return $observables[$idDiagnostic];
	}

	/** Liste des liens de site pour compléter la bibliographie. */
	public function getUrls() {
		if ($this->urls === null) {
			$this->urls = array();
			if ($this->getId() !== null) {
				$results = DB::select()->from("url_operation")->where("id_operation", "=", $this->getId())->execute()->as_array();
				foreach ($results as $res) {
					$this->urls[] = $res["url"];
				}
			}
		}
		return $this->urls;
	}
	#endregion

	#region Setters
	public function setId(int $value) { $this->id = $value; }
	public function setIdUser(string $value) { $this->idUser = $value; }
	public function setARevoir(string $value) { $this->aRevoir = $value; }
	public function setAnnee(?int $value) { $this->annee = $value; }
	public function setAdresse(string $value) { $this->adresse = $value; }
	public function setLongitude(?float $value) { $this->longitude = $value; }
	public function setLatitude(?float $value) { $this->latitude = $value; }
	public function setEa(string $value) { $this->ea = $value; }
	public function setOa(string $value) { $this->oa = $value; }
	public function setPatriarche(string $value) { $this->patriarche = $value; }
	public function setNumeroOperation(string $value) { $this->numeroOperation = $value; }
	public function setArretePrescription(string $value) { $this->arretePrescription = $value; }
	public function setBibliographie(string $value) { $this->bibliographie = $value; }

	public function setIdCommune(int $value) {
		$this->idCommune = $value;
		$this->commune = null;
	}

	public function setIdOrganisme(int $value) {
		$this->idOrganisme = $value;
		$this->organisme = null;
	}

	public function setIdTypeOperation(int $value) {
		$this->idTypeOp = $value;
		$this->typeOp = null;
	}

	private function setUrlsImg(array $urls) {
		$this->urlsImg = array_unique(array_filter($urls));
	}

	/**
	 * Change le nombre de cas observable pour chaque diagnostic.
	 * @param int[] $data Au format "id diagnostic" => "nombre".
	 */
	public function setObservables(array $data) {
		foreach ($data as $idDia => $count) {
			$this->setObservable($idDia, $count);
		}
	}

	/** Change le nombre de cas observable pour un diagnostic. */
	public function setObservable(int $idDiagnostic, int $number) {
		if ($number < 0) throw new InvalidArgumentException("\"number\" doit être positif (valeur actuel : $number).");
		if ($this->observables === null) $this->getObservables();
		$this->observables[$idDiagnostic] = $number;
	}
	#endregion

	/**
	 * Renvoie les droits qu'à un compte sur l'opération.
	 * @return string Niveau de droit du compte sur l'opération.
	 * @return null Si le compte n'a aucun droit sur l'opération.
	 */
	public function accountRights(string $login): ?string {
		if ($this->getAccountAdmin() !== null && $this->getAccountAdmin()->getLogin() === $login) return Compte::PERM_ADMIN;
		if (isset($this->getAccounts()[$login])) return Compte::PERM_WRITE;
		return null;
	}

	#region Validation
	/** True si toutes les données sont prêtes à être ajoutées à la bdd. */
	public function validate(): bool {
		return $this->validation->validate(function () {
			$validation = $this->validation;

			// Tests de validation des données
			$this->adresse = Helper::secureString($this->adresse);

			if ($this->annee !== null && !Helper::stringIsInt($this->annee)) $validation->invalidate("L'année indiquée doit être un nombre.");
			if ($this->getCommune() === null) $validation->invalidate("La commune n'existe pas.");
			if ($this->getOrganisme() === null) $validation->invalidate("L'organisme n'existe pas.");
			if ($this->getTypeOperation() === null) $validation->invalidate("Le type d'opération n'existe pas.");
			$this->aRevoir = Helper::secureString($this->aRevoir);

			// Correction bibliographie
			$this->bibliographie = Helper::secureString($this->bibliographie);

			// Vérification des comptes
			if (Compte::getInstance() === null) $validation->invalidate("Vous devez être connecter à un compte pour pouvoir créer/modifier une opération.");
			else {
				if (!isset($this->accounts)) {
					$this->getAccounts();
				}
				$admin = $this->getAccountAdmin();
				if ($admin === null) $admin = Compte::getInstance();
				if (isset($this->accounts[$admin->getLogin()])) unset($this->accounts[$admin->getLogin()]);
			}
		});
	}

	/**
	 * Ajoute / met à jour l'opération dans la base de données.
	 * @return bool Indique le succès de l'ajout.
	 */
	public function saveOnDB(): bool {
		$this->validation->resetValidation();
		// Validation des données
		if (!$this->validate()) return false;

		// Préparation des valeurs à envoyer à la BDD
		$arr = $this->toArray();

		if ($this->id === null || Operation::fetchSingle($this->id) === null) {
			if (Compte::getInstance() === null) {
				Messagehandler::prepareAlert("Pour créer une opération, vous devez être connecter à un compte.", Messagehandler::ALERT_DANGER);
				return false;
			}

			// L'opération n'existe pas : on la rajoute à la BDD
			$arr["id"] = null;
			$arr["date_ajout"] = DB::expr("CURRENT_DATE()");
			list($insertId, $rowAffected) = DB::insert("operation")
				->set($arr)
				->execute();
			$this->id = $insertId;

			if ($rowAffected < 1) return false;

			// Ajout créateur de l'opération en tant qu'admin de l'operation
			$admin = Compte::getInstance();
			$this->addAccount($admin->getLogin(), Compte::PERM_ADMIN);
		}
		else {
			// L'opération existe : on la met à jour
			$rowAffected = DB::update("operation")
				->set($arr)
				->where("id", $this->id)
				->execute();
		}

		// Maj des personnes
		$ros = $this->getResponsables();
		$paleos = $this->getPaleopathologistes();
		$anthros = $this->getAnthropologues();
		Personne::deleteOperationNames($this->getId());
		Personne::saveOperationNames($this->getId(), "etre_responsable", $ros);
		Personne::saveOperationNames($this->getId(), "etre_paleopathologiste", $paleos);
		Personne::saveOperationNames($this->getId(), "etre_anthropologue", $anthros);

		// Maj droits des comptes
		if (isset($this->accounts)) {
			DB::delete("droit_compte")->where("id_operation", "=", $this->id)->where("droit", "!=", Compte::PERM_ADMIN)->execute();
			foreach ($this->accounts as $acc) {
				$this->addAccount($acc->getLogin(), Compte::PERM_WRITE);
			}
		}

		// Maj des images
		Archeo::updateOnDB(
			"operation_image",
			"id_operation={$this->getId()}",
			$this->getUrlsImg(),
			function (string $url) { return array(
				"id_operation" => $this->getId(),
				"url_img" => $url
			); }
		);

		// Maj nombres cas observable
		$observables = $this->getObservables();
		DB::delete("observable")->where("id_operation", "=", $this->getId())->execute();
		foreach ($observables as $idDiagnostic => $count) {
			if ($count > 0) {
				DB::insert("observable")->set(array(
					"id_operation" => $this->getId(),
					"id_diagnostic" => $idDiagnostic,
					"nb_cas" => $count
				))->execute();
			}
		}

		// Maj des urls
		Archeo::updateOnDB(
			"url_operation",
			"id_operation={$this->getId()}",
			$this->getUrls(),
			function (string $url) { return array(
				"id_operation" => $this->getId(),
				"url" => $url
			); }
		);

		// Tout s'est bien passé.
		return true;
	}

	/** Ajoute un compte pour l'opération dans la BDD. */
	private function addAccount(string $login, string $droit) {
		DB::insert("droit_compte")->set(array(
			"id_operation" => $this->id,
			"login_compte" => $login,
			"droit" => $droit
		))->execute();
	}

	/** Affiche une alert bootstrap seulement si des erreurs existent. */
	public function echoErrors() { $this->validation->echoErrors(); }
	#endregion

	/** Renvoie le nombre de sujet ayant le diangostic donné. */
	public function countConcernedSubject(int $idDiagnosis): int {
		$subjects = $this->getSubjects();
		$counter = 0;
		foreach ($subjects as $sub) {
			if ($sub->hasDiagnosis($idDiagnosis)) $counter++;
		}
		return $counter;
	}

	/** Calcul le ration de personne concerné par le diagnostic */
	public function prevalence(int $idDiagnosis): float {
		$count = $this->countConcernedSubject($idDiagnosis);
		if ($count === 0) return 0;
		$total = $this->getObservable($idDiagnosis);
		if ($total === 0) return 1;
		$result = $count / $total;
		if ($result > 1) return 1;
		return $result;
	}

	/** Renvoie l'array des données représentant l'objet. */
	public function toArray(): array {
		return array(
			"id" => $this->id,
			"id_user" => $this->idUser,
			"a_revoir" => $this->aRevoir,
			"annee" => $this->annee,
			"id_commune" => $this->idCommune,
			"adresse" => $this->adresse,
			"longitude" => $this->longitude,
			"latitude" => $this->latitude,
			"id_organisme" => $this->idOrganisme,
			"id_type_op" => $this->idTypeOp,
			"ea" => $this->ea,
			"oa" => $this->oa,
			"patriarche" => $this->patriarche,
			"numero_operation" => $this->numeroOperation,
			"arrete_prescription" => $this->arretePrescription,
			"bibliographie" => $this->bibliographie,
			"complet" => $this->complet
		);
	}

}
