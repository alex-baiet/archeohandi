<?php
// View d'une liste d'opération et de leurs sujets.

use Model\Db\Archeo;
use Model\Searchresult;

/** @var Searchresult[] Liste des opérations à afficher. */
$lines = $lines;

?>

<table class="table table-bordered text-center">
	<thead>
		<tr>
			<th>#</th>
			<th>État</th>
			<th>Id</th>
			<th>Auteur de la saisie</th>
			<th>Nom du site</th>
			<th>Année</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($lines as $data) :
			$op = $data->operation
		?>
			<?php /* Affichage de l'opération */ ?>
			<tr>
				<td class="btn-fold" id="btn_<?= $op->getId() ?>" onclick="NestedTable.switchTableView(`row_subjects_<?= $op->getId() ?>`, `btn_<?= $op->getId() ?>`)"></td>
				<td><?= Archeo::getCompleteIcon($op->getComplet()) ?></td>
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
				<td><a href="/public/operation/view/<?= $op->getId() ?>">Consulter</a></td>
			</tr>
			<tr id="row_subjects_<?= $op->getId() ?>" style="display:none;">
				<td colspan="100%" style="
					border-color: black;
					border-width: 1px;
					padding: 0;"
				>

					<?php /* Tableau des sujets */ ?>
					<table class="table-child table table-bordered">
						<?php if (empty($data->subjects)) : ?>
							<thead>
								<tr>
									<th colspan="100%">Aucun sujets handicapés</th>
								</tr>
							</thead>
						<?php else : ?>
							<thead>
								<tr>
									<th colspan="100%">Sujets handicapés de l'opération</th>
								</tr>
								<tr>
									<th>État</th>
									<th>Id</th>
									<th>Nom</th>
									<th>Sexe</th>
									<th>Datation</th>
									<th>Milieu de vie</th>
									<th>Type de dépôt</th>
									<th>Type de sépulture</th>
									<th>Âge</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($data->subjects as $subject) : ?>
									<tr><?php /* Affichage d'un sujet */ ?>
										<td><?= Archeo::getCompleteIcon($subject->getComplet()) ?></td>
										<td><?= $subject->getId() ?></td>
										<td><?= $subject->getIdSujetHandicape() ?></td>
										<td><?= $subject->getSexe() ?></td>
										<td><?= $subject->getDateMin() . " - " . $subject->getDateMax() ?></td>
										<td><?= $subject->getMilieuVie() ?></td>
										<td><?= $subject->getTypeDepot()->getNom() ?></td>
										<td><?= $subject->getTypeSepulture()->getNom() ?></td>
										<td><?= $subject->getAgeMin() . " - " . $subject->getAgeMax() ?></td>
										<td><a href="/public/sujet/view/<?= $subject->getId() ?>">Consulter</a></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						<?php endif; ?>
					</table>

				</td>
			</tr>
		<?php
		endforeach;
		?>
	</tbody>
</table>