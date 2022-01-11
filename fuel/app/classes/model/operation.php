<?php

namespace Model;

use Fuel\Core\DB;
use Fuel\Core\Model;
use InvalidArgumentException;

/** Représentation d'une opération dans la base de données. */
class Operation extends Model {
	#region Values
	private int $id = -1;
	private string $idUser = "";
	private string $aRevoir = "";
	private int $annee = 0;
	private ?int $idCommune = null;
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
	private string $responsable = "";
	private string $anthropologues = "";
	private string $paleopathologistes = "";
	private string $bibliographie = "";

	/** @var Commune|null */
	private $commune = null;
	/** @var Typeoperation|null */
	private $typeOp = null;
	/** @var Organisme|null */
	private $organisme = null;
	/** @var string[]|unset */
	private array $anthroArray;
	/** @var string[]|unset */
	private array $paleoArray;
	/** @var Sujethandicape[]|unset */
	private array $subjects;

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
		unset($this->anthroArray);
		unset($this->paleoArray);

		// Fusion des valeurs
		Archeo::mergeValue($this->id, $data, "id");
		Archeo::mergeValue($this->idUser, $data, "id_user");
		Archeo::mergeValue($this->aRevoir, $data, "a_revoir");
		Archeo::mergeValue($this->annee, $data, "annee", "int");
		Archeo::mergeValue($this->idCommune, $data, "id_commune", "int");
		Archeo::mergeValue($this->adresse, $data, "adresse");
		Archeo::mergeValue($this->x, $data, "X");
		Archeo::mergeValue($this->y, $data, "Y");
		Archeo::mergeValue($this->idOrganisme, $data, "id_organisme", "int");
		Archeo::mergeValue($this->idTypeOp, $data, "id_type_op", "int");
		Archeo::mergeValue($this->EA, $data, "EA");
		Archeo::mergeValue($this->OA, $data, "OA");
		Archeo::mergeValue($this->patriarche, $data, "patriarche");
		Archeo::mergeValue($this->numeroOperation, $data, "numero_operation");
		Archeo::mergeValue($this->arretePrescription, $data, "arrete_prescription");
		Archeo::mergeValue($this->responsable, $data, "responsable");
		Archeo::mergeValue($this->bibliographie, $data, "bibliographie");

		if (isset($data["anthropologues"])) {
			if (is_array($data["anthropologues"])) {
				$this->anthroArray = $data["anthropologues"];
				$this->anthropologues = implode(',', $this->anthroArray);
			} else {
				$this->anthropologues = $data["anthropologues"];
			}
		}
		if (isset($data["paleopathologistes"])) {
			if (is_array($data["paleopathologistes"])) {
				$this->paleoArray = $data["paleopathologistes"];
				$this->paleopathologistes = implode(',', $this->paleoArray);
			} else {
				$this->paleopathologistes = $data["paleopathologistes"];
			}
		}

