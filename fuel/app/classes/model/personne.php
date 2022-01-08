<?php

namespace Model;

use Fuel\Core\DB;
use Fuel\Core\FuelException;
use Fuel\Core\Model;

/** Représentation d'une personne dans la base de données. */
class Personne extends Model {
	private ?int $id = null;
	private string $nom = "";
	private string $prenom = "";

	/**
	 * Créer l'objet à partir des données en paramètre.
	 */
	public function __construct(array $data) {
		Archeo::mergeValue($this->id, $data, "id", "int");
		Archeo::mergeValue($this->nom, $data, "nom");
		$this->nom = strtoupper($this->nom);
		Archeo::mergeValue($this->prenom, $data, "prenom");
		if (!empty($this->prenom)) $this->prenom[0] = strtoupper($this->prenom[0]);
	}

	/** Créer une nouvelle Personne à partie d'une nom et d'un prénom. */
	public static function create(string $firstName, string $lastName) {
		return new Personne(array("prenom" => trim($firstName), "nom" => trim($lastName)));
	}

	/**
	 * Récupère la personne correspondant à l'id.
	 * 
	 * @param int $id Identifiant de la personne.
	 * @return Personne|null
	 */
	public static function fetchSingle(int $id) {
		if (!is_numeric($id)) return null;
		$res = Helper::querySelectSingle("SELECT * FROM personne WHERE id=$id;");
		if ($res === null) return null;

		$obj = new Personne($res);
		return $obj;
	}

	/**
	 * Ajoute une personne dans la base de données.
	 * @return bool Indique le succès de l'ajout.
	 */
	public function saveOnDB(): bool {
		// Vérification que le format des valeurs est conforme
		if ($this->id !== null) return false;
		if (empty($this->prenom)) return false;
		if (empty($this->prenom)) return false;

		// Vérification que la personne n'existe pas déjà
    /** @var array */
    $results = DB::select()
      ->from("personne")
      ->where("prenom", "=", $this->prenom)
      ->where("nom", "=", $this->nom)
      ->execute()
      ->as_array();
    
		if (count($results) > 0) {
      return false;
    }

		// Préparation des valeurs à envoyer à la BDD
		$arr = $this->toArray();

    // Insertion
    list($insertId, $rowAffected) = DB::insert("personne")
      ->set($arr)
      ->execute();
		
    return $rowAffected === 1;
	}


	/**
	 * Récupère l'id à partir du nom de la personne.
	 * 
	 * @param string Le nom de la personne, au format "NOM Prénom".
	 * @return int id de la personne en cas de succès.
	 * @return null Si aucune personne ne correspond au paramètre donné.
	 */
	public static function nameToId(string $name): ?int {
		if (empty($name)) return null;
		// Test du format (à compléter)
		if (strpos($name, " ") === false) {
			throw new FuelException("Le nom \"$name\" n'est pas au bon format.");
		}

		$names = explode(" ", $name);

		$res = Helper::querySelectSingle("SELECT id FROM personne WHERE nom=\"{$names[0]}\" AND prenom=\"$names[1]\"");
		if ($res === null) return null;
		return intval($res["id"]);
	}

	/**
	 * Renvoie tous les id des personnes correspondant aux noms.
	 * @return int[]
	 */
	public static function namesToIds(array $names): array {
		$ids = array();

		foreach ($names as $name) {
			$res = Personne::nameToId($name);
			if ($res !== null) {
				$ids[] = $res;
			}
		}
		return $ids;
	}

	public function getId() { return $this->id; }
	public function getPrenom() { return $this->prenom; }
	public function getNom() { return $this->nom; }

	public function fullName() { return "{$this->nom} {$this->prenom}"; }

	public function toArray(): array {
		return array(
			"id" => $this->id,
			"prenom" => $this->prenom,
			"nom" => $this->nom
		);
	}
	
}