<?php

namespace App\Controllers;

class MainController extends CoreController {

    /**
     * Route : "home"
     * URL : "/"
     *
     * @param [] $params
     * @return void
     */
    public function home() {
        $this->show('main/home');
    }

}