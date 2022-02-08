<?php

use Fuel\Core\View;

?>

<div class="container">
	<h1>Page de recherche d'opération et de sujet</h1>

	<?= View::forge("operations/form", array("action" => "public/search")) ?>

	<?= View::forge("sujet/form", array()) ?>
</div>