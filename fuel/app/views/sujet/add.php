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
			if (array_key_exists('nb', $_GET)) {
				//Permet de faire les différentes opérations pour chaque sujet
				for ($i = 1; $i <= $_GET['nb']; $i++) {
					//Permet de vérifier si dans l'url il y a les différentes options et si oui, cela appel une fonction qui permet d'afficher un message d'erreur ou d'information pour chaque sujet
					// array_key_exists('erreur_alpha_sujet_' . $i, $_GET) ? alertBootstrap('L\'identifiant du sujet ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
					// array_key_exists('erreur_alpha_num_inventaire_' . $i, $_GET) ? alertBootstrap('Le numéro inventaire ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
					// array_key_exists('erreur_alpha_adresse_depot_' . $i, $_GET) ? alertBootstrap('L\'adresse du dépôt ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: àáâãäåçèéêëìíîïðòóôõöùúûüýÿ sont autorisés)', 'info') : null;
					// array_key_exists('erreur_NMI_vide_' . $i, $_GET) ? alertBootstrap('Le NMI est vide', 'danger') : null;
					// array_key_exists('erreur_NMI_' . $i, $_GET) ? alertBootstrap('Le NMI doit être un chiffre', 'danger') : null;
					// array_key_exists('erreur_chrono_vide_' . $i, $_GET) ? alertBootstrap('La période chronologique n\'est pas sélectionnée', 'danger') : null;
					// array_key_exists('erreur_chrono_' . $i, $_GET) ? alertBootstrap('La période chronologique n\'existe pas', 'danger') : null;
					// array_key_exists('erreur_sujet_vide_' . $i, $_GET) ? alertBootstrap('L\'identifiant du sujet est vide', 'danger') : null;
					// array_key_exists('erreur_sexe_vide_' . $i, $_GET) ? alertBootstrap('Veuillez sélectionner le sexe', 'danger') : null;
					// array_key_exists('erreur_sexe_' . $i, $_GET) ? alertBootstrap('Le sexe ne correspond pas aux propositions', 'danger') : null;
					// array_key_exists('erreur_age_min_' . $i, $_GET) ? alertBootstrap('L\'âge minimum doit être compris entre 0 et 130', 'danger') : null;
					// array_key_exists('erreur_age_max_' . $i, $_GET) ? alertBootstrap('L\'âge maximum doit être compris entre 0 et 130 et doit être supérieur à l\'âge minimum', 'danger') : null;
					// array_key_exists('erreur_datation_debut_' . $i, $_GET) ? alertBootstrap('La datation du début n\'est pas valide (nombre autorisé)', 'danger') : null;
					// array_key_exists('erreur_datation_fin_' . $i, $_GET) ? alertBootstrap('La datation de fin n\'est pas valide (nombre autorisé et supérieur à la datation de début)', 'danger') : null;
					// array_key_exists('erreur_depot_vide_' . $i, $_GET) ? alertBootstrap('Le type de dépôt est vide', 'danger') : null;
					// array_key_exists('erreur_depot_' . $i, $_GET) ? alertBootstrap('Le type de dépôt ne correspond pas aux propositions', 'danger') : null;
					// array_key_exists('erreur_sepulture_vide_' . $i, $_GET) ? alertBootstrap('Le type de sépulture est vide', 'danger') : null;
					// array_key_exists('erreur_sepulture_' . $i, $_GET) ? alertBootstrap('Le type de sépulture ne correspond pas aux propositions', 'danger') : null;
					// array_key_exists('erreur_contexte_normatif_' . $i, $_GET) ? alertBootstrap('Le contexte normatif ne correspond pas aux propositions', 'danger') : null;
					// array_key_exists('erreur_milieu_vie_' . $i, $_GET) ? alertBootstrap('Le milieu de vie ne correspond pas aux propositions', 'danger') : null;
					// array_key_exists('erreur_contexte_' . $i, $_GET) ? alertBootstrap('Le contexte de la tombe ne correspond pas aux propositions', 'danger') : null;
					// array_key_exists('erreur_description_mobilier_vide_' . $i, $_GET) ? alertBootstrap('La description de l\'autre mobilier est vide alors que son option est cochée', 'info') : null;
					// array_key_exists('erreur_pathologie_' . $i, $_GET) ? alertBootstrap('Vous avez activé la pathologie infectieuse sans indiquer laquelle. Veuillez selectionner au moins une pathologie', 'info') : null;
					// array_key_exists('erreur_description_autre_atteinte_' . $i, $_GET) ? alertBootstrap('Vous avez activé une autre atteinte sans indiquer laquelle. Veuillez remplir le champs texte', 'info') : null;
					// array_key_exists('erreur_commune_depot_' . $i, $_GET) ? alertBootstrap('La commune du dépôt n\'existe pas. Veuillez changer la commune', 'danger') : null;

					//Appel la fonction pour afficher tout les champs pour ajouter un sujet
					echo afficherLigne($i, $chronologie, $type_depot, $type_sepulture, $diagnostic, $accessoire, $localisation_atteinte, $appareil_compensatoire, $pathologie, 2);
				}
			} else {
				echo afficherLigne(1, $chronologie, $type_depot, $type_sepulture, $diagnostic, $accessoire, $localisation_atteinte, $appareil_compensatoire, $pathologie, 0);
			}
			?>
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

