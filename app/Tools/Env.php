<?php

namespace BibliOlen\Tools;

class Env
{
    public static function loadEnv(): void
    {
        $envFile = getcwd() . DIRECTORY_SEPARATOR . '.env';

        if (!file_exists($envFile))
            return;

        $fContent = file_get_contents($envFile);

        $rawEnv = explode("\n", $fContent);

        foreach ($rawEnv as $line) {
            $line = str_replace(["\n", "\r"], '', $line);

            if (empty($line) || $line[0] === '#')
                continue;

            [$key, $value] = explode('=', $line, 2);

            $key = trim($key);
            $value = str_replace('"', '', trim($value));

            putenv("$key=$value");
        }
    }
}
