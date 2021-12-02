<?php
foreach ($sujet_details as $key):

  //Récupération de tout les informations du sujet pour pouvoir les afficher
  $query = DB::query('SELECT nom FROM type_depot WHERE id='.$key['id_type_depot'].' ');
  $nom_type_depot = $query->execute();
  $nom_type_depot= $nom_type_depot->_results[0]['nom'];

  $query = DB::query('SELECT nom FROM type_sepulture WHERE id='.$key['id_sepulture'].' ');
  $nom_sepulture = $query->execute();
  $nom_sepulture= $nom_sepulture->_results[0]['nom'];


  if($key['id_depot'] != NULL):
    $query = DB::query('SELECT * FROM depot WHERE id="'.$key['id_depot'].'" ');
    $info_depot = $query->execute();
    $info_depot= $info_depot->_results[0];

    $query = DB::query('SELECT nom FROM commune WHERE id='.$info_depot['id_commune'].' ');
    $nom_commune = $query->execute();
    $nom_commune= $nom_commune->_results[0]['nom'];
  else:
    $info_depot['num_inventaire']="";
    $nom_commune="";
    $info_depot['adresse']="";
  endif;

  $query = DB::query('SELECT * FROM groupe_sujets WHERE id_groupe_sujets='.$key['id_groupe_sujets'].' ');
  $info_groupe = $query->execute();
  $info_groupe= $info_groupe->_results[0];

  $query = DB::query('SELECT * FROM chronologie_site WHERE id_chronologie='.$info_groupe['id_chronologie'].' ');
  $info_chrono = $query->execute();
  $info_chrono= $info_chrono->_results[0];

  $query = DB::query('SELECT nom_op FROM operations WHERE id_site='.$info_groupe['id_operation'].' ');
  $nom_op = $query->execute();
  $nom_op= $nom_op->_results[0]['nom_op'];
  ?>
  <!-- Entête de la page -->
