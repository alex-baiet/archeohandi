<?php

use Fuel\Core\Form;
use Model\Helper;

$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off");

?>
<div class="container">
	<h1 class="m-2">Création d'un compte</h1>

	<p class="text-muted">Vous pouvez demander la création d'un compte via ce formulaire.</p>

	<form action="" method="POST" style="background-color: #F5F5F5; padding: 10px;">

		<div class="row my-4">
			<!-- Prenom -->
			<div class="col-md-6">
				<p class="text-muted" style="margin-bottom:0;">Indiquez le prénom avec la 1<sup>ère</sup> lettre en majuscule.</p>
				<div class="form-floating">
					<input type="text" class="form-control" name="prenom" class="form_prenom" value="<?= Helper::arrayGetString("prenom", $_POST) ?>"
						placeholder="Prénom" autocomplete="off"
						onkeyup="this.required=`required`">
					<div class="form-msg-error">Veuillez remplir ce champ.</div>
					<label for="form_prenom">Prénom</label>
				</div>
			</div>

			<!-- Nom -->
			<div class="col-md-6">
				<p class="text-muted" style="margin-bottom:0;">Indiquez le nom en majuscules.</p>
				<div class="form-floating">
					<input type="text" class="form-control" name="nom" class="form_nom" value="<?= Helper::arrayGetString("nom", $_POST) ?>"
						placeholder="Nom" autocomplete="off"
						onkeyup="this.required=`required`">
					<div class="form-msg-error">Veuillez remplir ce champ.</div>
					<label for="form_nom">Nom</label>
				</div>
			</div>
		</div>

		<!-- Email -->
		<div class="row mt-4" >
			<div class="col-md">
				<p class="text-muted" style="margin-bottom:0;">
					Une fois le compte créé, vous recevrez un message au mail donné.
				</p>
				<div class="form-floating">
					<input type="email" class="form-control" name="email" class="form_email" value="<?= Helper::arrayGetString("email", $_POST) ?>"
						placeholder="Email" autocomplete="off"
						onkeyup="this.required=`required`">
					<div class="form-msg-error">Le mail n'est pas valide.</div>
					<label for="form_email">Email</label>
				</div>
			</div>
		</div>

		<!-- Organisme -->
		<div class="row mt-4" >
			<div class="col-md">
				<div class="form-floating">
					<input type="text" class="form-control" name="organisme" class="form_organisme" value="<?= Helper::arrayGetString("organisme", $_POST) ?>"
						placeholder="Organisme">
					<label for="form_organisme">Organisme</label>
				</div>
			</div>
		</div>

		<!-- Message à envoyer -->
		<div class="row my-2">
			<div class="col-md">
				<label for="form_msg">Message</label>
				<textarea class="form-control" name="msg" class="form_msg" value="<?= Helper::arrayGetString("msg", $_POST) ?>"
					placeholder="Entrez un message (optionnel)" autocomplete="off"></textarea>
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