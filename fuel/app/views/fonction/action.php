<?php //Permet de faire la recherche du nom de la commune
if (isset($_POST['query'])) {
  $inpText =$_POST['query'];

  $query = DB::query('SELECT nom, departement FROM commune WHERE nom LIKE "'.$inpText .'%"  ');
  $recherche = $query->execute();
  $recherche= $recherche->_results;

  //Permet d'afficher les différents noms trouvés ou d'indiquer que la recherche à rien trouvé
  if ($recherche) {
    foreach ($recherche as $key) {
      echo '<a class="list-group-item list-group-item-action border-1">' . $key['nom'] . ' ('.$key['departement'].')</a>';
    }
  } else {
    echo '<p class="list-group-item border-1">Commune non trouvée</p>';
  }
}

//Permet de récupérer les différentes données pour rafficher un sujet quand le bouton + est appuyé
if(isset($_GET['action'])){

  $chronologie = DB::query('SELECT id_chronologie,nom_chronologie FROM chronologie_site ORDER BY nom_chronologie ASC')->execute();
  $chronologie = $chronologie->_results;

  $type_depot = DB::query('SELECT * FROM type_depot')->execute();
  $type_depot = $type_depot->_results;

  $type_sepulture = DB::query('SELECT * FROM type_sepulture')->execute();
  $type_sepulture = $type_sepulture->_results;

  $diagnostic = DB::query('SELECT * FROM diagnostic')->execute();
  $diagnostic = $diagnostic->_results;

  $accessoire = DB::query('SELECT * FROM mobilier_archeologique')->execute();
  $accessoire = $accessoire->_results;

  $localisation_atteinte = DB::query('SELECT * FROM localisation_atteinte')->execute();
  $localisation_atteinte = $localisation_atteinte->_results;

  $appareil_compensatoire = DB::query('SELECT * FROM appareil_compensatoire')->execute();
  $appareil_compensatoire = $appareil_compensatoire->_results;

  $pathologie = DB::query('SELECT * FROM pathologie')->execute();
  $pathologie = $pathologie->_results;

  $ligne = afficherLigne($_GET['action'],$chronologie,$type_depot,$type_sepulture,$diagnostic,$accessoire,$localisation_atteinte,$appareil_compensatoire,$pathologie);
  die($ligne);
}

