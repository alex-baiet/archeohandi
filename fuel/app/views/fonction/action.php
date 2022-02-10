<?php

use Model\Commune;
use Model\Db\Compte;

/** @var string */
$id = $id;
/** @var int */
$maxResultCount = $maxResultCount;
/** @var Commune[]|null */
$communes = isset($communes) ? $communes : null;
/** @var Compte[]|null */
$comptes = isset($comptes) ? $comptes : null;

$attributs = "class='list-group-item list-group-item-action border-1 $id-auto-complete' style='cursor: pointer;'";

?>

<?php
// Affichage communes
if ($communes !== null) {
	for ($i = 0; $i < count($communes) && $i < $maxResultCount; $i++) {
		echo "<a $attributs>{$communes[$i]->getNom()}, {$communes[$i]->getDepartement()}</a>";
	}
}

// Affichage comptes
if ($comptes !== null) {
	for ($i = 0; $i < count($comptes) && $i < $maxResultCount; $i++) {
		echo "<a $attributs>{$comptes[$i]->getLogin()}</a>";
	}
}
?>

<?php if (empty($communes) && empty($comptes)) : // Cas pas de résultats trouvés 
?>
	<p class="list-group-item border-1">Aucun résultat trouvé</p>
<?php endif; ?>