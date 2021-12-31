<?php

use Fuel\Core\Asset;
?>
<main class="jumbotron m-3">
	<div class="bg-light p-5 rounded mt-3 text-center">
		<h1>Bienvenue sur la base de données archeohandi
			<i class="bi bi-server"></i>
		</h1>
		<p class="lead">Pour accéder à la base, cliquer sur le bouton ci-dessous</p>
		<a class="btn btn-lg btn-primary" href="./operations" role="button">&raquo; Cliquer</a>
	</div>
</main>
<div class="container">
	<div class="row center">
		<div class="col-6" align="justify">
			<div class="col-8">
				<h2 class="text-center">Présentation</h2>
				<p class="fs-6 text-justify">Ceci est la première réalisation pour mon stage. Il s'agit d'un site permettant de pouvoir accéder facilement
					aux données des chercheurs dans leur rubrique. Au fur et à mesure de l'avancement du site, nous pourrons modifier ou supprimer les éléments
					des différentes listes. C'est un challenge pour moi, car nous utilisons FuelPHP auquel je ne suis pas familier. Je remercie Jean-Baptiste Barreau
					pour me donner l'opportunité d'effectuer ce stage dans ce domaine que j'apprécie. <em>- Virgile LOUIN</em></p>
			</div>
		</div>
		<div class="col-4" align="justify">
			<h2 class="text-center">Mention</h2>
			<ul>
				<li>Maitre de stage : Jean-Baptiste Barreau</li>
				<li>
					Stagiaires :
					<ul>
						<li>Virgile LOUIN (2021)</li>
						<li>Alex BAIET (2021-2022)</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="container">
	<div style="text-align: center;">
		<?= Asset::img(array('Inrap.jpg', 'creaah.png', 'artehismedium.jpg', 'logo_amis.png', 'Centre_national_de_la_recherche_scientifique.png'), array('style' => 'width: 100 px; height: 50px; margin: 15px;')); ?>
	</div>
</div>