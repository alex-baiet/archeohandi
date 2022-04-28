<?php

use Model\Db\Compte;
use Model\Db\Localisation;
use Model\Db\Sujethandicape;
use Model\Formview;
use Model\Helper;

/** @var Sujethandicape */
$subject = $subject;

?>

<h1 class="m-2">Sujet n°<?= $subject->getId(); ?> <em>"<?= $subject->getIdSujetHandicape() ?>"</em></h1>

<p class="text-muted">
	Ici vous retrouvez toutes les informations du sujet <strong><?= $subject->getIdSujetHandicape(); ?></strong>.
</p>

<div class="row">
	<div class="col-lg">
		<!-- Informations générales -->
		<section class="view-sheet">
			<h2>Informations générales</h2>

			<div class="info">Date de saisie :
				<?=
				Formview::dataToView(
					$subject->getDateAjout(),
					function ($value) { return Helper::dateDBToFrench($value); })
				?>
			</div>

			<div class="info">
				Datation :
				<?php
				$arr = array();
				if ($subject->getDateMin() !== null) $arr[] = $subject->getDateMin();
				if ($subject->getDateMax() !== null) $arr[] = $subject->getDateMax();

				echo Formview::dataToView(
					$arr,
					function ($value) {
						if (count($value) === 2) return "entre {$value[0]} et {$value[1]}";
						else return "{$value[0]}}";
					}
				)
				?>
				<div class="indent-1">
					Écart type de la datation :
					<?php
					if ($subject->getDateMin() === null && $subject->getDateMax() === null) $diff = null;
					else if ($subject->getDateMin() === null && $subject->getDateMax() === null) $diff = 0;
					else $diff = $subject->getDateMax() - $subject->getDateMin();
					echo Formview::dataToView($diff, function ($value) { return "$value année".($value !== 1 ? 's' : null); })
					?>
				</div>
			</div>

			<div class="info">
				Sexe : <?= Formview::dataToView($subject->getSexe()); ?>
				<div class="indent-1">Méthode sexe : <?= Formview::dataToView($subject->getSexeMethode()) ?></div>
			</div>

			<div class="info">Âge estimé :
				<?php
				$arr = array();
				if ($subject->getAgeMin() !== null) $arr[] = $subject->getAgeMin();
				if ($subject->getAgeMax() !== null) $arr[] = $subject->getAgeMax();

				echo Formview::dataToView(
					$arr,
					function ($value) {
						if (count($value) === 2) return "entre {$value[0]} et {$value[1]} ans";
						else return "{$value[0]} ans}";
					}
				)
				?>
				<div class="indent-1">Méthode âge : <?= Formview::dataToView($subject->getAgeMethode()); ?></div>
			</div>

			<div class="info">Milieu de vie : <?= Formview::dataToView($subject->getMilieuVie()) ?></div>
			<div class="info">Type de dépôt : <?= Formview::dataToView($subject->getTypeDepot()->getNom()) ?></div>
			<div class="info">Type de sépulture : <?= Formview::dataToView($subject->getTypeSepulture()->getNom()) ?></div>
			<div class="info">Contexte : <?= Formview::dataToView($subject->getContexte()) ?></div>
			<div class="info">Contexte normatif : <?= Formview::dataToView($subject->getContexteNormatif()) ?></div>
			<div class="info">Commentaire du contexte : <?= Formview::descriptionToView($subject->getCommentContext()) ?></div>
		</section>
	</div>

	<div class="col-lg">
		<!-- Groupe du sujet -->
		<section class="view-sheet">
			<h2>Groupe du sujet</h2>
			<?php $group = $subject->getGroup(); ?>
			<div class="info">NMI : <?= Formview::dataToView($group->getNMI()) ?></div>
			<div class="info">Opération : <?= Formview::dataToView($group->getOperation()->getNomOp()) ?></div>
			<div class="info">Période : <?= Formview::dataToView($group->getChronology()->getName()) ?></div>
			<div class="info">Date de début : <?= Formview::dataToView($group->getChronology()->getStart()) ?></div>
			<div class="info">Date de fin : <?= Formview::dataToView($group->getChronology()->getEnd()) ?></div>
		</section>

		<!-- Dépôt -->
		<section class="view-sheet">
			<?php
			$depot = $subject->getDepot();
			if ($depot !== null) {
				$numInventaire = $depot->getNumInventaire();
				$communeName = $depot->getCommune() !== null ? $depot->getCommune()->fullName() : null;
				$address = $depot->getAdresse();
			} else {
				$numInventaire = null;
				$communeName = null;
				$address = null;
			}
			?>
			<h2>Dépôt</h2>
			<?php $depot = $subject->getDepot(); ?>
			<div class="info">Numéro d'inventaire : <?= Formview::dataToView($numInventaire) ?></div>
			<div class="info">Commune : <?= Formview::dataToView($communeName) ?></div>
			<div class="info">Adresse : <?= Formview::dataToView($address) ?></div>
		</section>

		<!-- Mobiliers / Accessoires -->
		<section class="view-sheet">
			<h2>Accessoire</h2>
			<?php if (empty($subject->getFurnitures())) : ?>
				<div class="info"><span class="no-data">Aucun accessoire</span></div>
			<?php else : ?>
				<ul>
					<?php foreach ($subject->getFurnitures() as $furniture) : ?>
						<li><b><?= $furniture->getNom(); ?></b></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<div class="info">Description du mobilier(s) : <?= Formview::descriptionToView($subject->getDescriptionMobilier()) ?></div>
		</section>

	</div>

	<div class="col-lg">
		<!-- Atteinte invalidante -->
		<section class="view-sheet">
			<h2>Atteinte invalidante</h2>
			<!-- Diagnostic -->
			<h3>Diagnostics</h3>
			<?php if (empty($subject->getAllDiagnosis())) : ?>
				<div class="info"><span class="no-data">Aucun diagnostic</span></div>
			<?php else : ?>
				<ul>
					<?php foreach ($subject->getAllDiagnosis() as $diagnosis) : ?>
						<li>
							<b><?= $diagnosis->getDiagnosis()->getNom(); ?></b>
							<ul>
								<?php
								$spots = $diagnosis->getSpots();
								if (count($spots) === Localisation::count()) :
								?>
									<li>corps complet</li>
								<?php else : ?>
									<?php foreach ($spots as $spot) : ?>
										<li><?= $spot->getNom(); ?></li>
									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<hr>

			<!-- Pathologies -->
			<h3>Pathologies</h3>
			<?php if (empty($subject->getPathologies())) : ?>
				<div class="info"><span class="no-data">Aucune pathologie</span></div>
			<?php else : ?>
				<ul>
					<?php foreach ($subject->getPathologies() as $pathology) : ?>
						<li><b><?= $pathology->getName(); ?></b></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<hr>

			<!-- Appareils compensatoires -->
			<h3>Appareils compensatoires</h3>
			<?php if (empty($subject->getItemsHelp())) : ?>
				<div class="info"><span class="no-data">Aucun appareil</span></div>
			<?php else : ?>
				<ul>
					<?php foreach ($subject->getItemsHelp() as $item) : ?>
						<li><b><?= $item->getName(); ?></b></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<hr>

			<div class="info">Commentaire du diagnostic : <?= Formview::descriptionToView($subject->getCommentDiagnosis()) ?></div>
			<div class="info">Données génétiques : <?= Formview::descriptionToView($subject->getDonneesGenetiques()) ?></div>
		</section>
	</div>
