<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Model\Db\Appareil;
use Model\Db\Chronology;
use Model\Db\Diagnostic;
use Model\Db\Localisation;
use Model\Db\Mobilier;
use Model\Db\Pathology;
use Model\Db\Sex;
use Model\Db\Typedepot;
use Model\Db\Typesepulture;
use Model\Helper;

/** @var array Valeurs entré pour la recherche précédente. */
$options = $options;

?>

<div class="row my-4">
	<!-- Id -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="id_sujet" id="form_id_sujet" value="<?= Helper::arrayGetValue("id_sujet", $options) ?>" type="number" class="form-control" placeholder="" maxlength="256" title="Indiquez l'identifiant du sujet">
			<label for="form_id_sujet">Numéro</label>
		</div>
	</div>

	<!-- Nom -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="id_sujet_handicape" id="form_id_sujet_handicape" value="<?= Helper::arrayGetValue("id_sujet_handicape", $options) ?>" type="text" class="form-control" placeholder="" maxlength="256" title="Indiquez le nom du sujet">
			<label for="form_id_sujet_handicape">Nom du sujet</label>
		</div>
	</div>
</div>

<div class="row my-4">
	<!-- Chronologie -->
	<div class="col-md-6">
		<div class="form-floating">
			<select name="id_chronologie" id="form_id_chronologie" class="form-select" required title="Indiquer la phase chronologique à laquelle appartient le sujet handicapé">
				<?= Chronology::fetchOptions(Helper::arrayGetValue("id_chronologie", $options, ""), "Tous") ?>
			</select>
			<label for="form_id_chronologie">Chronologie</label>
		</div>
	</div>

	<!-- Sexe -->
	<div class="col-md-6">
		<div class="form-floating">
			<select name="sexe" id="form_sexe" class="form-select">
				<?= Sex::fetchOptions("", "Tous") ?>
			</select>
			<label for="form_sexe">Sexe</label>
		</div>
	</div>
</div>

<div class="row my-4">
	<!-- Âge minimum estimé de décès -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="age_min" id="form_age_min" value="<?= Helper::arrayGetValue("age_min", $options) ?>" type="number" class="form-control" placeholder="Âge minimum au décès" min="0" max="130" step="1" title="Indiquez l'âge minimum estimé du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre 0 et 130</div>
			<label for="form_age_min">Âge minimum au décès</label>
		</div>
	</div>

	<!-- Âge maximum estimé de décès -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="age_max" id="form_age_max" value="<?= Helper::arrayGetValue("age_max", $options) ?>" type="number" class="form-control" placeholder="Âge maximum au décès" min="0" max="130" step="1" title="Indiquez l'âge maximum estimé du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre 0 et 130</div>
			<label for="form_age_max">Âge maximum au décès</label>
		</div>
	</div>

	<!-- Période minimum estimé -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="dating_min" id="form_dating_min" value="<?= Helper::arrayGetValue("dating_min", $options) ?>" type="number" class="form-control" placeholder="Datation minimale" min="-20000" max="1945" step="1" title="Indiquez la borne inférieure de datation du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre -20000 et 1945</div>
			<label for="form_dating_min">Datation minimale</label>
		</div>
	</div>

	<!-- Période maximum estimé -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="dating_max" id="form_dating_max" value="<?= Helper::arrayGetValue("dating_max", $options) ?>" type="number" class="form-control" placeholder="Datation maximal" min="-20000" max="1945" step="1" title="Indiquez la borne supérieure de datation du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre -20000 et 1945</div>
			<label for="form_dating_max">Datation maximale</label>
		</div>
	</div>

</div>

