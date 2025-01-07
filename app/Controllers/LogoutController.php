<?php

namespace BibliOlen\Controllers;

class LogoutController
{
    public function handle(): void
    {
        session_destroy();
        header('Location: /login');
    }
}
