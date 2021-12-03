<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Form;
use Fuel\Core\Response;

class Controller_Test extends Controller_Template {

	public function action_index() {
		$txt = "";

		$v = array("a", "b", "c");

		foreach ($v as $value) {
			$txt = "$txt {$v[1]}";
		}

		return new Response($txt);
	}
	
}
