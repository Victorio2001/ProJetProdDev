<?php

namespace BibliOlen\Services\Crud;


use BibliOlen\Models\UserModel;
use Exception;

class CrudUser extends Crud
{
    private static ?CrudUser $instance = null;

    private function __construct($database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudUser
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }

    public function create($data): UserModel
    {
        $sql = "INSERT INTO utilisateurs (nom_utilisateur, prenom_utilisateur, email_utilisateur, id_role, lecture_seule, id_promotion, mdp) VALUES (:nom_utilisateur, :prenom_utilisateur, :email_utilisateur, :id_role, :lecture_seule, :id_promotion, :mdp) RETURNING *";
        $result = $this->database->makeRequest($sql, [
            "nom_utilisateur" => $data['nom_utilisateur'],
            "prenom_utilisateur" => $data['prenom_utilisateur'],
            "email_utilisateur" => strtolower($data['email_utilisateur']),
            "id_role" => $data['id_role'],
            "id_promotion" => $data['id_promotion'],
            "lecture_seule" => $data['lecture_seule'],
            "mdp" => $data['mdp']
        ]);

        foreach ($data['module'] as $module_id) {
            $sql = "INSERT INTO utilisateurs_modules (user_id, module_id) VALUES (:user_id, :module_id)";
            $this->database->makeRequest($sql, [
                "user_id" => $result[0]["id_utilisateur"],
                "module_id" => $module_id
            ]);
        }

        return new UserModel(
            id: $result[0]['id_utilisateur'],
            firstName: $result[0]['nom_utilisateur'],
            lastName: $result[0]['prenom_utilisateur'],
            email: $result[0]['email_utilisateur'],
            password: $result[0]['mdp'],
            readOnly: $result[0]['lecture_seule'],
            role: $result[0]['id_role'],
            promotion: $result[0]['id_promotion']
        );
    }

    public function apiModif($data): array
    {
        try {
            $sql = "UPDATE utilisateurs SET nom_utilisateur = :user_name , prenom_utilisateur = :user_firstname , email_utilisateur = :email WHERE id_utilisateur = :id_user";

            $this->database->makeRequestNoReturn($sql, [
                "user_name" => $data["name"],
                "user_firstname" => $data["firstname"],
                "email" => $data["email"],
                "id_user" => $data["id_user"]
            ]);

            return array(
                'status' => 200,
                'success' => true,
                'message' => 'Modification avec les utilisateurs',
            );
        } catch (Exception) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => 'Erreur lors de la mise a jour d\'utilisateur',
            );
        }
    }

    public function apiArchivUser($data): array
    {
        try {
            $sql = "UPDATE utilisateurs SET lecture_seule = true WHERE id_utilisateur = :id_user";

            $this->database->makeRequestNoReturn($sql, [
                "id_user" => $data["id_user"]
            ]);

            return array(
                'status' => 200,
                'success' => true,
                'message' => 'Utilisateurs Archivées',
            );
        } catch (Exception) {
            return array(
                'status' => 500,
                'success' => false,
                'message' => 'Erreur lors de l\'archivage de l\'utilisateur',
            );
        }
    }

    public function editPassword($data): void
    {
        $sql = "UPDATE utilisateurs SET mdp= :mdp WHERE id_utilisateur = :id_utilisateur";
        $this->database->makeRequestNoReturn($sql, [
            "mdp" => $data["mdp"],
            "id_utilisateur" => $data["id_utilisateur"]
        ]);
    }

}
