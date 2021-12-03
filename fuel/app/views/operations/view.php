<?php
foreach ($operation_details as $key) :
  $query = DB::query('SELECT nom,departement FROM commune WHERE id=' . $key['id_commune'] . ' ');
  $detail_commune = $query->execute();
  $detail_commune = $detail_commune->_results[0];


  $query = DB::query('SELECT nom FROM organisme WHERE id=' . $key['id_organisme'] . ' ');
  $organisme = $query->execute();
  if (isset($organisme->_results[0])) {
    $organisme = $organisme->_results[0]['nom'];
  }

  $query = DB::query('SELECT nom FROM type_operation WHERE id=' . $key['id_type_op'] . ' ');
  $type_operation = $query->execute();
  $type_operation = $type_operation->_results[0]['nom'];
?>
  <div class="container">
    <h1 class="m-2">Opération <?= $key['nom_op']; ?>
      <a class="btn btn-primary btn-sm" href="/public/add/sujet/<?= $key['id_site']; ?>">Ajouter des sujets
        <i class="bi bi-plus-circle-fill"></i>
      </a>
    </h1>
    <p class="text-muted">Ici vous retrouvez toutes les informations de l'opération
      <strong>
        <?= $key['nom_op']; ?>
      </strong> .
    </p>
    <?php
    array_key_exists('erreur_supp_sujet', $_GET) ? alertBootstrap('Le numéro du sujet n\'est pas correcte (nombres autorisés). La suppression ne peut pas s\'effectuer', 'danger') : null;
    array_key_exists('erreur_supp_bdd', $_GET) ? alertBootstrap('Le numéro du sujet n\'existe pas. La suppression ne peut pas s\'effectuer', 'danger') : null;

    array_key_exists('success_ajout', $_GET) ? alertBootstrap('Ajout effectué', 'success') : null;
    array_key_exists('success_modif', $_GET) ? alertBootstrap('Modification effectuée', 'success') : null;
    array_key_exists('success_supp_sujet', $_GET) ? alertBootstrap('Suppression effectuée', 'success') : null; ?>
    <!-- Contenu de la page. Affichage des informations de l'opération -->
    <div class="container" style="background-color: #F5F5F5;">
      <h4>Informations</h4>
      <div class="row">
        <div class="col">
          <div class="p-2">Année de l'opération : <?= $key['annee']; ?></div>
        </div>
        <div class="col">
          <div class="p-2">Type d'opération : <?= $type_operation; ?></div>
        </div>
        <div class="col">
          <div class="p-2">Organisme : <?php echo $organisme; ?></div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="p-2">Département : <?= $detail_commune['departement']; ?></div>
        </div>
        <div class="col">
          <div class="p-2">Commune : <?= $detail_commune['nom']; ?></div>
        </div>
        <div class="col">
          <div class="p-2">Adresse : <?= $key['adresse']; ?></div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="p-2">Numéro d'opération : <?= $key['numero_operation']; ?></div>
        </div>
        <div class="col">
          <div class="p-2">Patriarche : <?= $key['patriarche']; ?></div>
        </div>
        <div class="col">
          <div class="p-2">EA : <?= $key['EA']; ?></div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="p-2">OA : <?= $key['OA']; ?></div>
        </div>
      </div>
    </div>
    <br />
    <div class="container" style="background-color: #F5F5F5;">
      <h4>Personnes</h4>
      <div class="row">
        <div class="col">
          <div class="p-2">Responsable de l'opération : <?= $key['responsable_op']; ?></div>
        </div>
        <div class="col">
          <div class="p-2">Anthropologue : <?= $key['anthropologue']; ?></div>
        </div>
        <div class="col">
          <div class="p-2">Paleopathologiste : <?= $key['paleopathologiste']; ?></div>
        </div>
      </div>
    </div>
    <br />
    <div class="container" style="background-color: #F5F5F5;">
      <h4>Autre</h4>
      <p>Bibliographie : <?= $key['bibliographie']; ?></p>
    </div>
  </div>
  <br />
  <?php

  $query = DB::query('SELECT id_groupe_sujets FROM groupe_sujets WHERE id_operation=' . $key['id_site'] . ' ');
  $id_groupe_sujets = $query->execute();
  $id_groupe_sujets = $id_groupe_sujets->_results;

  foreach ($id_groupe_sujets as $key => $val) :
    $query = DB::query('SELECT * FROM sujet_handicape WHERE id_groupe_sujets=' . $val['id_groupe_sujets'] . ' ');
    $sujet_handicap = $query->execute();
    $all_sujet_handicap[$key] = $sujet_handicap->_results;
  endforeach;
  // Vérifie si l'opération sélectionnée possède des sujets et si oui les affiches et si non affiche aucun sujet
  if (!empty($all_sujet_handicap)) : ?>
    <div class="container">
      <div class="row">
        <?php
        $l_sujets_count = 0;
        foreach ($all_sujet_handicap as $key) :
          foreach ($key as $key2) :
            $l_sujets_count++;
          endforeach;
        endforeach;
        ?>
        <h2>Sujets handicapés (<?= $l_sujets_count; ?>)</h2>
        <div class="table-responsive">
          <div class="scrollbar_view">
            <table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
              <thead>
                <tr class="text-center">
                  <th scope="col">ID</th>
                  <th scope="col">Sexe</th>
                  <th scope="col">Datation</th>
                  <th scope="col">Milieu de vie</th>
                  <th scope="col">Type de dépôt</th>
                  <th scope="col">Type de sépulture</th>
                  <th scope="col">Options</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 0;
                asort($all_sujet_handicap);
                foreach ($all_sujet_handicap as $key) :

                  foreach ($key as $key2) :
                    $i++;
                    $query = DB::query('SELECT nom FROM type_depot WHERE id=' . $key2['id_type_depot'] . ' ');
                    $nom_depot = $query->execute();
                    $nom_depot = $nom_depot->_results[0]['nom'];

                    $query = DB::query('SELECT nom FROM type_sepulture WHERE id=' . $key2['id_sepulture'] . ' ');
                    $nom_sepulture = $query->execute();
                    $nom_sepulture = $nom_sepulture->_results[0]['nom'];
                ?>
                    <tr class="text-center">
                      <?= '<td>' . $key2['id_sujet_handicape'] . ' (' . $i . ')</td>' ?>
                      <?= '<td>' . $key2['sexe'] . '</td>' ?>
                      <?= '<td>' . $key2['datation'] . '</td>' ?>
                      <?= '<td>' . $key2['milieu_vie'] . '</td>' ?>
                      <?= '<td>' . $nom_depot . '</td>' ?>
                      <?= '<td>' . $nom_sepulture . '</td>' ?>
                      <td class="col-auto">
                        <a title="Consulter #<?= $key2['id']; ?>" href="/public/sujet/view/<?= $key2['id']; ?>">
                          <img class="icon see" width="30px" src="https://archeohandi.huma-num.fr/public/assets/img/reply.svg" alt="Consulter">
                        </a>
                        <a title="Editer #<?= $key2['id']; ?>" href="/public/sujet/edit/<?= $key2['id']; ?>">
                          <img class="icon edit" width="24px" src="https://archeohandi.huma-num.fr/public/assets/img/pen.svg" alt="Éditer">
                        </a>
                        <form method="post" id="form_suppr_<?= $key2['id']; ?>">
                          <button type="button" class="btn" name="btn_supp_sujet" value="<?= $key2['id']; ?>">
                            <img class="icon del" width="25px" src="https://archeohandi.huma-num.fr/public/assets/img/trash.svg" alt="Supprimer">
                            <input type="hidden" name="supp_sujet" value="<?= $key2['id']; ?>">
                          </button>
                        </form>
                      </td>
                    </tr>
                <?php endforeach;
                endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php else : echo '
        <div class="container">
          <h2>Aucun sujets handicapés</h2>
        </div>';
  endif; ?>
<?php endforeach; ?>

<!-- Permet d'afficher un message d'alert avant la confirmation d'une suppression -->
<script type="text/javascript">
  $("[name=btn_supp_sujet]").click(function() {
    var x = $(this).val();
    if (window.confirm("Vous êtes sur le point de supprimer un sujet. Êtes-vous sûr de supprimer le sujet " + x + " ?")) {
      $("#form_suppr_" + x).submit();
    }
  });
</script>
<?php //Fonction permettant d'afficher un message d'alert
function alertBootstrap($text, $color)
{
  echo '<div class="alert alert-' . $color . ' alert-dismissible text-center my-2 fade show" role="alert">
    ' . $text . '
    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
    </div>';
} ?>
<?= Asset::css('scrollbar.css'); ?>