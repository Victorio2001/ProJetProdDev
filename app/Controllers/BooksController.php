<?php

namespace BibliOlen\Controllers;

use BibliOlen\Services\Repositories\BooksRepository;
use BibliOlen\Services\Repositories\LoanRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Http;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

class BooksController
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

    public function show(int $id): void
    {
        $bookRepo = BooksRepository::getInstance(database: $this->db);
        $cartBook = $bookRepo->findById($id);
        $LoanRepo = LoanRepository::getInstance(database: $this->db);
        $loanData = $LoanRepo->dateWhenTheNextBookWillBeBack($id);
        $userHasBook = $LoanRepo->userHasBook($id, $_SESSION['user']->id);
        $Loanwithid = $LoanRepo->findById($id);
        $quantiteReserve = 0;

        foreach ($Loanwithid as $item) {
            if ($item !== null && $item->validated && $item->real_loan_date === null) {
                $quantiteReserve = $quantiteReserve + $item->quantite;
            }
        }


        $nombreExemplaireActive = $cartBook->nbExemplaires - ($quantiteReserve);


        $header_data = [
            'titre' => $cartBook->titre,
            'description' => '',
            'img' => '/public/img/image-jeunesse.jpg',
            'livre_categories' => $cartBook->categories
        ];

        View::render(
            category: 'Books',
            view: 'booksInformation',
            data: [
                'cartBook' => $cartBook,
                'header_data' => $header_data,
                'nextDatetoBeAvaible' => ['valeur' => $loanData],
                'userHasBook' => $userHasBook,
                'countnbInStock' => $nombreExemplaireActive
            ]
        );
    }

    public function index(): void
    {
        $params = Http::getUriParams();
        $page = isset($params['page']) ? (int)$params['page'] : 1;

        $header_data = [
            'titre' => 'Les livres',
            'description' => 'Retrouvez sur cette page tous les livres achetés par l’ORT',
            'img' => '/public/img/books-head.jpg'
        ];

        $booksPerPage = 6;
        $bookRepo = BooksRepository::getInstance(database: $this->db);

        if (isset($params['filter'])) {
            $booksData = $bookRepo->findByFilter($params['filter'], $page, $booksPerPage, true);
        } else {
            $booksData = $bookRepo->findWithPagination($page, $booksPerPage,removeArchive:true);
        }

        $cartBook = $booksData['books'];
        $totalBooks = $booksData['total'];
        $totalPages = ceil($totalBooks / $booksPerPage);
        $currentPage = $page;

        View::render(
            category: 'Books',
            view: 'booksSearch',
            data: ['pageAction' => ['path' => "/livres/recherche", 'messageFilter' => 'Entrez le nom de l\'auteur, le titre ou l\'année de publication , la description et mot clé'],
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'cartBook' => $cartBook,
                'header_data' => $header_data
            ]
        );
    }

}
