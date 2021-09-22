<?php

namespace App\Controllers;

class CoreController {

    public function __construct()
    {
        
    }

    /**
     * Méthode permettant d'afficher les views
     *
     * @param int $viewName, paramètre obligatoire, indique le nom de la view (page) attendue
     * @param array $viewData, paramètre optionnel, permet de transmettre des données à la view
     * @return void
     */
    public function show($viewName, $viewData = []) {
        require_once __DIR__ . '/../Views/layout/header.tpl.php';
        require_once __DIR__ . '/../Views/' . $viewName . '.tpl.php';
        require_once __DIR__ . '/../Views/layout/footer.tpl.php';
    }

}