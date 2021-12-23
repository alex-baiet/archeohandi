<?php

use Fuel\Core\View;
use Model\Operation;

/** @var Operation */
$operation = $operation;
/** @var string|null */
$errors = $errors;

?>

<div class="container" style="background-color: #F5F5F5;">
	<!-- Entête de la page -->
	<h1 class="m-2">Modifier l'opération <?= $operation->getNomOp() ?>
		<a class="btn btn-sm btn-secondary" href="/public/operations/edit/<?= $operation->getIdSite(); ?>">Rafraichir la page
			<i class="bi bi-arrow-repeat"></i>
		</a>
	</h1>
	<p class="text-muted">Ici vous pouvez modifier une opération.</p>

	<?= View::forge("operations/form", array("operation" => $operation)); ?>
</div>

