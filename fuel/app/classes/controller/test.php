<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Form;
use Fuel\Core\Response;
use Model\Helper;
use Model\Operation;

class Controller_Test extends Controller_Template {

	private function test(&$array) {
		$array = array("vldfhfjkshjogsd,,,");
	}

	public function action_index() {
		$txt = "";

		$arr = null;
		$this->test($arr);

		Helper::varDump($arr);

		return new Response($txt);
	}
	
}
