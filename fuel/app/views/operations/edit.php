<?php

use Fuel\Core\Asset;
use Fuel\Core\View;
use Model\Operation;

/** @var Operation */
$operation = $operation;
/** @var string|null */
$errors = $errors;

?>

<!-- Entête de la page -->
<div class="container">
	<h1 class="m-2">Modifier l'opération <?= $operation->getNomOp() ?>
		<a class="btn btn-sm btn-secondary" href="/public/operations/edit/<?= $operation->getIdSite(); ?>">Rafraichir la page
			<i class="bi bi-arrow-repeat"></i>
		</a>
	</h1>
	<p class="text-muted">Ici vous pouvez modifier une opération.</p>
</div>

<?=

View::forge("operations/form", array("operation" => $operation));
?>

<?php
Asset::js('script_recherche.js');
?>