<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\PromotionModel;
use BibliOlen\Tools\Database;

class PromotionRepository
{
    private static ?PromotionRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'promotions';
    private array $Promotions;

    private PromotionModel $default;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->default = new PromotionModel(id: 1, name: 'Gestionnaire');
        $this->Promotions = [];
    }

    public static function getInstance(Database $database): PromotionRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new PromotionRepository($database);
            self::$_instance->loadRole();
        }

        return self::$_instance;
    }

    private function loadRole(): void
    {
        $roles = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($roles as $role) {
            $this->Promotions[] = new PromotionModel(
                id: $role['id_promotion'],
                name: $role['nom_promotion']
            );
        }

    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $categoryToReturn = $this->Promotions;

        if ($orderField) {
            usort($categoryToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $categoryToReturn;
    }

    public function findById(int $id): PromotionModel
    {
        foreach ($this->Promotions as $role) {
            if ($role->id === $id) {
                return $role;
            }
        }

        return $this->default;
    }
}
