<?php

use Fuel\Core\Asset;
use Model\Helper;
use Model\Operation;
use Model\Sujethandicape;
use Model\Typedepot;
use Model\Typesepulture;

/** @var Operation Operation actuelle. */
$operation = $operation;
/** @var Sujethandicape[] Liste des sujets handicapés concernés par l'opérations. */
$sujets = $sujets;

?>
<div class="container">
	<h1 class="m-2">Opération <?= $operation->getNomOp(); ?>
		<a class="btn btn-primary btn-sm" href="/public/sujet/add/<?= $operation->getIdSite(); ?>">Ajouter des sujets
			<i class="bi bi-plus-circle-fill"></i>
		</a>
	</h1>
	<p class="text-muted">Ici vous retrouvez toutes les informations de l'opération <strong><?= $operation->getNomOp(); ?></strong>.
	</p>
	<?php
	array_key_exists('erreur_supp_sujet', $_GET) ? Helper::alertBootstrap('Le numéro du sujet n\'est pas correcte (nombres autorisés). La suppression ne peut pas s\'effectuer', 'danger') : null;
	array_key_exists('erreur_supp_bdd', $_GET) ? Helper::alertBootstrap('Le numéro du sujet n\'existe pas. La suppression ne peut pas s\'effectuer', 'danger') : null;

	array_key_exists('success_ajout', $_GET) ? Helper::alertBootstrap('Ajout effectué', 'success') : null;
	array_key_exists('success_modif', $_GET) ? Helper::alertBootstrap('Modification effectuée', 'success') : null;
	array_key_exists('success_supp_sujet', $_GET) ? Helper::alertBootstrap('Suppression effectuée', 'success') : null;
	?>

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
			<div class="col">
				<div class="p-2">Département : <?= $operation->getCommune()->getDepartement(); ?></div>
			</div>
			<div class="col">
				<div class="p-2">Commune : <?= $operation->getCommune()->getNom(); ?></div>
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
					Responsable de l'opération :<br>
					<?php if ($operation->getResponsableOp() !== null): ?>
						- <?= $operation->getResponsableOp()->fullName(); ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="col">
				<div class="p-2">
					Anthropologues : 
					<?php foreach ($operation->getAnthropologues() as $person): ?>
						<br>- <?= $person->fullName(); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col">
				<div class="p-2">
					Paleopathologiste :
					<?php foreach ($operation->getPaleopathologistes() as $person): ?>
						<br>- <?= $person->fullName(); ?>
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
							<?php
							$i = 0;
							asort($sujets);
							foreach ($sujets as $sujet) :
								$i++;
								$typeDepot = Typedepot::fetchSingle($sujet->getIdTypeDepot());
								$typeSepulture = Typesepulture::fetchSingle($sujet->getIdSepulture());
							?>
								<tr class="text-center">
									<?= '<td>' . $sujet->getIdSujetHandicape() . ' (' . $i . ')</td>' ?>
									<?= '<td>' . $sujet->getSexe() . '</td>' ?>
									<?= '<td>' . $sujet->getDatation() . '</td>' ?>
									<?= '<td>' . $sujet->getMilieuVie() . '</td>' ?>
									<?= '<td>' . $typeDepot->getNom() . '</td>' ?>
									<?= '<td>' . $typeSepulture->getNom() . '</td>' ?>
									<td class="col-auto">
										<a title="Consulter #<?= $sujet->getId(); ?>" href="/public/sujet/view/<?= $sujet->getId(); ?>">
											<img class="icon see" width="30px" src="https://archeohandi.huma-num.fr/public/assets/img/reply.svg" alt="Consulter">
										</a>
										<a title="Editer #<?= $sujet->getId(); ?>" href="/public/sujet/edit/<?= $sujet->getId(); ?>">
											<img class="icon edit" width="24px" src="https://archeohandi.huma-num.fr/public/assets/img/pen.svg" alt="Éditer">
										</a>
										<form method="post" id="form_suppr_<?= $sujet->getId(); ?>">
											<button type="button" class="btn" name="btn_supp_sujet" value="<?= $sujet->getId(); ?>">
												<img class="icon del" width="25px" src="https://archeohandi.huma-num.fr/public/assets/img/trash.svg" alt="Supprimer">
												<input type="hidden" name="supp_sujet" value="<?= $sujet->getId(); ?>">
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
<?php else : echo '
				<div class="container">
					<h2>Aucun sujets handicapés</h2>
				</div>';
endif; ?>

<!-- Permet d'afficher un message d'alert avant la confirmation d'une suppression -->
<script type="text/javascript">
	$("[name=btn_supp_sujet]").click(function() {
		var x = $(this).val();
		if (window.confirm("Vous êtes sur le point de supprimer un sujet. Êtes-vous sûr de supprimer le sujet " + x + " ?")) {
			$("#form_suppr_" + x).submit();
		}
	});
</script>
<?= Asset::css('scrollbar.css'); ?>