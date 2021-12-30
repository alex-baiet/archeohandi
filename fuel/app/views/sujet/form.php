<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\FuelException;
use Model\Appareil;
use Model\Chronology;
use Model\Diagnostic;
use Model\Localisation;
use Model\Mobilier;
use Model\Pathology;
use Model\Sex;
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

<?= $subject->echoErrors(); ?>
<?=
Form::open(array(
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
				<?= Chronology::generateSelect("id_chronologie", "Chronologie", $group !== null && $group->getChronology() !== null ? $group->getChronology()->getId() : ""); ?>
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
						<?= Sex::generateSelect("sexe", $subject->getSexe()); ?>
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
			<label for="comment_contexte">Commentaire</label>
			<div class="input-group">
				<textarea class="form-control" name="comment_contexte" rows="2"><?= $subject->getCommentContext(); ?></textarea>
			</div>
		</div>
		<br />

		<div class="row">
			<!-- Accessoires -->
			<div class="col-md-6">
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
						<?= Form::label($mobilier->getNom(), null); ?>
						<?= Form::checkbox("id_mobiliers[]", $mobilier->getId(), $attr); ?>
					</div>
				<?php endforeach; ?>
				<div id="block_description_autre_mobilier_' . $noLigne . '" class="' . $d_none_descp_autre_mobilier . '">
					<label class="form-check-label" for="mobilier_description">Description du mobilier</label>
					<textarea class="form-control" name="mobilier_description" rows="2"></textarea>
				</div>
			</div>

			<div class="col-md-6">
				<h3>Dépôt</h3>
				<?php $depot = $subject->getDepot(); ?>
					<!-- Numéro de dépôt -->
						<div class="form-floating my-2">
							<?= Form::input("num_inventaire", $depot === null ? null : $depot->getNumInventaire(), array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
							<?= Form::label("Numéro de dépôt", "num_inventaire"); ?>
						</div>

					<!-- Commune du dépôt -->
						<div class="form-floating my-2">
							<?= Form::input("depot_commune", $depot !== null && $depot->getCommune() !== null ? $depot->getCommune()->fullName() : null, array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off")); ?>
							<?= Form::label("Rechercher une commune", "depot_commune"); ?>
						</div>
						<script>
							addAutocomplete("form_depot_commune", "commune");
						</script>

					<!-- Adresse du dépôt -->
						<div class="form-floating my-2">
							<?= Form::input("depot_adresse", $depot === null ? null : $depot->getAdresse(), array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
							<?= Form::label("Adresse du dépôt", "depot_adresse"); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br />

		<!-- Diagnostics -->
		<div class="row">
			<div class="col-md-6">
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
								<td><?= Asset::img($locali->getUrlImg(), array("style" => "width: 50 px; height: 25px; margin-right: 10px;", "alt" => $locali->getNom())); ?></td>
							<?php $i++; endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach (Diagnostic::fetchAll() as $diagnostic) : ?>
							<tr>
								<!-- Titre des diagnostics -->
								<td>
									<div class="form-check form-switch">
										<!-- Actuellement inutile... -->
										<?php
										$attr = array("class" => "form-check-input");
										$hasDiagnosis = $subject->hasDiagnosis($diagnostic->getId());
										if ($hasDiagnosis) $attr["checked"] = 1;
										?>
										<?= Form::label($diagnostic->getNom(), "diagnostic_{$diagnostic->getId()}", array("class" => "form-check-label")); ?>
										<?= Form::checkbox("diagnostic_{$diagnostic->getId()}", null, null, $attr); ?>
									</div>
								</td>
								<!-- Checkbox des zones atteintes -->
								<?php foreach ($localisations as $locali) : ?>
									<td>
										<?php
										$attr = array("class" => "form-check-input");
										if ($hasDiagnosis) {
											// Test pour savoir si le diagnostic a été localisé à la localisation actuel
											$subjectDia = $subject->getDiagnosis($diagnostic->getId());
											if ($subjectDia->isLocatedFromId($locali->getId()))  $attr["checked"] = 1;
										}
										?>
										<?= Form::checkbox("diagnostics[{$diagnostic->getId()}][]", $locali->getId(), null, $attr); ?>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="col-md-6">
				<!-- Appareils de compensation -->
				<h3>Appareils compensatoire</h3>
				<?php foreach (Appareil::fetchAll() as $item): ?>
					<div class="form-check form-switch">
						<?php
						$attr = array("class" => "form-check-input");
						if ($subject->hasItemHelp($item->getId())) $attr["checked"] = 1;
						?>
						<?= Form::label($item->getName(), null, array("class" => "form-check-label")); ?>
						<?= Form::checkbox("appareils[]", $item->getId(), null, $attr); ?>
					</div>
				<?php endforeach; ?>

				<!-- Pathologies -->
				<h3 style="margin-top: 70px;">Pathologies</h3>
				<?php foreach (Pathology::fetchAll() as $pathology): ?>
					<div class="form-check form-switch">
						<?php
						$attr = array("class" => "form-check-input");
						if ($subject->hasPathology($pathology->getId())) $attr["checked"] = 1;
						?>
						<?= Form::label($pathology->getName(), null, array("class" => "form-check-label")); ?>
						<?= Form::checkbox("pathologies[]", $pathology->getId(), null, $attr); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Commentaire du diagnostic -->
		<label for="comment_diagnostic">Commentaire du diagnostic</label>
		<div class="input-group">
			<textarea class="form-control" name="comment_diagnostic" rows="2"><?= $subject->getCommentDiagnosis(); ?></textarea>
		</div>

		<!-- <br />
			<div style="text-align: center;">
				<button type="button" class="btn btn-primary" id="btnAjouter' . $noLigne . '" onclick="ajouterLigne(' . ($noLigne + 1) . ');"><i class="bi bi-plus"></i></button>
				<button type="button" class="btn btn-danger" id="btnSupp' . $noLigne . '" onclick="supprimerLigne(' . $noLigne . ');"><i class="bi bi-x"></i></button>
			</div> -->
	</div>

</div>
<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 10px;">
	<?= Form::submit('confirm_sujet_handicape', 'Confirmer', array('class' => 'btn btn-success')); ?>
</div>
<?= Form::close(); ?>