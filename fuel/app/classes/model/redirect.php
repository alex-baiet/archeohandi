<?php

namespace Model;

use Fuel\Core\Response;

/** Gère les redirections. */
class Redirect {
	
	public static function redirectBack() {
		Response::redirect_back();
	}
}