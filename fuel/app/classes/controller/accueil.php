<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;

class Controller_Accueil extends Controller_Template {
  public function action_index(){
    $data = array();
    $this->template->title = 'Accueil';
    $this->template->content = View::forge('accueil/index', $data);
  }
}
