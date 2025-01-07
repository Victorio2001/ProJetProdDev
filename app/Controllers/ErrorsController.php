<?php

namespace BibliOlen\Controllers;

use BibliOlen\Tools\View;


class ErrorsController
{
    public function __construct()
    {
    }

    public function notAllowed(): void
    {
        http_response_code(403); // Forbidden (not allowed)

        View::render(
            category: 'Errors',
            view: '403',
        );
    }

    public function notFound(): void
    {
        http_response_code(404); // Not Found

        View::render(
            category: 'Errors',
            view: '404'
        );
    }
}
