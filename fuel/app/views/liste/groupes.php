<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Model\Db\Archeo;
use Model\Db\Chronology;
use Model\Db\Operation;

/** @var Groupesujet[] */
$groups = $groups;

?>
<!-- Entête de la page -->
<div class="container col-auto">
	<h1 class="m-2">Liste des groupes</h1>
	<p class="text-muted">Ici vous retrouvez toutes les informations sur les groupes de sujet.</p>
	<button type="button" id="id_bouton_filtre" class="btn btn-danger">Afficher les filtres de recherche</button>
	
	<!-- Système de recherche (filtre) -->
	<?php
	$selectAttr = array(
		"class" => "form-select custom-select my-1 mr-2",
		"style" => "width:15em"
	);
	?>
	<div name="filtres_recherche" id="filtres_recherche" class="d-none mt-3">
		<?=
		Form::open(array(
			"action" => "/liste/groupes",
			"method" => "GET",
			
		));
		?>
		<div class="form-group ml-3">

			<!-- Champ opération -->
			<?= Operation::generateSelect("id_operation", "Operation", !empty($_GET["id_operation"]) ? intval($_GET["id_operation"]) : "", false); ?>

			<!-- Champ nom de chronologie -->
			<?= Chronology::generateSelect("id_chronology", "Chronologie", !empty($_GET["id_chronology"]) ? intval($_GET["id_chronology"]) : "", false, true); ?>

			<!-- Champ NMI -->
			<?php
			$valueTrans = function ($data) { return $data["NMI"]; };
			echo Archeo::generateSelect("nmi", "NMI", !empty($_GET["nmi"]) ? intval($_GET["nmi"]) : "", "groupe_sujets", $valueTrans, $valueTrans, false);
			?>

			<?= Form::submit('search', 'Rechercher', array('class' => 'btn btn-success btn-sm')); ?>
		</div>
		<?= Form::close(); ?>
	</div>
</div>
<br />

<!-- Contenu principal de la page -->
<div class="container">
	<div class="row">
		<div class="table-responsive">
			<div class="scrollbar_index">
				<table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
					<thead>
						<tr class="text-center">
							<th scope="col">Chronology</th>
							<th scope="col">Date de début</th>
							<th scope="col">Date de fin</th>
							<th scope="col">Opération</th>
							<th scope="col">NMI</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($groups as $group) : ?>
							<?php if ($group->getOperation() !== null) : ?>
								<tr class="text-center">
									<td><?= $group->getChronology()->getName(); ?></td>
									<td><?= $group->getChronology()->getStart(); ?></td>
									<td><?= $group->getChronology()->getEnd(); ?></td>
									<td><?= $group->getOperation()->getNomOp(); ?></td>
									<td><?= $group->getNMI(); ?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?= Asset::css('scrollbar.css'); ?>
<!-- Script permet d'afficher ou non les options du filtre -->
<script>
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