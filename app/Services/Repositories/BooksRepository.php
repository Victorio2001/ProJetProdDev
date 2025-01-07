<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\BookModel;
use BibliOlen\Tools\Database;
use DateTime;
use DateTimeZone;
use Exception;


class BooksRepository
{
    private static ?BooksRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'livres';
    private array $books;


    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->books = [];
    }


    public static function getInstance(Database $database): BooksRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new BooksRepository($database);
            self::$_instance->loadBooks();
        }

        return self::$_instance;
    }

    private function loadBooks(): void
    {
        $books = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");
        $bookAuthors = $this->_bdd->makeRequest("SELECT * FROM livre_auteurs");
        $bookCategorys = $this->_bdd->makeRequest("SELECT * FROM livre_mots_cles");

        $publisherRepository = PublishersRepository::getInstance($this->_bdd);
        $authorsRepository = AuthorsRepository::getInstance($this->_bdd);
        $categoryRepository = CategoryRepository::getInstance($this->_bdd);

        $authors = array();
        $category = array();

        foreach ($bookCategorys as $bookCategory) {
            if (!array_key_exists($bookCategory['id_livre'], $category))
                $category[$bookCategory['id_livre']] = array();

            $categories = $categoryRepository->findById($bookCategory['id_mot_cle']);

            if ($categories)
                $category[$bookCategory['id_livre']][] = $categories;
        }

        foreach ($bookAuthors as $bookAuthor) {
            if (!array_key_exists($bookAuthor['id_livre'], $authors))
                $authors[$bookAuthor['id_livre']] = array();

            $author = $authorsRepository->findById($bookAuthor['id_auteur']);

            if ($author)
                $authors[$bookAuthor['id_livre']][] = $author;
        }

        foreach ($books as $book) {
            try {
                $bookCreatedAt = new DateTime(datetime: $book['created_at'], timezone: new DateTimeZone('Europe/Paris'));
            } catch (Exception) {
                $bookCreatedAt = new DateTime();
            }

            $this->books[] = new BookModel(
                id: $book['id_livre'],
                titre: $book['titre_livre'],
                resume: $book['resume_livre'],
                isbn: $book['isbn'],
                anneePublication: $book['annee_publication'],
                imageCouverture: $book['image_couverture'],
                nbExemplaires: $book['nombre_exemplaires'],
                idEditeur: $book['id_editeur'],
                createdAt: $bookCreatedAt,
                auteurs: $authors[$book['id_livre']] ?? [],
                publisher: $publisherRepository->findById($book['id_editeur']),
                categories: $category[$book['id_livre']] ?? [],
                archived: $book['archived']
            );
        }
    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $booksToReturn = $this->books;

        if ($orderField) {
            usort($booksToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $booksToReturn;
    }

    public function findById(int $id): BookModel|null
    {
        foreach ($this->books as $book) {
            if ($book->id === $id) {
                return $book;
            }
        }
        return null;
    }

    public function findByFilter(string $filter, int $page, int $booksPerPage, bool $removeArchive = false, bool $inventory = true): array
    {
        $allBooks = array();
        $filter = strtolower(trim($filter));
        if ($filter == '') {
            $allBooks = $this->books;
            $total = count($allBooks) - $removeArchive ? count(array_filter($allBooks, fn($book) => !$book->archived)) : 0;

            $offset = ($page - 1) * $booksPerPage;
            if ($removeArchive)
                $allBooks = array_filter($allBooks, fn($book) => !$book->archived);

            $booksToReturn = array_splice($allBooks, $offset, $booksPerPage);

            return array(
                'books' => $booksToReturn,
                'total' => $total,
            );
        }

        foreach ($this->books as $book) {
            if (str_contains(strtolower($book->titre), $filter))
                $allBooks[] = $book;
            elseif (str_contains(strtolower($book->description), $filter))
                $allBooks[] = $book;

            elseif (is_numeric($filter) && $book->anneePublication === (int)$filter)
                $allBooks[] = $book;

            foreach ($book->auteurs as $author) {
                $authorInfo = strtolower(trim($author));

                if (str_contains($authorInfo, $filter)&& !in_array($book, $allBooks))
                    $allBooks[] = $book;
            }
            if ($inventory == true) {
                foreach ($book->categories as $category) {
                    $categoryInfo = strtolower(trim($category));

                    if (str_contains($categoryInfo, $filter) && !in_array($book, $allBooks)) {
                        $allBooks[] = $book;
                    }
                }

            }
        }

        $total = count($allBooks) - $removeArchive ? count(array_filter($allBooks, fn($book) => !$book->archived)) : 0;

        $offset = ($page - 1) * $booksPerPage;
        if ($removeArchive)
            $allBooks = array_filter($allBooks, fn($book) => !$book->archived);

        $booksToReturn = array_splice($allBooks, $offset, $booksPerPage);

        return array(
            'books' => $booksToReturn,
            'total' => $total,
        );
    }

    public function findWithPagination(int $page, int $perPage, string $orderField = null, string $order = 'ASC', bool $removeArchive = false): array
    {
        $books = $this->books;
        $total = count($books) - $removeArchive ? count(array_filter($books, fn($book) => !$book->archived)) : 0;
        $offset = ($page - 1) * $perPage;
        if ($removeArchive)
            $books = array_filter($books, fn($book) => !$book->archived);

        if ($orderField)
            usort($books, function ($a, $b) use ($orderField, $order) {
                // memo : -1 < 0 > 1 (0 si $a == $b // -1 si $a < $b // 1 si $a > $b)
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });

        return array(
            'books' => array_splice($books, $offset, $perPage),
            'total' => $total
        );
    }

}
