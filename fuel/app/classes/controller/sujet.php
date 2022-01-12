<?php

use Fuel\Core\Controller;
use Fuel\Core\Controller_Template;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Helper;
use Model\Messagehandler;
use Model\Operation;
use Model\Sujethandicape;

class Controller_Sujet extends Controller_Template {
	private const DEBUG = false;

	public function action_view($id) {
		$subject = Sujethandicape::fetchSingle($id);
		if ($subject === null) {
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		$data = array("subject" => $subject);
		if (Controller_Sujet::DEBUG === true) Helper::varDump($subject);
		$this->template->title = 'Consultation du sujet';
		$this->template->content = View::forge('sujet/view', $data);
	}

	public function action_edit($id) {
		$subject = Sujethandicape::fetchSingle($id);
		if ($subject === null) {
			Messagehandler::prepareAlert("Le sujet n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		if (Input::method() === "POST") {
			// Maj du sujet
			$subject = new Sujethandicape($_POST);
			if ($subject->saveOnDB() && Controller_Sujet::DEBUG === false) {
				Messagehandler::prepareAlert("Modification du sujet réussi.", "success");
				Response::redirect("operations/view/".$subject->getGroup()->getIdOperation());
			} else if (Controller_Sujet::DEBUG === true) {
				Helper::varDump($_POST);
			}
		}

		$data = array("subject" => $subject);

		$this->template->title = 'Modification du sujet';
		$this->template->content = View::forge('sujet/edit', $data);
	}

	public function action_add($id) {
		$data = array('idOperation' => $id);

		if (Operation::fetchSingle($id) === null) {
			Messagehandler::prepareAlert("L'opération n'existe pas (quelqu'un vient peut-être de le supprimer).", "danger");
			Response::redirect("accueil");
		}

		if (Input::method() === "POST") {
			// Recréation du sujet à partir des valeurs entrées
			$subject = new Sujethandicape($_POST);
			if ($subject->saveOnDB()) {
				Messagehandler::prepareAlert("Ajout du sujet réussi.", "success");
				if (!$_POST["stayOnPage"]) Response::redirect("/operations/view/$id");

				$copy = new Sujethandicape($_POST);
				$copy->setIdSujetHandicape("");
				$data["subject"] = $copy;
			} else {
				// Problème lors de l'ajout
				$data["subject"] = $subject;
			}
		}

		$this->template->title = "Ajouter des sujets";
		$this->template->content = View::forge('sujet/add', $data);
	}
}
