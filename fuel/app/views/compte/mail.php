<?php
/*
Contenu du mail envoyé lors de la demande de création de compte.
*/

/** @var string[] */
$data = $data;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Demande d'accès Archéologie du handicap</title>
</head>

<body>

	<p>
		Monsieur/Madame <b><?= "{$data["prenom"]} {$data["nom"]}" ?></b> demande l'ouverture
		d'un compte sur la base de données Archéologie du Handicap.<br>
		<br>

		Mail : <a href="mailto:<?= $data["email"]; ?>"><?= $data["email"] ?></a><br>
		Organisme : <b><?= $data["organisme"] ?></b>
	</p>

	<?php if (!empty($data["msg"])) : ?>
		Message attaché à la demande :<br>
		<blockquote>
			<?= $data["msg"] ?>
		</blockquote>
	<?php endif; ?>

	<form action="https://archeohandi.huma-num.fr/public/compte/creation_confirmation" method="POST">
		<input type="hidden" name="prenom" value="<?= $data["prenom"] ?>">
		<input type="hidden" name="nom" value="<?= $data["nom"] ?>">
		<input type="hidden" name="email" value="<?= $data["email"] ?>">
		<input type="hidden" name="organisme" value="<?= $data["organisme"] ?>">
		<input type="hidden" name="token" value="c7e626f1f507f3798570649c91ff9a5e">
		<button type="submit">Confirmer la création</button>
	</form>

</body>

</html>
