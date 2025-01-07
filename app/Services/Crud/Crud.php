<?php

namespace BibliOlen\Services\Crud;

use BibliOlen\Tools\Database;

class Crud
{
    protected Database $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function create($data)
    {
    }

    public function update($id, $data)
    {
    }

    public function delete($id)
    {
    }
}