<div class="row my-3">
	<!-- Type de dépôt -->
	<div class="col-md-4">
		<div class="form-floating">
			<select name="id_type_depot" id="form_id_type_depot" class="form-select" title="Indiquez la modalité du dépôt">
				<?= Typedepot::fetchOptions(Helper::arrayGetValue("id_type_depot", $options, ""), "Tous") ?>
			</select>
			<label for="form_id_type_depot">Type de dépôt</label>
		</div>
	</div>

	<!-- Type de sepulture -->
	<div class="col-md-4">
		<div class="form-floating">
			<select name="id_sepulture" id="form_id_sepulture" class="form-select" title="Indiquez le type de la sépulture">
				<?= Typesepulture::fetchOptions(Helper::arrayGetValue("id_sepulture", $options, ""), "Tous") ?>
			</select>
			<label for="form_id_sepulture">Type de sépulture</label>
		</div>
	</div>

	<!-- Contexte normatif -->
	<div class="col-md-4">
		<div class="form-floating">
			<?=
			Form::select(
				"contexte_normatif",
				Helper::arrayGetValue("contexte_normatif", $options, ""),
				array(
					"" => "Tous",
					"Standard" => "Standard",
					"Atypique" => "Atypique"
				),
				array(
					"class" => "form-select",
					"title" => "Indiquer le type de contexte"
				)
			);
			?>
			<label for="form_contexte_normatif">Contexte normatif</label>
		</div>
	</div>
</div>

<div class="row g-2">
	<!-- Milieu de vie -->
	<div class="col-md-6">
		<div class="form-floating">
			<?=
			Form::select(
				"milieu_vie",
				Helper::arrayGetValue("milieu_vie", $options, ""),
				array(
					"" => "Tous",
					"Rural" => "Rural",
					"Urbain" => "Urbain"
				),
				array(
					"class" => "form-select",
					"title" => "Indiquez le milieu de vie de l'occupant"
				)
			);
			?>
			<label for="form_milieu_vie">Milieu de vie</label>
		</div>
	</div>

	<!-- Contexte -->
	<div class="col-md-6">
		<div class="form-floating">
			<?= Form::select(
				"contexte",
				Helper::arrayGetValue("contexte", $options, ""),
				array(
					"" => "Tous",
					"Funeraire" => "Funéraire",
					"Domestique" => "Domestique",
					"Autre" => "Autre"
				),
				array(
					"class" => "form-select",
					"title" => "Indiquez le contexte de l'occupation"
				)
			);
			?>
			<label for="form_contexte">Contexte de la tombe</label>
		</div>
	</div>
</div>

<br />

