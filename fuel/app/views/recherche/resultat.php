<?php

use Fuel\Core\View;
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

<pre style="display: none;">
<?=
Helper::postQuery("https://archeohandi.huma-num.fr/public/recherche/api", $_POST);
?>
</pre>

<div class="container">

	<form action="/public/recherche" method="post" class="mt-4">
		<button type="submit" name="keepOptions" value="1" class="btn btn-secondary">Modifier la recherche</button>
	</form>

	<h1>Résultats de la recherche</h1>
	<p class="text-muted mb-4">
		<b><?= $countOperation ?></b> opérations et <b><?= $countSubject ?></b> sujets handicapés correspondent à votre recherche.
	</p>

	<div class="table-scroll">
		<?= View::forge("operations/table", array("lines" => $results)) ?>
	</div>
		
</div>