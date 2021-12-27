<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Helper;
use Model\Operation;
use Model\Sujethandicape;

class Controller_Operations extends Controller_Template {
	private const DEBUG = false;

	/** Page d'affichages de toutes les opérations */
	public function action_index() {
		// Récupération des opérations
		$operations = Operation::fetchAll();

		// Préparation des valeurs de recherches
		$all_site = array();
		foreach ($operations as $op) { $all_site[$op->getIdSite()] = $op->getIdSite(); }
		$all_user = array();
		foreach ($operations as $op) { $all_user[$op->getIdUser()] = $op->getIdUser(); }
		$all_nom_op = array();
		foreach ($operations as $op) { $all_nom_op[$op->getNomOp()] = $op->getNomOp(); }
		$all_annee = array();
		foreach ($operations as $op) { $all_annee[$op->getAnnee()] = $op->getAnnee(); }

		$all_site[""] = "";
		$all_user[""] = "";
		$all_nom_op[""] = "";
		$all_annee[""] = "";

		asort($all_site);
		asort($all_user);
		asort($all_nom_op);
		asort($all_annee);

		// Tri selon la recherche
		if (Input::method() === "GET") {
			
			$filterId = Input::get("filter_id");
			$filterUser = Input::get("filter_user");
			$filterOp = Input::get("filter_op");
			$filterYear = Input::get("filter_year");

			for ($i=count($operations) -1; $i >= 0; $i--) { 
				$op = $operations[$i];
				$toRemove = false;
				if (!empty($filterId) && $op->getIdSite() != $filterId) $toRemove = true;
				if (!empty($filterUser) && $op->getIdUser() != $filterUser) $toRemove = true;
				if (!empty($filterOp) && $op->getNomOp() != $filterOp) $toRemove = true;
				if ((!empty($filterYear) || $filterYear === "0") && $op->getAnnee() != $filterYear) $toRemove = true;
				if ($toRemove) unset($operations[$i]);
			}
		}

		// Permet de supprimer une opération quand le bouton de suppression est validée
		// TODO: Supprimer POUR DE VRAI les données
		if (Input::post('supp_op')) {
			// if (is_numeric(Input::post('supp_op'))) {
			// 	$query = DB::query('SELECT id_site FROM operations WHERE id_site='.Input::post('supp_op').' ');
			// 	$if_op_ex = $query->execute();
			// 	$if_op_ex= $if_op_ex->_results;

			// 	if (!empty($if_op_ex)) {
			// 		Response::redirect('/operations?&success_supp_op');
			// 	} else {
			// 		Response::redirect('/operations?&erreur_supp_bdd');
			// 	}
			// } else {
			// 	Response::redirect('/operations?&erreur_supp_op');
			// }
			echo "La suppression n'est pas fonctionnelle pour le moment...";
		}

		// Ajout des valeurs à la view.
		$data = array(
			'operations' => $operations,
			'all_site' => $all_site,
			'all_user' => $all_user,
			'all_nom_op' => $all_nom_op,
			'all_annee' => $all_annee
		);
		$this->template->title = 'Opérations';
		$this->template->content = View::forge('operations/index', $data, false);
	}

	public function action_add() {
		// Ajout d'une opération
		if (Input::method() === "POST") {
			$operation = new Operation($_POST);
			
			if (Controller_Operations::DEBUG === true) {
				// Débuggage
				echo "Tentative de création d'une opération...<br>";
				Helper::varDump($_POST);
				Helper::varDump($operation);
			}
			else if ($operation->saveOnDB()) {
				// Ajout de l'opération avec succès
				Response::redirect("/operations?success_add");
			}
		}

		$this->template->title = 'Ajouter une opération';
		$data = array();
		if (isset($operation)) $data["operation"] = $operation;
		$this->template->content = View::forge('operations/add', $data);
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

				if (Controller_Operations::DEBUG === true) {
					Helper::varDump($operation);
				} else {
					Response::redirect("/operations?success_modif");
				}
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