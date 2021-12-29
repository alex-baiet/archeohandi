<?php

namespace Model;

use Fuel\Core\FuelException;
use Fuel\Core\Model;

/** Représente un diagnostic pour un Sujethandicape spécifique. */
class Subjectdiagnosis extends Model {
	private Diagnostic $diagnosis;
	/** @var Localisation[] */
	private array $spots;

	/**
	 * @param Diagnostic $diagnosis
	 * @param Localisation[] $spots
	 */
	public function __construct(Diagnostic $diagnosis, array $spots) {
		$this->diagnosis = $diagnosis;
		$this->spots = $spots;
	}

	/** Récupère tous les diagnostics d'un sujet. */
	public static function fetchAll(int $idSubject): array {
		$diagnosis = array();

		// Récupération des id des diagnostics du sujet
		$idDiagnosis = Helper::querySelectList(
			"SELECT DISTINCT id_diagnostic
			FROM localisation_sujet
			WHERE id_sujet = {$idSubject}
			;"
		);
		
		foreach ($idDiagnosis as $idDia) {
			// Récupération des localisations de chaque diagnostic
			$idSpots = Helper::querySelectList(
				"SELECT id_localisation
				FROM localisation_sujet
				WHERE id_sujet = {$idSubject}
				AND id_diagnostic = {$idDia};"
			);

			if (count($idSpots) !== 0) {
				// Ajout du diagnostic aux résultats finaux
				$dia = Diagnostic::fetchSingle($idDia);
				$spots = array();
				foreach ($idSpots as $idSpot) {
					$spots[] = Localisation::fetchSingle($idSpot);
				}

				$diagnosis[] = new Subjectdiagnosis($dia, $spots);
			}
		}
		return $diagnosis;
	}

	public function getDiagnosis(): Diagnostic { return $this->diagnosis; }

	/** @return Localisation[] */
	public function getSpots(): array { return $this->spots; }

	/**
	 * Test que le diagnostic est bien situé à la position donné.
	 * @param int $idSpot Identifiant de la Localisation.
	 */
	public function isLocatedFromId(int $idSpot): bool {
		foreach ($this->spots as $spot) {
			if ($spot->getId() === $idSpot) return true;
		}
		return false;
	}

	/**
	 * Test que le diagnostic a un appareil compensatoire correspondant au paramètre.
	 * @param int $idItem Identifiant de l'Appareil.
	 */
	public function hasItemFromId(int $idItem): bool {
		foreach ($this->items as $item) {
			if ($item->getId() === $idItem) return true;
		}
		return false;
	}
}