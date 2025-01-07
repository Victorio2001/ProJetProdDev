<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\CategoryModel;
use BibliOlen\Tools\Database;

class CategoryRepository
{
    private static ?CategoryRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'mots_cles';
    private array $category= [];

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
    }

    public static function getInstance(Database $database): CategoryRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new CategoryRepository($database);
            self::$_instance->loadCategory();
        }

        return self::$_instance;
    }

    private function loadCategory(): void
    {
        $books = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($books as $book) {
            $this->category[] = new CategoryModel(
                id: $book['id_mot_cle'],
                name: $book['mot_cle'],
            );
        }
    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $categoryToReturn = $this->category;

        if ($orderField) {
            usort($categoryToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $categoryToReturn;
    }

    public function findById(int $id): CategoryModel|null
    {
        foreach ($this->category as $category) {
            if ($category->id === $id) {
                return $category;
            }
        }
        return null;
    }
}
