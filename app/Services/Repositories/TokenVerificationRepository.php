<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Tools\Database;

class TokenVerificationRepository
{
    private static ?TokenVerificationRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'verification_tokens';

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
    }

    public static function getInstance(Database $database): TokenVerificationRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new TokenVerificationRepository($database);
        }

        return self::$_instance;
    }


    public function findById(string $token): array
    {
        return $this->_bdd->makeRequest("SELECT * FROM $this->tablename WHERE token=:token", [
            "token" => $token
        ]);
    }

}
