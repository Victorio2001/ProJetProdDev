<?php

namespace BibliOlen\Controllers;

use BibliOlen\Core\Table;
use BibliOlen\Services\Crud\CrudLoan;
use BibliOlen\Services\Repositories\BooksRepository;
use BibliOlen\Services\Repositories\LoanRepository;
use BibliOlen\Services\Repositories\UserRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Http;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;
use Exception;

class LoanController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance(
            host: getenv("DB_HOST"),
            user: getenv("DB_USERNAME"),
            password: getenv("DB_PASSWORD"),
            database: getenv("DB_NAME")
        );
    }

    public function add(): void
    {
        $_POST['quantity'] = $_POST['quantity'] ?? 1;
        $_POST['reservation_date'] = empty($_POST['reservation_date']) ? date("Y-m-d") : $_POST['reservation_date'];
        $_POST['book_id'] = intval($_POST['book_id']);
        $reservation_date = $_POST['reservation_date'];

        $week = 7 * 24 * 60 * 60;

        $new_timestamp = strtotime($reservation_date) + 2 * $week;
        $new_date = date('Y-m-d', $new_timestamp);

        $data = [
            'id_book' => $_POST['book_id'],
            'id_user' => $_SESSION['user']->id,
            'loan_date' => $_POST['reservation_date'] ?? null,
            'real_loan_date' => null,
            'loan_deadline' => $new_date,
            'quantity' => $_POST['quantity']
        ];

        $loanCrud = CrudLoan::getInstance(database: $this->db);

        try {
            $loanCrud->create($data);

            Tools::setFlash("success", "L'emprunt a bien été enregistré.");

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            if (str_contains(strtolower($errorMessage), "unique violation")) {
                Tools::setFlash("error", "Vous avez déjà emprunté ce livre.");
            } else {
                Tools::setFlash("error", "Une erreur est survenue lors de l'emprunt.");
            }
        }

        header("Location: /livres/" . $_POST['book_id']);
    }

    public static function processLoanItems($loanbyUser, $filter = null): array
    {
        $loans = $loanbyUser;

        if ($filter) {
            $loans = array_filter($loans, function ($item) use ($filter) {
                return str_contains(strtolower($item->user->firstName), strtolower($filter)) ||
                    str_contains(strtolower($item->user->lastName), strtolower($filter)) ||
                    str_contains(strtolower($item->book->titre), strtolower($filter));
            });
        }

        if (count($loans) == 0)
            return $loans;

        $loansToReturn = [];

        foreach ($loans as $item) {
            $item->titre = $item->book->titre;

            if ($item->validated) {
                $item->label = !empty($item->real_loan_date) ? 'Réservation terminée' : 'En attente de retour  (' . $item->loan_deadline . ')';
                $item->couleur = !empty($item->real_loan_date) ? '#3CFF3C' : '#3CC4FF';
                $item->content = !empty($item->real_loan_date) ? 'Vous avez rendu le livre' : "Le livre est en votre possession, veuillez le rendre à la date indiquée \n";
            } else {
                $item->btnlink = !empty($item->real_loan_date) ? '' : 'Annulé';
                $item->label = !empty($item->real_loan_date) ? 'Annulé' : 'En attente de validation';
                $item->couleur = !empty($item->real_loan_date) ? '#FF6748' : '#E4FF3C';
                $item->content = !empty($item->real_loan_date) ? 'Nous avons dû annuler votre réservation' : 'Allez chercher votre livre à la bibliothèque';
            }

            $loansToReturn[] = $item;
        }

        return $loansToReturn;
    }

    public function list(): void
    {
        $params = Http::getUriParams();
        $page = isset($params['page']) ? (int)$params['page'] : 1;

        $loanRepo = LoanRepository::getInstance(database: $this->db);
        $userRepo = UserRepository::getInstance(database: $this->db);

        $linesPerPage = 10;

        $users = $userRepo->findAll();
        $Loan = [];

        foreach ($users as $key => $user) {
            if ($user->readOnly)
                unset($users[$key]);

            $ld = LoanController::processLoanItems($loanRepo->loansByUser($user->id), $params['filter'] ?? null);

            foreach ($ld as $deal) {
                if ($deal->couleur == '#E4FF3C' || $deal->couleur == '#3CC4FF')
                    $TableAction = Table::createActions([
                        Table::createButton('openLoanModal', 'Gérer la réservation', dataContext: "$deal->id_book  ; $deal->id_user ; $deal->couleur ; $deal->loan_date")
                    ]);
                else
                    $TableAction = 'Aucune action possible';

                $Loan[] = [
                    'Nom' => $user->lastName,
                    'Prénom' => $user->firstName,
                    'Livre' => $deal->titre,
                    'Date d\'emprunt' => $deal->loan_date,
                    'Date de retour ' => !empty($deal->loan_deadline) ? $deal->loan_deadline : 'Vous n\'avez pas encore validé cette emprunt',
                    'Quantité' => $deal->quantite,
                    'Statut' => Table::createBadge($deal->couleur, $deal->label),
                    'Action' => $TableAction,
                ];
            }
        }

        $loanToDisplay = array_slice($Loan, ($page - 1) * $linesPerPage, $linesPerPage);

        $totalPages = ceil(count($Loan) / $linesPerPage);
        $currentPage = $page;

        $columnName = ['Nom', 'Prénom', 'Livre', 'Date d\'emprunt', 'Date de retour', 'Quantité', 'Statut', 'Action'];
        $loanTable = new Table($loanToDisplay, $columnName);

        $header_data = [
            'titre' => 'Réservations',
            'description' => 'Retrouvez ici toutes les réservations faites par les utilisateurs.',
            'img' => '/public/img/Booking.jpg'
        ];

        View::render(
            category: 'Loan',
            view: 'adminLoan',
            data: [
                'pageAction' => ['path' => "/reservation", 'messageFilter' => 'Entrez le nom / le prénom de l\'utilisateur / le titre du livre'],
                'loanTable' => $loanTable,
                'header_data' => $header_data,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
            ]
        );
    }

}

