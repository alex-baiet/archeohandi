<?php

use Model\Db\Compte;
use Model\Helper;

$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "", "autocomplete" => "off");

?>
<div class="container">
	<h1 class="m-2">Création d'un compte</h1>

	<p class="text-muted">
		Vous pouvez demander la création d'un compte via ce formulaire.<br>
		Vous avez déjà un compte ? <a href="/public/compte/connexion">Se connecter</a>
	</p>

	<form action="" method="POST" style="background-color: #F5F5F5; padding: 10px;">

		<div class="row my-4">
			<!-- Prenom -->
			<div class="col-md-6">
				<p class="text-muted" style="margin-bottom:0;">Indiquez le prénom avec la 1<sup>ère</sup> lettre en majuscule.</p>
				<div class="form-floating">
					<input type="text" class="form-control" name="prenom" class="form_prenom" value="<?= Helper::arrayGetValue("prenom", $_POST) ?>"
						placeholder="Prénom" autocomplete="off"
						onkeyup="this.required=`required`">
					<div class="form-msg-error">Veuillez remplir ce champ.</div>
					<label for="form_prenom">Prénom<span class="red">*</span></label>
				</div>
			</div>

			<!-- Nom -->
			<div class="col-md-6">
				<p class="text-muted" style="margin-bottom:0;">Indiquez le nom en majuscules.</p>
				<div class="form-floating">
					<input type="text" class="form-control" name="nom" class="form_nom" value="<?= Helper::arrayGetValue("nom", $_POST) ?>"
						placeholder="Nom" autocomplete="off"
						onkeyup="this.required=`required`">
					<div class="form-msg-error">Veuillez remplir ce champ.</div>
					<label for="form_nom">NOM<span class="red">*</span></label>
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
					<input type="email" class="form-control" name="email" class="form_email" value="<?= Helper::arrayGetValue("email", $_POST) ?>"
						placeholder="Email" autocomplete="off"
						onkeyup="this.required=`required`">
					<div class="form-msg-error">Le mail n'est pas valide.</div>
					<label for="form_email">Email<span class="red">*</span></label>
				</div>
			</div>
		</div>

		<!-- Organisme -->
		<div class="row mt-4">
			<div class="col-md">
				<div class="form-floating">
					<input name="organisme" id="form_organisme" class="form-control" placeholder="Organisme"
						value="<?= Helper::arrayGetValue("organisme", $_POST) ?>" autocomplete="off">
					<label for="form_organimse">Organisme</label>
					<script>
						addAutocomplete("form_organisme", "nom", "organisme", [
							["nom", "LIKE", "?%"]
						]);
					</script>
				</div>
			</div>
		</div>

		<!-- Message à envoyer -->
		<div class="row my-2">
			<div class="col-md">
				<label for="form_msg">Message</label>
				<textarea class="form-control" name="msg" class="form_msg" value="<?= Helper::arrayGetValue("msg", $_POST) ?>"
					placeholder="Entrez un message (optionnel)" autocomplete="off"></textarea>
			</div>
		</div>
		
		<!-- Conditions -->
		<?php if (!Compte::checkPermission(Compte::PERM_ADMIN)) : ?>
			<div class="row my-2">
				<div class="col-md" style="text-align: justify;">
					<input type="checkbox" class="form-checkbox" name="terms" id="form_terms" value="1" required>
					<label for="form_terms" class="text-muted" style="display: inline;">
						En intégrant le groupe de travail « Archéologie du handicap » je m'engage à respecter le cadre déontologique d'utilisation
						des données scientifiques ici partagées dans une optique collaborative.
						La perspective est l'organisation d'un colloque thématique qui sera organisé en décembre 2025 :
						ces informations seront alors disponibles pour des propositions de communications ».
						L'équipe de pilotage du projet est à disposition pour tout complément et/ou précision.
					</label>
				</div>
			</div>
		<?php endif; ?>
		
		<!-- Création immédiat -->
		<?php if (Compte::checkPermission(Compte::PERM_ADMIN)) : ?>
			<div class="row my-2">
				<p class="text-muted" style="margin-bottom: 0;">
					Ce bouton n'est visible que par les administrateurs et permet de créer immédiatement un compte sans passer par les mails.
				</p>
				<div class="col-md">
					<input type="checkbox" class="form-checkbox" name="immediate" class="form_immediate" value="1">
					<label for="form_immediate">Création immédiate (admins uniquement)</label>
				</div>
			</div>
		<?php endif; ?>
		
		<!-- Boutons de confirmation -->
		<div class="row">
			<div class="d-md-flex justify-content-md-end col">
				<button type="submit" name="create" class="btn btn-success">Demander l'accès</button>
			</div>
		</div>

	</form>
</div>