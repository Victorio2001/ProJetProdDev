<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\BookModel;
use BibliOlen\Models\LoanModel;
use BibliOlen\Models\UserModel;
use BibliOlen\Tools\Database;

class LoanRepository
{
    private static ?LoanRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'utilisateur_livres_emprunter';
    private array $loan;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->loan = [];
    }

    public static function getInstance(Database $database): LoanRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new LoanRepository($database);
            self::$_instance->loadLoan();
        }

        return self::$_instance;
    }

    private function loadLoan(): void
    {
        $loanUsers = $this->_bdd->makeRequest("SELECT * FROM utilisateurs");
        $loanBooks = $this->_bdd->makeRequest("SELECT * FROM livres");
        $reservateBooks = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        $bookRepo = BooksRepository::getInstance($this->_bdd);
        $userRepo = UserRepository::getInstance($this->_bdd);
        $users = array();
        $books = array();

        foreach ($loanUsers as $loanUser) {
            $user = $userRepo->findById($loanUser['id_utilisateur']);

            if ($user)
                $users[$loanUser['id_utilisateur']] = $user;
        }

        foreach ($loanBooks as $loanBook) {
            $book = $bookRepo->findById($loanBook['id_livre']);

            if ($book)
                $books[$loanBook['id_livre']] = $book;
        }


        foreach ($reservateBooks as $reservate) {
            $this->loan[] = new LoanModel(
                id_book: $reservate['id_livre'],
                id_user: $reservate['id_utilisateur'],
                loan_date: $reservate['date_emprunt'],
                real_loan_date: $reservate['date_retour_reel'] ?? '',
                loan_deadline: $reservate['date_retour_limite'],
                validated: $reservate['validated'],
                quantite: $reservate['quantite'],
                user: $users[$reservate['id_utilisateur']][0] ?? UserModel::getDefault(),
                book: $books[$reservate['id_livre']] ?? BookModel::getDefault(),
            );

        }
    }

    public function findById(int $idBook): array
    {
        $loans = [];
        foreach ($this->loan as $reservate) {
            if ($reservate->id_book === $idBook) {
                $loans[] = $reservate;
            }
        }
        return $loans;
    }

    public function countUnreturnedBooks(int $bookId): int
    {
        $loanData = $this->loan;
        $unreturnedBooks = array_filter($loanData, function ($r) use ($bookId) {
            return $r->id_book === $bookId && $r->real_loan_date === "" && $r->validated === true;
        });
        return count($unreturnedBooks);
    }

    public function dateWhenTheNextBookWillBeBack(int $bookId): string
    {
        foreach ($this->loan as $r) {
            if ($r->id_book === $bookId && $r->real_loan_date === "" && $r->validated === true) {
                return $r->loan_deadline;
            }
        }
        return "";
    }

    public function userHasBook(int $bookId, int $userId): bool
    {
        foreach ($this->loan as $r) {
            if ($r->id_book === $bookId && $r->id_user === $userId && $r->real_loan_date === "") {
                return true;
            }
        }
        return false;
    }

    public function loansByUser(int $userId): array
    {
        $loans = [];
        foreach ($this->loan as $loan) {
            if ($loan->id_user === $userId) {
                $loans[] = $loan;
            }
        }
        return $loans;
    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $loanToReturn = $this->loan;
        if ($orderField) {
            usort($loanToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $loanToReturn;
    }

}
