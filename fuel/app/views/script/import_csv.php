<div class="container">
	<h1>Importer des fichiers CSV</h1>
	<p>
		Pour importer un fichier excel, sauvegarder au format CXV les tableaux correspondants aux fichiers demandés.
	</p>

	<form enctype="multipart/form-data" action="/public/script/import_csv_result" method="POST">

		<div class="mb-3">
			<label for="file_operation" class="form-label">Opérations</label>
			<input class="form-control" type="file" name="file_operation" id="file_operation">
		</div>

		<div class="mb-3">
			<label for="file_operation" class="form-label">Groupe de sujets</label>
			<input class="form-control" type="file" name="file_sujet" id="form_file_sujet">
		</div>

		<div class="mb-3">
			<label for="file_diagnostics" class="form-label">Diagnostics rétrospectifs</label>
			<input class="form-control" type="file" name="file_diagnostics" id="form_file_diagnostics">
		</div>

		<div class="mb-3">
			<label for="file_atteinte" class="form-label">Atteinte invalidante</label>
			<input class="form-control" type="file" name="file_atteinte" id="form_file_atteinte">
		</div>

		<button class="btn btn-success" type="submit" name="submit">Envoyer</button>
	</form>
</div>

<pre>
<?php print_r($_FILES) ?>
</pre>