<div class="container">
  <h1 class="m-2">Sujet <?= $key['id_sujet_handicape'];?>
  </h1>
  <p class="text-muted">Ici vous retrouvez toutes les informations du sujet <strong><?= $key['id_sujet_handicape'];?></strong> .</p>
  <?php
    //Permet de vérifier si dans l'url il y a les différentes options et si oui, cela appel une fonction qui permet d'afficher un message     
    array_key_exists('erreur_supp_sujet',$_GET) ? alertBootstrap('Le numéro du sujet n\'est pas correcte (nombres autorisés). La suppression ne peut pas s\'effectuer','danger') : null; 
    array_key_exists('erreur_supp_bdd',$_GET) ? alertBootstrap('Le numéro du sujet n\'existe pas. La suppression ne peut pas s\'effectuer','danger') : null; 

    array_key_exists('success_ajout',$_GET) ? alertBootstrap('Ajout effectué','success') : null; 
    array_key_exists('success_modif',$_GET) ? alertBootstrap('Modification effectuée','success') : null; 
    array_key_exists('success_supp_sujet',$_GET) ? alertBootstrap('Suppression effectuée','success') : null; 
    ?>
    <!-- Contenu de la page -->
  <div class="container" style="background-color: #F5F5F5;">
    <h4>Informations générales</h4>
    <div class="row">
      <div class="col">
        <div class="p-2">Âge minimum : <?= $key['age_min'];?></div>
      </div>
      <div class="col">
        <div class="p-2">Âge maximum : <?= $key['age_max'];?></div>
      </div>
      <div class="col">
        <div class="p-2">Sexe : <?= $key['sexe'] ?></div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="p-2">Datation : <?= $key['datation'];?></div>
      </div>
      <div class="col">
        <div class="p-2">Écart type de la datation : <?= $key['datation_ecart_type'];?></div>
      </div>
      <div class="col">
        <div class="p-2">Milieu de vie : <?= $key['milieu_vie'];?></div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="p-2">Contexte normatif : <?= $key['contexte_normatif'];?></div>
      </div>
      <div class="col">
        <div class="p-2">Contexte : <?= $key['contexte'];?></div>
      </div>
      <div class="col">
        <div class="p-2">Commentaire du contexte : <?= $key['commentaire_contexte'];?></div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="p-2">Type de dépôt : <?= $nom_type_depot;?></div>
      </div>
      <div class="col-md-4">
        <div class="p-2">Type de sépulture : <?= $nom_sepulture;?></div>
      </div>
    </div>
  </div>
  <br/>
  <div class="container" style="background-color: #F5F5F5;">
    <h4>Groupe du sujet</h4>
    <div class="row">
      <div class="col-md-2">
        <div class="p-2">NMI : <?= $info_groupe['NMI'];?></div>
      </div>
      <div class="col-md-3">
        <div class="p-2">Opération : <?= $nom_op;?></div>
      </div>
      <div class="col-md-3">
        <div class="p-2">Période : <?= $info_chrono['nom_chronologie'];?></div>
      </div>
      <div class="col-md-2">
        <div class="p-2">Date de début : <?= $info_chrono['date_debut'];?></div>
      </div>
      <div class="col-md-2">
        <div class="p-2">Date de fin : <?= $info_chrono['date_fin'];?></div>
      </div>
    </div>
  </div>
  <br/>
  <div class="container" style="background-color: #F5F5F5;">
    <h4>Dépôt</h4>
    <div class="row">
      <div class="col">
        <div class="p-2">Numéro d'inventaire : <?= $info_depot['num_inventaire'];?>
        </div>
      </div>
      <div class="col">
        <div class="p-2">Commune : <?= $nom_commune;?></div>
      </div>
      <div class="col">
        <div class="p-2">Adresse : <?= $info_depot['adresse'];?></div>
      </div>
    </div>
  </div>
  <br/>
  <?php endforeach; ?>
  <div class="container" style="background-color: #F5F5F5;">
    <h4>Accessoire</h4>
    <div class="row">
      <?php if(!empty($accessoire_sujet)): foreach($accessoire_sujet as $key): 
        $query = DB::query('SELECT nom FROM mobilier_archeologique WHERE id='.$key['id_mobilier'].' ');
        $nom_mobilier = $query->execute();
        $nom_mobilier= $nom_mobilier->_results[0]['nom'];


        if($key['id_mobilier'] == 4): 
          echo '<div class="col">
          <div class="p-2">'; 
          echo $nom_mobilier; 
          echo' : ';
          echo $key['description']; 
          echo'</div>
          </div>';
        else: 
          echo '<div class="col">
          <div class="p-2">'; 
          echo $nom_mobilier; 
          echo'</div>
          </div>'; 
        endif;
      endforeach; 
      else:
        echo'<div class="col">
        <div class="p-2">Aucun accessoire</div>
        </div>';
      endif;?>
    </div>
  </div>
  <br/>
  <div class="container" style="background-color: #F5F5F5;">
    <h4>Atteinte invalidante</h4>
    <?php if(!empty($sujet_atteinte)):?>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">Diagnostic</th>
            <th scope="col" class="text-center">Pathologie</th>
            <th scope="col" class="text-center">Localisation</th>
            <th scope="col" class="text-center">Appareil de compensation</th>
            <th scope="col" class="text-center">Description du Autre</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($sujet_atteinte as $key): 
            $query = DB::query('SELECT nom FROM diagnostic WHERE id='.$key['id_diagnostic'].' ');
            $nom_diagnostic = $query->execute();
            $nom_diagnostic= $nom_diagnostic->_results[0]['nom'];
            $nom_patho=$description_autre="";

            if($key['id_diagnostic'] == 9):
              $query = DB::query('SELECT type_pathologie FROM pathologie WHERE id_pathologie='.$key['id_pathologie'].' ');
              $nom_patho = $query->execute();
              $nom_patho= $nom_patho->_results[0]['type_pathologie']; 
            endif;

            if($key['id_diagnostic'] == 13): $description_autre=$key['description_autre']; endif;

            echo '<tr>';
            echo '<td>'.$nom_diagnostic.'</td>';
            echo '<td class="text-center">'.$nom_patho.'</td>';
            echo '<td class="text-center">';

            $query = DB::query('SELECT id_localisation_atteinte FROM localisation_sujet WHERE id_sujet_handicape='.$key['id_sujet_handicape'].' AND id_diagnostic='.$key['id_diagnostic'].' ');
            $id_localisation_atteinte = $query->execute();
            $id_localisation_atteinte= $id_localisation_atteinte->_results;

            foreach ($id_localisation_atteinte as $key2 => $val) {
              $query = DB::query('SELECT nom FROM localisation_atteinte WHERE id='.$val['id_localisation_atteinte'].' ');
              $nom_localisation_atteinte = $query->execute();
              $nom_localisation_atteinte= $nom_localisation_atteinte->_results[0]['nom'];

              echo $nom_localisation_atteinte.'<br/>';
            }          
            echo'</td>';
            echo '<td class="text-center">';

            $query = DB::query('SELECT id_appareil_compensatoire FROM appareil_sujet WHERE id_sujet_handicape='.$key['id_sujet_handicape'].' AND id_diagnostic='.$key['id_diagnostic'].' ');
            $id_appareil_compensatoire = $query->execute();
            $id_appareil_compensatoire= $id_appareil_compensatoire->_results;

            foreach ($id_appareil_compensatoire as $key2 => $val) {
              $query = DB::query('SELECT type_appareil FROM appareil_compensatoire WHERE id_appareil_compensatoire='.$val['id_appareil_compensatoire'].' ');
              $nom_appareil = $query->execute();
              $nom_appareil= $nom_appareil->_results[0]['type_appareil'];
              echo $nom_appareil.'<br/>';
          }
            echo'</td>
            <td class="text-center">';
            echo $description_autre;
            echo'</td>';
          endforeach; ?>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="row">
      <div class="col">
        <div class="p-2">Commentaire du diagnostic : <?= $commentaire_sujet;?></div>
      </div>
    </div>
    <?php 
      else: 
        echo'<div class="col">
        <div class="p-2">Aucune atteinte</div>
        </div>'; 
      endif; ?>
  </div>
  <div class="d-grid gap-2 d-md-block p-1">
    <a class="btn btn-secondary" href="/public/operations/view/<?= $info_groupe['id_operation'];?>" role="button">Retour</a>
  </div>

<!--  Permet d'afficher un message d'alert avant la confirmation d'une suppression -->
  <script type="text/javascript">
    $("[name=btn_supp_sujet]").click(function() {
      var x=$(this).val();
      if (window.confirm("Vous êtes sur le point de supprimer un sujet. Êtes-vous sûr de supprimer le sujet "+x+" ?")){
        $("#form_suppr_"+x).submit();
      }
    });
  </script>
  <?php
  //Fonction permettant d'afficher un message d'alert
  function alertBootstrap($text,$color){
    echo'<div class="alert alert-'.$color.' alert-dismissible text-center my-2 fade show" role="alert">
    '.$text.'
    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
    </div>';
  }?>
