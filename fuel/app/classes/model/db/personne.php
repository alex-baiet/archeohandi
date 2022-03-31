<?php

namespace Model\Db;

use Fuel\Core\Database_Exception;
use Fuel\Core\DB;
use Model\Helper;

class Personne {
	/** @var string[] Contient le nom de toutes les personnes. */
	private static ?array $names = null;
	/** @var string[] Liste des tables relié à la table "personne". */
	const TABLE_LIST = array("etre_responsable", "etre_anthropologue", "etre_paleopathologiste");

	private ?int $id = null;
	private ?int $idOperation = null;
	private ?string $nom = null;
	private ?string $tableLink = null;

	public function __construct(?int $id, ?int $idOperation, ?string $nom, ?string $tableLink) {
		$this->id = $id;
		$this->idOperation = $idOperation;
		$this->nom = $nom;
		$this->tableLink = $tableLink;
	}

	#region Getters
	public function getId() { return $this->id; }
	public function getIdOperation() { return $this->idOperation; }
	public function getNom() { return $this->nom; }
	public function getTableLink() { return $this->tableLink; }
	#endregion

	/** 
	 * Renvoie toutes les personnes.
	 * @return string[]
	 */
	public static function fetchNames(): array {
		if (Personne::$names === null) {
			Personne::$names = Helper::querySelectList("SELECT nom FROM personne");
		}
		return Personne::$names;
	}

	/**
	 * Renvoie les personnes de l'opération passant par la table indiqué.
	 * @return string[]
	 */
	public static function fetchAllOperation(int $idOperation, string $tableLink): array {
		if (!Personne::tableLinkExist($tableLink)) return array();

		$results = DB::select()
			->from("personne")
			->join($tableLink)->on("id_personne", "=", "id")
			->where("id_operation", "=", $idOperation)
			->execute()->as_array();

		// Mis au bon format
		$people = array();
		foreach ($results as $res) {
			$people[] = $res["nom"];
		}

		return $people;
	}

	private static function tableLinkExist(string $tableLink): bool {
		return in_array($tableLink, Personne::TABLE_LIST);
	}

	/**
	 * Sauvergarde toutes les personnes d'une opération dans la table choisi.
	 */
	public static function saveOperationNames(int $idOperation, string $tableLink, array $names) {
		if (!Personne::tableLinkExist($tableLink)) throw new Database_Exception("La table '$tableLink' n'existe pas.");

		foreach ($names as $name) {
			$person = new Personne(null, $idOperation, $name, $tableLink);
			$person->saveOnDB();
		}
	}

	/**
	 * Supprime toutes les personnes d'une opération.
	 */
	public static function deleteOperationNames(int $idOperation) {
		
		// Suppression des liens
		foreach (Personne::TABLE_LIST as $tableLink) {
			$results = DB::delete($tableLink)
				->where("id_operation", "=", $idOperation)
				->execute();
		}

		// Suppression des personnes inutilisées
		$query = DB::delete("personne");
		foreach (Personne::TABLE_LIST as $tableLink) {
			$query->where(DB::expr("NOT EXISTS(
				SELECT *
				FROM $tableLink
				WHERE id_personne=personne.id
			)"));
		}
		$results = $query->execute();
	}

	public function saveOnDB(): bool {
		if (empty($this->getNom())) return false;

		// Ajout dans la table "personne" si non existant
		$result = DB::select()->from("personne")->where("nom", "=", $this->getNom())->execute()->as_array();
		if (count($result) > 0) {
			// Personne déjà existante
			$this->id = intval($result[0]["id"]);
		} else {
			// Valeur inconnu de la BDD
			list($insertId, $result) = DB::insert("personne")
				->set(array(
					"id" => null,
					"nom" => $this->getNom()))
				->execute();
			if ($result === 0) return false;
			$this->id = $insertId;
		}

		// Ajout du lien a operation
		list($insertId, $result) = DB::insert($this->getTableLink())
			->values(array(
				"id_operation" => $this->getIdOperation(),
				"id_personne" => $this->getId()))
			->execute();
		if ($result === 0) return false;
		return true;
	}

	/** Supprime la personne de l'opération. Ne supprime le nom de la personne que si il n'est plus attaché à aucune opération. */
	public function deleteOnDB() {
		// Suppression du lien
		DB::delete($this->getTableLink())->where("id_personne", "=", $this->getId());

		// Si le nom n'est attaché à aucune personne, suppression de la personne
		$used = false;
		foreach (Personne::TABLE_LIST as $tableLink) {
			$results = DB::select()
				->from("personne")
				->join($tableLink)->on("id_personne", "=", "id")
				->where("nom", "=", $this->getNom())
				->execute()->as_array();
			if (count($results) > 0) {
				$used = true;
				break;
			}
		}
		if (!$used) {
			// Le nom n'est plus utilisé : on la supprime
			DB::delete("personne")->where("nom", "=", $this->getNom());
		}
	}
}