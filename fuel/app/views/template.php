<?php

use Fuel\Core\Asset;
use Fuel\Core\Response;
use Fuel\Core\Uri;
use Model\Constants;
use Model\Db\Compte;
use Model\Messagehandler;

/** @var string */
$content = $content;
/** @var string */
$title = $title;
/** @var bool */
$navActive = isset($navActive) ? $navActive : true;

if (Constants::MAINTENANCE === true) {
	if (Uri::current() !== "https://archeohandi.huma-num.fr/public/accueil" && !Compte::checkPermission(Compte::PERM_ADMIN)) {
		Messagehandler::prepareAlert("Le site est actuellement en maintenance.");
		Response::redirect("/accueil");
	}
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<title>Archeohandi | <?= $title; ?></title>
	<meta charset="utf-8">
	<meta name="author" content="Virgile Louin">
	<meta name="author" content="Alex BAIET">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/public/assets/img/favicon.png" type="image/x-icon">

	<!-- CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	<?= Asset::css('bootstrap/bootstrap.min.css') ?>
	<?= Asset::css('accueil.css') ?>
	<?= Asset::css('error.css') ?>
	<?= Asset::css('form.css') ?>
	<?= Asset::css('gallery.css') ?>
	<?= Asset::css('global.css') ?>
	<?= Asset::css('mobile.css') ?>
	<?= Asset::css('result.css') ?>
	<?= Asset::css('shortcut.css') ?>
	<?= Asset::css('view.css') ?>

	<!-- Javascript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<?= Asset::js('helper.js') ?>
	<?= Asset::js('form.js') ?>
	<?= Asset::js('form_operation.js') ?>
	<?= Asset::js('form_sujet.js') ?>
	<?= Asset::js('page_manager.js') ?>
	<?= Asset::js('nested_table.js') ?>
	<?= Asset::js('search.js') ?>
	<?= Asset::js('window.js') ?>
	<?= Asset::js('gallery.js') ?>
	<?= Asset::js('db.js') ?>
	<?= Asset::js('db/archeo.js') ?>
	<?= Asset::js('db/search_result.js') ?>
	<?= Asset::js('db/operation.js') ?>
	<?= Asset::js('db/subject.js') ?>

	<!-- Leaflet -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
	<!-- Load Esri Leaflet from CDN -->
	<!-- <script src="https://unpkg.com/esri-leaflet@3.0.7/dist/esri-leaflet.js"
    integrity="sha512-ciMHuVIB6ijbjTyEdmy1lfLtBwt0tEHZGhKVXDzW7v7hXOe+Fo3UA1zfydjCLZ0/vLacHkwSARXB5DmtNaoL/g=="
    crossorigin=""></script> -->
	<!-- Load Esri Leaflet Geocoder from CDN -->
	<!-- <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.1/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.1/dist/esri-leaflet-geocoder.js"
    integrity="sha512-enHceDibjfw6LYtgWU03hke20nVTm+X5CRi9ity06lGQNtC9GkBNl/6LoER6XzSudGiXy++avi1EbIg9Ip4L1w=="
    crossorigin=""></script> -->
	<?= Asset::js('leaflet.js') ?>

</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="position: sticky; top: 0px; z-index: 2000;">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="/public/accueil">
				<i class="bi bi-house-fill"></i> Accueil
			</a>

			<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">

					<?php if ($navActive) : ?>
						<li class="nav-item active">
							<a class="nav-link" href="/public/operation">Opérations</a>
						</li>

						<?php /*if (Compte::checkPermission(Compte::PERM_ADMIN)) :*/ ?>
						<li class="nav-item active">
							<a class="nav-link" href="/public/recherche">Rechercher</a>
						</li>
						<?php /*endif*/ ?>

						<li class="nav-item active">
							<a class="nav-link" href="/public/assets/other/mode_emploi.pdf" target="_blank">Mode d'emploi</a>
						</li>
					<?php endif; ?>

					<li class="nav-item dropdown">
						<?php
						$account = Compte::getInstance();
						if ($account !== null) :
						?>
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?= $account->getLogin(); ?></a>
						<?php else : ?>
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Compte</a>

						<?php endif; ?>
						<div class="dropdown-menu dropdown-menu-dark bg-dark">
							<?php if ($account !== null) : ?>
								<!-- Operations personnelles -->
								<a class="dropdown-item" href="/public/operation/personnel">Mes opérations</a>
							<?php endif; ?>
							<?php if (Compte::checkPermission(Compte::PERM_DISCONNECTED)) : ?>
								<!-- Connexion -->
								<a class="dropdown-item" href="/public/compte/connexion">Se connecter</a>
							<?php endif; ?>
							<?php if (Compte::checkPermission(Compte::PERM_DISCONNECTED) || Compte::checkPermission(Compte::PERM_ADMIN)) : ?>
								<!-- Création d'un nouveau compte -->
								<a class="dropdown-item" href="/public/compte/creation">Créer un compte</a>
							<?php endif; ?>
							<?php if ($account !== null) : ?>
								<!-- Déconnexion -->
								<form action="/public/compte/deconnexion" method="POST">
									<input type="hidden" name="previous_page" value="<?= Uri::current() ?>">
									<button type="submit" name="disconnect" value="1" class="dropdown-item">Se déconnecter</button>
								</form>
							<?php endif; ?>
						</div>
					</li>
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
				<div class="text-ccenter">Un souci ? Envoyez un mail à <em>alex.baiet3@gmail.com</em></div>
				<div class="footer-copyright text-center">&copy;archeohandi | 2021-2022</div>
			</div>
		</div>
	</footer>

	<!-- Affiche les images en pleine écran -->
	<div id="fullpage"></div>
</body>

</html>