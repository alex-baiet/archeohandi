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
/** @var array|unset Array au format {"select": select, "table": table, "where": conditions}. Voir description addAutocomplete dans form.js */
if (isset($autocompletion)) $autocompletion = $autocompletion;
/** @var bool */
$imageInput = isset($imageInput) ? $imageInput : false;
/** @var array */
$inputAttributes = isset($inputAttributes) ? $inputAttributes : array();

if (empty($datas)) $datas[] = "";

if (isset($autocompletion)) {
	// Création de l'array en js équivalent
	$completionParams = "{
		'select': `{$autocompletion['select']}`,
		'table': `{$autocompletion['table']}`,
		'where': [";
	$wheres = array();
	foreach ($autocompletion["where"] as $w) {
		$wheres[] = "[`{$w[0]}`, `{$w[1]}`, `{$w[2]}`, `{$w[3]}`]";
	}
	$completionParams .= implode(", ", $wheres)."]}";
}

?>

<script>
	function zoomPreview(elem) {
		console.log(elem.src);

		let bigPreview = document.getElementById("<?= $name ?>_big_preview");
		bigPreview.src = elem.src;
		bigPreview.style.display = "block";
	}
</script>

<?php if ($imageInput) : ?>
	<div style="position: relative;">
		<img
			id="<?= $name ?>_big_preview"
			src=""
			alt="Image indisponible"
			style="display: none; height: 400px; width: 100%; object-fit: contain;"
			onclick="this.style.display='none'">
	</div>
<?php endif; ?>

<div class="row my-2">
	<div class="col-md-9" id="form_<?= $name; ?>_parent">
		<?php $i=0; foreach ($datas as $item) : ?>
			<div class="row" id="form_<?= $name; ?>_copy_<?= $i; ?>">

				<div class="col-md">
					<div class="form-floating">
						<input
							name="<?= $name ?>[]"
							value="<?= $item ?>"
							id="form_<?= "{$name}_$i" ?>"
							type="text"
							class="form-control"
							placeholder="<?= $label ?>"
							<?php if (isset($autocompletion)) : ?>autocomplete="off"<?php endif; ?>
							<?php if ($imageInput) : ?>
								onkeyup="changeImgSrc(`form_<?= $name ?>_<?= $i ?>`, `<?= $name ?>_preview_<?= $i; ?>`, this.value);"
							<?php endif; ?>
							<?php foreach ($inputAttributes as $key => $value) echo " $key='$value'"; ?>
							>
						<label for="<?= "form_{$name}_$i" ?>" id="form_<?= $name ?>_label_<?= $i ?>"><?= $label ?></label>
						<?php if (isset($autocompletion)) : ?>
							<script>
								function prepareField() {
									const autocomplete = <?= $completionParams ?>;
									addAutocomplete("form_<?= $name ?>_<?= $i; ?>", autocomplete["select"], autocomplete["table"], autocomplete["where"]);
								}
								prepareField();
							</script>
						<?php endif; ?>
					</div>
				</div>

				<?php if ($imageInput) : ?>
					<div class="col-auto" style="padding:0; background-color: white;">
						<img
							id="<?= $name ?>_preview_<?= $i ?>"
							src="<?= $item ?>" alt="Image indisponible"
							style="height: 58px; cursor: pointer; <?= empty($item) ? 'display: none;' : '' ?>"
							onclick="zoomPreview(this);">
					</div>
				<?php endif; ?>

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
			<button type="button" class="btn btn-primary me-md-2"
				<?php if ($imageInput) : ?>
					onclick="addCopyImg('<?= $name; ?>');"
				<?php else : ?>
					onclick="addCopy(`<?= $name; ?>`, <?= isset($autocompletion) ? $completionParams : `null` ?>);"
				<?php endif; ?>
			>
				<i class="bi bi-plus"></i>
			</button>
		</div>
	</div>
</div>
