<?php

use Model\Dataview;
use Model\Db\Compte;
use Model\Helper;

/** @var Compte[] */
$accounts = $accounts;
/** @var array */
$adminCounters = $adminCounters;
/** @var array */
$writeCounters = $writeCounters;

?>

<script>
	/** Prépare le popup pour supprimer le compte ayant le login indiqué. */
	function showConfirmPopup(login) {
		const txt = document.getElementById(`login_modal`);
		const btn = document.getElementById(`login_btn`);
		txt.innerText = login;
		btn.value = login;
	}
</script>

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
						<th scope="col">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($accounts as $account) : ?>
						<tr class="text-center">
							<td><?= $account->getLogin() ?></td>
							<td><?= $account->getEmail() ?></td>
							<td><?= Dataview::dataToView($account->getOrganisme(), null, false) ?></td>
							<td><?= Dataview::dataToView(Helper::arrayGetValue($account->getLogin(), $adminCounters, 0), null, false, "0") ?></td>
							<td><?= Dataview::dataToView(Helper::arrayGetValue($account->getLogin(), $writeCounters, 0), null, false, "0") ?></td>
							<td><?php
								$date = $account->getCreation() !== null ? Helper::dbDateBeautify($account->getCreation()) : "";
								echo Dataview::dataToView($date, null, false, "< 27/07/2022") 
							?></td>
							<td>
								<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="showConfirmPopup(`<?= $account->getLogin() ?>`)">
									Supprimer
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php /* Popup de suppression */ ?>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer le compte "<b id="login_modal"></b>" ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, retour</button>
				<form action="delete" method="post">
					<button type="submit" class="btn btn-danger" name="login" id="login_btn">Oui, supprimer</button>
				</form>
      </div>
    </div>
  </div>
</div>
