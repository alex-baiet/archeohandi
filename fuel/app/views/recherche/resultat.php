<?php

use Model\Searchresult;

/** @var Searchresult[] */
$results = $results;

?>

<div class="container">

	<h1 class="my-4">Résultats de la recherche</h1>

	<table class="table table-bordered sticky">
		<thead>
			<th>Id BDD</th>
			<th>Auteur de la saisie</th>
			<th>Nom du site</th>
			<th>Année</th>
			<th>Latitude</th>
			<th>Longitude</th>
		</thead>
		<tbody>
			<?php foreach ($results as $res) : $op = $res->operation ?>
				<?php /* Affichage de l'opération */ ?>
				<tr onclick="NestedTable.switchTableView(`row_subjects_<?= $op->getId() ?>`)">
					<td><?= $op->getId() ?></td>
					<?php
					$author = "";
					$account = $op->getAccountAdmin();
					if ($account !== null) {
						$author = $account->getPrenom()." ".$account->getNom();
					}
					?>
					<td><?= $author ?></td>
					<td><?= $op->getNomOp() ?></td>
					<td><?= $op->getAnnee() ?></td>
					<td><?= $op->getY() ?></td>
					<td><?= $op->getX() ?></td>
				</tr>
				<tr id="row_subjects_<?= $op->getId() ?>" style="display:none; background-color: #ccc;">
					<td colspan="100%">

						<?php /* Tableau des sujets */ ?>
						<table class="table table-bordered" style="background-color: white;">
							<thead>
								<th>Numéro</th>
								<th>Nom</th>
								<th>Sexe</th>
								<th>Datation</th>
								<th>Milieu de vie</th>
								<th>Type de dépôt</th>
								<th>Type de sépulture</th>
							</thead>
							<tbody>
								<?php foreach ($res->subjects as $subject) : ?>
									<?php /* Affichage d'un sujet */ ?>
									<tr>
										<td><?= $subject->getId() ?></td>
										<td><?= $subject->getIdSujetHandicape() ?></td>
										<td><?= $subject->getSexe() ?></td>
										<td><?= $subject->getDatingMin()." - ".$subject->getDatingMax() ?></td>
										<td><?= $subject->getMilieuVie() ?></td>
										<td><?= $subject->getTypeDepot()->getNom() ?></td>
										<td><?= $subject->getTypeSepulture()->getNom() ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>

					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>