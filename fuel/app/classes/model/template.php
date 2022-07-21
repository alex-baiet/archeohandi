<?php

namespace Model;

use Fuel\Core\Controller_Template;

/** Base des pages */
abstract class Template extends Controller_Template {
	/** Change le titre de l'onglet. */
	public function title(string $value) { $this->template->title = $value; }
	/** Importe ou non la bibliothèque JQuery. */
	public function jquery(bool $import) { $this->template->jquery = $import; }
	/** Importe ou non la bibliothèque Leaflet. */
	public function leaflet(bool $import) { $this->template->leaflet = $import; }
	/** Affiche ou non les différents liens de la barre de navigation (compte exclu).  */
	public function navActive(bool $active) { $this->template->navActive = $active; }
	/** Liste des fichiers CSS locaux à importer. */
	public function css(array $files) { $this->template->css = $files; }
	/** Liste des fichiers Javascript locaux à importer. */
	public function js(array $files) { $this->template->js = $files; }
}

