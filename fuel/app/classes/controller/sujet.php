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
use Model\Redirect;

/** Contient les pages de gestions des sujets handicapés. */
class Controller_Sujet extends Controller_Template {
	private const DEBUG = false;

	/**
	 * Page de consultation des infos d'un sujet handicapé.
	 * @param $id Id du sujet.
	 */
	public function action_view($id) {
		Compte::checkPermissionRedirect("Vous devez être connecté pour pouvoir consulter un sujet.", Compte::PERM_WRITE);

		$subject = Sujethandicape::fetchSingle($id);
		if ($subject === null) {
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		$data = array("subject" => $subject);
		if (Controller_Sujet::DEBUG === true) Helper::varDump($subject);
		$this->template->title = "Consultation du sujet $id";
		$this->template->content = View::forge(
			'sujet/template',
			array(
				"content" => View::forge("sujet/view", $data),
				"subject" => $subject
			)
		);
	}

	/**
	 * Page d'édition d'un sujet handicapé.
	 * @param $id Id du sujet.
	 */
	public function action_edit($id) {
		$subject = Sujethandicape::fetchSingle($id);
		$operation = $subject->getOperation();
		
		Compte::checkPermissionRedirect(
			"Vous n'êtes pas autorisés à modifier un sujet de l'opération.",
			Compte::PERM_WRITE,
			$operation->getId()
		);

		if ($subject === null) {
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		if (Input::method() === "POST") {
			// Maj nombre cas observable
			$operation->setObservables($_POST["observables"]);
			$operation->saveOnDB();

			// Maj du sujet
			$subject = new Sujethandicape($_POST, true);
			if ($subject->saveOnDB() && Controller_Sujet::DEBUG === false) {
				Messagehandler::prepareAlert("Modification du sujet réussi.", "success");
				Response::redirect("sujet/view/$id");
			} else if (Controller_Sujet::DEBUG === true) {
				echo "POST";
				Helper::varDump($_POST);
				echo "Objet";
				Helper::varDump($subject);
			}
		}

		$data = array("subject" => $subject);

		$this->template->title = "Modification du sujet $id";
		$this->template->content = View::forge(
			'sujet/template',
			array(
				"content" => View::forge("sujet/edit", $data),
				"subject" => $subject
			)
		);
	}

	/**
	 * Page de création d'un nouveau sujet handicapé.
	 * @param $id Id de l'opération parent au sujet.
	 */
	public function action_add($id) {
		Compte::checkPermissionRedirect("Vous n'êtes pas autorisés à ajouter un sujet sur cette opération.", Compte::PERM_WRITE, $id);

		$data = array('idOperation' => $id);
		$operation = Operation::fetchSingle($id);

		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		if (Input::method() === "POST") {
			// Recréation du sujet à partir des valeurs entrées
			$subject = new Sujethandicape($_POST);
			if ($subject->saveOnDB()) {
				// Maj nombre cas observable
				$operation->setObservables($_POST["observables"]);
				$operation->saveOnDB();

				Messagehandler::prepareAlert("Ajout du sujet réussi.", "success");
				if (!$_POST["stayOnPage"]) Response::redirect("/operations/sujets/$id");

				$copy = new Sujethandicape($_POST);
				$copy->setIdSujetHandicape("");
				$data["subject"] = $copy;
			} else {
				// Problème lors de l'ajout
				$data["subject"] = $subject;
			}
		}

		$this->template->title = "Nouveau sujet";
		$this->template->content = View::forge('sujet/add', $data);
	}

	/**
	 * Supprime un sujet.
	 * @param int $_POST["id"] Contient l'id du sujet à supprimer.
	 * @param string $_POST["redirect"] Contient l'url de destination après la suppression du sujet.
	 */
	public function action_delete() {
		if (!isset($_POST["id"])) Redirect::redirectBack();

		$id = $_POST["id"];

		$subject = Sujethandicape::fetchSingle($id);
		if ($subject === null) {
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Redirect::redirectBack();
		}

		$operation = $subject->getOperation();
		
		Compte::checkPermissionRedirect(
			"Vous n'êtes pas autorisés à modifier un sujet de l'opération.",
			Compte::PERM_WRITE,
			$operation->getId()
		);

		// Suppression du sujet
		$error = Sujethandicape::deleteOnDB($id);
		if ($error === null) {
			Messagehandler::prepareAlert("Le sujet n°$id a bien été supprimé.", "success");
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