//Fonction qui permet de retourner le code de l'affichage de tout les champs pour un sujet
function afficherLigne($noLigne,$chronologie,$type_depot,$type_sepulture,$diagnostic,$accessoire,$localisation_atteinte,$appareil_compensatoire,$pathologie){

  //Initialisation des variables permettant de garder les résultats des différents foreach
  $chrono_ligne=$depot_ligne=$sepulture_ligne=$diagnostic_ligne=$pathologie_ligne=$accessoire_ligne=$localisation_atteinte_ligne=$appreil_comp_ligne="";


  //Tout les nom des inputs sont suivis par des [] qui permet de retourner les informations rentrées sous forme de tableau qui permet de récupérer chaque champ de tout les différents sujets. La variable $noLigne correspond à l'ID du sujet qui va permettre de savoir quelle information va à quelle sujet.


  $ligne ='<div class="col-auto">
  <h2 class="text-center">Groupe de sujets</h2>
  <div class="row g-2">
  <div class="col-md-6">
  <div class="form-floating">
  <input type="number" class="form-control" name="NMI['.$noLigne.']" placeholder="183">
  <label for="NMI">NMI</label>
  </div>
  </div>
  <div class="col-md-6">
  <div class="form-floating">
  <select class="form-select" name="nom_chronologie['.$noLigne.']" aria-label="select_nom_chronologie">
  <option value="">Sélectionner</option>';
  foreach($chronologie as $key):
    $chrono_ligne .='<option value="'.$key['id_chronologie'].'">'.$key['nom_chronologie'].'</option>';
  endforeach;
  $ligne.=$chrono_ligne;
  $ligne.='</select>
  <label for="nom_chronologie">Période chronologique</label>
  </div>
  </div>
  </div>

  <h3 class="text-center my-2">Sujet handicapé  #'.$noLigne.'</h3>
  <div class="row g-2">
  <div class="row g-2">
  <div class="col-md-6">
  <div class="form-floating">
  <input type="text" class="form-control" name="id_sujet['.$noLigne.']" placeholder="183">
  <label for="id_sujet">Identifiant du sujet</label>
  </div>
  </div>
  <div class="col-md-6">
  <div class="form-floating">
  <select class="form-select" name="sexe['.$noLigne.']" aria-label="select_sexe">
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
  <input type="number" class="form-control" min="0" max="130" name="age_min['.$noLigne.']" placeholder="183">
  <label for="age_min">Age minimum au décès</label>
  </div>
  </div>
  <div class="col-md-6">
  <div class="form-floating">
  <input type="number" class="form-control" min="0" max="130" name="age_max['.$noLigne.']" placeholder="183">
  <label for="age_max">Age maximum au décès</label>
  </div>
  </div>
  </div>

  <div class="row g-2">
  <div class="col-md-6">
  <div class="form-floating">
  <input type="number" class="form-control" name="datation_debut['.$noLigne.']" placeholder="183">
  <label for="datation_debut">Datation début</label>
  </div>
  </div>
  <div class="col-md-6">
  <div class="form-floating">
  <input type="number" class="form-control" name="datation_fin['.$noLigne.']" placeholder="183">
  <label for="datation_fin">Datation fin</label>
  </div>
  </div>
  </div>
  </div>

  <div class="row g-3">
  <div class="col-md-4">
  <div class="form-floating">
  <select class="form-select" name="type_depot['.$noLigne.']" aria-label="select_depot" style="margin-top: 2.5%;margin-bottom: 2.5%;">
  <option value="4">Sélectionner</option>';
  foreach($type_depot as $key):
    $depot_ligne.='<option value="'.$key['id'].'">'.$key['nom'].'</option>';
  endforeach;
  $ligne.=$depot_ligne;
  $ligne.='</select>
  <label for="type_depot">Type de dépôt</label>
  </div>
  </div>
  <div class="col-md-4">
  <div class="form-floating">
  <select class="form-select" name="type_sepulture['.$noLigne.']" aria-label="select_sep" style="margin-top: 2.5%;margin-bottom: 2.5%;">
  <option value="4">Sélectionner</option>';
  foreach($type_sepulture as $key):
    $sepulture_ligne.='<option value="'.$key['id'].'">'.$key['nom'].'</option>';
  endforeach;

  $ligne.=$sepulture_ligne;
  $ligne.='</select>
  <label for="type_sepulture">Type de sépulture</label>
  </div>
  </div>
  <div class="col-md-4">
  <div class="form-floating">
  <select class="form-select" name="contexte_normatif['.$noLigne.']" aria-label="select_contexte_normatif" style="margin-top: 2.5%;margin-bottom: 2.5%;">
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
  <select class="form-select" name="milieu_vie['.$noLigne.']" aria-label="select_milieu_vie">
  <option value="">Sélectionner</option>
  <option value="Rural">Rural</option>
  <option value="Urbain">Urbain</option>
  </select>
  <label for="milieu_vie">Milieu de vie</label>
  </div>
  </div>
  <div class="col-md-6">
  <div class="form-floating">
  <select class="form-select" name="contexte['.$noLigne.']" aria-label="select_contexte">
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
  <textarea class="form-control" name="commentaire_contexte['.$noLigne.']" rows="2"></textarea>
  </div>
  </div>
  <br/>


  <div class="container">
  <div class="row">
  <div class="col-md-4">
  <h3>Accessoire</h3>';
  foreach ($accessoire as $key) {
    $accessoire_ligne.=' <div class="form-check form-switch">
    <label class="form-check-label" for="'.$key['name'].'['.$noLigne.']">'.$key['nom'].'</label>
    <input class="form-check-input" type="checkbox" name="'.$key['name'].'['.$noLigne.']" value="'.$key['id'].'"';
    if($key['name'] == "autre_mobilier"): $accessoire_ligne.='id="autre_mobilier_'.$noLigne.'" onchange="function_toggle('.$noLigne.');"'; endif;
    $accessoire_ligne.='>
    </div>';
  }
  $ligne.=$accessoire_ligne;
  $ligne.='<div id="block_description_autre_mobilier_'.$noLigne.'" class="d-none">
  <label class="form-check-label" for="description_autre_mobilier['.$noLigne.']">Description du autre</label>
  <textarea class="form-control" name="description_autre_mobilier['.$noLigne.']" rows="2"></textarea>
  </div>
  </div>

  <div class="col-md-8">
  <h3>Dépot</h3>
  <div class="row row-cols-2">
  <div class="col">
  <div class="form-floating">
  <input type="text" class="form-control" name="num_inventaire['.$noLigne.']" placeholder="Numéro" value="">
  <label for="num_inventaire['.$noLigne.']">Numéro du dépôt</label> 
  </div>
  </div>
  <div class="col">
  <div class="form-floating">
  <input type="text" name="commune_depot['.$noLigne.']" id="commune_depot_'.$noLigne.'" class="form-control" placeholder="Rechercher une commune ..." autocomplete="off" onclick="recherche_commune_depot('.$noLigne.');" value="">
  <label for="commune_depot['.$noLigne.']">Rechercher une commune</label> 
  </div>
  <div class="col-md-auto">
  <div class="list-group" id="show-list-depot_'.$noLigne.'"></div>
  </div>
  </div>
  <div class="col my-2">
  <div class="form-floating">
  <input type="text" class="form-control" name="adresse_depot['.$noLigne.']" placeholder="Adresse" value="">
  <label for="adresse_depot['.$noLigne.']">Adresse du dépôt</label> 
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
    $localisation_atteinte_ligne=$appreil_comp_ligne="";
    $diagnostic_ligne.='<div class="col-md-6">
    <div class="form-check form-switch">
    <label class="form-check-label" for="'.$key['name'].'['.$noLigne.']">'.$key['nom'].'</label>
    <input class="form-check-input" type="checkbox"';
    if($key['nom'] == "Pathologie infectieuse"): $diagnostic_ligne.=' id="pathologies_infectieuses_'.$noLigne.'"';
      elseif($key['nom'] == "Autre"): $diagnostic_ligne.='  id="autre_atteinte_'.$noLigne.'"'; endif;
      $diagnostic_ligne.='name="'.$key['name'].'['.$noLigne.']" value="'.$key['id'].'"';
      if($key['nom'] == "Pathologie infectieuse"): $diagnostic_ligne.=' onchange="function_toggle('.$noLigne.');"'; endif;
      if($key['nom'] == "Autre"): $diagnostic_ligne.=' onchange="function_toggle('.$noLigne.');"'; endif;
      $diagnostic_ligne.='> </div>';
      if($key['nom'] == "Pathologie infectieuse"): 
        $diagnostic_ligne.='<div class="d-none" id="block_pathologies_infectieuses_'.$noLigne.'" style="width: 30%; background-color: white;">';
        foreach ($pathologie as $key2) {
          $pathologie_ligne.='<div class="form-check form-switch" style="padding-left: 75px;">
          <label class="form-check-label" for="PI_'.$key2['name'].'['.$noLigne.']">'.$key2['type_pathologie'].'</label>
          <input class="form-check-input" type="checkbox" name="PI_'.$key2['name'].'['.$noLigne.']" value="'.$key2['id_pathologie'].'">
          </div>';
        }
        $diagnostic_ligne.=$pathologie_ligne;
        $diagnostic_ligne.='</div>';
      elseif ($key['nom'] == "Autre"):
        $diagnostic_ligne.='<div id="block_description_autre_atteinte_'.$noLigne.'" class="d-none">
        <label for="description_autre_atteinte['.$noLigne.']">Description du autre</label>
        <textarea class="form-control" name="description_autre_atteinte['.$noLigne.']" rows="2"></textarea>
        </div>';
      endif;
      $diagnostic_ligne.='</div>
      <div class="col-md-6">';
      if($key['name'] == "trepanation"):
       $diagnostic_ligne.='<div class="row"><div class="col-md-6">
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
       </div>'; endif;
       if($key['name'] != "trepanation" && $key['name'] != "edentement_complet"): 
        $diagnostic_ligne.='
        <div class="row">
        <div class="col-md-6">';
        foreach ($localisation_atteinte as $key2) {
          $localisation_atteinte_ligne.='<div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="'.$key2['name'].'['.$noLigne.']['.$key['id'].']" value="'.$key2['id'].'">
          </div>';
        }
        $diagnostic_ligne.=$localisation_atteinte_ligne;
        $diagnostic_ligne.='</div>
        <div class="col-md-6">';
        foreach ($appareil_compensatoire as $key2) {
          $appreil_comp_ligne.='<div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="'.$key2['name'].'['.$noLigne.']['.$key['id'].']" value="'.$key2['id_appareil_compensatoire'].'"> 
          </div>';
        }
        $diagnostic_ligne.=$appreil_comp_ligne;
        $diagnostic_ligne.='</div>
        </div>';endif;
        $diagnostic_ligne.='</div>';
      }
      $diagnostic_ligne.='<label for="commentaire_appareil['.$noLigne.']">Commentaire sur l\'appareil de compensation</label>
      <textarea class="form-control" name="commentaire_appareil['.$noLigne.']" rows="2"></textarea>';
      $diagnostic_ligne.='<label for="commentaire_diagnostic['.$noLigne.']">Commentaire du diagnostic</label>
      <textarea class="form-control" name="commentaire_diagnostic['.$noLigne.']" rows="2" placeholder="** INFO ** Les différentes atteintes cochées seront ajoutées à la description"></textarea>';
      $ligne.=$diagnostic_ligne;
      $ligne.='</div>
      </div>
      <br/>
      <center>
      <button type="button" class="btn btn-primary" id="btnAjouter'.$noLigne.'" onclick="ajouterLigne('.($noLigne+1).');"><i class="bi bi-plus"></i></button>
      <button type="button" class="btn btn-danger" id="btnSupp'.$noLigne.'" onclick="supprimerLigne('.$noLigne.');"><i class="bi bi-x"></i></button>
      </center>';

      return $ligne;
    }?>
