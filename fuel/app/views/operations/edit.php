<?php

use Fuel\Core\Asset;
use Fuel\Core\DB;
use Fuel\Core\Form;
use Model\Operation;
use Model\Organisme;
use Model\Typeoperation;

/** @var Operation */
$operation = $operation;

?>
<!-- Entête de la page -->
<div class="container">
	<h1 class="m-2">Modifier l'opération <?= $operation->getNomOp() ?>
		<a class="btn btn-sm btn-secondary" href="/public/operations/edit/<?= $operation->getIdSite(); ?>">Rafraichir la page
			<i class="bi bi-arrow-repeat"></i>
		</a>
	</h1>
	<p class="text-muted">Ici vous pouvez modifier une opération.</p>
</div>
<?= Form::open(array('action' => 'operations/edit/' . $operation->getIdSite() . '', 'method' => 'POST'));
//Permet de vérifier si dans l'url il y a les différentes options et si oui, cela appel une fonction qui permet d'afficher un message d'erreur
// array_key_exists('erreur_alpha_adresse', $_GET) ? alertBootstrap('L\'adresse ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: àáâãäåçèéêëìíîïðòóôõöùúûüýÿ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_ea', $_GET) ? alertBootstrap('L\'EA ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_oa', $_GET) ? alertBootstrap('L\'OA ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_patriarche', $_GET) ? alertBootstrap('Le patriarche ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_numop', $_GET) ? alertBootstrap('Le numéro d\'opération ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_prescription', $_GET) ? alertBootstrap('L\'arrêté prescription ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_ro', $_GET) ? alertBootstrap('Le responsable de l\'opération ne correspond pas au pattern de validation (De A à Z, a à z, 0 à 9, l\'espace et les caractères suivant: ,-;()/ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_anthro', $_GET) ? alertBootstrap('L\'anthropologiste ne correspond pas au pattern de validation (De A à Z, a à z, l\'espace, la virgule et les caractères suivant: àáâãäåçèéêëìíîïðòóôõöùúûüýÿ sont autorisés)', 'info') : null;
// array_key_exists('erreur_alpha_paleo', $_GET) ? alertBootstrap('Le paléopathologiste ne correspond pas au pattern de validation (De A à Z, a à z, l\'espace, la virgule et les caractères suivant: àáâãäåçèéêëìíîïðòóôõöùúûüýÿ sont autorisés)', 'info') : null;

// array_key_exists('erreur_adresse', $_GET) ? alertBootstrap('L\'adresse n\'est pas saisie', 'danger') : null;

// array_key_exists('erreur_annee_vide', $_GET) ? alertBootstrap('L\'année est vide', 'danger') : null;
// array_key_exists('erreur_annee', $_GET) ? alertBootstrap('L\'année n\'est pas correcte (chiffre autorisé)', 'danger') : null;

// array_key_exists('erreur_X', $_GET) ? alertBootstrap('La position X n\'est pas un nombre', 'danger') : null;
// array_key_exists('erreur_Y', $_GET) ? alertBootstrap('La position Y n\'est pas un nombre', 'danger') : null;

// array_key_exists('erreur_commune_select', $_GET) ? alertBootstrap('Veuillez choisir une commune', 'danger') : null;
// array_key_exists('erreur_commune', $_GET) ? alertBootstrap('La commune n\'existe pas', 'danger') : null;

// array_key_exists('erreur_organisme_select', $_GET) ? alertBootstrap('Veuillez sélectionner l\'organisme', 'danger') : null;
// array_key_exists('erreur_organisme', $_GET) ? alertBootstrap('L\'organisme ne correspond pas aux propositions', 'danger') : null;

// array_key_exists('erreur_type_operation_select', $_GET) ? alertBootstrap('Veuillez sélectionner le type d\'opération', 'danger') : null;
// array_key_exists('erreur_type_operation', $_GET) ? alertBootstrap('Le type d\'opération ne correspond pas aux propositions', 'danger') : null;

//Permet de vérifier si il y a c'est option et si oui cela permet de savoir quelle option est cochée pour l'afficher
if (array_key_exists('commune', $_GET)) : $commune_check = $_GET['commune'];
else : $commune_check = null;
endif;
if (array_key_exists('organisme', $_GET)) : $organisme_check = $_GET['organisme'];
else : $organisme_check = null;
endif;
if (array_key_exists('type_op', $_GET)) : $type_op_check = $_GET['type_op'];
else : $type_op_check = null;
endif;


