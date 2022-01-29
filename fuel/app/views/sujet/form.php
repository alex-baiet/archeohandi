<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\FuelException;
use Fuel\Core\View;
use Model\Appareil;
use Model\Chronology;
use Model\Diagnostic;
use Model\Helper;
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
/** @var bool Ajoute un bouton pour rester sur la page. */
$btnStay = isset($btnStay) ? $btnStay : false;

if (!empty($msg)) {
	Helper::alertBootstrap($msg, $msgType);
}

?>

<?= $subject->echoErrors(); ?>
<?=
Form::open(array(
	"method" => "POST",
	"style" => "background-color: #F5F5F5; padding: 10px;",
	"onsubmit" => "prepareFormSend()"
));
?>
<input type="hidden" name="id_operation" value="<?= $idOperation ?>">
<?php if ($subject->getId() !== null) echo Form::hidden("id", $subject->getId()); ?>
<div class="col-auto">
	<h2 class="text-center">Groupe du sujet</h2>

	<div class="row g-2">
		<?php $group = $subject->getGroup(); ?>
		<?php if ($group !== null && $group->getId() !== null) echo Form::hidden("id_group", $group->getId()); ?>

		<!-- NMI -->
		<div class="col-md-6">
			<div class="form-floating">
				<input name="NMI" id="form_NMI" value="<?= $group !== null ? $group->getNMI() : "" ?>"
					type="number" class="form-control" placeholder="NMI" min="0">
				<label for="form_NMI">NMI</label>
			</div>
		</div>

		<!-- Chronologie -->
		<div class="col-md-6">
			<?= Chronology::generateSelect("id_chronologie", "Chronologie", $group !== null && $group->getChronology() !== null ? $group->getChronology()->getId() : 18); ?>
		</div>
	</div>

	<h3 class="text-center my-2">Sujet handicapé</h3>
	<div class="row g-2">

		<div class="row g-2">
			<!-- Identifiant -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="id_sujet_handicape" id="form_id_sujet_handicape" value="<?= $subject->getIdSujetHandicape(); ?>"
						type="text" class="form-control" placeholder="" maxlength="256">
					<label for="form_id_sujet_handicape">Identifiant du sujet</label>
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
					<input name="age_min" id="form_age_min" value="<?= $subject->getAgeMin(); ?>"
						type="number" class="form-control" placeholder="Âge minimum au décès" min="0" max="130" step="1">
					<label for="form_age_min">Âge minimum au décès</label>
				</div>
			</div>

			<!-- Âge maximum estimé de décès -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="age_max" id="form_age_max" value="<?= $subject->getAgeMax(); ?>"
						type="number" class="form-control" placeholder="Âge maximum au décès" min="0" max="130" step="1">
					<label for="form_age_max">Âge maximum au décès</label>
				</div>
			</div>
		</div>

		<div class="row g-2">
			<!-- Période minimum estimé -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="dating_min" id="form_dating_min" value="<?= $subject->getDatingMin(); ?>"
						type="number" class="form-control" placeholder="Datation minimale" min="-20000" max="1945" step="1">
					<label for="form_dating_min">Datation minimale</label>
				</div>
			</div>

			<!-- Période maximum estimé -->
			<div class="col-md-6">
				<div class="form-floating">
					<input name="dating_max" id="form_dating_max" value="<?= $subject->getDatingMax(); ?>"
						type="number" class="form-control" placeholder="Datation maximal" min="-20000" max="1945" step="1">
					<label for="form_dating_max">Datation maximale</label>
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
		<label for="form_comment_contexte">Commentaire</label>
		<textarea name="comment_contexte" id="form_comment_contexte"
			class="form-control" rows="2" maxlength="65535"
		><?= $subject->getCommentContext(); ?></textarea>
	</div>
	<br />

	<div class="row">
		<!-- Accessoires -->
		<div class="col-md-6">
			<h3>Accessoires</h3>
			<?php $subFurnituresId = $subject->getFurnituresId(); ?>
			<?php foreach (Mobilier::fetchAll() as $mobilier) : ?>
				<div class="form-check form-switch">
					<label for="form_id_mobiliers_<?= $mobilier->getId() ?>" class="form-check-label"><?= $mobilier->getNom() ?></label>
					<input name="id_mobiliers[]" id="form_id_mobiliers_<?= $mobilier->getId() ?>" value="<?= $mobilier->getId() ?>"
						type="checkbox" class="form-check-input"
						<?php if (in_array($mobilier->getId(), $subFurnituresId)) : ?>checked<?php endif; ?>>
				</div>
			<?php endforeach; ?>
			<div id="block_description_autre_mobilier_' . $noLigne . '" class="' . $d_none_descp_autre_mobilier . '">
				<label class="form-check-label" for="mobilier_description">Description du mobilier</label>
				<textarea class="form-control" name="mobilier_description" rows="2"></textarea>
			</div>
		</div>

		<!-- Dépôt -->
		<div class="col-md-6">
			<h3>Dépôt</h3>
			<?php
			$depot = $subject->getDepot();
			if ($depot !== null && $depot->getId() !== null) echo Form::hidden("id_depot", $depot->getId());
			?>

			<!-- Numéro de dépôt -->
			<div class="form-floating my-2">
				<input name="num_inventaire" id="form_num_inventaire" value="<?= $depot === null ? null : $depot->getNumInventaire() ?>"
					type="text" class="form-control" placeholder="Numéro de dépôt" maxlength="256">
				<label for="form_num_inventaire">Numéro de dépôt</label>
			</div>

			<!-- Commune du dépôt -->
			<div class="form-floating my-2">
				<?php $communeName = $depot !== null && $depot->getCommune() !== null ? $depot->getCommune()->fullName() : null; ?>
				<input name="depot_commune" id="form_depot_commune" value="<?= $communeName ?>"
					type="text" class="form-control" placeholder="Commune" maxlength="256" autocomplete="off">
				<label for="form_depot_commune">Commune</label>
			</div>
			<script>addAutocomplete("form_depot_commune", "commune");</script>

			<!-- Adresse du dépôt -->
			<div class="form-floating my-2">
				<input name="depot_adresse" id="form_depot_adresse" value="<?= $depot === null ? null : $depot->getAdresse() ?>"
					type="text" class="form-control" placeholder="Adresse du dépôt" maxlength="256">
				<label for="form_depot_adresse">Adresse du dépôt</label>
			</div>
		</div>
	</div>
