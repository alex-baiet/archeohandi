<?php


use Fuel\Core\View;
use Model\Db\Sujethandicape;

/** @var Sujethandicape */
$subject = $subject;

?>

<!-- Entête de la page -->
<div class="container">
	<a class="btn btn-secondary mt-2" href="/public/operations/view/<?= $subject->getGroup()->getIdOperation() ?>" role="button">Retour</a>
	
	<h1 class="m-2">Modifier le sujet N°<?= $subject->getId(); ?>
		<a class="btn btn-sm btn-secondary" href="/public/sujet/edit/<?= $subject->getId(); ?>">Réinitialiser la page <i class="bi bi-arrow-repeat"></i></a>
	</h1>
	<p class="text-muted">
		Pour plus d'informations sur un champ, laissez la souris au dessus du champ pour afficher un texte d'aide.
	</p>

	<?= View::forge("sujet/form", array("subject" => $subject)); ?>
</div>

