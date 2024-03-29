<?php


use Fuel\Core\View;
use Model\Db\Sujethandicape;

/** @var Sujethandicape */
$subject = $subject;

?>

<h1 class="m-2">Modifier le sujet N°<?= $subject->getId(); ?>
	<a class="btn btn-sm btn-secondary" href="/public/sujet/edition/<?= $subject->getId(); ?>">Réinitialiser les champs <i class="bi bi-arrow-repeat"></i></a>
</h1>
<p class="text-muted">
	Sujet de l'opération <b><em>"<?= $subject->getOperation()->getNomOp() ?>"</em></b><br>
	Pour plus d'informations sur un champ, laissez la souris au dessus du champ pour afficher un texte d'aide.
</p>

<form action="" method="post" class="form-sheet" onsubmit="prepareFormSend()">
	<?= View::forge("sujet/form", array("subject" => $subject)); ?>

	<div class="row" style="margin-top: 10px;">
		<div class="d-md-flex justify-content-md-end col">
			<button type="submit" name="stayOnPage" value="0" class="btn btn-success">Confirmer</button>
		</div>
	</div>
</form>