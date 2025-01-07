<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\AuthorModel;
use BibliOlen\Tools\Database;

class AuthorsRepository
{
    private static ?AuthorsRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'auteurs';
    private array $authors;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->authors = [];
    }

    public static function getInstance(Database $database): AuthorsRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new AuthorsRepository($database);
            self::$_instance->loadAuthors();
        }

        return self::$_instance;
    }

    private function loadAuthors(): void
    {
        $books = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($books as $book) {
            $this->authors[] = new AuthorModel(
                id: $book['id_auteur'],
                firstName: $book['prenom_auteur'],
                lastName: $book['nom_auteur'],
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

    public function findById(int $id): AuthorModel|null
    {
        foreach ($this->authors as $author) {
            if ($author->id === $id) {
                return $author;
            }
        }

        return null;
    }
}
