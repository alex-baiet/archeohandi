<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Model\Operation;
use Model\Organisme;
use Model\Typeoperation;

/** @var string Page de destination lors de la validation du formulaire. */
$action = $action;
$showError = false;
/** @var Operation Operation sur lequel construire le formulaire. */
if (isset($operation)) {
	$operation = $operation;
	$showError = true;
}
else $operation = new Operation(array());
/** @var string|unset Texte du popup de confirmation du formulaire. Si non défini, il n'y aura pas de popup. */
if (isset($modalBodyMain)) $modalBodyMain = $modalBodyMain;
/** @var string|unset */
if (isset($modalBodyInfo)) $modalBodyInfo = $modalBodyInfo;

/** Array des attributs les plus communs. */
$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "");

?>

<?=
Asset::js("form.js");
?>

<?=
Form::open(array(
	'action' => $action,
	'method' => 'POST',
	"style" => "background-color: #F5F5F5; padding: 10px;"
));
?>
<?php if ($showError) $operation->alertBootstrap("danger"); ?>

<h3 class="text-center">Opération</h3>

<!-- Affichage des champs -->
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
<?php /*<div class="col-md-12">
	<?= Form::label('À revoir', 'a_revoir'); ?>
	<?= Form::textarea("a_revoir", $operation->getARevoir(), array("class" => "form-control")); ?>
</div>*/ ?>

<div class="row my-4">
	<div class="col-md-4">
		<div class="form-floating">
			<?php $fullName = $operation->getCommune() === null ? "" : $operation->getCommune()->fullName(); ?>
			<?= Form::input("commune", $fullName, array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off")); ?>
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

<!-- Responsable d'opération -->
<div class="row my-2">
	<div class="col-md-6">
		<div class="form-floating">
			<?=
				Form::input("responsable", $operation->getResponsable(),
					array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off"));
			?>
			<?= Form::label("Responsable de l'opération", "responsable"); ?>
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
if (count($anthropologues) === 0) $anthropologues[] = "";
?>
<div class="row my-2">
	<div class="col-md-6">
		<?php for ($i = 0; $i < count($anthropologues); $i++): $anthro = $anthropologues[$i]; ?>
			<div class="form-floating">
				<?= Form::input("anthropologues[]", $anthro, generateAttr("form_anthropologue_$i")); ?>
				<?= Form::label("Anthropologue", "anthropologue_$i", array("id" => "form_anthropologue_label_$i")); ?>
			</div>
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
if (count($paleos) === 0) $paleos[] = "";
?>
<div class="row my-2">
	<div class="col-md-6">
		<?php for ($i = 0; $i < count($paleos); $i++): $paleo = $paleos[$i]; ?>
			<div class="form-floating">
				<?= Form::input("paleopathologistes[]", $paleo, generateAttr("form_paleopathologiste_$i")); ?>
				<?= Form::label("Paleopathologiste", "paleopathologiste_$i", array("id" => "form_paleopathologiste_label_$i")); ?>
			</div>
		<?php endfor; ?>
	</div>

	<div class="col-md-6">
		<div class="d-grid gap-2 d-md-flex my-2">
			<button type="button" class="btn btn-primary me-md-2" onclick="addPerson('form_paleopathologiste');"><i class="bi bi-plus"></i></button>
			<button type="button" class="btn btn-danger" onclick="removePerson('form_paleopathologiste');"><i class="bi bi-x"></i></button>
		</div>
	</div>
</div>

<!-- Bibliographie -->
<div class="col-md-12">
	<?= Form::label('Bibliographie', 'bibliographie'); ?>
	<?= Form::textarea("bibliographie", $operation->getBibliographie(), array("class" => "form-control")); ?>
</div>

<!-- Comptes -->
<h3 class="text-center mt-4">Comptes autorisés</h3>
<p>Non fonctionnels</p>

<div class="row my-2">
	<div class="col-md" id="form_compte_parent">
		<?php
		$accounts = array();
		if (empty($accounts)) $accounts[] = "";

		for ($i = 0; $i < count($accounts); $i++) :
			$acc = $accounts[$i];
		?>
			<div class="row" id="form_compte_copy_<?= $i; ?>">
				<div class="col-md">
					<div class="form-floating">
						<input type="text" class="form-control" id="form_compte_<?= $i ?>" name="comptes[]" placeholder="" value="<?= $acc ?>">
						<label for="compte_<?= $i ?>" id="form_compte_label_<?= $i ?>">Nom du compte</label>
						<script>addAutocomplete("form_compte_<?= $i; ?>", "compte");</script>
					</div>
				</div>
				<div class="col-auto">
					<button type="button" class="btn btn-danger btn-remove-copy" onclick="removeElem('compte', <?= $i; ?>);"><i class="bi bi-x"></i></button>
				</div>
			</div>
		<?php endfor; ?>
	</div>

	<div class="col-md-3">
		<button type="button" class="btn btn-primary me-md-2 btn-add-copy" onclick="addCopy('compte');"><i class="bi bi-plus"></i></button>
	</div>
</div>

<br/>

<!-- Confirmation / retour -->
<div class="row">
	<div class="d-md-block col">
		<a class="btn btn-secondary" href="/public/operations" role="button">Retour</a>
	</div>
	
	<div class="d-md-flex justify-content-md-end col">
		<?php if (isset($modalBodyMain)): ?>
			<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#validationPopup">Confirmer</button>
		<?php else: ?>
			<?= Form::submit('submit', 'Modifier', array('class' => 'btn btn-success')); ?>
		<?php endif; ?>
	</div>
</div>

<?php if (isset($modalBodyMain)): ?>
	<!-- Popup de confirmation -->
	<div class="modal" id="validationPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="validationPopupLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="validationPopupLabel">Voulez-vous continuer ?</h5>
				</div>
				<div class="modal-body">
					<p>
						<?= $modalBodyMain; ?><br><br>
						<i class='bi bi-info-circle-fill'></i> <?= $modalBodyInfo; ?>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#validationPopup">Retour</button>
					<button type="submit" class="btn btn-success" name="confirm_operation" id="form_confirm_operation" value="">Continuer</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?= Form::close(); ?>