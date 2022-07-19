<?php

use Fuel\Core\Asset;

?>

<!-- Accueil principal -->
<main class="bg-light px-2 py-5 mb-3 text-center">
	<h1>
		<span style="font-weight: 100;">Base de données</span> Archéologie du handicap
		<i class="bi bi-server"></i>
	</h1>
	<p class="lead">
		Pour accéder à la base, cliquer sur le bouton ci-dessous.<br>
		Avant la saisie, <b>lire absolument</b> le mode d'emploi.
	</p>

	<div class="row justify-content-sm-center home-row">
		<a class="btn btn-primary col-sm-auto" href="/public/operation">Opérations</a>
		<a class="btn btn-primary col-sm-auto" href="/public/recherche">Recherche</a>
		<a class="btn btn-primary col-sm-auto" href="/public/assets/other/mode_emploi.pdf" target="_blank">Mode d'emploi</a>
	</div>
</main>

<!-- Informations complémentaires -->
<div class="container">
	<div class="row center">
		<div class="col-sm-6 text-center mb-3">
			<?= Asset::img("arc_du_handicap-low.jpg", array("alt" => "arc_du_handicap.jpg", "style" => "width: 18rem; max-width: 100%")); ?>
		</div>

		<div class="col-sm-6">
			<p class="text-justify">
				La création de l'arborescence de la base a été élaborée par <b>Valérie Delattre</b> (Inrap),
				à l'origine du projet Archéologie du Handicap et par <b>Rozenn Colleter</b> (Inrap) et <b>Cyrille Le Forestier</b> (Inrap).
			</p>
			<p class="text-justify">
				Cette base a été conçue par <b>Virgile Louin</b> (stagiaire) et <b>Alex Baiet</b> (apprenti à l'université de Marne-la-Vallée) sous la tutelle technique de <b>Jean-Baptiste Barreau</b> (CNRS).
			</p>
			<p class="text-justify">
				Ce projet est une Action de Recherche Collective menée par l'Inrap. L'association Archéologie des nécropoles participe également à cette aventure.
			</p>
			<p>
				Pour plus d'informations : <a href="https://archeohandi.hypotheses.org/" target="_blank">https://archeohandi.hypotheses.org/</a>
			</p>
		</div>
	</div>

	<!-- Logos -->
	<div style="text-align: center;">
		<a class="logo-link" href="https://www.inrap.fr/" target="_blank">
			<?= Asset::img("logo/inrap-low.jpg", array('class' => "logo", "alt" => "inrap")) ?>
		</a>
		<a class="logo-link" href="https://creaah.cnrs.fr/" target="_blank">
			<?= Asset::img("logo/creaah-low.png", array('class' => "logo", "alt" => "creaah")) ?>
		</a>
		<a class="logo-link" href="https://artehis.u-bourgogne.fr/" target="_blank">
			<?= Asset::img("logo/artehis-low.jpg", array('class' => "logo", "alt" => "artehis")) ?>
		</a>
		<a class="logo-link" href="https://www.cnrs.fr/" target="_blank">
			<?= Asset::img("logo/cnrs-low.png", array('class' => "logo", "alt" => "cnrs")) ?>
		</a>
		<a class="logo-link" href="https://archeonec.hypotheses.org/" target="_blank">
			<?= Asset::img("logo/adn-low.jpg", array('class' => "logo", "alt" => "adn", "style" => "height: 80px;")) ?>
		</a>
	</div>
</div>