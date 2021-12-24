<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Form;
use Fuel\Core\Response;
use Model\Commune;
use Model\Helper;
use Model\Operation;
use Model\Personne;

class Controller_Test extends Controller_Template {

	public function action_index() {
		$txt = "";

		$res = Commune::nameToId(",  ");

		Helper::varDump($res);

		return new Response($txt);
	}
	
}
