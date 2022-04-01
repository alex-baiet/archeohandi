# Interfacer nakala (API v2) en PHP

Ce projet n'a d'autre but que de faciliter la prise en main de nakala en PHP pour exploiter nakala depuis un projet PHP

Pour l'utiliser, après l'avoir cloner, copier `config_sample.php` en `config.php` et y mettre la clef que vous a transmise l'assistance Huma-Num.
Puis copier une image dans le répertoire courant du projet et mettez le nom de l'image en regard de la variable $original_filename
exécuter en ligne de commande (réalisé sous GNU/Linux Ubuntu) : `php nakala-exec.php`, votre image doit alors être chargé dans nakala, des métadatas doivent être ajoutées. L'url permettant d'accéder à l'image est retournée.

Les contributions pour compléter, adapter à tel ou tel framework (symfony, laravel, …) sont les bienvenus