</div>
<br />

<!-- Diagnostics -->
<div class="row">
	<div class="col-md-6">
		<h3>Atteinte invalidante</h3>

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
					<?php
					$hasDiagnosis = $subject->hasDiagnosis($diagnostic->getId());
					?>
					<tr>
						<!-- Titre des diagnostics -->
						<td>
							<div class="form-check form-switch">
								<label for="form_diagnostic_<?= $diagnostic->getId() ?>" class="form-check-label"><?= $diagnostic->getNom() ?></label>
								<input name="diagnostic_<?= $diagnostic->getId() ?>" id="form_diagnostic_<?= $diagnostic->getId() ?>"
									type="checkbox" class="form-check-input"
									<?php if ($hasDiagnosis) : ?>checked<?php endif; ?>>

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

								// Maj affichage du checkbox de la localisation si le diagnostic est coché par défaut
								if ($hasDiagnosis) {
									$subjectDia = $subject->getDiagnosis($diagnostic->getId());
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
								<input name="diagnostics[<?= $diagnostic->getId() ?>][]" id="form_diagnostic_<?= $diagnostic->getId() ?>" value="<?= $locali->getId() ?>"
									type="checkbox" class="form-check-input <?= $classes ?>"
									<?= $hidden ? "hidden" : null ?>
									<?= $disabled ? "disabled" : null ?>
									<?= $checked ? "checked" : null ?>
								>
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

	<div class="col-md-6">
		<!-- Appareils de compensation -->
		<h3>Appareils compensatoire</h3>
		<?php foreach (Appareil::fetchAll() as $item) : ?>
			<div class="form-check form-switch">
				<label for="form_appareils_<?= $item->getId() ?>" class="form-check-label"><?= $item->getName() ?></label>
				<input name="appareils[]" id="form_appareils_<?= $item->getId() ?>" value="<?= $item->getId() ?>"
					type="checkbox" class="form-check-input"
					<?php if ($subject->hasItemHelp($item->getId())) : ?>checked<?php endif; ?>>
			</div>
		<?php endforeach; ?>

		<!-- Pathologies -->
		<h4 style="margin-top: 30px;">Pathologies infectieuses</h4>
		<?php foreach (Pathology::fetchAll() as $pathology) : ?>
			<div class="form-check form-switch">
				<label for="form_pathologies_<?= $pathology->getId() ?>" class="form-check-label"><?= $pathology->getName() ?></label>
				<input name="pathologies[]" id="form_pathologies_<?= $pathology->getId() ?>" value="<?= $pathology->getId() ?>"
					type="checkbox" class="form-check-input"
					<?php if ($subject->hasPathology($pathology->getId())) : ?>checked<?php endif; ?>>			
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Commentaire du diagnostic -->
	<label for="comment_diagnostic">Commentaire du diagnostic</label>
	<div class="input-group">
		<textarea class="form-control" name="comment_diagnostic" rows="2" maxlength="65535"><?= $subject->getCommentDiagnosis(); ?></textarea>
	</div>

	<h3>Iconographie</h3>
	<!-- Lien Nakala -->
	<div class="row my-2">
		<div class="col-md-auto">
			<a href="https://nakala.fr/u/collections/10.34847/nkl.2400swmp" class="btn btn-primary" target="_blank">Aller sur Nakala</a>
		</div>

		<div class="col-md-auto">
			<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#helpPopup">Aide <i class="bi bi-question-circle"></i></button>
		</div>
	</div>

	<!-- Listes URL images -->
	<?php
	$urls = $subject->getUrlsImg();
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
	
</div>

<!-- Bouton de confirmation/retour -->
<div class="row" style="margin-top: 10px;">
	<div class="d-md-block col">
		<a class="btn btn-secondary" href="/public/operations/view/<?= $idOperation; ?>" role="button">Retour</a>
	</div>

	<div class="d-md-flex justify-content-md-end col">
		<button type="submit" name="stayOnPage" value="0" class="btn btn-success">
			Confirmer<?php if ($btnStay) : ?> et sortir<?php endif; ?>
		</button>

		<?php if ($btnStay) : ?>
			<button type="submit" name="stayOnPage" value="1" class="btn btn-success" style="margin-left: 10px">
				Confirmer et dupliquer la fiche
			</button>
		<?php endif; ?>
	</div>

</div>

<!-- Popup d'aide d'ajout d'image -->
<div class="modal" id="helpPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="helpPopupLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width: 800px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="helpPopupLabel">Aide</h5>
			</div>
			<div class="modal-body">
				<p>
					Pour ajouter une image de Nakala au sujet, allez d'abord sur l'affichage d'une image directement sur Nakala,
					puis copiez l'un des deux champs comme indiqué dans l'image suivante :<br>
					<?= Asset::img("help/demo_url.png", array("style" => "width: 100%;")) ?><br>
					<br>
					Collez le champ dans la zone, et si une prévisualisation de l'image s'affiche,
					c'est que votre image a bien été ajoutée au sujet !<br>
					<br>
					Il est aussi possible d'importer des images provenant d'autres sites, mais attention,
					si les images sur ces sites sont supprimées, elles ne seront plus disponible ici non plus.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#helpPopup">Retour</button>
			</div>
		</div>
	</div>
</div>

<?= Form::close(); ?>