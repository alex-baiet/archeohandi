<?php

use Fuel\Core\View;
use Model\Searchresult;

/** @var Searchresult[] Liste des opérations à afficher. */
$lines = $lines;

$countOperation = count($lines);
$countSubject = 0;
foreach ($lines as $data) {
	$countSubject += count($data->subjects);
}

?>

<div class="container">

	<h1 class="mt-4">
		Page des opérations personnels
		<a class="btn btn-primary btn-sm" href="/public/operation/ajout">Créer une opération <i class="bi bi-plus-circle-fill"></i></a>
	</h1>

	<p class="text-muted mb-4">
		Vous trouverez ici toutes les opérations sur lesquels vous avez des droits.<br>
		<b><?= $countOperation ?></b> opérations et <b><?= $countSubject ?></b> sujets handicapés correspondent à votre recherche.
	</p>
	
	<div class="table-scroll">
		<?= View::forge("operation/table", array("lines" => $lines)) ?>
	</div>
</div>
	

