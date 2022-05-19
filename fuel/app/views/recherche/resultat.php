<?php

use Fuel\Core\Asset;
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

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<?= Asset::css('highcharts.css') ?>
<?= Asset::js('charts.js') ?>

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
		<div class="row">
			<div class="col-xl-8">
				<div class="table-scroll">
					<?= View::forge("operation/table", array("lines" => $results)) ?>
				</div>
			</div>

			<!-- Charts -->
			<div class="col-xl-4">
				<div class="text-center">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
							Changer de graphique
						</button>

						<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
							<li><button class="dropdown-item" onclick="Charts.dateGraph()">Ancienneté</a></li>
							<li><button class="dropdown-item" onclick="Charts.contextGraph()">Contexte</a></li>
							<li><button class="dropdown-item" onclick="Charts.contextPrescriptiveGraph()">Contexte normatif</a></li>
							<li><button class="dropdown-item" onclick="Charts.diagnosticGraph()">Diagnostic</a></li>
							<li><button class="dropdown-item" onclick="Charts.environmentLifeGraph()">Milieu de vie</a></li>
							<li><button class="dropdown-item" onclick="Charts.pathologyGraph()">Pathologie</a></li>
							<li><button class="dropdown-item" onclick="Charts.periodGraph()">Période des sujets</a></li>
							<li><button class="dropdown-item" onclick="Charts.periodGroupGraph()">Période des groupes</a></li>
							<li><button class="dropdown-item" onclick="Charts.sexGraph()">Sexe</a></li>
							<li><button class="dropdown-item" onclick="Charts.typeDepotGraph()">Type de dépôt</a></li>
							<li><button class="dropdown-item" onclick="Charts.typeSepultureGraph()">Type de sépulture</a></li>
						</ul>
					</div>
				</div>

				<figure class="highcharts-figure">
					<div id="container"></div>
				</figure>
				<script>
					Charts.diagnosticGraph();
				</script>
			</div>
		</div>
	<?php else : ?>
		<h2 class="text-muted text-center">Aucun résultat pour la recherche</h2>
	<?php endif; ?>
</div>