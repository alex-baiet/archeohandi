<?php

use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Helper;
use Model\Messagehandler;
use Model\Redirect;
use Model\Searchresult;
use Model\Template;

class Controller_Operation extends Template {
	
	/** Page d'affichages de toutes les opérations. */
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
		$countOp = intval(Helper::querySelectSingle("SELECT COUNT(id) AS total FROM operation")["total"]);
		$data['countOp'] = $countOp;

		$this->title('Opérations');
    $this->css(["result.css", "gallery.css"]);
    $this->js(["nested_table.js"]);
		$this->content(View::forge('operation/index', $data, false));
	}

	/** Page des opérations personnels uniquement (opérations sur lesquels l'utilisateur a des droits). */
	public function action_personnel() {
		Compte::checkPermissionRedirect("Vous devez avoir un compte pour voir les opérations.", Compte::PERM_WRITE);
		$data = array();

		$lines = array();

		// Récupération des opérations
		$results = DB::select()
			->from("operation")
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
		$this->title('Opérations personnelles');
    $this->css(["result.css"]);
    $this->js(["nested_table.js"]);
		$this->content(View::forge('operation/personnel', $data, false));
	}

	/** Page d'ajout d'une opération. */
	public function action_ajout() {
		Compte::checkPermissionRedirect("Vous devez avoir un compte pour pouvoir créer une opération.", Compte::PERM_WRITE);

		// Ajout d'une opération
		if (Input::method() === "POST") {
			$operation = new Operation($_POST);
			
			if ($operation->saveOnDB()) {
				// Ajout de l'opération avec succès
				Messagehandler::prepareAlert("Ajout de l'opération réussi.", Messagehandler::ALERT_SUCCESS);
				Response::redirect("/sujet/ajout/{$operation->getId()}");
			}
		}

		$data = array();
		if (isset($operation)) $data["operation"] = $operation;

		$this->title('Nouvelle opération');
		$this->jquery(true);
    $this->leaflet(true);
    $this->css(["form.css"]);
    $this->js(["form.js", "form_operation.js"]);
		$this->content(View::forge('operation/ajout', $data));
	}

	/** Page affichant les informations d'une opération. */
	public function action_description($id) {
		Compte::checkPermissionRedirect("Vous devez être connecté pour pouvoir consulter une opération.", Compte::PERM_WRITE);

		$data = array();

		$operation = Operation::fetchSingle($id);		
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
			Response::redirect("accueil");
		}

		// Ajout des données à la view
		$data["operation"] = $operation;
		$this->title("Consultation de l'opération $id");
    $this->leaflet(true);
    $this->css(["view.css", "gallery.css"]);
    $this->js(["page_manager.js"]);
		$this->content(View::forge(
			'operation/template',
			array("content" => View::forge("operation/description", $data))
		));
	}

	/** Page des sujets d'une opération. */
	public function action_sujets($id) {
		Compte::checkPermissionRedirect("Vous devez être connecté pour pouvoir consulter une opération.", Compte::PERM_WRITE);

		$data = array();

		$operation = Operation::fetchSingle($id);
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
			Response::redirect("accueil");
		}

		// Ajout des données à la view
		$data["operation"] = $operation;
		$this->title("Sujets de l'opération $id");
    $this->js(["page_manager.js"]);
		$this->content(View::forge(
			'operation/template',
			array("content" => View::forge("operation/sujets", $data))
		));
	}

	/** Page d'édition d'une opération. */
	public function action_edition($id) {
		Compte::checkPermissionRedirect("Seul le créateur de l'opération peut modifier les informations de l'opération.", Compte::PERM_WRITE, $id);

		// Récupération des informations de l'opération
		$operation = Operation::fetchSingle($id);		
		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
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
				Messagehandler::prepareAlert("Modification de l'opération réussi.", Messagehandler::ALERT_SUCCESS);

				Response::redirect("/operation/description/$id");
			} else {
				// Les données ne sont pas valides : on affiche les problèmes
				$errors = $result;
			}
		}

		$data = array('operation'=> $operation, 'errors' => $errors);
		$this->title("Modification de l'opération $id");
		$this->jquery(true);
    $this->leaflet(true);
    $this->css(["form.css"]);
    $this->js(["form.js", "form_operation.js", "page_manager.js"]);
		$this->content(View::forge(
			'operation/template',
			array("content" => View::forge("operation/edition", $data))
		));
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
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
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
			Messagehandler::prepareAlert("L'opération n°$id a bien été supprimé.", Messagehandler::ALERT_SUCCESS);
		} else {
			Messagehandler::prepareAlert($error, Messagehandler::ALERT_DANGER);
		}

		// Redirection vers la page choisi
		if (isset($_POST["redirect"])) {
			Response::redirect($_POST["redirect"]);
		}

		// Redirection vers la page précédente
		Redirect::redirectBack();
	}

}