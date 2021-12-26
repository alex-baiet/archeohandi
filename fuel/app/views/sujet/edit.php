<?php

use Fuel\Core\Asset;
use Fuel\Core\DB;
use Fuel\Core\Form;

foreach ($modif_sujet as $key) : ?>
	<!-- Entête de la page -->
	<div class="container">
		<h1 class="m-2">Modifier le sujet N°<?= $key['id_sujet_handicape']; ?>
			<a class="btn btn-sm btn-secondary" href="/public/sujet/edit/<?= $key['id'] ?>">Rafraichir la page
				<i class="bi bi-arrow-repeat"></i>
			</a>
		</h1>
		<p class="text-muted">Ici vous pouvez modifier le sujet handicapé.</p>
	</div>
	
	<!-- Contenu de la page -->
	<div class="container" style="background-color: #F5F5F5;">

		<?= Form::open(array('action' => 'sujet/edit/' . $key['id'] . '', 'method' => 'POST'));
		//Permet de vérifier si dans l'url il y a les différentes options et si oui, cela appel une fonction qui permet d'afficher un message  

		array_key_exists('erreur_alpha_sujet', $_GET) ? alertBootstrap('L\'identifiant du sujet ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
		array_key_exists('erreur_alpha_num_inventaire', $_GET) ? alertBootstrap('Le numéro inventaire ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
		array_key_exists('erreur_alpha_adresse_depot', $_GET) ? alertBootstrap('L\'adresse du dépôt ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: àáâãäåçèéêëìíîïðòóôõöùúûüýÿ sont autorisés)', 'info') : null;
		array_key_exists('erreur_NMI_vide', $_GET) ? alertBootstrap('Le NMI est vide', 'danger') : null;
		array_key_exists('erreur_NMI', $_GET) ? alertBootstrap('Le NMI doit être un chiffre', 'danger') : null;
		array_key_exists('erreur_chrono_vide', $_GET) ? alertBootstrap('La période chronologique n\'est pas sélectionnée', 'danger') : null;
		array_key_exists('erreur_chrono', $_GET) ? alertBootstrap('La période chronologique n\'existe pas', 'danger') : null;
		array_key_exists('erreur_sujet_vide', $_GET) ? alertBootstrap('L\'identifiant du sujet est vide', 'danger') : null;
		array_key_exists('erreur_sexe_vide', $_GET) ? alertBootstrap('Veuillez sélectionner le sexe', 'danger') : null;
		array_key_exists('erreur_sexe', $_GET) ? alertBootstrap('Le sexe ne correspond pas aux propositions', 'danger') : null;
		array_key_exists('erreur_age_min', $_GET) ? alertBootstrap('L\'âge minimum doit être compris entre 0 et 130', 'danger') : null;
		array_key_exists('erreur_age_max', $_GET) ? alertBootstrap('L\'âge maximum doit être compris entre 0 et 130 et doit être supérieur à l\'âge minimum', 'danger') : null;
		array_key_exists('erreur_datation_debut', $_GET) ? alertBootstrap('La datation du début n\'est pas valide (nombre autorisé)', 'danger') : null;
		array_key_exists('erreur_datation_fin', $_GET) ? alertBootstrap('La datation de fin n\'est pas valide (nombre autorisé et supérieur à la datation de début)', 'danger') : null;
		array_key_exists('erreur_depot_vide', $_GET) ? alertBootstrap('Le type de dépôt est vide', 'danger') : null;
		array_key_exists('erreur_depot', $_GET) ? alertBootstrap('Le type de dépôt ne correspond pas aux propositions', 'danger') : null;
		array_key_exists('erreur_sepulture_vide', $_GET) ? alertBootstrap('Le type de sépulture est vide', 'danger') : null;
		array_key_exists('erreur_sepulture', $_GET) ? alertBootstrap('Le type de sépulture ne correspond pas aux propositions', 'danger') : null;
		array_key_exists('erreur_contexte_normatif', $_GET) ? alertBootstrap('Le contexte normatif ne correspond pas aux propositions', 'danger') : null;
		array_key_exists('erreur_milieu_vie', $_GET) ? alertBootstrap('Le milieu de vie ne correspond pas aux propositions', 'danger') : null;
		array_key_exists('erreur_contexte', $_GET) ? alertBootstrap('Le contexte de la tombe ne correspond pas aux propositions', 'danger') : null;
		array_key_exists('erreur_description_mobilier_vide', $_GET) ? alertBootstrap('La description de l\'autre mobilier est vide alors que son option est cochée', 'info') : null;
		array_key_exists('erreur_pathologie', $_GET) ? alertBootstrap('Vous avez activé la pathologie infectieuse sans indiquer laquelle. Veuillez selectionner au moins une pathologie', 'info') : null;
		array_key_exists('erreur_description_autre_atteinte', $_GET) ? alertBootstrap('Vous avez activé une autre atteinte sans indiquer laquelle. Veuillez remplir le champs texte', 'info') : null;
		array_key_exists('erreur_commune_depot', $_GET) ? alertBootstrap('La commune du dépôt n\'existe pas. Veuillez changer la commune', 'danger') : null;

		//Initialisation des variables permettant de cacher certains champs
		$d_none_descp_autre_mobilier = $d_none_pathologie = $d_none_autre_atteinte = 'd-none';

		//Permet de vérifier si il y a c'est option et si oui cela permet de savoir quelle option est cochée pour l'afficher
		if (array_key_exists('chronologie', $_GET)) : $chronologie_check = $_GET['chronologie'];
		else : $chronologie_check = null;
		endif;
		if (array_key_exists('sexe', $_GET)) : $sexe_check = $_GET['sexe'];
		else : $sexe_check = null;
		endif;
		if (array_key_exists('type_depot', $_GET)) : $type_depot_check = $_GET['type_depot'];
		else : $type_depot_check = null;
		endif;
		if (array_key_exists('type_sepulture', $_GET)) : $type_sepulture_check = $_GET['type_sepulture'];
		else : $type_sepulture_check = null;
		endif;
		if (array_key_exists('contexte_normatif', $_GET)) : $contexte_normatif_check = $_GET['contexte_normatif'];
		else : $contexte_normatif_check = null;
		endif;
		if (array_key_exists('milieu_vie', $_GET)) : $milieu_vie_check = $_GET['milieu_vie'];
		else : $milieu_vie_check = null;
		endif;
		if (array_key_exists('contexte', $_GET)) : $contexte_check = $_GET['contexte'];
		else : $contexte_check = null;
		endif;


		//Récupération des informations du groupe du sujet
		$info_groupe_sujet = DB::query('SELECT NMI,id_chronologie,id_operation FROM groupe_sujets WHERE id_groupe_sujets=' . $key['id_groupe_sujets'] . '')->execute();
		$NMI = $info_groupe_sujet->_results[0]['NMI'];
		$id_chronologie = $info_groupe_sujet->_results[0]['id_chronologie'];
		$id_buton_retour = $info_groupe_sujet->_results[0]['id_operation'];

		//Permet de récupérer les deux dates de la datation
		preg_match_all("(-?[0-9]+)", $key['datation'], $datation);
		//Et si la datation existe, elle initialise les deux dates de début et de fin pour les afficher et si non elles sont égales à 0
		if (!empty($datation[0])) : $datation_debut = $datation[0][0];
			$datation_fin = $datation[0][1];
		else : $datation_debut = 0;
			$datation_fin = 0;
		endif;


		//Vérifie si le sujet possède un dépot et si oui initalise les variables et si non les met vide
		if (!empty($depot_sujet)) :
			foreach ($depot_sujet as $key2) {
				$num_inventaire = $key2['num_inventaire'];
				$adresse_depot = $key2['adresse'];

				if (!empty($key2['id_commune'])) :
					$query = DB::query('SELECT nom FROM commune WHERE id=' . $key2['id_commune'] . ' ');
					$nom_commune_depot = $query->execute();
					$nom_commune_depot = $nom_commune_depot->_results[0]['nom'];
				else : $nom_commune_depot = "";
				endif;
			}
		else : $num_inventaire = $nom_commune_depot = $adresse_depot = "";
		endif;

		//Pour chaque input, l'option value vérifie dans un premier si il existe dans l'url son champs qui lui est propre et affiche sa valeur en cas d'erreur et sinon elle affiche la valeur du sujet pour ce champs là.
		?>
		<h2 class="text-center">Groupe de sujets</h2>
		<div class="row" style="margin-bottom: 5px;">
			<div class="col-md-6">
				<div class="form-floating">
					<input type="number" class="form-control" name="NMI" placeholder="183" value="<?php if (array_key_exists('NMI', $_GET)) : echo $_GET['NMI'];
																																												else : echo $NMI;
																																												endif; ?>">
					<?= Form::label('NMI', 'NMI'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-floating">
					<select class="form-select" name="nom_chronologie" aria-label="select_nom_chronologie">
						<option value="">Sélectionner</option>
						<?php foreach ($chronologie as $key_chrono) :
							echo '<option value="' . $key_chrono['id_chronologie'] . '"';
							if (($chronologie_check == $key_chrono['id_chronologie']) || ($id_chronologie == $key_chrono['id_chronologie'])) {
								echo 'selected';
							}
							echo '>' . $key_chrono['nom_chronologie'] . '</option>';
						endforeach;
						?>
					</select>
					<?= Form::label('Période chronologique', 'nom_chronologie'); ?>
				</div>
			</div>
		</div>
		<h3 class="text-center">Sujet handicapé</h3>
		<div class="row my-2">
			<div class="col-md-6">
				<div class="form-floating">
					<input type="text" class="form-control" name="id_sujet" placeholder="183" value="<?php if (array_key_exists('id_sujet', $_GET)) : echo $_GET['id_sujet'];
																																														else : echo $key['id_sujet_handicape'];
																																														endif; ?>">
					<?= Form::label('Identifiant du sujet', 'id_sujet'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-floating">
					<select class="form-select" name="sexe" aria-label="select_sexe">
						<option value="">Sélectionner</option>
						<option value="Femme" <?php if (($sexe_check == "Femme") || ($key['sexe'] == "Femme")) : echo ' selected';
																	endif; ?>>Femme
						</option>
						<option value="Homme" <?php if (($sexe_check == "Homme") || ($key['sexe'] == "Homme")) : echo ' selected';
																	endif; ?>>Homme
						</option>
						<option value="Indéterminé" <?php if (($sexe_check == "Indéterminé") || ($key['sexe'] == "Indéterminé")) : echo ' selected';
																				endif; ?>>Indéterminé
						</option>
					</select>
					<?= Form::label('Sexe', 'sexe'); ?>
				</div>
			</div>
		</div>
		<div class="row my-2">
			<div class="col-md-6">
				<div class="form-floating">
					<input type="number" class="form-control" min="0" max="130" name="age_min" placeholder="183" value="<?php if (array_key_exists('age_min', $_GET)) : echo $_GET['age_min'];
																																																							else : echo  $key['age_min'];
																																																							endif; ?>">
					<?= Form::label('Age minimum au décès', 'age_min'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-floating">
					<input type="number" class="form-control" min="0" max="130" name="age_max" placeholder="183" value="<?php if (array_key_exists('age_max', $_GET)) : echo $_GET['age_max'];
																																																							else : echo  $key['age_max'];
																																																							endif; ?>">
					<?= Form::label('Age maximum au décès', 'age_max'); ?>
				</div>
			</div>
		</div>
		<div class="row my-2">
			<div class="col-md-6">
				<div class="form-floating">
					<input type="number" class="form-control" name="datation_debut" placeholder="183" value="<?php if (array_key_exists('datation_debut', $_GET)) : echo $_GET['datation_debut'];
																																																		else : echo $datation_debut;
																																																		endif;  ?>">
					<?= Form::label('Datation début', 'datation_debut'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-floating">
					<input type="number" class="form-control" name="datation_fin" placeholder="183" value="<?php if (array_key_exists('datation', $_GET)) : echo $_GET['datation'];
																																																	else : echo $datation_fin;
																																																	endif;  ?>">
					<?= Form::label('Datation fin', 'datation_fin'); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-floating">
					<select class="form-select" name="type_depot" aria-label="select_depot" style="margin-top: 2.5%;margin-bottom: 2.5%;">
						<option value="4">Sélectionner</option>
						<?php foreach ($type_depot as $key_depot) :
							echo '<option value="' . $key_depot['id'] . '"';
							if (($type_depot_check == $key_depot['id']) || ($key['id_type_depot'] == $key_depot['id'])) {
								echo 'selected';
							}
							echo '>' . $key_depot['nom'] . '</option>';
						endforeach;
						?>
					</select>
					<?= Form::label('Type de dépôt', 'type_depot'); ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating">
					<select class="form-select" name="type_sepulture" aria-label="select_sep" style="margin-top: 2.5%;margin-bottom: 2.5%;">
						<option value="4">Sélectionner</option>
						<?php foreach ($type_sepulture as $key_sepult) :
							echo '<option value="' . $key_sepult['id'] . '"';
							if (($type_sepulture_check == $key_sepult['id']) || ($key['id_sepulture'] == $key_sepult['id'])) {
								echo 'selected';
							}
							echo '>' . $key_sepult['nom'] . '</option>';
						endforeach;
						?>
					</select>
					<?= Form::label('Type de sépulture', 'type_sepulture'); ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating">
					<select class="form-select" name="contexte_normatif" aria-label="select_contexte_normatif" style="margin-top: 2.5%;margin-bottom: 2.5%;">
						<option value="">Sélectionner</option>
						<option value="Standard" <?php if (($contexte_normatif_check  == "Standard") || ($key['contexte_normatif'] == "Standard")) : echo ' selected';
																			endif; ?>>Standard</option>
						<option value="Atypique" <?php if (($contexte_normatif_check  == "Atypique") || ($key['contexte_normatif'] == "Atypique")) : echo ' selected';
																			endif; ?>>Atypique</option>
					</select>
					<?= Form::label('Contexte normatif', 'contexte_normatif'); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-floating">
					<select class="form-select" name="milieu_vie" aria-label="select_milieu_vie">
						<option value="">Sélectionner</option>
						<option value="Rural" <?php if (($milieu_vie_check == "Rural") || ($key['milieu_vie'] == "Rural")) : echo ' selected';
																	endif; ?>>Rural</option>
						<option value="Urbain" <?php if (($milieu_vie_check == "Urbain") || ($key['milieu_vie'] == "Urbain")) : echo ' selected';
																		endif; ?>>Urbain</option>
					</select>
					<?= Form::label('Milieu de vie', 'milieu_vie'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-floating">
					<select class="form-select" name="contexte" aria-label="select_contexte">
						<option value="">Sélectionner</option>
						<option value="Funeraire" <?php if (($contexte_check == "Funeraire") || ($key['contexte'] == "Funeraire")) : echo 'selected';
																			endif; ?>>Funéraire</option>
						<option value="Domestique" <?php if (($contexte_check == "Domestique") ||  ($key['contexte'] == "Domestique")) : echo 'selected';
																				endif; ?>>Domestique</option>
						<option value="Autre" <?php if (($contexte_check == "Autre") ||  ($key['contexte'] == "Autre")) : echo 'selected';
																	endif; ?>>Autre</option>
					</select>
					<?= Form::label('Contexte de la tombe', 'contexte'); ?>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<?= Form::label('Commentaire', 'commentaire_contexte'); ?>
			<div class="input-group">
				<textarea class="form-control" name="commentaire_contexte" rows="2"><?php if (array_key_exists('commentaire_contexte', $_GET)) : echo $_GET['commentaire_contexte'];
																																						else : echo $key['commentaire_contexte'];
																																						endif; ?></textarea>
			</div>
		</div>
	<?php endforeach; ?>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<h3>Accessoire</h3>
				<?php foreach ($accessoire as $key) {
					echo '
				<div class="form-check form-switch">
				<label class="form-check-label" for="' . $key['name'] . '">' . $key['nom'] . '</label>
				<input class="form-check-input" type="checkbox" name="' . $key['name'] . '" value="' . $key['id'] . '"';
					if ($key['name'] == "autre_mobilier") : echo 'id="autre_mobilier" onchange="function_toggle();"';
					endif;
					if (array_key_exists('erreur', $_GET)) :
						if ($key['id'] == 1 && array_key_exists('accessoire_vestimentaire_et_parure', $_GET)) : echo "checked";
						endif;
						if ($key['id'] == 2 && array_key_exists('armement_objet_de_prestige', $_GET)) : echo "checked";
						endif;
						if ($key['id'] == 3 && array_key_exists('depot_de_recipient', $_GET)) : echo "checked";
						endif;
						if ($key['id'] == 4 && array_key_exists('autre_mobilier', $_GET)) : echo "checked";
							$d_none_descp_autre_mobilier = "";
							$description_autre_mobilier = $_GET['description_autre_mobilier'];
						else : $d_none_descp_autre_mobilier = "d-none";
							$description_autre_mobilier = "";
						endif;
					else : if (!empty($accessoire_sujet)) : foreach ($accessoire_sujet as $key2) {
								if ($key2['id_mobilier'] == $key['id']) : echo "checked";
								endif;
								if ($key2['id_mobilier'] == 4) : $d_none_descp_autre_mobilier = "";
									$description_autre_mobilier = $key2['description'];
								endif;
							}
						else : $d_none_descp_autre_mobilier = "d-none";
							$description_autre_mobilier = "";
						endif;
					endif;
					echo '>
				</div>';
				} ?>
				<div id="block_description_autre_mobilier" class='<?= $d_none_descp_autre_mobilier; ?>'>
					<label class="form-check-label" for="description_autre_mobilier">Description du autre</label>
					<textarea class="form-control" name="description_autre_mobilier" rows="2"><?= ""//$description_autre_mobilier; ?></textarea>
				</div>
			</div>
			<div class="col-md-8">
				<h3>Dépot</h3>
				<div class="row row-cols-2">
					<div class="col">
						<div class="form-floating">
							<input type="text" class="form-control" name="num_inventaire" placeholder="Numéro" value="<?php if (array_key_exists('num_inventaire', $_GET)) : echo $_GET['num_inventaire'];
																																																				else : echo $num_inventaire;
																																																				endif; ?>">
							<label for="num_inventaire">Numéro du dépôt</label>
						</div>
					</div>
					<div class="col">
						<div class="form-floating">
							<input type="text" name="commune_depot" id="commune_depot" class="form-control" placeholder="Rechercher une commune ..." autocomplete="off" value="<?php if (array_key_exists('commune_depot', $_GET)) : echo $_GET['commune_depot'];
																																																																																	else : echo $nom_commune_depot;
																																																																																	endif; ?>">
							<label for="commune_depot">Rechercher une commune</label>
						</div>
						<div class="col-md-auto">
							<div class="list-group" id="show-list-depot'"></div>
						</div>
					</div>
					<div class="col my-2">
						<div class="form-floating">
							<input type="text" class="form-control" name="adresse_depot" placeholder="Adresse" value="<?php if (array_key_exists('adresse_depot', $_GET)) : echo $_GET['adresse_depot'];
																																																				else : echo $adresse_depot;
																																																				endif; ?>">
							<label for="adresse_depot">Adresse du dépôt</label>
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
			<?php foreach ($diagnostic as $key) {
				echo '
			<div class="col-md-6">
			<div class="form-check form-switch">
			<label class="form-check-label" for="' . $key['name'] . '">' . $key['nom'] . '</label>
			<input class="form-check-input" type="checkbox"';
				if ($key['nom'] == "Pathologie infectieuse") : echo ' id="pathologies_infectieuses"';
				elseif ($key['nom'] == "Autre") : echo '  id="autre_atteinte"';
				endif;
				echo 'name="' . $key['name'] . '" value="' . $key['id'] . '"';
				if ($key['nom'] == "Pathologie infectieuse") : echo ' onchange="function_toggle();"';
				endif;
				if ($key['nom'] == "Autre") : echo ' onchange="function_toggle();"';
				endif;

				if (array_key_exists('erreur', $_GET)) :
					if ($key['id'] == 1 && array_key_exists('trepanation', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 2 && array_key_exists('edentement_complet', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 3 && array_key_exists('atteinte_neurale', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 4 && array_key_exists('scoliose_severe', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 5 && array_key_exists('paget', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 6 && array_key_exists('dish', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 7 && array_key_exists('rachitisme', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 8 && array_key_exists('nanisme', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 9 && array_key_exists('pathologie_infectieuse', $_GET)) : echo "checked";
						$d_none_pathologie = "";
					else :  $d_none_pathologie = "d-none";
					endif;
					if ($key['id'] == 10 && array_key_exists('fracture_non_reduite', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 11 && array_key_exists('amputation', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 12 && array_key_exists('pathologie_severe', $_GET)) : echo "checked";
					endif;
					if ($key['id'] == 13 && array_key_exists('autre_atteinte', $_GET)) : echo "checked";
						$d_none_autre_atteinte = "";
						$description_autre_atteinte = $_GET['description_autre_atteinte'];
					else : $d_none_autre_atteinte = "d-none";
						$description_autre_atteinte = "";
					endif;
				else : if (!empty($atteinte_invalidante_sujet)) : foreach ($atteinte_invalidante_sujet as $key2) {
							$description_autre_atteinte = "";
							if ($key2['id_diagnostic'] == $key['id']) : echo "checked";
							endif;
							if ($key2['id_diagnostic'] == 9) : $d_none_pathologie = "";
							endif;
							if ($key2['id_diagnostic'] == 13) : $d_none_autre_atteinte = "";
								$description_autre_atteinte = $key2['description_autre'];
							endif;
						}
					else : $d_none_pathologie = $d_none_autre_atteinte = "d-none";
						$description_autre_atteinte = "";
					endif;
				endif;
				echo '>
				</div>';
				if ($key['nom'] == "Pathologie infectieuse") :
					echo '
					<div class="' . $d_none_pathologie . '" id="block_pathologies_infectieuses" style="width: 30%; background-color: white;">';
					foreach ($pathologie as $key2) {
						echo '
						<div class="form-check form-switch" style="padding-left: 75px;">
						<label class="form-check-label" for="PI_' . $key2['name'] . '">' . $key2['type_pathologie'] . '</label>
						<input class="form-check-input" type="checkbox" name="PI_' . $key2['name'] . '" value="' . $key2['id_pathologie'] . '"';
						if (array_key_exists('erreur', $_GET)) :
							if ($key2['id_pathologie'] == 1 && array_key_exists('PI_lepre', $_GET)) : echo 'checked';
							endif;
							if ($key2['id_pathologie'] == 2 && array_key_exists('PI_syphilis', $_GET)) : echo 'checked';
							endif;
							if ($key2['id_pathologie'] == 3 && array_key_exists('PI_variole', $_GET)) : echo 'checked';
							endif;
							if ($key2['id_pathologie'] == 4 && array_key_exists('PI_autre_pathologie_infectieuse', $_GET)) : echo 'checked';
							endif;
						else : if (!empty($atteinte_invalidante_sujet)) : foreach ($atteinte_invalidante_sujet as $key3) {
									if ($key3['id_pathologie'] == $key2['id_pathologie']) : echo "checked";
									endif;
								}
							endif;
						endif;
						echo '>
					</div>';
					}

					echo '
				</div>';
				elseif ($key['nom'] == "Autre") :
					echo '
				<div id="block_description_autre_atteinte" class="' . $d_none_autre_atteinte . '">
				<label for="description_autre_atteinte">Description du autre</label>
				<textarea class="form-control" name="description_autre_atteinte" rows="2">';
					echo $description_autre_atteinte;
					echo '</textarea>
				</div>';
				endif;
				echo '
			</div>
			<div class="col-md-6">';
				if ($key['name'] == "trepanation") :
					echo '
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
			 <div class="col-auto" style="padding: 0px; margin: 0px; width: 50px; height: 75px; margin-left: 95px; transform: rotate(90deg); transform-origin: left top 0;">Béquillage<br/>Orthèse<br/>Prothèse<br/>Attèle<br/></div>
			 </div>
			 </div>';
				endif;
				if ($key['name'] != "trepanation" && $key['name'] != "edentement_complet") :
					echo '

				<div class="row">
				<div class="col-md-6">';
					foreach ($localisation_atteinte as $key2) {
						echo '
					<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" name="' . $key2['name'] . '[' . $key['id'] . ']" value="' . $key2['id'] . '"';
						if (array_key_exists('erreur', $_GET)) :
							if ($key2['id'] == 1 && array_key_exists('crane_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id'] == 2 && array_key_exists('msd_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id'] == 3 && array_key_exists('msg_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id'] == 4 && array_key_exists('tronc_bassin_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id'] == 5 && array_key_exists('mid_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id'] == 6 && array_key_exists('mig_' . $key['id'], $_GET)) : echo "checked";
							endif;
						else : if (!empty($localisation_sujet)) : foreach ($localisation_sujet as $key3) {
									if ($key['id'] == $key3['id_diagnostic']) : if ($key3['id_localisation_atteinte'] == $key2['id']) : echo 'checked';
										endif;
									endif;
								}
							endif;
						endif;
						echo '>
				</div>';
					}
					echo '
			</div>
			<div class="col-md-6">';
					foreach ($appareil_compensatoire as $key2) {
						echo '
				<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" name="' . $key2['name'] . '[' . $key['id'] . ']" value="' . $key2['id_appareil_compensatoire'] . '"';
						if (array_key_exists('erreur', $_GET)) :
							if ($key2['id_appareil_compensatoire'] == 1 && array_key_exists('attele_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id_appareil_compensatoire'] == 2 && array_key_exists('prothese_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id_appareil_compensatoire'] == 3 && array_key_exists('orthese_' . $key['id'], $_GET)) : echo "checked";
							endif;
							if ($key2['id_appareil_compensatoire'] == 4 && array_key_exists('bequillage_' . $key['id'], $_GET)) : echo "checked";
							endif;
						else : if (!empty($appareil_sujet)) : foreach ($appareil_sujet as $key3) {
									if ($key['id'] == $key3['id_diagnostic']) : if ($key3['id_appareil_compensatoire'] == $key2['id_appareil_compensatoire']) : echo 'checked';
										endif;
									endif;
									$commentaire_appareil = $key3['commentaire'];
								}
							else : $commentaire_appareil = "";
							endif;
						endif;
						echo '>
				</div>';
					}
					echo '
			</div>
			</div>';
				endif;
				echo '
			</div>';
			}
			echo '
		<label for="commentaire_appareil">Commentaire sur l\'appareil de compensation</label>
		<textarea class="form-control" name="commentaire_appareil" rows="2">';
			if (array_key_exists('commentaire_appareil', $_GET)) : echo $_GET['commentaire_appareil'];
			else : echo $commentaire_appareil;
			endif;
			echo '</textarea>';
			echo '
		<label for="commentaire_diagnostic">Commentaire du diagnostic</label>
		<textarea class="form-control" name="commentaire_diagnostic" rows="2" placeholder="** INFO ** Les différentes atteintes cochées seront ajoutées à la description">';
			if (array_key_exists('commentaire_diagnostic', $_GET)) : echo $_GET['commentaire_diagnostic'];
			else : echo $commentaire_diagnostic;
			endif;
			echo '</textarea>
		</div>
		</div>
		<br/>';
			?>
			<div class="d-grid gap-2 d-md-block">
				<a class="btn btn-secondary" href="/public/operations/view/<?= $id_buton_retour; ?>" role="button">Retour</a>
			</div>
			<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 10px;">
				<?= Form::submit('modif_sujet', 'Modifier', array('class' => 'btn btn-success')); ?>
			</div>
			<?= Form::close(); ?>
		</div>


		<?= Asset::js('script_recherche.js'); ?>
		<script type="text/javascript">
			//Permet d'afficher les informations cachées (champs texte, sélection d'option)
			function function_toggle() {
				if (document.getElementById("autre_mobilier").checked === true) {
					$("#block_description_autre_mobilier").removeClass("d-none");
				} else {
					$("#block_description_autre_mobilier").addClass("d-none");
				}

				if (document.getElementById("autre_atteinte").checked === true) {
					$("#block_description_autre_atteinte").removeClass("d-none");
				} else {
					$("#block_description_autre_atteinte").addClass("d-none");
				}

				if (document.getElementById("pathologies_infectieuses").checked === true) {
					$("#block_pathologies_infectieuses").removeClass("d-none");
				} else {
					$("#block_pathologies_infectieuses").addClass("d-none");
				}
			}
		</script>
		<?php
		//Fonction permettant d'afficher un message d'alert
		function alertBootstrap($text, $color)
		{
			echo '<div class="alert alert-' . $color . ' alert-dismissible text-center my-2 fade show" role="alert">
	' . $text . '
	<button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
	</div>';
		} ?>