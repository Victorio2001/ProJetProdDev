<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\PublisherModel;
use BibliOlen\Tools\Database;

class PublishersRepository
{
    private static ?PublishersRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'editeurs';
    private array $authors;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->authors = [];
    }

    public static function getInstance(Database $database): PublishersRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new PublishersRepository($database);
            self::$_instance->loadPublishers();
        }

        return self::$_instance;
    }

    private function loadPublishers(): void
    {
        $books = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($books as $book) {
            $this->authors[] = new PublisherModel(
                id: $book['id_editeur'],
                name: $book['nom_editeur'],
            );
        }
    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $authorsToReturn = $this->authors;

        if ($orderField) {
            usort($authorsToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $authorsToReturn;
    }

    public function findById(int $id): PublisherModel|null
    {
        foreach ($this->authors as $author) {
            if ($author->id === $id) {
                return $author;
            }
        }

        return null;
    }
}
