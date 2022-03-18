<?php

use Fuel\Core\Asset;

?>
<!-- Popup d'aide d'ajout d'image -->
<div class="modal" id="helpPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="helpPopupLabel" aria-hidden="true">
	<div class="modal-dialog" style="max-width: 800px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="helpPopupLabel">Aide</h5>
			</div>
			<div class="modal-body">
				<p>
					Pour ajouter une image de Nakala, allez d'abord sur l'affichage d'une image directement sur Nakala,
					puis copiez le champ comme indiqué dans l'image ci-dessous.<br>
					<br>
					Collez l'url dans le champ, et si une prévisualisation de l'image s'affiche,
					c'est que votre image a bien été ajoutée au sujet !<br>
					<b>Note</b> : Il est possible d'ajouter des images d'autres sources que Nakala si vous le souhaitez.<br>
					<br>
					<b>J'ai rempli le champ, mais aucune image ne s'affiche. Pourquoi ?</b>
					<ul>
						<li>Le texte du champ n'est pas bon : vérifiez que vous avez bien copié l'url (il doit commencer par "https://" ou "http://".</li>
						<li>L'image n'est pas au bon format : seul les formats <b>PNG</b> et <b>JPG</b> peuvent être prévisualisés.</li>
						<li>L'image n'existe plus : il est possible que l'image ai été supprimé de Nakala.</li>
						<li>Avez-vous bien <b>publié</b> et non <b>déposé</b> l'image sur Nakala ?</li>
					</ul>
					<br>
					<figure>
						<figcaption style="text-align: center; font-style: italic; background-color: #0002;">Exemple d'url à copier</figcaption>
						<?= Asset::img("help/demo_url.png", array("style" => "width: 100%;")) ?>
					</figure>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#helpPopup">Retour</button>
			</div>
		</div>
	</div>
</div>