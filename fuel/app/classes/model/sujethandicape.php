<?php

namespace Model;

use Fuel\Core\Model;

/** Représentation d'une opération dans la base de données. */
class Sujethandicape extends Model {
	#region Values
	private int $id = -1;
	private string $idSujetHandicape = "";
	private int $ageMin = 0;
	private int $ageMax = 0;
	private string $sexe = "Indéterminé";
	private int $datingMin = 0;
	private int $datingMax = 0;
	private string $milieuVie = "";
	private string $contexte = "";
	private string $contexteNormatif = "";
	private string $commentContext = "";
	private string $commentDiagnosis = "";
	private ?string $urlImg = null;
	private int $idTypeDepot = -1;
	private int $idSepulture = -1;
	private ?int $idDepot = null;
	private ?int $idGroupeSujet = null;

	/** @var Groupesujet|null|unset */
	private $group;
	/** @var Typedepot|null|unset */
	private $typeDepot;
	/** @var Typesepulture|null|unset */
	private $typeSepulture;
	/** @var Mobilier[]|unset */
	private $furnitures;
	/** @var Depot|null|unset */
	private $depot;
	/** @var Subjectdiagnosis[]|unset Array au format id_diagnostic => SubjectDiagnosis */
	private $diagnosis;
	/** @var Pathology[]|unset Array au format id_pathology => Pathology */
	private $pathologies;
	/** @var Appareil[]|unset Array au format id_appareil => Appareil */
	private $itemsHelp;

	/** @var bool|unset */
	private bool $validated;
	/** @var string|unset */
	private string $invalidReason;
	#endregion

	/** Construit le Sujethandicape depuis la liste des données. */
	public function __construct(array $data) {
		$this->mergeValues($data);
	}

	/**
	 * Ajoute les données dans l'objet.
	 * @param array $data Liste des données à exploiter.
	 * @param bool $setWithEmpty Si true, remplis les champs non définis avec des variables vides.
	 */
	public function mergeValues(array $data, bool $setWithEmpty = false) {
		Archeo::mergeValue($this->id, $data, "id", "int");
		Archeo::mergeValue($this->idSujetHandicape, $data, "id_sujet_handicape");
		Archeo::mergeValue($this->ageMin, $data, "age_min", "int");
		Archeo::mergeValue($this->ageMax, $data, "age_max", "int");
		Archeo::mergeValue($this->sexe, $data, "sexe");
		Archeo::mergeValue($this->datingMin, $data, "dating_min", "int");
		Archeo::mergeValue($this->datingMax, $data, "dating_max", "int");
		Archeo::mergeValue($this->milieuVie, $data, "milieu_vie");
		Archeo::mergeValue($this->contexte, $data, "contexte");
		Archeo::mergeValue($this->contexteNormatif, $data, "contexte_normatif");
		Archeo::mergeValue($this->commentContext, $data, "commentaire_contexte");
		Archeo::mergeValue($this->commentDiagnosis, $data, "commentaire_diagnostic");
		Archeo::mergeValue($this->urlImg, $data, "url_illustration");
		Archeo::mergeValue($this->idTypeDepot, $data, "id_type_depot", "int");
		Archeo::mergeValue($this->idSepulture, $data, "id_sepulture", "int");
		Archeo::mergeValue($this->idDepot, $data, "id_depot", "int");
		Archeo::mergeValue($this->idGroupeSujet, $data, "id_groupe_sujets", "int");

		// Recréation du groupe du sujet
		$groupData = array();
		if (isset($data["id_chronology"])) $groupData["id_chronology"] = $data["id_chronology"];
		if (isset($data["id_operation"])) $groupData["id_operation"] = $data["id_operation"];
		if (isset($data["NMI"])) $groupData["NMI"] = $data["NMI"];
		if (count($groupData) > 0 || $setWithEmpty) $this->group = new Groupesujet($groupData);

		// Récupération des mobiliers
		if (isset($_POST["id_mobiliers"])) {
			$this->furnitures = array();
			foreach ($_POST["id_mobiliers"] as $furnitureId) {
				$this->furnitures[] = Mobilier::fetchSingle($furnitureId);
			}
		}
		else if ($setWithEmpty) $this->furnitures = array();

	}

