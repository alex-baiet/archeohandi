<?php

use Fuel\Core\View;
use Model\Searchresult;

/** @var Searchresult[] Liste des opérations à afficher. */
$lines = $lines;

?>

<div class="container">

	<h1 class="m-2">
		Page des opérations personnels
		<a class="btn btn-primary btn-sm" href="/public/operations/add">Ajouter une opération <i class="bi bi-plus-circle-fill"></i></a>
	</h1>

	<p class="text-muted">Vous trouverez ici toutes les opérations sur lesquels vous avez des droits.</p>
	<br>
	
	<?= View::forge("operations/table", array("lines" => $lines)) ?>
</div>
	

