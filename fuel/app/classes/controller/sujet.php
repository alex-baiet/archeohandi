<?php

use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Sujethandicape;
use Model\Helper;
use Model\Messagehandler;
use Model\Redirect;
use Model\Template;

/** Pages de gestions des sujets handicapés. */
class Controller_Sujet extends Template {
	private const DEBUG = false;

	/**
	 * Page de consultation des infos d'un sujet handicapé.
	 * @param $id Id du sujet.
	 */
	public function action_description($id) {
		Compte::checkPermissionRedirect("Vous devez être connecté pour pouvoir consulter un sujet.", Compte::PERM_WRITE);

		$subject = Sujethandicape::fetchSingle($id);
		if ($subject === null) {
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
			Response::redirect("accueil");
		}

		$data = array("subject" => $subject);
		if (Controller_Sujet::DEBUG === true) Helper::varDump($subject);
		$this->title("Consultation du sujet $id");
    $this->css(["gallery.css", "view.css"]);
    $this->js(["page_manager.js"]);
		$this->content(View::forge(
			'sujet/template',
			array(
				"content" => View::forge("sujet/description", $data),
				"subject" => $subject
			)
		));
	}

	/**
	 * Page d'édition d'un sujet handicapé.
	 * @param $id Id du sujet.
	 */
	public function action_edition($id) {
		$subject = Sujethandicape::fetchSingle($id);
		$operation = $subject->getOperation();
		
		Compte::checkPermissionRedirect(
			"Vous n'êtes pas autorisés à modifier un sujet de l'opération.",
			Compte::PERM_WRITE,
			$operation->getId()
		);

		if ($subject === null) {
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
			Response::redirect("accueil");
		}

		if (Input::method() === "POST") {
			// Maj nombre cas observable
			$operation->setObservables($_POST["observables"]);
			$operation->saveOnDB();

			// Maj du sujet
			$subject = new Sujethandicape($_POST, true);
			if ($subject->saveOnDB() && Controller_Sujet::DEBUG === false) {
				Messagehandler::prepareAlert("Modification du sujet réussi.", Messagehandler::ALERT_SUCCESS);
				Response::redirect("sujet/description/$id");
			} else if (Controller_Sujet::DEBUG === true) {
				echo "POST";
				Helper::varDump($_POST);
				echo "Objet";
				Helper::varDump($subject);
			}
		}

		$data = array("subject" => $subject);

		$this->title("Modification du sujet $id");
		$this->jquery(true);
    $this->css(["form.css"]);
    $this->js(["form.js", "form_sujet.js", "page_manager.js"]);
		$this->content(View::forge(
			'sujet/template',
			array(
				"content" => View::forge("sujet/edition", $data),
				"subject" => $subject
			)
		));
	}

	/**
	 * Page de création d'un nouveau sujet handicapé.
	 * @param $id Id de l'opération parent au sujet.
	 */
	public function action_ajout($id) {
		Compte::checkPermissionRedirect("Vous n'êtes pas autorisés à ajouter un sujet sur cette opération.", Compte::PERM_WRITE, $id);

		$data = array('idOperation' => $id);
		$operation = Operation::fetchSingle($id);

		if ($operation === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
			Response::redirect("accueil");
		}

		if (Input::method() === "POST") {
			// Recréation du sujet à partir des valeurs entrées
			$subject = new Sujethandicape($_POST);
			if ($subject->saveOnDB()) {
				// Maj nombre cas observable
				$operation->setObservables($_POST["observables"]);
				$operation->saveOnDB();

				Messagehandler::prepareAlert("Ajout du sujet réussi.", Messagehandler::ALERT_SUCCESS);
				if (!$_POST["stayOnPage"]) Response::redirect("/operation/sujets/$id");

				$copy = new Sujethandicape($_POST);
				$copy->setIdSujetHandicape("");
				$data["subject"] = $copy;
			} else {
				// Problème lors de l'ajout
				$data["subject"] = $subject;
			}
		}

		$this->title("Nouveau sujet");
		$this->jquery(true);
    $this->css(["form.css"]);
    $this->js(["form.js", "form_sujet.js", "page_manager.js"]);
		$this->content(View::forge('sujet/ajout', $data));
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
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", Messagehandler::ALERT_DANGER);
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
			Messagehandler::prepareAlert("Le sujet n°$id a bien été supprimé.", Messagehandler::ALERT_SUCCESS);
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
