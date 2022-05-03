<?php
// View d'une liste d'opération et de leurs sujets.

use Model\Dataview;
use Model\Db\Archeo;
use Model\Searchresult;

/** @var Searchresult[] Liste des opérations à afficher. */
$lines = $lines;

?>

<table class="table table-bordered text-center margin-0">
	<thead>
		<tr>
			<th>#</th>
			<th>État</th>
			<th>Id</th>
			<th class="pc-only">Auteur de la saisie</th>
			<th>Nom du site</th>
			<th class="pc-only">Année</th>
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
				<td class="pc-only"><?= Dataview::dataToView($author, null, false) ?></td>
				<td>
					<?= $op->getNomOp() ?>
					<?php if (!empty($op->getUrlsImg())) : ?>
						<i class="bi bi-images opacity-50"></i>
					<?php endif; ?>
				</td>
				<td class="pc-only"><?= Dataview::dataToView($op->getAnnee(), null, false) ?></td>
				<td><a href="/public/operation/description/<?= $op->getId() ?>">Consulter</a></td>
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
									<th class="pc-only">Sexe</th>
									<th>Datation</th>
									<th class="pc-only">Milieu de vie</th>
									<th class="pc-only">Type de dépôt</th>
									<th class="pc-only">Type de sépulture</th>
									<th class="pc-only">Âge</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($data->subjects as $subject) : ?>
									<tr><?php /* Affichage d'un sujet */ ?>
										<td><?= Archeo::getCompleteIcon($subject->getComplet()) ?></td>
										<td><?= $subject->getId() ?></td>
										<td>
											<?= $subject->getIdSujetHandicape() ?>
											<?php if (!empty($subject->getUrlsImg())) : ?>
												<i class="bi bi-images opacity-50"></i>
											<?php endif; ?>
										</td>
										<td class="pc-only"><?= $subject->getSexe() ?></td>
										<td>
											<?php
											$arr = array();
											if ($subject->getDateMin() !== null) $arr[] = $subject->getDateMin();
											if ($subject->getDateMax() !== null) $arr[] = $subject->getDateMax();
											echo Dataview::dataToView($arr, function ($value) {
												if (count($value) === 2 && $value[0] === $value[1]) return $value[0];
												return implode(" - ", $value);
											}, false);
											?>
										</td>
										<td class="pc-only"><?= Dataview::dataToView($subject->getMilieuVie(), null, false) ?></td>
										<td class="pc-only"><?= $subject->getTypeDepot()->getNom() ?></td>
										<td class="pc-only"><?= $subject->getTypeSepulture()->getNom() ?></td>
										<td class="pc-only">
											<?php
											$arr = array();
											if ($subject->getAgeMin() !== null) $arr[] = $subject->getAgeMin();
											if ($subject->getAgeMax() !== null) $arr[] = $subject->getAgeMax();
											echo Dataview::dataToView($arr, function ($value) {
												if (count($value) === 2 && $value[0] === $value[1]) return $value[0];
												return implode(" - ", $value);
											}, false);
											?>
										</td>
										<td><a href="/public/sujet/description/<?= $subject->getId() ?>">Consulter</a></td>
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