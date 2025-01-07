<?php

namespace BibliOlen\Services\Crud;


class CrudTokenVerification extends Crud
{
    private static ?CrudTokenVerification $instance = null;

    private function __construct($database)
    {
        parent::__construct($database);
    }

    public static function getInstance($database): CrudTokenVerification
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }

    public function create($data): void
    {
        $sql = "INSERT INTO verification_tokens (user_id, token, used) VALUES (:user_id, :token, :used)";
        $this->database->makeRequest($sql, [
            "user_id" => $data['user_id'],
            "token" => $data['token'],
            "used" => $data['used']
        ]);
    }

    public function editUsed($data)
    {
        $sql = "UPDATE verification_tokens SET used = :used WHERE token = :token RETURNING user_id";
        $result = $this->database->makeRequest($sql, [
            "used" => $data['used'],
            "token" => $data['token']
        ]);
        return $result[0]["user_id"];
    }
}
