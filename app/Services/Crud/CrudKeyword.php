<?php

namespace BibliOlen\Services\Crud;


use Exception;

class CrudKeyword extends Crud
{
    private static ?CrudKeyword $instance = null;

    private function __construct($database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudKeyword
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }

    public function apiCreate($data): array
    {
        try {
            $sql = "INSERT INTO mots_cles (mot_cle) VALUES (:mot_cle)";

            $this->database->makeRequestNoReturn($sql, [
                'mot_cle' => $data['keyword']
            ]);

            return array(
                'success' => true,
                'message' => 'Mot clé ajouté avec succès',
                'status' => 201
            );
        } catch (Exception) {
            return array(
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du mot clé (vérifiez que le mot clé n\'existe pas déjà).',
                'status' => 500
            );
        }
    }
}
