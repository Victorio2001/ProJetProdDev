<?php

namespace BibliOlen\Models;

class RoleModel
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function getDefault(): RoleModel
    {
        return new RoleModel(
            id: 0,
            name: 'Default Role'
        );
    }
}
