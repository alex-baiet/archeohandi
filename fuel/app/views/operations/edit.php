<?php

use Fuel\Core\Asset;
use Fuel\Core\DB;
use Fuel\Core\Form;
use Model\Helper;
use Model\Operation;
use Model\Organisme;
use Model\Typeoperation;

/** @var Operation */
$operation = $operation;
/** @var string|null */
$errors = $errors;

/** Array des attributs les plus communs. */
$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "");

?>

<!-- Entête de la page -->
<div class="container">
	<h1 class="m-2">Modifier l'opération <?= $operation->getNomOp() ?>
		<a class="btn btn-sm btn-secondary" href="/public/operations/edit/<?= $operation->getIdSite(); ?>">Rafraichir la page
			<i class="bi bi-arrow-repeat"></i>
		</a>
	</h1>
	<p class="text-muted">Ici vous pouvez modifier une opération.</p>
</div>
<?= Form::open(array('action' => 'operations/edit/' . $operation->getIdSite() . '', 'method' => 'POST')); ?>

<?php $operation->alertBootstrap("danger"); ?>
<!-- Affichage des champs -->
<div class="container" style="background-color: #F5F5F5;">
	<div class="row my-2 pt-1">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("adresse", $operation->getAdresse(), $defaultAttr); ?>
				<?= Form::label('Adresse', 'adresse'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("annee", $operation->getAnnee(), array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
				<?= Form::label('Année de l\'opération', 'annee'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("X", $operation->getX(), array("type" => "number", "class" => "form-control", "placeholder" => "", "step" => "any")); ?>
				<?= Form::label('Position X', 'X'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("Y", $operation->getY(), array("type" => "number", "class" => "form-control", "placeholder" => "", "step" => "any")); ?>
				<?= Form::label('Position Y', 'Y'); ?>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<?= Form::label('À revoir', 'a_revoir'); ?>
		<?= Form::textarea("a_revoir", $operation->getARevoir(), array("class" => "form-control")); ?>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-floating">
				<?= Form::input("commune", $operation->getCommune()->getNom(), array("type" => "text", "class" => "form-control my-4")); ?>
				<!-- <input type="text" name="commune" id="commune" class="form-control border-info my-4" placeholder="Rechercher une commune ..." autocomplete="off" value="<?php // echo $nom_commune; ?>" required> -->
				<?= Form::label('Commune', 'commune'); ?>
			</div>
			<div class="col-md-auto">
				<!-- Zone d'autocomplétion ...? -->
				<!-- <div class="list-group" id="show-list"></div> -->
			</div>
		</div>
		<div class="col-md-4">
			<?= Organisme::generateSelect("organisme", $operation->getIdOrganisme()); ?>
		</div>
		<div class="col-md-4">
			<?= Typeoperation::generateSelect("type_operation", $operation->getIdTypeOp()); ?>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("EA", $operation->getEA(), $defaultAttr); ?>
				<?= Form::label('EA', 'EA'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("OA", $operation->getOA(), $defaultAttr); ?>
				<?= Form::label('OA', 'OA'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("patriarche", $operation->getPatriarche(), $defaultAttr); ?>
				<?= Form::label('Patriarche', 'patriarche'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("numero_operation", $operation->getNumeroOperation(), $defaultAttr); ?>
				<?= Form::label('Numéro d\'opération', 'numero_operation'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("arrete_prescription", $operation->getArretePrescription(), $defaultAttr); ?>
				<?= Form::label('Arrêté de prescription', 'arrete_prescription'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("responsable_op", $operation->getResponsableOp(), $defaultAttr); ?>
				<?= Form::label('Responsable de l\'opération', 'responsable_op'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("anthropologue", $operation->getAnthropologue(), $defaultAttr); ?>
				<?= Form::label('Anthropologue (Nom Prénom, etc.)', 'anthropologue'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("paleopathologiste", $operation->getPaleopathologiste(), $defaultAttr); ?>
				<?= Form::label('Paléopathologiste (Nom Prénom, etc.)', 'paleopathologiste'); ?>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<?= Form::label('Bibliographie', 'bibliographie'); ?>
		<?= Form::textarea("bibliographie", $operation->getBibliographie(), array("class" => "form-control")); ?>
	</div>
	<br />
	<div class="d-grid gap-2 d-md-block">
		<a class="btn btn-secondary" href="/public/operations" role="button">Retour</a>
	</div>
	<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 10px;">
		<?= Form::submit('submit', 'Modifier', array('class' => 'btn btn-success')); ?>
	</div>
</div>
<?= Form::close(); ?>

<?php
Asset::js('script_recherche.js');
?>