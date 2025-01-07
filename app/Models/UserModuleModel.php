<?php

namespace BibliOlen\Models;

class UserModuleModel
{
    public int $id;
    public string $user_id;
    public string $module_id;


    public function __construct(int $id, int $user_id, int $module_id)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->module_id = $module_id;

    }
}
