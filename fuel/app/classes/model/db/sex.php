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

	/** Génère le select. */
	public static function generateSelect($field = "sexe", $value = "Indéterminé"): string {
		$txt = Form::select(
			$field,
			$value,
			Sex::$values,
			array("class" => "form-select")
		);
		$txt .= Form::label("Sexe", $field);
		return $txt;
	}

	/** Génère les options du select. */
	public static function fetchOptions($idSelected = "Indéterminé", ?string $emptyValue = null): string {

		$options = Sex::$values;
		if ($emptyValue !== null) $options[""] = $emptyValue;

		$html = "";
		foreach ($options as $value => $text) {
			$html .= "<option value='$value'".($idSelected == $value ? " selected" : "").">$text</option>\n";
		}

		return $html;
	}
	
	/** Récupère la liste des options possible de sexe. */
	public static function getValues(): array { return Sex::$values; }

	/** Test que le sexe indiqué existe bien. */
	public static function exist(string $value): bool {
		return isset(Sex::$values[$value]);
	}

	public static function idToName(int $id): string { return Sex::$values[$id]; }

}