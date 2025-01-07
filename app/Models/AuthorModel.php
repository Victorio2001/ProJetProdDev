<?php

namespace BibliOlen\Models;

class AuthorModel
{
    public int $id;
    public string|null $firstName;
    public string $lastName;

    public function __construct(int $id, string|null $firstName, string $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function __toString(): string
    {
        return trim($this->firstName . ' ' . $this->lastName ?? '');
    }
}
