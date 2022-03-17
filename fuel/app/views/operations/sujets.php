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
		let btnElem = document.getElementById("form_delete_sujet");
		btnElem.value = idSubject;
	}
</script>

<!-- Tableau des sujets -->
<?php if (!empty($sujets)) : // Vérifie si l'opération sélectionnée possède des sujets et si oui les affiches et si non affiche aucun sujet 
?>
	<div class="row">
		<h2>
			Sujets handicapés (<?= count($sujets); ?>)
			<?php if (Compte::checkPermission(Compte::PERM_WRITE, $operation->getId())) : ?>
				<a class="btn btn-primary btn-sm" href="/public/sujet/add/<?= $operation->getId(); ?>">
					Ajouter des sujets <i class="bi bi-plus-circle-fill"></i>
				</a>
			<?php endif; ?>
		</h2>
		
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

	<!-- Popup de confirmation de suppression -->
	<?= View::forge("fonction/popup_confirm", array(
		"title" => "Voulez-vous continuer ?",
		"bodyText" => "Êtes-vous sûr de vouloir supprimer le sujet ?",
		"infoText" => "La suppression est irréversible.",
		"btnName" => "delete_sujet"
	)); ?>

<?php else : ?>

	<div class="container">
		<h2>Aucun sujet handicapé</h2>
	</div>

<?php endif; ?>

<?= Asset::css('scrollbar.css'); ?>