<?php

namespace BibliOlen\Tools;

use PDO;

class Database
{
    /**
     * L'objet unique Database
     */
    private static ?Database $_instance = null;

    private PDO $_bdd;

    /**
     * Constructeur de la classe
     *
     * @param string $host L'hote de la base de données
     * @param string $user Le nom d'utilisateur de la base de données
     * @param string $password Le mot de passe de la base de données
     * @param string $database Le nom de la base de données
     */
    private function __construct(
        private readonly string $host,
        private readonly string $user,
        private readonly string $password,
        private readonly string $database,
        private readonly int $port,
    )
    {
        $this->_bdd = new PDO(
            "pgsql:host=$this->host;dbname=$this->database;port=$this->port;",
            $this->user,
            $this->password
        );
    }

    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     */
    public static function getInstance(
        string $host,
        string $user,
        string $password,
        string $database,
        int $port
    ): Database
    {
        if (is_null(self::$_instance))
            self::$_instance = new Database($host, $user, $password, $database, $port);

        return self::$_instance;
    }

    /**
     * Méthode qui fait une requête SQL
     *
     * @param string $sql La requête SQL
     * @param array $params Les paramètres de la requête
     */

    public function makeRequest(string $sql, array $params = []): array
    {
        $request = $this->_bdd->prepare($sql);
        $request->execute($params);
        $response = $request->fetchAll(PDO::FETCH_ASSOC);
        $request->closeCursor();
        return $response;
    }

    public function makeRequestNoReturn(string $sql, array $params = []): void
    {
        $request = $this->_bdd->prepare($sql);
        $request->execute($params);
        $request->closeCursor();
    }

    public function makeMigrationRequest(string $sql): void
    {
        $this->_bdd->exec($sql);
    }
}
