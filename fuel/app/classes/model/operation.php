<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Operation extends Model {
	#region Values
	private $idSite;
	private $idUser;
	private $nomOp;
	private $aRevoir;
	private $annee;
	private $idCommune;
	private $adresse;
	private $x;
	private $y;
	private $idOrganisme;
	private $idTypeOp;
	private $EA;
	private $OA;
	private $patriarche;
	private $numeroOperation;
	private $arretePrescription;
	private $responsableOp;
	private $anthropologue;
	private $paleopathologiste;
	private $bibliographie;

	/** @var Commune|undefined */
	private $commune;
	/** @var Typeoperation|undefined */
	private $typeOp;
	/** @var Organisme|undefined */
	private $organisme;

	/**
	 * Indique que l'objet est valide pour la base de données.
	 * undefined signifie que l'objet n'a pas encore été vérifié.
	 * @var bool|undefined 
	 */
	private $validated;
	/**
	 * Message indiquant pourquoi l'objet n'est pas valide.
	 * @var string|undefined
	 */
	private $invalidReason;
	#endregion

	/** Construit l'Operation depuis la liste des données. */
	public function __construct(array $data) {
		$this->idSite = Helper::arrayGetString("id_site", $data);
		$this->idUser = Helper::arrayGetString("id_user", $data);
		$this->nomOp = Helper::arrayGetString("nom_op", $data);
		$this->aRevoir = Helper::arrayGetString("a_revoir", $data);
		$this->annee = Helper::arrayGetString("annee", $data);
		$this->idCommune = intval(Helper::arrayGetString("id_commune", $data));
		$this->adresse = Helper::arrayGetString("adresse", $data);
		$this->x = Helper::arrayGetString("X", $data);
		$this->y = Helper::arrayGetString("Y", $data);
		$this->idOrganisme = intval(Helper::arrayGetString("id_organisme", $data));
		$this->idTypeOp = intval(Helper::arrayGetString("id_type_op", $data));
		$this->EA = Helper::arrayGetString("EA", $data);
		$this->OA = Helper::arrayGetString("OA", $data);
		$this->patriarche = Helper::arrayGetString("patriarche", $data);
		$this->numeroOperation = Helper::arrayGetString("numero_operation", $data);
		$this->arretePrescription = Helper::arrayGetString("arrete_prescription", $data);
		$this->responsableOp = Helper::arrayGetString("responsable_op", $data);
		$this->anthropologue = Helper::arrayGetString("anthropologue", $data);
		$this->paleopathologiste = Helper::arrayGetString("paleopathologiste", $data);
		$this->bibliographie = Helper::arrayGetString("bibliographie", $data);
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

	public function getIdSite() { return $this->idSite; }
	public function getIdUser() { return $this->idUser; }
	public function getNomOp() { return $this->nomOp; }
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
	public function getResponsableOp() { return $this->responsableOp; }
	public function getAnthropologue() { return $this->anthropologue; }
	public function getPaleopathologiste() { return $this->paleopathologiste; }
	public function getBibliographie() { return $this->bibliographie; }
	
	/**
	 * @return Commune|null
	 */
	public function getCommune() {
		if (!isset($this->commune)) $this->commune = Commune::fetchSingle($this->idCommune);
		return $this->commune;
	}
	/**
	 * @return Typeoperation|null
	 */
	public function getTypeOperation() {
		if (!isset($this->typeOp)) $this->typeOp = Typeoperation::fetchSingle($this->idTypeOp);
		return $this->typeOp;
	}
	/**
	 * @return Organisme|null
	 */
	public function getOrganisme() {
		if (!isset($this->organisme)) $this->organisme = Organisme::fetchSingle($this->idOrganisme);
		return $this->organisme;
	}
	
	/**
	 * Vérifie que toutes les valeurs sont correctes.
	 * @return true|string Renvoie un string contenant un message d'erreurs en cas de test non passant, ou l'opération en cas de succès.
	 */
	public function validate() {
		// L'objet à déjà été validé : on retourne le résultat précédent.
		if (isset($this->validated)) {
			if ($this->validated) return true;
			else return $this->invalidReason;
		}

		// Tests de validation des données
		// Test pour l'adresse
		$res = Helper::verif_alpha($this->adresse, 'alphatout');
		if ($res === false) $this->invalidate("L'adresse contient des caractères interdit.");
		$this->adresse = $res;

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
		$this->EA = $res;

		// Test OA
		$res = Helper::verif_alpha($this->OA, 'alphanum');
		if ($res === false) $this->invalidate("La valeur \"OA\" contient des caractères interdit.");
		$this->OA = $res;

		// Test patriarche
		$res = Helper::verif_alpha($this->patriarche, 'alphanum');
		if ($res === false) $this->invalidate("Le patriarche contient des caractères interdit.");
		$this->patriarche = $res;

		// Test numero operation
		$res = Helper::verif_alpha($this->numeroOperation, 'alphanum');
		if ($res === false) $this->invalidate("Le numéro d'opération contient des caractères interdit.");
		$this->numeroOperation = $res;

		// Test arrete prescription
		$res = Helper::verif_alpha($this->arretePrescription, 'alphanum');
		if ($res === false) $this->invalidate("L'arrete de prescription contient des caractères interdit.");
		$this->arretePrescription = $res;

		// Test responsable
		$res = Helper::verif_alpha($this->responsableOp, 'alpha');
		if ($res === false) $this->invalidate("Le nom du responsable contient des caractères interdit.");
		$this->responsableOp = $res;

		// Test anthropologue
		$res = Helper::verif_alpha($this->anthropologue, 'alpha');
		if ($res === false) $this->invalidate("Le nom de l'anthropologue contient des caractères interdit.");
		$this->anthropologue = $res;

		// Test paleopathologiste
		$res = Helper::verif_alpha($this->paleopathologiste, 'alpha');
		if ($res === false) $this->invalidate("Le nom du paléopathologiste contient des caractères interdit.");
		$this->paleopathologiste = $res;

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
	 * @return bool Indique le succès de l'ajout.
	 */
	public function saveOnDB(): bool {
		// Validation des données
		if (!isset($this->validated)) $this->validate();
		// Cas données non valide
		if (!$this->validated) return false;

		// Maj des données en ligne

		// Tout s'est bien passé.
		return true;
		
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
