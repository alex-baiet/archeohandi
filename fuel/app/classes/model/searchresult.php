<?php

namespace Model;

use Model\Db\Operation;
use Model\Db\Sujethandicape;

/** Représente le résultat de recherche pour une opération. */
class Searchresult {
	public Operation $operation;
	/** @var Sujethandicape[] */
	public array $subjects;
}