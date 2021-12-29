<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\View;
use Model\Appareil;
use Model\Depot;
use Model\Diagnostic;
use Model\Groupesujet;
use Model\Helper;
use Model\Localisation;
use Model\Mobilier;
use Model\Pathology;
use Model\Subjectdiagnosis;
use Model\Sujethandicape;

class Controller_Sujet extends Controller_Template {
	public function action_view($id) {
		//Permet de récupérer toutes les informations du sujet handicapé
		$query = DB::query('SELECT * FROM sujet_handicape WHERE id=' . $id . ' ');
		$sujet_details = $query->execute();
		$sujet_details = $sujet_details->_results;

		$query = DB::query('SELECT * FROM atteinte_invalidante WHERE id_sujet_handicape=' . $id . ' ');
		$sujet_atteinte = $query->execute();
		$sujet_atteinte = $sujet_atteinte->_results;

		if (!empty($sujet_atteinte[0]['id_commentaire_diagnostic'])) {
			$query = DB::query('SELECT commentaire_diagnostic FROM commentaire_diagnostic WHERE id=' . $sujet_atteinte[0]['id_commentaire_diagnostic'] . ' ');
			$commentaire_sujet = $query->execute();
			$commentaire_sujet = $commentaire_sujet->_results[0]['commentaire_diagnostic'];
		} else $commentaire_sujet = "";

		$query = DB::query('SELECT * FROM accessoire_sujet WHERE id_sujet_handicape=' . $id . ' ');
		$accessoire_sujet = $query->execute();
		$accessoire_sujet = $accessoire_sujet->_results;

		$query = DB::query('SELECT * FROM localisation_sujet WHERE id_sujet_handicape=' . $id . ' ');
		$localisation_sujet = $query->execute();
		$localisation_sujet = $localisation_sujet->_results;

		$query = DB::query('SELECT * FROM appareil_sujet WHERE id=' . $id . ' ');
		$appareil_sujet = $query->execute();
		$appareil_sujet = $appareil_sujet->_results;

		$data = array('sujet_details' => $sujet_details, 'sujet_atteinte' => $sujet_atteinte, 'commentaire_sujet' => $commentaire_sujet, 'accessoire_sujet' => $accessoire_sujet, 'localisation_sujet' => $localisation_sujet, 'appareil_sujet' => $appareil_sujet);
		$this->template->title = 'Consultation du sujet';
		$this->template->content = View::forge('sujet/view', $data);
	}

	public function action_edit($id) {
		$subject = Sujethandicape::fetchSingle($id);		
		$data = array("subject" => $subject);

		$this->template->title = 'Modification du sujet';
		$this->template->content = View::forge('sujet/edit', $data);
	}

	public function action_add($id) {
		$data = array('idOperation' => $id);

		if (Input::method() === "POST") {
			// Recréation du sujet à partir des valeurs entrées
			$subject = new Sujethandicape($_POST, true);

			// Récupération des appareils de compensation
			$itemsHelp = array();
			foreach (Appareil::fetchAll() as $item) {
				if (isset($_POST["item_{$item->getId()}"])) {
					$itemsHelp[$item->getId()] = $item;
				}
			}
			$subject->setItemsHelp($itemsHelp);
			
			$data["subject"] = $subject;
			Helper::varDump($subject);
		}

		$this->template->title = "Ajouter des sujets";
		$this->template->content = View::forge('sujet/add', $data);
	}
}
