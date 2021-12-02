<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;

class Controller_Operations extends Controller_Template{
	//L'action index sert pour la page index de opération qui affiche les différents opération
	public function action_index(){
		//Permet de récupérer toutes les informations pour le système de filtre
		$query = DB::query('SELECT id_site FROM operations ORDER BY id_site ASC');
		$all_site = $query->execute();
		$all_site= $all_site->_results;
		//Permet de récupérer toutes les informations pour le système de filtre
		$query = DB::query('SELECT DISTINCT id_user FROM operations ORDER BY id_user');
		$all_user = $query->execute();
		$all_user= $all_user->_results;
		//Permet de récupérer toutes les informations pour le système de filtre
		$query = DB::query('SELECT DISTINCT nom_op FROM operations ORDER BY nom_op ');
		$all_nom_op = $query->execute();
		$all_nom_op= $all_nom_op->_results;
		//Permet de récupérer toutes les informations pour le système de filtre
		$query = DB::query('SELECT annee FROM operations ORDER BY annee DESC');
		$all_annee = $query->execute();
		$all_annee= $all_annee->_results;
		//Permet de récupérer toutes les informations pour le système de filtre
		$query = DB::query('SELECT id_site,id_user,nom_op,annee,X,Y FROM operations');
		$operation = $query->execute();
		$operation= $operation->_results;

		//Permet de supprimer une opération quand l'alert de suppression est validée
		if (Input::post('supp_op')) {
			if (is_numeric(Input::post('supp_op'))) {
				$query = DB::query('SELECT id_site FROM operations WHERE id_site='.Input::post('supp_op').' ');
				$if_op_ex = $query->execute();
				$if_op_ex= $if_op_ex->_results;

				if (!empty($if_op_ex)) {
					Response::redirect('/operations?&success_supp_op');
				} else {
					Response::redirect('/operations?&erreur_supp_bdd');
				}
			} else {
				Response::redirect('/operations?&erreur_supp_op');
			}
		}

		//Permet de faire la recherche du filtre
		if (Input::post('recherche')) {
			if (isset($_POST['radio'])) {
				//Initialisation de qu'est ce qui est recherché et quelle l'information voulu
				switch ($_POST['radio']) {
					case 'Id':
						$what='id_site';
						$condition=Input::post('select_site');
						break;
					case 'user':
						$what='id_user';
						$condition=Input::post('select_user');
						break;
					case 'nom_op':
						$what='nom_op';
						$condition=Input::post('select_op');
						break;
					case 'annee':
						$what='annee';
						$condition=Input::post('select_annee');
						break;
					default:
						$what="";
						$condition="";
						break;
				}
				//Récupère les données de ce qui recherché
				$operation= DB::select()->from('operations')->where($what,$condition)->execute();
				$operation=$operation->_results;
			}
		}

		$data = array('operations' => $operation, 'all_site' => $all_site, 'all_user'=>$all_user, 'all_nom_op'=>$all_nom_op, 'all_annee' => $all_annee);
		$this->template->title = 'Opérations';
		$this->template->content = View::forge('operations/index', $data, false);
	}

	//L'action view sert pour la page view de opération qui affiche les détails d'une opération
	public function action_view($id) {
		//Permet de récupérer les informations de l'opération voulu
		$query = DB::query('SELECT * FROM operations WHERE id_site='.$id.' ');
		$operation_details = $query->execute();
		$operation_details= $operation_details->_results;

		//Permet de supprimer un sujet quand l'alert de suppression est validée
		if (Input::post('supp_sujet')){
			if (is_numeric(Input::post('supp_sujet'))) {
				$query = DB::query('SELECT id_sujet_handicape FROM sujet_handicape WHERE id_sujet_handicape='.Input::post('supp_sujet').' ');
				$if_op_ex = $query->execute();
				$if_op_ex= $if_op_ex->_results;

				if (!empty($if_op_ex)){
					Response::redirect('/operations/view/'.$id.'?&success_supp_sujet');
				} else {
					Response::redirect('/operations/view/'.$id.'?&erreur_supp_bdd');
				}

			} else {
				Response::redirect('/operations/view/'.$id.'?&erreur_supp_sujet');
			}
		}

		$data = array('operation_details' => $operation_details);
		foreach ($operation_details as $key) {
			$this->template->title = 'Consultation de l\'opération '.$key['nom_op'];
		}
		
		$this->template->content=View::forge('operations/view',$data);
	}

