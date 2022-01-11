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
		$data = array();

		// Suppression d'une opération
		if (isset($_POST['delete_op'])) {
			echo "deletion d'une operation";

			$result = Operation::deleteOnDB($_POST["delete_op"]);
			if ($result === null) {
				$data["msgType"] = "success_delete";
			} else {
				$data["msgType"] = "error_delete";
				$data["msg"] = $result;
			}
		}

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

		// Ajout des valeurs à la view.
		$data['operations'] = $operations;
		$data['all_site'] = $all_site;
		$data['all_user'] = $all_user;
		$data['all_nom_op'] = $all_nom_op;
		$data['all_annee'] = $all_annee;
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
				Response::redirect("/sujet/add/{$operation->getIdSite()}");
			}
		}

		$this->template->title = 'Ajouter une opération';
		$data = array();
		if (isset($operation)) $data["operation"] = $operation;
		$this->template->content = View::forge('operations/add', $data);
	}

	//L'action view sert pour la page view de opération qui affiche les détails d'une opération
	public function action_view($id) {
		$data = array();
		
		// Suppression d'un sujet (si l'utilisateur le demande)
		if (isset($_POST['delete_sujet'])) {
			$result = Sujethandicape::deleteOnDB($_POST["delete_sujet"]);
			if ($result === null) {
				$data["msgType"] = "success_delete";
			} else {
				$data["msgType"] = "error_delete";
				$data["msg"] = $result;
			}
		}

		// Récupération des informations de l'opération
		$operation = Operation::fetchSingle($id);
		if ($operation === null) Response::redirect("/operations");

		// Ajout des données à la view
		$data["operation"] = $operation;
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