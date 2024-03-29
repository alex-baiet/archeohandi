<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Model\Db\Appareil;
use Model\Db\Chronology;
use Model\Db\Diagnostic;
use Model\Db\Localisation;
use Model\Db\Pathology;
use Model\Db\Sex;
use Model\Db\Subjectdiagnosis;
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
			<input name="date_min" id="form_date_min" value="<?= Helper::arrayGetValue("date_min", $options) ?>" type="number" class="form-control" placeholder="Datation minimale" min="-20000" max="1945" step="1" title="Indiquez la borne inférieure de datation du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre -20000 et 1945</div>
			<label for="form_date_min">Datation minimale</label>
		</div>
	</div>

	<!-- Période maximum estimé -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="date_max" id="form_date_max" value="<?= Helper::arrayGetValue("date_max", $options) ?>" type="number" class="form-control" placeholder="Datation maximal" min="-20000" max="1945" step="1" title="Indiquez la borne supérieure de datation du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre -20000 et 1945</div>
			<label for="form_date_max">Datation maximale</label>
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

<h3>Atteinte invalidante</h3>
<p class="text-muted">
	Sélectionner un diagnostique sans indiquer la localisation permet de rechercher les sujets touchés, quel que soit la localisation du diagnostique.
</p>

<div class="table-scroll" style="max-height: none; max-width: 600px">
	<table class="table table-bordered table-no-padding table-diagnostic">
		<!-- Tous les titres -->
		<?php
		$localisations = Localisation::fetchAll();
		$appareils = Appareil::fetchAll();
		//$countSubject = count($subject->getGroup()->getOperation()->getSubjects());
		?>
		<thead>
			<tr>
				<td style="width: 300px;"></td>
				<?php $i = 0;
				foreach ($localisations as $locali) : ?>
					<td style="vertical-align: bottom;">
						<?php if (strpos($locali->getUrlImg(), "right") !== false) : ?>
							<div style="text-align: center;">D</div>
						<?php elseif (strpos($locali->getUrlImg(), "left") !== false) : ?>
							<div style="text-align: center;">G</div>
						<?php endif; ?>
						<?= Asset::img($locali->getUrlImg(), array("style" => "width: 50 px; height: 25px; margin-right: 5px; margin-left: 5px;", "alt" => $locali->getNom())); ?>
					</td>
				<?php $i++;
				endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach (Diagnostic::fetchAll() as $diagnostic) : ?>
				<?php
				$hasDiagnosis = isset($options["diagnostics"][$diagnostic->getId()]);
				?>
				<tr>
					<!-- Titre des diagnostics -->
					<th>
						<div class="form-check form-switch">
							<label id="form_diagnostic_label_<?= $diagnostic->getId(); ?>" for="form_diagnostic_<?= $diagnostic->getId() ?>" class="form-check-label"><?= $diagnostic->getNom() ?></label>
							<input name="diagnostic_<?= $diagnostic->getId() ?>" id="form_diagnostic_<?= $diagnostic->getId() ?>" type="checkbox" class="form-check-input" <?php if ($hasDiagnosis) : ?>checked<?php endif; ?>>
						</div>
					</th>
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

							// Maj affichage du checkbox de la localisation si le diagnostic est coché par défaut
							if ($hasDiagnosis) {
								$spotIds = $options["diagnostics"][$diagnostic->getId()];
								$spots = array();
								foreach ($spotIds as $value) {
									$spots[] = Localisation::fetchSingle($value);
								}
								$subjectDia = new Subjectdiagnosis($diagnostic, $spots);
								if ($subjectDia->isLocatedFromId($locali->getId())) $checked = true;
								if (
									!$diagnostic->isLocated($locali->getId())
									|| $diagnostic->isSpotMandatory($locali->getId())
								) {
									$disabled = true;
								}
							} else {
								$disabled = true;
							}
							?>
							<input name="diagnostics[<?= $diagnostic->getId() ?>][]" id="form_diagnostic_<?= $diagnostic->getId() ?>" value="<?= $locali->getId() ?>" type="checkbox" class="form-check-input <?= $classes ?>" <?= $hidden ? "hidden" : null ?> <?= $disabled ? "disabled" : null ?> <?= $checked ? "checked" : null ?>>
						</td>
					<?php endforeach; ?>

					<script>
						updateCheckboxOnSwitch(<?= $diagnostic->getId(); ?>);
					</script>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<!-- Pathologies -->
<?php foreach (Pathology::fetchAll() as $pathology) : ?>
	<div class="form-check form-switch">
		<?php
		$checked = "";
		if (isset($options["pathologies"]) && in_array($pathology->getId(), $options["pathologies"])) $checked = " checked";
		?>
		<label id="form_pathologies_label_<?= $pathology->getId() ?>" for="form_pathologies_<?= $pathology->getId() ?>" class="form-check-label"><?= $pathology->getName() ?></label>
		<input name="pathologies[]" id="form_pathologies_<?= $pathology->getId() ?>" value="<?= $pathology->getId() ?>" type="checkbox" class="form-check-input"<?= $checked ?>>
	</div>
<?php endforeach; ?>
