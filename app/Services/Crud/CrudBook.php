<?php

namespace BibliOlen\Services\Crud;

use BibliOlen\Tools\Database;
use BibliOlen\Tools\Tools;
use Exception;

class CrudBook extends Crud
{
    private static ?CrudBook $instance = null;
    protected Database $database;

    private function __construct($database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudBook
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }

    public function create($data): array
    {
        try {
            $sql = "INSERT INTO livres (titre_livre, resume_livre, isbn, annee_publication, image_couverture, nombre_exemplaires, id_editeur) VALUES (:t, :r, :i, :ap, :ic, :ne, :ide) RETURNING id_livre";

            $result = $this->database->makeRequest($sql, [
                "t" => $data['titre_livre'],
                "r" => $data['resume_livre'],
                "i" => $data['isbn'],
                "ap" => $data['annee_publication'],
                "ic" => $data['image_couverture'],
                "ne" => $data['nombre_exemplaires'],
                "ide" => trim(explode("-", $data['id_editeur'])[0])
            ]);


            foreach ($data['id_auteur'] as $id_auteurs) {
                $id_auteur = trim(explode("-", $id_auteurs)[0]);
                $sql = "INSERT INTO livre_auteurs (id_livre, id_auteur) VALUES (:il, :ia)";
                $this->database->makeRequest($sql, [
                    "il" => $result[0]["id_livre"],
                    "ia" => $id_auteur
                ]);
            }

            foreach ($data['id_mot_cle'] as $id_mot_cles) {
                $id_mot_cle = trim(explode("-", $id_mot_cles)[0]);
                $sql = "INSERT INTO livre_mots_cles (id_livre, id_mot_cle) VALUES (:il, :imc)";
                $this->database->makeRequest($sql, [
                    "il" => $result[0]["id_livre"],
                    "imc" => $id_mot_cle
                ]);
            }
        }
        catch (Exception $e) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => "Un problème est survenue lors de l'ajout du livre",
                'error' => $e->getMessage()
            );
        }
        return $result;
    }

    public function apiModif(array $data): array
    {
        try {
            $sql = "UPDATE livres SET titre_livre = :n, resume_livre = :r, annee_publication = :a, nombre_exemplaires = :nb WHERE id_livre = :ib";

            $this->database->makeRequestNoReturn($sql, [
                "n" => $data['name'],
                "r" => $data['resume'],
                "a" => $data['date'],
                "nb" => (int)$data['nombre_exemplaires'],
                "ib" => $data['id_book'],
            ]);
            $crudDeal = CrudDeal::getInstance(database: $this->database);
            $crudDeal->modifRegist($data);

            return array(
                'status' => 200,
                'success' => true,
                'message' => 'Le livres à été mis a jour avec succès',
            );
        } catch (Exception) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => 'Erreur lors de la mise a jour du livre',
            );
        }

    }

    public function apiDelete(array $data): array
    {
        try {
            $sql = "UPDATE livres SET archived = true WHERE id_livre = :ib";

            $this->database->makeRequest($sql, [
                "ib" => $data['id_book']
            ]);

            return array(
                'status' => 200,
                'success' => true,
                'message' => 'Le livre à été supprimé avec succès',
            );
        } catch (Exception) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => 'Erreur lors de la suppression du livre',
            );
        }
    }
}
