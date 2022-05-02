<?php


use Fuel\Core\Uri;
use Model\Db\Compte;

/** @var string Contenu principal de la page. */
$content = $content;

/** Nom de la view utilisé */
$currentTab = Uri::segment(2);
$idOperation = Uri::segment(3);

?>

<div class="container">
	<!-- Navigation dans l'opération -->
	<ul class="nav nav-tabs justify-content-center mt-4">
		<li class="nav-item">
			<a class="nav-link" id="view" href="/public/operation/view/<?= $idOperation ?>">
				<span class="pc-only">Informations sur le site</span>
				<span class="mobile-only">Informations</span>
			</a>
		</li>
		<?php if (Compte::checkPermission(Compte::PERM_ADMIN, $idOperation)) : ?>
			<li class="nav-item">
				<a class="nav-link" id="edit" href="/public/operation/edit/<?= $idOperation ?>">
					<span class="pc-only">Éditer les informations du site</span>
					<span class="mobile-only">Édition</span>
				</a>
			</li>
		<?php endif; ?>
		<li class="nav-item">
			<a class="nav-link" id="sujets" href="/public/operation/sujets/<?= $idOperation ?>">
				<span class="pc-only">Voir les sujets</span>
				<span class="mobile-only">Sujets</span>
			</a>
		</li>
	</ul>
	<script>
		PageManager.selectNav(`<?= $currentTab ?>`);
	</script>

	<?= $content ?>
</div>