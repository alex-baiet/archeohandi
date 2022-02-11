<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Typeoperation;

/** @var string Page de destination lors de la validation du formulaire. */
$action = $action;

$showError = isset($operation);
/** @var Operation Operation sur lequel construire le formulaire. */
$operation = isset($operation) ? $operation : new Operation(array());

/** @var bool */
$displayOnlyFields = isset($displayOnlyFields) ? $displayOnlyFields : false;

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
<div class="row my-4">

	<?php $commune = $operation->getCommune(); ?>
	<!-- Departement -->
	<script>
		/** Permet de vérifier que le département existe. */
		function checkDepartementExist() {
			/** @type {HTMLButtonElement} */
			const field = document.getElementById("form_departement");
			checkValueExist(
				"commune",
				[["departement", "=", field.value]],
				() => {
					field.setCustomValidity("");
					document.getElementById("form_commune").oninput();
				},
				() => { field.setCustomValidity("Le departement n'existe pas."); }
			);
		}
	</script>
	<div class="col-md-4">
		<div class="form-floating">
			<input name="departement" id="form_departement" value="<?= $commune !== null ? $commune->getDepartement() : null ?>"
				type="text" class="form-control" placeholder="Département" autocomplete="off"
				title="Indiquez le département de l'opération"
				oninput="checkDepartementExist()">
			<div class="form-msg-error">Le département n'existe pas.</div>
			<label for="form_departement">Nom du département</label>
			<script>addAutocomplete(`form_departement`, `DISTINCT departement`, `commune`, [[`departement`, `LIKE`, `?%`]])</script>
		</div>
	</div>

	<!-- Commune -->
	<?php $nameCom = "commune" ?>
	<script>
		/** Permet de vérifier que le département existe. */
		function checkCommuneExist() {
			/** @type {HTMLButtonElement} */
			const fieldCom = document.getElementById("form_<?= $nameCom ?>");
			/** @type {HTMLButtonElement} */
			const fieldDep = document.getElementById("form_departement");
			
			let where = [["nom", "=", fieldCom.value]];
			if (fieldDep.value != "" && fieldDep.validity.valid) where.push(["departement", "=", fieldDep.value]);
			
			checkValueExist(
				"commune",
				where,
				() => { fieldCom.setCustomValidity(""); },
				() => { fieldCom.setCustomValidity("Le departement n'existe pas."); }
			);
		}
	</script>
	<div class="col-md-4">
		<div class="form-floating">
			<input name="<?= $nameCom ?>" id="form_<?= $nameCom ?>" value="<?= $commune !== null ? $commune->getNom() : null ?>"
				type="text" class="form-control" placeholder="Commune" autocomplete="chrome-off"
				title="Indiquez la commune de l'opération"
				oninput="checkCommuneExist()">
			<div class="form-msg-error">La commune n'existe pas.</div>
			<label for="form_<?= $nameCom ?>">Commune</label>
			<script>addAutocompleteCommune();</script>
		</div>
	</div>

	<!-- Adresse -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="adresse" id="form_adresse" value="<?= $operation->getAdresse() ?>"
				type="text" class="form-control" placeholder="Adresse" maxlength="256"
				title="Indiquez l'adresse de l'opération">
			<label for="form_adresse">Adresse ou nom du site</label>
		</div>
	</div>

</div>

<!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3121224.189160384!2d2.166198738935703!3d47.108438324355085!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfr!2sfr!4v1644502368364!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
<a href="https://www.google.fr/maps" target="_blank">Rechercher une position depuis Google Map</a>
<div class="row my-2">

	<!-- Longitude -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="X" id="form_X" value="<?= $operation->getX() ?>"
				type="number" class="form-control" placeholder="Longitude" min="-180" max="180" step="any" required
				title="Indiquez la position GPS horizontale">
			<div class="form-msg-error">La valeur doit être un nombre entre -180 et 180</div>
			<label for="form_X">Longitude</label>
		</div>
	</div>

	<!-- Latitude -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="Y" id="form_Y" value="<?= $operation->getY() ?>"
				type="number" class="form-control" placeholder="Latitude" min="-90" max="90" step="any" required
				title="Indiquez la position GPS verticale">
			<div class="form-msg-error">La valeur doit être un nombre entre -90 et 90</div>
			<label for="form_Y">Latitude</label>
		</div>
	</div>

</div>

<div class="row my-4">

	<!-- Année -->
	<div class="col-md-4">
		<div class="form-floating">
			<?php
			$opYear = $operation->getAnnee();
			$year = $opYear < 1800 || $opYear === null ? null : $operation->getAnnee();
			?>
			<input name="annee" id="form_annee" value="<?= $year ?>"
				type="number" class="form-control" placeholder="Année de l'opération" min="1800" max="<?= date("Y") ?>"
				title="Mettez l'année de l'opération, ou la dernière année si l'opération s'est déroulé sur plusieurs année">
			<div class="form-msg-error">La valeur doit être un nombre entre 1800 et <?= date("Y") ?></div>
			<label for="form_annee">Année de l'opération</label>
		</div>
	</div>

	<!-- Organisme -->
	<script>
		/** Met à jour l'affichage de l'input de l'organisme en fonction de si il existe dans la BDD. */
		function checkOrganismeExist() {
			const input = document.getElementById("form_organisme");
			checkValueExist("organisme", [["nom", "=", input.value]], 
				() => { input.setCustomValidity(""); },
				() => { input.setCustomValidity("L'organisme n'existe pas."); }
			);
		}

		/** Ajoute un organisme à la BDD. */
		function addOrganisme() {
			const input = document.getElementById("form_organisme");
			$.ajax({
				type: "POST",
				url: "https://archeohandi.huma-num.fr/public/fonction/add_organisme",
				data: { name: input.value },
				success: function (response) {
					input.setCustomValidity("");
				}
			});
		}
	</script>
	<div class="col-md-4">
		<div class="form-floating">
			<input name="organisme" id="form_organisme" class="form-control" placeholder="Organisme"
				title="Entrez l'organisme attaché à l'opération" value="<?= $operation->getOrganisme()->getNom() ?>"
				oninput="checkOrganismeExist()">
				<div class="form-msg-error">
					L'organisme n'existe pas. <a class="link-primary" style="cursor: pointer;" onclick="addOrganisme()">Ajouter l'organisme</a>
				</div>
			<label for="form_organimse">Organisme</label>
			<script>addAutocomplete("form_organisme", "nom", "organisme", [["nom", "LIKE", "?%"]]);</script>
		</div>
	</div>

	<!-- Type de l'opération -->
	<div class="col-md-4">
		<div class="form-floating">
			<select name="id_type_op" id="form_id_type_op" class="form-select"
				title="Sélectionner le type de l'opération">
				<?= Typeoperation::fetchOptions($operation->getIdTypeOp()); ?>
			</select>
			<label for="form_id_type_op">Type d'opération</label>
		</div>
	</div>
