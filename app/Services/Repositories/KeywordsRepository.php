<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Tools\Database;
use BibliOlen\Models\KeywordModel;


class KeywordsRepository
{
    private static ?KeywordsRepository $_instance = null;
    private Database $_bdd;
    private string $tablename = 'mots_cles';
    private array $Keywords;

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
        $this->Keywords = [];
    }

    public static function getInstance(Database $database): KeywordsRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new KeywordsRepository($database);
            self::$_instance->loadKeywords();
        }

        return self::$_instance;
    }

    private function loadKeywords(): void
    {
        $Keywords = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($Keywords as $Keyword) {
            $this->Keywords[] = new KeywordModel(
                id: $Keyword['id_mot_cle'],
                name: $Keyword['mot_cle'],
            );
        }
    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $KeywordsToReturn = $this->Keywords;

        if ($orderField) {
            usort($KeywordsToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $KeywordsToReturn;
    }

}