<?php

use Fuel\Core\View;
use Model\Db\Compte;
use Model\Template;

/**
 * Pages diverses sans catégorie.
 */
class Controller_Autre extends Template {

	/** Page de la carte de référents par région. */
	public function action_referents() {
		Compte::checkPermissionRedirect("Vous devez être connecté pour accéder à cette page.", Compte::PERM_WRITE);

		$this->title('Carte des référents');
    $this->css(["referents.css"]);
    $this->js(["referents.js"]);
		$this->content(View::forge('autre/referents'));
	}
}
