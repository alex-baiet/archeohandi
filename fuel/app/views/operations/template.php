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
			<a class="nav-link" id="view" href="/public/operations/view/<?= $idOperation ?>">Informations</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="sujets" href="/public/operations/sujets/<?= $idOperation ?>">Sujets</a>
		</li>
		<?php if (Compte::checkPermission(Compte::PERM_ADMIN, $idOperation)) : ?>
			<li class="nav-item">
				<a class="nav-link" id="edit" href="/public/operations/edit/<?= $idOperation ?>">Modifier</a>
			</li>
		<?php endif; ?>
	</ul>
	<script>
		PageManager.selectNav(`<?= $currentTab ?>`);
	</script>

	<?= $content ?>
</div>