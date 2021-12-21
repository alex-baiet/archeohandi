<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Database_Result;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Helper;
use Model\Operation;
use Model\Sujethandicape;

class Controller_Operations extends Controller_Template {
	/**
	 * Récupère toutes les opérations en fonction des options de filtre entrées.
	 * 
	 * @return array
	 */
	private function getOperationArray(): array {
		$operations = array();

		// Cas ou aucun filtre n'est utilisé
		if (Input::method() !== "POST") {
			return Helper::querySelect("SELECT id_site, id_user, nom_op, annee, X, Y FROM operations");
		} 

		$filterId = Input::post("filter_id");
		$filterUser = Input::post("filter_user");
		$filterOp = Input::post("filter_op");
		$filterYear = Input::post("filter_year");
		$query = DB::select("id_site", "id_user", "nom_op", "annee", "X", "Y")->from("operations");
		// Ajout des conditions à la requête
		if (!empty($filterId)) $query->where("id_site", "=", $filterId);
		if (!empty($filterUser)) $query->where("id_user", "=", $filterUser);
		if (!empty($filterOp)) $query->where("nom_op", "=", $filterOp);
		if (!empty($filterYear) || $filterYear === "0") $query->where("annee", "=", $filterYear);

		/** @var Database_Result */
		$res = $query->execute();

		return $res->as_array();
	}

	/** Page d'affichages de toutes les opérations */
	public function action_index() {
		//Permet de récupérer toutes les informations pour le système de filtre
		$all_site = Helper::querySelectList("SELECT id_site FROM operations ORDER BY id_site ASC");
		$all_user = Helper::querySelectList("SELECT DISTINCT id_user FROM operations ORDER BY id_user");
		$all_nom_op = Helper::querySelectList("SELECT DISTINCT nom_op FROM operations ORDER BY nom_op");
		$all_annee = Helper::querySelectList("SELECT annee FROM operations ORDER BY annee DESC");

		$all_site = Helper::arrayValuesAreKeys($all_site);
		$all_user = Helper::arrayValuesAreKeys($all_user);
		$all_nom_op = Helper::arrayValuesAreKeys($all_nom_op);
		$all_annee = Helper::arrayValuesAreKeys($all_annee);

		$all_site[""] = "";
		$all_user[""] = "";
		$all_nom_op[""] = "";
		$all_annee[""] = "";
		
		$operation = $this->getOperationArray();

		// Permet de supprimer une opération quand le bouton de suppression est validée
		// TODO: Supprimer POUR DE VRAI les données
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

		// Ajout des valeurs à la view.
		$data = array(
			'operations' => $operation,
			'all_site' => $all_site,
			'all_user' => $all_user,
			'all_nom_op' => $all_nom_op,
			'all_annee' => $all_annee
		);
		$this->template->title = 'Opérations';
		$this->template->content = View::forge('operations/index', $data, false);
	}

	//L'action view sert pour la page view de opération qui affiche les détails d'une opération
	public function action_view($id) {
		// Récupération des informations de l'opération
		$operation = Operation::fetchSingle($id);
		if ($operation === null) Response::redirect("/operations");

		// Récupération des groupe_sujets
		$idGroups = Helper::querySelectList('SELECT * FROM groupe_sujets WHERE id_operation=' . $operation->getIdSite());

		// Récupération de tous les sujets handicapé des différents groupes
		/** @var Sujethandicape[] */
		$sujets = array();
		foreach ($idGroups as $id) {
			$results = Helper::querySelect('SELECT * FROM sujet_handicape WHERE id_groupe_sujets=' . $id);
			foreach ($results as $res) {
				// Ajout d'un sujet à la liste.
				array_push($sujets, new Sujethandicape($res));
			}
		}

		//Permet de supprimer un sujet quand l'alert de suppression est validée
		// TODO: Supprimer POUR DE VRAI les données
		if (Input::post('supp_sujet')){
			if (is_numeric(Input::post('supp_sujet'))) {
				$query = DB::query('SELECT id_sujet_handicape FROM sujet_handicape WHERE id_sujet_handicape='.Input::post('supp_sujet').' ');
				$if_op_ex = $query->execute();
				$if_op_ex= $if_op_ex->_results;

				if (!empty($if_op_ex)) Response::redirect('/operations/view/'.$id.'?&success_supp_sujet');
				else Response::redirect('/operations/view/'.$id.'?&erreur_supp_bdd');
			}
			else Response::redirect('/operations/view/'.$id.'?&erreur_supp_sujet');
		}

		// Ajout des données à la view
		$data = array('operation' => $operation, 'sujets' => $sujets);
		$this->template->title = 'Consultation de l\'opération '.$operation->getNomOp();
		$this->template->content=View::forge('operations/view', $data);
	}

	//L'action edit sert pour la page edit de opération qui affiche les informations d'une opération pour les modifier
	public function action_edit($id){
		// Récupération des informations de l'opération
		$operation = Operation::fetchSingle($id);
		/** @var null|string */
		$errors = null;
		
		// Tentative de mise à jour de l'opération
		if (Input::method() === "POST") {
			$operation->mergeValues($_POST);

			$result = $operation->validate();
			if ($result === true) {
				// Les données sont valides : on met à jour la BDD
				$operation->saveOnDB();

				Response::redirect("/operations?success_modif");
			} else {
				// Les données ne sont pas valides : on affiche les problèmes
				$errors = $result;
			}
		}

		$data = array('operation'=> $operation, 'errors' => $errors);
		$this->template->title = 'Modification de l\'opération '.$id;
		$this->template->content=View::forge('operations/edit',$data);
	}
}