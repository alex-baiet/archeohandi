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
	<a class="btn btn-secondary mt-2" href="/public/operation/sujets/<?= $subject->getGroup()->getIdOperation() ?>" role="button">Retour vers l'opération</a>
	
	<ul class="nav nav-tabs justify-content-center mt-2">
		<li class="nav-item">
			<a class="nav-link" id="description" href="/public/sujet/description/<?= $subject->getId() ?>">
				<span class="pc-only">Informations sur le sujet</span>
				<span class="mobile-only">Informations</span>
			</a>
		</li>
		<?php if (Compte::checkPermission(Compte::PERM_WRITE, $subject->getGroup()->getOperation()->getId())) : ?>
			<li class="nav-item">
				<a class="nav-link" id="edition" href="/public/sujet/edition/<?= $subject->getId() ?>">
					<span class="pc-only">Éditer le sujet</span>
					<span class="mobile-only">Édition</span>
				</a>
			</li>
		<?php endif; ?>
	</ul>
	<script>
		PageManager.selectNav(`<?= $currentTab ?>`);
	</script>

	<?= $content ?>
</div>