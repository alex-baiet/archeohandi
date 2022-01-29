<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Organisme;
use Model\Db\Typeoperation;

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
	"action" => $action,
	"method" => "POST",
	"style" => "background-color: #F5F5F5; padding: 10px;"
));
?>
<?php if ($showError) $operation->echoErrors(); ?>

<h3 class="text-center">Opération</h3>

<!-- Affichage des champs -->
<div class="row my-2 pt-1">
	<div class="col-md-6">
		<div class="form-floating">
			<input name="adresse" id="form_adresse" value="<?= $operation->getAdresse() ?>"
				type="text" class="form-control" placeholder="Adresse" maxlength="256">
			<label for="form_adresse">Adresse</label>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-floating">
			<?php
			$opYear = $operation->getAnnee();
			$year = $opYear < 1800 || $opYear === null ? null : $operation->getAnnee();
			?>
			<input name="annee" id="form_annee" value="<?= $year ?>"
				type="number" class="form-control" placeholder="Année de l'opération" min="1800" max="<?= date("Y") ?>">
			<label for="form_annee">Année de l'opération</label>
		</div>
	</div>
</div>
<div class="row my-2">
	<div class="col-md-6">
		<div class="form-floating">
			<input name="X" id="form_X" value="<?= $operation->getX() ?>"
				type="number" class="form-control" placeholder="Longitude" min="-180" max="180" step="any">
			<label for="form_X">Longitude</label>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-floating">
			<input name="Y" id="form_Y" value="<?= $operation->getY() ?>"
				type="number" class="form-control" placeholder="Latitude" min="-90" max="90" step="any">
			<label for="form_Y">Latitude</label>
		</div>
	</div>
</div>

<div class="row my-4">
	<div class="col-md-4">
		<div class="form-floating">
			<?php $fullName = $operation->getCommune() === null ? "" : $operation->getCommune()->fullName(); ?>
			<input name="commune" id="form_commune" value="<?= $fullName ?>"
				type="text" class="form-control" placeholder="Commune" autocomplete="off">
			<label for="form_commune">Commune</label>
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
			<input name="EA" id="form_EA" value="<?= $operation->getEA() ?>"
				type="text" class="form-control" placeholder="EA" maxlength="256">
			<label for="form_EA">EA</label>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-floating">
			<input name="OA" id="form_OA" value="<?= $operation->getOA() ?>"
				type="text" class="form-control" placeholder="OA" maxlength="256">
			<label for="form_OA">OA</label>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-floating">
			<input name="numero_operation" id="form_numero_operation" value="<?= $operation->getNumeroOperation() ?>"
				type="text" class="form-control" placeholder="Numéro d'opération" maxlength="256">
			<label for="form_numero_operation">Numéro d'opération</label>
		</div>
	</div>
</div>
<div class="row my-2">
	<div class="col-md-6">
		<div class="form-floating">
			<input name="patriarche" id="form_patriarche" value="<?= $operation->getPatriarche() ?>"
				type="text" class="form-control" placeholder="Patriarche" maxlength="256">
			<label for="form_patriarche">Patriarche</label>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-floating">
			<input name="arrete_prescription" id="form_arrete_prescription" value="<?= $operation->getArretePrescription() ?>"
				type="text" class="form-control" placeholder="Arrêté de prescription" maxlength="256">
			<label for="form_arrete_prescription">Arrêté de prescription</label>
		</div>
	</div>
</div>

<!-- Responsable d'opération -->
<div class="row my-2">
	<div class="col-md-6">
		<div class="form-floating">
			<input name="responsable" id="form_responsable" value="<?= $operation->getResponsable() ?>"
				type="text" class="form-control" placeholder="Responsable de l'opération" maxlength="256" autocomplete="off">
			<label for="form_responsable">Responsable de l'opération</label>
		</div>
	</div>
</div>

<!-- Anthropologues -->
<?php
$anthropologues = $operation->getAnthropologues();
if (empty($anthropologues)) $anthropologues[] = "";
?>
<?= View::forge("fonction/multiple_input", array("name" => "anthropologues", "datas" => $anthropologues, "label" => "Anthropologue")); ?>

<!-- Paleopathologistes -->
<?php
$paleos = $operation->getPaleopathologistes();
if (empty($paleos)) $paleos[] = "";
?>
<?= View::forge("fonction/multiple_input", array("name" => "paleopathologistes", "datas" => $paleos, "label" => "Paleopathologiste")); ?>

<!-- Bibliographie -->
<div class="col-md-12">
	<label for="form_bibliographie">Bibliographie</label>
	<textarea name="bibliographie" id="form_bibliographie"
		class="form-control" maxlength="65535"><?= $operation->getBibliographie() ?></textarea>
</div>

<!-- Comptes -->
<h3 class="text-center mt-4">Comptes autorisés</h3>

<?php
$accounts = $operation->getAccounts();
if (empty($accounts)) $accounts[""] = new Compte(array());
$logins = array();
foreach ($accounts as $acc) {
	$logins[] = $acc->getLogin();
}
?>
<?=
View::forge("fonction/multiple_input", array(
	"name" => "compte",
	"datas" => $logins,
	"label" => "Nom du compte",
	"autocompletion" => "compte"
));
?>

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