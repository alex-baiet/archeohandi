<?php

/** @var array Contient tous les résultats de la recherche */
$results = $results;
/** @var int Nombre maximum de résultats affichés. */
$maxResultCount = $maxResultCount;
/** @var string */
$id = $id;

?>

<?php if (empty($results)) : ?>
	<p class="list-group-item border-1">Aucun résultat trouvé</p>
<?php
else :
	$key = array_key_first($results[0]);
	for ($i = 0; $i < count($results) && $i < $maxResultCount; $i++) :
	?>
		<a class='list-group-item list-group-item-action border-1 <?= $id ?>-auto-complete' style='cursor: pointer;'>
			<?= $results[$i][$key] ?>
		</a>
	<?php
	endfor;
endif;
?>
