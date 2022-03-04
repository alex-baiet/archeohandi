<?php

use Fuel\Core\View;
use Model\Db\Operation;

/** @var Operation */
$operation = $operation;
/** @var string|null */
$errors = $errors;

?>

<div class="container">
	
	<a class="btn btn-secondary mt-2" href="/public/operations" role="button">Retour</a>
	<h1 class="m-2">Modification de l'opération n°<?= $operation->getId() ?>
		<a class="btn btn-primary btn-sm" href="/public/sujet/add/<?= $operation->getId(); ?>" style="margin-right: 10px">
			Ajouter des sujets <i class="bi bi-plus-circle-fill"></i>
		</a>
		<a class="btn btn-success btn-sm" href="/public/operations/view/<?= $operation->getId(); ?>">
			Voir/modifier les sujets <i class="bi bi-arrow-up-right-circle-fill"></i>
		</a>
	</h1>	

	<p class="text-muted">
		Opération <b><?= $operation->getNomOp() ?></b><br>
		Pour plus d'informations sur un champ, laissez la souris au dessus du champ pour afficher un texte d'aide.
	</p>

	<form action="/public/operations/edit/<?= $operation->getId() ?>" method="post" style="background-color: #F5F5F5; padding: 10px;">
		<?= View::forge("operations/form", array("operation" => $operation)); ?>

		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" class="btn btn-success">Confirmer</button>
			</div>
		</div>
	</form>
</div>

