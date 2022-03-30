<?php

namespace Model;

use Model\Db\Operation;
use Model\Db\Sujethandicape;

/** Représente le résultat de recherche pour une opération. */
class Searchresult {
	public Operation $operation;
	/** @var Sujethandicape[] */
	public array $subjects;

	/** Renvoie l'array des données sans les classes englobantes. */
	public function toArray(): array {
		$arr = array();

		// Transformation opération
		$opArray = $this->operation->toArray();
		$opArray["organisme"] = $this->operation->getOrganisme()->getNom();
		$opArray["type_operation"] = $this->operation->getTypeOperation()->getNom();
		$opArray["commune"] = $this->operation->getCommune()->getNom();
		$opArray["departement"] = $this->operation->getCommune()->getDepartement();
		$opArray["region"] = $this->operation->getCommune()->getRegion();
		$arr["operation"] = $opArray;

		// Transformation sujets
		$arr["subjects"] = array();
		foreach($this->subjects as $sub) {
			$subArray = $sub->toArray();
			$subArray["type_depot"] = $sub->getTypeDepot()->getNom();
			$subArray["type_sepulture"] = $sub->getTypeSepulture()->getNom();
			$subArray["adresse"] = $sub->getDepot()->getAdresse();
			$subArray["num_inventaire"] = $sub->getDepot()->getNumInventaire();
			$arr["subjects"][$sub->getId()] = $subArray;
		}
		return $arr;
	}

	public static function fromArray(array $array): Searchresult {
		$res = new Searchresult();
		// Récupération de l'opération
		$res->operation = new Operation($array["operation"]);

		// Récupération des sujets
		$res->subjects = array();
		foreach ($array["subjects"] as $sub) {
			$subject = new Sujethandicape($sub);
			$res->subjects[$subject->getId()] = $subject;
		}
		krsort($res->subjects);

		return $res;
	}
}