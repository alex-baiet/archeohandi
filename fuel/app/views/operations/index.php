<?php

use Fuel\Core\Asset;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;

/** @var Operation[] */
$operations = $operations;
/** @var int */
$countSubject = $countSubject;
/** @var int */
$countOp = $countOp;

?>

<!-- Entête de la page -->
<div class="container">

	<!-- Titre principal de la page -->
	<h1 class="m-2">Opérations
		<!-- Bouton "Ajout d'un opération -->
		<?php if (Compte::checkPermission(Compte::PERM_WRITE)) : ?>
			<a class="btn btn-primary btn-sm" href="/public/operations/add">Ajouter une opération <i class="bi bi-plus-circle-fill"></i></a>
		<?php endif; ?>
	</h1>

	<p class="text-muted">
		Ici vous pouvez retrouver toutes les informations sur les opérations.<br>
		<b><?= $countOp ?></b> opérations existantes pour un total de <b><?= $countSubject ?></b> sujets enregistrés.<br>
		Pour éditer les sujets d'une opération, veuillez d'abord <b>consulter</b> l'opération en question puis vous pourrez éditer ses sujets.
	</p>

	<br />
	<!-- Contenu de la page -->
	<div class="row">
		<div class="table-responsive">
			<div class="scrollbar_index">
				<table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
					<thead>
						<tr class="text-center">
							<!-- <th scope="col">#</th> -->
							<th scope="col">Etat</th>
							<th scope="col">Auteur de la saisie</th>
							<th scope="col">Date de saisie</th>
							<th scope="col">Nom du site</th>
							<th scope="col">Année</th>
							<?php if (Compte::checkPermission(Compte::PERM_WRITE)) : ?>
								<th scope="col">Actions</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($operations as $op) :  ?>
							<tr class="text-center">
								<td>
									<?php if ($op->getComplet()) : ?>
										<i class="bi bi-check-circle-fill" title="La fiche est complète"></i>
									<?php else : ?>
										<i class="bi bi-exclamation-diamond-fill" title="La fiche n'est pas complète"></i>
									<?php endif; ?>
								</td>
								<?php $author = $op->getAccountAdmin() ?>
								<td><?= $author !== null ? "{$author->getPrenom()} {$author->getNom()}" : null ?></td>
								<td><?= $op->getDateAjout() ?></td>
								<td><?= $op->getNomOp() ?></td>
								<td><?= $op->getAnnee() ?></td>
	
								<?php if (Compte::checkPermission(Compte::PERM_WRITE)) : ?>
									<td class="col-auto">
										<a title="Consulter #<?= $op->getId(); ?>" href="/public/operations/view/<?= $op->getId() ?>">
											Consulter
											<?= ""//Asset::img("reply.svg", array("class"=>"icon see", "width" => "30px", "alt" => "Consulter")) ?>
										</a>

										<?php if (Compte::checkPermission(Compte::PERM_ADMIN, $op->getId())) : ?>
											<br>
											<a class="" title="Editer #<?= $op->getId(); ?>" href="/public/operations/edit/<?= $op->getId() ?>">
												Editer
												<?= ""//Asset::img("pen.svg", array("class"=>"icon edit", "width" => "24px", "alt" => "Éditer")) ?>
											</a>

											<form action="" method="post" id="form_suppr_<?= $op->getId() ?>">
												<a href="" data-bs-toggle="modal" data-bs-target="#validationPopup"
													onclick="deleteOperation(<?= $op->getId() ?>)">
													Supprimer
													<?= ""//Asset::img("trash.svg", array("class"=>"icon del", "width" => "25px", "alt" => "Supprimer")) ?>
												</a>
											</form>
										<?php endif; ?>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?= View::forge("fonction/popup_confirm", array(
	"title" => "Voulez-vous continuer ?",
	"bodyText" => "Êtes-vous sûr de vouloir supprimer l'opération ?",
	"infoText" => "La suppression est irréversible.",
	"btnName" => "delete_op"
)); ?>

<?php
//Fonction permettant d'afficher un message d'alert
function alertBootstrap($text, $color)
{
	echo '<div class="alert alert-' . $color . ' alert-dismissible text-center my-2 fade show" role="alert">
	' . $text . '
	<button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
	</div>';
} ?>
<?= Asset::css('scrollbar.css'); ?>