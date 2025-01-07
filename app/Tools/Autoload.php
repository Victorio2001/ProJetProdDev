<?php

namespace BibliOlen;

class Autoload
{
    public static function register(): void
    {
        spl_autoload_register(function ($class) {
            $class = str_replace('\\', '/', $class);

            # root namespace is BibliOlen
            $class = str_replace('BibliOlen/', 'app' . DIRECTORY_SEPARATOR, $class);
            $class = str_replace('/', DIRECTORY_SEPARATOR, $class);
            $class = getcwd() . DIRECTORY_SEPARATOR . $class;
            $path = $class . '.php';

            if (file_exists($path)) {
                require_once $path;
            }
        });
    }
}