	//L'action edit sert pour la page edit de opération qui affiche les informations d'une opération pour les modifier
	public function action_edit($id){
		//Permet de récupérer les informations de l'opération qu'on veut modifier
		$query = DB::query('SELECT * FROM operations WHERE id_site='.$id.' ');
		$modif_op = $query->execute();
		$modif_op= $modif_op->_results;

		//Permet de récupérer id et le nom pour les foreach
		$query = DB::query('SELECT id,nom FROM type_operation');
		$all_type_op = $query->execute();
		$all_type_op= $all_type_op->_results;

		$query = DB::query('SELECT id,nom FROM organisme');
		$all_organisme = $query->execute();
		$all_organisme= $all_organisme->_results;

		//Initialisation des variables qui permettent de stocker les erreurs et les valeurs des différents champs pour les afficher sur la page en cas d'erreur
		$erreurs=$valeurs="";

		if (Input::post("modif_op")){

			//Pour chaque champs, nous vérifions si elle est pas vide, quelle corresponde au caractère valide et si tout est correct la variable valeurs ajoute la valeur dans sa variable. Et en cas d'erreur, la variable erreurs ajoute l'erreur du problème

			if(!empty(Input::post('adresse'))): 
				if(verif_alpha(Input::post('adresse'), 'alphatout') != false): $adresse=verif_alpha(Input::post('adresse'), 'alphatout'); $valeurs.='&adresse='.$adresse; else: $erreurs.="&erreur_alpha_adresse"; endif; 
			else: $erreurs.="&erreur_adresse"; endif;

			if(!empty(Input::post('annee'))):
				if(is_numeric(Input::post('annee'))): $valeurs.='&annee='.Input::post('annee'); $annee = Input::post('annee'); else: $erreurs.="&erreur_annee"; endif;
			else: $erreurs.="&erreur_annee_vide"; endif;

			if(!empty(Input::post('X'))):
				if(is_numeric(Input::post('X')) ): $valeurs.='&X='.Input::post('X'); $X=Input::post('X'); else: $erreurs.="&erreur_X"; endif;
			else: $X=""; endif;

			if(!empty(Input::post('Y'))):
				if(is_numeric(Input::post('Y')) ): $valeurs.='&Y='.Input::post('Y'); $Y=Input::post('Y'); else: $erreurs.="&erreur_Y"; endif;
			else: $Y=""; endif;

			if(Input::post('commune') != null) {
				//Permet de récupérer juste le nom de la commune et non avec le département
				$nom_commune_for_id= preg_replace("(\((.*?)\))","",Input::post('commune'));
				//Récupère l'ID de la commune 
				$query = DB::query('SELECT id FROM commune WHERE nom="'.$nom_commune_for_id.'"');
				$if_commune_ex = $query->execute();
				$if_commune_ex=$if_commune_ex->_results;
				//Vérifie quelle existe dans la BDD
				if (!empty($if_commune_ex)) {
					$valeurs.='&commune='.Input::post('commune');
					$id_commune=$if_commune_ex[0]['id'];
				}
				else $erreurs.="&erreur_commune";
			}
			else $erreurs.="&erreur_commune_select";

			//Pour les différents select, nous vérifions que les différentes entités existent dans la BDD
			if(Input::post('organisme') != null) {
				$query = DB::query('SELECT nom FROM organisme WHERE id='.Input::post('organisme').'');
				$if_organisme_ex = $query->execute();
				$if_organisme_ex=$if_organisme_ex->_results;
				if(!empty($if_organisme_ex)) {
					$valeurs.='&organisme='.Input::post('organisme');
					$organisme=Input::post('organisme');
				}
				else $erreurs.="&erreur_organisme";
			}
			else $erreurs.="&erreur_organisme_select";

			if(Input::post('type_operation') != null) {
				$query = DB::query('SELECT nom FROM type_operation WHERE id='.Input::post('type_operation').'');
				$if_type_operation_ex = $query->execute();
				$if_type_operation_ex=$if_type_operation_ex->_results;
				if(!empty($if_type_operation_ex)) {
					$valeurs.='&type_op='.Input::post('type_operation');
					$type_operation=Input::post('type_operation');
				}
				else $erreurs.="&erreur_type_operation";
			}
			else $erreurs.="&erreur_type_operation_select";

			if (!empty(Input::post('a_revoir'))) {
				$a_revoir=trim(strip_tags(Input::post('a_revoir')));
				$valeurs.='&a_revoir='.$a_revoir;
			}
			if(!empty(Input::post('EA'))) {
				if(verif_alpha(Input::post('EA'), 'alphanum') != false) {
					$EA=verif_alpha(Input::post('EA'),'alphanum');
					$valeurs.='&EA='.$EA;
				} 
				else $erreurs.="&erreur_alpha_ea";
			}
			if(!empty(Input::post('OA'))) {
				if(verif_alpha(Input::post('OA'), 'alphanum') != false) {
					$OA=verif_alpha(Input::post('OA'),'alphanum');
					$valeurs.='&OA='.$OA;
				}
				else $erreurs.="&erreur_alpha_oa";
			}
			if(!empty(Input::post('patriarche'))) {
				if(verif_alpha(Input::post('patriarche'), 'alphanum') != false) {
					$patriarche=verif_alpha(Input::post('patriarche'), 'alphanum');
					$valeurs.='&patriarche='.$patriarche;
				}
				else $erreurs.="&erreur_alpha_patriarche"; 
			}
			if(!empty(Input::post('numero_operation'))) {
				if(verif_alpha(Input::post('numero_operation'), 'alphanum') != false) {
					$num_op=verif_alpha(Input::post('numero_operation'), 'alphanum');
					$valeurs.='&numero_op='.$num_op;
				} 
				else $erreurs.="&erreur_alpha_numop";
			}
			if(!empty(Input::post('arrete_prescription'))) {
				if(verif_alpha(Input::post('arrete_prescription'), 'alphanum') != false): $prescription=verif_alpha(Input::post('arrete_prescription'), 'alphanum'); $valeurs.='&arrete_prescription='.$prescription; else: $erreurs.="&erreur_alpha_prescription"; endif; 
			}
			if(!empty(Input::post('responsable_op'))) {
				if(verif_alpha(Input::post('responsable_op'), 'alpha') != false): $RO=verif_alpha(Input::post('responsable_op'), 'alpha'); $valeurs.='&responsable_op='.$RO; else: $erreurs.="&erreur_alpha_ro"; endif; 
			}
			if(!empty(Input::post('anthropologue'))) {
				if(verif_alpha(Input::post('anthropologue'), 'alpha') != false) {
					$anthropologue=verif_alpha(Input::post('anthropologue'), 'alpha');
					$valeurs.='&anthropologue='.$anthropologue;
				}
				else $erreurs.="&erreur_alpha_anthro";
			}
			if(!empty(Input::post('paleopathologiste'))) {
				if(verif_alpha(Input::post('paleopathologiste'), 'alpha') != false) {
					$paleopathologiste=verif_alpha(Input::post('paleopathologiste'), 'alpha');
					$valeurs.='&paleopathologiste='.$paleopathologiste;
				}
				else $erreurs.="&erreur_alpha_paleo";
			}

			if(!empty(Input::post('bibliographie'))) {
				$bibliographie=trim(strip_tags(Input::post('bibliographie')));
				$valeurs.='&bibliographie='.$bibliographie;
			}

			//Si erreur est non vide, nous serrons redirigés avec les erreurs et les valeurs dans l'url pour les garder en mémoire
			if ($erreurs!="") {
				Response::redirect('/operations/edit/operation/'.$id.'?'.$erreurs.''.$valeurs.'');
				die;
			}

			//Permet de récupérer le nom de la commune
			$query = DB::query('SELECT nom FROM commune WHERE id='.$id_commune.'');
			$nom_commune = $query->execute();
			$nom_commune=$nom_commune->_results[0]['nom'];
			//Initialise le nom de l'opération
			$nom_op= $nom_commune.', '.$adresse.', '.$annee;
			//Met à jour l'opération
			$operation=DB::update('operations')->set(array('nom_op' => $nom_op,'a_revoir'=>$a_revoir,'annee'=>$annee,'id_commune'=> $id_commune,'adresse'=>$adresse,'X'=>$X,'Y'=>$Y,'id_organisme'=>$organisme, 'id_type_op'=>$type_operation,'EA'=>$EA,'OA'=>$OA,'patriarche'=>$patriarche,'numero_operation'=>$num_op,'arrete_prescription'=>$prescription,'responsable_op'=>$RO,'anthropologue'=>$anthropologue,'paleopathologiste'=>$paleopathologiste,'bibliographie'=>$bibliographie))->where('id_site',$id)->execute();

			Response::redirect('/operations?&success_modif');
		}

		$data = array('modif_op'=> $modif_op, 'all_type_op'=>$all_type_op, 'all_organisme'=>$all_organisme);
		$this->template->title = 'Modification de l\'opération '.$id;
		$this->template->content=View::forge('operations/edit',$data);
	}

	//Fonction qui permet de vérifier si ce qui est envoyé correspond à des caractères valides
	function verif_alpha($str,$type){
		//Enlève les espaces, tabulations, etc au début et fin de la chaine de caractère
		trim($str);
		//Enlève les balises html pour éviter des problèmes d'affichage ou des attaques XSS
		strip_tags($str);

		if($type == "alpha") preg_match('/([^A-Za-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ ,])/',$str,$result);
		if($type == "alphatout") preg_match('/([^A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ ])/',$str,$result);
		if($type == "alphanum") preg_match('/([^A-Za-z0-9,-;()\/ ])/',$str,$result);
		if(!empty($result)) return false;
		else return $str;
	}
}