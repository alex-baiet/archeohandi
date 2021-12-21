<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Form;
use Fuel\Core\Response;
use Model\Helper;
use Model\Operation;

class Controller_Test extends Controller_Template {

	public function action_index() {
		$txt = "";

		// Helper::varDump(Helper::stringIsInt("1.2"));
		// Helper::varDump(Helper::stringIsInt("1"));
		// $op = new Operation(array());
		// echo $op->validate();

		Helper::varDump($_POST);

		$txt .= Form::open(array('method' => 'POST'));

		$txt .= "<input name='coucou[]'>";
		$txt .= "<input name='coucou[]'>";
		$txt .= Form::submit();

		$txt .= Form::close();

		return new Response($txt);
	}
	
}
