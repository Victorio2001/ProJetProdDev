<?php

namespace BibliOlen\Models;

class CategoryModel
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
}
