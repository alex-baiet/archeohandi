<?php
/*
Contenu du mail envoyé lors de la demande de création de compte.
*/


use Model\Db\Compte;

/** @var string */
$login = $login;
/** @var string */
$pw = $pw;

$account = Compte::fetchSingle($login);

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
		Bonjour <?= $account->getPrenom()." ".$account->getNom() ?>,<br>
		<br>
		Voici votre nouveau mot de passe : <b><?= $pw ?></b>
	</p>

</body>

</html>
