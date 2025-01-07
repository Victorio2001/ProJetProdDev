<?php

namespace BibliOlen\Services\Crud;

use Exception;

class CrudPublisher extends Crud
{
    private static ?CrudPublisher $instance = null;

    private function __construct($database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudPublisher
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }

    public function apiCreate($data): array
    {
        try {
            $sql = "INSERT INTO editeurs (nom_editeur) VALUES (:nom_editeur) RETURNING id_editeur";

            $this->database->makeRequestNoReturn($sql, ["nom_editeur" => $data['publisher']]);
            return array(
                'success' => true,
                'message' => 'Auteur ajouté avec succès',
                'status' => 201
            );
        } catch (Exception) {
            return array(
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'auteur (vérfier que l\'auteur n\'existe pas déjà)',
                'status' => 500
            );
        }

    }

}
