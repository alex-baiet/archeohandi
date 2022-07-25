<?php

use Fuel\Core\View;
use Model\Template;

/** Page d'accueil. */
class Controller_Accueil extends Template {

  /** Page d'accueil du site. */
  public function action_index(){
    $data = array();
    $this->navActive(false);
    $this->title('Accueil');
    $this->css(["accueil.css"]);
    $this->content(View::forge('accueil/index', $data));
  }
}
