<?php

namespace BibliOlen\Controllers;


use BibliOlen\Services\Repositories\BooksRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\View;

class HomeController
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

    public function show(): void
    {
        $bookRepo = BooksRepository::getInstance(database: $this->db);
        $books = $bookRepo->findWithPagination(1, 6, 'createdAt', "DESC", true);
        $cartBook = $books['books'];

        View::render(
            category: 'Home',
            view: 'home',
            data: [
                'header_data' => [
                    'titre' => 'Bienvenue sur BibliOlen',
                    'description' => 'BibliOlen est une application WEB concue pur faciliter la gestion de la bibliothèque de l\'ORT Lyon.',
                    'img' => '/public/img/home-bibliotheque.jpg',
                    'bouton_link' => '/livres/recherche',
                    'bouton_text' => 'Voir tous les livres',
                ],
                'cartBook' => $cartBook
            ]
        );
    }
}
