<?php

use Fuel\Core\Asset;

?>

<!-- Accueil principal -->
<main class="jumbotron m-3">
	<div class="bg-light p-5 rounded mt-3 text-center">
		<h1>
			<span style="font-weight: 100;">Base de données</span> Archéologie du handicap
			<i class="bi bi-server"></i>
		</h1>
		<p class="lead">
			Pour accéder à la base, cliquer sur le bouton ci-dessous.<br>
			Avant la saisie, <b>lire absolument</b> le mode d'emploi.
		</p>

		<a href="/public/operation" class="btn btn-primary">Opérations</a>
		<a href="/public/recherche" class="btn btn-primary">Recherche</a>
		<a href="/public/assets/other/mode_emploi.pdf" target="_blank" class="btn btn-primary">Mode d'emploi</a>
		<div class="btn-group" role="group" aria-label="Basic example">
		</div>

	</div>
</main>

<!-- Informations complémentaires -->
<div class="container">
	<div class="row center">
		<div class="col-6">
			<p class="paragraph">
				Cette base a été conçue par <b>Virgile Loin</b> (stagiaire) et <b>Alex Baiet</b> (apprenti Université de Marne-la-Vallée) sous la tutelle technique de Jean-Baptiste Barreau (CNRS).
			</p>
			<p class="paragraph">
				La création de l'arborescence de la base a été élaborée par <b>Valérie Delattre</b> (Inrap),
				à l'origine du projet Archéologie du Handicap et par <b>Rozenn Colleter</b> (Inrap) et <b>Cyrille Le Forestier</b> (Inrap).
			</p>
			<p class="paragraph">
				Ce projet est une Action de Recherche Collective menée par l'Inrap. L'association Archéologie des nécropoles participe également à cette aventure.
			</p>
			<p class="paragraph">
				Pour plus d'informations : <a href="https://archeohandi.hypotheses.org/" target="_blank">https://archeohandi.hypotheses.org/</a>
			</p>
		</div>
		<div class="col-6" style="text-align: justify;">
			<?= Asset::img("arc_du_handicap-low.jpg", array("alt" => "arc_du_handicap.jpg", "style" => "width: 50%;")); ?>
		</div>
	</div>
</div>

<!-- Logos -->
<div class="container">
	<div style="text-align: center;">
		<a href="https://www.inrap.fr/" target="_blank">
			<?= Asset::img("logo/inrap-low.jpg", array('class' => "logo", "alt" => "inrap")) ?>
		</a>
		<a href="https://creaah.cnrs.fr/" target="_blank">
			<?= Asset::img("logo/creaah-low.png", array('class' => "logo", "alt" => "creaah")) ?>
		</a>
		<a href="https://artehis.u-bourgogne.fr/" target="_blank">
			<?= Asset::img("logo/artehis-low.jpg", array('class' => "logo", "alt" => "artehis")) ?>
		</a>
		<a href="https://www.cnrs.fr/" target="_blank">
			<?= Asset::img("logo/cnrs-low.png", array('class' => "logo", "alt" => "cnrs")) ?>
		</a>
		<a href="https://archeonec.hypotheses.org/" target="_blank">
			<?= Asset::img("logo/adn-low.jpg", array('class' => "logo", "alt" => "adn", "style" => "height: 80px;")) ?>
		</a>
	</div>
</div>