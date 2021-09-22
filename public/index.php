<?php

// POINT D'ENTRÉE UNIQUE : 
// FrontController

/* ----------------
------ DEBUG ------
-----------------*/

// Affichage de toutes les erreurs
// /!\ 💀 environnement DEV uniquement, à ne pas utiliser en PROD
@ini_set('display_errors', 1); // affiche les erreurs à l'écran
@ini_set('display_startup_errors', 1); // affiche les erreurs de démarrage PHP
@error_reporting(E_ALL); // affiche tous les types d'erreurs


/* ----------------
---- INCLUSION ----
-----------------*/

// Inclusion des bibliothèques gérées par composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)
require __DIR__ . '/../vendor/autoload.php';


/* ----------------
----- SESSION -----
-----------------*/

// Création d'une session (ou restauration celle trouvée sur le serveur), grâce à la méthode session_start()
// => On pourra ainsi se servir de la variable $_SESSION dans tous les controllers
session_start();


/* ----------------
----- ROUTAGE -----
-----------------*/

// Instanciation d'AltoRouter
$router = new AltoRouter();

// On déclare à Altorouter que notre site est placé dans des sous-répertoires
// Rappel : la variable $_SERVER['BASE_URI'] est créée via le .htaccess
$router->setBasePath($_SERVER['BASE_URI']);


/* HOMEPAGE */
// ----------

$router->map(
    'GET', // Méthode HTTP
    '/',   // URL de la route
    [
        'controller' => 'MainController', // Nom du controller contenant la méthode
        'action' => 'home' // Méthode à utiliser pour répondre à cette route
    ],
    'main-home' // Nom unique de la route, convention "NomDuController-NomDeLaMéthode"
);

// Autre possibilité pour créer la même route :
// $routes = [
//     [
//         'GET',
//         '/',
//         [
//             'controller' => 'MainController',
//             'action' => 'home'
//         ],
//         'home'
//     ],
// ]
// $router->addRoutes($routes);


/* -----------------
---- DISPATCHER ----
------------------*/

// 4. On utilise le dispatcher pour appeller un controller et sa méthode en fonction du "match" de la route.
$match = $router->match();

// $match renvoie false si la route n'existe pas
// Sinon elle renvoie un tableau avec les données de la route

// Si la route existe 
if ($match !== false) {
    // On récupère les données de la route :
    // On stocke dans la variable $controllerToUse le nom du contrôleur à appeler (/!\ sans oublier le namespace)
    $controllerToUse = 'App\\Controllers\\' . $match['target']['controller'];
    // On stocke dans la variable $methodToUse la méthode du contrôleur à appeler
    $methodToUse = $match['target']['action'];
}
// Si la route n'existe pas
else {
    // On lève une erreur 404
    http_response_code(404);
    // On redirige vers la méthode gérant l'affichage de la page 404
    $controllerToUse = 'App\\Controllers\\ErrorController';
    $methodToUse = 'err404';
    $match['params'] = []; // Evite d'avoir une erreur lors de l'exécution de la méthode, car $match['params'] n'existe pas si $match vaut false
}

// On instancie le controller et exécute la méthode
$controller = new $controllerToUse();
$controller->$methodToUse($match['params']);


// Alternative : utiliser l'AltoDispatcher de benoclock : 
// https://github.com/benoclock/AltoDispatcher
// https://packagist.org/packages/benoclock/alto-dispatcher
