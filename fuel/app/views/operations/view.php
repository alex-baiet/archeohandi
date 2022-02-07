<?php

use Fuel\Core\Asset;
use Fuel\Core\Form;
use Fuel\Core\View;
use Model\Db\Compte;
use Model\Db\Operation;
use Model\Db\Typedepot;
use Model\Db\Typesepulture;

/** @var Operation Operation actuelle. */
$operation = $operation;
/** @var string Message a afficher. */
$msg = isset($msg) ? $msg : null;

$sujets = $operation->getSubjects();

?>

<script type="text/javascript">
	function deleteSubject(idSubject) {
		let btnElem = document.getElementById("form_delete_sujet");
		btnElem.value = idSubject;
	}
</script>

<div class="container">
	<h1 class="m-2">Opération <?= $operation->getNomOp(); ?>
		<?php if (Compte::checkPermission(Compte::PERM_WRITE, $operation->getId())) : ?>
			<a class="btn btn-primary btn-sm" href="/public/sujet/add/<?= $operation->getId(); ?>">
				Ajouter des sujets <i class="bi bi-plus-circle-fill"></i>
			</a>
		<?php endif; ?>
	</h1>
	<p class="text-muted">Ici vous retrouvez toutes les informations de l'opération <strong><?= $operation->getNomOp(); ?></strong>.
	</p>

	<!-- Contenu de la page. Affichage des informations de l'opération -->
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Informations</h4>
		<div class="row">
			<div class="col">
				<div class="p-2">Année de l'opération : <?= $operation->getAnnee(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Type d'opération : <?= $operation->getTypeOperation()->getNom(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Organisme : <?= $operation->getOrganisme()->getNom(); ?></div>
			</div>
		</div>
		<div class="row">
			<?php $commune = $operation->getCommune(); ?>
			<div class="col">
				<div class="p-2">Département : <?php if ($commune !== null) echo $commune->getDepartement(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Commune : <?php if ($commune !== null) echo $commune->getNom(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Adresse : <?= $operation->getAdresse(); ?></div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="p-2">Numéro d'opération : <?= $operation->getNumeroOperation(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Patriarche : <?= $operation->getPatriarche(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">EA : <?= $operation->getEA(); ?></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="p-2">OA : <?= $operation->getOA(); ?></div>
			</div>
		</div>
	</div>
	<br />
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Personnes</h4>
		<div class="row">
			<div class="col">
				<div class="p-2">
					Responsable de l'opération :<?= $operation->getResponsable(); ?>
				</div>
			</div>
			<div class="col">
				<div class="p-2">
					Anthropologue(s) :
					<?php foreach ($operation->getAnthropologues() as $person): ?>
						<br>- <?= $person; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col">
				<div class="p-2">
					Paleopathologiste(s) :
					<?php foreach ($operation->getPaleopathologistes() as $person): ?>
						<br>- <?= $person; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<br />
	<div class="container" style="background-color: #F5F5F5;">
		<h4>Autre</h4>
		<p>Bibliographie : <?= $operation->getBibliographie(); ?></p>
	</div>
</div>
<br />

<?php if (!empty($sujets)) : // Vérifie si l'opération sélectionnée possède des sujets et si oui les affiches et si non affiche aucun sujet ?>
	<div class="container">
		<div class="row">
			<h2>Sujets handicapés (<?= count($sujets); ?>)</h2>
			<div class="table-responsive">
				<div class="scrollbar_view" style="height: auto; max-height: 600px;">
					<table class="table table-striped table-hover table-bordered sticky" data-toggle="table" data-search="true">
						<thead>
							<tr class="text-center">
								<th scope="col">Identifiant</th>
								<th scope="col">Sexe</th>
								<th scope="col">Datation</th>
								<th scope="col">Milieu de vie</th>
								<th scope="col">Type de dépôt</th>
								<th scope="col">Type de sépulture</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 0;
							asort($sujets);
							foreach ($sujets as $sujet) :
								$i++;
								$typeDepot = Typedepot::fetchSingle($sujet->getIdTypeDepot());
								$typeSepulture = Typesepulture::fetchSingle($sujet->getIdTypeSepulture());
							?>
								<tr class="text-center">
									<td><?= $sujet->getIdSujetHandicape()?></td>
									<td><?= $sujet->getSexe() ?></td>
									<td><?= "{$sujet->getDatingMin()} - {$sujet->getDatingMax()}" ?></td>
									<td><?= $sujet->getMilieuVie() ?></td>
									<td><?= $typeDepot->getNom() ?></td>
									<td><?= $typeSepulture->getNom() ?></td>
									<td class="col-auto">

										<a title="Consulter #<?= $sujet->getId(); ?>" href="/public/sujet/view/<?= $sujet->getId(); ?>">
											Consulter
											<?= ""//Asset::img("reply.svg", array("class"=>"icon see", "width" => "30px", "alt" => "Consulter")) ?>
										</a>
										
										<?php if (Compte::checkPermission(Compte::PERM_WRITE, $operation->getId())) : ?>
											<br>
											<a title="Editer #<?= $sujet->getId(); ?>" href="/public/sujet/edit/<?= $sujet->getId(); ?>">
												Editer
												<?= ""//Asset::img("pen.svg", array("class"=>"icon edit", "width" => "24px", "alt" => "Éditer")) ?>
											</a>
											
											<?= Form::open(array("method" => "POST")); ?>
												<a
													href="" data-bs-toggle="modal" data-bs-target="#validationPopup" onclick="deleteSubject(<?= $sujet->getId(); ?>)">
													Supprimer
													<?= ""//Asset::img("trash.svg", array("class"=>"icon del", "width" => "25px", "alt" => "Supprimer")) ?>
												</a>
											<?= Form::close(); ?>
										<?php endif; ?>
										
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Popup de confirmation de suppression -->
	<?= View::forge("fonction/popup_confirm", array(
		"title" => "Voulez-vous continuer ?",
		"bodyText" => "Êtes-vous sûr de vouloir supprimer le sujet ?",
		"infoText" => "La suppression est irréversible.",
		"btnName" => "delete_sujet"
	)); ?>

<?php else : ?>
	<div class="container">
		<h2>Aucun sujet handicapé</h2>
	</div>
<?php endif; ?>

<?= Asset::css('scrollbar.css'); ?>