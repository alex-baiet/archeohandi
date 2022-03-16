<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Sujethandicape;
use Model\Helper;
use Model\Messagehandler;

class Controller_Operations extends Controller_Template {
	private const DEBUG = false;

	/** Page d'affichages de toutes les opérations */
	public function action_index() {
		Compte::checkPermissionRedirect("Vous devez avoir un compte pour voir les opérations.", Compte::PERM_WRITE);

		$data = array();

		// Suppression d'une opération
		if (isset($_POST["delete_op"])) {
			//Helper::varDump($_POST["delete_op"]);
			$idOp = intval($_POST["delete_op"]);
			if (!Compte::checkPermission(Compte::PERM_ADMIN, $idOp)) {
				Messagehandler::prepareAlert("Seul le créateur de l'opération à le droit de supprimer l'opération", "danger");
			} else {
				$result = Operation::deleteOnDB($idOp);
				if ($result === null) {
					Messagehandler::prepareAlert("Suppression de l'opération réussi.", "success");
				} else {
					Messagehandler::prepareAlert($result, "danger");
				}
			}
		}

		// Récupération des opérations
		$operations = Operation::fetchAll();

		// Calcul nombre de sujets enregistrés
		$countSubject = intval(Helper::querySelectSingle("SELECT COUNT(id) AS total FROM sujet_handicape")["total"]);
		$countOp = intval(Helper::querySelectSingle("SELECT COUNT(id) AS total FROM operations")["total"]);

		// Ajout des valeurs à la view.
		krsort($operations);
		$data['operations'] = $operations;
		$data['countSubject'] = $countSubject;
		$data['countOp'] = $countOp;
		$this->template->title = 'Opérations';
		$this->template->content = View::forge('operations/index', $data, false);
	}

	public function action_add() {
		Compte::checkPermissionRedirect("Vous devez avoir un compte pour pouvoir créer une opération.", Compte::PERM_WRITE);

		// Ajout d'une opération
		if (Input::method() === "POST") {
			$operation = new Operation($_POST);
			
			if ($operation->saveOnDB()) {
				if (Controller_Operations::DEBUG === true) {
					// Débuggage
					echo "Tentative de création d'une opération...<br>";
					Helper::varDump($_POST);
					Helper::varDump($operation);
				}
				else {
					// Ajout de l'opération avec succès
					Messagehandler::prepareAlert("Ajout de l'opération réussi.", "success");
					Response::redirect("/sujet/add/{$operation->getId()}");
				}
			}
		}

		$this->template->title = 'Ajouter une opération';
		$data = array();
		if (isset($operation)) $data["operation"] = $operation;
		$this->template->content = View::forge('operations/add', $data);
	}

	//L'action view sert pour la page view de opération qui affiche les détails d'une opération
	public function action_view($id) {
		Compte::checkPermissionRedirect("Vous devez être connecté pour pouvoir consulter une opération.", Compte::PERM_WRITE);

		$data = array();

		$operation = Operation::fetchSingle($id);		
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		// Suppression d'un sujet (si l'utilisateur le demande)
		if (isset($_POST['delete_sujet'])) {
			$idSubject = $_POST['delete_sujet'];
			if (!Compte::checkPermission(Compte::PERM_WRITE, $id)) {
				Messagehandler::prepareAlert("Vous n'avez pas les permissions nécessaires sur l'opération pour pouvoir supprimer un sujet.", "danger");
			} else {
				$result = Sujethandicape::deleteOnDB($idSubject);
				if ($result === null) {
					Messagehandler::prepareAlert("Suppression du sujet réussi.", "success");
				} else {
					Messagehandler::prepareAlert("Echec de la suppression du sujet.", "danger");
				}
			}
		}

		// Ajout des données à la view
		$data["operation"] = $operation;
		$this->template->title = 'Consultation de l\'opération '.$operation->getNomOp();
		$this->template->content=View::forge('operations/view', $data);
	}

	//L'action edit sert pour la page edit de opération qui affiche les informations d'une opération pour les modifier
	public function action_edit($id){
		Compte::checkPermissionRedirect("Seul le créateur de l'opération peut éditer les informations de l'opération.", Compte::PERM_WRITE, $id);

		// Récupération des informations de l'opération
		$operation = Operation::fetchSingle($id);		
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		/** @var null|string */
		$errors = null;
		
		// Tentative de mise à jour de l'opération
		if (Input::method() === "POST") {
			$operation->mergeValues($_POST);

			$result = $operation->validate();
			if ($result === true) {
				// Les données sont valides : on met à jour la BDD
				$operation->saveOnDB();
				Messagehandler::prepareAlert("Modification de l'opération réussi.", "success");

				if (Controller_Operations::DEBUG === true) {
					Helper::varDump($operation);
				} else {
					Response::redirect("/operations");
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