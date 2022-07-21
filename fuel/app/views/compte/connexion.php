<?php

use Fuel\Core\Form;
use Model\Helper;

?>

<div class="container">
	<h1 class="m-2">Connexion</h1>
	<p class="text-muted">
		Vous n'avez pas de compte ? <a href="/public/compte/creation">Créer un compte</a>
	</p>

	<?= Form::open(array("method" => "POST", "class" => "form-sheet")); ?>
		<!-- Login -->
		<div class="row my-2">
			<div class="col-md">
				<div class="form-floating">
					<input type="text" id="form_login" name="login" class="form-control" placeholder="Login" value="<?= Helper::arrayGetString("login", $_POST) ?>">
					<label for="form_login">Login<span class="red">*</span></label>
				</div>
			</div>
		</div>
		
		<!-- Mot de passe -->
		<div class="row my-2">
			<div class="col-md">
				<div class="form-floating">
					<input type="password" id="form_mdp" name="mdp" class="form-control" placeholder="Mot de passe" value="<?= Helper::arrayGetString("mdp", $_POST) ?>">
					<label for="form_mdp">Mot de passe<span class="red">*</span></label>
				</div>
			</div>
		</div>
		<p class="text-muted">Mot de passe oublié ? <a href="/public/compte/redefinition">Changer le mot de passe</a></p>
		
		<!-- Boutons de confirmation -->
		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" name="create" class="btn btn-success">Se connecter</button>
			</div>
		</div>

	<?= Form::close(); ?>
</div>