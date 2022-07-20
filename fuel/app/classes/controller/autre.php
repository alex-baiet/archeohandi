<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Model\Db\Compte;

/**
 * Pages sans catégorie.
 */
class Controller_Autre extends Controller_Template {

	/** Page de la carte de référents par région. */
	public function action_referents() {
		Compte::checkPermissionRedirect("Vous devez être connecté pour accéder à cette page.", Compte::PERM_WRITE);

		$this->template->title = 'Carte des référents';
		$this->template->content = View::forge('autre/referents');
	}
}
