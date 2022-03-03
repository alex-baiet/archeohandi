<?php

use Model\Searchresult;

/** @var Searchresult[] */
$results = $results;

?>

<div class="container">

	<h1 class="my-4">Résultats de la recherche</h1>

	<table class="table table-bordered sticky">
		<thead>
			<tr class="text-center">
				<th>#</th>
				<th>Id BDD</th>
				<th>Auteur de la saisie</th>
				<th>Nom du site</th>
				<th>Année</th>
				<th>Latitude</th>
				<th>Longitude</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($results as $res) :
				if (!empty($res->subjects)) :
					$op = $res->operation
			?>
					<?php /* Affichage de l'opération */ ?>
					<tr class="text-center">
						<td class="btn-fold" id="btn_<?= $op->getId() ?>" onclick="NestedTable.switchTableView(`row_subjects_<?= $op->getId() ?>`, `btn_<?= $op->getId() ?>`)"></td>
						<td><?= $op->getId() ?></td>
						<?php
						$author = "";
						$account = $op->getAccountAdmin();
						if ($account !== null) {
							$author = $account->getPrenom() . " " . $account->getNom();
						}
						?>
						<td><?= $author ?></td>
						<td><?= $op->getNomOp() ?></td>
						<td><?= $op->getAnnee() ?></td>
						<td><?= $op->getY() ?></td>
						<td><?= $op->getX() ?></td>
						<td><a href="/public/operations/view/<?= $op->getId() ?>">Consulter</a></td>
					</tr>
					<tr id="row_subjects_<?= $op->getId() ?>" style="display:none; background-color: #ccc;">
						<td colspan="100%">

							<?php /* Tableau des sujets */ ?>
							<table class="table table-bordered" style="background-color: white;">
								<thead>
									<tr class="text-center">
										<th>Numéro</th>
										<th>Nom</th>
										<th>Sexe</th>
										<th>Datation</th>
										<th>Milieu de vie</th>
										<th>Type de dépôt</th>
										<th>Type de sépulture</th>
										<th>Age</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($res->subjects as $subject) : ?>
										<?php /* Affichage d'un sujet */ ?>
										<tr class="text-center">
											<td><?= $subject->getId() ?></td>
											<td><?= $subject->getIdSujetHandicape() ?></td>
											<td><?= $subject->getSexe() ?></td>
											<td><?= $subject->getDatingMin() . " - " . $subject->getDatingMax() ?></td>
											<td><?= $subject->getMilieuVie() ?></td>
											<td><?= $subject->getTypeDepot()->getNom() ?></td>
											<td><?= $subject->getTypeSepulture()->getNom() ?></td>
											<td><?= $subject->getAgeMin() . " - " . $subject->getAgeMax() ?></td>
											<td><a href="/public/sujet/view/<?= $subject->getId() ?>">Consulter</a></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>

						</td>
					</tr>
			<?php
				endif;
			endforeach;
			?>
		</tbody>
	</table>
</div>