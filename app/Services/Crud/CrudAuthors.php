<?php

namespace BibliOlen\Services\Crud;

use Exception;

class CrudAuthors extends Crud
{
    private static ?CrudAuthors $instance = null;

    private function __construct($database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudAuthors
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }

    public function apiCreate($data): array
    {
        try {
            $sql = "INSERT INTO auteurs (nom_auteur, prenom_auteur) VALUES (:nom_auteur, :prenom_auteur)";

            $this->database->makeRequestNoReturn($sql, [
                'nom_auteur' => $data['lastname'],
                'prenom_auteur' => $data['firstname'] ??''
            ]);

            return array(
                'success' => true,
                'message' => 'Auteur ajouté avec succès',
                'status' => 201
            );
        } catch (Exception) {
            return array(
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'auteur. (vérifiez que l\'auteur n\'existe pas déjà)',
                'status' => 500
            );
        }
    }
}
