<?php

use Model\Db\Operation;
use Model\Db\Sujethandicape;
use Model\Searchresult;

/** @var Searchresult[] */
$results = $results;

?>

<div class="container">
	<table class="table table-striped table-hover table-bordered sticky">
		<thead>
			<th>Identifiant</th>
			<th>Nom</th>
		</thead>
		<tbody>
			<?php foreach ($results as $res) : ?>
				<?php // Affichage de l'opération ?>
				<tr>
					<td><?= $res->operation->getId() ?></td>
					<td><?= $res->operation->getNomOp() ?></td>
				</tr>
				<?php // Tableau des sujets ?>
				<tr>
					<td colspan="100%">
						<table class="table">
							<thead>
								<tr>Id BDD</tr>
								<tr>Identifiant</tr>
							</thead>
							<tbody>
								<?php foreach ($res->subjects as $subject) : ?>
									<tr>
										<td><?= $subject->getId() ?></td>
										<td><?= $subject->getIdSujetHandicape() ?></td>
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
