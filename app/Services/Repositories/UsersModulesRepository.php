<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\UserModuleModel;
use BibliOlen\Tools\Database;

class UsersModulesRepository
{
    private static ?UsersModulesRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'utilisateurs_modules';
    private array $usersModules;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->usersModules = [];
    }

    public static function getInstance(Database $database): UsersModulesRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new UsersModulesRepository($database);
            self::$_instance->loadUtilisateurs_modules();
        }

        return self::$_instance;
    }

    private function loadUtilisateurs_modules(): void
    {
        $usersModules = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($usersModules as $userModule) {
            $this->usersModules[] = new UserModuleModel(
                id: $userModule['id'],
                user_id: $userModule['user_id'],
                module_id: $userModule['module_id']
            );
        }

    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $categoryToReturn = $this->usersModules;

        if ($orderField) {
            usort($categoryToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $categoryToReturn;
    }

    public function findById(int $idModules): array
    {
        $usersModules = [];
        foreach ($this->usersModules as $userModule) {
            if ($userModule->id === $idModules) {
                $usersModules[] = $userModule;
            }
        }
        return $usersModules;
    }
}
