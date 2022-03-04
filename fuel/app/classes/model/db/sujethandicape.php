<?php

namespace Model\Db;

use Closure;
use Fuel\Core\DB;
use Fuel\Core\Model;
use Model\Helper;
use Model\Validation;

/** Représentation d'une opération dans la base de données. */
class Sujethandicape extends Model {
	#region Values
	private ?int $id = null;
	private string $idSujetHandicape = "";
	private ?int $ageMin = null;
	private ?int $ageMax = null;
	private string $sexe = "Indéterminé";
	private ?int $datingMin = null;
	private ?int $datingMax = null;
	private ?string $milieuVie = null;
	private ?string $contexte = null;
	private ?string $contexteNormatif = null;
	private string $commentContext = "";
	private string $commentDiagnosis = "";
	private string $descriptionMobilier = "";
	private ?int $idTypeDepot = -1;
	private ?int $idSepulture = -1;
	private ?int $idDepot = null;
	private ?int $idGroupeSujet = null;

	/** @var Groupesujet|null|unset */
	private $group;
	/** @var Typedepot|null|unset */
	private $typeDepot;
	/** @var Typesepulture|null|unset */
	private $typeSepulture;
	/** @var Mobilier[]|unset */
	private array $furnitures;
	/** @var Depot|null|unset */
	private $depot;
	/** @var Subjectdiagnosis[]|unset Array au format id_diagnostic => SubjectDiagnosis */
	private $diagnosis;
	/** @var Pathology[]|unset Array au format id_pathology => Pathology */
	private $pathologies;
	/** @var Appareil[]|unset Array au format id_appareil => Appareil */
	private $itemsHelp;
	/** @var string[]|unset */
	private $urlsImg;

	private bool $empty = false;
	private Validation $validation;
	#endregion

	/** Construit le Sujethandicape depuis la liste des données. */
	public function __construct(array $data, bool $setWithEmpty = false) {
		$this->validation = new Validation();
		$this->mergeValues($data, $setWithEmpty);
	}

