<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\DB;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;
use Model\Helper;
use Model\Sujethandicape;

class Controller_Sujet extends Controller_Template {
	private const DEBUG = true;

	public function action_view($id) {
		//Permet de récupérer toutes les informations du sujet handicapé

		$subject = Sujethandicape::fetchSingle($id);
		$data = array("subject" => $subject);
		if (Controller_Sujet::DEBUG === true) Helper::varDump($subject);
		$this->template->title = 'Consultation du sujet';
		$this->template->content = View::forge('sujet/view', $data);
	}

	public function action_edit($id) {
		$subject = Sujethandicape::fetchSingle($id);
		if (Controller_Sujet::DEBUG === true) Helper::varDump($subject);

		$data = array("subject" => $subject);

		$this->template->title = 'Modification du sujet';
		$this->template->content = View::forge('sujet/edit', $data);
	}

	public function action_add($id) {
		$data = array('idOperation' => $id);

		if (Input::method() === "POST") {
			// Recréation du sujet à partir des valeurs entrées
			$subject = new Sujethandicape($_POST, true);
			if ($subject->saveOnDB() && Controller_Sujet::DEBUG === false) {
				Response::redirect("/operations/view/$id");
			} else {
				$data["subject"] = $subject;
				if (Controller_Sujet::DEBUG === true) Helper::varDump($subject);
			}

		}

		$this->template->title = "Ajouter des sujets";
		$this->template->content = View::forge('sujet/add', $data);
	}
}
