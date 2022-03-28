<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Organisme;
use Model\Db\Typedepot;
use Model\Db\Typesepulture;
use Model\Helper;

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

<h2>
	<?php if (!empty($sujets)) : ?>
		Sujets handicapés (<?= count($sujets); ?>)
	<?php else : ?>
		Aucun sujet handicapé
	<?php endif; ?>

	<?php if (Compte::checkPermission(Compte::PERM_WRITE, $operation->getId())) : ?>
		<a class="btn btn-primary btn-sm" href="/public/sujet/add/<?= $operation->getId(); ?>">
			Ajouter des sujets <i class="bi bi-plus-circle-fill"></i>
		</a>
	<?php endif; ?>
</h2>

<!-- Tableau des sujets -->
<?php if (!empty($sujets)) : ?>
	<div class="row">

		<div class="table-responsive">
			<div class="scrollbar_view" style="height: auto; max-height: 600px;">
				<table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
					<thead>
						<tr class="text-center">
							<th scope="col">État</th>
							<th scope="col">Numéro</th>
							<th scope="col">Nom</th>
							<th scope="col">Sexe</th>
							<th scope="col">Datation</th>
							<th scope="col">Milieu de vie</th>
							<th scope="col">Type de dépôt</th>
							<th scope="col">Type de sépulture</th>
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
								<td><?= $sujet->getIdSujetHandicape() ?></td>
								<td><?= $sujet->getSexe() ?></td>
								<td><?= "{$sujet->getDatingMin()} - {$sujet->getDatingMax()}" ?></td>
								<td><?= $sujet->getMilieuVie() ?></td>
								<td><?= $typeDepot->getNom() ?></td>
								<td><?= $typeSepulture->getNom() ?></td>
								<td class="col-auto">

									<a title="Consulter #<?= $sujet->getId(); ?>" href="/public/sujet/view/<?= $sujet->getId(); ?>">Consulter</a>

									<?php if (Compte::checkPermission(Compte::PERM_WRITE, $operation->getId())) : ?>
										<br>
										<a title="Editer #<?= $sujet->getId(); ?>" href="/public/sujet/edit/<?= $sujet->getId(); ?>">Modifier</a>

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
						Êtes-vous sûr de vouloir supprimer le sujet ?
						<br><br>
						<?php if (isset($infoText)) : ?>
							<i class='bi bi-info-circle-fill'></i> La suppression est irréversible.
						<?php endif; ?>
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

<?= Asset::css('scrollbar.css'); ?>