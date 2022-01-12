<?php

use Model\Sujethandicape;

/** @var Sujethandicape */
$subject = $subject;

?>

<style>
	section {
		background-color: #F5F5F5;
		padding: 10px;
	}
</style>

<!-- Entête de la page -->
<div class="container-xl">
	<h1 class="m-2">Sujet <?= $subject->getIdSujetHandicape(); ?></h1>
	<p class="text-muted">Ici vous retrouvez toutes les informations du sujet <strong><?= $subject->getIdSujetHandicape(); ?></strong>.</p>

	<!-- Contenu de la page -->
	<section>
		<h4>Informations générales</h4>

		<div class="row">
			<div class="col">
				<div class="row">
					<div class="col">
						<div class="p-2">Âge minimum : <?= $subject->getAgeMin(); ?></div>
					</div>
					<div class="col">
						<div class="p-2">Âge maximum : <?= $subject->getAgeMax(); ?></div>
					</div>
					<div class="col">
						<div class="p-2">Sexe : <?= $subject->getSexe(); ?></div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="p-2">
							<?php if ($subject->getDatingMin() === $subject->getDatingMax()) : ?>
								Datation : <?= $subject->getDatingMin(); ?>
							<?php else : ?>
								Datation : entre <?= $subject->getDatingMin(); ?> et <?= $subject->getDatingMax(); ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="col">
						<!-- Ce n'est pas vraiment l'écart type qui est calculé mais bon... -->
						<div class="p-2">Écart type de la datation : <?= $subject->getDatingMax() - $subject->getDatingMin(); ?> années</div>
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
			</div>
			<?php
			$urls = $subject->getUrlsImg();
			if (empty($urls)) $urls[] = "";
			for ($i = 0; $i < count($urls); $i++) :
				$url = $urls[$i];
			?>
				<div class="col-auto" style="padding:0; background-color: white;">
					<img src="<?= $url ?>" alt="Image indisponible" style="width: 400px;">
				</div>
			<?php endfor; ?>
		</div>

	</section>
	<br />

	<section>
		<h4>Groupe du sujet</h4>
		<?php $group = $subject->getGroup(); ?>
		<div class="row">
			<div class="col-md-4">
				<div class="p-2">NMI : <?= $group->getNMI(); ?></div>
			</div>
			<div class="col-md-4">
				<div class="p-2">Opération : <?= $group->getOperation()->getNomOp(); ?></div>
			</div>
			<div class="col-md-4">
				<div class="p-2">Période : <?= $group->getChronology()->getName(); ?></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="p-2">Date de début : <?= $group->getChronology()->getStart(); ?></div>
			</div>
			<div class="col-md-4">
				<div class="p-2">Date de fin : <?= $group->getChronology()->getEnd(); ?></div>
			</div>
		</div>
	</section>
	<br />

	<!-- Dépôt -->
	<section>
		<h4>Dépôt</h4>
		<?php $depot = $subject->getDepot(); ?>
		<div class="row">
			<div class="col">
				<div class="p-2">Numéro d'inventaire : <?= $depot->getNumInventaire(); ?>
				</div>
			</div>
			<div class="col">
				<?php $commune = $depot->getCommune(); ?>
				<div class="p-2">Commune : <?= $commune !== null ? $commune()->fullName() : "aucun" ?></div>
			</div>
			<div class="col">
				<div class="p-2">Adresse : <?= $depot->getAdresse(); ?></div>
			</div>
		</div>
	</section>
	<br />

	<!-- Mobiliers / Accessoires -->
	<section>
		<h4>Accessoire</h4>
		<div class="row">
			<div class="col">
				<div class="p-2">
					<?php foreach ($subject->getFurnitures() as $furniture) : ?>
						<?= $furniture->getNom(); ?><br>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<br />

	<!-- Diagnostics -->
	<section>
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
				<h5>Pathologies</h5>
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
			<div class="col">
				<div class="p-2">Commentaire du diagnostic : <?= $subject->getCommentDiagnosis(); ?></div>
			</div>
		</div>
	</section>

	<div class="d-grid gap-2 d-md-block p-1">
		<a class="btn btn-secondary" href="/public/operations/view/<?= $subject->getGroup()->getIdOperation(); ?>" role="button">Retour</a>
	</div>
</div>

<script type="text/javascript">
	// Permet d'afficher un message d'alert avant la confirmation d'une suppression -->
	$("[name=btn_supp_sujet]").click(function() {
		var x = $(this).val();
		if (window.confirm("Vous êtes sur le point de supprimer un sujet. Êtes-vous sûr de supprimer le sujet " + x + " ?")) {
			$("#form_suppr_" + x).submit();
		}
	});
</script>