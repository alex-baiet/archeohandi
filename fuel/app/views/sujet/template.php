<?php


use Fuel\Core\Uri;
use Model\Db\Compte;
use Model\Db\Sujethandicape;

/** @var string Contenu principal de la page. */
$content = $content;
/** @var Sujethandicape */
$subject = $subject;

/** Nom de la view utilisé */
$currentTab = Uri::segment(2);

?>

<div class="container">
	<!-- Navigation dans l'opération -->
	<a class="btn btn-secondary" href="/public/operations/sujets/<?= $subject->getGroup()->getIdOperation() ?>" role="button"
		style="position: absolute">Retour</a>
	
	<ul class="nav nav-tabs justify-content-center mt-4">
		<li class="nav-item">
			<a class="nav-link" id="view" href="/public/sujet/view/<?= $subject->getId() ?>">Informations sur le sujet</a>
		</li>
		<?php if (Compte::checkPermission(Compte::PERM_WRITE, $subject->getGroup()->getOperation()->getId())) : ?>
			<li class="nav-item">
				<a class="nav-link" id="edit" href="/public/sujet/edit/<?= $subject->getId() ?>">Modifier les informations du sujet</a>
			</li>
		<?php endif; ?>
	</ul>
	<script>
		PageManager.selectNav(`<?= $currentTab ?>`);
	</script>

	<?= $content ?>
</div>