<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;

?>
<!-- Contenu de la page -->
<div class="container">
	<h1 class="m-2">Ajouter des sujets handicapés <a class="btn btn-sm btn-secondary" href="/public/add/sujet/<?= $id; ?>">Rafraichir la page <i class="bi bi-arrow-repeat"></i></a></h1>
	<p class="text-muted">Ici vous pouvez ajouter des sujets handicapés.</p>
	<div class="container" style="background-color: #F5F5F5;">

		<?= Form::open(array('action' => 'add/sujet/' . $id . '', 'method' => 'POST')); ?>
		<div class="contenu" id="contenu">
			<?php
			//En cas d'erreur de saisie, vérifie le nombre de sujet qui à été saisie qui est sotocké dans l'url
			// if (array_key_exists('nb', $_GET)) {
			// 	//Permet de faire les différentes opérations pour chaque sujet
			// 	for ($i = 1; $i <= $_GET['nb']; $i++) {
			// 		//Permet de vérifier si dans l'url il y a les différentes options et si oui, cela appel une fonction qui permet d'afficher un message d'erreur ou d'information pour chaque sujet

			// 		//Appel la fonction pour afficher tout les champs pour ajouter un sujet
			// 		echo afficherLigne($i, $chronologie, $type_depot, $type_sepulture, $diagnostic, $accessoire, $localisation_atteinte, $appareil_compensatoire, $pathologie, 2);
			// 	}
			// } else {
			// 	echo afficherLigne(1, $chronologie, $type_depot, $type_sepulture, $diagnostic, $accessoire, $localisation_atteinte, $appareil_compensatoire, $pathologie, 0);
			// }
			?>
			<div class="col-auto">
				<h2 class="text-center">Groupe de sujets</h2>
				<div class="row g-2">
					<div class="col-md-6">
						<div class="form-floating">
							<input type="number" class="form-control" name="NMI[' . $noLigne . ']" placeholder="183" value="">
							<label for="NMI">NMI</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-floating">
							<select class="form-select" name="nom_chronologie[' . $noLigne . ']" aria-label="select_nom_chronologie">
								<option value="">Sélectionner</option>
								<option value="' . $key['id_chronologie'] . '">$key['nom_chronologie']</option>
							</select>
							<label for="nom_chronologie">Période chronologique</label>
						</div>
					</div>
				</div>

				<h3 class="text-center my-2">Sujet handicapé #' . $noLigne . '</h3>
				<div class="row g-2">
					<div class="row g-2">
						<div class="col-md-6">
							<div class="form-floating">
								<input type="text" class="form-control" name="id_sujet[' . $noLigne . ']" placeholder="183" value="">
								<label for="id_sujet">Identifiant du sujet</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-floating">
								<select class="form-select" name="sexe[' . $noLigne . ']" aria-label="select_sexe">
									<option value="" selected>Sélectionner</option>
									<option value="Femme">Femme</option>
									<option value="Homme">Homme</option>
									<option value="Indéterminé">Indéterminé</option>
								</select>
								<label for="sexe">Sexe</label>
							</div>
						</div>
					</div>

					<div class="row g-2">
						<div class="col-md-6">
							<div class="form-floating">
								<input type="number" class="form-control" min="0" max="130" name="age_min[' . $noLigne . ']" placeholder="183" value="">
								<label for="age_min">Age minimum au décès</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-floating">
								<input type="number" class="form-control" min="0" max="130" name="age_max[' . $noLigne . ']" placeholder="183" value="">
								<label for="age_max">Age maximum au décès</label>
							</div>
						</div>
					</div>

					<div class="row g-2">
						<div class="col-md-6">
							<div class="form-floating">
								<input type="number" class="form-control" name="datation_debut[' . $noLigne . ']" placeholder="183" value="">
								<label for="datation_debut">Datation début</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-floating">
								<input type="number" class="form-control" name="datation_fin[' . $noLigne . ']" placeholder="183" value="">
								<label for="datation_fin">Datation fin</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-3">
					<div class="col-md-4">
						<div class="form-floating">
							<select class="form-select" name="type_depot[' . $noLigne . ']" aria-label="select_depot" style="margin-top: 2.5%;margin-bottom: 2.5%;">
								<option value="4">Sélectionner</option>
								<option value="' . $key['id'] . '">$key['nom']</option>
							</select>
							<label for="type_depot">Type de dépôt</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
							<select class="form-select" name="type_sepulture[' . $noLigne . ']" aria-label="select_sep" style="margin-top: 2.5%;margin-bottom: 2.5%;">
								<option value="4">Sélectionner</option>
								<option value="' . $key['id'] . '">$key['nom']</option>
							</select>
							<label for="type_sepulture">Type de sépulture</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
							<select class="form-select" name="contexte_normatif[' . $noLigne . ']" aria-label="select_contexte_normatif" style="margin-top: 2.5%;margin-bottom: 2.5%;">
								<option value="" selected>Sélectionner</option>
								<option value="Standard">Standard</option>
								<option value="Atypique">Atypique</option>
							</select>
							<label for="contexte_normatif">Contexte normatif</label>
						</div>
					</div>
				</div>

				<div class="row g-2">
					<div class="col-md-6">
						<div class="form-floating">
							<select class="form-select" name="milieu_vie[' . $noLigne . ']" aria-label="select_milieu_vie">
								<option value="">Sélectionner</option>
								<option value="Rural">Rural</option>
								<option value="Urbain">Urbain</option>
							</select>
							<label for="milieu_vie">Milieu de vie</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-floating">
							<select class="form-select" name="contexte[' . $noLigne . ']" aria-label="select_contexte">
								<option value="">Sélectionner</option>
								<option value="Funeraire">Funéraire</option>
								<option value="Domestique">Domestique</option>
								<option value="Autre">Autre</option>
							</select>
							<label for="contexte">Contexte de la tombe</label>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<label for="commentaire_contexte">Commentaire</label>
					<div class="input-group">
						<textarea class="form-control" name="commentaire_contexte[' . $noLigne . ']" rows="2"></textarea>
					</div>
				</div>
				<br />

				<div class="container">
					<div class="row">
						<div class="col-md-4">
							<h3>Accessoire</h3>
							<div class="form-check form-switch">
								<label class="form-check-label" for="' . $key['name'] . '[' . $noLigne . ']">$key['nom']</label>
								<input class="form-check-input" type="checkbox" name="' . $key['name'] . '[' . $noLigne . ']" value="' . $key['id'] . '">
							</div>
							<div id="block_description_autre_mobilier_' . $noLigne . '" class="' . $d_none_descp_autre_mobilier . '">
								<label class="form-check-label" for="description_autre_mobilier[' . $noLigne . ']">Description du autre</label>
								<textarea class="form-control" name="description_autre_mobilier[' . $noLigne . ']" rows="2">$description_autre_mobilier</textarea>
							</div>
						</div>

						<div class="col-md-8">
							<h3>Dépot</h3>
							<div class="row row-cols-2">
								<div class="col">
									<div class="form-floating">
										<input type="text" class="form-control" name="num_inventaire[' . $noLigne . ']" placeholder="Numéro" value="">
										<label for="num_inventaire[' . $noLigne . ']">Numéro du dépôt</label>
									</div>
								</div>
								<div class="col">
									<div class="form-floating">
										<input type="text" name="commune_depot[' . $noLigne . ']" id="commune_depot_' . $noLigne . '" class="form-control" placeholder="Rechercher une commune ..." autocomplete="off" onclick="recherche_commune_depot(' . $noLigne . ');" value="">
										<label for="commune_depot[' . $noLigne . ']">Rechercher une commune</label>
									</div>
									<div class="col-md-auto">
										<div class="list-group" id="show-list-depot_' . $noLigne . '"></div>
									</div>
								</div>
								<div class="col my-2">
									<div class="form-floating">
										<input type="text" class="form-control" name="adresse_depot[' . $noLigne . ']" placeholder="Adresse" value="">
										<label for="adresse_depot[' . $noLigne . ']">Adresse du dépôt</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br />

				<div class="container">
					<h3>Atteinte invalidante</h3>
					<p class="text-muted">Vous pouvez activer ou désactiver les différents boutons avec la barre espace.</p>
					<div class="row">
						<div class="col-md-6">
							<div class="form-check form-switch">
								<label class="form-check-label" for="' . $key['name'] . '[' . $noLigne . ']">$key['nom']</label>
								<input class="form-check-input" type="checkbox">
							</div>
							<div class="' . $d_none_pathologie . '" id="block_pathologies_infectieuses_' . $noLigne . '" style="width: 50%; background-color: white;">
								<div class="form-check form-switch" style="padding-left: 75px;">
									<?php for ($i = 0; $i<5; $i++): ?>
										<label class="form-check-label" for="PI_' . $key2['name'] . '[' . $noLigne . ']">$key2['type_pathologie']</label>
										<input class="form-check-input" type="checkbox" name="PI_' . $key2['name'] . '[' . $noLigne . ']" value="' . $key2['id_pathologie'] . '">
									<?php endfor; ?>
								</div>
							</div>
							<div id="block_description_autre_atteinte_' . $noLigne . '" class="' . $d_none_autre_atteinte . '">
								<label for="description_autre_atteinte[' . $noLigne . ']">Description du autre</label>
								<textarea class="form-control" name="description_autre_atteinte[' . $noLigne . ']" rows="2"></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/crane.png?1621418029" alt="Crâne">
									<img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/superieurdroit.png?1621418019" alt="Membre supérieur droit">
									<img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/superieurgauche.png?1621418019" alt="Membre supérieur gauche">
									<img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/bassin.png?1621418020" alt="Tronc bassin">
									<img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/inferieurdroit.png?1621418030" alt="Membre inférieur droit">
									<img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/inferieurgauche.png?1621418016" alt="Membre inférieur gauche">
								</div>
								<div class="col-md-6">
									<div class="col-auto" style="padding: 0px; margin: 0px; width: 50px; height: 75px; margin-left: 95px; transform: rotate(90deg); transform-origin: left top 0;">
										Béquillage<br />
										Orthèse<br />
										Prothèse<br />
										Attèle<br />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<?php for ($i=0; $i < 5; $i++): ?>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="checkbox" name="' . $key2['name'] . '[' . $noLigne . '][' . $key['id'] . ']" value="' . $key2['id'] . '">
										</div>
									<?php endfor; ?>
								</div>
								<div class="col-md-6">
									<?php for ($i=0; $i < 4; $i++): ?>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="checkbox" name="' . $key2['name'] . '[' . $noLigne . '][' . $key['id'] . ']" value="' . $key2['id_appareil_compensatoire'] . '">
										</div>
									<?php endfor; ?>
								</div>
							</div>
						</div>
						<label for="commentaire_appareil[' . $noLigne . ']">Commentaire sur l\'appareil de compensation</label>
						<textarea class="form-control" name="commentaire_appareil[' . $noLigne . ']" rows="2">OSKUR</textarea>
						<label for="commentaire_diagnostic[' . $noLigne . ']">Commentaire du diagnostic</label>
						<textarea class="form-control" name="commentaire_diagnostic[' . $noLigne . ']" rows="2" placeholder="** INFO ** Les différentes atteintes cochées seront ajoutées à la description"></textarea>
					</div>
				</div>
				<br />
				<div style="text-align:center">
					<button type="button" class="btn btn-primary" id="btnAjouter' . $noLigne . '" onclick="ajouterLigne(' . ($noLigne + 1) . ');"><i class="bi bi-plus"></i></button>
					<button type="button" class="btn btn-danger" id="btnSupp' . $noLigne . '" onclick="supprimerLigne(' . $noLigne . ');"><i class="bi bi-x"></i></button>
				</div>
			</div>

		</div>
		<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 10px;">
			<?= Form::submit('confirm_sujet_handicape', 'Ajouter', array('class' => 'btn btn-success')); ?>
		</div>
		<?= Form::close(); ?>

	</div>
</div>

<?php
//Fonction permettant d'afficher un message d'alert
function alertBootstrap($text, $color)
{
	echo '
	<div class="alert alert-' . $color . ' alert-dismissible text-center my-2 fade show" role="alert">
	' . $text . '
	<button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
	</div>';
}
?>

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