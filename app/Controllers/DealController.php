<?php
namespace BibliOlen\Controllers;


use BibliOlen\Tools\Database;

class DealController

{
    private Database $db;
    public function __construct()
    {
        $this->db = Database::getInstance(
    getenv('DB_HOST'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD'),
    getenv('DB_NAME'),
    getenv('DB_PORT')
);
    }

}
