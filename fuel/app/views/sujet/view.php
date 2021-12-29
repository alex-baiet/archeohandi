<?php

use Fuel\Core\DB;
use Model\Sujethandicape;

/** @var Sujethandicape */
$subject = $subject;

?>
<!-- Entête de la page -->
<div class="container">
	<h1 class="m-2">Sujet <?= $subject->getIdSujetHandicape(); ?></h1>
	<p class="text-muted">Ici vous retrouvez toutes les informations du sujet <strong><?= $subject->getIdSujetHandicape(); ?></strong>.</p>

	<!-- Contenu de la page -->
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Informations générales</h4>
		<div class="row">
			<div class="col">
				<div class="p-2">Âge minimum : <?= $subject->getAgeMin(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Âge maximum : <?= $subject->getAgeMax(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Sexe : <?= $subject->getSexe(); ?></div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="p-2">
					<?php if ($subject->getDatingMin() === $subject->getDatingMax()) : ?>
						Datation : <?= $subject->getDatingMin(); ?>
					<?php else : ?>
						Datation : entre <?= $subject->getDatingMin(); ?> et <?= $subject->getDatingMax(); ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="col">
				<!-- Ce n'est pas vraiment l'écart type qui est calculé mais bon... -->
				<div class="p-2">Écart type de la datation : <?= $subject->getDatingMax() - $subject->getDatingMin(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Milieu de vie : <?= $subject->getMilieuVie(); ?></div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="p-2">Contexte normatif : <?= $subject->getContexteNormatif(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Contexte : <?= $subject->getContexte(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Commentaire du contexte : <?= $subject->getCommentContext(); ?></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="p-2">Type de dépôt : <?= $subject->getTypeDepot()->getNom(); ?></div>
			</div>
			<div class="col-md-4">
				<div class="p-2">Type de sépulture : <?= $subject->getTypeSepulture()->getNom(); ?></div>
			</div>
		</div>
	</div>
	<br />
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Groupe du sujet</h4>
		<?php $group = $subject->getGroup(); ?>
		<div class="row">
			<div class="col-md-2">
				<div class="p-2">NMI : <?= $group->getNMI(); ?></div>
			</div>
			<div class="col-md-3">
				<div class="p-2">Opération : <?= "" //$nom_op; 
																			?></div>
			</div>
			<div class="col-md-3">
				<div class="p-2">Période : <?= "" //$info_chrono['nom_chronologie']; 
																		?></div>
			</div>
			<div class="col-md-2">
				<div class="p-2">Date de début : <?= "" //$info_chrono['date_debut']; 
																					?></div>
			</div>
			<div class="col-md-2">
				<div class="p-2">Date de fin : <?= "" //$info_chrono['date_fin']; 
																				?></div>
			</div>
		</div>
	</div>
	<br />

	<!-- Dépôt -->
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Dépôt</h4>
		<?php $depot = $subject->getDepot(); ?>
		<div class="row">
			<div class="col">
				<div class="p-2">Numéro d'inventaire : <?= ""//$depot->getNumInventaire(); ?>
				</div>
			</div>
			<div class="col">
				<div class="p-2">Commune : <?= ""//$depot->getCommune()->fullName() ?></div>
			</div>
			<div class="col">
				<div class="p-2">Adresse : <?= ""//$depot->getAdresse(); ?></div>
			</div>
		</div>
	</div>
	<br />

	<!-- Mobiliers / Accessoires -->
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Accessoire</h4>
		<div class="row">
			<div class="col">
				<div class="p-2">
					<?php foreach ($subject->getFurnitures() as $furniture) : ?>
						<?= $furniture->getNom(); ?><br>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<br />

	<!-- Diagnostics -->
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Atteinte invalidante</h4>
		<?php if (true) : ?>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th scope="col">Diagnostic</th>
							<th scope="col" class="text-center">Pathologie</th>
							<th scope="col" class="text-center">Localisation</th>
							<th scope="col" class="text-center">Appareil de compensation</th>
							<th scope="col" class="text-center">Description du Autre</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Pâtes</td>
							<td class="text-center">Obesité</td>
							<td class="text-center">Bide</td>
							<td class="text-center">De l'eau</td>
							<td class="text-center">C'est bon les pâtes</td>
						</tr>
						<?php //foreach ($sujet_atteinte as $key) :
						// 	$query = DB::query('SELECT nom FROM diagnostic WHERE id=' . $key['id_diagnostic'] . ' ');
						// 	$nom_diagnostic = $query->execute();
						// 	$nom_diagnostic = $nom_diagnostic->_results[0]['nom'];
						// 	$nom_patho = $description_autre = "";

						// 	if ($key['id_diagnostic'] == 9) :
						// 		$query = DB::query('SELECT type_pathologie FROM pathologie WHERE id_pathologie=' . $key['id_pathologie'] . ' ');
						// 		$nom_patho = $query->execute();
						// 		$nom_patho = $nom_patho->_results[0]['type_pathologie'];
						// 	endif;

						// 	if ($key['id_diagnostic'] == 13) : $description_autre = $key['description_autre'];
						// 	endif;

						// 	echo '<tr>';
						// 	echo '<td>' . $nom_diagnostic . '</td>';
						// 	echo '<td class="text-center">' . $nom_patho . '</td>';
						// 	echo '<td class="text-center">';

						// 	$query = DB::query('SELECT id_localisation_atteinte FROM localisation_sujet WHERE id_sujet_handicape=' . $key['id_sujet_handicape'] . ' AND id_diagnostic=' . $key['id_diagnostic'] . ' ');
						// 	$id_localisation_atteinte = $query->execute();
						// 	$id_localisation_atteinte = $id_localisation_atteinte->_results;

						// 	foreach ($id_localisation_atteinte as $key2 => $val) {
						// 		$query = DB::query('SELECT nom FROM localisation_atteinte WHERE id=' . $val['id_localisation_atteinte'] . ' ');
						// 		$nom_localisation_atteinte = $query->execute();
						// 		$nom_localisation_atteinte = $nom_localisation_atteinte->_results[0]['nom'];

						// 		echo $nom_localisation_atteinte . '<br/>';
						// 	}
						// 	echo '</td>';
						// 	echo '<td class="text-center">';

						// 	$query = DB::query('SELECT id_appareil_compensatoire FROM appareil_sujet WHERE id_sujet_handicape=' . $key['id_sujet_handicape'] . ' AND id_diagnostic=' . $key['id_diagnostic'] . ' ');
						// 	$id_appareil_compensatoire = $query->execute();
						// 	$id_appareil_compensatoire = $id_appareil_compensatoire->_results;

						// 	foreach ($id_appareil_compensatoire as $key2 => $val) {
						// 		$query = DB::query('SELECT type_appareil FROM appareil_compensatoire WHERE id_appareil_compensatoire=' . $val['id_appareil_compensatoire'] . ' ');
						// 		$nom_appareil = $query->execute();
						// 		$nom_appareil = $nom_appareil->_results[0]['type_appareil'];
						// 		echo $nom_appareil . '<br/>';
						// 	}
						// 	echo '</td>
						// <td class="text-center">';
						// 	echo $description_autre;
						// 	echo '</td>';
						// endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col">
					<div class="p-2">Commentaire du diagnostic : <?= $subject->getCommentDiagnosis(); ?></div>
				</div>
			</div>
		<?php else : ?>
			<div class="col">
				<div class="p-2">Aucune atteinte</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="d-grid gap-2 d-md-block p-1">
		<a class="btn btn-secondary" href="/public/operations/view/<?= $subject->getGroup()->getIdOperation(); ?>" role="button">Retour</a>
	</div>
</div>

	<!--  Permet d'afficher un message d'alert avant la confirmation d'une suppression -->
	<script type="text/javascript">
		$("[name=btn_supp_sujet]").click(function() {
			var x = $(this).val();
			if (window.confirm("Vous êtes sur le point de supprimer un sujet. Êtes-vous sûr de supprimer le sujet " + x + " ?")) {
				$("#form_suppr_" + x).submit();
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