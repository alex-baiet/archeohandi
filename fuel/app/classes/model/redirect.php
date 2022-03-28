<?php

namespace Model;

use Fuel\Core\Response;

/** Permet de gérer les redirections. */
class Redirect {
	
	public static function redirectBack() {
		Response::redirect_back();
	}
}