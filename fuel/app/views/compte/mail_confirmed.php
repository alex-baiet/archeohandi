<?php
/*
Contenu du mail envoyé lors de la demande de création de compte.
*/

/** @var string */
$firstName = $firstName;
/** @var string */
$lastName = $lastName;
/** @var string */
$login = $login;
/** @var string */
$password = $password;

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
		Bonjour <?= "$firstName $lastName" ?>,<br>
		<br>
		Votre demande de compte a été validé !<br>
		<br>
		Vos identifiants :
	</p>

	<ul>
		<li>login : <b><?= $login ?></b></li>
		<li>mot de passe : <b><?= $password ?></b></li>
	</ul>
</body>

</html>
