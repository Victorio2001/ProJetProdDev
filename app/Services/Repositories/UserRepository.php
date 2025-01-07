<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\UserModel;
use BibliOlen\Tools\Database;

class UserRepository
{
    private static ?UserRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'utilisateurs';
    private array $users;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->users = [];
    }

    public static function getInstance(Database $database): UserRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new UserRepository($database);
            self::$_instance->loadUser();
        }

        return self::$_instance;
    }

    private function loadUser(): void
    {
        $users = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");
        $roleRepo = RoleRepository::getInstance(database: $this->_bdd);
        $promotionRepo = PromotionRepository::getInstance(database: $this->_bdd);

        foreach ($users as $user) {
            $this->users[] = new UserModel(
                id: $user['id_utilisateur'],
                firstName: $user['nom_utilisateur'],
                lastName: $user['prenom_utilisateur'],
                email: $user['email_utilisateur'],
                password: $user['mdp'],
                readOnly: $user['lecture_seule'],
                role: $roleRepo->findById($user['id_role']),
                promotion: $promotionRepo->findById($user['id_promotion'])
            );
        }

    }

    public function findByFilter(string $filter, int $page, int $userPerPage): array
    {
        $allUsers = array();
        $filter = strtolower(trim($filter));
        if($filter == ''){
            $allUsers = $this->users;
            $total = count($allUsers);

            $offset = ($page - 1) * $userPerPage;

            $usersToReturn = array_splice($allUsers, $offset, $userPerPage);

            return array(
                'users' => $usersToReturn,
                'total' => $total,
            );
        }

        foreach ($this->users as $user) {
            $userInfo = strtolower(trim($user->firstName . ' ' . $user->lastName));
            $userPromo = strtolower(trim($user->promotion->name));
            $userRole = strtolower(trim($user->role->name));

            if (str_contains($userInfo, $filter))
                $allUsers[] = $user;

            if (str_contains($userRole, $filter))
                $allUsers[] = $user;

            if (str_contains($userPromo, $filter))
                $allUsers[] = $user;
        }

        $total = count($allUsers);

        $offset = ($page - 1) * $userPerPage;

        $usersToReturn = array_splice($allUsers, $offset, $userPerPage);

        return array(
            'users' => $usersToReturn,
            'total' => $total,
        );
    }

    public function findWithPagination(int $page, int $perPage, string $orderField = null, string $order = 'ASC'): array
    {
        $users = $this->users;
        $total = count($users);
        $offset = ($page - 1) * $perPage;

        $usersToReturn = array_splice($users, $offset, $perPage);
        if ($orderField)
            usort($usersToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });

        return array(
            'users' => $usersToReturn,
            'total' => $total
        );
    }
    
    public function getByEmail($email): UserModel|null
    {
        foreach ($this->users as $user) {
            if ($user->email == $email) {
                return $user;
            }
        }
        return null;
    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $categoryToReturn = $this->users;

        if ($orderField) {
            usort($categoryToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $categoryToReturn;
    }

    public function findById(int $idUser): array
    {
        $users = [];
        foreach ($this->users as $user) {
            if ($user->id === $idUser) {
                $users[] = $user;
            }
        }
        return $users;
    }
}