	/**
	 * Récupère le sujet handicapé correspondant à l'id.
	 * 
	 * @param int $id Identifiant du sujet handicapé.
	 * @return Sujethandicape|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM sujet_handicape WHERE id=$id");
		if ($res === null) return null;

		$obj = new Sujethandicape($res);
		return $obj;
	}

	#region Getters
	public function getId() { return $this->id; }
	public function getIdSujetHandicape() { return $this->idSujetHandicape; }
	public function getAgeMin() { return $this->ageMin; }
	public function getAgeMax() { return $this->ageMax; }
	public function getSexe() { return $this->sexe; }
	public function getDatingMin() { return $this->datingMin; }
	public function getDatingMax() { return $this->datingMax; }
	public function getMilieuVie() { return $this->milieuVie; }
	public function getContexte() { return $this->contexte; }
	public function getContexteNormatif() { return $this->contexteNormatif; }
	public function getCommentContext() { return $this->commentContext; }
	public function getCommentDiagnosis() { return $this->commentDiagnosis; }
	public function getUrlIllustration() { return $this->urlIllustration; }
	public function getIdTypeDepot() { return $this->idTypeDepot; }
	public function getIdTypeSepulture() { return $this->idSepulture; }
	public function getIdDepot() { return $this->idDepot; }
	public function getIdGroupeSujet() { return $this->idGroupeSujet; }

	public function getGroup() {
		if (!isset($this->group)){
			if ($this->idGroupeSujet === null) $this->group = null;
			else $this->group = Groupesujet::fetchSingle($this->idGroupeSujet);
		}
		return $this->group;
	}
	
	public function getTypeDepot() {
		if (!isset($this->typeDepot)) $this->typeDepot = Typedepot::fetchSingle($this->idTypeDepot);
		return $this->typeDepot;
	}
	
	public function getTypeSepulture() {
		if (!isset($this->typeSepulture)) $this->typeSepulture = Typesepulture::fetchSingle($this->idSepulture);
		return $this->typeSepulture;
	}
	
	public function getDepot() {
		if (!isset($this->depot)) {
			if ($this->idDepot === null) $this->depot = null;
			else $this->depot = Depot::fetchSingle($this->idDepot);
		}
		return $this->depot;
	}


	
	/** @return Mobilier[] */
	public function getFurnitures() {
		if (!isset($this->furnitures)) {
			$results = Helper::querySelect("SELECT mob.id, mob.nom 
				FROM mobilier_archeologique AS mob 
				JOIN accessoire_sujet AS acc ON mob.id=acc.id_mobilier
				WHERE acc.id_sujet_handicape={$this->id};");
			$this->furnitures = array();

			foreach ($results as $res) {
				$this->furnitures[] = new Mobilier($res);
			}
		}
		return $this->furnitures;
	}

	public function getFurnituresId() {
		$furnitures = $this->getFurnitures();
		$ids = array();
		foreach ($furnitures as $fur) {
			$ids[] = $fur->getId();
		}
		return $ids;
	}

	/** @return Subjectdiagnosis[] */
	public function getAllDiagnosis(): array {
		if (!isset($this->diagnosis)) {
			$this->diagnosis = Subjectdiagnosis::fetchAll($this->id);
		}
		return $this->diagnosis;
	}

	public function getDiagnosis(int $idDiagnosis): Subjectdiagnosis {
		return $this->getAllDiagnosis()[$idDiagnosis];
	}

	/** A TESTER */
	public function getPathologies() {
		if (!isset($this->pathologies)) {
			$this->pathologies = array();
			$results = Helper::querySelect(
				"SELECT pat.id, pat.nom
				FROM pathologie AS pat
				JOIN atteinte_pathologie AS att
				ON pat.id = att.id_pathologie
				WHERE att.id_sujet = {$this->id};"
			);
			foreach ($results as $res) {
				$this->pathologies[] = new Pathology($res);
			}
		}
		return $this->pathologies;
	}

	/** A TESTER */
	public function getItemsHelp() {
		if (!isset($this->itemsHelp)) {
			$this->itemsHelp = array();
			$results = Helper::querySelect(
				"SELECT app.id, app.nom
				FROM appareil_compensatoire AS app
				JOIN appareil_sujet AS asu
				ON app.id = asu.id_appareil
				WHERE asu.id_sujet = {$this->id};"
			);
			foreach ($results as $res) {
				$this->itemsHelp[] = new Appareil($res);
			}
		}
		return $this->itemsHelp;
	}

	public function getItemHelp($idItem) {
		return $this->getItemsHelp()[$idItem];
	}

	public function hasDiagnosis(int $idDiagnosis) {
		return isset($this->diagnosis[$idDiagnosis]);
	}

	public function hasPathology(int $idPathology) {
		return isset($this->pathologies[$idPathology]);
	}

	public function hasItemHelp(int $idItemHelp) {
		return isset($this->itemsHelp[$idItemHelp]);
	}
	#endregion

	#region Setters
	public function setFurnitures(array $furnitures) {
		$this->resetValidation();
		$this->furnitures = $furnitures;
	}

	public function addFurniture(Mobilier $furniture) {
		$this->resetValidation();
		if (!isset($this->furnitures)) $this->furnitures = array();
		$this->furnitures[] = $furniture;
	}

	public function setGroup(Groupesujet $group) {
		$this->resetValidation();
		$this->group = $group;
		$this->idGroupeSujet = $group->getId();
	}

	public function setDepot(Depot $depot) {
		$this->resetValidation();
		$this->depot = $depot;
		$this->idDepot = $depot->getId();
	}

	/** @param Subjectdiagnosis[] $diagnosis */
	public function setDiagnosis(array $diagnosis) {
		$this->resetValidation();
		$this->diagnosis = $diagnosis;
	}

	/** @param Pathology[] $pathologies */
	public function setPathologies(array $pathologies) {
		$this->resetValidation();
		$this->pathologies = $pathologies;
	}

	/** @param Appareil[] $items */
	public function setItemsHelp(array $items) {
		$this->resetValidation();
		$this->itemsHelp = $items;
	}
	#endregion

	#region ValidateAndSave
	public function validate(): bool {
		if (isset($this->validated)) return $this->validated;

		if ($this->group === null || $this->group->getChronology() === null) $this->invalidate("Choisissez une valeur pour la chronologie.");
		if ($this->ageMin > $this->ageMax) $this->invalidate("L'âge minimum doit être inférieur à l'âge maximum.");
		if ($this->datingMin > $this->datingMax) $this->invalidate("La datation minimum doit être inférieur à la datation maximum.");
		if ($this->milieuVie === "") $this->invalidate("Choisissez une valeur pour le milieu de vie.");
		if ($this->contexte === "") $this->invalidate("Choisissez une valeur pour le contexte de la tombe.");
		if ($this->contexteNormatif === "") $this->invalidate("Choisissez une valeur pour le contexte normatif.");
		if (Typedepot::fetchSingle($this->idTypeDepot) === null) $this->invalidate("Choisissez une valeur pour le type de dépôt.");
		if (Typesepulture::fetchSingle($this->idSepulture) === null) $this->invalidate("Choisissez une valeur pour le type de sepulture.");
		if ($this->depot === null || $this->depot->getCommune() === null) $this->invalidate("Choisissez une valeur pour la commune du dépôt.");

		// Renvoie la valeur défini entre temps
		if (isset($this->validated)) return $this->validated;
		// Tout s'est bien passé ^.^
		$this->validated = true;
		return true;
	}

	/**
	 * Ajoute / met à jour le sujet handicapé dans la base de données.
	 * @return bool Indique le succès de l'ajout.
	 */
	public function saveOnDB(): bool {
		// Validation des données
		if (!isset($this->validated)) $this->validate();
		// Cas données non valide
		if (!$this->validated) return false;

		// Préparation des valeurs à envoyer à la BDD
		// $arr = $this->toArray();
		// unset($arr["id_anthropologue[]"]);
		// unset($arr["id_paleopathologiste[]"]);

		// if ($this->idSite === -1 || Operation::fetchSingle($this->idSite) === null) {
		// 	// L'opération n'existe pas : on la rajoute à la BDD
		// 	$arr["id_site"] = null;
		// 	list($insertId, $rowAffected) = DB::insert("operations")
		// 		->set($arr)
		// 		->execute();
		// 	$this->idSite = $insertId;
		// 	if ($rowAffected < 1) return false;
		// }
		// else {
		// 	// L'opération existe : on la met à jour
		// 	$rowAffected = DB::update("operations")
		// 		->set($arr)
		// 		->where("id_site", $this->idSite)
		// 		->execute();
		// 		if ($rowAffected < 1) return false;
		// }

		// Tout s'est bien passé.
		return true;
	}

	/** Affiche une alert bootstrap seulement si des erreurs existent. */
	public function echoErrors() {
		if ($this->validate() !== true) {
			echo '
				<div class="alert alert-danger alert-dismissible text-center my-2 fade show" role="alert">
					' . $this->invalidReason . '
					<button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
				</div>';
		}
	}

	/** Renvoie l'array des données représentant l'objet. */
	// public function toArray(): array {
	// }

	/** Annule la validation de l'objet. */
	private function resetValidation() {
		unset($this->validated);
		unset($this->invalidReason);
	}

	/** Invalide les données, rendant impossible l'export des données en ligne. */
	private function invalidate(string $reason) {
		if (!isset($this->validated) || $this->validated !== false) {
			$this->validated = false;
			$this->invalidReason = "Les données sont invalides pour les raisons suivantes :<br>\n";
		}
		$this->invalidReason .= "- $reason<br>\n";
	}
	#endregion

}
