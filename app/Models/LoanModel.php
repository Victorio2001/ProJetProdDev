<?php

namespace BibliOlen\Models;

class LoanModel
{
    public int $id_book;
    public int $id_user;
    public string $loan_date;
    public string $real_loan_date;
    public string $loan_deadline;
    public bool $validated;
    public int $quantite;
    public UserModel $user;
    public BookModel $book;

    public function __construct(int $id_book, int $id_user, string $loan_date, string $real_loan_date, string $loan_deadline, bool $validated, int $quantite, UserModel $user, BookModel $book)
    {
        $this->id_book = $id_book;
        $this->id_user = $id_user;
        $this->loan_date = $loan_date;
        $this->real_loan_date = $real_loan_date;
        $this->loan_deadline = $loan_deadline;
        $this->validated = $validated;
        $this->quantite = $quantite;
        $this->user = $user;
        $this->book = $book;
    }
}
