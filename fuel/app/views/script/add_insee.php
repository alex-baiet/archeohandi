<?php

/** @var string */
if (isset($file)) $file = $file;
/** @var string[] */
if (isset($results)) $results = $results;

?>


<form enctype="multipart/form-data" action="" method="POST">
	<div class="mb-3">
		<label for="file" class="form-label">CSV</label>
		<input class="form-control" type="file" name="file" id="file">
	</div>

	<button class="btn btn-success" type="submit" name="submit">Envoyer</button>
</form>

<?php if (isset($results)) : ?>
	<pre><?= ""//$file ?></pre>

	<!-- Affichage des résultats -->
	<ul class="list-group">
		<?php foreach ($results as $name => $color) : ?>
			<li class="list-group-item" style="background-color: <?= $color ?>"><?= $name ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>