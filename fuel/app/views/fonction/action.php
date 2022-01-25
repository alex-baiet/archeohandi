<?php
use Model\Commune;

/** @var string */
$id = $id;
/** @var Commune[]|null */
$communes = isset($communes) ? $communes : null;
/** @var int */
$maxResultCount = $maxResultCount;

$attributs = "class='list-group-item list-group-item-action border-1 $id-auto-complete' style='cursor: pointer;'";

?>

<?php if ($communes !== null): ?>
	<?php for ($i=0; $i<count($communes) && $i<$maxResultCount; $i++): // Affichage de tous les résultats pour les communes ?>
		<a <?= $attributs ?>><?= $communes[$i]->getNom() ?>, <?= $communes[$i]->getDepartement() ?></a>
	<?php endfor; ?>
<?php endif; ?>

<?php if (count($communes) === 0 && count($people) === 0): // Cas pas de résultats trouvés ?>
	<p class="list-group-item border-1">Aucun résultat trouvé</p>
<?php endif; ?>
