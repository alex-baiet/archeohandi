<?php

use Fuel\Core\Asset;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Organisme;
use Model\Db\Typeoperation;

$showError = isset($operation);
/** @var Operation Operation sur lequel construire le formulaire. */
$operation = isset($operation) ? $operation : new Operation(array());
/** @var bool */
$displayOnlyFields = isset($displayOnlyFields) ? $displayOnlyFields : false;
/** @var bool Indique que le formulaire est utilisé pour la recherche ou non. */
$search = isset($search) ? $search : false;

/** Array des attributs les plus communs. */
$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "");

?>

<?php if ($showError) $operation->echoErrors(); ?>

<h3 class="text-center">Opération</h3>

<!-- Affichage des champs -->
<div class="row my-2">
	<div class="col-md-6 mt-4">

		<div class="row mb-4 mt-2">
			<?php $commune = $operation->getCommune(); ?>
			<!-- Departement -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="departement" id="form_departement" value="<?= $commune !== null ? $commune->getDepartement() : null ?>" type="text" class="form-control"
						placeholder="Département" autocomplete="chrome-fait-chier" title="Indiquez le département de l'opération" oninput="FormOperation.checkDepartementExist()">
					<div class="form-msg-error">Le département n'existe pas.</div>
					<label for="form_departement">Nom du département<span class="red">*</span></label>
					<script>
						addAutocomplete(`form_departement`, `DISTINCT departement`, `commune`, [
							[`departement`, `LIKE`, `?%`]
						])
					</script>
				</div>
			</div>

			<!-- Commune -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="commune" id="form_commune" value="<?= $commune !== null ? $commune->getNom() : null ?>" type="text" class="form-control"
						placeholder="Commune" title="Indiquez la commune de l'opération" oninput="FormOperation.checkCommuneExist()" autocomplete="chrome-fait-des-decisions-debiles-avec-l-autocompletion">
					<div class="form-msg-error">La commune n'existe pas.</div>
					<label for="form_commune">Commune<span class="red">*</span></label>
					<script>
						addAutocompleteCommune();
					</script>
				</div>
			</div>
		</div>

		<!-- Adresse -->
		<div class="row my-4">
			<div class="col md-12">
				<div class="form-floating">
					<input name="adresse" id="form_adresse" value="<?= $operation->getAdresse() ?>" type="text" class="form-control"
						placeholder="Adresse" autocomplete="chrome-c-nul-ptn" maxlength="256" title="Indiquez l'adresse de l'opération">
					<label for="form_adresse">Adresse ou nom du site</label>
				</div>
			</div>
		</div>

		<a href="https://twcc.fr" target="_blank" class="btn btn-secondary">Conversion coordonnées GPS <i class="bi bi-box-arrow-up-right"></i></a>

		<div class="row my-2">
			<!-- Longitude -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="longitude" id="form_longitude" value="<?= $operation->getLongitude() ?>" type="number" class="form-control" placeholder="Longitude" min="-180" max="180" step="any" title="Indiquez la position GPS horizontale" oninput="FormOperation.updateCoordinate()">
					<div class="form-msg-error">La valeur doit être un nombre entre -180 et 180</div>
					<label for="form_longitude">Longitude</label>
				</div>
			</div>

			<!-- Latitude -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="latitude" id="form_latitude" value="<?= $operation->getLatitude() ?>" type="number" class="form-control" placeholder="Latitude" min="-90" max="90" step="any" title="Indiquez la position GPS verticale" oninput="FormOperation.updateCoordinate()">
					<div class="form-msg-error">La valeur doit être un nombre entre -90 et 90</div>
					<label for="form_latitude">Latitude</label>
				</div>
			</div>
		</div>
	</div>

	<!-- Carte -->
	<div class="col-md-6">
		<p style="text-align: center; margin-bottom: 6px;">
			Sélectionner une position sur la carte pour récupérer les coordonnées.
		</p>
		<div id="map" style="height: 250px"></div>
		<script>
			FormOperation.prepareMap();
		</script>
	</div>
