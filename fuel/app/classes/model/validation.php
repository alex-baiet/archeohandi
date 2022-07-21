<?php

namespace Model;

use Closure;
use Fuel\Core\Model;

/**
 * Permet de simplifier la validation des données à exporter.
 */
class Validation extends Model {
	/** @var bool|unset */
	private bool $validated;
	/** @var string|unset */
	private string $invalidReason;

	public function __construct() { }

	/**
	 * Renvoie un boolean indiquant si les données sont valides.
	 * 
	 * @param Closure $lambdaTest Tests à effectuer si la validation n'a pas encore été faites.
	 */
	public function validate(Closure $lambdaTest): bool {
		// Test déjà effectué, on renvoie la valeur définie
		if (isset($this->validated)) return $this->validated;

		$lambdaTest();
		
		// Renvoie la valeur défini entre temps
		if (isset($this->validated)) return $this->validated;
		// Tout s'est bien passé ^.^
		$this->validated = true;
		return true;
	}

	/** Affiche une alert bootstrap seulement si des erreurs existent. */
	public function echoErrors() {
		if (isset($this->validated) && $this->validated !== true) {
			echo '
				<div class="alert alert-danger alert-dismissible text-center my-2 fade show" role="alert">
					' . $this->invalidReason . '
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer">
				</div>';
		}
	}

	/** Annule la validation de l'objet. */
	public function resetValidation() {
		unset($this->validated);
		unset($this->invalidReason);
	}

	/** Invalide les données. */
	public function invalidate(?string $reason = null) {
		if (!isset($this->validated) || $this->validated !== false) {
			$this->validated = false;
			$this->invalidReason = "Les données sont invalides pour les raisons suivantes :<br>\n";
		}
		if ($reason !== null) $this->invalidReason .= "- $reason<br>\n";
	}
	#endregion

}