<?php

namespace BibliOlen\Services\Crud;

use BibliOlen\Tools\Database;
use BibliOlen\Tools\Tools;
use Exception;
use InvalidArgumentException;


class CrudLoan extends Crud
{
    private static ?CrudLoan $instance = null;

    private string $tablename = 'utilisateur_livres_emprunter';

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudLoan
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }


    public function create($data): bool
    {
        if (!isset($data['id_book'], $data['id_user'], $data['loan_date'], $data['loan_deadline'])) {
            throw new InvalidArgumentException("Missing required data for creating a loan.");
        }

        $query = "INSERT INTO $this->tablename (id_livre, id_utilisateur, date_emprunt, date_retour_reel, quantite, date_retour_limite) VALUES (:ib, :iu, :ld, :rld, :q, :ldl)";
        $this->database->makeRequestNoReturn($query, [
            "ib" => $data['id_book'],
            "iu" => $data['id_user'],
            "ld" => $data['loan_date'],
            "rld" => $data['real_loan_date'] ?? null,
            "q" => $data['quantity'],
            "ldl" => $data['loan_deadline'],
        ]);
        return true;
    }

    public function cancelLoan($data): array
    {
        try {
            $query = "UPDATE $this->tablename SET date_retour_reel= :d ,validated = :v WHERE id_livre= :ib AND id_utilisateur= :iu";

            $this->database->makeRequestNoReturn($query, [
                "ib" => $data['id_book'],
                "iu" => $data['id_user'],
                "v" => 'false',
                "d" => date("Y-m-d"),
            ]);

            return array(
                'status' => 200,
                'success' => true,
                'message' => 'Vous avez bien annulé la réservation',
            );
        } catch (Exception) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de la réservation',
            );
        }
    }

    public function updateDateRetour($data): array
    {
        if($data['loanReturn'] > date("Y-m-d")){
            $data['loanReturn'] = date("Y-m-d");
        }
        try {
            $query = "UPDATE $this->tablename SET date_retour_reel = :rld , date_emprunt = :dt WHERE id_livre = :ib AND id_utilisateur = :iu";

            $this->database->makeRequestNoReturn($query, [
                "rld" => date("Y-m-d"),
                "ib" => $data['id_book'],
                "iu" => $data['id_user'],
                "dt" => $data['loanReturn'],
            ]);
            return array(
                'status' => 200,
                'success' => true,
                'message' => 'Vous avez bien confirmé le retour du livre',
            );
        } catch (Exception) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => 'Erreur lors du retour du livre',
            );
        }
    }

    public function updateDateEmprunt($data): array
    {
        try {
            $query = "UPDATE $this->tablename SET validated = :v WHERE id_livre = :ib AND id_utilisateur = :iu";
            $this->database->makeRequestNoReturn($query, [
                "ib" => $data['id_book'],
                "iu" => $data['id_user'],
                "v" => true
            ]);
            return array(
                'status' => 200,
                'success' => true,
                'message' => 'Vous avez bien confirmé l\'emprunt du livre',
            );
        } catch (Exception) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => 'Erreur lors de l\'emprunt du livre',
            );
        }

    }

    public function annulLoan($data): bool
    {

        if($data['loanReturn'] > date("Y-m-d")){
            $data['loanReturn'] = date("Y-m-d");
        }

        $query = "UPDATE $this->tablename SET date_retour_reel = :cd, validated = :v , date_emprunt=:ld WHERE id_livre= :ib AND id_utilisateur= :iu";

        $this->database->makeRequestNoReturn($query, [
            "ib" => $data['book_id'],
            "iu" => $data['user_id'],
            "cd" => date("Y-m-d"),
            "v" => 'false',
            "ld" => $data['loanReturn'],
        ]);

        return true;
    }
}