</div>
<div class="row my-4">

	<!-- Année -->
	<div class="col-md-4">
		<div class="form-floating">
			<?php
			$oplatitudeear = $operation->getAnnee();
			$year = $oplatitudeear < 1800 || $oplatitudeear === null ? null : $operation->getAnnee();
			?>
			<input name="annee" id="form_annee" value="<?= $year ?>" type="number" class="form-control" placeholder="Année de l'opération" min="1800" max="<?= date("Y") ?>" title="Mettez l'année de l'opération, ou la dernière année si l'opération s'est déroulé sur plusieurs année">
			<div class="form-msg-error">La valeur doit être un nombre entre 1800 et <?= date("Y") ?></div>
			<label for="form_annee">Année de l'opération</label>
		</div>
	</div>

	<!-- Organisme -->
	<div class="col-md-4">
		<div class="form-floating">
			<?php
			$org = $operation->getOrganisme() !== null ? $operation->getOrganisme() : Organisme::fetchSingle(-1);
			?>
			<input name="organisme" id="form_organisme" class="form-control" placeholder="Organisme" title="Entrez l'organisme attaché à l'opération" value="<?= $org->getNom() ?>" required oninput="FormOperation.checkOrganismeExist()">
			<div class="form-msg-error">
				L'organisme n'existe pas. <a class="link-primary" style="cursor: pointer;" onclick="FormOperation.addOrganisme()">Ajouter l'organisme</a>
			</div>
			<label for="form_organimse">Organisme</label>
			<script>
				addAutocomplete("form_organisme", "nom", "organisme", [
					["nom", "LIKE", "?%"]
				]);
			</script>
		</div>
	</div>

	<!-- Numéro de l'opération -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="numero_operation" id="form_numero_operation" value="<?= $operation->getNumeroOperation() ?>" type="text" class="form-control" placeholder="Numéro d'opération" maxlength="256" title="Indiquez le numéro de l'opération (propre à l'opérateur)">
			<label for="form_numero_operation">Numéro de l'opération</label>
		</div>
	</div>
</div>

<a href="https://www.culture.gouv.fr/content/download/150076/file/Identifiants-operation-archeologique.pdf" target="_blank">Informations sur les champs EA et OA</a>
<div class="row mb-2">
	<!-- EA -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="ea" id="form_ea" value="<?= $operation->getEA() ?>" type="text" class="form-control" placeholder="ea" maxlength="256" title="Indiquez le numéro de l'entité archéologique">
			<label for="form_ea">EA</label>
		</div>
	</div>

	<!-- OA -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="oa" id="form_oa" value="<?= $operation->getOA() ?>" type="text" class="form-control" placeholder="oa" maxlength="256" title="Indiquez le numéro d'opération archéologique">
			<label for="form_oa">OA</label>
		</div>
	</div>

	<!-- Type de l'opération -->
	<div class="col-md-4">
		<div class="form-floating">
			<select name="id_type_op" id="form_id_type_op" class="form-select" title="Sélectionner le type de l'opération">
				<?php $idType = $operation->getIdTypeOp() !== null ? $operation->getIdTypeOp() : -1; ?>
				<?= Typeoperation::fetchOptions($idType); ?>
			</select>
			<label for="form_id_type_op">Type d'opération</label>
		</div>
	</div>
</div>
<div class="row my-2">

	<!-- Patriarche -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="patriarche" id="form_patriarche" value="<?= $operation->getPatriarche() ?>" type="text" class="form-control" placeholder="Patriarche" maxlength="256" title="Indiquez le patriarche de l'opération">
			<label for="form_patriarche">Patriarche</label>
		</div>
	</div>

	<!-- Arrêté de prescription -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="arrete_prescription" id="form_arrete_prescription" value="<?= $operation->getArretePrescription() ?>" type="text" class="form-control" placeholder="Arrêté de prescription" maxlength="256" title="Indiquez le numéro de l'arrêté de prescription">
			<label for="form_arrete_prescription">Arrêté de prescription</label>
		</div>
	</div>
</div>

