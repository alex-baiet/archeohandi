<?php

use Fuel\Core\View;
use Model\Db\Operation;

/** @var Operation */
$operation = $operation;
/** @var string|null */
$errors = $errors;

?>

<h1 class="m-2">Modification de l'opération n°<?= $operation->getId() ?></h1>

<p class="text-muted">
	Pour plus d'informations sur un champ, laissez la souris au dessus du champ pour afficher un texte d'aide.
</p>

<form action="/public/operations/edit/<?= $operation->getId() ?>" method="post" class="form-sheet" autocomplete="off">
	<?= View::forge("operations/form", array("operation" => $operation)); ?>

	<div class="row">
		<div class="d-md-flex justify-content-md-end col">
			<button type="submit" class="btn btn-success">Confirmer</button>
		</div>
	</div>
</form>