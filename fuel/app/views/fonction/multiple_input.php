<?php
/*
Créé un champ pouvant être multiplié pour pouvoir
envoyer une liste de valeurs par formulaire.
*/

/** @var string */
$name = $name;
/** @var array */
$datas = $datas;
/** @var string */
$label = $label;
/** @var string|unset */
if (isset($autocompletion)) $autocompletion = $autocompletion;

?>
<div class="row my-2">
	<div class="col-md-9" id="form_<?= $name; ?>_parent">
		<?php $i=0; foreach ($datas as $item) : ?>
			<div class="row" id="form_<?= $name; ?>_copy_<?= $i; ?>">

				<div class="col-md">
					<div class="form-floating">
						<input
							name="<?= $name ?>[]" value="<?= $item ?>" id="form_<?= "{$name}_$i" ?>"
							type="text" class="form-control" placeholder="" autocomplete="off">
						<label for="<?= "{$name}_$i" ?>" id="form_<?= $name ?>_label_<?= $i ?>"><?= $label ?></label>
						<?php if (isset($autocompletion)) : ?>
							<script>addAutocomplete("form_<?= $name ?>_<?= $i; ?>", "<?= $autocompletion ?>");</script>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-auto">
					<div class="my-2">
						<button type="button" class="btn btn-danger" onclick="removeCopy('<?= $name; ?>', <?= $i; ?>);"><i class="bi bi-x"></i></button>
					</div>
				</div>

			</div>
		<?php $i++; endforeach; ?>
	</div>

	<div class="col-md-3">
		<div class="my-2">
			<button type="button" class="btn btn-primary me-md-2" onclick="addCopy('<?= $name; ?>');"><i class="bi bi-plus"></i></button>
		</div>
	</div>
</div>
