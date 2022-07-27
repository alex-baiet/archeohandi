<?php

use Model\Db\Compte;
use Model\Helper;

/** @var Compte[] */
$accounts = $accounts;
/** @var array */
$adminCounters = $adminCounters;
/** @var array */
$writeCounters = $writeCounters;

?>

<div class="container">
	<h1 class="my-3">Liste des comptes</h1>

	<div class="table-responsive">
		<div class="table-scroll" style="height: auto; max-height: 600px;">
			<table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
				<thead>
					<tr class="text-center">
						<th scope="col">Login</th>
						<th scope="col">Email</th>
						<th scope="col">Organisme</th>
						<th scope="col" title="Nombre d'opérations sur lesquels l'utilisateur est le créateur.">Opération admin</th>
						<th scope="col" title="Nombre d'opérations sur lesquels l'utilisateur à les droits d'édition.">Opération édition</th>
						<th scope="col">Date de création</th>
						<!-- <th scope="col">Actions</th> -->
					</tr>
				</thead>
				<tbody>
					<?php foreach ($accounts as $account) : ?>
						<tr class="text-center">
							<td><?= $account->getLogin() ?></td>
							<td><?= $account->getEmail() ?></td>
							<td><?= $account->getOrganisme() ?></td>
							<td><?= Helper::arrayGetValue($account->getLogin(), $adminCounters, 0) ?></td>
							<td><?= Helper::arrayGetValue($account->getLogin(), $writeCounters, 0) ?></td>
							<td><?= $account->getCreation() ? Helper::dbDateBeautify($account->getCreation()) : "< 27/07/2022" ?></td>
							<!-- <td><a href="#" class="btn btn-danger">Supprimer</a></td> -->
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>