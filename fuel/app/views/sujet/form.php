<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\FuelException;
use Model\Appareil;
use Model\Chronology;
use Model\Diagnostic;
use Model\Localisation;
use Model\Mobilier;
use Model\Sujethandicape;
use Model\Typedepot;
use Model\Typesepulture;

if (!isset($subject) && !isset($idOperation)) {
	throw new FuelException("Pour générer le formulaire, il est nécessaire de connaître au moins soit l'id de l'opération parent, soit le sujet handicapé.");
}
/** @var Sujethandicape Base pour définir les valeurs du formulaires. */
$subject = isset($subject) ? $subject : new Sujethandicape(array());
/** @var int */
$idOperation = isset($idOperation) ? $idOperation : $subject->getGroup()->getIdOperation();

?>

<?=
Form::open(array(
	"action" => "sujet/add/$idOperation",
	"method" => "POST",
	"style" => "background-color: #F5F5F5; padding: 10px;"
));
?>
<input type="hidden" name="id_operation" value="<?= $idOperation ?>">
<div class="contenu" id="contenu">
	<div class="col-auto">
		<h2 class="text-center">Groupe du sujet</h2>

		<div class="row g-2">
			<?php $group = $subject->getGroup(); ?>
			<!-- NMI -->
			<div class="col-md-6">
				<div class="form-floating">
					<input type="number" class="form-control" name="NMI" placeholder="" value="<?= $group !== null ? $group->getNMI() : "" ?>">
					<label for="NMI">NMI</label>
				</div>
			</div>

			<!-- Chronology -->
			<div class="col-md-6">
				<?= Chronology::generateSelect("id_chronology", "Chronologie", $group === null ? "" : $group->getChronology()->getId()); ?>
			</div>
		</div>

		<h3 class="text-center my-2">Sujet handicapé</h3>
		<div class="row g-2">
			<div class="row g-2">

				<!-- Identifiant -->
				<div class="col-md-6">
					<div class="form-floating">
						<input type="text" class="form-control" name="id_sujet_handicape" placeholder="" value="<?= $subject->getIdSujetHandicape(); ?>">
						<label for="id_sujet">Identifiant du sujet</label>
					</div>
				</div>

				<!-- Sexe -->
				<div class="col-md-6">
					<div class="form-floating">
						<?=
						Form::select(
							"sexe",
							$subject->getSexe(),
							array(
								"Femme" => "Femme",
								"Homme" => "Homme",
								"Indéterminé" => "Indéterminé"
							),
							array("class" => "form-select")
						);
						?>
						<?= Form::label("Sexe", "sexe") ?>
					</div>
				</div>
			</div>

			<div class="row g-2">
				<!-- Âge minimum estimé de décès -->
				<div class="col-md-6">
					<div class="form-floating">
						<?= Form::input("age_min", $subject->getAgeMin(), array("type" => "number", "class" => "form-control", "min" => "0", "max" => "130", "placeholder" => "")); ?>
						<?= Form::label("Âge minimum au décès", "age_min"); ?>
					</div>
				</div>

				<!-- Âge maximum estimé de décès -->
				<div class="col-md-6">
					<div class="form-floating">
						<?= Form::input("age_max", $subject->getAgeMax(), array("type" => "number", "class" => "form-control", "min" => "0", "max" => "130", "placeholder" => "")); ?>
						<?= Form::label("Âge maximum au décès", "age_max"); ?>
					</div>
				</div>
			</div>

			<div class="row g-2">
				<!-- Période minimum estimé -->
				<div class="col-md-6">
					<div class="form-floating">
						<?= Form::input("dating_min", $subject->getDatingMin(), array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
						<?= Form::label("Datation début", "dating_min"); ?>
					</div>
				</div>

				<!-- Période maximum estimé -->
				<div class="col-md-6">
					<div class="form-floating">
						<?= Form::input("dating_max", $subject->getDatingMax(), array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
						<?= Form::label("Datation fin", "dating_max"); ?>
					</div>
				</div>

			</div>
		</div>

		<div class="row my-3">
			<!-- Type de dépôt -->
			<div class="col-md-4">
				<?= Typedepot::generateSelect("id_type_depot", "Type de dépôt", $subject->getIdTypeDepot()); ?>
			</div>

			<!-- Type de sepulture -->
			<div class="col-md-4">
				<?= Typesepulture::generateSelect("id_sepulture", "Type de sépulture", $subject->getIdTypeSepulture()); ?>
			</div>

			<!-- Contexte normatif -->
			<div class="col-md-4">
				<div class="form-floating">
					<?= Form::select(
						"contexte_normatif",
						$subject->getContexteNormatif(),
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
						$subject->getMilieuVie(),
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
						$subject->getContexte(),
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
				<textarea class="form-control" name="commentaire_contexte" rows="2"><?= $subject->getCommentaireContexte(); ?></textarea>
			</div>
		</div>
		<br />

		<div class="row">
			<!-- Accessoires -->
			<div class="col-md-4">
				<h3>Accessoires</h3>
				<?php $subFurnituresId = $subject->getFurnituresId(); ?>
				<?php foreach (Mobilier::fetchAll() as $mobilier) : ?>
					<div class="form-check form-switch">
						<?php
						$attr = array("class" => "form-check-input");
						if (in_array($mobilier->getId(), $subFurnituresId)) {
							$attr["checked"] = 1;
						}
						?>
						<?= Form::label($mobilier->getNom(), "mobilier_{$mobilier->getId()}"); ?>
						<?= Form::checkbox("mobilier_{$mobilier->getId()}", null, $attr); ?>
					</div>
				<?php endforeach; ?>
				<div id="block_description_autre_mobilier_' . $noLigne . '" class="' . $d_none_descp_autre_mobilier . '">
					<label class="form-check-label" for="mobilier_description">Description du mobilier</label>
					<textarea class="form-control" name="mobilier_description" rows="2"></textarea>
				</div>
			</div>

			<div class="col-md-8">
				<h3>Dépôt</h3>
				<?php $depot = $subject->getDepot(); ?>
				<div class="row row-cols-2">
					<!-- Numéro de dépôt -->
					<div class="col">
						<div class="form-floating">
							<?= Form::input("num_inventaire", $depot === null ? null : $depot->getNumInventaire(), array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
							<?= Form::label("Numéro de dépôt", "num_inventaire"); ?>
						</div>
					</div>

					<!-- Commune du dépôt -->
					<div class="col">
						<div class="form-floating">
							<?= Form::input("depot_commune", $depot === null ? null : $depot->getCommune()->fullName(), array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off")); ?>
							<?= Form::label("Rechercher une commune", "depot_commune"); ?>
						</div>
						<script>
							addAutocomplete("form_depot_commune", "commune");
						</script>
					</div>

					<!-- Adresse du dépôt -->
					<div class="col my-2">
						<div class="form-floating">
							<?= Form::input("depot_adresse", $depot === null ? null : $depot->getAdresse(), array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
							<?= Form::label("Adresse du dépôt", "depot_adresse"); ?>
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
								<?= Form::checkbox("diagnostic_{$diagnostic->getId()}_spot_{$locali->getId()}", null, null, array("class" => "form-check-input")); ?>
							</td>
						<?php endforeach; ?>
						<!-- Checkbox des appareils compensatoires -->
						<?php foreach ($appareils as $appareil) : ?>
							<td>
								<?= Form::checkbox("diagnostic_{$diagnostic->getId()}_item_{$appareil->getId()}", null, null, array("class" => "form-check-input")); ?>
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