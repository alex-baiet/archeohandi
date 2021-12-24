<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\View;

?>

<!-- Contenu de la page partie opération -->
<div class="" id="ajout_operation">
  <div class="container">
    <h1 class="m-2">
      Ajout d'une opération
      <a class="btn btn-sm btn-secondary" href="/public/operations/add">Rafraichir la page <i class="bi bi-arrow-repeat"></i></a>
    </h1>
    <p class="text-muted">
      Ici vous pouvez ajouter une opération de façon simplifiée.
      Pour aller plus vite, vous pouvez utiliser la touche TAB
      <?= Asset::img('TAB.jpg', array('width' => '75px', 'height' => '35px')); ?>
      pour aller d'un champs texte à un autre.
    </p>
    
    <?=
    View::forge("operations/form", array(
      "action" => "operations/add",
      "modalContent" => 
        "En continuant, vous allez ajouter des sujets à l'opération que vous venez de créer.
        En vous stopant, vous serrez redirigé vers la page des opérations.<br /><br />
        <i class='bi bi-info-circle-fill'></i>
        Pour ajouter des sujets plus tard, il vous suffit d'aller dans le détail d'une opération.</p>"
    ));
    ?>
    
  </div>
</div>
