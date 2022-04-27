<?php

use Model\Db\Compte;
use Model\Db\Sujethandicape;
use Model\Helper;

/** @var Sujethandicape */
$subject = $subject;

?>

<h1 class="m-2">Sujet n°<?= $subject->getId(); ?> <em>"<?= $subject->getIdSujetHandicape() ?>"</em></h1>

<p class="text-muted">
	Ici vous retrouvez toutes les informations du sujet <strong><?= $subject->getIdSujetHandicape(); ?></strong>.
</p>

<!-- Contenu de la page -->
<section class="form-sheet">
	<h4>Informations générales</h4>

	<div class="row">
		<div class="col">
			<div class="p-2">Date de saisie : <?= $subject->getDateAjout() !== null ? Helper::dateDBToFrench($subject->getDateAjout()) : "inconnu" ?></div>
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			<div class="p-2">Âge estimé :
				<?php
				if ($subject->getAgeMin() !== null && $subject->getAgeMax() !== null && $subject->getAgeMin() !== $subject->getAgeMax()) echo "entre {$subject->getAgeMin()} et {$subject->getAgeMax()} ans";
				else if ($subject->getAgeMin() !== null) echo "{$subject->getAgeMin()} ans";
				else if ($subject->getAgeMax() !== null) echo "{$subject->getAgeMax()} ans";
				else echo "inconnu";
				?>
			</div>
		</div>
		<div class="col-8">
			<div class="p-2">Méthode âge : <?= $subject->getAgeMethode(); ?></div>
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			<div class="p-2">Sexe : <?= $subject->getSexe(); ?></div>
		</div>
		<div class="col-8">
			<div class="p-2">Méthode sexe : <?= $subject->getSexeMethode(); ?></div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="p-2">
				Datation :
				<?php
				if ($subject->getDateMin() !== null && $subject->getDateMax() !== null && $subject->getDateMin() !== $subject->getDateMax()) echo "entre {$subject->getDateMin()} et {$subject->getDateMax()}";
				else if ($subject->getDateMin() !== null) echo "{$subject->getDateMin()} ans";
				else if ($subject->getDateMax() !== null) echo "{$subject->getDateMax()} ans";
				else echo "inconnu";
				?>
			</div>
		</div>
		<div class="col">
			<!-- Ce n'est pas vraiment l'écart type qui est calculé mais bon... -->
			<?php $diff = $subject->getDateMax() - $subject->getDateMin(); ?>
			<div class="p-2">Écart type de la datation : <?= $diff ?> année<?= $diff !== 1 ? 's' : null ?></div>
		</div>
		<div class="col">
			<div class="p-2">Milieu de vie : <?= $subject->getMilieuVie(); ?></div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="p-2">Contexte normatif : <?= $subject->getContexteNormatif(); ?></div>
		</div>
		<div class="col">
			<div class="p-2">Contexte : <?= $subject->getContexte(); ?></div>
		</div>
		<div class="col">
			<div class="p-2">Commentaire du contexte : <?= $subject->getCommentContext(); ?></div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="p-2">Type de dépôt : <?= $subject->getTypeDepot()->getNom(); ?></div>
		</div>
		<div class="col-md-4">
			<div class="p-2">Type de sépulture : <?= $subject->getTypeSepulture()->getNom(); ?></div>
		</div>
	</div>
</section>
<br />

<section class="form-sheet">
	<h4>Groupe du sujet</h4>
	<?php $group = $subject->getGroup(); ?>
	<div class="row">
		<div class="col-md-4 m-2">NMI : <?= $group->getNMI(); ?></div>
		<div class="col-md-4 m-2">Opération : <?= $group->getOperation()->getNomOp(); ?></div>
		<div class="col-md-4 m-2">Période : <?= $group->getChronology()->getName(); ?></div>
	</div>
	<div class="row">
		<div class="col-md-4 m-2">Date de début : <?= $group->getChronology()->getStart(); ?></div>
		<div class="col-md-4 m-2">Date de fin : <?= $group->getChronology()->getEnd(); ?></div>
	</div>
</section>
<br />

