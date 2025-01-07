<?php

namespace BibliOlen\Models;

class DealModel
{
    public int $id_transaction;
    public string $deal_date;
    public int $nbadd;
    public int $nbdelete;
    public int $id_user;
    public int $id_book;

    public function __construct(int $id_transaction,string $deal_date, int $nbdelete,int $nbadd, int $id_user,int $id_book)
    {
        $this->id_transaction = $id_transaction;
        $this->deal_date = $deal_date;
        $this->nbadd = $nbadd;
        $this->nbdelete = $nbdelete;
        $this->id_user = $id_user;
        $this->id_book = $id_book;
    }
}
