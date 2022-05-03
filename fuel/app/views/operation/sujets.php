<?php

use Fuel\Core\Form;
use Model\Dataview;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Typedepot;
use Model\Db\Typesepulture;

/** @var Operation Operation actuelle. */
$operation = $operation;
/** @var string Message a afficher. */
$msg = isset($msg) ? $msg : null;

$sujets = $operation->getSubjects();

?>

<script type="text/javascript">
	function deleteSubject(idSubject) {
		let btnElem = document.getElementById("form_id");
		btnElem.value = idSubject;
	}
</script>

<h1 class="m-2">
	<?php if (!empty($sujets)) : ?>
		Sujets handicapés (<?= count($sujets); ?>)
	<?php else : ?>
		Aucun sujet handicapé
	<?php endif; ?>

	<?php if (Compte::checkPermission(Compte::PERM_WRITE, $operation->getId())) : ?>
		<a class="btn btn-primary btn-sm" href="/public/sujet/ajout/<?= $operation->getId(); ?>">
			Ajouter des sujets <i class="bi bi-plus-circle-fill"></i>
		</a>
	<?php endif; ?>
</h1>

<!-- Tableau des sujets -->
<?php if (!empty($sujets)) : ?>
	<div class="row">

		<div class="table-responsive">
			<div class="table-scroll" style="height: auto; max-height: 600px;">
				<table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
					<thead>
						<tr class="text-center">
							<th scope="col">État</th>
							<th scope="col">Numéro</th>
							<th scope="col">Nom</th>
							<th scope="col" class="pc-only">Sexe</th>
							<th scope="col" class="pc-only">Datation</th>
							<th scope="col" class="pc-only">Milieu de vie</th>
							<th scope="col" class="pc-only">Type de dépôt</th>
							<th scope="col" class="pc-only">Type de sépulture</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 0;
						asort($sujets);
						foreach ($sujets as $sujet) :
							$i++;
							$typeDepot = Typedepot::fetchSingle($sujet->getIdTypeDepot());
							$typeSepulture = Typesepulture::fetchSingle($sujet->getIdTypeSepulture());
						?>
							<tr class="text-center">
								<td>
									<?php if ($sujet->getComplet()) : ?>
										<i class="bi bi-check-circle-fill" title="La fiche est complète"></i>
									<?php else : ?>
										<i class="bi bi-exclamation-diamond-fill" title="La fiche n'est pas complète"></i>
									<?php endif; ?>
								</td>
								<td><?= $sujet->getId() ?></td>
								<td>
									<?= $sujet->getIdSujetHandicape() ?>
									<?php if (!empty($sujet->getUrlsImg())) : ?>
										<i class="bi bi-images opacity-50"></i>
									<?php endif; ?>
								</td>
								<td class="pc-only"><?= $sujet->getSexe() ?></td>
								<td class="pc-only">
									<?php
									$arr = array();
									if ($sujet->getDateMin() !== null) $arr[] = $sujet->getDateMin();
									if ($sujet->getDateMax() !== null) $arr[] = $sujet->getDateMax();
									echo Dataview::dataToView($arr, function ($value) {
										if (count($value) === 2 && $value[0] === $value[1]) return $value[0];
										return implode(" - ", $value);
									}, false);
									?>
								</td>
								<td class="pc-only"><?= Dataview::dataToView($sujet->getMilieuVie(), null, false) ?></td>
								<td class="pc-only"><?= $typeDepot->getNom() ?></td>
								<td class="pc-only"><?= $typeSepulture->getNom() ?></td>
								<td class="col-auto">

									<a title="Consulter #<?= $sujet->getId(); ?>" href="/public/sujet/description/<?= $sujet->getId(); ?>">Consulter</a>

									<?php if (Compte::checkPermission(Compte::PERM_WRITE, $operation->getId())) : ?>
										<br>
										<a title="Editer #<?= $sujet->getId(); ?>" href="/public/sujet/edition/<?= $sujet->getId(); ?>">Modifier</a>

										<?= Form::open(array("method" => "POST")); ?>
										<a href="" data-bs-toggle="modal" data-bs-target="#validationPopup" onclick="deleteSubject(<?= $sujet->getId(); ?>)">Supprimer</a>
										<?= Form::close(); ?>
									<?php endif; ?>

								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Popup suppression du sujet handicapé -->
	<div class="modal" id="validationPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="validationPopupLabel" aria-hidden="true" style="z-index: 100000;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="validationPopupLabel">Suppression du sujet</h5>
				</div>
				<div class="modal-body">
					<p>
						Êtes-vous sûr de vouloir supprimer le sujet ?<br>
						<br>
						<i class='bi bi-info-circle-fill'></i> La suppression est irréversible.
					</p>
				</div>
				<div class="modal-footer">
					<form method="post" action="/public/sujet/delete">
						<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#validationPopup">Retour</button>
						<button type="submit" name="id" id="form_id" value="" class="btn btn-danger">Supprimer</button>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
