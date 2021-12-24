<?php

namespace Model;

use Fuel\Core\DB;
use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Operation extends Model {
	#region Values
	private int $idSite = -1;
	private string $idUser = "";
	private string $aRevoir = "";
	private int $annee = 0;
	private int $idCommune = -1;
	private string $adresse = "";
	private float $x = 0.0;
	private float $y = 0.0;
	private int $idOrganisme = -1;
	private int $idTypeOp = -1;
	private string $EA = "";
	private string $OA = "";
	private string $patriarche = "";
	private string $numeroOperation = "";
	private string $arretePrescription = "";
	private int $idResponsableOp = -1;
	private string $bibliographie = "";

	/** @var string[]|null */
	private $idAnthropologues = null;
	/** @var string[]|null */
	private $idPaleopathologistes = null;

	/** @var Commune|null */
	private $commune = null;
	/** @var Typeoperation|null */
	private $typeOp = null;
	/** @var Organisme|null */
	private $organisme = null;
	/** @var Personne|null */
	private $responsableOp = null;
	/** @var Personne[]|null */
	private $anthropologues = null;
	/** @var Personne[]|null */
	private $paleopathologistes = null;

	/**
	 * Indique que l'objet est valide pour la base de données.
	 * undefined signifie que l'objet n'a pas encore été vérifié.
	 * @var bool|null 
	 */
	private $validated = null;
	/**
	 * Message indiquant pourquoi l'objet n'est pas valide.
	 * @var string|null
	 */
	private $invalidReason = null;
	#endregion

	/** Construit l'Operation depuis la liste des données. */
	public function __construct(array $data) {
		$this->mergeValues($data);
	}

	/** Ajoute les données de l'array donnée à l'objet. Pratique pour les POST et GET. */
	public function mergeValues(array $data) {
		$this->resetValidation();
		
		// Suppression des valeurs obsolètes
		$this->commune = null;
		$this->typeOp = null;
		$this->organisme = null;
		$this->responsableOp = null;
		$this->anthropologues = null;

		// Fusion des valeurs
		if (isset($data["id_site"])) $this->idSite = $data["id_site"];
		if (isset($data["id_user"])) $this->idUser = $data["id_user"];
		if (isset($data["a_revoir"])) $this->aRevoir = $data["a_revoir"];
		if (isset($data["annee"])) $this->annee = $data["annee"];
		if (isset($data["id_commune"])) $this->idCommune = intval($data["id_commune"]);
		if (isset($data["adresse"])) $this->adresse = $data["adresse"];
		if (isset($data["X"])) $this->x = $data["X"];
		if (isset($data["Y"])) $this->y = $data["Y"];
		if (isset($data["id_organisme"])) $this->idOrganisme = intval($data["id_organisme"]);
		if (isset($data["id_type_op"])) $this->idTypeOp = intval($data["id_type_op"]);
		if (isset($data["EA"])) $this->EA = $data["EA"];
		if (isset($data["OA"])) $this->OA = $data["OA"];
		if (isset($data["patriarche"])) $this->patriarche = $data["patriarche"];
		if (isset($data["numero_operation"])) $this->numeroOperation = $data["numero_operation"];
		if (isset($data["arrete_prescription"])) $this->arretePrescription = $data["arrete_prescription"];
		if (isset($data["id_responsable_op"])) $this->idResponsableOp = intval($data["id_responsable_op"]);
		if (isset($data["id_anthropologues[]"])) $this->idAnthropologues = $data["id_anthropologues[]"];
		if (isset($data["id_paleopathologiste[]"])) $this->paleopathologiste = $data["id_paleopathologiste[]"];
		if (isset($data["bibliographie"])) $this->bibliographie = $data["bibliographie"];

		if (isset($data["commune"])) $this->idCommune = Commune::nameToId($data["commune"]);
		if (isset($data["responsable_op"])) $this->idResponsableOp = Personne::nameToId($data["responsable_op"]);
		if (isset($data["anthropologues"])) $this->idAnthropologues = Personne::namesToIds($data["anthropologues"]);
		if (isset($data["paleopathologistes"])) $this->idPaleopathologistes = Personne::namesToIds($data["paleopathologistes"]);
	}

	/**
	 * Récupère l'opération correspondant à l'id.
	 * 
	 * @param int $id Identifiant de l'opération.
	 * @return Operation|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM operations WHERE id_site=$id");
		if ($res === null) return null;

		$obj = new Operation($res);
		return $obj;
	}

	/**
	 * Récupère TOUTES les opérations.
	 * @return Operation[]
	 */
	public static function fetchAll() {
		$results = Helper::querySelect("SELECT * FROM operations");
		$operations = array();
		foreach ($results as $res) {
			$operations[] = new Operation($res);
		}
		return $operations;
	}

	#region Getters
	public function getIdSite() { return $this->idSite; }
	public function getIdUser() { return $this->idUser; }
	public function getARevoir() { return $this->aRevoir; }
	public function getAnnee() { return $this->annee; }
	public function getIdCommune() { return $this->idCommune; }
	public function getAdresse() { return $this->adresse; }
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
	public function getIdOrganisme() { return $this->idOrganisme; }
	public function getIdTypeOp() { return $this->idTypeOp; }
	public function getEA() { return $this->EA; }
	public function getOA() { return $this->OA; }
	public function getPatriarche() { return $this->patriarche; }
	public function getNumeroOperation() { return $this->numeroOperation; }
	public function getArretePrescription() { return $this->arretePrescription; }
	public function getIdResponsableOp() { return $this->idResponsableOp; }
	public function getBibliographie() { return $this->bibliographie; }
	#endregion

	/** Créer un nom pour l'opération */
	public function getNomOp(): string { return "{$this->getCommune()->getNom()}, {$this->adresse}, {$this->annee}"; }
	/**
	 * @return Commune|null
	 */
	public function getCommune() {
		if ($this->commune === null) $this->commune = Commune::fetchSingle($this->idCommune);
		return $this->commune;
	}
	/**
	 * @return Typeoperation|null
	 */
	public function getTypeOperation() {
		if ($this->typeOp === null) $this->typeOp = Typeoperation::fetchSingle($this->idTypeOp);
		return $this->typeOp;
	}
	/**
	 * @return Organisme|null
	 */
	public function getOrganisme() {
		if ($this->organisme === null) $this->organisme = Organisme::fetchSingle($this->idOrganisme);
		return $this->organisme;
	}
	/**
	 * @return Personne|null
	 */
	public function getResponsableOp() {
		if ($this->responsableOp === null) $this->responsableOp = Personne::fetchSingle($this->idResponsableOp);
		return $this->responsableOp;
	}
	/**
	 * Renvoie une liste de personne en fonction des différents paramètres données.
	 * @param array|null &$ids Liste des identifiant définis.
	 * @param array|null &$people Liste des personnes déjà récupérées.
	 * @param array &$tableName Nom de la table où recherché en cas d'informations manquantes.
	 * @return Personne[]
	 */
	private function getPersonnes(&$ids, &$people, string $tableName) {
		// personnes déjà définis : on renvoie directement le résultat
		if ($people !== null) return $people;

		$people = array();

		// Récupération des personnes selon les id définis dans l'objet
		if ($ids !== null) {
			foreach ($ids as $id) {
				$person = Personne::fetchSingle($id);
				if ($person !== null) $people[] = $person;
			}
		}

		// Récupération des personnes déjà défini sur la BDD si aucun n'est donnée
		else {
			$ids = array();
			$results = Helper::querySelect("SELECT * FROM $tableName WHERE id_operation={$this->idSite};");
			foreach ($results as $res) {
				$person = Personne::fetchSingle($res["id_personne"]);
				if ($person !== null) {
					$ids[] = $person->getId();
					$people[] = $person;
				}
			}
		}
		return $people;
	}
	/** @return Personne[] */
	public function getAnthropologues() {
		return $this->getPersonnes($this->idAnthropologues, $this->anthropologues, "etre_anthropologue");
	}
	/** @return Personne[] */
	public function getPaleopathologistes() {
		return $this->getPersonnes($this->idPaleopathologistes, $this->paleopathologistes, "etre_paleopathologiste");
	}

	/**
	 * Vérifie que toutes les valeurs sont correctes.
	 * @return true|string Renvoie un string contenant un message d'erreurs en cas de test non passant, ou l'opération en cas de succès.
	 */
	public function validate() {
		// L'objet à déjà été validé : on retourne le résultat précédent.
		if ($this->validated !== null) {
			if ($this->validated) return true;
			else return $this->invalidReason;
		}

		// Tests de validation des données
		// Correction adresse
		$res = Helper::verif_alpha($this->adresse, 'alphatout');
		if ($res === false) $this->invalidate("L'adresse contient des caractères interdit.");
		$this->adresse = Helper::secureString($this->adresse);

		// Test année
		if (!Helper::stringIsInt($this->annee)) $this->invalidate("L'année indiquée doit être un nombre.");
		
		// Test position X
		if (!is_numeric($this->x)) $this->invalidate("La position sur x (longitude) indiquée doit être un nombre.");
		
		// Test position Y
		if (!is_numeric($this->y)) $this->invalidate("La position sur y (latitude) indiquée doit être un nombre.");

		// Test commune
		if ($this->getCommune() === null) $this->invalidate("La commune n'existe pas.");

		// Test organisme
		if ($this->getOrganisme() === null) $this->invalidate("L'organisation n'existe pas.");

		// Test type operation
		if ($this->getTypeOperation() === null) $this->invalidate("Le type d'opération n'existe pas.");

		// Correction aRevoir
		$this->aRevoir = Helper::secureString($this->aRevoir);

		// Test EA
		$res = Helper::verif_alpha($this->EA, 'alphanum');
		if ($res === false) $this->invalidate("La valeur \"EA\" contient des caractères interdit.");
		else $this->EA = $res;

		// Test OA
		$res = Helper::verif_alpha($this->OA, 'alphanum');
		if ($res === false) $this->invalidate("La valeur \"OA\" contient des caractères interdit.");
		else $this->OA = $res;

		// Test patriarche
		$res = Helper::verif_alpha($this->patriarche, 'alphanum');
		if ($res === false) $this->invalidate("Le patriarche contient des caractères interdit.");
		else $this->patriarche = $res;

		// Test numero operation
		$res = Helper::verif_alpha($this->numeroOperation, 'alphanum');
		if ($res === false) $this->invalidate("Le numéro d'opération contient des caractères interdit.");
		else $this->numeroOperation = $res;

		// Test arrete prescription
		$res = Helper::verif_alpha($this->arretePrescription, 'alphanum');
		if ($res === false) $this->invalidate("L'arrete de prescription contient des caractères interdit.");
		else $this->arretePrescription = $res;

		// Test responsable
		if ($this->getResponsableOp() === null) $this->invalidate("Le responsable n'existe pas.");

		// Test anthropologues
		// $res = Helper::verif_alpha($this->anthropologue, 'alpha');
		// if ($res === false) $this->invalidate("Le nom de l'anthropologue contient des caractères interdit.");
		// else $this->anthropologue = $res;

		// Test paleopathologistes
		// $res = Helper::verif_alpha($this->paleopathologiste, 'alpha');
		// if ($res === false) $this->invalidate("Le nom du paléopathologiste contient des caractères interdit.");
		// else $this->paleopathologiste = $res;

		// Correction bibliographie
		$this->bibliographie = Helper::secureString($this->bibliographie);

		// Vérification final
		if ($this->validated === false) {
			return $this->invalidReason;
		}

		// Les données sont conforme et sont validés.
		$this->validated = true;
		return true;
	}

	/**
	 * Ajoute / met à jour l'opération dans la base de données.
	 * Actuellement ne fait que la mise à jour et non l'ajout.
	 * 
	 * @return bool Indique le succès de l'ajout.
	 */
	public function saveOnDB(): bool {
		// Validation des données
		if ($this->validated == null) $this->validate();
		// Cas données non valide
		if (!$this->validated) return false;

		// Préparation des valeurs à envoyer à la BDD
		$arr = $this->toArray();
		unset($arr["id_anthropologue[]"]);
		unset($arr["id_paleopathologiste[]"]);

		if ($this->idSite === -1 || Operation::fetchSingle($this->idSite) === null) {
			// L'opération n'existe pas : on la rajoute à la BDD
			$arr["id_site"] = null;
			list($insertId, $rowAffected) = DB::insert("operations")
				->set($arr)
				->execute();
			$this->idSite = $insertId;
			if ($rowAffected < 1) return false;
		}
		else {
			// L'opération existe : on la met à jour
			$rowAffected = DB::update("operations")
				->set($arr)
				->where("id_site", $this->idSite)
				->execute();
				if ($rowAffected < 1) return false;
		}

		// Maj des anthropologues et paleopathologistes
		$this->savePeople($this->getAnthropologues(), "etre_anthropologue");
		$this->savePeople($this->getPaleopathologistes(), "etre_paleopathologiste");

		// Tout s'est bien passé.
		return true;
	}

	/** Facilite la sauvegarde des anthropologues et paleopathologistes */
	private function savePeople(array $people, string $table) {
		// Suppression des anciennes valeurs stockées
		DB::delete($table)->where("id_operation", $this->idSite)->execute();

		// Création des nouvelles valeurs
		$values = array();
		foreach ($people as $person) {
			$values[] = array($this->idSite, $person->getId());
		}

		// Ajoute les nouvelles valeurs
		if (count($values) > 0) DB::insert($table)->values($values)->execute();
	}

	/** Affiche une alert bootstrap seulement si des erreurs existent. */
	public function alertBootstrap(string $type) {
		if ($this->validate() !== true) {
			echo '
				<div class="alert alert-' . $type . ' alert-dismissible text-center my-2 fade show" role="alert">
					' . $this->invalidReason . '
					<button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
				</div>';
		}
	}

	/** Renvoie l'array des données représentant l'objet. */
	public function toArray(): array {
		return array(
			"id_site" => $this->idSite,
			"id_user" => $this->idUser,
			"a_revoir" => $this->aRevoir,
			"annee" => $this->annee,
			"id_commune" => $this->idCommune,
			"adresse" => $this->adresse,
			"X" => $this->x,
			"Y" => $this->y,
			"id_organisme" => $this->idOrganisme,
			"id_type_op" => $this->idTypeOp,
			"EA" => $this->EA,
			"OA" => $this->OA,
			"patriarche" => $this->patriarche,
			"numero_operation" => $this->numeroOperation,
			"arrete_prescription" => $this->arretePrescription,
			"id_responsable_op" => $this->idResponsableOp,
			"id_anthropologue[]" => $this->idAnthropologues,
			"id_paleopathologiste[]" => $this->idPaleopathologistes,
			"bibliographie" => $this->bibliographie,
		);
	}

	/** Annule la validation de l'objet. */
	private function resetValidation() {
		$this->validated = null;
		$this->invalidReason = null;
	}

	/** Invalide les données, rendant impossible l'export des données en ligne. */
	private function invalidate(string $reason) {
		if ($this->validated !== false) {
			$this->validated = false;
			$this->invalidReason = "Les données sont invalides pour les raisons suivantes :<br>\n";
		}
		$this->invalidReason .= "- $reason<br>\n";
	}

}