<!-- Dépôt -->
<section class="form-sheet">
	<?php
	$depot = $subject->getDepot();
	if ($depot !== null) {
		$numInventaire = $depot->getNumInventaire();
		$communeName = $depot->getCommune() !== null ? $depot->getCommune()->fullName() : "aucun";
		$address = $depot->getAdresse();
	} else {
		$numInventaire = null;
		$communeName = "aucun";
		$address = null;
	}
	?>
	<h4>Dépôt</h4>
	<?php $depot = $subject->getDepot(); ?>
	<div class="row">
		<div class="col m-2">Numéro d'inventaire : <?= $numInventaire; ?></div>
		<div class="col m-2">Commune : <?= $communeName ?></div>
		<div class="col m-2">Adresse : <?= $address; ?></div>
	</div>
</section>
<br />

<!-- Mobiliers / Accessoires -->
<section class="form-sheet">
	<h4>Accessoire</h4>
	<div class="row">
		<div class="col m-2">
			<?php foreach ($subject->getFurnitures() as $furniture) : ?>
				<?= $furniture->getNom(); ?><br>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<br />

<!-- Diagnostics -->
<section class="form-sheet">
	<h4>Atteinte invalidante</h4>
	<div class="row">
		<!-- Diagnostic -->
		<div class="col">
			<h5>Diagnostics</h5>
			<ul>
				<?php foreach ($subject->getAllDiagnosis() as $diagnosis) : ?>
					<li>
						<?= $diagnosis->getDiagnosis()->getNom(); ?> :
						<?php
						$spots = $diagnosis->getSpots();
						$i = 0;
						foreach ($spots as $spot) :
						?>
							<?= $spot->getNom(); ?><?= $i !== count($spots) - 1 ? "," : null; ?>
						<?php
							$i++;
						endforeach;
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<!-- Pathologies -->
		<div class="col">
			<h5>Pathologies infectieuses</h5>
			<ul>
				<?php foreach ($subject->getPathologies() as $pathology) : ?>
					<li><?= $pathology->getName(); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<!-- Appareils compensatoires -->
		<div class="col">
			<h5>Appareils compensatoires</h5>
			<ul>
				<?php foreach ($subject->getItemsHelp() as $item) : ?>
					<li><?= $item->getName(); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col m-2">Commentaire du diagnostic : <?= $subject->getCommentDiagnosis(); ?></div>
		<div class="col m-2">Données génétiques : <?= $subject->getDonneesGenetiques(); ?></div>
	</div>
</section>
<br />

<section class="form-sheet">
	<h4>Iconographie</h4>
	<?php
	$urls = $subject->getUrlsImg();
	if (empty($urls)) :
	?>
		Aucune image.
	<?php else : ?>
		<?php
		for ($i = 0; $i < count($urls); $i++) :
			$url = $urls[$i];
		?>
			<a href="<?= $url ?>" target="_blank">
				<img src="<?= $url ?>" alt="" style="height: 300px;">
			</a>
		<?php endfor; ?>
	<?php endif; ?>
</section>
<br>

<?php if (Compte::checkPermission(Compte::PERM_WRITE, $subject->getOperation()->getId())) : ?>
	<!-- Suppression du sujet handicapé -->
	<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#validationPopup">Supprimer le sujet</button>

	<div class="modal" id="validationPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="validationPopupLabel" aria-hidden="true" style="z-index: 100000;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="validationPopupLabel">Suppression du sujet</h5>
				</div>
				<div class="modal-body">
					<p>
						Êtes-vous sûr de vouloir supprimer le sujet n°<?= $subject->getId() ?> <em>"<?= $subject->getIdSujetHandicape() ?>"</em> ?<br>
						<br>
						<i class='bi bi-info-circle-fill'></i> La suppression est irréversible.
					</p>
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

<script type="text/javascript">
	// Permet d'afficher un message d'alert avant la confirmation d'une suppression -->
	$("[name=btn_supp_sujet]").click(function() {
		var x = $(this).val();
		if (window.confirm("Vous êtes sur le point de supprimer un sujet. Êtes-vous sûr de supprimer le sujet " + x + " ?")) {
			$("#form_suppr_" + x).submit();
		}
	});
</script>