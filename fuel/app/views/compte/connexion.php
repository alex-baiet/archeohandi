<?php

use Fuel\Core\Form;
use Model\Helper;

?>

<div class="container">
	<h1 class="m-2">Connexion</h1>

	<?= Form::open(array("method" => "POST", "style" => "background-color: #F5F5F5; padding: 10px;")); ?>
		<!-- Login -->
		<div class="row my-2">
			<div class="col-md">
				<div class="form-floating">
					<?= Form::input("login", Helper::arrayGetString("login", $_POST), array("type" => "text", "class" => "form-control", "placeholder" => "")); ?>
					<?= Form::label("Login", "login"); ?>
				</div>
			</div>
		</div>
		
		<!-- Mot de passe -->
		<div class="row my-2">
			<div class="col-md">
				<div class="form-floating">
					<?= Form::input("mdp", Helper::arrayGetString("mdp", $_POST), array("type" => "password", "class" => "form-control", "placeholder" => "")); ?>
					<?= Form::label("Mot de passe", "mdp"); ?>
				</div>
			</div>
		</div>
		
		<!-- Boutons de confirmation -->
		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" name="create" class="btn btn-success">Demander l'accès</button>
			</div>
		</div>

	<?= Form::close(); ?>
</div>