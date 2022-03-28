<?php

use Fuel\Core\View;

?>

<div class="container" style="position: relative;">

	<h1 class="mt-4">Recherche d'opération et de sujet</h1>

	<form action="/public/recherche/resultat" method="post">
		<div id="form_operation" class="form-sheet">
			<h3 class="text-center">Opération</h3>
			<?= View::forge("recherche/form_operation", array("options" => $options)) ?>
		</div>

		<hr>

		<div id="form_sujet" class="form-sheet">
			<h3 class="text-center">Sujet handicapé</h3>
			<?= View::forge("recherche/form_sujet", array("options" => $options)) ?>
		</div>

		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" name="search" value="1" class="btn btn-success">Rechercher</button>
			</div>
		</div>
	</form>

</div>