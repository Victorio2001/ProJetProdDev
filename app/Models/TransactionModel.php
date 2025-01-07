<?php

namespace BibliOlen\Models;

class TransactionModel
{
    public int $id;
    public string $date;
    public int $nbAjout;
    public int $nbRetrait;
    public BookModel $book;
    public UserModel $user;

    public function __construct(int $id, string $date, int $nbAjout, int $nbRetrait, BookModel $book, UserModel $user)
    {
        $this->id = $id;
        $this->date = $date;
        $this->nbAjout = $nbAjout;
        $this->nbRetrait = $nbRetrait;
        $this->book = $book;
        $this->user = $user;
    }
}
