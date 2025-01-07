<?php

namespace BibliOlen\Tools;

class Http
{
    public static function getUriParams(): array
    {
        $uri = $_SERVER['REQUEST_URI'];
        $data = parse_url($uri);
        $query = $data['query'] ?? '';
        parse_str($query, $params);
        return $params;
    }

    public static function sendJson(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
