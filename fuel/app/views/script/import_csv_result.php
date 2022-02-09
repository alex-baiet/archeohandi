<?php

use Model\Db\Operation;

/** @var Operation[] */
$operations = $operations;

?>

<style>
	.fold-parent {
		border-style: solid;
		border-width: 1px;
		border-color: black;
	}

	.fold-content {
		display: none;
	}
</style>

<script>
	$(document).ready(function () {
		let folds = document.getElementsByClassName("fold-parent");
		for (const fold of folds) {
			fold.onclick = function () {
				/** @type {HTMLElement} */
				const content = fold.getElementsByClassName("fold-content")[0];
				content.style.display = content.style.display === "block" ? "none" : "block";
			}
		}
	});
</script>

<h2>Fichiers envoyés</h2>

<pre>
<?php print_r($_FILES) ?>
</pre>

<h2>Contenu des fichiers</h2>
<pre>
<?php
foreach ($_FILES as $file) {
	if ($file["error"] === 0) echo file_get_contents($file['tmp_name']);
}
?>
</pre>

<h2>Affichages des résultats</h2>

<?php foreach ($operations as $op) : ?>
	<div class="fold-parent">
		<h3 style="background-color: <?= $op->validate() ? "#0f04" : "#f004" ?>">Operation</h3>
		<div class="fold-content">
			<?= $op->echoErrors() ?>
			<pre>
				<?php var_dump($op); ?>
			</pre>
		</div>
	</div>

<?php endforeach; ?>

