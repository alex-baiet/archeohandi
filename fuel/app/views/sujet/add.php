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
	<a class="btn btn-secondary mt-2" href="/public/operations/sujets/<?= $idOperation ?>" role="button">Retour</a>

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
	<form action="" method="post" class="form-sheet" onsubmit="prepareFormSend()">
		<?= View::forge("sujet/form", $data); ?>

		<div class="row" style="margin-top: 10px;">
			<div class="d-md-flex justify-content-md-end col">		
				<button type="submit" name="stayOnPage" value="0" class="btn btn-success">Confirmer et sortir</button>
				<button type="submit" name="stayOnPage" value="1" class="btn btn-success" style="margin-left: 10px">Confirmer et dupliquer la fiche</button>
			</div>
		</div>
	</form>
</div>
