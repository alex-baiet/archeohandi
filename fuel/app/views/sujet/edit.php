<?php


use Fuel\Core\View;
use Model\Db\Sujethandicape;

/** @var Sujethandicape */
$subject = $subject;

?>

<!-- Entête de la page -->
<div class="container">
	<h1 class="m-2">Modifier le sujet N°<?= $subject->getId(); ?>
		<a class="btn btn-sm btn-secondary" href="/public/sujet/edit/<?= $subject->getId(); ?>">Rafraichir la page
			<i class="bi bi-arrow-repeat"></i>
		</a>
	</h1>
	<p class="text-muted">
		Pour plus d'informations sur un champ, laissez la souris au dessus du champ pour afficher un texte d'aide.
	</p>

	<?= View::forge("sujet/form", array("subject" => $subject)); ?>
</div>

