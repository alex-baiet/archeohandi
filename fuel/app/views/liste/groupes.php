<?php

use Fuel\Core\Asset;
use Fuel\Core\DB;
use Fuel\Core\Form;
use Model\Groupesujet;
use Model\Helper;

/** @var Groupesujet[] */
$groups = $groups;

?>
<!-- Entête de la page -->
<div class="container col-auto">
  <h1 class="m-2">Liste des groupes</h1>
  <p class="text-muted">Ici vous retrouvez toutes les informations sur les groupes de sujet.</p>
  <button type="button" id="id_bouton_filtre" class="btn btn-danger">Afficher les filtres de recherche</button>
  <a class="btn btn-secondary" href="/public/liste/groupes">Rafraichir la page
    <i class="bi bi-arrow-repeat"></i>
  </a>
  
  <!-- Système de recherche (filtre) -->
  <?php /*
  <div name="filtres_recherche" id="filtres_recherche" class="d-none mt-3">
    <?= Form::open('/liste/groupes'); ?>
    <div class="form-group ml-3">
      <div class="form-check form-check-inline">
        <?= Form::radio('radio', 'operation', true); ?>
        <?= Form::label('Opération', 'operation'); ?>
        <select class="form-select custom-select my-1 mr-2" style="width:15em" id="id_select_operation" name="select_operation">
          <?php
          foreach ($groupe_op as $key) :
            $query = DB::query('SELECT nom_op FROM operations WHERE id_site="' . $key['id_operation'] . '" ');
            $select = $query->execute();
            $select_operation = $select->_results[0]['nom_op'];

            echo '<option value="' . $key['id_operation'] . '">' . $select_operation . '</option>';
          endforeach;
          ?>
        </select>
      </div>
      <div class="form-check form-check-inline">
        <?= Form::radio('radio', 'chronologie', false); ?>
        <?= Form::label('Chronology', 'chronologie'); ?>
        <select class="form-select custom-select my-1 mr-2" style="width:15em" id="id_select_chronologie" name="select_chronologie">
          <?php
          foreach ($all_chrono as $key) :
            echo '<option value="' . $key['id_chronologie'] . '">' . $key['nom_chronologie'] . '</option>';
          endforeach;
          ?>
        </select>
      </div>
      <div class="form-check form-check-inline">
        <?= Form::radio('radio', 'NMI', false); ?>
        <?= Form::label('NMI', 'NMI'); ?>
        <select class="form-select custom-select my-1 mr-2" style="width:15em" id="id_select_NMI" name="select_NMI">
          <?php
          foreach ($groupe_NMI as $key) :
            echo '<option value="' . $key['NMI'] . '">' . $key['NMI'] . '</option>';
          endforeach;
          ?>
        </select>
      </div>
      <?= Form::submit('recherche', 'Rechercher', array('class' => 'btn btn-success btn-sm')); ?>
    </div>
    <?= Form::close(); ?>
  </div>
  */ ?>
</div>
<br />
<!-- Contenu principal de la page -->
<div class="container">
  <div class="row">
    <div class="table-responsive">
      <div class="scrollbar_index">
        <table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
          <thead>
            <tr class="text-center">
              <th scope="col">Chronology</th>
              <th scope="col">Date de début</th>
              <th scope="col">Date de fin</th>
              <th scope="col">Opération</th>
              <th scope="col">NMI</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($groups as $group) : ?>
              <tr class="text-center">
                <td><?= $group->getChronology()->getName(); ?></td>
                <td><?= $group->getChronology()->getStart(); ?></td>
                <td><?= $group->getChronology()->getEnd(); ?></td>
                <td><?= $group->getOperation()->getNomOp(); ?></td>
                <td><?= $group->getNMI(); ?></td>
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
<!-- <script>
  $("#id_bouton_filtre").click(function() {
    if ($("#filtres_recherche").hasClass("d-none")) {
      $("#id_bouton_filtre").text("Masquer les filtres de recherche");
      $("#filtres_recherche").removeClass("d-none");
    } else {
      $("#id_bouton_filtre").text("Afficher les filtres de recherche");
      $("#filtres_recherche").addClass("d-none");
    }
  });
</script> -->