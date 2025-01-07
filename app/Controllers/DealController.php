<?php
namespace BibliOlen\Controllers;


use BibliOlen\Tools\Database;

class DealController

{
    private Database $db;
    public function __construct()
    {
        $this->db = Database::getInstance(
            host: getenv("DB_HOST"),
            user: getenv("DB_USERNAME"),
            password: getenv("DB_PASSWORD"),
            database: getenv("DB_NAME")
        );
    }

}
