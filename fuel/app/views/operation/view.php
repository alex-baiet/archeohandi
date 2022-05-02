<?php

use Model\Dataview;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Organisme;
use Model\Helper;

/** @var Operation Operation actuelle. */
$operation = $operation;
/** @var string Message a afficher. */
$msg = isset($msg) ? $msg : null;

$sujets = $operation->getSubjects();

?>

<h1 class="m-2">Opération n°<?= $operation->getId() ?> <em>"<?= $operation->getNomOp(); ?>"</em></h1>

<p class="text-muted">
	Ici vous retrouvez toutes les informations de l'opération <strong><?= $operation->getNomOp(); ?></strong>.<br>
	<?php if ($operation->getSubjectsCount() === 0) : ?>
		Cette opération ne contient aucun sujet pour le moment.
	<?php else : ?>
		Un total de <b><?= $operation->getSubjectsCount() ?></b> sujets sont enregistrés sur cette opération.
	<?php endif; ?>
</p>

<!-- Contenu de la page. Affichage des informations de l'opération -->
<div class="row">
	<div class="col-lg-6">
		<!-- Informations globales -->
		<section class="view-sheet">
			<h2>Informations</h2>
			<div class="info">Date de saisie : <?= Dataview::dataToView($operation->getDateAjout(), function ($value) { return Helper::dateDBToFrench($value); }) ?></div>
			<?php $commune = $operation->getCommune(); ?>
			<div class="info">Département : <?= $commune !== null ? Dataview::dataToView($commune->getDepartement()) : Dataview::dataToView(null) ?></div>
			<div class="info">Commune : <?= $commune !== null ? Dataview::dataToView($commune->getNom()) : Dataview::dataToView(null) ?></div>
			<div class="info">Adresse : <?= Dataview::dataToView($operation->getAdresse()) ?></div>
			<div class="info">Numéro INSEE : <?= $commune !== null ? Dataview::dataToView($commune->getInsee()) : Dataview::dataToView(null) ?></div>
			<div class="info">Numéro d'opération : <?= Dataview::dataToView($operation->getNumeroOperation()) ?></div>
			<div class="info">Année de l'opération : <?= Dataview::dataToView($operation->getAnnee()) ?></div>
			<div class="info">Type d'opération : <?= Dataview::dataToView($operation->getTypeOperation()->getNom()) ?></div>
			<?php $org = $operation->getOrganisme() !== null ? $operation->getOrganisme() : Organisme::fetchSingle(-1); ?>
			<div class="info">Organisme : <?= Dataview::dataToView($org->getNom()) ?></div>
			<div class="info">Patriarche : <?= Dataview::dataToView($operation->getPatriarche()) ?></div>
			<div class="info">Arrêté de prescription : <?= Dataview::dataToView($operation->getArretePrescription()) ?></div>
			<div class="info">EA : <?= Dataview::dataToView($operation->getEA()) ?></div>
			<div class="info">OA : <?= Dataview::dataToView($operation->getOA()) ?></div>
		</section>

		<!-- Bibliographie -->
		<section class="view-sheet">
			<h2>Bibliographie</h2>
			<div class="info"><?= Dataview::descriptionToView($operation->getBibliographie()) ?></div>
			<ul>
				<?php foreach ($operation->getUrls() as $url) : ?>
					<li><a href="<?= $url ?>" target="_blank"><?= $url ?></a></li>
				<?php endforeach; ?>
			</ul>
		</section>
	</div>

	<div class="col-lg-6">
		<!-- Carte Leaflet -->
		<div id="map_parent" class="view-sheet-shape">
			<div id="map" style="height: 300px"></div>
			<div id='map_overlay' class='map-overlay' style="display: none; opacity: 0;"><p>Position inconnu</p></div>
		</div>
		<script>
			Leaflet.initMap("map");
			Leaflet.disableDragging();
			<?php if ($operation->getLongitude() !== null && $operation->getLatitude() !== null) : ?>
				Leaflet.setMarker(<?= $operation->getLatitude() ?>, <?= $operation->getLongitude() ?>);
			<?php else : ?>
				// Ajoute un titre qui cache la map
				Leaflet.map.whenReady(function () {
					const overlay = document.getElementById("map_overlay");
					overlay.style.display = "block";
					window.requestAnimationFrame(function () { overlay.style.opacity = 1; })
				})
			<?php endif; ?>
		</script>

		<!-- Personnes -->
		<section class="view-sheet">
			<h2>Équipe</h2>
			<h3>Responsable</h3>
			<?php if (empty($operation->getResponsable())) : ?>
				<span class="no-data">Aucun responsable</span>
			<?php else : ?>
				<ul>
					<li><b><?= $operation->getResponsable(); ?></b></li>
				</ul>
			<?php endif; ?>

			<hr>

			<h3>Anthropologues</h3>
			<?php if (empty($operation->getAnthropologues())) : ?>
				<span class="no-data">Aucun anthropologue</span>
			<?php else : ?>
				<ul>
					<?php foreach ($operation->getAnthropologues() as $person) : ?>
						<li><b><?= $person; ?></b></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<hr>

			<h3>Paleopathologistes</h3>
			<?php if (empty($operation->getPaleopathologistes())) : ?>
				<span class="no-data">Aucun paleopathologiste</span>
			<?php else : ?>
				<ul>
					<?php foreach ($operation->getPaleopathologistes() as $person) : ?>
						<li><b><?= $person; ?></b></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</section>
	</div>
</div>

<!-- Iconographie -->
<section class="view-sheet">
	<h2>Iconographie</h2>
	<?php
	$urls = $operation->getUrlsImg();
	if (empty($urls)) :
	?>
		<div class="info no-data">Aucune image</div>
	<?php else : ?>
		<div class="gallery">
			<?php
			for ($i = 0; $i < count($urls); $i++) :
				$url = $urls[$i];
			?>
				<figure class="gallery-item">
					<img class="can-zoom" loading="lazy" src="<?= $url ?>" alt="<?= $url ?>">
				</figure>
			<?php endfor; ?>
		</div>
	<?php endif; ?>
</section>

<?php if (Compte::checkPermission(Compte::PERM_ADMIN, $operation->getId())) : ?>
	<!-- Suppression de l'opération -->
	<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#validationPopup">Supprimer l'opération</button>

	<div class="modal" id="validationPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="validationPopupLabel" aria-hidden="true" style="z-index: 100000;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="validationPopupLabel">Suppression de l'opération</h5>
				</div>
				<div class="modal-body">
					<p>
						Êtes-vous sûr de vouloir supprimer l'opération n°<?= $operation->getId() ?> <em>"<?= $operation->getNomOp() ?>"</em> ?
						<br><br>
						<i class='bi bi-info-circle-fill'></i> La suppression est irréversible.
					</p>
				</div>
				<div class="modal-footer">
					<form method="post" action="/public/operation/delete">
						<input type="hidden" name="redirect" value="/operation">
						<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#validationPopup">Retour</button>
						<button type="submit" name="id" id="form_id" value="<?= $operation->getId() ?>" class="btn btn-danger">Supprimer</button>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>