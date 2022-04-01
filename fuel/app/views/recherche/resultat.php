<?php

use Fuel\Core\View;
use Model\Db\Compte;
use Model\Helper;
use Model\Searchresult;

/** @var Searchresult[] */
$results = $results;

$countOperation = count($results);
$countSubject = 0;
foreach ($results as $data) {
	$countSubject += count($data->subjects);
}

?>

<script>
	Search.setDataId("data");
</script>

<pre id="data" style="display: none;">
<?=
Helper::postQuery("https://archeohandi.huma-num.fr/public/recherche/api", $_POST);
?>
</pre>

<div class="container">

	<form action="/public/recherche" method="post" class="mt-4">
		<button type="submit" name="keepOptions" value="1" class="btn btn-secondary">Modifier la recherche</button>
	</form>

	<h1>
		Résultats de la recherche
		<button class="btn btn-primary" onclick="Search.exportToCSV()">Exporter en CSV</button>
	</h1>
	<p class="text-muted mb-4">
		<b><?= $countOperation ?></b> opérations et <b><?= $countSubject ?></b> sujets handicapés correspondent à votre recherche.
	</p>

	<?php if (!empty($results)) : ?>
	<div class="table-scroll">
		<?= View::forge("operations/table", array("lines" => $results)) ?>
	</div>
	<?php else : ?>
		<h2 class="text-muted text-center">Aucun résultat pour la recherche</h2>
	<?php endif; ?>
</div>