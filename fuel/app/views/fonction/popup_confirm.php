<?php
/*
Affichage d'un popup pour confirmer une action.
*/

/** @var string Titre du popup. */
$title = isset($title) ? $title : "";
/** @var string Texte principal. */
$bodyText = isset($bodyText) ? $bodyText : "";
/** @var string Texte supplémentaire. */
$infoText = isset($infoText) ? $infoText : "";
/** @var string */
$btnName = isset($btnName) ? $btnName : null;

?>
<div class="modal" id="validationPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="validationPopupLabel" aria-hidden="true" style="z-index: 100000;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="validationPopupLabel"><?= $title ?></h5>
			</div>
			<div class="modal-body">
				<p>
					<?= $bodyText ?><br><br>
					<?php if (isset($infoText)) : ?>
						<i class='bi bi-info-circle-fill'></i> <?= $infoText ?>
					<?php endif; ?>
				</p>
			</div>
			<div class="modal-footer">
				<form method="POST">
					<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#validationPopup">Retour</button>
					<button type="submit" name="<?= $btnName ?>" id="form_<?= $btnName ?>" value="" class="btn btn-success">Continuer</button>
				</form>
			</div>
		</div>
	</div>
</div>
