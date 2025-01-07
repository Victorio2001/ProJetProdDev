<?php

namespace BibliOlen\Services\Crud;



use InvalidArgumentException;

class CrudDeal extends Crud
{
    private static ?CrudDeal $instance = null;

    private function __construct($database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudDeal
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }
    public function dealRegist($data,$bookadd): bool
    {
        if (!isset($bookadd)) {
            throw new InvalidArgumentException("Missing required data for creating a loan.");
        }
        $query = "INSERT INTO transactions (date_transaction, nb_ex_ajoute, nb_ex_retire, id_utilisateur, id_livre) VALUES (:transactions, :nb_ex_ajoute, :nb_ex_retire, :id_utilisateur, :id_livre)";
        $this->database->makeRequestNoReturn($query, [
            "transactions" => date("Y-m-d"),
            "nb_ex_ajoute" => $data['nombre_exemplaires'] ?? 0,
            "nb_ex_retire"=> $data['nombre_delete'] ?? 0,
            "id_utilisateur" => $_SESSION['user']->id,
            "id_livre" => $bookadd[0]['id_livre'],
        ]);
        return true;
    }

    public function modifRegist($data): bool
    {
        $query = "INSERT INTO transactions (date_transaction, nb_ex_ajoute, nb_ex_retire, id_utilisateur, id_livre) VALUES (:transactions, :nb_ex_ajoute, :nb_ex_retire, :id_utilisateur, :id_livre)";
        $this->database->makeRequestNoReturn($query, [
            "transactions" => date("Y-m-d"),
            "nb_ex_ajoute" => $data['add'] ?? 0,
            "nb_ex_retire"=> $data['supp'] ?? 0,
            "id_utilisateur" => $_SESSION['user']->id,
            "id_livre" => $data['id_book'],
        ]);
        return true;
    }
}
