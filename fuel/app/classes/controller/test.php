<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Response;
use Model\Helper;
use Model\Operation;

class Controller_Test extends Controller_Template {

	public function action_index() {
		$txt = "";

		Helper::varDump(Helper::stringIsInt("1.2"));
		Helper::varDump(Helper::stringIsInt("1"));
		$op = new Operation(array());
		echo $op->validate();

		return new Response($txt);
	}
	
}
