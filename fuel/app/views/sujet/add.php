<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Model\Chronologie;
use Model\Mobilier;
use Model\Typedepot;
use Model\Typesepulture;

?>
<!-- Contenu de la page -->
<div class="container">
	<h1 class="m-2">Ajouter des sujets handicapés <a class="btn btn-sm btn-secondary" href="/public/add/sujet/<?= $id; ?>">Rafraichir la page <i class="bi bi-arrow-repeat"></i></a></h1>
	<p class="text-muted">Ici vous pouvez ajouter des sujets handicapés.</p>

	<div style="background-color: #F5F5F5; padding: 10px;">
		<?= Form::open(array('action' => 'add/sujet/' . $id . '', 'method' => 'POST')); ?>
		<div class="contenu" id="contenu">
			<div class="col-auto">
				<h2 class="text-center">Groupe du sujet</h2>

				<div class="row g-2">
					<!-- NMI -->
					<div class="col-md-6">
						<div class="form-floating">
							<input type="number" class="form-control" name="nmi" placeholder="" value="">
							<label for="NMI">NMI</label>
						</div>
					</div>
					
					<!-- Chronologie -->
					<div class="col-md-6">
						<?= Chronologie::generateSelect() ?>
					</div>
				</div>

				<h3 class="text-center my-2">Sujet handicapé #</h3>
				<div class="row g-2">
					<div class="row g-2">

						<!-- Identifiant -->
						<div class="col-md-6">
							<div class="form-floating">
								<input type="text" class="form-control" name="id_sujet" placeholder="" value="">
								<label for="id_sujet">Identifiant du sujet</label>
							</div>
						</div>

						<!-- Sexe -->
						<div class="col-md-6">
							<div class="form-floating">
								<select class="form-select" name="sexe" aria-label="select_sexe">
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
						<!-- Âge minimum estimé de décès -->
						<div class="col-md-6">
							<div class="form-floating">
								<?= Form::input("age_min", null, array("type" => "number", "class" => "form-control", "min" => "0", "max" => "130", "placeholder" => "")); ?>
								<?= Form::label("Âge minimum au décès", "age_min"); ?>
							</div>
						</div>

						<!-- Âge maximum estimé de décès -->
						<div class="col-md-6">
							<div class="form-floating">
								<?= Form::input("age_max", null, array("type" => "number", "class" => "form-control", "min" => "0", "max" => "130", "placeholder" => "")); ?>
								<?= Form::label("Âge maximum au décès", "age_max"); ?>
							</div>
						</div>
					</div>

					<div class="row g-2">
						<!-- Période minimum estimé -->
						<div class="col-md-6">
							<div class="form-floating">
								<?= Form::input("datation_debut", null, array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
								<?= Form::label("Datation début", "datation_debut"); ?>
							</div>
						</div>

						<!-- Période maximum estimé -->
						<div class="col-md-6">
							<div class="form-floating">
								<?= Form::input("datation_fin", null, array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
								<?= Form::label("Datation fin", "datation_fin"); ?>
							</div>
						</div>

					</div>
				</div>

				<div class="row my-3">
					<!-- Type de dépôt -->
					<div class="col-md-4">
						<?= Typedepot::generateSelect(); ?>
					</div>

					<!-- Type de sepulture -->
					<div class="col-md-4">
						<?= Typesepulture::generateSelect(); ?>
					</div>

					<!-- Contexte normatif -->
					<div class="col-md-4">
						<div class="form-floating">
							<?= Form::select(
								"contexte_normatif",
								"",
								array( // Les options
									"" => "Sélectionner",
									"Standard" => "Standard",
									"Atypique" => "Atypique"),
								array("class" => "form-select"));
							?>
							<?= Form::label("Contexte normatif", "contexte_normatif") ?>
						</div>
					</div>
				</div>

				<div class="row g-2">
					<!-- Milieu de vie -->
					<div class="col-md-6">
						<div class="form-floating">
							<?= Form::select(
								"milieu_vie",
								"",
								array(
									"" => "Sélectionner",
									"Rural" => "Rural",
									"Urbain" => "Urbain"),
								array("class" => "form-select"));
							?>
							<?= Form::label("Milieu de vie", "milieu_vie") ?>
						</div>
					</div>

					<!-- Contexte -->
					<div class="col-md-6">
						<div class="form-floating">
							<?= Form::select(
								"contexte",
								"",
								array(
									"" => "Sélectionner",
									"Funeraire" => "Funéraire",
									"Domestique" => "Domestique",
									"Autre" => "Autre"),
								array("class" => "form-select"));
							?>
							<?= Form::label("Contexte de la tombe", "contexte") ?>
						</div>
					</div>
				</div>

				<!-- Commentaire contexte -->
				<div class="col-md-12">
					<label for="commentaire_contexte">Commentaire</label>
					<div class="input-group">
						<textarea class="form-control" name="commentaire_contexte" rows="2"></textarea>
					</div>
				</div>
				<br />

				<div class="row">
					<!-- Accessoires -->
					<div class="col-md-4">
						<h3>Accessoires</h3>
						<?php foreach (Mobilier::fetchAll() as $mobilier): ?>
							<div class="form-check form-switch">
								<?= Form::label($mobilier->getNom(), "mobilier_{$mobilier->getId()}"); ?>
								<?= Form::input("mobilier_{$mobilier->getId()}", null, array("type" => "checkbox", "class" => "form-check-input")); ?>
							</div>
						<?php endforeach; ?>
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
				<br />

				<!-- Toutes les invalidations du sujets -->
				<h3>Atteinte invalidante</h3>
				<style>
					.th-title-rotate {
						padding: 0px;
						margin: 0px;
						width: 24px;
						height: 80px;
						transform: rotate(90deg);
						transform-origin: 10px 12px;
					}
				</style>
				<table>
					<thead>
						<tr>
							<td style="width: 300px;"></td>
							<td><img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/crane.png?1621418029" alt="Crâne"></td>
							<td><img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/superieurdroit.png?1621418019" alt="Membre supérieur droit"></td>
							<td><img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/superieurgauche.png?1621418019" alt="Membre supérieur gauche"></td>
							<td><img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/bassin.png?1621418020" alt="Tronc bassin"></td>
							<td><img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/inferieurdroit.png?1621418030" alt="Membre inférieur droit"></td>
							<td style="width: 100px;"><img style="width: 50 px; height: 25px; margin-right: 10px;" src="https://archeohandi.huma-num.fr/public/assets/img/inferieurgauche.png?1621418016" alt="Membre inférieur gauche"></td>
							<td><div class="th-title-rotate">Béquillage</div></td>
							<td><div class="th-title-rotate">Orthèse</div></td>
							<td><div class="th-title-rotate">Prothèse</div></td>
							<td><div class="th-title-rotate">Attèle</div></td>
						</tr>
					</thead>
					<tbody>
						<?php for ($i=0; $i < 5; $i++): ?>
							<tr>
								<td>
									<div class="form-check form-switch">
										<label class="form-check-label" for="' . $key['name'] . '[' . $noLigne . ']">$key['nom']</label>
										<input class="form-check-input" type="checkbox">
									</div>
								</td>
								<?php for ($j=0; $j < 10; $j++): ?>
									<td>
										<input class="form-check-input" type="checkbox" name="test" value="TEST">
									</td>
								<?php endfor; ?>
							</tr>
						<?php endfor; ?>
					</tbody>
				</table>
				<!-- Affichage des pathologies
				<div class="' . $d_none_pathologie . '" id="block_pathologies_infectieuses_' . $noLigne . '" style="width: 50%; background-color: white;">
					<div class="form-check form-switch" style="padding-left: 75px;">
						<label class="form-check-label" for="PI_' . $key2['name'] . '[' . $noLigne . ']">$key2['type_pathologie']</label>
						<input class="form-check-input" type="checkbox" name="PI_' . $key2['name'] . '[' . $noLigne . ']" value="' . $key2['id_pathologie'] . '">
					</div>
				</div> -->


				<div class="row">
					<label for="commentaire_appareil[' . $noLigne . ']">Commentaire sur l\'appareil de compensation</label>
					<textarea class="form-control" name="commentaire_appareil[' . $noLigne . ']" rows="2">OSKUR</textarea>
					<label for="commentaire_diagnostic[' . $noLigne . ']">Commentaire du diagnostic</label>
					<textarea class="form-control" name="commentaire_diagnostic[' . $noLigne . ']" rows="2" placeholder="** INFO ** Les différentes atteintes cochées seront ajoutées à la description"></textarea>
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