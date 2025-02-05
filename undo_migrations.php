<?php
require_once __DIR__ . '/app/Tools/Autoload.php';

use BibliOlen\Autoload;
use BibliOlen\Tools\Env;
use BibliOlen\Tools\Database;

Autoload::register();
Env::loadEnv();

$database = Database::getInstance(
    getenv('DB_HOST'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD'),
    getenv('DB_NAME'),
    getenv('DB_PORT')
);

//scan du directory "/sql"
$migrations = scandir(__DIR__ . '/sql');

// un peu flou, compare les deux tableaux et prend la dif
$migrations = array_diff($migrations, ['.', '..']);

//tri
sort($migrations);


//récupération du dernier fichier de migrations
$lastFileMigration = (int)explode('_', $migrations[count($migrations) - 1])[0];




