<?php

use Fuel\Core\Form;

$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off");

?>
<div class="container">
	<h1 class="m-2">Création d'un compte</h1>

	<p class="text-muted">Vous pouvez demander la création d'un compte via ce formulaire.</p>

	<form action="" method="POST" style="background-color: #F5F5F5; padding: 10px;">

		<!-- Message à envoyer -->
		<div class="row my-2">
			<div class="col-md-6">
				<div class="form-floating">
					<?= Form::input("prenom", null, $defaultAttr); ?>
					<?= Form::label("Prénom", "prenom"); ?>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-floating">
					<?= Form::input("nom", null, $defaultAttr); ?>
					<?= Form::label("Nom", "nom"); ?>
				</div>
			</div>
		</div>

		<!-- Message à envoyer -->
		<div class="row my-2">
			<div class="col-md">
				<?= Form::label("Message", "msg"); ?>
				<?= Form::textarea("msg", null, $defaultAttr); ?>
			</div>
		</div>
		
		<!-- Boutons de confirmation -->
		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" name="create" class="btn btn-success">Demander l'accès</button>
			</div>
		</div>

	</form>
</div>