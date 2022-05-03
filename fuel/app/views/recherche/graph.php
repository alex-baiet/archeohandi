<?php

use Fuel\Core\Asset;
use Model\Helper;

?>

<!-- HighChart -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<?= Asset::css('highcharts.css') ?>
<?= Asset::js('charts.js') ?>

<pre id="data" style="display: none;">
<?=
Helper::postQuery("https://archeohandi.huma-num.fr/public/recherche/api", array(
	"id_operation" => "",
	"departement" => "",
	"commune" => "",
	"insee" => "",
	"adresse" => "",
	"longitude" => "",
	"latitude" => "",
	"radius" => "100",
	"annee_min" => "",
	"annee_max" => "",
	"organisme" => "",
	"id_type_op" => "",
	"id_sujet" => "",
	"id_sujet_handicape" => "",
	"id_chronologie" => "",
	"sexe" => "",
	"age_min" => "",
	"age_max" => "",
	"date_min" => "",
	"date_max" => "",
	"id_type_depot" => "",
	"id_sepulture" => "",
	"contexte_normatif" => "",
	"milieu_vie" => "",
	"contexte" => "",
	"search" => "1"
));
?>
</pre>

<div class="text-center">
	<div class="dropdown">
		<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
			Changer de graphique
		</button>

		<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			<li><button class="dropdown-item" onclick="Charts.diagnosticPie()">Diagnostics</a></li>
		</ul>
	</div>
</div>

<figure class="highcharts-figure">
	<div id="container"></div>
</figure>
<script>
	Charts.diagnosticPie()
</script>