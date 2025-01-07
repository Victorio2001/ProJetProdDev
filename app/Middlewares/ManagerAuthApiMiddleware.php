<?php

namespace BibliOlen\Middlewares;

use BibliOlen\Tools\Http;
use Closure;
use Corviz\Router\Middleware;

class ManagerAuthApiMiddleware extends Middleware
{
    public function handle(Closure $next): mixed
    {
        if (!isset($_SESSION['user'])) {
            Http::sendJson(['error' => 'Unauthorized'], 401);
            exit();
        }

        if ($_SESSION['user']->role->id !== 1) {
            Http::sendJson(['error' => 'Unauthorized'], 401);
            exit();
        }

        return $next();
    }
}
