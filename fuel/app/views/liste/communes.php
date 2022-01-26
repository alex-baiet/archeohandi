<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;

?>

<!-- Entête de la page -->
<div class="container col-auto">
  <h1 class="m-2">Liste des communes</h1>
  <p class="text-muted">Ici vous retrouvez toutes les informations sur les communes de France.</p>
  <button type="button" id="id_bouton_filtre" class="btn btn-danger">Afficher les filtres de recherche</button>
  <a class="btn btn-secondary" href="/public/liste/communes?page=1">Rafraichir la page <i class="bi bi-arrow-repeat"></i></a>
  <div name="filtres_recherche" id="filtres_recherche" class="d-none mt-3">
    <!-- Système de recherche (filtre) -->
    <?= Form::open('/liste/communes?page=' . $page . ''); ?>
    <div class="form-group ml-3">
      <div class="form-check form-check-inline">
        <?= Form::radio('radio', 'commune', true); ?>
        <?= Form::label('Commune', 'commune'); ?>
        <input type="text" name="commune" id="commune" class="form-control border-info my-1 mr-2" placeholder="Rechercher une commune ..." autocomplete="off">
        <div class="col-auto">
          <div class="list-group" id="show-list"></div>
        </div>
      </div>

      <div class="form-check form-check-inline">
        <?= Form::radio('radio', 'departement', false); ?>
        <?= Form::label('Département', 'departement'); ?>
        <select class="form-select custom-select my-1 mr-2" style="width:15em" id="id_select_departement" name="select_departement">
          <?php
          foreach ($departement as $key) :
            echo '
            <option value="' . $key['departement'] . '">' . $key['departement'] . '</option>';
          endforeach;
          ?>
        </select>
      </div>
      <div class="form-check form-check-inline">
        <?= Form::radio('radio', 'region', false); ?>
        <?= Form::label('Région', 'region'); ?>
        <select class="form-select custom-select my-1 mr-2" style="width:15em" id="id_select_region" name="select_region">
          <?php
          foreach ($region as $key) :
            echo '
            <option value="' . $key['region'] . '">' . $key['region'] . '</option>';
          endforeach;
          ?>
        </select>
      </div>
      <?= Form::submit('recherche', 'Rechercher', array('class' => 'btn btn-success btn-sm')); ?>
      <?= Form::close(); ?>
    </div>
  </div>
</div>
<br />
<!-- Contenu principal de la page -->
<div class="container well">
  <div class="row">
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item">
          <a class="page-link" href="/public/liste/communes?page=<?= $Previous; ?>" tabindex="-1" aria-disabled="true">Previous</a>
        </li>
        <li class="page-item"><a class="page-link" href="/public/liste/communes?page=1">Début</a></li>
        <?php if ($Previous >= 1) : ?><li class="page-item"><a class="page-link" href="/public/liste/communes?page=<?= $Previous; ?>"><?= $Previous; ?></a></li><?php endif; ?>
        <li class="page-item active"><a class="page-link active" href="/public/liste/communes?page=<?= $page; ?>"><?= $page; ?></a></li>
        <?php if ($Next <= $pages) : ?><li class="page-item"><a class="page-link" href="/public/liste/communes?page=<?= $Next; ?>"><?= $Next; ?></a></li><?php endif; ?>
        <li class="page-item"><a class="page-link" href="/public/liste/communes?page=<?= $pages; ?>">Fin</a></li>
        <li class="page-item">
          <a class="page-link" href="/public/liste/communes?page=<?= $Next; ?>">Next</a>
        </li>
      </ul>
    </nav>
    <div class="table-responsive">
      <div class="scrollbar_index">
        <table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
          <thead>
            <tr class="text-center">
              <th scope="col">Nom</th>
              <th scope="col">Code postal</th>
              <th scope="col">X</th>
              <th scope="col">Y</th>
              <th scope="col">Z</th>
              <th scope="col">Département</th>
              <th scope="col">Région</th>
              <th scope="col">Pays</th>
              <th scope="col">Superficie</th>
              <th scope="col">Population</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_commune as $key) : ?>
              <tr class="text-center">
                <?= '<td>' . $key['nom'] . '</td>'; ?>
                <?= '<td>' . $key['code_postal'] . '</td>'; ?>
                <?= '<td>' . $key['x'] . '</td>'; ?>
                <?= '<td>' . $key['y'] . '</td>'; ?>
                <?= '<td>' . $key['z'] . '</td>'; ?>
                <?= '<td>' . $key['departement'] . '</td>'; ?>
                <?= '<td>' . $key['region'] . '</td>'; ?>
                <?= '<td>' . $key['pays'] . '</td>'; ?>
                <?= '<td>' . $key['superficie'] . '</td>'; ?>
                <?= '<td>' . $key['population'] . '</td>'; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<?= Asset::css('scrollbar.css'); ?>
<!-- Script permet d'afficher ou non les options du filtre -->
<script>
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