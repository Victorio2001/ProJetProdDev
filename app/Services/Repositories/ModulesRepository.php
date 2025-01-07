<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\ModuleModel;
use BibliOlen\Tools\Database;

class ModulesRepository
{
    private static ?ModulesRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'modules';
    private array $modules;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->modules = [];
    }

    public static function getInstance(Database $database): ModulesRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new ModulesRepository($database);
            self::$_instance->loadModules();
        }

        return self::$_instance;
    }

    private function loadModules(): void
    {
        $modules = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($modules as $module) {
            $this->modules[] = new ModuleModel(
                id: $module['id_module'],
                name: $module['nom_module']
            );
        }

    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $categoryToReturn = $this->modules;

        if ($orderField) {
            usort($categoryToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $categoryToReturn;
    }

    public function findById(int $idModules): array
    {
        $modules = [];
        foreach ($this->modules as $module) {
            if ($module->id === $idModules) {
                $modules[] = $module;
            }
        }
        return $modules;
    }
}