</div>


<!-- Iconographie -->
<section class="view-sheet">
	<h2>Iconographie</h2>
	<?php
	$urls = $subject->getUrlsImg();
	if (empty($urls)) :
	?>
		Aucune image.
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

<?php if (Compte::checkPermission(Compte::PERM_WRITE, $subject->getOperation()->getId())) : ?>
	<!-- Suppression du sujet handicapé -->
	<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#validationPopup">Supprimer le sujet</button>

	<div class="modal" id="validationPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="validationPopupLabel" aria-hidden="true" style="z-index: 100000;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="validationPopupLabel">Suppression du sujet</h3>
				</div>
				<div class="modal-body">
					<div class="info">
						Êtes-vous sûr de vouloir supprimer le sujet n°<?= $subject->getId() ?> <em>"<?= $subject->getIdSujetHandicape() ?>"</em> ?<br>
						<br>
						<i class='bi bi-info-circle-fill'></i> La suppression est irréversible.
					</div>
				</div>
				<div class="modal-footer">
					<form method="post" action="/public/sujet/delete">
						<input type="hidden" name="redirect" value="/operation/sujets/<?= $subject->getOperation()->getId() ?>">
						<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#validationPopup">Retour</button>
						<button type="submit" name="id" id="form_id" value="<?= $subject->getId() ?>" class="btn btn-danger">Supprimer</button>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>