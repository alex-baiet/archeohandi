<!-- Entête de la page -->
<div class="container">
  <h1 class="m-2">Opérations (<?= count($operations); ?>)
    <a class="btn btn-primary btn-sm" href="/public/add/operation">Ajouter une opération
      <i class="bi bi-plus-circle-fill"></i>
    </a>
  </h1>
  <p class="text-muted">Ici vous retrouvez toutes les informations sur les opérations.</p>
  <div class="ml-3">
    <button type="button" id="id_bouton_filtre" class="btn btn-danger">Afficher les filtres de recherche</button>
    <a class="btn btn-secondary" href="/public/operations">Rafraichir la page
      <i class="bi bi-arrow-repeat"></i>
    </a>
    <!-- Système de recherche (filtre) -->
    <div name="filtres_recherche" id="filtres_recherche" class="d-none mt-3">
      <?= Form::open('/operations'); ?>
      <div class="form-group ml-3">
        <div class="form-check form-check-inline">
          <?= Form::radio('radio', 'Id', true); ?>
          <?= Form::label('#', 'Id'); ?>
          <select class="form-select custom-select my-1 mr-2" style="width:15em" name="select_site">
            <?php
            foreach ($all_site as $key) :
              echo '<option value="' . $key['id_site'] . '">' . $key['id_site'] . '</option>';
            endforeach;
            ?>
          </select>
        </div>
        <div class="form-check form-check-inline">
          <?= Form::radio('radio', 'user'); ?>
          <?= Form::label('Utilisateur', 'user'); ?>
          <select class="form-select custom-select my-1 mr-2" style="width:15em" name="select_user">
            <?php
            foreach ($all_user as $key) :
              echo '<option value="' . $key['id_user'] . '">' . $key['id_user'] . '</option>';
            endforeach;
            ?>
          </select>
        </div>
        <div class="form-check form-check-inline">
          <?= Form::radio('radio', 'nom_op'); ?>
          <?= Form::label('Nom de l\'opération', 'nom_op'); ?>
          <select class="form-select custom-select my-1 mr-2" style="width:15em" name="select_op">
            <?php
            foreach ($all_nom_op as $key) :
              echo '<option value="' . $key['nom_op'] . '">' . $key['nom_op'] . '</option>';
            endforeach;
            ?>
          </select>
        </div>
        <div class="form-check form-check-inline">
          <?= Form::radio('radio', 'annee', false); ?>
          <?= Form::label('Année', 'annee'); ?>
          <select class="form-select custom-select my-1 mr-2" style="width:15em" name="select_annee">
            <?php
            foreach ($all_annee as $key) :
              echo '<option value="' . $key['annee'] . '">' . $key['annee'] . '</option>';
            endforeach;
            ?>
          </select>
        </div>
        <?= Form::submit('recherche', 'Rechercher', array('class' => 'btn btn-success btn-sm')); ?>
      </div>
    </div>
    <?= Form::close(); ?>
  </div>
  <?php
  //Permet de vérifier si dans l'url il y a les différentes options et si oui, cela appel une fonction qui permet d'afficher un message  
  array_key_exists('erreur_supp_op', $_GET) ? alertBootstrap('Le numéro de l\'opération n\'est pas correcte (nombres autorisés). La suppression ne peut pas s\'effectuer', 'danger') : null;
  array_key_exists('erreur_supp_bdd', $_GET) ? alertBootstrap('Le numéro de l\'opération n\'existe pas. La suppression ne peut pas s\'effectuer', 'danger') : null;

  array_key_exists('success_ajout', $_GET) ? alertBootstrap('Les ajouts ont été effectué', 'success') : null;
  array_key_exists('success_modif', $_GET) ? alertBootstrap('Modification effectuée', 'success') : null;
  array_key_exists('success_supp_op', $_GET) ? alertBootstrap('Suppression effectuée', 'success') : null; ?>
</div>
<br />
<!-- Contenu de la page -->
<div class="container">
  <div class="row">
    <div class="table-responsive">
      <div class="scrollbar_index">
        <table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
          <thead>
            <tr class="text-center">
              <!-- <th scope="col">#</th> -->
              <th scope="col">Utilisateur de la saisie</th>
              <th scope="col">Nom</th>
              <th scope="col">Année</th>
              <th scope="col">Position X</th>
              <th scope="col">Position Y</th>
              <th scope="col">Options</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($operations as $key) :  ?>
              <tr class="text-center">
                <?php //echo '<td scope="col">'.$key['id_site'].'</td>';
                ?>
                <?= '
              <td>' . $key['id_user'] . '</td>'; ?>
                <?= '
              <td>' . $key['nom_op'] . '</td>'; ?>
                <?= '
              <td>' . $key['annee'] . '</td>'; ?>
                <?= '
              <td>' . $key['X'] . '</td>'; ?>
                <?= '
              <td>' . $key['Y'] . '</td>'; ?>
                <td class="col-auto">
                  <a title="Consulter #<?= $key['id_site']; ?>" href="/public/operations/view/<?= $key['id_site']; ?>">
                    <img class="icon see" width="30px" src="https://archeohandi.huma-num.fr/public/assets/img/reply.svg" alt="Consulter">
                  </a>
                  <a class="" title="Editer #
                    <?= $key['id_site']; ?>" href="/public/operations/edit/<?= $key['id_site']; ?>">
                    <img class="icon edit" width="24px" src="https://archeohandi.huma-num.fr/public/assets/img/pen.svg" alt="Éditer">
                  </a>
                  <form action="" method="post" id="form_suppr_<?= $key['id_site']; ?>">
                    <button type="button" class="btn" name="btn_supp_op" value="<?= $key['id_site']; ?>">
                      <img class="icon del" width="25px" src="https://archeohandi.huma-num.fr/public/assets/img/trash.svg" alt="Supprimer">
                      <input type="hidden" name="supp_op" value="<?= $key['id_site']; ?>">
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>



<script>
  //Permet d'afficher un message d'alert avant la confirmation d'une suppression
  $("[name=btn_supp_op]").click(function() {
    var x = $(this).val();
    if (window.confirm("Vous êtes sur le point de supprimer une opération. La suppression d'une opération comprend aussi la suppression de tous sujets et groupes liés à cette opération. Êtes-vous sûr de supprimer l'opération " + x + " ?")) {
      $("#form_suppr_" + x).submit();
    }
  });
  //Script permet d'afficher ou non les options du filtre
  $("#id_bouton_filtre").click(function() {
    if ($("#filtres_recherche").hasClass("d-none")) {
      $("#id_bouton_filtre").text("Masquer les filtres de recherche");
      $("#filtres_recherche").removeClass("d-none");
    } else {
      $("#id_bouton_filtre").text("Afficher les filtres de recherche");
      $("#filtres_recherche").addClass("d-none");
    }
  });
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
<?= Asset::css('scrollbar.css'); ?>