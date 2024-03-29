<?php

use Fuel\Core\Asset;
use Fuel\Core\View;
use Model\Db\Typeoperation;
use Model\Helper;

/** @var array Valeurs entré pour la recherche précédente. */
$options = $options;

/** Array des attributs les plus communs. */
$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "");

?>

<!-- Id operation -->
<div class="row my-2">
	<div class="col-md-12">
		<div class="form-floating">
			<input name="id_operation" id="form_id_operation" value="<?= Helper::arrayGetValue("id_operation", $options) ?>" type="number" class="form-control"
				placeholder="" maxlength="256" title="Indiquez l'identifiant de l'opération">
			<label for="form_id_operation">Numéro</label>
		</div>
	</div>
</div>

<div class="row my-2">

	<div class="col-md-6 mt-4">

		<div class="row mb-4 mt-2">
			<!-- Departement -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="departement" id="form_departement" value="<?= Helper::arrayGetValue("departement", $options) ?>" type="text" class="form-control"
						placeholder="Département" autocomplete="chrome-caca" title="Indiquez le département de l'opération" oninput="FormOperation.checkDepartementExist(true)">
					<div class="form-msg-error">Le département n'existe pas.</div>
					<label for="form_departement">Nom du département</label>
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
					<input name="commune" id="form_commune" value="<?= Helper::arrayGetValue("commune", $options) ?>" type="text" class="form-control"
						placeholder="Commune" autocomplete="caca-chrome" title="Indiquez la commune de l'opération" oninput="FormOperation.checkCommuneExist(true)">
					<div class="form-msg-error">La commune n'existe pas.</div>
					<label for="form_commune">Commune</label>
					<script>
						addAutocompleteCommune();
					</script>
				</div>
			</div>
		</div>

		<!-- INSEE -->
		<div class="row my-4">
			<div class="col md-12">
				<div class="form-floating">
					<input name="insee" id="form_insee" value="<?= Helper::arrayGetValue("insee", $options) ?>" type="text" class="form-control"
						placeholder="INSEE" maxlength="5" title="Indiquez le numéro INSEE de la commune">
					<label for="form_insee">Numéro INSEE</label>
				</div>
			</div>
		</div>

		<!-- Adresse -->
		<div class="row my-4">
			<div class="col md-12">
				<div class="form-floating">
					<input name="adresse" id="form_adresse" value="<?= Helper::arrayGetValue("adresse", $options) ?>" type="search" class="form-control"
						placeholder="Adresse" autocomplete="utilisez-firefox-ou-autre-mais-pas-chrome" maxlength="256" title="Indiquez l'adresse de l'opération">
					<label for="form_adresse">Adresse ou nom du site</label>
				</div>
			</div>
		</div>

		<div class="row my-2">
			<p class="text-muted">
				Si un seul des trois champs de positionnement suivants manque, aucun des trois champs ne sera pris en compte.
			</p>
			<!-- Longitude -->
			<div class="col-md-4">
				<div class="form-floating">
					<input name="longitude" id="form_longitude" value="<?= Helper::arrayGetValue("longitude", $options) ?>" type="number" class="form-control" placeholder="Longitude"
						min="-180" max="180" step="any" title="Indiquez la position GPS horizontale" oninput="FormOperation.updateCoordinate()">
					<div class="form-msg-error">La valeur doit être un nombre entre -180 et 180</div>
					<label for="form_longitude">Longitude</label>
				</div>
			</div>

			<!-- Latitude -->
			<div class="col-md-4">
				<div class="form-floating">
					<input name="latitude" id="form_latitude" value="<?= Helper::arrayGetValue("latitude", $options) ?>" type="number" class="form-control" placeholder="Latitude"
						min="-90" max="90" step="any" title="Indiquez la position GPS verticale" oninput="FormOperation.updateCoordinate()">
					<div class="form-msg-error">La valeur doit être un nombre entre -90 et 90</div>
					<label for="form_latitude">Latitude</label>
				</div>
			</div>

			<!-- Rayon -->
			<div class="col-md-4">
				<div class="form-floating">
					<input name="radius" id="form_radius" type="number" class="form-control" value="<?= Helper::arrayGetValue("radius", $options, 100) ?>" placeholder="Latitude"
						min="0" step="any" title="Indiquez le rayon de recherche" oninput="FormOperation.updateCoordinate()">
					<div class="form-msg-error">La valeur doit être supérieur à 0</div>
					<label for="form_radius">Rayon (km)</label>
				</div>
			</div>
		</div>
	</div>

	<!-- Carte -->
	<div class="col-md-6">
		<p style="text-align: center; margin-bottom: 6px;">
			Sélectionner une position sur la carte pour récupérer les coordonnées.
		</p>
		<div id="map" style="height: 350px"></div>
		<script>
			FormOperation.prepareMap(true);
		</script>
	</div>
