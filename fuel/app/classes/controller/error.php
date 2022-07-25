<?php

use Fuel\Core\View;
use Model\Template;

/** Pages d'affichage d'erreur */
class Controller_Error extends Template {
	/** Afficher par défaut si aucune page trouvée */
	public function action_404() {
		$this->title('Créer un compte');
    $this->css(["error.css"]);
		$this->content(View::forge('error/template', array(
			"title" => "404",
			"body" => "La page que vous recherchez n'existe pas."
		)));
	}
}