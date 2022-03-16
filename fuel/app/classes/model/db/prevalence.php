<?php

namespace Model\Db;

use Fuel\Core\Database_Exception;
use Fuel\Core\DB;
use Model\Helper;

class Prevalence {
	private ?int $idSujet = null;
	private ?int $idDiagnostic = null;
	private ?int $valeur = null;

	public static function create(?int $idSujet, ?int $idDiagnostic, ?int $valeur): Prevalence {
		$p = new Prevalence(array());
		$p->idSujet = $idSujet;
		$p->idDiagnostic = $idDiagnostic;
		$p->valeur = $valeur;
		return $p;
	}

	public function __construct(array $data) {
		$this->mergeValues($data);
	}

	public function mergeValues(array $data) {
		Archeo::mergeValue($this->idSujet, $data, "id_sujet", "int");
		Archeo::mergeValue($this->idDiagnostic, $data, "id_diagnostic", "int");
		Archeo::mergeValue($this->valeur, $data, "valeur", "int");
	}

	public function getIdSujet() { return $this->idSujet; }
	public function getIdDiagnostic() { return $this->idDiagnostic; }
	public function getValeur() { return $this->valeur; }

	public function setIdSujet(?int $value) { $this->idSujet = $value; }
	public function setIdDiagnostic(?int $value) { $this->idDiagnostic = $value; }
	public function setValeur(?int $value) { $this->valeur = $value; }

	public function saveOnDb(): bool {
		if (!$this->validate()) throw new Database_Exception("L'objet Prevalence contient un null.");
		list($id, $result) = DB::insert("prevalence")->set($this->toArray())->execute();
		return $result === 1;
	}

	public function toArray(): array {
		return array(
			"id_sujet" => $this->idSujet,
			"id_diagnostic" => $this->idDiagnostic,
			"valeur" => $this->valeur,
		);
	}

	public function validate(): bool {
		return !in_array(null, $this->toArray());
	}

}
