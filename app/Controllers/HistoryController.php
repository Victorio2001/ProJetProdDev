<?php

namespace BibliOlen\Controllers;

use BibliOlen\Services\Crud\CrudLoan;
use BibliOlen\Services\Repositories\BooksRepository;
use BibliOlen\Services\Repositories\LoanRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\View;
use BibliOlen\Tools\Tools;
use Exception;

class HistoryController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance(
    getenv('DB_HOST'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD'),
    getenv('DB_NAME'),
    getenv('DB_PORT')
);
    }

    public function cancel(): void
    {
        $loanCrud = CrudLoan::getInstance(database: $this->db);
        try {
            $loanCrud->annulLoan($_POST);

            Tools::setFlash("success", "L'emprunt a bien été annulé.");

        } catch (Exception) {
            Tools::setFlash("error", "Une erreur est survenue lors de l'annulation.");
        }

        header("Location: /historique");

    }

    public function show(): void
    {
        $header_data = [
            'titre' => 'Historique',
            'description' => 'Retrouvez ici vos emprunts passés.',
            'img' => '/public/img/historic.jpg'
        ];
        $loanRepository = LoanRepository::getInstance(database: $this->db);
        $loanbyUser = $loanRepository->loansByUser($_SESSION['user']->id);

        usort($loanbyUser, function ($a, $b) {
            $loanDateA = strtotime($a->loan_date);
            $loanDateB = strtotime($b->loan_date);

            if ($loanDateA == $loanDateB) {
                return 0;
            }

            return ($loanDateA > $loanDateB) ? -1 : 1;
        });

        $loanController = LoanController::processLoanItems($loanbyUser);

        View::render(
            category: 'History',
            view: 'historic',
            data: [
                'header_data' => $header_data,
                'timelineItems' => $loanController
            ]
        );
    }

}
