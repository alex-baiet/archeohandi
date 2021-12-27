<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Model\Appareil;
use Model\Chronologie;
use Model\Diagnostic;
use Model\Localisation;
use Model\Mobilier;
use Model\Sujethandicape;
use Model\Typedepot;
use Model\Typesepulture;

/** @var int */
$idOperation = $idOperation;
/** @var Sujethandicape Base pour définir les valeurs du formulaires. */
$subject = isset($subject) ? $subject : new Sujethandicape(array());

?>

<div style="background-color: #F5F5F5; padding: 10px;">
	<?= Form::open(array('action' => 'add/sujet/' . $idOperation . '', 'method' => 'POST')); ?>
	<div class="contenu" id="contenu">
		<div class="col-auto">
			<h2 class="text-center">Groupe du sujet</h2>

			<div class="row g-2">
				<?php $group = $subject->getGroup(); ?>
				<!-- NMI -->
				<div class="col-md-6">
					<div class="form-floating">
						<input type="number" class="form-control" name="nmi" placeholder="" value="<?= $group !== null ? $group->getNMI() : "" ?>">
						<label for="NMI">NMI</label>
					</div>
				</div>

				<!-- Chronologie -->
				<div class="col-md-6">
					<?= Chronologie::generateSelect("chronologie", "Chronologie", $group === null ? "" : $group->getChronology()->getNom()) ?>
				</div>
			</div>

			<h3 class="text-center my-2">Sujet handicapé #</h3>
			<div class="row g-2">
				<div class="row g-2">

					<!-- Identifiant -->
					<div class="col-md-6">
						<div class="form-floating">
							<input type="text" class="form-control" name="id_sujet" placeholder="" value="">
							<label for="id_sujet">Identifiant du sujet</label>
						</div>
					</div>

					<!-- Sexe -->
					<div class="col-md-6">
						<div class="form-floating">
							<select class="form-select" name="sexe" aria-label="select_sexe">
								<option value="" selected>Sélectionner</option>
								<option value="Femme">Femme</option>
								<option value="Homme">Homme</option>
								<option value="Indéterminé">Indéterminé</option>
							</select>
							<label for="sexe">Sexe</label>
						</div>
					</div>
				</div>

				<div class="row g-2">
					<!-- Âge minimum estimé de décès -->
					<div class="col-md-6">
						<div class="form-floating">
							<?= Form::input("age_min", null, array("type" => "number", "class" => "form-control", "min" => "0", "max" => "130", "placeholder" => "")); ?>
							<?= Form::label("Âge minimum au décès", "age_min"); ?>
						</div>
					</div>

					<!-- Âge maximum estimé de décès -->
					<div class="col-md-6">
						<div class="form-floating">
							<?= Form::input("age_max", null, array("type" => "number", "class" => "form-control", "min" => "0", "max" => "130", "placeholder" => "")); ?>
							<?= Form::label("Âge maximum au décès", "age_max"); ?>
						</div>
					</div>
				</div>

				<div class="row g-2">
					<!-- Période minimum estimé -->
					<div class="col-md-6">
						<div class="form-floating">
							<?= Form::input("datation_debut", null, array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
							<?= Form::label("Datation début", "datation_debut"); ?>
						</div>
					</div>

					<!-- Période maximum estimé -->
					<div class="col-md-6">
						<div class="form-floating">
							<?= Form::input("datation_fin", null, array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
							<?= Form::label("Datation fin", "datation_fin"); ?>
						</div>
					</div>

				</div>
			</div>

			<div class="row my-3">
				<!-- Type de dépôt -->
				<div class="col-md-4">
					<?= Typedepot::generateSelect(); ?>
				</div>

				<!-- Type de sepulture -->
				<div class="col-md-4">
					<?= Typesepulture::generateSelect(); ?>
				</div>

				<!-- Contexte normatif -->
				<div class="col-md-4">
					<div class="form-floating">
						<?= Form::select(
							"contexte_normatif",
							"",
							array( // Les options
								"" => "Sélectionner",
								"Standard" => "Standard",
								"Atypique" => "Atypique"
							),
							array("class" => "form-select")
						);
						?>
						<?= Form::label("Contexte normatif", "contexte_normatif") ?>
					</div>
				</div>
			</div>

			<div class="row g-2">
				<!-- Milieu de vie -->
				<div class="col-md-6">
					<div class="form-floating">
						<?= Form::select(
							"milieu_vie",
							"",
							array(
								"" => "Sélectionner",
								"Rural" => "Rural",
								"Urbain" => "Urbain"
							),
							array("class" => "form-select")
						);
						?>
						<?= Form::label("Milieu de vie", "milieu_vie") ?>
					</div>
				</div>

				<!-- Contexte -->
				<div class="col-md-6">
					<div class="form-floating">
						<?= Form::select(
							"contexte",
							"",
							array(
								"" => "Sélectionner",
								"Funeraire" => "Funéraire",
								"Domestique" => "Domestique",
								"Autre" => "Autre"
							),
							array("class" => "form-select")
						);
						?>
						<?= Form::label("Contexte de la tombe", "contexte") ?>
					</div>
				</div>
			</div>

			<!-- Commentaire contexte -->
			<div class="col-md-12">
				<label for="commentaire_contexte">Commentaire</label>
				<div class="input-group">
					<textarea class="form-control" name="commentaire_contexte" rows="2"></textarea>
				</div>
			</div>
			<br />

			<div class="row">
				<!-- Accessoires -->
				<div class="col-md-4">
					<h3>Accessoires</h3>
					<?php foreach (Mobilier::fetchAll() as $mobilier) : ?>
						<div class="form-check form-switch">
							<?= Form::label($mobilier->getNom(), "mobilier_{$mobilier->getId()}"); ?>
							<?= Form::input("mobilier_{$mobilier->getId()}", null, array("type" => "checkbox", "class" => "form-check-input")); ?>
						</div>
					<?php endforeach; ?>
					<div id="block_description_autre_mobilier_' . $noLigne . '" class="' . $d_none_descp_autre_mobilier . '">
						<label class="form-check-label" for="mobilier_description">Description du mobilier</label>
						<textarea class="form-control" name="mobilier_description" rows="2"></textarea>
					</div>
				</div>

				<div class="col-md-8">
					<h3>Dépot</h3>
					<div class="row row-cols-2">
						<!-- Numéro de dépôt -->
						<div class="col">
							<div class="form-floating">
								<?= Form::input("num_inventaire", null, array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
								<?= Form::label("Numéro de dépôt", "num_inventaire"); ?>
							</div>
						</div>

						<!-- Commune du dépôt -->
						<div class="col">
							<div class="form-floating">
								<?= Form::input("commune_depot", null, array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off")); ?>
								<?= Form::label("Rechercher une commune", "commune_depot"); ?>
							</div>
							<script>
								addAutocomplete("form_commune_depot", "commune");
							</script>
						</div>

						<!-- Adresse du dépôt -->
						<div class="col my-2">
							<div class="form-floating">
								<?= Form::input("adresse_depot", null, array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
								<?= Form::label("Adresse du dépôt", "adresse_depot"); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br />

			<!-- Toutes les invalidations du sujets -->
			<h3>Atteinte invalidante</h3>
			<style>
				.th-title-rotate {
					padding: 0px;
					margin: 0px;
					width: 24px;
					height: 80px;
					transform: rotate(90deg);
					transform-origin: 10px 12px;
				}
			</style>
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
							<?php $margin = $i + 1 === count($localisations) ? "100px" : "10px"; // Grosse margin appliqué pour le dernier élément des localisations 
							?>
							<td><?= Asset::img($locali->getUrlImg(), array("style" => "width: 50 px; height: 25px; margin-right: $margin;", "alt" => $locali->getNom())); ?></td>
						<?php $i++;
						endforeach; ?>
						<?php foreach ($appareils as $appareil) : ?>
							<td>
								<div class="th-title-rotate"><?= $appareil->getNom() ?></div>
							</td>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach (Diagnostic::fetchAll() as $diagnostic) : ?>
						<tr>
							<!-- Titre des diagnostics -->
							<td>
								<div class="form-check form-switch">
									<?= Form::label($diagnostic->getNom(), "diagnostic_{$diagnostic->getId()}", array("class" => "form-check-label")); ?>
									<?= Form::checkbox("diagnostic_{$diagnostic->getId()}", null, null, array("class" => "form-check-input")); ?>
								</div>
							</td>
							<!-- Checkbox des zones atteintes -->
							<?php foreach ($localisations as $locali) : ?>
								<td>
									<?= Form::checkbox("diagnostic_{$diagnostic->getId()}_localisation_{$locali->getId()}", null, null, array("class" => "form-check-input")); ?>
								</td>
							<?php endforeach; ?>
							<!-- Checkbox des appareils compensatoires -->
							<?php foreach ($appareils as $appareil) : ?>
								<td>
									<?= Form::checkbox("diagnostic_{$diagnostic->getId()}_appareil_{$appareil->getId()}", null, null, array("class" => "form-check-input")); ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<!-- Affichage des pathologies
			<div class="' . $d_none_pathologie . '" id="block_pathologies_infectieuses_' . $noLigne . '" style="width: 50%; background-color: white;">
				<div class="form-check form-switch" style="padding-left: 75px;">
					<label class="form-check-label" for="PI_' . $key2['name'] . '[' . $noLigne . ']">$key2['type_pathologie']</label>
					<input class="form-check-input" type="checkbox" name="PI_' . $key2['name'] . '[' . $noLigne . ']" value="' . $key2['id_pathologie'] . '">
				</div>
			</div> -->

			<!-- <div class="row">
				<label for="commentaire_appareil">Commentaire sur l\'appareil de compensation</label>
				<textarea class="form-control" name="commentaire_appareil[' . $noLigne . ']" rows="2">OSKUR</textarea>
				<label for="commentaire_diagnostic[' . $noLigne . ']">Commentaire du diagnostic</label>
				<textarea class="form-control" name="commentaire_diagnostic[' . $noLigne . ']" rows="2" placeholder="** INFO ** Les différentes atteintes cochées seront ajoutées à la description"></textarea>
			</div> -->

			<!-- <br />
			<div style="text-align: center;">
				<button type="button" class="btn btn-primary" id="btnAjouter' . $noLigne . '" onclick="ajouterLigne(' . ($noLigne + 1) . ');"><i class="bi bi-plus"></i></button>
				<button type="button" class="btn btn-danger" id="btnSupp' . $noLigne . '" onclick="supprimerLigne(' . $noLigne . ');"><i class="bi bi-x"></i></button>
			</div> -->
		</div>

	</div>
	<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 10px;">
		<?= Form::submit('confirm_sujet_handicape', 'Ajouter', array('class' => 'btn btn-success')); ?>
	</div>
	<?= Form::close(); ?>

</div>