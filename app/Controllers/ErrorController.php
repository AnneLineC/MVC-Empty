<?php

namespace App\Controllers;

class ErrorController extends CoreController
{
    /**
     * Méthode gérant l'affichage de la page 404
     *
     * @return void
     */
    public function err404() {
        // Envoie le header 404 en réponse à la requête
        header('HTTP/1.0 404 Not Found');

        $this->show('error/err404');
    }
}