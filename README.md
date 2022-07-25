# Archeohandi

Ce projet est construit avec le framework **FuelPHP v1.8.2**.

Pour vous aider à comprendre la structure du site et de la base de données, il est conseillé d'aller voir les différents schémas dans `doc/schemas`. Les documents en `.asi` sont des fichiers de **AnalyseSI** et sont des schémas de la base de données. Le fichier `navigation.uxf` est un fichier **Umlet** et est un schéma de la navigation du site.

## Structure

La structure suit le modèle MVC, comme voulu par FuelPHP. Seul la partie *Presenter* n'est pas
`fuel/app/classes/model/db` Contient tous les modèles représentant les tables de la bdd, ainsi que d'autres classes permettant d'exploiter la bdd plus facilement.

La plupart des informations nécessaires à la compréhension du code sont dans les commentaires du code 

- Alex BAIET

## FuelPHP

* Version: 1.8.2
* [Website](https://fuelphp.com/)
* [Release Documentation](https://fuelphp.com/docs)
* [Release API browser](https://fuelphp.com/api)
* [Development branch Documentation](https://fuelphp.com/dev-docs)
* [Development branch API browser](https://fuelphp.com/dev-api)
* [Support Forum](https://forums.fuelphp.com) for comments, discussion and community support