//Fonction permettant d'afficher tout les champs pour saisir un sujet qui sera appelé pour chaque sujet
//La variable $para permet de savoir quelle bouton que nous mettons entre le + et x ou juste le + ou rien
function afficherLigne($noLigne, $chronologie, $type_depot, $type_sepulture, $diagnostic, $accessoire, $localisation_atteinte, $appareil_compensatoire, $pathologie, $para)
{

	//Initialisation des variables permettant de stocker le code des foreach
	$chrono_ligne = $depot_ligne = $sepulture_ligne = $diagnostic_ligne = $pathologie_ligne = $accessoire_ligne = $localisation_atteinte_ligne = $appreil_comp_ligne = "";
	//Initialisation des variables pour cacher des morceaux de code comme des champs text
	$d_none_descp_autre_mobilier = $d_none_pathologie = $d_none_autre_atteinte = 'd-none';

	//Permet de vérifier si il y a c'est option et si oui cela permet de savoir quelle option est cochée pour l'afficher
	if (array_key_exists('erreur_sujet_' . $noLigne, $_GET)) : $erreur_operation = 0;
	endif;
	if (array_key_exists('chronologie_' . $noLigne, $_GET)) : $chronologie_check = $_GET['chronologie_' . $noLigne];
	else : $chronologie_check = null;
	endif;
	if (array_key_exists('sexe_' . $noLigne, $_GET)) : $sexe_check = $_GET['sexe_' . $noLigne];
	else : $sexe_check = null;
	endif;
	if (array_key_exists('type_depot_' . $noLigne, $_GET)) : $type_depot_check = $_GET['type_depot_' . $noLigne];
	else : $type_depot_check = null;
	endif;
	if (array_key_exists('type_sepulture_' . $noLigne, $_GET)) : $type_sepulture_check = $_GET['type_sepulture_' . $noLigne];
	else : $type_sepulture_check = null;
	endif;
	if (array_key_exists('contexte_normatif_' . $noLigne, $_GET)) : $contexte_normatif_check = $_GET['contexte_normatif_' . $noLigne];
	else : $contexte_normatif_check = null;
	endif;
	if (array_key_exists('milieu_vie_' . $noLigne, $_GET)) : $milieu_vie_check = $_GET['milieu_vie_' . $noLigne];
	else : $milieu_vie_check = null;
	endif;
	if (array_key_exists('contexte_' . $noLigne, $_GET)) : $contexte_check = $_GET['contexte_' . $noLigne];
	else : $contexte_check = null;
	endif;


	if (array_key_exists('accessoire_vestimentaire_et_parure_' . $noLigne, $_GET)) : $accessoire_vestimentaire_parure = 'checked';
	else : $accessoire_vestimentaire_parure = null;
	endif;
	if (array_key_exists('armement_objet_de_prestige_' . $noLigne, $_GET)) : $armement_objet_prestige = 'checked';
	else : $armement_objet_prestige = null;
	endif;
	if (array_key_exists('depot_de_recipient_' . $noLigne, $_GET)) : $depot_de_recipient = 'checked';
	else : $depot_de_recipient = null;
	endif;
	if (array_key_exists('autre_mobilier_' . $noLigne, $_GET)) : $autre_mobilier = 'checked';
		$d_none_descp_autre_mobilier = "";
	else : $autre_mobilier = null;
	endif;
	if (array_key_exists('description_autre_mobilier_' . $noLigne, $_GET)) : $description_autre_mobilier = $_GET['description_autre_mobilier_' . $noLigne];
	else : $description_autre_mobilier = null;
	endif;

	if (array_key_exists('trepanation_' . $noLigne, $_GET)) : $trepanation = 'checked';
	else : $trepanation = null;
	endif;
	if (array_key_exists('edentement_complet_' . $noLigne, $_GET)) : $edentement_complet = 'checked';
	else : $edentement_complet = null;
	endif;
	if (array_key_exists('atteinte_neurale_' . $noLigne, $_GET)) : $atteinte_neurale = 'checked';
	else : $atteinte_neurale = null;
	endif;
	if (array_key_exists('scoliose_severe_' . $noLigne, $_GET)) : $scoliose_severe = 'checked';
	else : $scoliose_severe = null;
	endif;
	if (array_key_exists('paget_' . $noLigne, $_GET)) : $paget = 'checked';
	else : $paget = null;
	endif;
	if (array_key_exists('dish_' . $noLigne, $_GET)) : $dish = 'checked';
	else : $dish = null;
	endif;
	if (array_key_exists('rachitisme_' . $noLigne, $_GET)) : $rachitisme = 'checked';
	else : $rachitisme = null;
	endif;
	if (array_key_exists('nanisme_' . $noLigne, $_GET)) : $nanisme = 'checked';
	else : $nanisme = null;
	endif;
	if (array_key_exists('pathologie_infectieuse_' . $noLigne, $_GET)) : $pathologie_infectieuse = 'checked';
		$d_none_pathologie = "";
	else : $pathologie_infectieuse = null;
	endif;
	if (array_key_exists('PI_lepre_' . $noLigne, $_GET)) : $PI_lepre = 'checked';
	else : $PI_lepre = null;
	endif;
	if (array_key_exists('PI_syphilis_' . $noLigne, $_GET)) : $PI_syphilis = 'checked';
	else : $PI_syphilis = null;
	endif;
	if (array_key_exists('PI_variole_' . $noLigne, $_GET)) : $PI_variole = 'checked';
	else : $PI_variole = null;
	endif;
	if (array_key_exists('PI_tuberculose_' . $noLigne, $_GET)) : $PI_tuberculose = 'checked';
	else : $PI_tuberculose = null;
	endif;
	if (array_key_exists('PI_autre_pathologie_infectieuse_' . $noLigne, $_GET)) : $PI_autre_pathologie_infectieuse = 'checked';
	else : $PI_autre_pathologie_infectieuse = null;
	endif;
	if (array_key_exists('fracture_non_reduite_' . $noLigne, $_GET)) : $fracture_non_reduite = 'checked';
	else : $fracture_non_reduite = null;
	endif;
	if (array_key_exists('amputation_' . $noLigne, $_GET)) : $amputation = 'checked';
	else : $amputation = null;
	endif;
	if (array_key_exists('pathologie_severe_' . $noLigne, $_GET)) : $pathologie_severe = 'checked';
	else : $pathologie_severe = null;
	endif;
	if (array_key_exists('autre_atteinte_' . $noLigne, $_GET)) : $autre_atteinte = 'checked';
		$d_none_autre_atteinte = "";
	else : $autre_atteinte = null;
	endif;


	if (array_key_exists('crane_' . $noLigne, $_GET)) : $crane = 'checked';
	else : $crane = null;
	endif;
	if (array_key_exists('msd_' . $noLigne, $_GET)) : $msd = 'checked';
	else : $msd = null;
	endif;
	if (array_key_exists('msg_' . $noLigne, $_GET)) : $msg = 'checked';
	else : $msg = null;
	endif;
	if (array_key_exists('tronc_bassin_' . $noLigne, $_GET)) : $tronc_bassin = 'checked';
	else : $tronc_bassin = null;
	endif;
	if (array_key_exists('mid_' . $noLigne, $_GET)) : $mid = 'checked';
	else : $mid = null;
	endif;
	if (array_key_exists('mig_' . $noLigne, $_GET)) : $mig = 'checked';
	else : $mig = null;
	endif;

	if (array_key_exists('attele_' . $noLigne, $_GET)) : $attele = 'checked';
	else : $attele = null;
	endif;
	if (array_key_exists('prothese_' . $noLigne, $_GET)) : $prothese = 'checked';
	else : $prothese = null;
	endif;
	if (array_key_exists('orthese_' . $noLigne, $_GET)) : $orthese = 'checked';
	else : $orthese = null;
	endif;
	if (array_key_exists('bequillage_' . $noLigne, $_GET)) : $bequillage = 'checked';
	else : $bequillage = null;
	endif;

	//Tout les nom des inputs sont suivis par des [] qui permet de retourner les informations rentrées sous forme de tableau qui permet de récupérer chaque champ de tout les différents sujets. La variable $noLigne correspond à l'ID du sujet qui va permettre de savoir quelle information va à quelle sujet.

	//Pour chaque input, l'option value vérifie dans un premier si il existe dans l'url son champs qui lui est propre et affiche sa valeur en cas d'erreur et sinon elle affiche la valeur du sujet pour ce champs là.

	$ligne = '<div class="col-auto">
	<h2 class="text-center">Groupe de sujets</h2>
	<div class="row g-2">
	<div class="col-md-6">
	<div class="form-floating">
	<input type="number" class="form-control" name="NMI[' . $noLigne . ']" placeholder="183" value="';
	if (array_key_exists('NMI_' . $noLigne, $_GET)) : $ligne .= $_GET['NMI_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="NMI">NMI</label>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-floating">
	<select class="form-select" name="nom_chronologie[' . $noLigne . ']" aria-label="select_nom_chronologie">
	<option value="">Sélectionner</option>';
	foreach ($chronologie as $key) :
		$chrono_ligne .= '<option value="' . $key['id_chronologie'] . '"';
		if ($chronologie_check == $key['id_chronologie']) {
			$chrono_ligne .= 'selected';
		}
		$chrono_ligne .= '>' . $key['nom_chronologie'] . '</option>';
	endforeach;
	$ligne .= $chrono_ligne;
	$ligne .= '</select>
	<label for="nom_chronologie">Période chronologique</label>
	</div>
	</div>
	</div>

	<h3 class="text-center my-2">Sujet handicapé  #' . $noLigne . '</h3>
	<div class="row g-2">
	<div class="row g-2">
	<div class="col-md-6">
	<div class="form-floating">
	<input type="text" class="form-control" name="id_sujet[' . $noLigne . ']" placeholder="183" value="';
	if (array_key_exists('id_sujet_' . $noLigne, $_GET)) : $ligne .= $_GET['id_sujet_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="id_sujet">Identifiant du sujet</label>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-floating">
	<select class="form-select" name="sexe[' . $noLigne . ']" aria-label="select_sexe">
	<option value="" selected>Sélectionner</option>
	<option value="Femme"';
	if ($sexe_check == "Femme") : $ligne .= ' selected';
	endif;
	$ligne .= '>Femme</option>
	<option value="Homme"';
	if ($sexe_check == "Homme") : $ligne .= ' selected';
	endif;
	$ligne .= '>Homme</option>
	<option value="Indéterminé"';
	if ($sexe_check == "Indéterminé") : $ligne .= ' selected';
	endif;
	$ligne .= '>Indéterminé</option>
	</select>
	<label for="sexe">Sexe</label>
	</div>
	</div>
	</div>

	<div class="row g-2">
	<div class="col-md-6">
	<div class="form-floating">
	<input type="number" class="form-control" min="0" max="130" name="age_min[' . $noLigne . ']" placeholder="183" value="';
	if (array_key_exists('age_min_' . $noLigne, $_GET)) : $ligne .= $_GET['age_min_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="age_min">Age minimum au décès</label>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-floating">
	<input type="number" class="form-control" min="0" max="130" name="age_max[' . $noLigne . ']" placeholder="183" value="';
	if (array_key_exists('age_max_' . $noLigne, $_GET)) : $ligne .= $_GET['age_max_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="age_max">Age maximum au décès</label>
	</div>
	</div>
	</div>

	<div class="row g-2">
	<div class="col-md-6">
	<div class="form-floating">
	<input type="number" class="form-control" name="datation_debut[' . $noLigne . ']" placeholder="183" value="';
	if (array_key_exists('datation_debut_' . $noLigne, $_GET)) : $ligne .= $_GET['datation_debut_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="datation_debut">Datation début</label>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-floating">
	<input type="number" class="form-control" name="datation_fin[' . $noLigne . ']" placeholder="183" value="';
	if (array_key_exists('datation_fin_' . $noLigne, $_GET)) : $ligne .= $_GET['datation_fin_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="datation_fin">Datation fin</label>
	</div>
	</div>
	</div>
	</div>

	<div class="row g-3">
	<div class="col-md-4">
	<div class="form-floating">
	<select class="form-select" name="type_depot[' . $noLigne . ']" aria-label="select_depot" style="margin-top: 2.5%;margin-bottom: 2.5%;">
	<option value="4">Sélectionner</option>';
	foreach ($type_depot as $key) :
		$depot_ligne .= '<option value="' . $key['id'] . '"';
		if ($type_depot_check == $key['id']) {
			$depot_ligne .= 'selected';
		}
		$depot_ligne .= '>' . $key['nom'] . '</option>';
	endforeach;
	$ligne .= $depot_ligne;
	$ligne .= '</select>
	<label for="type_depot">Type de dépôt</label>
	</div>
	</div>
	<div class="col-md-4">
	<div class="form-floating">
	<select class="form-select" name="type_sepulture[' . $noLigne . ']" aria-label="select_sep" style="margin-top: 2.5%;margin-bottom: 2.5%;">
	<option value="4">Sélectionner</option>';
	foreach ($type_sepulture as $key) :
		$sepulture_ligne .= '<option value="' . $key['id'] . '"';
		if ($type_sepulture_check == $key['id']) {
			$sepulture_ligne .= 'selected';
		}
		$sepulture_ligne .= '>' . $key['nom'] . '</option>';
	endforeach;

	$ligne .= $sepulture_ligne;
	$ligne .= '</select>
	<label for="type_sepulture">Type de sépulture</label>
	</div>
	</div>
	<div class="col-md-4">
	<div class="form-floating">
	<select class="form-select" name="contexte_normatif[' . $noLigne . ']" aria-label="select_contexte_normatif" style="margin-top: 2.5%;margin-bottom: 2.5%;">
	<option value="" selected>Sélectionner</option>
	<option value="Standard"';
	if ($contexte_normatif_check == "Standard") : $ligne .= ' selected';
	endif;
	$ligne .= '>Standard</option>
	<option value="Atypique"';
	if ($contexte_normatif_check == "Atypique") : $ligne .= ' selected';
	endif;
	$ligne .= '>Atypique</option>
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
	<option value="Rural"';
	if ($milieu_vie_check == "Rural") : $ligne .= ' selected';
	endif;
	$ligne .= '>Rural</option>
	<option value="Urbain"';
	if ($milieu_vie_check == "Urbain") : $ligne .= ' selected';
	endif;
	$ligne .= '>Urbain</option>
	</select>
	<label for="milieu_vie">Milieu de vie</label>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-floating">
	<select class="form-select" name="contexte[' . $noLigne . ']" aria-label="select_contexte">
	<option value="">Sélectionner</option>
	<option value="Funeraire"';
	if ($contexte_check == "Funeraire") : $ligne .= ' selected';
	endif;
	$ligne .= '>Funéraire</option>
	<option value="Domestique"';
	if ($contexte_check == "Domestique") : $ligne .= ' selected';
	endif;
	$ligne .= '>Domestique</option>
	<option value="Autre"';
	if ($contexte_check == "Autre") : $ligne .= ' selected';
	endif;
	$ligne .= '>Autre</option>
	</select>
	<label for="contexte">Contexte de la tombe</label>
	</div>
	</div>
	</div>

	<div class="col-md-12">
	<label for="commentaire_contexte">Commentaire</label>
	<div class="input-group">
	<textarea class="form-control" name="commentaire_contexte[' . $noLigne . ']" rows="2">';
	if (array_key_exists('commentaire_contexte_' . $noLigne, $_GET)) : $ligne .= $_GET['commentaire_contexte_' . $noLigne];
	else : null;
	endif;
	$ligne .= '</textarea>
	</div>
	</div>
	<br/>


	<div class="container">
	<div class="row">
	<div class="col-md-4">
	<h3>Accessoire</h3>';
	foreach ($accessoire as $key) {
		$accessoire_ligne .= ' <div class="form-check form-switch">
		<label class="form-check-label" for="' . $key['name'] . '[' . $noLigne . ']">' . $key['nom'] . '</label>
		<input class="form-check-input" type="checkbox" name="' . $key['name'] . '[' . $noLigne . ']" value="' . $key['id'] . '"';
		if ($key['name'] == "accessoire_vestimentaire_et_parure") : $accessoire_ligne .= $accessoire_vestimentaire_parure;
		endif;
		if ($key['name'] == "armement_objet_de_prestige") : $accessoire_ligne .= $armement_objet_prestige;
		endif;
		if ($key['name'] == "depot_de_recipient") : $accessoire_ligne .= $depot_de_recipient;
		endif;
		if ($key['name'] == "autre_mobilier") : $accessoire_ligne .= 'id="autre_mobilier_' . $noLigne . '" ' . $autre_mobilier . ' onchange="function_toggle(' . $noLigne . ');"';
		endif;
		$accessoire_ligne .= '>
		</div>';
	}
	$ligne .= $accessoire_ligne;
	$ligne .= '<div id="block_description_autre_mobilier_' . $noLigne . '" class="' . $d_none_descp_autre_mobilier . '">
	<label class="form-check-label" for="description_autre_mobilier[' . $noLigne . ']">Description du autre</label>
	<textarea class="form-control" name="description_autre_mobilier[' . $noLigne . ']" rows="2">' . $description_autre_mobilier . '</textarea>
	</div>
	</div>
	
	<div class="col-md-8">
	<h3>Dépot</h3>
	<div class="row row-cols-2">
	<div class="col">
	<div class="form-floating">
	<input type="text" class="form-control" name="num_inventaire[' . $noLigne . ']" placeholder="Numéro" value="';
	if (array_key_exists('num_inventaire_' . $noLigne, $_GET)) : $ligne .= $_GET['num_inventaire_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="num_inventaire[' . $noLigne . ']">Numéro du dépôt</label> 
	</div>
	</div>
	<div class="col">
	<div class="form-floating">
	<input type="text" name="commune_depot[' . $noLigne . ']" id="commune_depot_' . $noLigne . '" class="form-control" placeholder="Rechercher une commune ..." autocomplete="off" onclick="recherche_commune_depot(' . $noLigne . ');" value="';
	if (array_key_exists('commune_depot_' . $noLigne, $_GET)) : $ligne .= $_GET['commune_depot_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="commune_depot[' . $noLigne . ']">Rechercher une commune</label> 
	</div>
	<div class="col-md-auto">
	<div class="list-group" id="show-list-depot_' . $noLigne . '"></div>
	</div>
	</div>
	<div class="col my-2">
	<div class="form-floating">
	<input type="text" class="form-control" name="adresse_depot[' . $noLigne . ']" placeholder="Adresse" value="';
	if (array_key_exists('adresse_depot_' . $noLigne, $_GET)) : $ligne .= $_GET['adresse_depot_' . $noLigne];
	else : null;
	endif;
	$ligne .= '">
	<label for="adresse_depot[' . $noLigne . ']">Adresse du dépôt</label> 
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	<br/>


	<div class="container">
	<h3>Atteinte invalidante</h3>
	<p class="text-muted">Vous pouvez activer ou désactiver les différents boutons avec la barre espace.</p>
	<div class="row">';
	foreach ($diagnostic as $key) {
		$localisation_atteinte_ligne = $appreil_comp_ligne = "";
		$diagnostic_ligne .= '<div class="col-md-6">
		<div class="form-check form-switch">
		<label class="form-check-label" for="' . $key['name'] . '[' . $noLigne . ']">' . $key['nom'] . '</label>
		<input class="form-check-input" type="checkbox"';
		if ($key['nom'] == "Pathologie infectieuse") : $diagnostic_ligne .= ' id="pathologies_infectieuses_' . $noLigne . '"';
		elseif ($key['nom'] == "Autre") : $diagnostic_ligne .= '  id="autre_atteinte_' . $noLigne . '"';
		endif;
		$diagnostic_ligne .= 'name="' . $key['name'] . '[' . $noLigne . ']" value="' . $key['id'] . '"';
		if ($key['nom'] == "Pathologie infectieuse") : $diagnostic_ligne .= ' onchange="function_toggle(' . $noLigne . ');"';
		endif;
		if ($key['nom'] == "Autre") : $diagnostic_ligne .= ' onchange="function_toggle(' . $noLigne . ');"';
		endif;
		if ($key['name'] == "trepanation") : $diagnostic_ligne .= $trepanation;
		endif;
		if ($key['name'] == "edentement_complet") : $diagnostic_ligne .= $edentement_complet;
		endif;
		if ($key['name'] == "atteinte_neurale") : $diagnostic_ligne .= $atteinte_neurale;
		endif;
		if ($key['name'] == "scoliose_severe") : $diagnostic_ligne .= $scoliose_severe;
		endif;
		if ($key['name'] == "maladie_de_paget_ou_osteite_deformante") : $diagnostic_ligne .= $paget;
		endif;
		if ($key['name'] == "dish") : $diagnostic_ligne .= $dish;
		endif;
		if ($key['name'] == "rachitisme") : $diagnostic_ligne .= $rachitisme;
		endif;
		if ($key['name'] == "nanisme") : $diagnostic_ligne .= $nanisme;
		endif;
		if ($key['name'] == "pathologie_infectieuse") : $diagnostic_ligne .= $pathologie_infectieuse;
		endif;
		if ($key['name'] == "fracture_non_reduite") : $diagnostic_ligne .= $fracture_non_reduite;
		endif;
		if ($key['name'] == "amputation") : $diagnostic_ligne .= $amputation;
		endif;
		if ($key['name'] == "pathologie_degenerative_severe") : $diagnostic_ligne .= $pathologie_severe;
		endif;
		if ($key['name'] == "autre_atteinte") : $diagnostic_ligne .= $autre_atteinte;
		endif;
		$diagnostic_ligne .= '> </div>';
		if ($key['nom'] == "Pathologie infectieuse") :
			$diagnostic_ligne .= '<div class="' . $d_none_pathologie . '" id="block_pathologies_infectieuses_' . $noLigne . '" style="width: 50%; background-color: white;">';
			foreach ($pathologie as $key2) {
				$pathologie_ligne .= '<div class="form-check form-switch" style="padding-left: 75px;">
					<label class="form-check-label" for="PI_' . $key2['name'] . '[' . $noLigne . ']">' . $key2['type_pathologie'] . '</label>
					<input class="form-check-input" type="checkbox" name="PI_' . $key2['name'] . '[' . $noLigne . ']" value="' . $key2['id_pathologie'] . '"';
				if ('PI_' . $key2['name'] . '' == "PI_lepre") : $pathologie_ligne .= $PI_lepre;
				endif;
				if ('PI_' . $key2['name'] . '' == "PI_syphilis") : $pathologie_ligne .= $PI_syphilis;
				endif;
				if ('PI_' . $key2['name'] . '' == "PI_variole") : $pathologie_ligne .= $PI_variole;
				endif;
				if ('PI_' . $key2['name'] . '' == "PI_tuberculose") : $pathologie_ligne .= $PI_tuberculose;
				endif;
				if ('PI_' . $key2['name'] . '' == "PI_autre_pathologie_infectieuse") : $pathologie_ligne .= $PI_autre_pathologie_infectieuse;
				endif;
				$pathologie_ligne .= '>
					</div>';
			}
			$diagnostic_ligne .= $pathologie_ligne;
			$diagnostic_ligne .= '</div>';
		elseif ($key['nom'] == "Autre") :
			$diagnostic_ligne .= '<div id="block_description_autre_atteinte_' . $noLigne . '" class="' . $d_none_autre_atteinte . '">
				<label for="description_autre_atteinte[' . $noLigne . ']">Description du autre</label>
				<textarea class="form-control" name="description_autre_atteinte[' . $noLigne . ']" rows="2">';
			if (array_key_exists('description_autre_atteinte_' . $noLigne, $_GET)) : $diagnostic_ligne .= $_GET['description_autre_atteinte_' . $noLigne];
			else : $diagnostic_ligne .= "";
			endif;
			$diagnostic_ligne .= '</textarea>
				</div>';
		endif;
		$diagnostic_ligne .= '</div>
			<div class="col-md-6">';
		if ($key['name'] == "trepanation") :
			$diagnostic_ligne .= '<div class="row"><div class="col-md-6">
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
			$diagnostic_ligne .= '
				<div class="row">
				<div class="col-md-6">';
			foreach ($localisation_atteinte as $key2) {
				$localisation_atteinte_ligne .= '<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" name="' . $key2['name'] . '[' . $noLigne . '][' . $key['id'] . ']" value="' . $key2['id'] . '"';
				if ($key2['name'] == "crane") : if (array_key_exists('crane_' . $noLigne . '_' . $key['id'], $_GET)) : $localisation_atteinte_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "membre_superieur_droit") : if (array_key_exists('msd_' . $noLigne . '_' . $key['id'], $_GET)) : $localisation_atteinte_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "membre_superieur_gauche") : if (array_key_exists('msg_' . $noLigne . '_' . $key['id'], $_GET)) : $localisation_atteinte_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "tronc_bassin") : if (array_key_exists('tronc_bassin_' . $noLigne . '_' . $key['id'], $_GET)) : $localisation_atteinte_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "membre_inferieur_droit") : if (array_key_exists('mid_' . $noLigne . '_' . $key['id'], $_GET)) : $localisation_atteinte_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "membre_inferieur_gauche") : if (array_key_exists('mig_' . $noLigne . '_' . $key['id'], $_GET)) : $localisation_atteinte_ligne .= ' checked';
					endif;
				endif;
				$localisation_atteinte_ligne .= '>
					</div>';
			}
			$diagnostic_ligne .= $localisation_atteinte_ligne;
			$diagnostic_ligne .= '</div>
				<div class="col-md-6">';
			foreach ($appareil_compensatoire as $key2) {
				$appreil_comp_ligne .= '<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" name="' . $key2['name'] . '[' . $noLigne . '][' . $key['id'] . ']" value="' . $key2['id_appareil_compensatoire'] . '"';
				if ($key2['name'] == "attele") : if (array_key_exists('attele_' . $noLigne . '_' . $key['id'], $_GET)) : $appreil_comp_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "prothese") : if (array_key_exists('prothese_' . $noLigne . '_' . $key['id'], $_GET)) : $appreil_comp_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "orthese") : if (array_key_exists('orthese_' . $noLigne . '_' . $key['id'], $_GET)) : $appreil_comp_ligne .= ' checked';
					endif;
				endif;
				if ($key2['name'] == "bequillage") : if (array_key_exists('bequillage_' . $noLigne . '_' . $key['id'], $_GET)) : $appreil_comp_ligne .= ' checked';
					endif;
				endif;
				$appreil_comp_ligne .= '> 
					
					</div>';
			}
			$diagnostic_ligne .= $appreil_comp_ligne;
			$diagnostic_ligne .= '</div>
				</div>';
		endif;
		$diagnostic_ligne .= '</div>';
	}
	$diagnostic_ligne .= '<label for="commentaire_appareil[' . $noLigne . ']">Commentaire sur l\'appareil de compensation</label>
			<textarea class="form-control" name="commentaire_appareil[' . $noLigne . ']" rows="2">';
	if (array_key_exists('commentaire_appareil_' . $noLigne, $_GET)) : $diagnostic_ligne .= $_GET['commentaire_appareil_' . $noLigne];
	else : $diagnostic_ligne .= "";
	endif;
	$diagnostic_ligne .= '</textarea>';
	$diagnostic_ligne .= '<label for="commentaire_diagnostic[' . $noLigne . ']">Commentaire du diagnostic</label>
			<textarea class="form-control" name="commentaire_diagnostic[' . $noLigne . ']" rows="2" placeholder="** INFO ** Les différentes atteintes cochées seront ajoutées à la description">';
	if (array_key_exists('commentaire_diagnostic_' . $noLigne, $_GET)) : $diagnostic_ligne .= $_GET['commentaire_diagnostic_' . $noLigne];
	else : $diagnostic_ligne .= "";
	endif;
	$diagnostic_ligne .= '</textarea>';
	$ligne .= $diagnostic_ligne;
	$ligne .= '</div>
			</div>
			<br/>
			<center>';
	if ($para == 0) :
		$ligne .= '<button type="button" class="btn btn-primary" id="btnAjouter' . $noLigne . '" onclick="ajouterLigne(' . ($noLigne + 1) . ');"><i class="bi bi-plus"></i></button>';
	elseif ($para == 1) : $ligne .= '<button type="button" class="btn btn-primary" id="btnAjouter' . $noLigne . '" onclick="ajouterLigne(' . ($noLigne + 1) . ');"><i class="bi bi-plus"></i></button>
				<button type="button" class="btn btn-danger" id="btnSupp' . $noLigne . '" onclick="supprimerLigne(' . $noLigne . ');"><i class="bi bi-x"></i></button>';
	endif;
	$ligne .= '</center></div>';

	return $ligne;
} ?>

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