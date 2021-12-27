<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Helper;

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
		//Permet de récupérer toutes les informations du sujet handicapé
		$query = DB::query('SELECT * FROM sujet_handicape WHERE id=' . $id . '   ');
		$modif_sujet = $query->execute();
		$modif_sujet = $modif_sujet->_results;

		if ($modif_sujet[0]['id_depot'] != NULL) {
			$query = DB::query('SELECT * FROM depot WHERE id="' . $modif_sujet[0]['id_depot'] . '" ');
			$depot_sujet = $query->execute();
			$depot_sujet = $depot_sujet->_results;
		} else $depot_sujet = "";

		$query = DB::query('SELECT * FROM atteinte_invalidante WHERE id_sujet_handicape="' . $id . '" ');
		$atteinte_invalidante_sujet = $query->execute();
		$atteinte_invalidante_sujet = $atteinte_invalidante_sujet->_results;

		if (!empty($atteinte_invalidante_sujet)) {
			if ($atteinte_invalidante_sujet[0]['id_commentaire_diagnostic'] != NULL) {
				$query = DB::query('SELECT commentaire_diagnostic FROM commentaire_diagnostic WHERE id="' . $atteinte_invalidante_sujet[0]['id_commentaire_diagnostic'] . '" ');
				$commentaire_diagnostic_sujet = $query->execute();
				$commentaire_diagnostic_sujet = $commentaire_diagnostic_sujet->_results[0]['commentaire_diagnostic'];
			} else $commentaire_diagnostic_sujet = "";
		} else $commentaire_diagnostic_sujet = "";

		$query = DB::query('SELECT * FROM accessoire_sujet WHERE id_sujet_handicape="' . $id . '" ');
		$accessoire_sujet = $query->execute();
		$accessoire_sujet = $accessoire_sujet->_results;

		$query = DB::query('SELECT * FROM localisation_sujet WHERE id_sujet_handicape="' . $id . '" ');
		$localisation_sujet = $query->execute();
		$localisation_sujet = $localisation_sujet->_results;

		$query = DB::query('SELECT * FROM appareil_sujet WHERE id_sujet_handicape="' . $id . '" ');
		$appareil_sujet = $query->execute();
		$appareil_sujet = $appareil_sujet->_results;

		//Permet de récupérer les informations des foreach pour permettre la modification

		$chronologie = DB::query('SELECT id_chronologie,nom_chronologie FROM chronologie_site ORDER BY nom_chronologie ASC')->execute();
		$chronologie = $chronologie->_results;

		$type_depot = DB::query('SELECT * FROM type_depot')->execute();
		$type_depot = $type_depot->_results;

		$type_sepulture = DB::query('SELECT * FROM type_sepulture')->execute();
		$type_sepulture = $type_sepulture->_results;

		$diagnostic = DB::query('SELECT * FROM diagnostic')->execute();
		$diagnostic = $diagnostic->_results;

		$accessoire = DB::query('SELECT * FROM mobilier_archeologique')->execute();
		$accessoire = $accessoire->_results;

		$localisation_atteinte = DB::query('SELECT * FROM localisation_atteinte')->execute();
		$localisation_atteinte = $localisation_atteinte->_results;

		$pathologie = DB::query('SELECT * FROM pathologie')->execute();
		$pathologie = $pathologie->_results;

		$appareil_compensatoire = DB::query('SELECT * FROM appareil_compensatoire')->execute();
		$appareil_compensatoire = $appareil_compensatoire->_results;

		//Initialisation des variables qui permettent de stocker les erreurs et les valeurs des différents champs pour les afficher sur la page en cas d'erreur
		$erreurs = $valeurs = "";

		if (Input::post("modif_sujet")) {

			//Initialisation des différents tableaux
			$description_diagnostic_tab = $description_patho_tab = array();
			//Initialisation des différents tableaux en créant les différentes clés pour pouvoir ajouter les informations directement dedans
			for ($a = 1; $a <= 13; $a++) : $localisation[$a] = array();
				$appareil[$a] = array();
			endfor;

			//Pour chaque champs, nous vérifions si elle est pas vide, quelle corresponde au caractère valide et si tout est correct la variable valeurs ajoute la valeur dans sa variable. Et en cas d'erreur, la variable erreurs ajoute l'erreur du problème

			//Pour les différents select, nous vérifions que les différentes entités existent dans la BDD

			if (Input::post('NMI') != NULL) {
				if (is_numeric(Input::post('NMI'))) {
					$valeurs .= '&NMI=' . Input::post('NMI');
					$NMI = Input::post('NMI');
				} else $erreurs .= "&erreur_NMI";
			} else $erreurs .= "&erreur_NMI_vide";

			if (Input::post('nom_chronologie') != NULL) {
				$if_chrono_ex = DB::query('SELECT nom_chronologie FROM chronologie_site WHERE id_chronologie=' . Input::post('nom_chronologie') . ' ')->execute();
				$if_chrono_ex = $if_chrono_ex->_results;
				if (!empty($if_chrono_ex)) {
					$valeurs .= '&chronologie=' . Input::post('nom_chronologie');
					$periode_chrono = Input::post('nom_chronologie');
				} else $erreurs .= "&erreur_chrono";
			} else $erreurs .= "&erreur_chrono_vide";

			if (Input::post('id_sujet') != NULL)
				if (Helper::verifAlpha(Input::post('id_sujet'), 'alphanum') != false) {
					$valeurs .= '&id_sujet=' . Input::post('id_sujet');
					$id_sujet = Input::post('id_sujet');
				} else $erreurs .= "&erreur_alpha_sujet";
		} else $erreurs .= "&erreur_sujet_vide";

		if (Input::post('sexe') != NULL) {
			if (Input::post('sexe') == "Femme" || Input::post('sexe') == "Homme" || Input::post('sexe') == "Indéterminé") {
				$valeurs .= '&sexe=' . Input::post('sexe');
				$sexe = Input::post('sexe');
			} else $erreurs .= "&erreur_sexe";
		} else $erreurs .= "&erreur_sexe_vide";

		if (!empty(Input::post('age_min'))) {
			if (is_numeric(Input::post('age_min')) && Input::post('age_min') > 0 && Input::post('age_min') < 130) {
				$valeurs .= '&age_min=' . Input::post('age_min');
				$age_min = Input::post('age_min');
			} else $erreurs .= "&erreur_age_min";
		} else $age_min = "";

		if (!empty(Input::post('age_max'))) {
			if (is_numeric(Input::post('age_max')) && Input::post('age_max') > 0 && Input::post('age_max') < 130 && Input::post('age_max') > $age_min) {
				$valeurs .= '&age_max=' . Input::post('age_max');
				$age_max = Input::post('age_max');
			} else $erreurs .= "&erreur_age_max";
		} else $age_max = "";

		if (!empty(Input::post('datation_debut'))) {
			if (is_numeric(Input::post('datation_debut'))) {
				$valeurs .= '&datation_debut=' . Input::post('datation_debut');
				$datation_debut = Input::post('datation_debut');
			} else $erreurs .= "&erreur_datation_debut";
		} else $datation_debut = "";

		if (!empty(Input::post('datation_fin'))) {
			if (is_numeric(Input::post('datation_fin')) && Input::post('datation_fin') > $datation_debut) {
				$valeurs .= '&datation_fin=' . Input::post('datation_fin');
				$datation_fin = Input::post('datation_fin');
			} else $erreurs .= "&erreur_datation_fin";
		} else $datation_fin = "";

		if (Input::post('type_depot') != NULL) {
			$if_depot_ex = DB::query('SELECT nom FROM type_depot WHERE id=' . Input::post('type_depot') . ' ')->execute();
			$if_depot_ex = $if_depot_ex->_results;
			if (!empty($if_depot_ex)) {
				$valeurs .= '&type_depot=' . Input::post('type_depot');
				$type_depot = Input::post('type_depot');
			} else $erreurs .= "&erreur_depot";
		} else $erreurs .= "&erreur_depot_vide";

		if (Input::post('type_sepulture') != NULL) :
			$if_sepulture_ex = DB::query('SELECT nom FROM type_sepulture WHERE id=' . Input::post('type_sepulture') . ' ')->execute();
			$if_sepulture_ex = $if_sepulture_ex->_results;
			if (!empty($if_sepulture_ex)) : 	$valeurs .= '&type_sepulture=' . Input::post('type_sepulture');
				$type_sepulture = Input::post('type_sepulture');
			else : $erreurs .= "&erreur_sepulture";
			endif;
		else : $erreurs .= "&erreur_sepulture_vide";
		endif;

		if (Input::post('contexte_normatif') != NULL) :
			if (Input::post('contexte_normatif') == "Standard" || Input::post('contexte_normatif') == "Atypique") : $valeurs .= '&contexte_normatif=' . Input::post('contexte_normatif');
				$contexte_normatif = Input::post('contexte_normatif');
			else : $erreurs .= "&erreur_contexte_normatif";
			endif;
		else : $contexte_normatif = "";
		endif;

		if (Input::post('milieu_vie') != NULL) :
			if (Input::post('milieu_vie') == "Rural" || Input::post('milieu_vie') == "Urbain") : $valeurs .= '&milieu_vie=' . Input::post('milieu_vie');
				$milieu_vie = Input::post('milieu_vie');
			else : $erreurs .= "&erreur_milieu_vie";
			endif;
		else : $milieu_vie = "";
		endif;

		if (Input::post('contexte') != NULL) :
			if (Input::post('contexte') == "Funeraire" || Input::post('contexte') == "Domestique" || Input::post('contexte') == "Autre") : $valeurs .= '&contexte=' . Input::post('contexte');
				$contexte = Input::post('contexte');
			else : $erreurs .= "&erreur_contexte";
			endif;
		else : $contexte = "";
		endif;

		if (Input::post('commentaire_contexte') != NULL) :
			$commentaire_contexte = trim(strip_tags(Input::post('commentaire_contexte')));
			$valeurs .= '&commentaire_contexte=' . $commentaire_contexte;
		else : $commentaire_contexte = "";
		endif;

		if (Input::post('accessoire_vestimentaire_et_parure') != NULL) :
			$valeurs .= '&accessoire_vestimentaire_et_parure';
			$accessoire_vesti = Input::post('accessoire_vestimentaire_et_parure');
		else : $accessoire_vesti = NULL;
		endif;

		if (Input::post('armement_objet_de_prestige') != NULL) :
			$valeurs .= '&armement_objet_de_prestige';
			$armement = Input::post('armement_objet_de_prestige');
		else : $armement = NULL;
		endif;

		if (Input::post('depot_de_recipient') != NULL) :
			$valeurs .= '&depot_de_recipient';
			$depot_recipient = Input::post('depot_de_recipient');
		else : $depot_recipient = NULL;
		endif;

		if (Input::post('autre_mobilier') != NULL) :
			$valeurs .= '&autre_mobilier';
			if (Input::post('description_autre_mobilier') != NULL) :
				$autre_mobilier = Input::post('autre_mobilier');
				$description_autre_mobilier = trim(strip_tags(Input::post('description_autre_mobilier')));
				$valeurs .= '&description_autre_mobilier=' . $description_autre_mobilier;
			else : $erreurs .= "&erreur_description_mobilier_vide";
			endif;
		else : $autre_mobilier = NULL;
		endif;


		if (Input::post('num_inventaire') != NULL) :
			if (Helper::verifAlpha(Input::post('num_inventaire'), 'alphanum') != false) :
				$num_inventaire = Helper::verifAlpha(Input::post('num_inventaire'), 'alphanum');
				$valeurs .= '&num_inventaire=' . $num_inventaire;
			else : $erreurs .= "&erreur_alpha_num_inventaire";
			endif;
		else : $num_inventaire = NULL;
		endif;

		if (Input::post('commune_depot') != NULL) :
			//Permet de récupérer juste le nom de la commune et non avec le département
			$nom_commune_depot = preg_replace("(\((.*?)\))", "", Input::post('commune_depot'));
			//Récupère l'ID de la commune 
			$query = DB::query('SELECT id FROM commune WHERE nom="' . $nom_commune_depot . '"');
			$if_commune_depot_ex = $query->execute();
			$if_commune_depot_ex = $if_commune_depot_ex->_results;
			//Vérifie quelle existe dans la BDD
			if (!empty($if_commune_depot_ex)) : $valeurs .= '&commune_depot=' . $nom_commune_depot;
				$id_commune_depot = $if_commune_depot_ex[0]['id'];
			else : $erreurs .= "&erreur_commune_depot";
			endif;
		else : $id_commune_depot = NULL;
		endif;

		if (Input::post('adresse_depot') != NULL) :
			if (Helper::verifAlpha(Input::post('adresse_depot'), 'alphatout') != false) :
				$adresse_depot = Helper::verifAlpha(Input::post('adresse_depot'), 'alphatout');
				$valeurs .= '&adresse_depot=' . $adresse_depot;
			else : $erreurs .= "&erreur_alpha_adresse_depot";
			endif;
		else : $adresse_depot = NULL;
		endif;


		if (Input::post('trepanation') != NULL) :
			$valeurs .= '&trepanation';
			$trepanation = Input::post('trepanation');
			//Ajout de la localisation de la pathologie n°1 à la clé numéro 1 avec l'information de son ID
			$localisation[1] += array('crane' => 1);
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic  
			array_push($description_diagnostic_tab, 'Trépanation');
		else : $trepanation = NULL;
		endif;

		if (Input::post('edentement_complet') != NULL) :
			$valeurs .= '&edentement_complet';
			$edentement_complet = Input::post('edentement_complet');
			//Ajout de la localisation de la pathologie n°2 à la clé numéro 2 avec l'information de son ID
			$localisation[2] += array('crane' => 1);
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic   
			array_push($description_diagnostic_tab, 'Édentement complet');
		else : $edentement_complet = NULL;
		endif;

		if (Input::post('atteinte_neurale') != NULL) :
			$valeurs .= '&atteinte_neurale';
			$atteinte_neurale = Input::post('atteinte_neurale');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'Atteinte neurale');
		else : $atteinte_neurale = NULL;
		endif;

		if (Input::post('scoliose_severe') != NULL) :
			$valeurs .= '&scoliose_severe';
			$scoliose_severe = Input::post('scoliose_severe');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'Scoliose sévère');
		else : $scoliose_severe = NULL;
		endif;

		if (Input::post('maladie_de_paget_ou_osteite_deformante') != NULL) :
			$valeurs .= '&paget';
			$paget = Input::post('maladie_de_paget_ou_osteite_deformante');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'Maladie de paget/Ostéite déformante');
		else : $paget = NULL;
		endif;

		if (Input::post('dish') != NULL) :
			$valeurs .= '&dish';
			$dish = Input::post('dish');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'DISH');
		else : $dish = NULL;
		endif;

		if (Input::post('rachitisme') != NULL) :
			$valeurs .= '&rachitisme';
			$rachitisme = Input::post('rachitisme');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'Rachitisme');
		else : $rachitisme = NULL;
		endif;

		if (Input::post('nanisme') != NULL) :
			$valeurs .= '&nanisme';
			$nanisme = Input::post('nanisme');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'Nanisme');
		else : $nanisme = NULL;
		endif;

		if (Input::post('pathologie_infectieuse') != NULL) :
			$valeurs .= '&pathologie_infectieuse';
			//Vérifie si une des options de la pathologie infectieuse est active
			if (Input::post('PI_lepre') != NULL || Input::post('PI_syphilis') != NULL || Input::post('PI_variole') != NULL || Input::post('PI_tuberculose') != NULL || Input::post('PI_autre_pathologie_infectieuse') != NULL) :

				//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
				array_push($description_diagnostic_tab, 'Pathologie infectieuse');
				$pathologie_infectieuse = Input::post('pathologie_infectieuse');

				//Vérifie pour chaque option si elle est active
				if (Input::post('PI_lepre') != NULL) :
					$valeurs .= '&PI_lepre';
					$lepre = Input::post('PI_lepre');
					//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
					array_push($description_patho_tab, 'Lèpre');
				else : $lepre = NULL;
				endif;
				if (Input::post('PI_syphilis') != NULL) :
					$valeurs .= '&PI_syphilis';
					$syphilis = Input::post('PI_syphilis');
					//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
					array_push($description_patho_tab, 'Syphilis');
				else : $syphilis = NULL;
				endif;
				if (Input::post('PI_variole') != NULL) :
					$valeurs .= '&PI_variole';
					$variole = Input::post('PI_variole');
					//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic  
					array_push($description_patho_tab, 'Variole');
				else : $variole = NULL;
				endif;
				if (Input::post('PI_tuberculose') != NULL) :
					$valeurs .= '&PI_tuberculose';
					$tuberculose = Input::post('PI_tuberculose');
					//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
					array_push($description_patho_tab, 'Tuberculose');
				else : $tuberculose = NULL;
				endif;
				if (Input::post('PI_autre_pathologie_infectieuse') != NULL) :
					$valeurs .= '&PI_autre_pathologie_infectieuse';
					$autre_patho_infectieuse = Input::post('PI_autre_pathologie_infectieuse');
					//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
					array_push($description_patho_tab, 'Autre pathologie');
				else : $autre_patho_infectieuse = NULL;
				endif;

			else : $erreurs .= '&erreur_pathologie';
			endif;
		else : $pathologie_infectieuse = NULL;
		endif;

		if (Input::post('fracture_non_reduite') != NULL) :
			$valeurs .= '&fracture_non_reduite';
			$fracture_non_reduite = Input::post('fracture_non_reduite');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic  
			array_push($description_diagnostic_tab, 'Fracture non réduite');
		else : $fracture_non_reduite = NULL;
		endif;

		if (Input::post('amputation') != NULL) :
			$valeurs .= '&amputation';
			$amputation = Input::post('amputation');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'Amputation');
		else : $amputation = NULL;
		endif;

		if (Input::post('pathologie_degenerative_severe') != NULL) :
			$valeurs .= '&pathologie_severe';
			$pathologie_severe = Input::post('pathologie_severe');
			//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
			array_push($description_diagnostic_tab, 'Pathologie dégénérative sévère');
		else : $pathologie_severe = NULL;
		endif;

		if (Input::post('autre_atteinte') != NULL) :
			$valeurs .= '&autre_atteinte';
			//Vérifie que le champs de la description de l'autre atteinte est non vide pour valider l'ajout
			if (!empty(Input::post('description_autre_atteinte'))) :
				$autre_atteinte = Input::post('autre_atteinte');
				//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic 
				array_push($description_diagnostic_tab, 'Autre atteinte');

				$description_autre_atteinte = trim(strip_tags(Input::post('description_autre_atteinte')));
				$valeurs .= '&description_autre_atteinte=' . $description_autre_atteinte;
				//Ajout du nom de la pathologie dans le tableau permettant de construire la phrase qui s'ajoute au commentaire du diagnostic  
				array_push($description_diagnostic_tab, $description_autre_atteinte);

			else : $erreurs .= "&erreur_description_autre_atteinte";
			endif;
		else : $autre_atteinte = NULL;
		endif;

		//Permet de construire la commentaire du diagnostic
		if (!empty($description_diagnostic_tab)) :
			foreach ($description_diagnostic_tab as $key => $val) :
				$ajout_description_diagnostic = "";
				//Vérifie si la clé correspond à la dernière clé du tableau et que la valeur ne soit pas égal à Pathologie infectieuse et Autre atteinte  
				if (array_key_last($description_diagnostic_tab) == $key && $val != "Pathologie infectieuse" && $val != "Autre atteinte") :
					//Fait un espace avec le diagnostic précédant et met un point à la fin 
					$ajout_description_diagnostic .= " " . $val . '.';
				elseif ($val == "Pathologie infectieuse") :
					$ajout_description_diagnostic .= $val . ': ';
					foreach ($description_patho_tab as $key2 => $val2) :
						//Vérifie si la clé correspond à la dernière clé du tableau 
						if (array_key_last($description_patho_tab) == $key2) : $ajout_description_diagnostic .= " " . $val2 . '. ';
						else : $ajout_description_diagnostic .= $val2 . ', ';
						endif;
					endforeach;
				elseif ($val == "Autre atteinte") :
					$ajout_description_diagnostic .= $val . ': ';
				else : $ajout_description_diagnostic .= $val . ', ';
				endif;

				//Permet d'ajouter le commentaire à la construction automatique du commentaire
				if (!empty(Input::post('commentaire_diagnostic'))) :
					$commentaire_diagnostic = trim(strip_tags(Input::post('commentaire_diagnostic')));
					$commentaire_diagnostic = $ajout_description_diagnostic . ' ' . $commentaire_diagnostic . '.';
				else : $commentaire_diagnostic = $ajout_description_diagnostic;
				endif;
			endforeach;
		else : $commentaire_diagnostic = "";
		endif;
		$valeurs .= '&commentaire_diagnostic=' . Input::post('commentaire_diagnostic');


		if (Input::post('crane') != NULL) :
			foreach (Input::post('crane') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie la localisation correspond
				$valeurs .= '&crane_' . $key;
				//Ajout de la localisation de la pathologie n°X à la clé numéro X avec l'information de son ID
				$localisation[$key] += array('crane' => 1);
			endforeach;
		endif;

		if (Input::post('membre_superieur_droit') != NULL) :
			foreach (Input::post('membre_superieur_droit') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie la localisation correspond
				$valeurs .= '&msd_' . $key;
				//Ajout de la localisation de la pathologie n°X à la clé numéro X avec l'information de son ID
				$localisation[$key] += array('membre_superieur_droit' => 2);
			endforeach;
		endif;

		if (Input::post('membre_superieur_gauche') != NULL) :
			foreach (Input::post('membre_superieur_gauche') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie la localisation correspond
				$valeurs .= '&msg_' . $key;
				//Ajout de la localisation de la pathologie n°X à la clé numéro X avec l'information de son ID
				$localisation[$key] += array('membre_superieur_gauche' => 3);
			endforeach;
		endif;

		if (Input::post('tronc_bassin') != NULL) :
			foreach (Input::post('tronc_bassin') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie la localisation correspond
				$valeurs .= '&tronc_bassin_' . $key;
				//Ajout de la localisation de la pathologie n°X à la clé numéro X avec l'information de son ID
				$localisation[$key] += array('tronc_bassin' => 4);
			endforeach;
		endif;

		if (Input::post('membre_inferieur_droit') != NULL) :
			foreach (Input::post('membre_inferieur_droit') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie la localisation correspond
				$valeurs .= '&mid_' . $key;
				//Ajout de la localisation de la pathologie n°X à la clé numéro X avec l'information de son ID
				$localisation[$key] += array('membre_inferieur_droit' => 5);
			endforeach;
		endif;

		if (Input::post('membre_inferieur_gauche') != NULL) :
			foreach (Input::post('membre_inferieur_gauche') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie la localisation correspond
				$valeurs .= '&mig_' . $key;
				//Ajout de la localisation de la pathologie n°X à la clé numéro X avec l'information de son ID
				$localisation[$key] += array('membre_inferieur_gauche' => 6);
			endforeach;
		endif;

		if (Input::post('attele') != NULL) :
			foreach (Input::post('attele') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie l'appareil correspond
				$valeurs .= '&attele_' . $key;
				//Ajout de l'appareil de la pathologie n°X à la clé numéro X avec l'information de son ID
				$appareil[$key] += array('attele' => 1);
			endforeach;
		endif;

		if (Input::post('prothese') != NULL) :
			foreach (Input::post('prothese') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie l'appareil correspond
				$valeurs .= '&prothese_' . $key;
				//Ajout de l'appareil de la pathologie n°X à la clé numéro X avec l'information de son ID
				$appareil[$key] += array('prothese' => 2);
			endforeach;
		endif;

		if (Input::post('orthese') != NULL) :
			foreach (Input::post('orthese') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie l'appareil correspond
				$valeurs .= '&orthese_' . $key;
				//Ajout de l'appareil de la pathologie n°X à la clé numéro X avec l'information de son ID
				$appareil[$key] += array('orthese' => 3);
			endforeach;
		endif;

		if (Input::post('bequillage') != NULL) :
			foreach (Input::post('bequillage') as $key => $val) :
				//L'ajout de la clé à la valeur permet d'identifier à quelle pathologie l'appareil correspond
				$valeurs .= '&bequillage_' . $key;
				//Ajout de l'appareil de la pathologie n°X à la clé numéro X avec l'information de son ID
				$appareil[$key] += array('bequillage' => 4);
			endforeach;
		endif;

		if (Input::post('commentaire_appareil') != NULL) :
			$valeurs .= '&commentaire_appareil=' . Input::post('commentaire_appareil');
			$commentaire_appareil = Input::post('commentaire_appareil');
		else : $commentaire_appareil = "";
		endif;

		//Vérifie si la variable erreurs est vide pour continuer
		if (empty($erreurs)) {
			//Récupération de l'ID de l'opération
			$query = DB::query('SELECT id_operation FROM groupe_sujets WHERE id_groupe_sujets=' . $modif_sujet[0]['id_groupe_sujets'] . '  ');
			$id_operation = $query->execute();
			$id_operation = $id_operation->_results[0]['id_operation'];
			//Récupération de l'ID du groupe du sujet
			$groupe_sujets = DB::query('SELECT id_groupe_sujets FROM groupe_sujets WHERE id_chronologie=' . $periode_chrono . ' AND id_operation=' . $id_operation . ' AND NMI=' . $NMI . ' ')->execute();
			$groupe_sujets = $groupe_sujets->_results;
			//Si le résultat est vide nous l'ajoutons à la base de données et récupérons son ID. Et si il existe, nous récupérons son ID
			if (!empty($groupe_sujets)) : $num_groupe_sujet = $groupe_sujets[0]['id_groupe_sujets'];
			else :
				$ajout_groupe_sujets = DB::insert('groupe_sujets', array('id_groupe_sujets', 'id_chronologie', 'id_operation', 'NMI'))->values(array('id_groupe_sujets' => NULL, $periode_chrono, $id_operation, $NMI))->execute();

				$groupe_sujets = DB::query('SELECT id_groupe_sujets FROM groupe_sujets WHERE id_chronologie=' . $periode_chrono . ' AND id_operation=' . $id_operation . ' AND NMI=' . $NMI . ' ')->execute();
				$num_groupe_sujet = $groupe_sujets->_results[0]['id_groupe_sujets'];
			endif;

			//Construction de la datation et calcule de son écart type
			if (!empty($datation_debut) &&  !empty($datation_fin)) :
				$datation = '[' . $datation_debut . ';' . $datation_fin . ']';
				$datation_ecart_type = abs(abs($datation_debut) - abs($datation_fin));
			else :
				$datation = "";
				$datation_ecart_type = 0;
			endif;

			//Pour la modification des informations du sujet, nous supprimons tout ses informations pour les remplacer par les nouvelles
			$query = DB::delete('accessoire_sujet')->where('id_sujet_handicape', $id);
			$accessoire_sujet_supp = $query->execute();

			$query = DB::delete('appareil_sujet')->where('id_sujet_handicape', $id);
			$appareil_sujet_supp = $query->execute();

			$query = DB::delete('atteinte_invalidante')->where('id_sujet_handicape', $id);
			$atteinte_invalidante_supp = $query->execute();

			//Récupération de l'ID du commentaire du diagnostic pour pouvoir le supprimer
			$id_commentaire_diagnostic = DB::query('SELECT id_commentaire_diagnostic FROM atteinte_invalidante WHERE id_sujet_handicape="' . $id . '" ')->execute();

			if (!empty($id_commentaire_diagnostic['_results'])) :
				$query = DB::delete('commentaire_diagnostic')->where('id', $id_commentaire_diagnostic['_results'][0]['id_commentaire_diagnostic']);
				$commentaire_diagnostic_supp = $query->execute();
			endif;

			$query = DB::delete('localisation_sujet')->where('id_sujet_handicape', $id);
			$localisation_sujet_supp = $query->execute();

			$sujet_handicap = DB::delete('sujet_handicape')->where('id', $id)->execute();

			$query = DB::delete('depot')->where('id', $modif_sujet[0]['id_depot']);
			$depot_supp = $query->execute();

			//Ajout des nouvelles informations
			$ajout_depot = DB::insert('depot', array('id', 'num_inventaire', 'id_commune', 'adresse'))->values(array('id' => NULL, $num_inventaire, $id_commune_depot, $adresse_depot))->execute();
			$id_depot = $ajout_depot[0];

			$sujet_handicap = DB::insert('sujet_handicape', array('id', 'id_sujet_handicape', 'age_min', 'age_max', 'sexe', 'datation', 'datation_ecart_type', 'milieu_vie', 'contexte', 'contexte_normatif', 'commentaire_contexte', 'url_illustration', 'id_type_depot', 'id_sepulture', 'id_depot', 'id_groupe_sujets'))->values(array('id' => NULL, $id_sujet, $age_min, $age_max, $sexe, $datation, $datation_ecart_type, $milieu_vie, $contexte, $contexte_normatif, $commentaire_contexte, NULL, $type_depot, $type_sepulture, $id_depot, $num_groupe_sujet))->execute();

			//Initialisation de l'ID du sujet
			$id_sujet_handicape = $sujet_handicap[0];

			//Pour les accessoires, vérifie si chaque accessoire a été coché pour l'ajouter
			if ($accessoire_vesti != NULL) :
				$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $accessoire_vesti, ""))->execute();
			endif;
			if ($armement != NULL) :
				$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $armement, ""))->execute();
			endif;
			if ($depot_recipient != NULL) :
				$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $depot_recipient, ""))->execute();
			endif;
			if ($autre_mobilier != NULL) :
				$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $autre_mobilier, $description_autre_mobilier))->execute();
			endif;

			$ajout_commentaire_diagnostic = DB::insert('commentaire_diagnostic', array('id', 'commentaire_diagnostic'))->values(array('id' => NULL, $commentaire_diagnostic))->execute();
			$id_commentaire_diagnostic = $ajout_commentaire_diagnostic[0];

			//Pour les pathologies, vérifie si chaque pathologie a été coché pour l'ajouter
			if ($trepanation != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $trepanation, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($edentement_complet != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $edentement_complet, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($atteinte_neurale != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $atteinte_neurale, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($scoliose_severe != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $scoliose_severe, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($paget != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $paget, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($dish != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $dish, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($rachitisme != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $rachitisme, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($nanisme != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $nanisme, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($pathologie_infectieuse != NULL) :
				//Pour les pathologies infectieuses, vérifie si chaque pathologie infectieuse a été coché pour l'ajouter
				if ($lepre != NULL) :
					$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $pathologie_infectieuse, $lepre, "", $id_commentaire_diagnostic))->execute();
				endif;
				if ($syphilis != NULL) :
					$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $pathologie_infectieuse, $syphilis, "", $id_commentaire_diagnostic))->execute();
				endif;
				if ($variole != NULL) :
					$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $pathologie_infectieuse, $variole, "", $id_commentaire_diagnostic))->execute();
				endif;
				if ($tuberculose != NULL) :
					$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $pathologie_infectieuse, $tuberculose, "", $id_commentaire_diagnostic))->execute();
				endif;
				if ($autre_patho_infectieuse != NULL) :
					$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $pathologie_infectieuse, $autre_patho_infectieuse, "", $id_commentaire_diagnostic))->execute();
				endif;
			endif;
			if ($fracture_non_reduite != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $fracture_non_reduite, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($amputation != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $amputation, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($pathologie_severe != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $pathologie_severe, NULL, "", $id_commentaire_diagnostic))->execute();
			endif;
			if ($autre_atteinte != NULL) :
				$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $autre_atteinte, NULL, $description_autre_atteinte, $id_commentaire_diagnostic))->execute();
			endif;

			//Pour les localisations, ajoute chaque localisation qui a été coché
			foreach ($localisation as $key => $val) :
				foreach ($val as $key2 => $val2) {
					$ajout_localisation = DB::insert('localisation_sujet', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_localisation_atteinte'))->values(array('id' => NULL, $id_sujet_handicape, $key, $val2))->execute();
				}
			endforeach;
			//Pour les appareils, ajoute chaque appareil qui a été coché
			foreach ($appareil as $key => $val) :
				foreach ($val as $key2 => $val2) {
					$ajout_localisation = DB::insert('appareil_sujet', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_appareil_compensatoire', 'commentaire'))->values(array('id' => NULL, $id_sujet_handicape, $key, $val2, $commentaire_appareil))->execute();
				}
			endforeach;

			Response::redirect('/operations/view/' . $id_operation . '?&success_modif');
		} elseif (!empty($erreurs)) {
			//Redirection en cas d'erreur avec les informations des erreurs et des valeurs dans l'url
			// Response::redirect('/sujet/edit/' . $id . '?erreur=' . $erreurs . '' . $valeurs . '');
		} else {
			Response::redirect('/operations');
		}

		$data = array('modif_sujet' => $modif_sujet, 'depot_sujet' => $depot_sujet, 'atteinte_invalidante_sujet' => $atteinte_invalidante_sujet, 'commentaire_diagnostic' => $commentaire_diagnostic_sujet, 'accessoire_sujet' => $accessoire_sujet, 'localisation_sujet' => $localisation_sujet, 'appareil_sujet' => $appareil_sujet, 'chronologie' => $chronologie, 'type_depot' => $type_depot, 'type_sepulture' => $type_sepulture, 'accessoire' => $accessoire, 'diagnostic' => $diagnostic, 'localisation_atteinte' => $localisation_atteinte, 'pathologie' => $pathologie, 'appareil_compensatoire' => $appareil_compensatoire);

		$this->template->title = 'Modification du sujet';
		$this->template->content = View::forge('sujet/edit', $data);
	}

	//Pour l'ajout d'un sujet, nous reprennons le même principe que pour l'ajout d'une opération et de sujet sans l'aspect de l'opération
	public function action_add($id) {
		
		
		#region OldGarbage
		$chronologie = DB::query('SELECT id_chronologie,nom_chronologie FROM chronologie_site ORDER BY nom_chronologie ASC')->execute();
		$chronologie = $chronologie->_results;

		$type_depot = DB::query('SELECT * FROM type_depot')->execute();
		$type_depot = $type_depot->_results;

		$type_sepulture = DB::query('SELECT * FROM type_sepulture')->execute();
		$type_sepulture = $type_sepulture->_results;

		$diagnostic = DB::query('SELECT * FROM diagnostic')->execute();
		$diagnostic = $diagnostic->_results;

		$accessoire = DB::query('SELECT * FROM mobilier_archeologique')->execute();
		$accessoire = $accessoire->_results;

		$localisation_atteinte = DB::query('SELECT * FROM localisation_atteinte')->execute();
		$localisation_atteinte = $localisation_atteinte->_results;

		$pathologie = DB::query('SELECT * FROM pathologie')->execute();
		$pathologie = $pathologie->_results;

		$appareil_compensatoire = DB::query('SELECT * FROM appareil_compensatoire')->execute();
		$appareil_compensatoire = $appareil_compensatoire->_results;

		$erreurs = $valeurs = "";

		if (Input::post("confirm_sujet_handicape")) {

			$nb = array_key_last(Input::post('NMI'));
			for ($i = 1; $i <= $nb; $i++) : $array[$i] = array();
				$description_diagnostic_tab[$i] = array();
				$commentaire_appareil[$i] = array();
				for ($a = 1; $a <= 13; $a++) : $localisation[$i][$a] = array();
					$appareil[$i][$a] = array();
				endfor;
				$description_patho_tab[$i] = array();
			endfor;

			foreach (Input::post('NMI') as $key => $val) :
				if ($val != null) :
					if (is_numeric($val)) : $valeurs .= '&NMI_' . $key . '=' . $val;
						$array[$key] += array('NMI' => $val);
					else : $erreurs .= "&erreur_NMI_" . $key;
					endif;
				else : $erreurs .= "&erreur_NMI_vide_" . $key;
				endif;
			endforeach;

			foreach (Input::post('nom_chronologie') as $key => $val) :
				if ($val != null) :
					$if_chrono_ex = DB::query('SELECT nom_chronologie FROM chronologie_site WHERE id_chronologie=' . $val . ' ')->execute();
					$if_chrono_ex = $if_chrono_ex->_results;
					if (!empty($if_chrono_ex)) : $valeurs .= '&chronologie_' . $key . '=' . $val;
						$array[$key] += array('chronologie' => $val);
					else : $erreurs .= "&erreur_chrono_" . $key;
					endif;
				else : $erreurs .= "&erreur_chrono_vide_" . $key;
				endif;
			endforeach;
			
			foreach (Input::post('id_sujet') as $key => $val) :
				if ($val != null) :
					if (Helper::verifAlpha($val, 'alphanum') != false) :
						$val = Helper::verifAlpha($val, 'alphanum');
						$valeurs .= '&id_sujet_' . $key . '=' . $val;
						$array[$key] += array('id_sujet' => $val);
					else : $erreurs .= "&erreur_alpha_sujet_" . $key;
					endif;
				else : $erreurs .= "&erreur_sujet_vide_" . $key;
				endif;
			endforeach;

			foreach (Input::post('sexe') as $key => $val) :
				if ($val != null) :
					if ($val == "Femme" || $val == "Homme" || $val == "Indéterminé") : $valeurs .= '&sexe_' . $key . '=' . $val;
						$array[$key] += array('sexe' => $val);
					else : $erreurs .= "&erreur_sexe_" . $key;
					endif;
				else : $erreurs .= "&erreur_sexe_vide_" . $key;
				endif;
			endforeach;

			foreach (Input::post('age_min') as $key => $val) :
				if (!empty($val)) :
					if (is_numeric($val) && $val > 0 && $val < 130) : $valeurs .= '&age_min_' . $key . '=' . $val;
						$array[$key] += array('age_min' => $val);
					else : $erreurs .= "&erreur_age_min_" . $key;
					endif;
				else : $array[$key] += array('age_min' => "");
				endif;
			endforeach;

			foreach (Input::post('age_max') as $key => $val) :
				if (!empty($val)) :
					if (is_numeric($val) && $val > 0 && $val < 130 && $val > $array[$key]['age_min']) : $valeurs .= '&age_max_' . $key . '=' . $val;
						$array[$key] += array('age_max' => $val);
					else : $erreurs .= "&erreur_age_max_" . $key;
					endif;
				else : $array[$key] += array('age_max' => "");
				endif;
			endforeach;

			foreach (Input::post('datation_debut') as $key => $val) :
				if (!empty($val)) :
					if (is_numeric($val)) : $valeurs .= '&datation_debut_' . $key . '=' . $val;
						$array[$key] += array('datation_debut' => $val);
					else : $erreurs .= "&erreur_datation_debut_" . $key;
					endif;
				else :  $array[$key] += array('datation_debut' => "");
				endif;
			endforeach;

			foreach (Input::post('datation_fin') as $key => $val) :
				if (!empty($val)) :
					if (is_numeric($val) && $val > $array[$key]['datation_debut']) : 	$valeurs .= '&datation_fin_' . $key . '=' . $val;
						$array[$key] += array('datation_fin' => $val);
					else : $erreurs .= "&erreur_datation_fin_" . $key;
					endif;
				else :  $array[$key] += array('datation_fin' => $val);
				endif;
			endforeach;

			foreach (Input::post('type_depot') as $key => $val) :
				if ($val != null) :
					$if_depot_ex = DB::query('SELECT nom FROM type_depot WHERE id=' . $val . ' ')->execute();
					$if_depot_ex = $if_depot_ex->_results;
					if (!empty($if_depot_ex)) : $valeurs .= '&type_depot_' . $key . '=' . $val;
						$array[$key] += array('type_depot' => $val);
					else : $erreurs .= "&erreur_depot_" . $key;
					endif;
				else : $erreurs .= "&erreur_depot_vide_" . $key;
				endif;
			endforeach;

			foreach (Input::post('type_sepulture') as $key => $val) :
				if ($val != null) :
					$if_sepulture_ex = DB::query('SELECT nom FROM type_sepulture WHERE id=' . $val . ' ')->execute();
					$if_sepulture_ex = $if_sepulture_ex->_results;
					if (!empty($if_sepulture_ex)) : $valeurs .= '&type_sepulture_' . $key . '=' . $val;
						$array[$key] += array('type_sepulture' => $val);
					else : $erreurs .= "&erreur_sepulture_" . $key;
					endif;
				else : $erreurs .= "&erreur_sepulture_vide_" . $key;
				endif;
			endforeach;

			foreach (Input::post('contexte_normatif') as $key => $val) :
				if ($val != null) :
					if ($val == "Standard" || $val == "Atypique") : $valeurs .= '&contexte_normatif_' . $key . '=' . $val;
						$array[$key] += array('contexte_normatif' => $val);
					else : $erreurs .= "&erreur_contexte_normatif_" . $key;
					endif;
				else : $array[$key] += array('contexte_normatif' => "");
				endif;
			endforeach;

			foreach (Input::post('milieu_vie') as $key => $val) :
				if ($val != null) :
					if ($val == "Rural" || $val == "Urbain") : $valeurs .= '&milieu_vie_' . $key . '=' . $val;
						$array[$key] += array('milieu_vie' => $val);
					else : $erreurs .= "&erreur_milieu_vie_" . $key;
					endif;
				else : $array[$key] += array('milieu_vie' => "");
				endif;
			endforeach;

			foreach (Input::post('contexte') as $key => $val) :
				if ($val != null) :
					if ($val == "Funeraire" || $val == "Domestique" || $val == "Autre") : $valeurs .= '&contexte_' . $key . '=' . $val;
						$array[$key] += array('contexte' => $val);
					else : $erreurs .= "&erreur_contexte_" . $key;
					endif;
				else : $array[$key] += array('contexte' => "");
				endif;
			endforeach;

			foreach (Input::post('commentaire_contexte') as $key => $val) :
				if ($val != null) :
					$val = trim(strip_tags($val));
					$valeurs .= '&commentaire_contexte_' . $key . '=' . $val;
					$array[$key] += array('commentaire_contexte' => $val);
				else : $array[$key] += array('commentaire_contexte' => "");
				endif;
			endforeach;


			if (Input::post('accessoire_vestimentaire_et_parure') != NULL) :
				foreach (Input::post('accessoire_vestimentaire_et_parure') as $key => $val) :
					$valeurs .= '&accessoire_vestimentaire_et_parure_' . $key;
					$array[$key] += array('accessoire_vestimentaire_et_parure' => $val);
				endforeach;
			endif;

			if (Input::post('armement_objet_de_prestige') != NULL) :
				foreach (Input::post('armement_objet_de_prestige') as $key => $val) :
					$valeurs .= '&armement_objet_de_prestige_' . $key;
					$array[$key] += array('armement_objet_de_prestige' => $val);
				endforeach;
			endif;

			if (Input::post('depot_de_recipient') != NULL) :
				foreach (Input::post('depot_de_recipient') as $key => $val) :
					$valeurs .= '&depot_de_recipient_' . $key;
					$array[$key] += array('depot_de_recipient' => $val);
				endforeach;
			endif;

			if (Input::post('autre_mobilier') != NULL) :
				foreach (Input::post('autre_mobilier') as $key => $val) :
					$valeurs .= '&autre_mobilier_' . $key;
					if (Input::post('description_autre_mobilier') != NULL) :
						if (!empty(Input::post('description_autre_mobilier')[$key])) :
							$description_autre_mobilier = trim(strip_tags(Input::post('description_autre_mobilier')[$key]));
							$array[$key] += array('autre_mobilier' => $val);
							$valeurs .= '&description_autre_mobilier_' . $key . '=' . $description_autre_mobilier;
							$array[$key] += array('description_autre_mobilier' => $description_autre_mobilier);
						else : $erreurs .= "&erreur_description_mobilier_vide_" . $key;
						endif;
					endif;
				endforeach;
			endif;

			if (Input::post('num_inventaire') != NULL) :
				foreach (Input::post('num_inventaire') as $key => $val) :
					if (!empty($key)) :
						if (Helper::verifAlpha($val, 'alphanum') != false) :
							$val = Helper::verifAlpha($val, 'alphanum');
							$valeurs .= '&num_inventaire_' . $key . '=' . $val;
							$array[$key] += array('num_inventaire' => $val);
						else : $erreurs .= "&erreur_alpha_num_inventaire_" . $key;
						endif;
					endif;
				endforeach;
			endif;

			if (Input::post('commune_depot') != NULL) :
				foreach (Input::post('commune_depot') as $key => $val) :
					if (!empty($val)) :
						$nom_commune_depot = preg_replace("(\((.*?)\))", "", $val);

						$query = DB::query('SELECT id FROM commune WHERE nom="' . $nom_commune_depot . '"');
						$if_commune_depot_ex = $query->execute();
						$if_commune_depot_ex = $if_commune_depot_ex->_results;

						if (!empty($if_commune_depot_ex)) : $valeurs .= '&commune_depot_' . $key . '=' . $nom_commune_depot;
							$array[$key] += array('commune_depot' => $if_commune_depot_ex[0]['id']);
						else : $erreurs .= "&erreur_commune_depot_" . $key;
						endif;

					else : $array[$key] += array('commune_depot' => "");
					endif;

				endforeach;
			endif;

			if (Input::post('adresse_depot') != NULL) :
				foreach (Input::post('adresse_depot') as $key => $val) :
					if (!empty($key)) :
						if (Helper::verifAlpha($val, 'alphatout') != false) :
							$val = Helper::verifAlpha($val, 'alphatout');
							$valeurs .= '&adresse_depot_' . $key . '=' . $val;
							$array[$key] += array('adresse_depot' => $val);
						else : $erreurs .= "&erreur_alpha_adresse_depot_" . $key;
						endif;
					endif;
				endforeach;
			endif;

			if (Input::post('trepanation') != NULL) :
				foreach (Input::post('trepanation') as $key => $val) :
					$valeurs .= '&trepanation_' . $key;
					$array[$key] += array('trepanation' => $val);
					$localisation[$key][1] += array('crane' => 1);
					array_push($description_diagnostic_tab[$key], 'Trépanation');
				endforeach;
			endif;

			if (Input::post('edentement_complet') != NULL) :
				foreach (Input::post('edentement_complet') as $key => $val) :
					$valeurs .= '&edentement_complet_' . $key;
					$array[$key] += array('edentement_complet' => $val);
					$localisation[$key][2] += array('crane' => 1);
					array_push($description_diagnostic_tab[$key], 'Édentement complet');
				endforeach;
			endif;

			if (Input::post('atteinte_neurale') != NULL) :
				foreach (Input::post('atteinte_neurale') as $key => $val) :
					$valeurs .= '&atteinte_neurale_' . $key;
					$array[$key] += array('atteinte_neurale' => $val);
					array_push($description_diagnostic_tab[$key], 'Atteinte neurale');
				endforeach;
			endif;

			if (Input::post('scoliose_severe') != NULL) :
				foreach (Input::post('scoliose_severe') as $key => $val) :
					$valeurs .= '&scoliose_severe_' . $key;
					$array[$key] += array('scoliose_severe' => $val);
					array_push($description_diagnostic_tab[$key], 'Scoliose sévère');
				endforeach;
			endif;

			if (Input::post('maladie_de_paget_ou_osteite_deformante') != NULL) :
				foreach (Input::post('maladie_de_paget_ou_osteite_deformante') as $key => $val) :
					$valeurs .= '&paget_' . $key;
					$array[$key] += array('maladie_de_paget_ou_osteite_deformante' => $val);
					array_push($description_diagnostic_tab[$key], 'Maladie de paget/Ostéite déformante');
				endforeach;
			endif;

			if (Input::post('dish') != NULL) :
				foreach (Input::post('dish') as $key => $val) :
					$valeurs .= '&dish_' . $key;
					$array[$key] += array('dish' => $val);
					array_push($description_diagnostic_tab[$key], 'DISH');
				endforeach;
			endif;

			if (Input::post('rachitisme') != NULL) :
				foreach (Input::post('rachitisme') as $key => $val) :
					$valeurs .= '&rachitisme_' . $key;
					$array[$key] += array('rachitisme' => $val);
					array_push($description_diagnostic_tab[$key], 'Rachitisme');
				endforeach;
			endif;

			if (Input::post('nanisme') != NULL) :
				foreach (Input::post('nanisme') as $key => $val) :
					$valeurs .= '&nanisme_' . $key;
					$array[$key] += array('nanisme' => $val);
					array_push($description_diagnostic_tab[$key], 'Nanisme');
				endforeach;
			endif;

			if (Input::post('pathologie_infectieuse') != NULL) :
				foreach (Input::post('pathologie_infectieuse') as $key => $val) :
					$valeurs .= '&pathologie_infectieuse_' . $key;

					if (!empty(Input::post('PI_lepre')[$key]) || !empty(Input::post('PI_syphilis')[$key]) || !empty(Input::post('PI_variole')[$key]) || !empty(Input::post('PI_tuberculose')[$key]) || !empty(Input::post('PI_autre_pathologie_infectieuse')[$key])) :

						array_push($description_diagnostic_tab[$key], 'Pathologie infectieuse');
						$array[$key] += array('pathologie_infectieuse' => $val);

						if (!empty(Input::post('PI_lepre')[$key])) :
							$valeurs .= '&PI_lepre_' . $key;
							$array[$key] += array('lepre' => Input::post('PI_lepre')[$key]);
							array_push($description_patho_tab[$key], 'Lèpre');
						endif;
						if (!empty(Input::post('PI_syphilis')[$key])) :
							$valeurs .= '&PI_syphilis_' . $key;
							$array[$key] += array('syphilis' => Input::post('PI_syphilis')[$key]);
							array_push($description_patho_tab[$key], 'Syphilis');
						endif;
						if (!empty(Input::post('PI_variole')[$key])) :
							$valeurs .= '&PI_variole_' . $key;
							$array[$key] += array('variole' => Input::post('PI_variole')[$key]);
							array_push($description_patho_tab[$key], 'Variole');
						endif;
						if (!empty(Input::post('PI_tuberculose')[$key])) :
							$valeurs .= '&PI_tuberculose_' . $key;
							$array[$key] += array('tuberculose' => Input::post('PI_tuberculose')[$key]);
							array_push($description_patho_tab[$key], 'Tuberculose');
						endif;
						if (!empty(Input::post('PI_autre_pathologie_infectieuse')[$key])) :
							$valeurs .= '&PI_autre_pathologie_infectieuse_' . $key;
							$array[$key] += array('autre_pathologie_infectieuse' => Input::post('PI_autre_pathologie_infectieuse')[$key]);
							array_push($description_patho_tab[$key], 'Autre pathologie');
						endif;

					else : $erreurs .= '&erreur_pathologie_' . $key;
					endif;
				endforeach;
			endif;

			if (Input::post('fracture_non_reduite') != NULL) :
				foreach (Input::post('fracture_non_reduite') as $key => $val) :
					$valeurs .= '&fracture_non_reduite_' . $key;
					$array[$key] += array('fracture_non_reduite' => $val);
					array_push($description_diagnostic_tab[$key], 'Fracture non réduite');
				endforeach;
			endif;

			if (Input::post('amputation') != NULL) :
				foreach (Input::post('amputation') as $key => $val) :
					$valeurs .= '&amputation_' . $key;
					$array[$key] += array('amputation' => $val);
					array_push($description_diagnostic_tab[$key], 'Amputation');
				endforeach;
			endif;

			if (Input::post('pathologie_degenerative_severe') != NULL) :
				foreach (Input::post('pathologie_degenerative_severe') as $key => $val) :
					$valeurs .= '&pathologie_severe_' . $key;
					$array[$key] += array('pathologie_degenerative_severe' => $val);
					array_push($description_diagnostic_tab[$key], 'Pathologie dégénérative sévère');
				endforeach;
			endif;

			if (Input::post('autre_atteinte') != NULL) :
				foreach (Input::post('autre_atteinte') as $key => $val) :
					$valeurs .= '&autre_atteinte_' . $key;
					if (Input::post('description_autre_atteinte') != NULL) :
						if (!empty(Input::post('description_autre_atteinte')[$key])) :
							$array[$key] += array('autre_atteinte' => $val);
							array_push($description_diagnostic_tab[$key], 'Autre atteinte');

							$description_autre_atteinte = trim(strip_tags(Input::post('description_autre_atteinte')[$key]));
							$valeurs .= '&description_autre_atteinte_' . $key . '=' . $description_autre_atteinte;
							$array[$key] += array('description_autre_atteinte' => $description_autre_atteinte);
							array_push($description_diagnostic_tab[$key], $description_autre_atteinte);

						else : $erreurs .= "&erreur_description_autre_atteinte_" . $key;
						endif;
					endif;
				endforeach;
			endif;


			foreach ($description_diagnostic_tab as $key => $val) :
				$ajout_description_diagnostic = "";
				foreach ($val as $key2 => $val2) :
					if (array_key_last($val) == $key2 && $val2 != "Pathologie infectieuse" && $val2 != "Autre atteinte") :
						$ajout_description_diagnostic .= " " . $val2 . '.';
					elseif ($val2 == "Pathologie infectieuse") :
						$ajout_description_diagnostic .= $val2 . ': ';
						foreach ($description_patho_tab as $key3 => $val3) :
							foreach ($val3 as $key4 => $val4) :
								if (array_key_last($val3) == $key4) : $ajout_description_diagnostic .= " " . $val4 . '. ';
								else : $ajout_description_diagnostic .= $val4 . ', ';
								endif;
							endforeach;
						endforeach;
					elseif ($val2 == "Autre atteinte") :
						$ajout_description_diagnostic .= $val2 . ': ';
					else : $ajout_description_diagnostic .= $val2 . ', ';
					endif;
				endforeach;

				if (Input::post('commentaire_diagnostic') != NULL) :
					if (!empty(Input::post('commentaire_diagnostic')[$key])) :
						$commentaire_diagnostic = trim(strip_tags(Input::post('commentaire_diagnostic')[$key]));
						$array[$key] += array('commentaire_diagnostic' => $ajout_description_diagnostic . ' ' . $commentaire_diagnostic . '.');
						$valeurs .= '&commentaire_diagnostic_' . $key . '=' . $commentaire_diagnostic;
					else : $array[$key] += array('commentaire_diagnostic' => $ajout_description_diagnostic);
					endif;
				else : $array[$key] += array('commentaire_diagnostic' => $ajout_description_diagnostic);
				endif;
			endforeach;


			if (Input::post('crane') != NULL) :
				foreach (Input::post('crane') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&crane_' . $key . '_' . $key2;
						$localisation[$key][$key2] += array('crane' => 1);
					}
				endforeach;
			endif;

			if (Input::post('membre_superieur_droit') != NULL) :
				foreach (Input::post('membre_superieur_droit') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&msd_' . $key . '_' . $key2;
						$localisation[$key][$key2] += array('membre_superieur_droit' => 2);
					}
				endforeach;
			endif;

			if (Input::post('membre_superieur_gauche') != NULL) :
				foreach (Input::post('membre_superieur_gauche') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&msg_' . $key . '_' . $key2;
						$localisation[$key][$key2] += array('membre_superieur_gauche' => 3);
					}
				endforeach;
			endif;

			if (Input::post('tronc_bassin') != NULL) :
				foreach (Input::post('tronc_bassin') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&tronc_bassin_' . $key . '_' . $key2;
						$localisation[$key][$key2] += array('tronc_bassin' => 4);
					}
				endforeach;
			endif;

			if (Input::post('membre_inferieur_droit') != NULL) :
				foreach (Input::post('membre_inferieur_droit') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&mid_' . $key . '_' . $key2;
						$localisation[$key][$key2] += array('membre_inferieur_droit' => 5);
					}
				endforeach;
			endif;

			if (Input::post('membre_inferieur_gauche') != NULL) :
				foreach (Input::post('membre_inferieur_gauche') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&mig_' . $key . '_' . $key2;
						$localisation[$key][$key2] += array('membre_inferieur_gauche' => 6);
					}
				endforeach;
			endif;

			if (Input::post('attele') != NULL) :
				foreach (Input::post('attele') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&attele_' . $key . '_' . $key2;
						$appareil[$key][$key2] += array('attele' => 1);
					}
				endforeach;
			endif;

			if (Input::post('prothese') != NULL) :
				foreach (Input::post('prothese') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&prothese_' . $key . '_' . $key2;
						$appareil[$key][$key2] += array('prothese' => 2);
					}
				endforeach;
			endif;

			if (Input::post('orthese') != NULL) :
				foreach (Input::post('orthese') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&orthese_' . $key . '_' . $key2;
						$appareil[$key][$key2] += array('orthese' => 3);
					}
				endforeach;
			endif;

			if (Input::post('bequillage') != NULL) :
				foreach (Input::post('bequillage') as $key => $val) :
					foreach ($val as $key2 => $val2) {
						$valeurs .= '&bequillage_' . $key . '_' . $key2;
						$appareil[$key][$key2] += array('bequillage' => 4);
					}
				endforeach;
			endif;

			if (Input::post('commentaire_appareil') != NULL) :
				foreach (Input::post('commentaire_appareil') as $key => $val) :
					$val = trim(strip_tags($val));
					$valeurs .= '&commentaire_appareil_' . $key . '=' . $val;
					$commentaire_appareil[$key] += array('commentaire_appareil' => $val);
				endforeach;
			endif;

			if (empty($erreurs)) {
				foreach ($array as $key) :

					$groupe_sujets = DB::query('SELECT id_groupe_sujets FROM groupe_sujets WHERE id_chronologie=' . $key['chronologie'] . ' AND id_operation=' . $id . ' AND NMI=' . $key['NMI'] . ' ')->execute();
					$groupe_sujets = $groupe_sujets->_results;

					if (!empty($groupe_sujets)) : $num_groupe_sujet = $groupe_sujets[0]['id_groupe_sujets'];
					else :
						$ajout_groupe_sujets = DB::insert('groupe_sujets', array('id_groupe_sujets', 'id_chronologie', 'id_operation', 'NMI'))->values(array('id_groupe_sujets' => NULL, $key['chronologie'], $id, $key['NMI']))->execute();

						$groupe_sujets = DB::query('SELECT id_groupe_sujets FROM groupe_sujets WHERE id_chronologie=' . $key['chronologie'] . ' AND id_operation=' . $id . ' AND NMI=' . $key['NMI'] . ' ')->execute();
						$num_groupe_sujet = $groupe_sujets->_results[0]['id_groupe_sujets'];
					endif;

					if (!empty($key['datation_debut']) &&  !empty($key['datation_fin'])) :
						$datation = '[' . $key['datation_debut'] . ';' . $key['datation_fin'] . ']';
						$datation_ecart_type = abs(abs($key['datation_debut']) - abs($key['datation_fin']));
					else :
						$datation = "";
						$datation_ecart_type = 0;
					endif;

					if (!empty($key['num_inventaire']) || !empty($key['commune_depot']) || !empty($key['adresse_depot'])) :

						if (!empty($key['num_inventaire'])) : $num_inventaire = $key['num_inventaire'];
						else : $num_inventaire = "";
						endif;
						if (!empty($key['commune_depot'])) : $commune_depot = $key['commune_depot'];
						else : $commune_depot = "";
						endif;
						if (!empty($key['adresse_depot'])) : $adresse_depot = $key['adresse_depot'];
						else : $adresse_depot = "";
						endif;

						$ajout_depot = DB::insert('depot', array('id', 'num_inventaire', 'id_commune', 'adresse'))->values(array('id' => NULL, $num_inventaire, $commune_depot, $adresse_depot))->execute();

						$id_depot = $ajout_depot[0];
					else : $id_depot = NULL;
					endif;

					$ajout_sujets_handicape = DB::insert('sujet_handicape', array('id', 'id_sujet_handicape', 'age_min', 'age_max', 'sexe', 'datation', 'datation_ecart_type', 'milieu_vie', 'contexte', 'contexte_normatif', 'commentaire_contexte', 'url_illustration', 'id_type_depot', 'id_sepulture', 'id_depot', 'id_groupe_sujets'))
						->values(array('id' => NULL, $key['id_sujet'], $key['age_min'], $key['age_max'], $key['sexe'], $datation, $datation_ecart_type, $key['milieu_vie'], $key['contexte'], $key['contexte_normatif'], $key['commentaire_contexte'], NULL, $key['type_depot'], $key['type_sepulture'], $id_depot, $num_groupe_sujet))->execute();

					$id_sujet_handicape = $ajout_sujets_handicape[0];

					if (!empty($key['accessoire_vestimentaire_et_parure'])) :
						$id_mobilier = $key['accessoire_vestimentaire_et_parure'];
						$description = "";
						$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $id_mobilier, $description))->execute();
					endif;
					if (!empty($key['armement_objet_de_prestige'])) :
						$id_mobilier = $key['armement_objet_de_prestige'];
						$description = "";
						$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $id_mobilier, $description))->execute();
					endif;
					if (!empty($key['depot_de_recipient'])) :
						$id_mobilier = $key['depot_de_recipient'];
						$description = "";
						$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $id_mobilier, $description))->execute();
					endif;
					if (!empty($key['autre_mobilier'])) :
						$id_mobilier = $key['autre_mobilier'];
						$description = $key['description_autre_mobilier'];
						$ajout_accessoire = DB::insert('accessoire_sujet', array('id', 'id_sujet_handicape', 'id_mobilier', 'description'))->values(array('id' => NULL, $id_sujet_handicape, $id_mobilier, $description))->execute();
					endif;


					$ajout_commentaire_diagnostic = DB::insert('commentaire_diagnostic', array('id', 'commentaire_diagnostic'))->values(array('id' => NULL, $key['commentaire_diagnostic']))->execute();

					$id_commentaire_diagnostic = $ajout_commentaire_diagnostic[0];


					if (!empty($key['trepanation'])) :
						$id_diagnostic = $key['trepanation'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['edentement_complet'])) :
						$id_diagnostic = $key['edentement_complet'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['atteinte_neurale'])) :
						$id_diagnostic = $key['atteinte_neurale'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['scoliose_severe'])) :
						$id_diagnostic = $key['scoliose_severe'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['maladie_de_paget_ou_osteite_deformante'])) :
						$id_diagnostic = $key['maladie_de_paget_ou_osteite_deformante'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['dish'])) :
						$id_diagnostic = $key['dish'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['rachitisme'])) :
						$id_diagnostic = $key['rachitisme'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['nanisme'])) :
						$id_diagnostic = $key['nanisme'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['pathologie_infectieuse'])) :
						$id_diagnostic = $key['pathologie_infectieuse'];
						$description_du_autre = "";
						if (!empty($key['lepre'])) :
							$id_pathologie = $key['lepre'];
							$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
						endif;
						if (!empty($key['syphilis'])) :
							$id_pathologie = $key['syphilis'];
							$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
						endif;
						if (!empty($key['variole'])) :
							$id_pathologie = $key['variole'];
							$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
						endif;
						if (!empty($key['tuberculose'])) :
							$id_pathologie = $key['tuberculose'];
							$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
						endif;
						if (!empty($key['autre_pathologie_infectieuse'])) :
							$id_pathologie = $key['autre_pathologie_infectieuse'];
							$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
						endif;
					endif;

					if (!empty($key['fracture_non_reduite'])) :
						$id_diagnostic = $key['fracture_non_reduite'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['amputation'])) :
						$id_diagnostic = $key['amputation'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['pathologie_degenerative_severe'])) :
						$id_diagnostic = $key['pathologie_degenerative_severe'];
						$id_pathologie = NULL;
						$description_du_autre = "";
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;

					if (!empty($key['autre_atteinte'])) :
						$id_diagnostic = $key['autre_atteinte'];
						$id_pathologie = NULL;
						$description_du_autre = $key['description_autre_atteinte'];
						$ajout_atteinte = DB::insert('atteinte_invalidante', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_pathologie', 'description_autre', 'id_commentaire_diagnostic'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_pathologie, $description_du_autre, $id_commentaire_diagnostic))->execute();
					endif;
				endforeach;

				foreach ($localisation as $key => $val) :
					foreach ($val as $key2 => $val2) {
						if (!empty($val2)) :
							foreach ($val2 as $key3 => $val3) {
								$id_diagnostic = $key2;
								$id_localisation_atteinte = $val3;
								$ajout_localisation = DB::insert('localisation_sujet', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_localisation_atteinte'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_localisation_atteinte))->execute();
							}
						endif;
					}
				endforeach;


				foreach ($appareil as $key => $val) :
					foreach ($val as $key2 => $val2) {
						if (!empty($key2) && !empty($val2)) :
							foreach ($commentaire_appareil as $key99 => $val99) {
								if (!empty($val99)) : $commentaire = $val99;
								else : $commentaire = "";
								endif;
								foreach ($val2 as $key3 => $val3) {
									$id_diagnostic = $key2;
									$id_appareil_compensatoire = $val3;
									$ajout_appareil = DB::insert('appareil_sujet', array('id', 'id_sujet_handicape', 'id_diagnostic', 'id_appareil_compensatoire', 'commentaire'))->values(array('id' => NULL, $id_sujet_handicape, $id_diagnostic, $id_appareil_compensatoire, $commentaire))->execute();
								}
							}
						endif;
					}
				endforeach;

				Response::redirect('/operations/view/' . $id . '?&success_ajout');
			} elseif (!empty($erreurs)) {
				Response::redirect('/sujet/add/' . $id . '?nb=' . $nb . '' . $erreurs . '' . $valeurs . '');
			} else {
				Response::redirect('/operations');
			}
		}
		#endregion

		$data = array('idOperation' => $id);
		$this->template->title = "Ajouter des sujets";
		$this->template->content = View::forge('sujet/add', $data);
	}
}
