<?php

namespace BibliOlen\Models;

class PromotionModel
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function getDefault(): PromotionModel
    {
        return new PromotionModel(
            id: 0,
            name: 'Default Promotion'
        );
    }

}
