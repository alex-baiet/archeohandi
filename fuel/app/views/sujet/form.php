<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\View;
use Model\Db\Appareil;
use Model\Db\Chronology;
use Model\Db\Diagnostic;
use Model\Db\Localisation;
use Model\Db\Mobilier;
use Model\Db\Operation;
use Model\Db\Pathology;
use Model\Db\Sex;
use Model\Db\Sujethandicape;
use Model\Db\Typedepot;
use Model\Db\Typesepulture;
use Model\Helper;

/** @var null|int */
$idOp = null;
if (isset($idOperation)) $idOp = $idOperation;
if (isset($subject)) $idOp = $subject->getGroup()->getIdOperation();

/** @var Sujethandicape Base pour définir les valeurs du formulaires. */
$subject = isset($subject) ? $subject : new Sujethandicape(array());

/** @var null|int */
$idOperation = isset($idOperation) ? $idOperation : null;
if ($subject->getGroup() !== null) $idOperation = $subject->getGroup()->getIdOperation();

/** @var bool Ajoute un bouton pour rester sur la page. */
$btnStay = isset($btnStay) ? $btnStay : false;

$operation = Operation::fetchSingle($idOperation);

if (!empty($msg)) {
	Helper::alertBootstrap($msg, $msgType);
}

?>

<?= $subject->echoErrors(); ?>
<?php if ($idOperation !== null) : ?>
	<input type="hidden" name="id_operation" value="<?= $idOperation ?>">
<?php endif; ?>
<?php if ($subject->getId() !== null) : ?>
	<input type="hidden" name="id" value="<?= $subject->getId() ?>">
<?php endif; ?>
<h2 class="text-center">Groupe du sujet</h2>

<div class="row g-2">
	<?php $group = $subject->getGroup(); ?>
	<?php if ($group !== null && $group->getId() !== null) : ?>
		<input type="hidden" name="id_group" value="<?= $group->getId() ?>">
	<?php endif; ?>
	<!-- NMI -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="nmi" id="form_nmi" value="<?= $group !== null ? $group->getNMI() : "" ?>" type="number" class="form-control" placeholder="nmi" min="0" title="Indiquez le Nombre Minimum d'Individu que compose le groupe dont fait parti le sujet handicapé">
			<div class="form-msg-error">La valeur doit être un nombre positif</div>
			<label for="form_nmi">NMI</label>
		</div>
	</div>

	<!-- Chronologie -->
	<?php $chrono = $group !== null && $group->getChronology() !== null ? $group->getChronology()->getId() : 18; ?>
	<div class="col-md-6">
		<div class="form-floating">
			<select name="id_chronologie" id="form_id_chronologie" class="form-select" required title="Indiquer la phase chronologique à laquelle appartient le sujet handicapé">
				<?= Chronology::fetchOptions($chrono) ?>
			</select>
			<label for="form_id_chronologie">Chronologie</label>
		</div>
	</div>
</div>

<h3 class="text-center my-2">Sujet handicapé</h3>

<div class="row my-4">
	<!-- Nom -->
	<div class="col-md-12">
		<div class="form-floating">
			<input name="id_sujet_handicape" id="form_id_sujet_handicape" value="<?= $subject->getIdSujetHandicape(); ?>" type="text" class="form-control" placeholder="Identifiant du sujet" maxlength="256" onclick="this.required=`required`" title="Indiquez le nom du sujet">
			<div class="form-msg-error">Veuillez indiquer une valeur pour ce champ</div>
			<label for="form_id_sujet_handicape">Identifiant du sujet<span class="red">*</span></label>
		</div>
	</div>
</div>

<div class="row my-4">
	<!-- Période minimum estimé -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="date_min" id="form_date_min" value="<?= $subject->getDateMin(); ?>" type="number" class="form-control" placeholder="Datation minimale" min="-20000" max="1945" step="1" title="Indiquez la borne inférieure de datation du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre -20000 et 1945</div>
			<label for="form_date_min">Datation minimale</label>
		</div>
	</div>

	<!-- Période maximum estimé -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="date_max" id="form_date_max" value="<?= $subject->getDateMax(); ?>" type="number" class="form-control" placeholder="Datation maximal" min="-20000" max="1945" step="1" title="Indiquez la borne supérieure de datation du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre -20000 et 1945</div>
			<label for="form_date_max">Datation maximale</label>
		</div>
	</div>
