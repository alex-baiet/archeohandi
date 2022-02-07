<?php

use Fuel\Core\Asset;
use Fuel\Core\View;
use Model\Db\Operation;
use Model\Db\Sujethandicape;

/** @var int */
$idOperation = $idOperation;
/** @var Sujethandicape */
$subject;

?>
<?=
Asset::js("form.js");
?>

<div class="container">
	<h1 class="m-2">Ajouter des sujets handicapés</h1>
	<?php $op = Operation::fetchSingle($idOperation); ?>
	<p class="text-muted">Opération "<?= $op->getNomOp(); ?>"</p>

	<?php
	$data = array("idOperation" => $idOperation, "btnStay" => true);
	if (isset($subject)) $data["subject"] = $subject;
	?>
	<?= View::forge("sujet/form", $data); ?>
</div>
