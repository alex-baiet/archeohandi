<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;

?>

<!-- Entête de la page -->
<div class="container">
	
	<!-- Titre principal de la page -->
	<h1 class="m-2">Opérations (<?= count($operations); ?>)</h1>

	<!-- Bouton "Ajout d'un opération -->
	<a class="btn btn-primary btn-sm" href="/public/add/operation">Ajouter une opération<i class="bi bi-plus-circle-fill"></i></a>
	
	<p class="text-muted">Ici vous retrouvez toutes les informations sur les opérations.</p>

	<div class="ml-3">
		<button type="button" id="id_bouton_filtre" class="btn btn-danger">Afficher les filtres de recherche</button>
		<a class="btn btn-secondary" href="/public/operations">Rafraichir la page
			<i class="bi bi-arrow-repeat"></i>
		</a>
		
		<!-- Système de recherche (filtre) -->
		<?php
			/** @var array Tous les attributs communs des <select>. */
			$selectAttr = array(
				"class" => "form-select custom-select my-1 mr-2",
				"style" => "width:15em"
			);
		?>

		<div name="filtres_recherche" id="filtres_recherche" class="d-none mt-3">
			<?= Form::open('/operations'); ?>
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

	<?php
		//Permet de vérifier si dans l'url il y a les différentes options et si oui, cela appel une fonction qui permet d'afficher un message  
		array_key_exists('erreur_supp_op', $_GET) ? alertBootstrap('Le numéro de l\'opération n\'est pas correcte (nombres autorisés). La suppression ne peut pas s\'effectuer', 'danger') : null;
		array_key_exists('erreur_supp_bdd', $_GET) ? alertBootstrap('Le numéro de l\'opération n\'existe pas. La suppression ne peut pas s\'effectuer', 'danger') : null;

		array_key_exists('success_ajout', $_GET) ? alertBootstrap('Les ajouts ont été effectué', 'success') : null;
		array_key_exists('success_modif', $_GET) ? alertBootstrap('Modification effectuée', 'success') : null;
		array_key_exists('success_supp_op', $_GET) ? alertBootstrap('Suppression effectuée', 'success') : null;
	?>
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
							<th scope="col">Identifiant</th>
							<th scope="col">Utilisateur de la saisie</th>
							<th scope="col">Nom</th>
							<th scope="col">Année</th>
							<th scope="col">Position X</th>
							<th scope="col">Position Y</th>
							<th scope="col">Options</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($operations as $key) :  ?>
							<tr class="text-center">
								<?= '<td>' . $key['id_site'] . '</td>'; ?>
								<?= '<td>' . $key['id_user'] . '</td>'; ?>
								<?= '<td>' . $key['nom_op'] . '</td>'; ?>
								<?= '<td>' . $key['annee'] . '</td>'; ?>
								<?= '<td>' . $key['X'] . '</td>'; ?>
								<?= '<td>' . $key['Y'] . '</td>'; ?>
								<td class="col-auto">
									<a title="Consulter #<?= $key['id_site']; ?>" href="operations/view/<?= $key['id_site']; ?>">
										<?= Asset::img("reply.svg", array("class"=>"icon see", "width" => "30px", "alt" => "Consulter")) ?>
									</a>
									<a class="" title="Editer #<?= $key['id_site']; ?>" href="operations/edit/<?= $key['id_site']; ?>">
										<?= Asset::img("pen.svg", array("class"=>"icon edit", "width" => "24px", "alt" => "Éditer")) ?>
									</a>
									<form action="" method="post" id="form_suppr_<?= $key['id_site']; ?>">
										<button type="button" class="btn" name="btn_supp_op" value="<?= $key['id_site']; ?>">
											<?= Asset::img("trash.svg", array("class"=>"icon del", "width" => "25px", "alt" => "Supprimer")) ?>
											<input type="hidden" name="supp_op" value="<?= $key['id_site']; ?>">
										</button>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



<script>
	//Permet d'afficher un message d'alert avant la confirmation d'une suppression
	$("[name=btn_supp_op]").click(function() {
		var x = $(this).val();
		if (window.confirm("Vous êtes sur le point de supprimer une opération. La suppression d'une opération comprend aussi la suppression de tous sujets et groupes liés à cette opération. Êtes-vous sûr de supprimer l'opération " + x + " ?")) {
			$("#form_suppr_" + x).submit();
		}
	});
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