<?php

use Fuel\Core\Asset;
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

<p class="text-muted">Ici vous retrouvez toutes les informations de l'opération <strong><?= $operation->getNomOp(); ?></strong>.
</p>

<!-- Contenu de la page. Affichage des informations de l'opération -->
<div class="container" style="background-color: #F5F5F5;">
	<h4>Informations</h4>
	<div class="row">
		<div class="col">
			<div class="p-2">Numéro d'opération : <?= $operation->getNumeroOperation(); ?></div>
		</div>
		<div class="col">
			<div class="p-2">Date de saisie : <?= $operation->getDateAjout() !== null ? Helper::dateDBToFrench($operation->getDateAjout()) : "inconnu" ?></div>
		</div>
		<div class="col">
			<div class="p-2">Année de l'opération : <?= $operation->getAnnee(); ?></div>
		</div>
	</div>
	<div class="row">
		<?php $commune = $operation->getCommune(); ?>
		<div class="col">
			<div class="p-2">Département : <?php if ($commune !== null) echo $commune->getDepartement(); ?></div>
		</div>
		<div class="col">
			<div class="p-2">Commune : <?php if ($commune !== null) echo $commune->getNom(); ?></div>
		</div>
		<div class="col">
			<div class="p-2">Adresse : <?= $operation->getAdresse(); ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="p-2">Numéro INSEE : <?= $operation->getInsee(); ?></div>
		</div>
		<div class="col">
			<div class="p-2">Type d'opération : <?= $operation->getTypeOperation()->getNom(); ?></div>
		</div>
		<div class="col">
			<?php $org = $operation->getOrganisme() !== null ? $operation->getOrganisme() : Organisme::fetchSingle(-1); ?>
			<div class="p-2">Organisme : <?= $org->getNom() ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="p-2">Patriarche : <?= $operation->getPatriarche(); ?></div>
		</div>
		<div class="col">
			<div class="p-2">EA : <?= $operation->getEA(); ?></div>
		</div>
		<div class="col-md-4">
			<div class="p-2">OA : <?= $operation->getOA(); ?></div>
		</div>
	</div>
</div>
<br />
<div class="container" style="background-color: #F5F5F5;">
	<h4>Personnes</h4>
	<div class="row">
		<div class="col">
			<div class="p-2">
				Responsable de l'opération :<?= $operation->getResponsable(); ?>
			</div>
		</div>
		<div class="col">
			<div class="p-2">
				Anthropologue(s) :
				<?php foreach ($operation->getAnthropologues() as $person) : ?>
					<br>- <?= $person; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="col">
			<div class="p-2">
				Paleopathologiste(s) :
				<?php foreach ($operation->getPaleopathologistes() as $person) : ?>
					<br>- <?= $person; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<br />

<div class="container" style="background-color: #F5F5F5;">
	<h4>Autre</h4>
	<p>Bibliographie : <?= $operation->getBibliographie(); ?></p>
</div>

<div class="container" style="background-color: #F5F5F5;">
	<h4>Iconographie</h4>
	<?php
	$urls = $operation->getUrlsImg();
	if (empty($urls)) :
	?>
		<p>Aucune image.</p>
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
</div>
<br />

<?= Asset::css('scrollbar.css'); ?>