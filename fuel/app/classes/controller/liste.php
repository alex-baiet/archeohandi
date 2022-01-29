<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Model\Db\Groupesujet;

class Controller_Liste extends Controller_Template {
	//L'action groupes sert pour la page groupe qui affiche les différents groupe de sujet
	public function action_groupes() {
		$data = array();
		$groups = Groupesujet::fetchAll();
		$filteredGroups = array();
		
		// Application des filtres
		if (isset($_GET["search"])) {
			$filterOp = $_GET["id_operation"];
			$filterChrono = $_GET["id_chronology"];
			$filterNMI = $_GET["nmi"];

			foreach ($groups as $group) {
				$toAdd = true;
				if (!empty($filterOp) && $group->getIdOperation() != $filterOp) $toAdd = false;
				if (!empty($filterChrono) && $group->getIdChronology() != $filterChrono) $toAdd = false;
				if (!empty($filterNMI) && $group->getNMI() != $filterNMI) $toAdd = false;
				if ($toAdd) $filteredGroups[] = $group;
			}
			$data["groups"] = $filteredGroups;
		} else {
			$data["groups"] = $groups;
		}

		// $data = array('groupe_sujets' => $groupe_sujets , 'groupe_op'=>$groupe_op, 'all_chrono'=>$all_chrono, 'groupe_NMI'=>$groupe_NMI);
		$this->template->title = 'Liste des groupes';
		$this->template->content = View::forge('liste/groupes', $data);

	}

	// public function action_communes() {
	// 	//Permet de récupérer toutes les informations pour le système de filtre
	// 	$query = DB::query("SELECT DISTINCT departement FROM commune ORDER BY departement");
	// 	$departement = $query->execute();
	// 	$departement= $departement->_results;

	// 	//Permet de récupérer toutes les informations pour le système de filtre
	// 	$query = DB::query("SELECT DISTINCT region FROM commune ORDER BY region");
	// 	$region = $query->execute();
	// 	$region= $region->_results;

	// 	//Permet de limiter l'affichage des communes
	// 	$limit=100;

	// 	//Récupère le nombre de commune
	// 	$query = DB::query("SELECT count(id) AS id FROM commune");
	// 	$count_commune = $query->execute();
	// 	$count_commune= $count_commune->_results;
	// 	$total = $count_commune[0]['id'];
	// 	//Permet de calculer le nombre de page
	// 	$pages= ceil($total/$limit);

	// 	//Vérifie si dans l'url l'option page est présente et vérifie si sa valeur est un nombre
	// 	if (array_key_exists('page',$_GET)) {
	// 		if (is_numeric($_GET['page']) && $_GET['page']>0 && $_GET['page']<=$pages) {
	// 			$page=$_GET['page'];
	// 		}
	// 		else $page=1;
	// 	}
	// 	else $page=1; 

	// 	$start= ($page -1) * $limit;

	// 	//Permet de récupérer toutes les communes entre le start et la limite
	// 	$query = DB::query("SELECT * FROM commune ORDER BY nom ASC LIMIT $start, $limit");
	// 	$all_commune = $query->execute();
	// 	$all_commune= $all_commune->_results;

	// 	//Initialisation des variables pour savoir la prochaine et la précédente page pour la pagination
	// 	$Previous= $page -1;
	// 	$Next= $page +1;

	// 	//Permet de faire la recherche du filtre
	// 	if (Input::post('recherche')) {
	// 		if (isset($_POST['radio'])) {
	// 			//Initialisation de qu'est ce qui est recherché et quelle l'information voulu
	// 			switch ($_POST['radio']) {
	// 				case 'commune':
	// 					$what='nom';
	// 					$condition= preg_replace("(\((.*?)\))","",Input::post('commune'));
	// 					break;
	// 				case 'departement':
	// 					$what='departement';
	// 					$condition=Input::post('select_departement');
	// 					break;
	// 				case 'region':
	// 					$what='region';
	// 					$condition=Input::post('select_region');
	// 					break;
	// 				default:
	// 					$what="";
	// 					$condition="";
	// 					break;
	// 			}
	// 			//Récupère les données de ce qui recherché
	// 			$query = DB::query('SELECT * FROM commune WHERE '.$what.'= "'.$condition.'" ');
	// 			$all_commune = $query->execute();
	// 			$all_commune= $all_commune->_results;
	// 		}
	// 	}

	// 	$data = array('departement'=>$departement, 'region'=>$region, 'page'=>$page,'pages'=>$pages, 'all_commune'=>$all_commune, 'Previous'=>$Previous, 'Next'=> $Next);
	// 	$this->template->title = 'Liste des communes';
	// 	$this->template->content = View::forge('liste/communes', $data);

	// }
}