</div>

<div class="row my-4">
	<!-- Sexe -->
	<div class="col-md-6">
		<div class="form-floating">
			<?= Sex::generateSelect("sexe", $subject->getSexe()); ?>
		</div>
	</div>

	<!-- Methode sexe -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="sexe_methode" id="form_sexe_methode" value="<?= $subject->getSexeMethode(); ?>" type="text" class="form-control" placeholder="Méthode de détermination du sexe" maxlength="256" title="Indiquez la méthode utilisé pour déterminé le sexe">
			<label for="form_sexe_methode">Méthode de détermination du sexe</label>
		</div>
	</div>
</div>

<div class="row my-4">
	<!-- Âge minimum estimé de décès -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="age_min" id="form_age_min" value="<?= $subject->getAgeMin(); ?>" type="number" class="form-control" placeholder="Âge minimum au décès" min="0" max="130" step="1" title="Indiquez l'âge minimum estimé du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre 0 et 130</div>
			<label for="form_age_min">Âge minimum au décès</label>
		</div>
	</div>

	<!-- Âge maximum estimé de décès -->
	<div class="col-md-3">
		<div class="form-floating">
			<input name="age_max" id="form_age_max" value="<?= $subject->getAgeMax(); ?>" type="number" class="form-control" placeholder="Âge maximum au décès" min="0" max="130" step="1" title="Indiquez l'âge maximum estimé du sujet">
			<div class="form-msg-error">La valeur doit être un nombre entier entre 0 et 130</div>
			<label for="form_age_max">Âge maximum au décès</label>
		</div>
	</div>

	<!-- Méthode âge -->
	<div class="col-md-6">
		<div class="form-floating">
			<input name="age_methode" id="form_age_methode" value="<?= $subject->getAgeMethode(); ?>" type="text" class="form-control" placeholder="Méthode de détermination de l'âge" title="Indiquez la méthode utilisé pour déterminer l'âge">
			<label for="form_age_methode">Méthode de détermination de l'âge</label>
		</div>
	</div>
</div>

