<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Exemple extends CoreModel
{
    /**
     * Méthode permettant de trouver un exemple en fonction de son id
     *
     * @param int $id
     * @return Exemple
     */
    static public function find($id)
    {
        // Connection à la BDD
        $pdo = Database::getPDO();

        // Requête SQL avec un paramètre nommé (:id)
        $sql = "
            SELECT *
            FROM `exemple`
            WHERE id = :id
            ";
        
        // Préparation de la requête : les paramètres nommés sont "découpés" en champs, évitant la faille SQLI
        $pdoStatement = $pdo->prepate($sql);

        //* Méthode 1 :
        // bindValue permet d'associer une valeur à un paramètre nommé dans la requête SQL qui a été préparée
        $pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        // Exécution de la requête :
        $pdoStatement->execute();

        //* Méthode 2 :
        // On ne passe pas par un bindValue, mais on passe directement un tableau associatif en paramètre de execute()
        // $pdoStatement->execute([':id' => $id]);

        // Récupération de l'objet issu de la requête
        $exemple = $pdoStatement->fetchObject(self::class);

        return $exemple;

    }

    /**
     * Méthode permettant de trouver tous les exemples
     *
     * @return Exemple[]
     */
    static public function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `exemple`';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute();
        $exemples = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        return $exemples;
    }


    public function insert()
    {
        $pdo = Database::getPDO();

        $sql = "
            INSERT INTO `exemple` (champ1, champ2, champ3)
            VALUES (:champ1, :champ2, :champ3)
        ";

        $preparation = $pdo->prepare($sql);

        $isInserted = $preparation->execute([
            ":name" => $this->name,
            ":subtitle" => $this->subtitle,
            ":picture" => $this->picture
        ]);

        // $isInserted contient le bootélen true si l'insertion a eue lieu, false sinon
        if ($isInserted) {

            // on donne à notre Model l'ID auto-incrémentée générée par SQL
            // https://www.php.net/manual/fr/pdo.lastinsertid.php
            $this->id = $pdo->lastInsertId();
        }

        return $isInserted;
    }
}