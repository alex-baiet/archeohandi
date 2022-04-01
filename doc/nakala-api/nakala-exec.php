<?php
// on fait ici appel à exec pour exécuter le programme curl de GNU/linux qui doit être installé sur le serveur où s'exécute votre code PHP.
// Il serait sans doute plus opportun d'utiliser cUrl de PHP (qui devrait alors aussi être présent sur le serveur PHP) mais pour avoir essayé des conversions avec https://incarnate.github.io/curl-to-php, ça ne fonctionne pas… bienvenue aux experts de PHP cUrl !-)

// global variables for nakala server access
require_once 'config.php'; // define your config.php from config_sample.php model

/* fonction qui définit le type et la chaîne utile pour la requête postDatasUpload
cf. les différents type sur : https://facile.cines.fr et https://fr.wikipedia.org/wiki/Type_de_médias
 */
function setMediaType ($file_extension) {
	$extension = strtolower($file_extension);
	if ($extension == "jpg" || $extension == "jpeg")
		return "image/jpeg";
	elseif ($extension == "png")
		return "image/png";
	elseif ($extension == "svg") // image vectorielle
		return "image/svg+xml";
	elseif ($extension == "pdf") // document PDF
		return "application/pdf";
	elseif ($extension == "mp4")
		return "video/mp4"; // vidéo
	elseif ($extension == "ply" || $extension == "blend")
		return "model"; // modèle 3D
	else
		return "text";
}

function mycUrl ($cmd) {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	//TODO insérer les paramètres ci-dessus à la commande cUrl
	exec($cmd, $result);
	//var_dump($result);
	$decoded_result = json_decode($result[0], true);
	//var_dump($decoded_result);
	return $decoded_result;
}
	
// POST /datas/uploads ------------------------------- Dépôt de fichier
function postDatasUpload ($file_name, $file_extension) {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	$document_type = setMediaType($file_extension);
	$cmd = "curl -X POST '$server_url/datas/uploads' -H  'accept: application/json' -H '$option_key' -H  'Content-Type: multipart/form-data' -F 'file=@$file_name;type=$document_type'";
	exec($cmd, $result);
	//var_dump($result);
	$decoded_result = json_decode($result[0], true);
	//var_dump($decoded_result);
	return $decoded_result['sha1'];
}

// POST /datas/{identifier}/metadatas ---------------- Ajout d'une nouvelle métadonnée à une donnée
function postDataMetadatas ($sha1_file, string $description='') {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	$status = 'published'; // ou 'pending' mais attention avec pending, accès restreint !!
	$lang = 'fr';
	// type du document : consulter la page http://vocabularies.coar-repositories.org/pubby/resource_type.html pour obtenir la liste, quelques exemples dans le commentaire ci-dessous
	$document_type_url = 'http://purl.org/coar/resource_type/c_c513'; // document de type : c_c513 -> image, c_12ce -> video, c_18cc -> son, c_6501 -> article de journal,
	// titre
	$title = 'Paris* [75] (FR), coll. Peiresc, inv. BB';
	// Créateur et date
	$author_value = ''; // ou null
	$created_at = '1983';
	// licence
	$license_value = 'CC-BY-4.0';
	$embargo_date = "2020-06-01";

	$cmd = "curl -X POST '$server_url/datas' -H  'accept: application/json' -H  '$option_key' -H  'Content-Type: application/json' -d '{  \"status\": \"$status\",  \"metas\": [ { \"lang\": \"$lang\", \"typeUri\": \"http://www.w3.org/2001/XMLSchema#string\", \"propertyUri\": \"http://nakala.fr/terms#type\", \"value\": \"$document_type_url\" }, { \"lang\": \"$lang\", \"typeUri\": \"http://www.w3.org/2001/XMLSchema#string\", \"propertyUri\": \"http://nakala.fr/terms#title\", \"value\": \"$title\" }, { \"lang\": \"$lang\", \"typeUri\": \"http://www.w3.org/2001/XMLSchema#string\", \"propertyUri\": \"http://nakala.fr/terms#creator\", \"value\": \"$author_value\" }, { \"lang\": \"$lang\", \"typeUri\": \"http://www.w3.org/2001/XMLSchema#date\", \"propertyUri\": \"http://nakala.fr/terms#created\", \"value\": \"$created_at\" }, { \"lang\": \"$lang\", \"typeUri\": \"http://www.w3.org/2001/XMLSchema#string\", \"propertyUri\": \"http://nakala.fr/terms#license\", \"value\": \"$license_value\" }  ],  \"files\": [ { \"sha1\": \"$sha1_file\", \"description\": \"$description\", \"embargoed\": \"$embargo_date\" }  ]}";
	exec($cmd, $result);
	//var_dump($result);
	$decoded_result = json_decode($result[0], true);
	//var_dump($decoded_result);
	if (isset($decoded_result['payload'])) {
		$payload = $decoded_result['payload']['id'];
		return $payload;
	}
	return null;
}
// POST /datas/{identifier}/files -------------------- Ajout d'un fichier à une donnée
// permet d'associer plusieurs fichiers à un même enregistrement (donnée) nakala
function postDatasFiles ($identifier, $sha1_file, $description, $embargo_date) {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	$cmd = "curl -X POST '".$server_url."/datas/$identifier/files' -H  'accept: application/json' -H  '$option_key' -H  'Content-Type: application/json' -d '{  \"sha1\": \"$sha1_file\",  \"description\": \"$description\",  \"embargoed\": \"$embargo_date\"}'";
	exec($cmd, $result);
	//var_dump($result);
	$decoded_result = json_decode($result[0], true);
	return $decoded_result;
}