</div>

<div class="row my-2">
	<!-- EA -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="EA" id="form_EA" value="<?= $operation->getEA() ?>"
				type="text" class="form-control" placeholder="EA" maxlength="256"
				title="Indiquez le numéro de l'entité archéologique">
			<label for="form_EA">EA</label>
		</div>
	</div>

	<!-- OA -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="OA" id="form_OA" value="<?= $operation->getOA() ?>"
				type="text" class="form-control" placeholder="OA" maxlength="256"
				title="Indiquez le numéro d'opération archéologique">
			<label for="form_OA">OA</label>
		</div>
	</div>

	<!-- Numéro de l'opération -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="numero_operation" id="form_numero_operation" value="<?= $operation->getNumeroOperation() ?>"
				type="text" class="form-control" placeholder="Numéro d'opération" maxlength="256"
				title="Indiquez le numéro de l'opération (propre à l'opérateur)">
			<label for="form_numero_operation">Numéro de l'opération</label>
		</div>
	</div>

</div>
<div class="row my-2">

	<!-- Patriarche -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="patriarche" id="form_patriarche" value="<?= $operation->getPatriarche() ?>"
				type="text" class="form-control" placeholder="Patriarche" maxlength="256"
				title="Indiquez le patriarche de l'opération">
			<label for="form_patriarche">Patriarche</label>
		</div>
	</div>

	<!-- Arrêté de prescription -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="arrete_prescription" id="form_arrete_prescription" value="<?= $operation->getArretePrescription() ?>"
				type="text" class="form-control" placeholder="Arrêté de prescription" maxlength="256"
				title="Indiquez le numéro de l'arrêté de prescription">
			<label for="form_arrete_prescription">Arrêté de prescription</label>
		</div>
	</div>
</div>

<!-- Responsable d'opération -->
<div class="row my-2">
	<div class="col-md-6">
		<div class="form-floating">
			<input name="responsable" id="form_responsable" value="<?= $operation->getResponsable() ?>"
				type="text" class="form-control" placeholder="Responsable de l'opération" maxlength="256" autocomplete="off"
				title="Indiquez le responsable de l'opération, de préférence au format Prénom NOM">
			<label for="form_responsable">Responsable de l'opération</label>
		</div>
	</div>
</div>

<!-- Anthropologues -->
<?php
$anthropologues = $operation->getAnthropologues();
if (empty($anthropologues)) $anthropologues[] = "";
?>
<?=
View::forge("fonction/multiple_input", array(
	"name" => "anthropologues",
	"datas" => $anthropologues,
	"label" => "Anthropologue",
	"inputAttributes" => array(
		"maxlength" => "256",
		"title" => "Indiquez le nom de l'anthropologue au format Prénom NOM"
	)
));
?>

<!-- Paleopathologistes -->
<?php
$paleos = $operation->getPaleopathologistes();
if (empty($paleos)) $paleos[] = "";
?>
<?=
View::forge("fonction/multiple_input", array(
	"name" => "paleopathologistes",
	"datas" => $paleos,
	"label" => "Paleopathologiste",
	"inputAttributes" => array(
		"maxlength" => "256",
		"title" => "Indiquez le nom du paléopathologiste au format Prénom NOM"
	)
));
?>

<!-- Bibliographie -->
<div class="col-md-12">
	<label for="form_bibliographie">
		Bibliographie
		(<a href="https://gallia.cnrs.fr/guide-auteurs/recommandations/" target="_blank">recommandations sur le format de GALLIA</a>)
	</label>
	<textarea name="bibliographie" id="form_bibliographie"
		class="form-control" maxlength="65535"
		title="Indiquez les références bibliographiques où sont mentionnés les détails du cas (selon les normes GALLIA/CNRS)"
		><?= $operation->getBibliographie() ?></textarea>
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
	"autocompletion" => "compte",
	"inputAttributes" => array(
		"maxlength" => "256",
		"title" => "Indiquez le nom d'un compte autorisé à éditer les sujets de l'opération"
	)
));
?>

<br/>

<!-- Confirmation / retour -->
<div class="row">
	<div class="d-md-block col">
		<a class="btn btn-secondary" href="/public/operations" role="button">Retour</a>
	</div>
	
	<div class="d-md-flex justify-content-md-end col">
		<?= Form::submit('submit', 'Modifier', array('class' => 'btn btn-success')); ?>
	</div>
</div>

<?= Form::close(); ?>