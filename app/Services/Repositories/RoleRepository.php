<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\RoleModel;
use BibliOlen\Tools\Database;

class RoleRepository
{
    private static ?RoleRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'roles';
    private array $roles;

    private RoleModel $default;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->default = new RoleModel(id: 4, name: 'guest');
        $this->roles = [];
    }

    public static function getInstance(Database $database): RoleRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new RoleRepository($database);
            self::$_instance->loadRole();
        }

        return self::$_instance;
    }

    private function loadRole(): void
    {
        $roles = $this->_bdd->makeRequest("SELECT * FROM $this->tablename WHERE id_role != 4");

        foreach ($roles as $role) {
            $this->roles[] = new RoleModel(
                id: $role['id_role'],
                name: $role['nom_role']
            );
        }

    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $categoryToReturn = $this->roles;

        if ($orderField) {
            usort($categoryToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $categoryToReturn;
    }

    public function findById(int $id): RoleModel
    {
        foreach ($this->roles as $role) {
            if ($role->id === $id) {
                return $role;
            }
        }

        return $this->default;
    }
}
