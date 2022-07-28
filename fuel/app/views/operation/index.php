<?php

use Fuel\Core\View;
use Model\Db\Compte;
use Model\Searchresult;

/** @var Searchresult[] */
$lines = $lines;
/** @var int */
$countSubject = $countSubject;
/** @var int */
$countOp = $countOp;

$json = "[";
foreach ($lines as $key => $op) {
	$json .= json_encode($op->toArray()).",";
}
$json = substr($json, 0, strlen($json)-1) ."]";

?>

<script>
	FastSearch.init(<?= $json ?>);
</script>

<!-- Entête de la page -->
<div class="container">

	<!-- Titre principal de la page -->
	<h1 class="m-2">Opérations
		<!-- Bouton "Ajout d'un opération -->
		<?php if (Compte::checkPermission(Compte::PERM_WRITE)) : ?>
			<a class="btn btn-primary btn-sm" href="/public/operation/ajout">Créer une opération <i class="bi bi-plus-circle-fill"></i></a>
		<?php endif; ?>
	</h1>

	<p class="text-muted">
		Ici vous pouvez retrouver toutes les informations sur les opérations.<br>
		<b><?= $countOp ?></b> opérations existantes pour un total de <b><?= $countSubject ?></b> sujets enregistrés.<br>
	</p>

	<input id="search" class="form-control my-3" type="text" placeholder="Rechercher" aria-label="Champ de recherche rapide" onkeyup="FastSearch.search(this)" autocomplete="off">

	<!-- Contenu de la page -->
	<div class="table-scroll">
		<?= View::forge("operation/table", array("lines" => $lines)) ?>
	</div>
</div>
