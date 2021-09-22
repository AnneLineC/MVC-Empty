# A faire lors de l'initialisation d'un projet en MVC

----------------------------------------------------

[https://github.com/O-clock-Wonderland/MVC-S05-step-by-step/blob/main/docs/Mise-en-place.md]

1. Lancer la commande `composer install` afin d'installer les bibliothèques indiquées dans le `composer.json`
2. Ajouter le dossier `vendor/` dans le `.gitignore`

/!\ L'ajout, la mise à jour ou la suppression de lignes dans le fichier `composer.json` nécessite de mettre à jour les bibliothèques installées dans le dossier `vendor/` grâce à la commande `composer update`
/!\ Si l'on modifie les données concernant l'autoload dans le `composer.json`, il faudra lancer la commande `composer dump-autoload` afin de prendre en compte ces modifications.

