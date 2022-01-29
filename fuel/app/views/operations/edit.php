<?php

use Fuel\Core\View;
use Model\Db\Operation;

/** @var Operation */
$operation = $operation;
/** @var string|null */
$errors = $errors;

?>

<div class="container">
	
	<!-- Entête de la page -->
	<h1 class="m-2">Modification d'une opération</h1>
	<p class="text-muted">Opération <b><?= $operation->getNomOp() ?></b></p>

	<?= View::forge("operations/form", array("action" => "operations/edit/{$operation->getId()}", "operation" => $operation)); ?>
	
</div>

