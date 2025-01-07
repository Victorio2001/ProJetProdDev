<?php

namespace BibliOlen\Models;

class TokenVerificationModel
{
    public int $id;
    public int $user_id;
    public string $token;
    public string $used;

    public function __construct(int $id, string $user_id, string $token, string $used)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->token = $token;
        $this->used = $used;
    }
}