<!-- Responsable d'opération -->
<div class="row my-2">
	<div class="col-md-6">
		<div class="form-floating">
			<input name="responsable" id="form_responsable" value="<?= $operation->getResponsable() ?>" type="text" class="form-control" placeholder="Responsable de l'opération" maxlength="256" title="Indiquez le responsable de l'opération, de préférence au format Prénom NOM" autocomplete="off">
			<label for="form_responsable">Responsable de l'opération</label>
			<script>
				addAutocomplete(
					"form_responsable",
					"nom",
					"personne",
					[["nom", "LIKE", "%?%", "or"]]
				);
			</script>
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
	"autocompletion" => array(
		"select" => "nom",
		"table" => "personne",
		"where" => array(
			array("nom", "LIKE", "%?%", "or"),
		)
	),
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
	"autocompletion" => array(
		"select" => "nom",
		"table" => "personne",
		"where" => array(
			array("nom", "LIKE", "%?%", "or"),
		)
	),
	"inputAttributes" => array(
		"maxlength" => "256",
		"title" => "Indiquez le nom du paléopathologiste au format Prénom NOM"
	)
));
?>

<!-- Bibliographie -->
<label for="form_bibliographie">
	Bibliographie
	(<a href="https://gallia.cnrs.fr/guide-auteurs/recommandations/" target="_blank">recommandations sur le format de GALLIA</a>)
</label>
<textarea name="bibliographie" id="form_bibliographie" class="form-control" maxlength="65535" title="Indiquez les références bibliographiques où sont mentionnés les détails du cas (selon les normes GALLIA/CNRS)"><?= $operation->getBibliographie() ?></textarea>

<?=
View::forge("fonction/multiple_input", array(
	"name" => "urls",
	"datas" => $operation->getUrls(),
	"label" => "URL",
	"inputAttributes" => array(
		"maxlength" => "256",
		"title" => "Indiquez un url pour compléter la bibliographie"
	)
));
?>

<!-- Comptes -->
<h3 class="text-center mt-4">Comptes autorisés</h3>
<p class="text-muted">
	Indiquez ici les comptes ayant le droit d'ajouter et modifier les sujets de l'opération.
</p>

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
	"autocompletion" => array(
		"select" => "login",
		"table" => "compte",
		"where" => array(
			array("login", "LIKE", "?%", "or"),
			array("prenom", "LIKE", "?%", "or"),
			array("nom", "LIKE", "?%", "or"),
		)
	),
	"inputAttributes" => array(
		"maxlength" => "256",
		"title" => "Indiquez le nom d'un compte autorisé à modifier les sujets de l'opération"
	)
));
?>

<h3>Iconographie</h3>
<!-- Lien Nakala -->
<div class="row my-2">
	<div class="col-md-auto">
		<a href="https://nakala.fr/u/collections/shared" class="btn btn-primary" target="_blank">Aller sur Nakala</a>
	</div>

	<!-- Bouton résumé à copier -->
	<!-- <div class="col-md-auto">
		<button type="button" class="btn btn-primary" onclick="FormSujet.generateDescription(`fast_summary`)">Générer une résumé à copier</button>
	</div> -->

	<div class="col-md-auto">
		<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#helpPopup">Aide <i class="bi bi-question-circle"></i></button>
	</div>

	<!-- Texte à copier -->
	<!-- <pre id="fast_summary" style="background-color: white; margin: 10px;"></pre> -->
</div>

<!-- Listes URL images -->
<?php
$urls = $operation->getUrlsImg();
if (empty($urls)) $urls[] = "";
?>

<?=
View::forge("fonction/multiple_input", array(
	"name" => "urls_img",
	"datas" => $urls,
	"label" => "Lien URL de l'image",
	"imageInput" => true
));
?>

<input type="hidden" name="complet" value="0">
<input type="checkbox" name="complet" value="1" id="form_complet" class="form-check-input" <?php if ($operation->getComplet()) : ?>checked<?php endif; ?>>
<label for="form_complet">Les informations de l'opération sont complètes. <span class="text-muted">(vous pourrez toujours modifier l'opération)</span></label>

<?= View::forge("global/help_img"); ?>

<br />