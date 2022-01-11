<?php

use Fuel\Core\Asset;
use Fuel\Core\View;
use Model\Operation;
use Model\Sujethandicape;

/** @var int */
$idOperation = $idOperation;
/** @var Sujethandicape */
$subject;

?>
<?=
Asset::js("form.js");
?>

<div class="container">
	<h1 class="m-2">Ajouter des sujets handicapés <a class="btn btn-sm btn-secondary" href="/public/add/sujet/<?= $idOperation; ?>">Rafraichir la page <i class="bi bi-arrow-repeat"></i></a></h1>
	<?php $op = Operation::fetchSingle($idOperation); ?>
	<p class="text-muted">Opération "<?= $op->getNomOp(); ?>"</p>

	<?php
	$data = array("idOperation" => $idOperation, "btnStay" => true);
	if (isset($subject)) $data["subject"] = $subject;
	?>
	<?= View::forge("sujet/form", $data); ?>
</div>

<?= Asset::js('script_recherche.js'); ?>

<script type="text/javascript">
	//Permet d'afficher les informations cachées (champs texte, sélection d'option)
	function function_toggle(id) {
		if (document.getElementById("autre_mobilier_" + id).checked === true) {
			$("#block_description_autre_mobilier_" + id).removeClass("d-none");
		} else {
			$("#block_description_autre_mobilier_" + id).addClass("d-none");
		}

		if (document.getElementById("autre_atteinte_" + id).checked === true) {
			$("#block_description_autre_atteinte_" + id).removeClass("d-none");
		} else {
			$("#block_description_autre_atteinte_" + id).addClass("d-none");
		}

		if (document.getElementById("pathologies_infectieuses_" + id).checked === true) {
			$("#block_pathologies_infectieuses_" + id).removeClass("d-none");
		} else {
			$("#block_pathologies_infectieuses_" + id).addClass("d-none");
		}
	}

	//Permet d'ajouter un sujet
	function ajouterLigne(nbLigne) {
		//Cache le bouton pour ajouter à nouveau
		$("#btnAjouter" + (nbLigne - 1)).hide();
		if (nbLigne > 2) {
			//Cache le bouton pour supprimer à nouveau
			$("#btnSupp" + (nbLigne - 1)).hide();
		}
		//Envoi les informations à la page action
		$.post('https://archeohandi.huma-num.fr/public/fonction/action.php?action=' + nbLigne, function(row) {
			//Ajoute les informations retournées dans la div avec l'ID contenu
			$('#contenu').append(row);
		});
	}

	//Permet de supprimer un sujet ajouté
	function supprimerLigne(nbLigne) {
		//Affiche le bouton d'ajout de l'ancien sujet
		$("#btnAjouter" + (nbLigne - 1)).show();
		//Affiche le bouton de suppression de l'ancien sujet
		$("#btnSupp" + (nbLigne - 1)).show();
		//Supprime le dernier de la div contenu
		$('.contenu').children().last().remove();
	}
</script>