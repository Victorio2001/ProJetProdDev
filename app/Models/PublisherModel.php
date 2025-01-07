<?php

namespace BibliOlen\Models;

class PublisherModel
{
    public int $id;
    public string $name;
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public static function getDefault(): PublisherModel
    {
        return new PublisherModel(0, '');
    }
}