</div>

<div class="row my-4">
	<!-- Année -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="annee_min" id="form_annee_min" value="<?= Helper::arrayGetValue("annee_min", $options) ?>" type="number" class="form-control" placeholder="Année de l'opération" min="1800" max="<?= date("Y") ?>" title="Mettez l'année minimum de l'opération">
			<div class="form-msg-error">La valeur doit être un nombre entre 1800 et <?= date("Y") ?></div>
			<label for="form_annee">Année minimal</label>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-floating">
			<input name="annee_max" id="form_annee_max" value="<?= Helper::arrayGetValue("annee_max", $options) ?>" type="number" class="form-control" placeholder="Année de l'opération" min="1800" max="<?= date("Y") ?>" title="Mettez l'année maximum de l'opération">
			<div class="form-msg-error">La valeur doit être un nombre entre 1800 et <?= date("Y") ?></div>
			<label for="form_annee">Année maximal</label>
		</div>
	</div>

	<!-- Organisme -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="organisme" id="form_organisme" value="<?= Helper::arrayGetValue("organisme", $options) ?>" class="form-control" placeholder="Organisme" title="Entrez l'organisme attaché à l'opération" oninput="FormOperation.checkOrganismeExist()">
			<div class="form-msg-error">
				L'organisme n'existe pas.
			</div>
			<label for="form_organimse">Organisme</label>
			<script>
				addAutocomplete("form_organisme", "nom", "organisme", [
					["nom", "LIKE", "?%"]
				]);
			</script>
		</div>
	</div>

	<!-- Type de l'opération -->
	<div class="col-md-3">
		<div class="form-floating">
			<select name="id_type_op" id="form_id_type_op" class="form-select" title="Sélectionner le type de l'opération">
				<?= Typeoperation::fetchOptions(Helper::arrayGetValue("id_type_op", $options, ""), "Tous"); ?>
			</select>
			<label for="form_id_type_op">Type d'opération</label>
		</div>
	</div>
</div>

<?php if (false) : ?>

<div class="row my-2">
	<!-- EA -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="ea" id="form_ea" value="<?= Helper::arrayGetValue("ea", $options) ?>" type="text" class="form-control" placeholder="ea" maxlength="256" title="Indiquez le numéro de l'entité archéologique">
			<label for="form_ea">EA</label>
		</div>
	</div>

	<!-- OA -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="oa" id="form_oa" value="<?= Helper::arrayGetValue("oa", $options) ?>" type="text" class="form-control" placeholder="oa" maxlength="256" title="Indiquez le numéro d'opération archéologique">
			<label for="form_oa">OA</label>
		</div>
	</div>

	<!-- Numéro de l'opération -->
	<div class="col-md-4">
		<div class="form-floating">
			<input name="numero_operation" id="form_numero_operation" value="<?= Helper::arrayGetValue("numero_operation", $options) ?>" type="text" class="form-control" placeholder="Numéro d'opération" maxlength="256" title="Indiquez le numéro de l'opération (propre à l'opérateur)">
			<label for="form_numero_operation">Numéro de l'opération</label>
		</div>
	</div>

</div>
<div class="row my-2">

	<!-- Patriarche -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="patriarche" id="form_patriarche" value="<?= Helper::arrayGetValue("patriarche", $options) ?>" type="text" class="form-control" placeholder="Patriarche" maxlength="256" title="Indiquez le patriarche de l'opération">
			<label for="form_patriarche">Patriarche</label>
		</div>
	</div>

	<!-- Arrêté de prescription -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="arrete_prescription" id="form_arrete_prescription" value="<?= Helper::arrayGetValue("arrete_prescription", $options) ?>" type="text" class="form-control" placeholder="Arrêté de prescription" maxlength="256" title="Indiquez le numéro de l'arrêté de prescription">
			<label for="form_arrete_prescription">Arrêté de prescription</label>
		</div>
	</div>
</div>

<!-- Responsable d'opération -->
<div class="row my-2">
	<div class="col-md-6">
		<div class="form-floating">
			<input name="responsable" id="form_responsable" value="<?= Helper::arrayGetValue("responsable", $options) ?>" type="text" class="form-control" placeholder="Responsable de l'opération" maxlength="256" autocomplete="off" title="Indiquez le responsable de l'opération, de préférence au format Prénom NOM">
			<label for="form_responsable">Responsable de l'opération</label>
		</div>
	</div>
</div>

<!-- Anthropologues -->
<?php
$anthropologues = Helper::arrayGetValue("anthropologues", $options);
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
$paleos = Helper::arrayGetValue("paleopathologistes", $options);
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
<?php endif ?>

<br />
