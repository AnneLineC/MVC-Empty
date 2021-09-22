<?php

namespace App\Models;

abstract class CoreModel {
    // 1. Propriétés partagées entre tous les models, en protected (accessibles seulement depuis les classes enfants de CoreModel)
    // protected $propriété1
    // protected $propriété2

    // 2. Leurs getters et setters

    // 3. On peut également définir ici des méthodes abstraites :
    // elles ne peuvent pas être définies dans le CoreModel,
    // mais elles devront obligatoirement l'être dans ses classes enfant
    // en respéctant strictement leur structure définie ici (static ou non, nom, paramètres...)
    // Exemple : 
    // abstract static public function find($id);
    // abstract static public function findAll();
    // abstract public function insert();
    // abstract public function update();
    // abstract public function delete();
}