<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\Uri;
use Model\Db\Compte;
use Model\Messagehandler;
use Model\Redirect;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?= $title; ?></title>
	<meta charset="utf-8">
	<meta name="author" content="Virgile Louin">
	<meta name="author" content="Alex BAIET">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	<?= Asset::css('bootstrap/bootstrap.min.css') ?>
	<?= Asset::css('button.css') ?>
	<?= Asset::css('form.css') ?>
	<?= Asset::css('global.css') ?>

	<!-- Javascript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<?= Asset::js('button.js') ?>
	<?= Asset::js('form.js') ?>

	<!-- Leaflet -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  	integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
  	integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
  	crossorigin=""></script>
	<?= Asset::js('leaflet.js') ?>
</head>

<body>
	<div class="cRetour"></div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="position: sticky; top: 0; z-index: 1000;">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="/public/accueil">
				<i class="bi bi-house-fill"></i> Accueil
			</a>
			
			<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">

					<li class="nav-item active">
						<a class="nav-link" href="/public/operations">Opérations</a>
					</li>

					<li class="nav-item active">
						<a class="nav-link" href="/public/search">Rechercher</a>
					</li>

					<?php
					$account = Compte::getInstance();
					if ($account !== null) :
					?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?= $account->getLogin(); ?></a>
							<div class="dropdown-menu">
								<form action="/public/compte/deconnexion" method="POST">
									<?= Form::hidden("previous_page", Uri::current()); ?>
									<?= Form::submit("disconnect", "Se déconnecter", array("class" => "dropdown-item")); ?>
								</form>
							</div>
						</li>
					<?php else : ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Compte</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="/public/compte/connexion">Se connecter</a>
								<a class="dropdown-item" href="/public/compte/creation">Créer un compte</a>
							</div>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>

	<?php
	// Zone de débuggage
	?>

	<?php Messagehandler::echoAlert(); ?>

	<!-- Tout le contenu de la page -->
	<?= $content; ?>

	<footer class="footer mt-3 pt-5 sticky-bottom">
		<div class="container">
			<div class="text-center">
				<div class="footer-copyright text-center">&copy;archeohandi | 2021-2022</div>
			</div>
		</div>
	</footer>
</body>

</html>

<?php
Redirect::setPreviousPage();
?>