<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Exemple extends CoreModel
{
    // ***********************************
    // C[R]UD
    // ***********************************

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
        // Connection à la BDD
        $pdo = Database::getPDO();

        // Requête SQL
        $sql = 'SELECT * FROM `exemple`';

        // Préparation de la requête
        $pdoStatement = $pdo->prepare($sql);

        // Exécution de la requête
        $pdoStatement->execute();

        // Récupération des objets exemples issus de la requête
        $exemples = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $exemples;
    }

    // ***********************************
    // [C]RUD
    // ***********************************

    /**
     * Méthode permettant d'insérer une nouvelle entrée dans la BDD
     *
     * @return bool
     */
    public function insert()
    {
        $pdo = Database::getPDO();

        $sql = "
            INSERT INTO `exemple` (champ1, champ2, champ3)
            VALUES (:champ1, :champ2, :champ3)
        ";

        $preparation = $pdo->prepare($sql);

        //* Méthode 1 :
        $preparation->bindValue(':champ1', $this->champ1, PDO::PARAM_STR);
        $preparation->bindValue(':champ2', $this->champ2, PDO::PARAM_STR);
        $preparation->bindValue(':champ3', $this->champ3, PDO::PARAM_STR);
        $isInserted = $preparation->execute();

        //* Méthode 2 :
        // $isInserted = $preparation->execute([
        //     ":champ1" => $this->name,
        //     ":champ2" => $this->subtitle,
        //     ":champ3" => $this->picture
        // ]);

        // $isInserted contient le bootélen true si l'insertion a eue lieu, false sinon
        if ($isInserted) {
            // on donne à notre Model l'ID auto-incrémentée générée par SQL
            // https://www.php.net/manual/fr/pdo.lastinsertid.php
            $this->id = $pdo->lastInsertId();
        }

        return $isInserted;
    }


    // ***********************************
    // CR[U]D
    // ***********************************

    /**
     * Méthode permettant de mettre à jour un enregistrement
     * 
     * @return bool
     */
    public function update()
    {
        // Connexion à la BDD
        $pdo = Database::getPDO();

        // Écriture de la requête avec des paramètres nommés
        $sql = "
            UPDATE `exemple`
            SET
                champ1 = :champ1,
                champ2 = :champ2,
                champ3 = :champ3,
                updated_at = NOW()
            WHERE id = :id
        ";

        // Préparation de la requête
        $preparation = $pdo->prepare($sql);
        $preparation->bindValue(':id', $this->id, PDO::PARAM_INT);
        $preparation->bindValue(':champ1', $this->champ1, PDO::PARAM_STR);
        $preparation->bindValue(':champ2', $this->champ2, PDO::PARAM_STR);
        $preparation->bindValue(':champ3', $this->champ3, PDO::PARAM_STR);

        // Execution de la requête préparée
        $result = $preparation->execute();
        // $result renvoie un booléen true si la mise a jour s'est déroulée dans la BDD, false sinon

        return $result;

    }

    /**
     * Méthode permettant de mettre 5 exemples à la une
     *
     * @param array $emplacements
     * @return bool
     */
    static public function defineHomepage(array $emplacements) {

        // Connexion à la BDD
        $pdo = Database::getPDO();

        // Notre variable $sql contient 6 requêtes de type UPDATE
        // On peut faire plusieurs requêtes en une seule fois en les séparant par des point-virgules «;»
        // Et - contrairement à toutes les requêtes codées jusqu'à maintenant en S06 - nous n'utiliserons pas de paramètres nommés
        // mais une autre possibilité de faire des requêtes préparées : des marqueurs (identifiés par des points d'interrogation `?`)
        // https://www.php.net/manual/fr/pdo.prepare.php
        $sql = '
            UPDATE `category` set home_order = 0;
            UPDATE `category` set home_order = 1 WHERE `id` = ?;
            UPDATE `category` set home_order = 2 WHERE `id` = ?;
            UPDATE `category` set home_order = 3 WHERE `id` = ?;
            UPDATE `category` set home_order = 4 WHERE `id` = ?;
            UPDATE `category` set home_order = 5 WHERE `id` = ?;
        ';
        // La première requête met les champs home_order de chaque catégorie à 0
        // Puis, chaque catégorie concernée recevra son positionnement home_order

        // pPréparation de la requête
        $pdoStatement = $pdo->prepare($sql);

        // Lorsqu'on utilise des marqueurs (?) dans la requête,
        // on passe à execute() un tableau indexé avec la liste des valeurs - exemple [2,4,6,5,9]
        // $emplacements est justement un tableau d'id qui respecte ce format
        // c'est donc lui qui sera passé à execute()
        return $pdoStatement->execute($emplacements);
        // on retourne le résultat de execute()
        // pour savoir si l'opération s'est bien déroulée (TRUE) ou pas (FALSE)
    }

    // ***********************************
    // CRU[D]
    // ***********************************

    /**
     * Méthode permettant de supprimer un enregistrement
     * 
     * @return bool
     */
    public function delete()
    {
        // Connexion à la BDD
        $pdo = Database::getPDO();

        // Écriture de la requête préparée
        $sql = "
            DELETE FROM `exemple`
            WHERE id = :id
        ";

        // Préparation de la requête
        $preparation = $pdo->prepare($sql);
        $preparation->bindValue(':id', $this->id, PDO::PARAM_INT);

        // Execution de la requête préparée
        return $preparation->execute();
        // on retourne le résultat de execute()
        // pour savoir si l'opération s'est bien déroulée (TRUE) ou pas (FALSE)
    }


}