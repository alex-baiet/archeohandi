<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Sujethandicape;
use Model\Helper;
use Model\Messagehandler;
use Model\Redirect;
use Model\Searchresult;

class Controller_Operations extends Controller_Template {
	private const DEBUG = false;

	/** Page d'affichages de toutes les opérations */
	public function action_index() {
		Compte::checkPermissionRedirect("Vous devez avoir un compte pour voir les opérations.", Compte::PERM_WRITE);

		$data = array();

		// Récupération des opérations
		$operations = Operation::fetchAll();
		$lines = array();
		foreach ($operations as $op) {
			$line = new Searchresult();
			$line->operation = $op;
			$line->subjects = $op->getSubjects();
			$lines[$op->getId()] = $line;
		}
		krsort($lines);
		$data['lines'] = $lines;

		// Calcul nombre de sujets enregistrés
		$countSubject = intval(Helper::querySelectSingle("SELECT COUNT(id) AS total FROM sujet_handicape")["total"]);
		$data['countSubject'] = $countSubject;
		$countOp = intval(Helper::querySelectSingle("SELECT COUNT(id) AS total FROM operations")["total"]);
		$data['countOp'] = $countOp;

		$this->template->title = 'Opérations';
		$this->template->content = View::forge('operations/index', $data, false);
	}

	public function action_personnel() {
		Compte::checkPermissionRedirect("Vous devez avoir un compte pour voir les opérations.", Compte::PERM_WRITE);
		$data = array();

		$lines = array();

		// Récupération des opérations
		$results = DB::select()
			->from("operations")
			->join("droit_compte")
			->on("id_operation", "=", "id")
			->where("login_compte", "=", Compte::getInstance()->getLogin())
			->execute()
			->as_array();

		foreach ($results as $res) {
			$operation = new Operation($res);
			$line = new Searchresult();
			$line->operation = $operation;
			$line->subjects = $operation->getSubjects();

			$lines[] = $line;
		}

		$data["lines"] = $lines;
		$this->template->title = 'Opérations personnelles';
		$this->template->content = View::forge('operations/personnel', $data, false);
	}

	/** Page d'ajout d'une opération. */
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

		$this->template->title = 'Nouvelle opération';
		$data = array();
		if (isset($operation)) $data["operation"] = $operation;
		$this->template->content = View::forge('operations/add', $data);
	}

	/** Page affichant les informations d'une opération. */
	public function action_view($id) {
		Compte::checkPermissionRedirect("Vous devez être connecté pour pouvoir consulter une opération.", Compte::PERM_WRITE);

		$data = array();

		$operation = Operation::fetchSingle($id);		
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		// Ajout des données à la view
		$data["operation"] = $operation;
		$this->template->title = 'Consultation de l\'opération '.$id;
		$this->template->content = View::forge(
			'operations/template',
			array("content" => View::forge("operations/view", $data))
		);
	}

	/** Page des sujets d'une opération. */
	public function action_sujets($id) {
		Compte::checkPermissionRedirect("Vous devez être connecté pour pouvoir consulter une opération.", Compte::PERM_WRITE);

		$data = array();

		$operation = Operation::fetchSingle($id);
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		// Ajout des données à la view
		$data["operation"] = $operation;
		$this->template->title = 'Sujets de l\'opération '.$id;
		$this->template->content = View::forge(
			'operations/template',
			array("content" => View::forge("operations/sujets", $data))
		);
	}

	/** Page d'édition d'une opération. */
	public function action_edit($id){
		Compte::checkPermissionRedirect("Seul le créateur de l'opération peut modifier les informations de l'opération.", Compte::PERM_WRITE, $id);

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
					Response::redirect("/operations/view/$id");
				}
			} else {
				// Les données ne sont pas valides : on affiche les problèmes
				$errors = $result;
			}
		}

		$data = array('operation'=> $operation, 'errors' => $errors);
		$this->template->title = 'Modification de l\'opération '.$id;
		$this->template->content = View::forge(
			'operations/template',
			array("content" => View::forge("operations/edit", $data))
		);
	}

	/**
	 * Supprime une opération.
	 * @param int $_POST["id"] Contient l'id de l'opération à supprimer.
	 * @param string $_POST["redirect"] Contient l'url de destination après la suppression.
	 */
	public function action_delete() {
		if (!isset($_POST["id"])) Redirect::redirectBack();

		$id = $_POST["id"];

		$operation = Operation::fetchSingle($id);
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Redirect::redirectBack();
		}

		Compte::checkPermissionRedirect(
			"Vous n'êtes pas autorisés à modifier l'opération.",
			Compte::PERM_ADMIN,
			$operation->getId()
		);

		// Suppression de l'opération
		$error = Operation::deleteOnDB($id);
		if ($error === null) {
			Messagehandler::prepareAlert("L'opération n°$id a bien été supprimé.", "success");
		} else {
			Messagehandler::prepareAlert($error, "danger");
		}

		// Redirection vers la page choisi
		if (isset($_POST["redirect"])) {
			Response::redirect($_POST["redirect"]);
		}

		// Redirection vers la page précédente
		Redirect::redirectBack();
	}

}