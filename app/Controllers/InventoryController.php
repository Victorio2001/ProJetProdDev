<?php

namespace BibliOlen\Controllers;

use BibliOlen\Core\Table;
use BibliOlen\Services\Crud\CrudBook;
use BibliOlen\Services\Crud\CrudDeal;
use BibliOlen\Services\Repositories\AuthorsRepository;
use BibliOlen\Services\Repositories\BooksRepository;
use BibliOlen\Services\Repositories\DealRepository;
use BibliOlen\Services\Repositories\KeywordsRepository;
use BibliOlen\Services\Repositories\LoanRepository;
use BibliOlen\Services\Repositories\PublishersRepository;
use BibliOlen\Services\Repositories\UserRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Http;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;
use Exception;

class InventoryController
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
        $header_data = [
            'titre' => 'Ajout de Livre',
            'description' => '',
            'img' => '/public/img/addLivre.jpg'
        ];

        $optionsEditeurFindAll = PublishersRepository::getInstance(database: $this->db);
        $optionsAuteurFindAll = AuthorsRepository::getInstance(database: $this->db);
        $optionsMotCleFindAll = KeywordsRepository::getInstance(database: $this->db);

        View::render(
            category: 'Books',
            view: 'booksAdd',
            data: [
                'header_data' => $header_data,
                'optionsEditeur' => $optionsEditeurFindAll->findAll(),
                'optionsAuteur' => $optionsAuteurFindAll->findAll(),
                'optionsMotCle' => $optionsMotCleFindAll->findAll()
            ]
        );
    }

    public function inventory(): void
    {
        $params = Http::getUriParams();

        if (!isset($params['option'])) {
            $params['option'] = 'inventaire';
        }

        $page = isset($params['page']) ? (int)$params['page'] : 1;

        $header_data = [
            'titre' => 'Inventaire',
            'description' => 'Retrouvez ici tout les livres disponibles à la bibliothèque de l\'ORT Lyon.',
            'img' => '/public/img/Inventaire.jpg'
        ];

        $linesPerPage = 10;

        $bookRepo = BooksRepository::getInstance(database: $this->db);
        $loanRepo = LoanRepository::getInstance(database: $this->db);
        $dealRepo = DealRepository::getInstance(database: $this->db);
        $userRepo = UserRepository::getInstance(database: $this->db);

        $tableData = [];

        if (isset($params['filter'])) {
            $booksData = $bookRepo->findByFilter($params['filter'], $page, $linesPerPage, true, false);
        } else {
            $booksData = $bookRepo->findWithPagination($page, $linesPerPage, removeArchive: true);
        }

        if ($params['option'] == 'inventaire') {
            $tableHeader = ['Titre Livre', 'Auteur', 'Éditeur', 'Stock', 'Emprunt', 'Action'];

            foreach ($booksData['books'] as $book) {
                $loanData = $loanRepo->countUnreturnedBooks($book->id);
                $editButton = Table::createButton('openBookModal', 'Modifier', dataContext: "$book->id ; $loanData ; $book->titre ; $book->description; $book->anneePublication ; $book->nbExemplaires");
                $tableData[] = [
                    'Titre Livre' => $book->titre,
                    'Auteur' => $book->auteurs[0] ?? 'Aucun auteur',
                    'Éditeur' => $book->publisher,
                    'Stock' => $book->nbExemplaires,
                    'Emprunt' => $loanData,
                    'Action' => Table::createActions([$editButton])
                ];
            }

            $totalPages = ceil($booksData['total'] / $linesPerPage);
        } else {
            $tableHeader = ['Date', 'Identité de l\'actionnaire', 'Livre', 'Ajouté(s)', 'Supprimé(s)'];
            $dealDataTable = $dealRepo->findWithPagination($page, $linesPerPage);

            foreach ($dealDataTable['deal'] as $deal) {
                $book = $bookRepo->findById($deal->id_book);
                $user = $userRepo->findById($deal->id_user);

                $tableData[] = [
                    'Date' => $deal->deal_date,
                    'Identité de l\'actionnaire' => $user[0]->firstName . ' ' . $user[0]->lastName,
                    'Titre Livre' => $book->titre,
                    'Ajouté(s)' => $deal->nbadd,
                    'Supprimé(s)' => $deal->nbdelete,

                ];
            }

            $totalPages = ceil($dealDataTable['total'] / $linesPerPage);
        }

        $bookTable = new Table($tableData, $tableHeader);

        $currentPage = $page;

        View::render(
            category: 'Books',
            view: 'booksInventory',
            data: [
                'pageAction' => ['path' => "/inventaire", 'messageFilter' => 'Entrez le nom de l\'auteur, le titre, le prénom de l\'auteur'],
                'add' => ['path' => "/inventaire/ajout", 'messageAdd' => 'Ajout Livre'],
                'header_data' => $header_data,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'bookTable' => $bookTable
            ]
        );
    }

    public function addBooksInventory(): void
    {
        $data = $_POST;

        $data["image_couverture"] = $data["isbn"] . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $BooksRepository = CrudBook::getInstance(database: $this->db);
        $DealsRepository = CrudDeal::getInstance(database: $this->db);

        try {
            $valeur = $BooksRepository->create($data);
            if ($valeur['status'] == '500') {
                throw new Exception($valeur['error']);
            }
            $DealsRepository->dealRegist($data, $valeur);
            Tools::setFlash("success", "Le livre a bien été ajouter a l'inventaire.");
            header("Location: /inventaire?option=inventaire");
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            if (str_contains(strtolower($errorMessage), "unique violation")) {
                Tools::setFlash("error", "ISBN identique a un livre qui existe.");
            } elseif (str_contains(strtolower($errorMessage), "Path cannot be empty")) {
                Tools::setFlash("error", "Path cannot be empty.");
            } else {
                Tools::setFlash("error", "Une erreur est survenue lors de l'ajout à l'inventaire.");
            }

            header("Location: /inventaire?option=inventaire");
        }

        $uploadDir = getcwd() . "/public/img/couvertures/";
        $uploadFile = $uploadDir . basename($data["image_couverture"]);
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile);
        } else {
            Tools::setFlash("error", "Le fichier n'est pas une image valide.");
        }


    }
}
