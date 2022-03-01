<?php

use Fuel\Core\View;

?>

<div class="container" style="position: relative;">

	<h1 class="mt-4">Recherche d'opération et de sujet</h1>

	<div class="btn-group my-4" style="position: relative; left: 50%; transform: translateX(-50%);">
		<button id="btn_operation" type="button" class="btn btn-outline-primary" onclick="Search.switchPage(`form_operation`, `btn_operation`)">Opération</button>
		<button id="btn_sujet" type="button" class="btn btn-outline-primary" onclick="Search.switchPage(`form_sujet`, `btn_sujet`)">Sujet</button>
	</div>

	<form action="" method="post" style="background-color: #F5F5F5; padding: 10px;">
		<div id="form_operation">
			<?= View::forge("recherche/form_operation", array("action" => "public/search")) ?>
		</div>

		<div id="form_sujet">
			<?= View::forge("recherche/form_sujet", array()) ?>
		</div>
	</form>

	<script>
		Search.init(
			["form_operation", "form_sujet"],
			["btn_operation", "btn_sujet"]
		);
		Search.switchPage("form_operation", "btn_operation");
	</script>
</div>