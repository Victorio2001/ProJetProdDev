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
    getenv('DB_NAME')
);

//création de la table migrations
$database->makeRequest('CREATE TABLE IF NOT EXISTS migrations (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)');

//récupération de la dernière migrations
$lastDbMigration = $database->makeRequest('SELECT * FROM migrations ORDER BY id DESC LIMIT 1');

//scan du directory "/sql"
$migrations = scandir(__DIR__ . '/sql/up');

// un peu flou, compare les deux tableaux et prend la dif
$migrations = array_diff($migrations, ['.', '..']);

//tri
sort($migrations);


//récupération du dernier fichier de migrations
$lastFileMigration = (int)explode('_', $migrations[count($migrations) - 1])[0];


//check stock migrations
if (empty($lastDbMigration)) {
    $lastDbMigration = -1;
} else{
    //pas compris
    $lastDbMigration = (int)$lastDbMigration[0]['id'];
}

//affichage last migrations et son id
echo '----------------------------------------' . PHP_EOL;
echo 'Migration system' . PHP_EOL;
echo 'Last migration id applied in database : ' . $lastDbMigration . PHP_EOL;
echo 'Last migration id in migration files : ' . $lastFileMigration . PHP_EOL;


//message en mode tout est fini
if ($lastDbMigration === $lastFileMigration) {
    echo 'All migrations are applied' . PHP_EOL;
    echo '----------------------------------------' . PHP_EOL;
    exit;
}

echo '----------------------------------------' . PHP_EOL;
echo PHP_EOL;



foreach ($migrations as $migration) {
    //recup id migration
    $migrationId = (int)explode('_', $migration)[0];

    //si reste migration
    if ($migrationId > $lastDbMigration) {
        echo "Applying new migration : $migration" . PHP_EOL;

        //recuperation content fichier sql
        $sql = file_get_contents(__DIR__ . '/sql/up/' . $migration);

        //push du contenu dans bdd
        $database->makeMigrationRequest($sql);

        //insert nom migrations table migration
        $database->makeRequest('INSERT INTO migrations (name) VALUES (?)', [$migration]);
    }
}

//inutile
echo PHP_EOL;
echo '----------------------------------------' . PHP_EOL;
echo 'Migration system' . PHP_EOL;
echo 'All migrations are applied' . PHP_EOL;
echo '----------------------------------------' . PHP_EOL;
echo PHP_EOL;
