<?php

namespace Model\Script;

use Model\Db\Operation;
use Model\Db\Organisme;
use Model\Db\Typeoperation;
use Model\Helper;

/** Permet de convertir des fichiers CSV en models. */
class Import {
	/**
	 * Renvoie une liste d'opération depuis le fichier donné.
	 * @return Operation[]
	 */
	public static function fileToOperations(string $textCSV): array {
		$lines = explode("\n", $textCSV);
		unset($lines[0]);
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
		$iadresse = 3;
		$icodePostal = 4;
		$iannee = 5;
		$ix = 6;
		$iy = 7;
		$iz = 8;
		$itypeOp = 9;
		$iea = 10;
		$ioa = 11;
		$ipatriarche = 12;
		$inumOp = 13;
		$iarretePrescription = 14;
		$iorganisme = 15;
		$iresponsable = 16;
		$ianthropo1 = 17;
		$ianthropo2 = 18;
		$ipaleo = 19;
		$ibibliographie = 20;
		#endregion

		$columns = explode(";", $line);

		$op = new Operation(array());

		// Remplissage de l'opération
		$op->setIdUser("ab");
		$op->setIdCommune(-1);
		$op->setARevoir($columns[$iarevoir]);
		$op->setAdresse($columns[$iadresse]);
		$op->setAnnee(intval($columns[$iannee]));
		$op->setX(floatval($columns[$ix]));
		$op->setY(floatval($columns[$iy]));
		$op->setEA($columns[$iea]);
		$op->setOA($columns[$ioa]);
		$op->setPatriarche($columns[$ipatriarche]);
		$op->setNumeroOperation($columns[$inumOp]);
		$op->setArretePrescription($columns[$iarretePrescription]);
		$op->setResponsable($columns[$iresponsable]);
		$anth2 = $columns[$ianthropo2];
		$op->setAnthropologues($columns[$ianthropo1] . (!empty($anth2) ? ",$anth2" : ""));
		$op->setPaleopathologistes($columns[$ipaleo]);
		$op->setBibliographie($columns[$ibibliographie]);
		$org = Organisme::fetchSingleFromName($columns[$iorganisme]);
		Helper::varDump($org->getId());
		$op->setIdOrganisme($org->getId());
		$type = Typeoperation::fetchSingleFromName($columns[$itypeOp]);
		$op->setIdTypeOperation($type->getId());

		return $op;
	}
}