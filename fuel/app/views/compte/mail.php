<?php
/*
Contenu du mail envoyé lors de la demande de création de compte.
*/

/** @var string */
$firstName = $firstName;
/** @var string */
$lastName = $lastName;
/** @var string */
$email = $email;
/** @var string */
$msg = $msg;

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Demande d'accès Archéologie du handicap</title>

	<style>

	</style>
</head>

<body>

	<p>
		Monsieur/Madame <?= "$firstName $lastName" ?> demande l'ouverture
		d'un compte sur la base de données Archéologie du Handicap.<br>
		<br>

		Mail : <a href="mailto:<?= $email; ?>"><?= $email ?></a>
	</p>

	<?php if (!empty($msg)) : ?>
		Message attaché à la demande :<br>
		<blockquote>
			<?= $msg ?>
		</blockquote>
	<?php endif; ?>

	<form action="https://archeohandi.huma-num.fr/public/compte/creation_confirmation" method="POST">
		<input type="hidden" name="prenom" value="<?= $firstName ?>">
		<input type="hidden" name="nom" value="<?= $lastName ?>">
		<input type="hidden" name="email" value="<?= $email ?>">
		<button type="submit">Confirmer la création</button>
	</form>

</body>

</html>
