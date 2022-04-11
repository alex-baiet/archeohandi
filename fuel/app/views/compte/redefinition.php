<?php

use Model\Helper;

?>

<div class="container">
	<h1 class="m-2">Demande d'un nouveau mot de passe</h1>
	<p class="text-muted">
		Retour à la <a href="/public/compte/connexion">connexion</a>
	</p>

	<form action="" method="post" style="background-color: #F5F5F5; padding: 10px;">
		<!-- Login -->
		<div class="row my-2">
			<div class="col-md">
				<div class="form-floating">
					<input type="text" id="form_login" name="login" class="form-control" placeholder="Login" value="<?= Helper::arrayGetValue("login", $_POST) ?>">
					<label for="form_login">Login</label>
				</div>
			</div>
		</div>
		
		<!-- Mail -->
		<div class="row my-2">
			<div class="col-md">
				<div class="form-floating">
					<input type="text" id="form_email" name="email" class="form-control" placeholder="Email" value="<?= Helper::arrayGetValue("email", $_POST) ?>">
					<label for="form_email">Email</label>
				</div>
			</div>
		</div>
		
		<!-- Boutons de confirmation -->
		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" name="create" class="btn btn-success">Confirmer</button>
			</div>
		</div>

	</form>
</div>