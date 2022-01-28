<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\View;
use Model\Compte;
use Model\Operation;

/** @var Operation[] */
$operations = $operations;
/** @var array */
$all_site = $all_site;
/** @var array */
$all_user = $all_user;
/** @var array */
$all_nom_op = $all_nom_op;
/** @var array */
$all_annee = $all_annee;
/** @var int */
$countSubject = $countSubject;

?>

<script type="text/javascript">
	function deleteOperation(idSubject) {
		let btnElem = document.getElementById("form_delete_op");
		btnElem.value = idSubject;
	}
</script>

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
		Ici vous pouvez retrouvez toutes les informations sur les opérations.<br>
		<b><?= count($operations); ?></b> opérations existantes pour un total de <b><?= $countSubject; ?></b> sujets enregistrés.
	</p>

	<div class="ml-3">
		<button type="button" id="id_bouton_filtre" class="btn btn-danger">Afficher les filtres de recherche</button>

		<!-- Système de recherche (filtre) -->
		<?php
			/** @var array Tous les attributs communs des select. */
			$selectAttr = array(
				"class" => "form-select custom-select my-1 mr-2",
				"style" => "width:15em"
			);
		?>

		<div name="filtres_recherche" id="filtres_recherche" class="d-none mt-3">
			<?= Form::open(array("action" => "/operations", "method" => "GET")); ?>
			<div class="form-group ml-3">

				<!-- Champ ID de l'operation -->
				<div class="form-check form-check-inline">
					<?= Form::label("Id de l'opération", 'filter_id'); ?>
					<?= Form::select("filter_id", "", $all_site, $selectAttr); ?>
				</div>

				<!-- Champ recherche par utilisateur -->
				<div class="form-check form-check-inline">
					<?= Form::label('Créateur', 'filter_user'); ?>
					<?= Form::select("filter_user", "", $all_user, $selectAttr); ?>
				</div>

				<!-- champ nom de l'opération -->
				<div class="form-check form-check-inline">
					<?= Form::label("Nom de l'opération", 'filter_op'); ?>
					<?= Form::select("filter_op", "", $all_nom_op, $selectAttr); ?>
				</div>

				<!-- Champ année -->
				<div class="form-check form-check-inline">
					<?= Form::label('Année', 'filter_year'); ?>
					<?= Form::select("filter_year", "", $all_annee, $selectAttr); ?>
				</div>

				<?= Form::submit(null, 'Rechercher', array('class' => 'btn btn-success btn-sm')); ?>
			</div>
		</div>
		<?= Form::close(); ?>
	</div>

</div>
<br />
<!-- Contenu de la page -->
<div class="container">
	<div class="row">
		<div class="table-responsive">
			<div class="scrollbar_index">
				<table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
					<thead>
						<tr class="text-center">
							<!-- <th scope="col">#</th> -->
							<th scope="col">Utilisateur de la saisie</th>
							<th scope="col">Nom</th>
							<th scope="col">Année</th>
							<th scope="col">Position X</th>
							<th scope="col">Position Y</th>
							<th scope="col">Options</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($operations as $op) :  ?>
							<tr class="text-center">
								<td><?= $op->getAccountAdmin() !== null ? $op->getAccountAdmin()->getLogin() : null ?></td>
								<td><?= $op->getNomOp() ?></td>
								<td><?= $op->getAnnee() ?></td>
								<td><?= $op->getX() ?></td>
								<td><?= $op->getY() ?></td>
								<td class="col-auto">

									<a title="Consulter #<?= $op->getId(); ?>" href="/public/operations/view/<?= $op->getId() ?>">
										<?= Asset::img("reply.svg", array("class"=>"icon see", "width" => "30px", "alt" => "Consulter")) ?>
									</a>

									<?php if (Compte::checkPermission(Compte::PERM_ADMIN, $op->getId())) : ?>
										<a class="" title="Editer #<?= $op->getId(); ?>" href="/public/operations/edit/<?= $op->getId() ?>">
											<?= Asset::img("pen.svg", array("class"=>"icon edit", "width" => "24px", "alt" => "Éditer")) ?>
										</a>

										<form action="" method="post" id="form_suppr_<?= $op->getId() ?>">
											<button
													type="button"
													class="btn"
													data-bs-toggle="modal"
													data-bs-target="#validationPopup"
													onclick="deleteOperation(<?= $op->getId() ?>)">
												<?= Asset::img("trash.svg", array("class"=>"icon del", "width" => "25px", "alt" => "Supprimer")) ?>
											</button>
										</form>
									<?php endif; ?>

								</td>
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

<script>
	//Script permet d'afficher ou non les options du filtre
	$("#id_bouton_filtre").click(function() {
		if ($("#filtres_recherche").hasClass("d-none")) {
			$("#id_bouton_filtre").text("Masquer les filtres de recherche");
			$("#filtres_recherche").removeClass("d-none");
		} else {
			$("#id_bouton_filtre").text("Afficher les filtres de recherche");
			$("#filtres_recherche").addClass("d-none");
		}
	});
</script>

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