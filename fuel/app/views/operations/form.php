<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Model\Operation;
use Model\Organisme;
use Model\Personne;
use Model\Typeoperation;

/** @var Operation */
$operation = $operation;

/** Array des attributs les plus communs. */
$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "");

?>

<?=
Asset::js("form.js");
?>

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

	<div class="row my-4">
		<div class="col-md-4">
			<div class="form-floating">
				<?= Form::input("commune", $operation->getCommune()->getNom().', '.$operation->getCommune()->getDepartement(), array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
				<?= Form::label('Commune', 'commune'); ?>
				<script>addAutocomplete("form_commune", "commune");</script>
			</div>
		</div>

		<div class="col-md-4">
			<?= Organisme::generateSelect("id_organisme", $operation->getIdOrganisme()); ?>
		</div>
		<div class="col-md-4">
			<?= Typeoperation::generateSelect("id_type_op", $operation->getIdTypeOp()); ?>
		</div>
	</div>

	<div class="row my-2">
		<div class="col-md-4">
			<div class="form-floating">
				<?= Form::input("EA", $operation->getEA(), $defaultAttr); ?>
				<?= Form::label('EA', 'EA'); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-floating">
				<?= Form::input("OA", $operation->getOA(), $defaultAttr); ?>
				<?= Form::label('OA', 'OA'); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-floating">
				<?= Form::input("numero_operation", $operation->getNumeroOperation(), $defaultAttr); ?>
				<?= Form::label('Numéro d\'opération', 'numero_operation'); ?>
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
				<?= Form::input("arrete_prescription", $operation->getArretePrescription(), $defaultAttr); ?>
				<?= Form::label('Arrêté de prescription', 'arrete_prescription'); ?>
			</div>
		</div>
	</div>

	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("id_reponsable_op", $operation->getResponsableOp()->getNom().' '.$operation->getResponsableOp()->getPrenom(),
					array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
				<?= Form::label("Responsable de l'opération", "id_responsable_op"); ?>
				<script>addAutocomplete("form_id_reponsable_op", "personne");</script>
			</div>
		</div>
	</div>

	<!-- Anthropologues -->
	<?php
	/** Créer les attributs pour les inputs des anthropologues et paleopathologistes. */
	function generateAttr(string $id) {
		return array("type" => "text", "class" => "form-control", "placeholder" => "", "id" => $id, "autocomplete" => "off");
	}

	$anthropologues = $operation->getAnthropologues();
	if (count($anthropologues) === 0) $anthropologues[] = new Personne(array());
	?>
	<div class="row my-2">
		<div class="col-md-6">
			<?php for ($i = 0; $i < count($anthropologues); $i++): ?>
				<div class="form-floating">
					<?php $fullName = empty($anthropologues[$i]->getNom()) ? "" : $anthropologues[$i]->getNom().' '.$anthropologues[$i]->getPrenom(); ?>
					<?= Form::input("anthropologues[]", $fullName, generateAttr("form_anthropologue_$i")); ?>
					<?= Form::label("Anthropologue", "anthropologue_$i", array("id" => "form_anthropologue_label_$i")); ?>
				</div>
				<script>addAutocomplete("form_anthropologue_<?= $i ?>", "personne");</script>
			<?php endfor; ?>
		</div>

		<div class="col-md-6">
			<div class="d-grid gap-2 d-md-flex my-2">
				<button type="button" class="btn btn-primary me-md-2" onclick="addPerson('form_anthropologue');"><i class="bi bi-plus"></i></button>
				<button type="button" class="btn btn-danger" onclick="removePerson('form_anthropologue');"><i class="bi bi-x"></i></button>
			</div>
		</div>
	</div>

	<!-- Paleopathologistes -->
	<?php
	$paleos = $operation->getPaleopathologistes();
	if (count($paleos) === 0) $paleos[] = new Personne(array());
	?>
	<div class="row my-2">
		<div class="col-md-6">
			<?php for ($i = 0; $i < count($paleos); $i++): ?>
				<div class="form-floating">
					<?php $fullName = empty($paleos[$i]->getNom()) ? "" : $paleos[$i]->getNom().' '.$paleos[$i]->getPrenom(); ?>
					<?= Form::input("paleopathologistes[]", $fullName, generateAttr("form_paleopathologiste_$i")); ?>
					<?= Form::label("Paleopathologiste", "paleopathologiste_$i", array("id" => "form_paleopathologiste_label_$i")); ?>
				</div>
				<script>addAutocomplete("form_paleopathologiste_<?= $i ?>", "personne");</script>
			<?php endfor; ?>
		</div>

		<div class="col-md-6">
			<div class="d-grid gap-2 d-md-flex my-2">
				<button type="button" class="btn btn-primary me-md-2" onclick="addPerson('form_paleopathologiste');"><i class="bi bi-plus"></i></button>
				<button type="button" class="btn btn-danger" onclick="removePersonOld('form_paleopathologiste');"><i class="bi bi-x"></i></button>
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