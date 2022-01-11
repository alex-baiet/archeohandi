<?php

use Fuel\Core\Asset;
use Fuel\Core\View;

/** @var Operation|unset */
$operation;

?>

<!-- Contenu de la page partie opération -->
<div class="" id="ajout_operation">
	<div class="container">
		<h1 class="m-2">
			Ajout d'une opération
		</h1>
		<p class="text-muted">
			Ici vous pouvez ajouter une opération de façon simplifiée.
			Pour aller plus vite, vous pouvez utiliser la touche TAB
			<?= Asset::img('TAB.jpg', array('width' => '75px', 'height' => '35px')); ?>
			pour aller d'un champs texte à un autre.
		</p>
		
		<?php
		$data = array(
			"action" => "operations/add",
			"modalBodyMain" => 
				"En continuant, vous allez ajouter des sujets à l'opération que vous venez de créer.
				En vous stopant, vous serrez redirigé vers la page des opérations.",
			"modalBodyInfo" => "Pour ajouter des sujets plus tard, il vous suffit d'aller dans le détail d'une opération."
		);
		if (isset($operation)) $data["operation"] = $operation;
		echo View::forge("operations/form", $data);
		?>
		
	</div>
</div>
