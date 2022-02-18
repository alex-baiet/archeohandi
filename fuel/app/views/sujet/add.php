<?php

use Fuel\Core\Asset;
use Fuel\Core\View;
use Model\Db\Operation;
use Model\Db\Sujethandicape;

/** @var int */
$idOperation = $idOperation;
/** @var Sujethandicape */
if (isset($subject)) $subject = $subject;

?>
<?=
Asset::js("form.js");
?>

<div class="container">
	<h1 class="m-2">Ajout d'un sujet handicapé</h1>
	<?php $op = Operation::fetchSingle($idOperation); ?>
	<p class="text-muted">
		Opération "<?= $op->getNomOp(); ?>"<br>
		Pour plus d'informations, laissez la souris au dessus du champ pour afficher un texte d'aide.<br>
		Le numéro du sujet ne sera attribué que après sa création. Vous pourrez ensuite y accéder en consultant le sujet.
	</p>

	<?php
	$data = array("idOperation" => $idOperation, "btnStay" => true);
	if (isset($subject)) $data["subject"] = $subject;
	?>
	<?= View::forge("sujet/form", $data); ?>
</div>
