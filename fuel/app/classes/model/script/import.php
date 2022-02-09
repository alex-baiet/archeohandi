<?php

namespace Model\Script;

use Model\Db\Operation;

/** Permet de convertir des fichiers CSV en models. */
class Import {
	/**
	 * Renvoie une liste d'opération depuis le fichier donné.
	 * @return Operation[]
	 */
	public static function fileToOperations(string $textCSV): array {
		$lines = explode("\n", $textCSV);
		$operations = array();

		foreach ($lines as $line) {
			if (!empty($line)) {
				$operations[] = Import::lineToOperation($line);
			}
		}

		return $operations;
	}

	/**
	 * Convertit une ligne de fichier CSV en Operation
	 */
	private static function lineToOperation(string $line): Operation {
		#region ColumnIndex
		$iid = 0;
		$iarevoir = 1;
		$icommune = 2;
		$iadresse = 4;
		$icodePostal = 5;
		$iannee = 6;
		$ix = 7;
		$iy = 8;
		$iz = 9;
		$itypeOp = 10;
		$iea = 11;
		$ioa = 12;
		$ipatriarche = 13;
		$inumOp = 14;
		$iarretePrescription = 15;
		$iorganisme = 16;
		$iresponsable = 17;
		$ianthropo1 = 18;
		$ianthropo2 = 19;
		$ipaleo = 20;
		$ibibliographie = 21;
		#endregion

		$columns = explode(";", $line);

		$op = new Operation(array());

		// Remplissage de l'opération
		$op->setARevoir($columns[$iarevoir]);

		return $op;
	}
}