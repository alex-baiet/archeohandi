<?php


use Fuel\Core\Uri;
use Model\Db\Compte;

/** @var string Contenu principal de la page. */
$content = $content;

$segments = Uri::segments();
/** Nom de la view utilisé */
$currentTab = Uri::segment(2);
$idOperation = Uri::segment(3);

?>

<script>
	/** Rend "actif" l'onglet de navigation correspondant. */
	function selectNav(idTab) {
		document.getElementById(idTab).classList.add("active");
	}
</script>

<div class="container">
	<!-- Navigation dans l'opération -->
	<ul class="nav nav-tabs justify-content-center mt-4">
		<li class="nav-item">
			<a class="nav-link" id="tabView" href="/public/operations/view/<?= $idOperation ?>">Informations</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="tabSubjects" href="/public/operations/sujets/<?= $idOperation ?>">Sujets</a>
		</li>
		<?php if (Compte::checkPermission(Compte::PERM_WRITE, $idOperation)) : ?>
			<li class="nav-item">
				<a class="nav-link" id="tabEdit" href="/public/operations/edit/<?= $idOperation ?>">Editer</a>
			</li>
		<?php endif; ?>
	</ul>
	<script>
		selectNav(`<?= $currentTab ?>`);
	</script>

	<?= $content ?>
</div>