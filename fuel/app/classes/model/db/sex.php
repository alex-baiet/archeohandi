<?php

namespace Model\Db;

use Fuel\Core\Form;
use Fuel\Core\Model;

class Sex extends Model {
	private static array $values = array(
		"Femme" => "Femme",
		"Homme" => "Homme",
		"Indéterminé" => "Indéterminé"
	);

	/** @param */
	public static function generateSelect($field = "sexe", $value = "Indéterminé") {
		$txt = Form::select(
			$field,
			$value,
			Sex::$values,
			array("class" => "form-select")
		);
		$txt .= Form::label("Sexe", $field);
		return $txt;
	}

	public static function getValues(): array { return Sex::$values; }

	/** Test que le sexe indiqué existe bien. */
	public static function exist(string $value): bool {
		return isset(Sex::$values[$value]);
	}

}