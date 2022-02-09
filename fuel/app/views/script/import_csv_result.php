<h2>Fichiers envoyés</h2>

<pre>
<?php print_r($_FILES) ?>
</pre>

<h2>Contenu des fichiers</h2>
<pre>
<?php
foreach ($_FILES as $file) {
	if ($file["error"] === 0) echo file_get_contents($file['tmp_name']);
}
?>
</pre>

