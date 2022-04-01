<?php

use Fuel\Core\View;

/** @var Operation|unset */
$operation;

?>

<!-- Contenu de la page partie opération -->
<div class="container">

	<a class="btn btn-secondary mt-2" href="/public/operations" role="button">Retour</a>
	<h1 class="m-2">Ajout d'une opération</h1>

	<p class="text-muted">
		Ici vous pouvez ajouter une nouvelle opération.<br>
		Pour plus d'informations, laissez la souris au dessus du champ pour afficher un texte d'aide.
	</p>
	
	<form action="/public/operations/add" method="POST" class="form-sheet" autocomplete="off">
		<?php
		$data = array();
		if (isset($operation)) $data["operation"] = $operation;
		echo View::forge("operations/form", $data);
		?>
		
		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" class="btn btn-success">Confirmer</button>
			</div>
		</div>
	</form>	
</div>