<div class="row my-3">
	<!-- Type de dépôt -->
	<div class="col-md-4">
		<div class="form-floating">
			<select name="id_type_depot" id="form_id_type_depot" class="form-select" title="Indiquez la modalité du dépôt">
				<?= Typedepot::fetchOptions($subject->getIdTypeDepot()) ?>
			</select>
			<label for="form_id_type_depot">Type de dépôt</label>
		</div>
	</div>

	<!-- Type de sepulture -->
	<div class="col-md-4">
		<div class="form-floating">
			<select name="id_sepulture" id="form_id_sepulture" class="form-select" title="Indiquez le type de la sépulture">
				<?= Typesepulture::fetchOptions($subject->getIdTypeSepulture()) ?>
			</select>
			<label for="form_id_sepulture">Type de sépulture</label>
		</div>
	</div>

	<!-- Milieu de vie -->
	<div class="col-md-4">
		<div class="form-floating">
			<?=
			Form::select(
				"milieu_vie",
				$subject->getMilieuVie(),
				array(
					"" => "Indéterminé",
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
</div>

<div class="row g-2">
	<!-- Contexte normatif -->
	<div class="col-md-6">
		<div class="form-floating">
			<?=
			Form::select(
				"contexte_normatif",
				$subject->getContexteNormatif(),
				array(
					"" => "Indéterminé",
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

	<!-- Contexte -->
	<div class="col-md-6">
		<div class="form-floating">
			<?= Form::select(
				"contexte",
				$subject->getContexte(),
				array(
					"" => "Indéterminé",
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

<!-- Commentaire contexte -->
<div class="col-md-12">
	<label for="form_comment_contexte">Commentaire</label>
	<textarea name="comment_contexte" id="form_comment_contexte" class="form-control" rows="2" maxlength="65535" title="Ecrivez ici des commentaires sur le groupe ou la sépulture en question si cela est nécessaire"><?= $subject->getCommentContext(); ?></textarea>
</div>
<br />

<div class="row">

	<div class="col-xxl-6">

		<h3>Atteinte invalidante</h3>
		<p class="text-muted"><em>D : Partie droite, G : Partie gauche</em></p>

		<div class="table-scroll" style="max-height: none;">
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
						<th>Nb cas concerné</th>
						<th>Nb cas observable</th>
						<th>Prévalence*</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach (Diagnostic::fetchAll() as $diagnostic) : ?>
						<?php
						$hasDiagnosis = $subject->hasDiagnosis($diagnostic->getId());
						?>
						<tr>
							<!-- Titre des diagnostics -->
							<th>
								<div class="form-check form-switch">
									<label id="form_diagnostic_label_<?= $diagnostic->getId(); ?>" for="form_diagnostic_<?= $diagnostic->getId() ?>" class="form-check-label"><?= $diagnostic->getNom() ?></label>
									<input name="diagnostic_<?= $diagnostic->getId() ?>" id="form_diagnostic_<?= $diagnostic->getId() ?>" type="checkbox" class="form-check-input" <?php if ($hasDiagnosis) : ?>checked<?php endif; ?>
										onchange="FormSujet.updatePrevalence(<?= $diagnostic->getId() ?>, this.checked ? 1 : -1)">
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
									<input name="diagnostics[<?= $diagnostic->getId() ?>][]" id="form_diagnostic_<?= $diagnostic->getId() ?>" value="<?= $locali->getId() ?>" type="checkbox" class="form-check-input <?= $classes ?>" <?= $hidden ? "hidden" : null ?> <?= $disabled ? "disabled" : null ?> <?= $checked ? "checked" : null ?>>
								</td>
							<?php endforeach; ?>
							
							<?php /* Prévalence */ ?>
							<td style="text-align:center;" id="count_concerned_<?= $diagnostic->getId() ?>"><?= $operation->countConcernedSubject($diagnostic->getId()) ?></td>
							<td style="text-align:center;">
								<input type="number" id="count_observable_<?= $diagnostic->getId() ?>" name="observables[<?= $diagnostic->getId() ?>]" value="<?= $operation->getObservable($diagnostic->getId()) ?>" class="form-control" style="padding: 3px 12px 3px 12px;" autocomplete="off"
									onchange="FormSujet.updatePrevalence(<?= $diagnostic->getId() ?>)">
							</td>
							<td style="text-align:center;" id="prevalence_<?= $diagnostic->getId() ?>"><?= round($operation->prevalence($diagnostic->getId()) * 1000.0) ?></td>

							<script>
								updateCheckboxOnSwitch(<?= $diagnostic->getId(); ?>);
							</script>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<br />
		<!-- Pathologies -->
		<?php foreach (Pathology::fetchAll() as $pathology) : ?>
			<div class="form-check form-switch">
				<label id="form_pathologies_label_<?= $pathology->getId() ?>" for="form_pathologies_<?= $pathology->getId() ?>" class="form-check-label"><?= $pathology->getName() ?></label>
				<input name="pathologies[]" id="form_pathologies_<?= $pathology->getId() ?>" value="<?= $pathology->getId() ?>" type="checkbox" class="form-check-input" <?php if ($subject->hasPathology($pathology->getId())) : ?>checked<?php endif; ?>>
			</div>
		<?php endforeach; ?>

		<br>
		<div class="text-muted">*La prévalence est ici une estimation minimale de la prévalence réelle.</div>
	</div>

	<div class="col-xxl-6">
		<!-- Dépôt -->
		<h3>Dépôt</h3>
		<?php
		$depot = $subject->getDepot();
		if ($depot !== null && $depot->getId() !== null) :
		?>
			<input type="hidden" name="id_depot" value="<?= $depot->getId() ?>">
		<?php endif; ?>
		<!-- Numéro de dépôt -->
		<div class="form-floating my-2">
			<input name="num_inventaire" id="form_num_inventaire" value="<?= $depot === null ? null : $depot->getNumInventaire() ?>" type="text" class="form-control" placeholder="Numéro de dépôt" maxlength="256" title="Indiquez le numéro du dépôt">
			<label for="form_num_inventaire">Numéro de dépôt</label>
		</div>
		<!-- Commune du dépôt -->
		<div class="form-floating my-2">
			<?php $communeName = $depot !== null && $depot->getCommune() !== null ? $depot->getCommune()->fullName() : null; ?>
			<input name="depot_commune" id="form_depot_commune" value="<?= $communeName ?>" type="text" class="form-control" placeholder="Commune" maxlength="256" autocomplete="off" title="Indiquez la commune du dépôt du sujet">
			<label for="form_depot_commune">Commune</label>
		</div>
		<script>
			addAutocomplete("form_depot_commune", "CONCAT(nom, ', ', departement)", "commune", [
				["nom", "LIKE", "?%"],
				["departement", "LIKE", "?%", "or"]
			]);
		</script>
		<!-- Adresse du dépôt -->
		<div class="form-floating my-2">
			<input name="depot_adresse" id="form_depot_adresse" value="<?= $depot === null ? null : $depot->getAdresse() ?>" type="text" class="form-control" placeholder="Adresse du dépôt" maxlength="256" title="Indiquez l'adresse du dépôt du sujet">
			<label for="form_depot_adresse">Adresse du dépôt</label>
		</div>

		<!-- Accessoires -->
		<h3 class="mt-4">Accessoire</h3>
		<?php $subFurnituresId = $subject->getFurnituresId(); ?>
		<?php foreach (Mobilier::fetchAll() as $mobilier) : ?>
			<div class="form-check form-switch">
				<label for="form_id_mobiliers_<?= $mobilier->getId() ?>" class="form-check-label"><?= $mobilier->getNom() ?></label>
				<input name="id_mobiliers[]" id="form_id_mobiliers_<?= $mobilier->getId() ?>" value="<?= $mobilier->getId() ?>" type="checkbox" class="form-check-input" <?php if (in_array($mobilier->getId(), $subFurnituresId)) : ?>checked<?php endif; ?>>
			</div>
		<?php endforeach; ?>
		<div>
			<label class="form-check-label" for="form_description_mobilier">Description du mobilier</label>
			<textarea class="form-control" name="description_mobilier" id="form_description_mobilier" rows="4"><?= $subject->getDescriptionMobilier() ?></textarea>
		</div>

		<!-- Appareils de compensation -->
		<h3 class="mt-4">Appareil compensatoire</h3>
		<?php foreach (Appareil::fetchAll() as $item) : ?>
			<div class="form-check form-switch">
				<label for="form_appareils_<?= $item->getId() ?>" class="form-check-label"><?= $item->getName() ?></label>
				<input name="appareils[]" id="form_appareils_<?= $item->getId() ?>" value="<?= $item->getId() ?>" type="checkbox" class="form-check-input" <?php if ($subject->hasItemHelp($item->getId())) : ?>checked<?php endif; ?>>
			</div>
		<?php endforeach; ?>

	</div>

</div>
<br />

<!-- Commentaire du diagnostic -->
<label for="comment_diagnostic">Commentaire du diagnostic</label>
<div class="input-group">
	<textarea class="form-control" name="comment_diagnostic" rows="4" maxlength="65535" title="Ecrivez ici des commentaires sur le diagnostic si besoin"><?= $subject->getCommentDiagnosis(); ?></textarea>
</div>

<!-- Commentaire du diagnostic -->
<div class="form-check form-switch">
	<input type="hidden" name="genetiques_actif" value="0">
	<input type="checkbox" name="genetiques_actif" value="1" id="form_genetiques_actif" class="form-check-input" 
	<?php if ($subject->getDonneesGenetiques() !== null) : ?>checked<?php endif; ?> onclick="checkboxActivator(`form_genetiques_actif` ,`genetique_parent`)">
	<label for="form_genetiques_actif">Données génétiques</label>
</div>
<div class="input-group" id="genetique_parent">
	<textarea class="form-control" name="genetique" rows="4" maxlength="65535" title="Ecrivez ici les informations sur les données génétiques"
	style="width: 100%;"
	><?= $subject->getDonneesGenetiques(); ?></textarea>
</div>
<script>checkboxActivator(`form_genetiques_actif` ,`genetique_parent`);</script>

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

<input type="hidden" name="complet" value="0">
<input type="checkbox" name="complet" value="1" id="form_complet" class="form-check-input" <?php if ($subject->getComplet()) : ?>checked<?php endif; ?>>
<label for="form_complet">Les informations du sujet sont complètes. <span class="text-muted">(vous pourrez toujours modifier le sujet)</span></label>

<?= View::forge("global/help_img"); ?>