//Permet de récupérer le nom commune
$query = DB::query('SELECT nom FROM commune WHERE id=' . $operation->getIdCommune());
$nom_commune = $query->execute();
$nom_commune = $nom_commune->_results[0]['nom'];

// Array des attributs les plus communs.
$defaultAttr = array("type" => "text", "class" => "form-control", "placeholder" => "");

//Pour chaque input, l'option value vérifie dans un premier si il existe dans l'url son champs qui lui est propre et affiche sa valeur en cas d'erreur et sinon elle affiche la valeur de l'opération pour ce champs là.
?>
<div class="container" style="background-color: #F5F5F5;">
	<div class="row my-2 pt-1">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("adresse", $operation->getAdresse(), $defaultAttr); ?>
				<?= Form::label('Adresse', 'adresse'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("annee", $operation->getAnnee(), array("type" => "number", "class" => "form-control", "placeholder" => "")); ?>
				<?= Form::label('Année de l\'opération', 'annee'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("X", $operation->getX(), array("type" => "number", "class" => "form-control", "placeholder" => "", "step" => "any")); ?>
				<?= Form::label('Position X', 'X'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("Y", $operation->getY(), array("type" => "number", "class" => "form-control", "placeholder" => "", "step" => "any")); ?>
				<?= Form::label('Position Y', 'Y'); ?>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<?= Form::label('À revoir', 'a_revoir'); ?>
		<?= Form::textarea("a_revoir", $operation->getARevoir(), array("class" => "form-control")); ?>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-floating">
				<?= Form::input("commune", $operation->getCommune()->getNom(), array("type" => "text", "class" => "form-control my-4")); ?>
				<!-- <input type="text" name="commune" id="commune" class="form-control border-info my-4" placeholder="Rechercher une commune ..." autocomplete="off" value="<?= $nom_commune; ?>" required> -->
				<?= Form::label('Commune', 'commune'); ?>
			</div>
			<div class="col-md-auto">
				<!-- Zone d'autocomplétion ...? -->
				<!-- <div class="list-group" id="show-list"></div> -->
			</div>
		</div>
		<div class="col-md-4">
			<?= Organisme::generateSelect("organisme", $operation->getIdOrganisme()); ?>
		</div>
		<div class="col-md-4">
			<?= Typeoperation::generateSelect("type_operation", $operation->getIdTypeOp()); ?>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("EA", $operation->getEA(), $defaultAttr); ?>
				<?= Form::label('EA', 'EA'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("OA", $operation->getOA(), $defaultAttr); ?>
				<?= Form::label('OA', 'OA'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("patriarche", $operation->getPatriarche(), $defaultAttr); ?>
				<?= Form::label('Patriarche', 'patriarche'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("numero_operation", $operation->getNumeroOperation(), $defaultAttr); ?>
				<?= Form::label('Numéro d\'opération', 'numero_operation'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("arrete_prescription", $operation->getArretePrescription(), $defaultAttr); ?>
				<?= Form::label('Arrêté de prescription', 'arrete_prescription'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("responsable_op", $operation->getResponsableOp(), $defaultAttr); ?>
				<?= Form::label('Responsable de l\'opération', 'responsable_op'); ?>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("anthropologue", $operation->getAnthropologue(), $defaultAttr); ?>
				<?= Form::label('Anthropologue (Nom Prénom, etc.)', 'anthropologue'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating">
				<?= Form::input("paleopathologiste", $operation->getPaleopathologiste(), $defaultAttr); ?>
				<?= Form::label('Paléopathologiste (Nom Prénom, etc.)', 'paleopathologiste'); ?>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<?= Form::label('Bibliographie', 'bibliographie'); ?>
		<?= Form::textarea("bibliographie", $operation->getBibliographie(), array("class" => "form-control")); ?>
	</div>
	<br />
	<div class="d-grid gap-2 d-md-block">
		<a class="btn btn-secondary" href="/public/operations" role="button">Retour</a>
	</div>
	<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-top: 10px;">
		<?= Form::submit('submit', 'Modifier', array('class' => 'btn btn-success')); ?>
	</div>
</div>
<?= Form::close(); ?>

<?php
Asset::js('script_recherche.js');
//Fonction permettant d'afficher un message d'alert 
function alertBootstrap($text, $color)
{
	echo '<div class="alert alert-' . $color . ' alert-dismissible text-center my-2 fade show" role="alert">
			' . $text . '
			<button type="button" class="btn-close" data-dismiss="alert" aria-label="Fermer">
			</div>';
} ?>