<?php

namespace BibliOlen\Services\Repositories;

use BibliOlen\Models\DealModel;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Tools;

class DealRepository
{
    private static ?DealRepository $_instance = null;
    private Database $_bdd;

    private string $tablename = 'transactions';
    private array $deal = [];

    private function __construct(
        private readonly Database $database
    )
    {
        $this->_bdd = $this->database;
    }
    public static function getInstance(Database $database): DealRepository
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new DealRepository($database);
            self::$_instance->loadDeal();
        }

        return self::$_instance;
    }

    private function loadDeal(): void
    {
        $deals = $this->_bdd->makeRequest("SELECT * FROM $this->tablename");

        foreach ($deals as $deal) {
            $this->deal[] = new DealModel(
                id_transaction: $deal['id_transaction'],
                deal_date: $deal['date_transaction'],
                nbdelete: $deal['nb_ex_retire'],
                nbadd: $deal['nb_ex_ajoute'],
                id_user: $deal['id_utilisateur'],
                id_book: $deal['id_livre'],
            );
        }
    }

    public function findAll(string $orderField = null, string $order = 'ASC'): array
    {
        $dealsToReturn = $this->deal;

        if ($orderField) {
            usort($dealsToReturn, function ($a, $b) use ($orderField, $order) {
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        }

        return $dealsToReturn;
    }

    public function findWithPagination(int $page, int $perPage, string $orderField = null, string $order = 'ASC', bool $removeArchive = false): array
    {
        $deal = $this->deal;
        $total = count($deal);
        $offset = ($page - 1) * $perPage;

        if ($orderField)
            usort($deal, function ($a, $b) use ($orderField, $order) {
                // memo : -1 < 0 > 1 (0 si $a == $b // -1 si $a < $b // 1 si $a > $b)
                return $order === 'ASC' ? $a->$orderField <=> $b->$orderField : $b->$orderField <=> $a->$orderField;
            });
        return array(
            'deal' => array_splice($deal, $offset, $perPage),
            'total' => $total
        );
    }
}