<div class="row">
	<div class="col-md-6">
		<h3>Atteinte invalidante</h3>
		<p class="text-muted">
			Sélectionner un diagnostique sans indiquer la localisation permet de rechercher les sujets touchés, quel que soit la localisation du diagnostique.
		</p>

		<table>
			<!-- Tous les titres -->
			<?php
			$localisations = Localisation::fetchAll();
			$appareils = Appareil::fetchAll();
			?>
			<thead>
				<tr>
					<td style="width: 300px;"></td>
					<?php $i = 0;
					foreach ($localisations as $locali) : ?>
						<td><?= Asset::img($locali->getUrlImg(), array("style" => "width: 50 px; height: 25px; margin-right: 10px;", "alt" => $locali->getNom())); ?></td>
					<?php $i++;
					endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach (Diagnostic::fetchAll() as $diagnostic) : ?>
					<tr>
						<!-- Titre des diagnostics -->
						<td>
							<div class="form-check form-switch">
								<label id="form_diagnostic_label_<?= $diagnostic->getId(); ?>" for="form_diagnostic_<?= $diagnostic->getId() ?>" class="form-check-label"><?= $diagnostic->getNom() ?></label>
								<input name="diagnostic_<?= $diagnostic->getId() ?>" id="form_diagnostic_<?= $diagnostic->getId() ?>" type="checkbox" class="form-check-input">

							</div>
						</td>
						<!-- Checkbox des zones atteintes -->
						<?php foreach ($localisations as $locali) : ?>
							<td>
								<?php
								$classes = "form-check-input";
								$hidden = false;
								$disabled = false;
								$checked = false;
								// Ajout des classes pour que la fonction js sache quoi faire sur le checkbox
								if ($diagnostic->isSpotMandatory($locali->getId())) $classes .= " always-disabled auto-check";
								if (!$diagnostic->isLocated($locali->getId())) {
									$classes .= " always-disabled";
									$hidden = true;
								}
								?>
								<input name="diagnostics[<?= $diagnostic->getId() ?>][]" id="form_diagnostic_<?= $diagnostic->getId() ?>" value="<?= $locali->getId() ?>" type="checkbox" class="form-check-input <?= $classes ?>" <?= $hidden ? "hidden" : null ?> disabled <?= $checked ? "checked" : null ?>>
							</td>
						<?php endforeach; ?>
						<script>
							updateCheckboxOnSwitch(<?= $diagnostic->getId(); ?>);
						</script>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<br />
		<!-- Pathologies -->
		<?php foreach (Pathology::fetchAll() as $pathology) : ?>
			<div class="form-check form-switch">
				<label id="form_pathologies_label_<?= $pathology->getId() ?>" for="form_pathologies_<?= $pathology->getId() ?>" class="form-check-label"><?= $pathology->getName() ?></label>
				<input name="pathologies[]" id="form_pathologies_<?= $pathology->getId() ?>" value="<?= $pathology->getId() ?>" type="checkbox" class="form-check-input">
			</div>
		<?php endforeach; ?>

	</div>

	<div class="col-md-6">
	<?php if (false) : ?>
		<!-- Dépôt -->
		<h3>Dépôt</h3>
		<!-- Numéro de dépôt -->
		<div class="form-floating my-2">
			<input name="num_inventaire" id="form_num_inventaire" value="<?= Helper::arrayGetValue("num_inventaire", $options) ?>" type="text" class="form-control" placeholder="Numéro de dépôt" maxlength="256" title="Indiquez le numéro du dépôt">
			<label for="form_num_inventaire">Numéro de dépôt</label>
		</div>
		<!-- Commune du dépôt -->
		<div class="form-floating my-2">
			<input name="depot_commune" id="form_depot_commune" value="<?= Helper::arrayGetValue("depot_commune", $options) ?>" type="text" class="form-control" placeholder="Commune" maxlength="256" autocomplete="off" title="Indiquez la commune du dépôt du sujet">
			<label for="form_depot_commune">Commune</label>
		</div>
		<script>
			addAutocompleteOld("form_depot_commune", "commune");
		</script>
		<!-- Adresse du dépôt -->
		<div class="form-floating my-2">
			<input name="depot_adresse" id="form_depot_adresse" value="<?= Helper::arrayGetValue("depot_adresse", $options) ?>" type="text" class="form-control" placeholder="Adresse du dépôt" maxlength="256" title="Indiquez l'adresse du dépôt du sujet">
			<label for="form_depot_adresse">Adresse du dépôt</label>
		</div>

		<!-- Accessoires -->
		<h3 class="mt-4">Accessoire</h3>
		<?php foreach (Mobilier::fetchAll() as $mobilier) : ?>
			<div class="form-check form-switch">
				<label for="form_id_mobiliers_<?= $mobilier->getId() ?>" class="form-check-label"><?= $mobilier->getNom() ?></label>
				<input name="id_mobiliers[]" id="form_id_mobiliers_<?= $mobilier->getId() ?>" value="<?= $mobilier->getId() ?>" type="checkbox" class="form-check-input">
			</div>
		<?php endforeach; ?>
		<div>
			<label class="form-check-label" for="form_description_mobilier">Description du mobilier</label>
			<textarea class="form-control" name="description_mobilier" id="form_description_mobilier" rows="2"></textarea>
		</div>

		<!-- Appareils de compensation -->
		<h3 class="mt-4">Appareil compensatoire</h3>
		<?php foreach (Appareil::fetchAll() as $item) : ?>
			<div class="form-check form-switch">
				<label for="form_appareils_<?= $item->getId() ?>" class="form-check-label"><?= $item->getName() ?></label>
				<input name="appareils[]" id="form_appareils_<?= $item->getId() ?>" value="<?= $item->getId() ?>" type="checkbox" class="form-check-input">
			</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>

</div>
<br />

<?php if (false) : ?>
<!-- Commentaire du diagnostic -->
<label for="comment_diagnostic">Commentaire du diagnostic</label>
<div class="input-group">
	<textarea class="form-control" name="comment_diagnostic" value="<?= Helper::arrayGetValue("comment_diagnostic", $options) ?>" rows="2" maxlength="65535" title="Ecrivez ici des commentaires sur le diagnostic si besoin"></textarea>
</div>
<?php endif ?>
