<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;

/**
 * Pages d'affichage d'erreur
 */
class Controller_Error extends Controller_Template {
	public function action_404() {
		$this->template->title = 'Créer un compte';
		$this->template->content = View::forge('error/template', array(
			"title" => "404",
			"body" => "La page que vous recherchez n'existe pas."
		));
	}
}