<?php
class Controller_Fonction extends Controller{

//Le controlleur action sert à faire les différentes actions de la page action sans être rattaché au template
public function action_action(){
  return Response::forge(View::forge('fonction/action'));
}
}
