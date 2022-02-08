<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Model\Db\Compte;

class Controller_Search extends Controller_Template {
	public function action_index() {
		Compte::checkPermissionRedirect("La page n'est pas encore disponible pour le public.", Compte::PERM_ADMIN);

		$data = array();

		$this->template->title = 'Consultation du sujet';
		$this->template->content = View::forge('search/index', $data);
	}
}