// GET /datas/{indentifier}/files -------------------- Récupération des métadonnées des fichiers associés à une donnée
function getDataFiles ($identifier) {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	$cmd = "curl -X GET '".$server_url."/datas/$identifier/files' -H  'accept: application/json' -H  '$option_key'";
	exec($cmd, $result);
	$decoded_result = json_decode($result[0], true);
	var_dump($decoded_result);
	return $decoded_result;
}

// GET /datas/{identifier}/metadatas ----------------- Récupération de la liste des métadonnées d'une donnée
function getDataMetadatas ($identifier) {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	$cmd = "curl -X GET '".$server_url."/datas/$identifier' -H  'accept: application/json' -H  '$option_key'";
	exec($cmd, $result);
	//var_dump($result);
	$decoded_result = json_decode($result[0], true);
	//var_dump($decoded_result);
	$document_metadatas = [];
	$document_metadatas['file_name'] = $decoded_result['files'][0]['name'];
	$document_metadatas['file_extension'] = $decoded_result['files'][0]['extension'];
	$document_metadatas['description'] = $decoded_result['files'][0]['description'];
	//echo "$file_name, de type $file_extension qui a pour description $description";
	return $document_metadatas;
}

// POST /collections --------------------------------- Création d'une nouvelle collection
function postCollections ($collection_name) {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	$cmd = "curl -X POST '".$server_url."/collections' -H 'accept: application/json' -H '$option_key' -H 'Content-Type: application/json' -d '{ \"status\": \"public\",  \"metas\": [    {      \"value\": \"$collection_name\",      \"lang\": \"fr\",      \"typeUri\": \"http://www.w3.org/2001/XMLSchema#string\",      \"propertyUri\": \"http://nakala.fr/terms#title\" } ]}'";
	exec($cmd, $result);
	var_dump($result);
	$decoded_result = json_decode($result[0], true);
	if (isset($decoded_result['payload'])) {
		$payload = $decoded_result['payload']['id'];
		return $payload;
	}
	return null;
}

// GET /collections ceramo:
function getCollections ($identifier) {
	$server_url = $GLOBALS['server_url'];
	$option_key = $GLOBALS['option_key'];
	$cmd = "curl -X GET '".$server_url."/collections/$identifier' -H  'accept: application/json' -H  '$option_key'";
	exec($cmd, $result);
	$decoded_result = json_decode($result[0], true);
	return($decoded_result['metas'][0]);
}

/* test postCollections
$collection_name = "ma-collection1";
$payload = postCollections($collection_name);
print"payload pour $collection_name : $payload\n";
exit;
 */

// Upload file
$original_filename = "loeil-du-cyclone.jpg"; // nom du fichier (image jpg/png, document SVG, document PDF) à déposer dans nakala
$file_extension = 'jpg';
$sha1 = postDatasUpload($original_filename, $file_extension);

// Define metadatas for previous uploaded file
$payload = postDataMetadatas($sha1, 'le mauvais œil'); // on définit ici la description
$identifier = $payload.'/'.$sha1;

// Upload second file
$original_filename = "nakala.png"; // nom du fichier (image jpg/png, document SVG, document PDF) à déposer dans nakala
$file_extension = 'png';
$sha1 = postDatasUpload($original_filename, $file_extension);
$description = "test png";
$embargo_date = "2020-01-02";
$dr = postDatasFiles($identifier, $sha1, $description, $embargo_date);
var_dump($dr);

$metadatas = getDataFiles($identifier);
//$metadatas = getDataMetadatas($payload);
//var_dump($metadatas);
//print"si le fichier est une image (matriciel), il est accessible via l'api IIIF à l'url : $server_url/iiif/$identifier/full/full/0/default.jpg\n et dans tous les cas (dont SVG, PDF) à $serveur_url/data/$indentifier\n";
?>
