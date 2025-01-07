<?php

namespace BibliOlen\Middlewares;

use Closure;
use Corviz\Router\Middleware;

class AuthMiddleware extends Middleware
{
    public function handle(Closure $next): mixed
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit();
        }

        if ($_SESSION['user']->role->id == 4) {
            header("Location: /not-allowed");
            exit();
        }

        return $next();
    }
}
