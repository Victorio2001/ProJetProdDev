<?php

namespace BibliOlen\Tools;

class View
{
    public static function render(string $category, string $view, array $data = []): void
    {
        if (!str_contains($view, '.php')) {
            $view .= '.php';
        }

        $paths = array('app', 'Views');

        if ($category) {
            $paths[] = $category;
        }

        $paths[] = $view;

        $path = implode(DIRECTORY_SEPARATOR, $paths);

        if (file_exists($path)) {
            extract($data);

            require_once $path;
        } else {
            header('Location: /404');
        }
    }

    public static function getEmailTemplate(string $template): string
    {
        if (!str_contains($template, '.html')) {
            $template .= '.html';
        }

        $paths = array('app', 'Views', 'Emails', $template);

        $path = implode(DIRECTORY_SEPARATOR, $paths);

        if (file_exists($path)) {
            return file_get_contents($path);
        } else {
            return '';
        }
    }
}