		if (isset($data["commune"])) $this->idCommune = Commune::nameToId($data["commune"]);

	}

	/**
	 * Récupère l'opération correspondant à l'id.
	 * 
	 * @param int $id Identifiant de l'opération.
	 * @return Operation|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM operations WHERE id=$id");
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

	public static function deleteOnDB(int $id): ?string {
		$op = Operation::fetchSingle($id);
		if ($op === null) return "L'opération à supprimer n'existe pas";

		// Deletion
		foreach ($op->getSubjects() as $subject) {
			$result = Sujethandicape::deleteOnDB($subject->getId());
			if ($result !== null) return "$result (sujet n°{$subject->getId()})";
		}

		$result = DB::delete("operations")->where("id", "=", $id)->execute();
		if ($result < 1) return "L'opération n'a pas pû être supprimé";

		// Tous s'est bien passé
		return null;
	}

	public static function generateSelect(string $field = "id_operation", string $label = "Opération", $idSelected = "", bool $formFloating = true) {
		$valueRecover = function ($data) { return $data["id"]; };
		$textRecover = function ($data) {
			$value = new Operation($data);
			return $value->getNomOp();
		};
		return Archeo::generateSelect($field, $label, $idSelected, "operations", $valueRecover, $textRecover, $formFloating);
	}

	#region Getters
	public function getId() { return $this->id; }
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

	/** Créer un nom pour l'opération */
	public function getNomOp(): string {
		return ($this->getCommune() !== null ? $this->getCommune()->getNom() : "")
			.", {$this->adresse}, {$this->annee}";
	}

	public function getCommune(): ?Commune {
		if ($this->idCommune === null) return null;
		if ($this->commune === null) $this->commune = Commune::fetchSingle($this->idCommune);
		return $this->commune;
	}

	public function getTypeOperation(): ?Typeoperation {
		if ($this->typeOp === null) $this->typeOp = Typeoperation::fetchSingle($this->idTypeOp);
		return $this->typeOp;
	}

	public function getOrganisme(): ?Organisme {
		if ($this->organisme === null) $this->organisme = Organisme::fetchSingle($this->idOrganisme);
		return $this->organisme;
	}

	public function getResponsable(): string {
		return $this->responsable;
	}

	/** @return string[] */
	public function getAnthropologues(): array {
		if (!isset($this->anthroArray)) {
			if (empty($this->anthropologues)) {
				$this->anthroArray = array();
			} else {
				$this->anthroArray = explode(',', $this->anthropologues);
			}
		}
		return $this->anthroArray;
	}

	/** @return string[] */
	public function getPaleopathologistes(): array {
		if (!isset($this->paleoArray)) {
			if (empty($this->paleopathologistes)) {
				$this->paleoArray = array();
			} else {
				$this->paleoArray = explode(',', $this->paleopathologistes);
			}
		}
		return $this->paleoArray;
	}

	/** @return Sujethandicape[] Liste de tous les sujets de l'opération. */
	public function getSubjects(): array {
		if (isset($this->subjects)) return $this->subjects;
		
		// Récupération des groupe_sujets
		$idGroups = Helper::querySelectList('SELECT id FROM groupe_sujets WHERE id_operation=' . $this->getId());

		// Récupération de tous les sujets handicapé des différents groupes
		$this->subjects = array();
		foreach ($idGroups as $idGroup) {
			$result = Helper::querySelectSingle('SELECT * FROM sujet_handicape WHERE id_groupe_sujets=' . $idGroup);
			if ($result !== null) {
				$subject = new Sujethandicape($result);
				$this->subjects[$subject->getId()] = $subject;
			}
		}

		return $this->subjects;
	}
	#endregion

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
		$this->adresse = Helper::secureString($this->adresse);

		if (!Helper::stringIsInt($this->annee)) $this->invalidate("L'année indiquée doit être un nombre.");
		if (!is_numeric($this->x)) $this->invalidate("La position sur x (longitude) indiquée doit être un nombre.");
		if (!is_numeric($this->y)) $this->invalidate("La position sur y (latitude) indiquée doit être un nombre.");
		if ($this->getCommune() === null) $this->invalidate("La commune n'existe pas.");
		if ($this->getOrganisme() === null) $this->invalidate("L'organisation n'existe pas.");
		if ($this->getTypeOperation() === null) $this->invalidate("Le type d'opération n'existe pas.");
		$this->aRevoir = Helper::secureString($this->aRevoir);

		$res = Helper::verifAlpha($this->EA, 'alphanum');
		if ($res === false) $this->invalidate("La valeur \"EA\" contient des caractères interdit.");
		else $this->EA = $res;

		$res = Helper::verifAlpha($this->OA, 'alphanum');
		if ($res === false) $this->invalidate("La valeur \"OA\" contient des caractères interdit.");
		else $this->OA = $res;

		$res = Helper::verifAlpha($this->patriarche, 'alphanum');
		if ($res === false) $this->invalidate("Le patriarche contient des caractères interdit.");
		else $this->patriarche = $res;

		$res = Helper::verifAlpha($this->numeroOperation, 'alphanum');
		if ($res === false) $this->invalidate("Le numéro d'opération contient des caractères interdit.");
		else $this->numeroOperation = $res;

		$res = Helper::verifAlpha($this->arretePrescription, 'alphanum');
		if ($res === false) $this->invalidate("L'arrete de prescription contient des caractères interdit.");
		else $this->arretePrescription = $res;

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
	 * @return bool Indique le succès de l'ajout.
	 */
	public function saveOnDB(): bool {
		// Validation des données
		if ($this->validated == null) $this->validate();
		// Cas données non valide
		if (!$this->validated) return false;

		// Préparation des valeurs à envoyer à la BDD
		$arr = $this->toArray();

		if ($this->id === -1 || Operation::fetchSingle($this->id) === null) {
			// L'opération n'existe pas : on la rajoute à la BDD
			$arr["id"] = null;
			list($insertId, $rowAffected) = DB::insert("operations")
				->set($arr)
				->execute();
			$this->id = $insertId;
			if ($rowAffected < 1) return false;
		}
		else {
			// L'opération existe : on la met à jour
			$rowAffected = DB::update("operations")
				->set($arr)
				->where("id", $this->id)
				->execute();
				if ($rowAffected < 1) return false;
		}

		// Tout s'est bien passé.
		return true;
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
			"id" => $this->id,
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
			"responsable" => $this->responsable,
			"anthropologues" => $this->anthropologues,
			"paleopathologistes" => $this->paleopathologistes,
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
