<?php

/** @var string */
$firstName = $firstName;
/** @var string */
$lastName = $lastName;
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
		d'un compte sur la base de données Archéologie du Handicap.
	</p>

	<?php if (!empty($msg)) : ?>
		Message attaché à la demande :<br>
		<blockquote>
			<?= $msg ?>
		</blockquote>
	<?php endif; ?>

	<a href="https://google.com">
		<button>Confirmer</button>
	</a>

</body>

</html>
