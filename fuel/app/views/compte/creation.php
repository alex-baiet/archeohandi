<?php

use Fuel\Core\Form;
use Model\Helper;

$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off");

?>
<div class="container">
	<h1 class="m-2">Création d'un compte</h1>

	<p class="text-muted">Vous pouvez demander la création d'un compte via ce formulaire.</p>

	<form action="" method="POST" style="background-color: #F5F5F5; padding: 10px;">

		<div class="row my-2">
			<!-- Prenom -->
			<div class="col-md-6">
				<div class="form-floating">
					<?= Form::input("prenom", Helper::arrayGetString("prenom", $_POST), $defaultAttr); ?>
					<?= Form::label("Prénom", "prenom"); ?>
				</div>
			</div>

			<!-- Nom -->
			<div class="col-md-6">
				<div class="form-floating">
					<?= Form::input("nom", Helper::arrayGetString("nom", $_POST), $defaultAttr); ?>
					<?= Form::label("Nom", "nom"); ?>
				</div>
			</div>
		</div>

		<!-- Email -->
		<div class="row my-2">
			<div class="col-md">
				<div class="form-floating">
					<?= Form::input("email", Helper::arrayGetString("email", $_POST),
						array("type" => "email", "class" => "form-control", "placeholder" => "", "autocomplete" => "off")); ?>
					<?= Form::label("Email", "email"); ?>
				</div>
				<p class="text-muted">
					Une fois le compte créé, vous recevrez un message au mail donné.
				</p>
			</div>
		</div>

		<!-- Message à envoyer -->
		<div class="row my-2">
			<div class="col-md">
				<?= Form::label("Message", "msg"); ?>
				<?= Form::textarea("msg", Helper::arrayGetString("msg", $_POST), $defaultAttr); ?>
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