	/**
	 * Ajoute les données dans l'objet.
	 * @param array $data Liste des données à exploiter.
	 * @param bool $setWithEmpty Si true, remplis les champs non définis avec des variables vides.
	 */
	public function mergeValues(array $data, bool $setWithEmpty = false) {
		if (count($data) === 0) {
			$this->empty = true;
			return;
		}

		Archeo::mergeValue($this->id, $data, "id", "int");
		if ($this->id === null) $setWithEmpty = true;
		Archeo::mergeValue($this->idSujetHandicape, $data, "id_sujet_handicape");
		Archeo::mergeValue($this->ageMin, $data, "age_min", "int", true);
		Archeo::mergeValue($this->ageMax, $data, "age_max", "int", true);
		Archeo::mergeValue($this->sexe, $data, "sexe");
		Archeo::mergeValue($this->datingMin, $data, "dating_min", "int", true);
		Archeo::mergeValue($this->datingMax, $data, "dating_max", "int", true);
		Archeo::mergeValue($this->milieuVie, $data, "milieu_vie");
		Archeo::mergeValue($this->contexte, $data, "contexte");
		Archeo::mergeValue($this->contexteNormatif, $data, "contexte_normatif");
		Archeo::mergeValue($this->commentContext, $data, "comment_contexte");
		Archeo::mergeValue($this->commentDiagnosis, $data, "comment_diagnostic");
		Archeo::mergeValue($this->descriptionMobilier, $data, "description_mobilier");
		Archeo::mergeValue($this->urlImg, $data, "url_img");
		if (empty($this->urlImg)) $this->urlImg = null;
		Archeo::mergeValue($this->idTypeDepot, $data, "id_type_depot", "int", true);
		Archeo::mergeValue($this->idSepulture, $data, "id_sepulture", "int", true);
		Archeo::mergeValue($this->idDepot, $data, "id_depot", "int");
		Archeo::mergeValue($this->idGroupeSujet, $data, "id_groupe_sujets", "int");

		// Recréation du groupe du sujet
		$groupData = array();
		if (isset($data["id_group"])) $groupData["id"] = $data["id_group"];
		if (isset($data["id_chronologie"])) $groupData["id_chronologie"] = $data["id_chronologie"];
		if (isset($data["id_operation"])) $groupData["id_operation"] = $data["id_operation"];
		if (isset($data["NMI"])) $groupData["NMI"] = $data["NMI"];
		if (count($groupData) > 0 || $setWithEmpty) $this->group = new Groupesujet($groupData);

		// Récupération des données du dépôt
		$depotData = array();
		if (isset($data["id_depot"])) $depotData["id"] = $data["id_depot"];
		if (isset($data["num_inventaire"])) $depotData["num_inventaire"] = $data["num_inventaire"];
		if (isset($data["depot_commune"])) $depotData["commune"] = $data["depot_commune"];
		if (isset($data["depot_adresse"])) $depotData["adresse"] = $data["depot_adresse"];
		if (count($groupData) > 0 || $setWithEmpty) $this->depot = new Depot($depotData);

		// Récupération des mobiliers
		if (isset($_POST["id_mobiliers"])) {
			$this->furnitures = array();
			foreach ($_POST["id_mobiliers"] as $furnitureId) {
				$item = Mobilier::fetchSingle($furnitureId);
				if ($item !== null) $this->furnitures[] = $item;
			}
		}
		else if ($setWithEmpty) $this->furnitures = array();

		// Récupération des diagnostic et des localisation
		if (isset($data["diagnostics"])) {
			$this->diagnosis = array();
			foreach ($data["diagnostics"] as $idDiagnosis => $dataSpots) {
				$spots = array();
				foreach ($dataSpots as $idSpot) {
					$item = Localisation::fetchSingle($idSpot);
					if ($item !== null) $spots[$idSpot] = $item;
				}
				$this->diagnosis[$idDiagnosis] = new Subjectdiagnosis(Diagnostic::fetchSingle($idDiagnosis), $spots);
			}
		}
		else if ($setWithEmpty) $this->diagnosis = array();

		// Récupération des pathologies
		if (isset($data["pathologies"])) {
			$this->pathologies = array();
			foreach ($data["pathologies"] as $idPathology) {
				$item = Pathology::fetchSingle($idPathology);
				if ($item !== null) $this->pathologies[$idPathology] = $item;
			}
		}
		else if ($setWithEmpty) $this->pathologies = array();

		// Récupération des pathologies
		if (isset($data["appareils"])) {
			$this->itemsHelp = array();
			foreach ($data["appareils"] as $idItem) {
				$item = Appareil::fetchSingle($idItem);
				if ($item !== null) $this->itemsHelp[$idItem] = $item;
			}
		}
		else if ($setWithEmpty) $this->itemsHelp = array();

		// Récupération des urls d'images
		if (isset($data["urls_img"])) {
			$this->setUrlsImg($data["urls_img"]);
		}
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

	/**
	 * Supprime le sujet correspondant à l'id donnée, ainsi que les données des autres tables attachées au sujet.
	 * @param int Identifiant du sujet.
	 * @return string Renvoie un message décrivant l'erreur.
	 * @return null si tout se passe bien.
	 */
	public static function deleteOnDB(int $id): ?string {
		// Vérification que le sujet existe
		$subject = Sujethandicape::fetchSingle($id);
		if ($subject === null) {
			return "Le sujet à supprimer n'existe pas.";
		}

		// Deletions
		if ($subject->getIdDepot() !== null) {
			$result = Depot::deleteOnDB($subject->getIdDepot());
			if ($result !== null) return $result;
		}
		
		if ($subject->getIdGroupeSujet() !== null) {
			$result = Groupesujet::deleteOnDB($subject->getIdGroupeSujet());
			if ($result !== null) return $result;
		}

		DB::delete("accessoire_sujet")->where("id_sujet", "=", $id)->execute();
		DB::delete("appareil_sujet")->where("id_sujet", "=", $id)->execute();
		DB::delete("localisation_sujet")->where("id_sujet", "=", $id)->execute();
		DB::delete("atteinte_pathologie")->where("id_sujet", "=", $id)->execute();
		DB::delete("sujet_image")->where("id_sujet", "=", $id)->execute();

		$result = DB::delete("sujet_handicape")->where("id", "=", $id)->execute();
		if ($result < 1) return "Le sujet n'a pas pû être supprimé";

		// Tout s'est bien passé.
		return null;
	}

	/**
	 * Récupère l'id du prochain ajout de sujet.
	 * @todo Si plusieurs personnes tentent d'ajouter des sujets en même temps, le numéro peut sera faux pour plusieurs de ces personnes.
	 * Il faut corriger ça.
	 */
	public static function nextId(): int {
		$req = "SELECT AUTO_INCREMENT
			FROM information_schema.tables
			WHERE table_name = 'sujet_handicape'";
		return intval(DB::query($req)->execute()->as_array()[0]["AUTO_INCREMENT"]);
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
	public function getDescriptionMobilier() { return $this->descriptionMobilier; }
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
			$this->furnitures = array();
			if ($this->id !== null) {
				$results = Helper::querySelect("SELECT mob.id, mob.nom 
					FROM mobilier_archeologique AS mob 
					JOIN accessoire_sujet AS acc ON mob.id=acc.id_mobilier
					WHERE acc.id_sujet={$this->id};");
				$this->furnitures = array();
				
				foreach ($results as $res) {
					$this->furnitures[] = new Mobilier($res);
				}
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
			if ($this->id === null) $this->diagnosis = array();
			else $this->diagnosis = Subjectdiagnosis::fetchAll($this->id);
		}
		return $this->diagnosis;
	}

	public function getDiagnosis(int $idDiagnosis): Subjectdiagnosis {
		return $this->getAllDiagnosis()[$idDiagnosis];
	}

	/** @return Pathology[] */
	public function getPathologies(): array {
		if (!isset($this->pathologies)) {
			if ($this->id === null) $this->pathologies = array();
			else {
				$this->pathologies = array();
				$results = Helper::querySelect(
					"SELECT pat.id, pat.nom
					FROM pathologie AS pat
					JOIN atteinte_pathologie AS att
					ON pat.id = att.id_pathologie
					WHERE att.id_sujet = {$this->id};"
				);
				foreach ($results as $res) {
					$pathology = new Pathology($res);
					$this->pathologies[$pathology->getId()] = $pathology;
				}
			}
		}
		return $this->pathologies;
	}

	/** @return Appareil[] */
	public function getItemsHelp() {
		if (!isset($this->itemsHelp)) {
			if ($this->id === null) $this->itemsHelp = array();
			else {
				$this->itemsHelp = array();
				$results = Helper::querySelect(
					"SELECT app.id, app.nom
					FROM appareil_compensatoire AS app
					JOIN appareil_sujet AS asu
					ON app.id = asu.id_appareil
					WHERE asu.id_sujet = {$this->id};"
				);
				foreach ($results as $res) {
					$item = new Appareil($res);
					$this->itemsHelp[$item->getId()] = $item;
				}
			}
		}
		return $this->itemsHelp;
	}

	public function getItemHelp($idItem) {
		return $this->getItemsHelp()[$idItem];
	}

	public function getUrlsImg() : array {
		if (!isset($this->urlsImg)) {
			if ($this->getId() === null) return array();
			$this->urlsImg = Helper::querySelectList("SELECT url_img FROM sujet_image WHERE id_sujet={$this->getId()}");
		}
		return $this->urlsImg;
	}

	public function hasDiagnosis(int $idDiagnosis) {
		return isset($this->getAllDiagnosis()[$idDiagnosis]);
	}

	public function hasPathology(int $idPathology) {
		return isset($this->getPathologies()[$idPathology]);
	}

	public function hasItemHelp(int $idItemHelp) {
		return isset($this->getItemsHelp()[$idItemHelp]);
	}
	#endregion

	#region Setters
	public function setFurnitures(array $furnitures) {
		$this->validation->resetValidation();
		$this->furnitures = $furnitures;
	}

	public function addFurniture(Mobilier $furniture) {
		$this->validation->resetValidation();
		if (!isset($this->furnitures)) $this->furnitures = array();
		$this->furnitures[] = $furniture;
	}

	public function setGroup(Groupesujet $group) {
		$this->validation->resetValidation();
		$this->group = $group;
		$this->idGroupeSujet = $group->getId();
	}

	public function setDepot(Depot $depot) {
		$this->validation->resetValidation();
		$this->depot = $depot;
		$this->idDepot = $depot->getId();
	}

	/** @param Subjectdiagnosis[] $diagnosis */
	public function setDiagnosis(array $diagnosis) {
		$this->validation->resetValidation();
		$this->diagnosis = $diagnosis;
	}

	/** @param Pathology[] $pathologies */
	public function setPathologies(array $pathologies) {
		$this->validation->resetValidation();
		$this->pathologies = $pathologies;
	}

	/** @param Appareil[] $items */
	public function setItemsHelp(array $items) {
		$this->validation->resetValidation();
		$this->itemsHelp = $items;
	}

	public function setIdSujetHandicape(string $value) {
		$this->validation->resetValidation();
		$this->idSujetHandicape = $value;
	}

	private function setUrlsImg(array $urls) {
		$this->urlsImg = array_unique(array_filter($urls));
	}

	/** Réinitialise l'id pour permettre de dupliquer les données dans la BDD. */
	public function resetId() {
		$this->id = null;
	}
	#endregion

	#region ValidateAndSave
	public function validate(): bool {
		if ($this->empty) return false;

		return $this->validation->validate(function () {
			$validation = $this->validation;
			$this->idSujetHandicape = Helper::secureString($this->idSujetHandicape);
			if (strlen($this->idSujetHandicape) === 0) $validation->invalidate("Indiquez un identifiant pour le sujet.");
			if ($this->getGroup() === null || $this->getGroup()->getChronology() === null) $validation->invalidate("Choisissez une valeur pour la chronologie.");
			if ($this->ageMin > $this->ageMax) $validation->invalidate("L'âge minimum doit être inférieur à l'âge maximum.");
			if ($this->datingMin > $this->datingMax) $validation->invalidate("La datation minimum doit être inférieur à la datation maximum.");
			if ($this->milieuVie === "") $this->milieuVie = null;
			if ($this->contexte === "") $this->contexte = null;
			if ($this->contexteNormatif === "") $this->contexteNormatif = null;
			if (Typedepot::fetchSingle($this->idTypeDepot) === null) $validation->invalidate("Choisissez une valeur pour le type de dépôt.");
			if (Typesepulture::fetchSingle($this->idSepulture) === null) $validation->invalidate("Choisissez une valeur pour le type de sepulture.");
			if (empty($this->getPathologies()) && empty($this->getAllDiagnosis())) $validation->invalidate("Le sujet doit avoir au moins un diagnostic ou une pathologie.");

			if ($this->getGroup() !== null && !$this->getGroup()->validate()) $validation->invalidate();
		});
	}

	/**
	 * Ajoute/met à jour le sujet handicapé dans la base de données.
	 * @return bool Indique le succès de l'ajout.
	 */
	public function saveOnDB(): bool {
		if (!$this->validate()) return false;

		// Maj group
		if (!$this->getGroup()->saveOnDB()) {
			$this->validation->invalidate();
			return false;
		}
		$this->idGroupeSujet = $this->getGroup()->getId();

		// Maj du depot
		if (!$this->getDepot()->saveOnDB()) {
			$this->validation->invalidate();
			return false;
		}
		$this->idDepot = $this->getDepot()->getId();

		// Préparation des valeurs à envoyer à la BDD
		$arr = $this->toArray();

		if ($this->id === null || Sujethandicape::fetchSingle($this->id) === null) {
			// Le sujet n'existe pas : on la rajoute à la BDD
			list($insertId, $rowAffected) = DB::insert("sujet_handicape")
				->set($arr)
				->execute();
			$this->id = $insertId;
			if ($rowAffected < 1) {
				$this->validation->invalidate("Une erreur inconnu est survenu lors de l'ajout des données du sujet.");
				return false;
			}
		}
		else {
			// Le sujet existe : on la met à jour
			$rowAffected = DB::update("sujet_handicape")
				->set($arr)
				->where("id", "=", $this->id)
				->execute();
		}

		// Maj des accessoires/mobiliers
		$this->updateOnDB(
			"accessoire_sujet",
			"id_sujet",
			$this->getFurnitures(),
			function (Mobilier $furniture) { return array(
				"id_sujet" => $this->id,
				"id_mobilier" => $furniture->getId()
			); }
		);

		// Maj des appareils compensatoires
		$this->updateOnDB(
			"appareil_sujet",
			"id_sujet",
			$this->getItemsHelp(),
			function (Appareil $item) { return array(
				"id_sujet" => $this->id,
				"id_appareil" => $item->getId()
			); }
		);

		// Maj des pathologies
		$this->updateOnDB(
			"atteinte_pathologie",
			"id_sujet",
			$this->getPathologies(),
			function (Pathology $pathology) { return array(
				"id_sujet" => $this->id,
				"id_pathologie" => $pathology->getId()
			); }
		);

		// Maj des images
		$this->updateOnDB(
			"sujet_image",
			"id_sujet",
			$this->getUrlsImg(),
			function (string $url) { return array(
				"id_sujet" => $this->id,
				"url_img" => $url
			); }
		);

		// Maj des diagnostic
		$values = array();
		// Définition de toutes les paires diagnostic-localisation
		foreach ($this->getAllDiagnosis() as $diagnosis) {
			foreach ($diagnosis->getSpots() as $spot) {
				$values[] = array("diagnosis" => $diagnosis->getDiagnosis()->getId(), "spot" => $spot->getId());
			}
		}
		$this->updateOnDB(
			"localisation_sujet",
			"id_sujet",
			$values,
			function (array $value) { return array(
				"id_sujet" => $this->id,
				"id_diagnostic" => $value["diagnosis"],
				"id_localisation" => $value["spot"]
			); }
		);

		// Tout s'est bien passé.
		return true;
	}

	/** @param Closure $valueTransform Permet de transformer chaque valeur de $toInsert en un array pour permettre l'insertion. */
	private function updateOnDB(string $table, string $idSujetName, array $toInsert, Closure $valueTransform) {
		// Deletion des anciennes valeurs de la BDD
		DB::delete($table)
			->where($idSujetName, "=", $this->getId())
			->execute();
		// Ajout des nouvelles valeurs dans la BDD
		foreach ($toInsert as $obj) {
			DB::insert($table)
				->set($valueTransform($obj))
				->execute();
		}
	}

	/** Affiche une alert bootstrap seulement si des erreurs existent. */
	public function echoErrors() {
		$this->validation->echoErrors();
		if ($this->group !== null) $this->group->echoErrors();
		if ($this->depot !== null) $this->depot->echoErrors();
	}

	/** Renvoie l'array des données représentant l'objet. */
	public function toArray(): array {
		return array(
			"id" => $this->id,
			"id_sujet_handicape" => $this->idSujetHandicape,
			"age_min" => $this->ageMin,
			"age_max" => $this->ageMax,
			"sexe" => $this->sexe,
			"dating_min" => $this->datingMin,
			"dating_max" => $this->datingMax,
			"milieu_vie" => $this->milieuVie,
			"contexte" => $this->contexte,
			"contexte_normatif" => $this->contexteNormatif,
			"comment_contexte" => $this->commentContext,
			"comment_diagnostic" => $this->commentDiagnosis,
			"description_mobilier" => $this->descriptionMobilier,
			"id_type_depot" => $this->idTypeDepot,
			"id_sepulture" => $this->idSepulture,
			"id_depot" => $this->idDepot,
			"id_groupe_sujets" => $this->idGroupeSujet,
		);
	}

	#endregion

}
