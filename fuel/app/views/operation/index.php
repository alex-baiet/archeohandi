<?php

use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;

/** @var Operation[] */
$lines = $lines;
/** @var int */
$countSubject = $countSubject;
/** @var int */
$countOp = $countOp;

?>

<!-- Entête de la page -->
<div class="container">

	<!-- Titre principal de la page -->
	<h1 class="m-2">Opérations
		<!-- Bouton "Ajout d'un opération -->
		<?php if (Compte::checkPermission(Compte::PERM_WRITE)) : ?>
			<a class="btn btn-primary btn-sm" href="/public/operation/add">Créer une opération <i class="bi bi-plus-circle-fill"></i></a>
		<?php endif; ?>
	</h1>

	<p class="text-muted">
		Ici vous pouvez retrouver toutes les informations sur les opérations.<br>
		<b><?= $countOp ?></b> opérations existantes pour un total de <b><?= $countSubject ?></b> sujets enregistrés.<br>
	</p>

	<br />
	<!-- Contenu de la page -->
	<div class="table-scroll">
		<?= View::forge("operation/table", array("lines" => $lines)) ?>
	</div>
</div>