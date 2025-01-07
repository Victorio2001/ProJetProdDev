<?php

namespace BibliOlen\Controllers;

use BibliOlen\Services\Crud\CrudAuthors;
use BibliOlen\Services\Crud\CrudBook;
use BibliOlen\Services\Crud\CrudKeyword;
use BibliOlen\Services\Crud\CrudLoan;
use BibliOlen\Services\Crud\CrudPublisher;
use BibliOlen\Services\Crud\CrudUser;
use BibliOlen\Services\Repositories\AuthorsRepository;
use BibliOlen\Services\Repositories\KeywordsRepository;
use BibliOlen\Services\Repositories\PublishersRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Http;

class ApiController
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

    public function getKeywords(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $keywordsRepo = KeywordsRepository::getInstance(database: $this->db);
        $keywords = $keywordsRepo->findAll();

        Http::sendJson($keywords);
    }

    public function addKeyword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['keyword'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }

        $crudKeyword = CrudKeyword::getInstance(database: $this->db);
        $response = $crudKeyword->apiCreate(['keyword' => $data['keyword']]);

        Http::sendJson($response, $response['status']);
    }

    public function getAuthors(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $authorsRepo = AuthorsRepository::getInstance(database: $this->db);
        $authors = $authorsRepo->findAll();

        Http::sendJson($authors);
    }

    public function addAuthor(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['lastname'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }

        $crudAuthor = CrudAuthors::getInstance(database: $this->db);
        $response = $crudAuthor->apiCreate(['lastname' => $data['lastname']]);

        Http::sendJson($response, $response['status']);
    }

    public function getPublishers(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $publishersRepo = PublishersRepository::getInstance(database: $this->db);
        $publishers = $publishersRepo->findAll();

        Http::sendJson($publishers);
    }

    public function addPublisher(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['publisher'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }

        $crudPublisher = CrudPublisher::getInstance(database: $this->db);
        $response = $crudPublisher->apiCreate(['publisher' => $data['publisher']]);

        Http::sendJson($response, $response['status']);
    }

     /* 
    Traitement de l'edit d'un book
    */
    public function modifBook(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);


        if (empty($data['id_book'] ||$data['name'] ||$data['resume']||$data['date']|| $data['nombre_exemplaires'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }

        $crudBook = CrudBook::getInstance(database: $this->db);
        $response = $crudBook->apiModif($data);


        Http::sendJson($response, $response['status']);
    }
    public function deleteBook(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_book'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }
        $crudBook = CrudBook::getInstance(database: $this->db);
        $response = $crudBook->apiDelete($data);

        Http::sendJson($response, $response['status']);
    }

    /* 
    Traitement de l'edit d'un usser
    */
    public function modifUser(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_user'] || $data['name'] || $data['firstname'] || $data['email'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }
        $crudUser = CrudUser::getInstance(database: $this->db);
        $response = $crudUser->apiModif($data);

        Http::sendJson($response, $response['status']);
    }
    public function archivUser(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_user'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }
        $crudUser = CrudUser::getInstance(database: $this->db);
        $response = $crudUser->apiArchivUser($data);

        Http::sendJson($response, $response['status']);
    }


    /* 
    Traitement de l'edit d'un loan
    */


    public function cancelLoan(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_user'] || $data['id_book'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }
        $crudUser = CrudLoan::getInstance(database: $this->db);
        $response = $crudUser->cancelLoan($data);

        Http::sendJson($response, $response['status']);
    }

    public function loanFirstStep(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_user'] || $data['id_book'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }

        $crudUser = CrudLoan::getInstance(database: $this->db);
        $response = $crudUser->updateDateEmprunt($data);

        Http::sendJson($response, $response['status']);
    }

    public function loanReturn(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Http::sendJson(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_user'] || $data['id_book']||$data['loanReturn'])) {
            Http::sendJson(['error' => 'Bad request'], 400);
            return;
        }
        $crudUser = CrudLoan::getInstance(database: $this->db);
        $response = $crudUser->updateDateRetour($data);

        Http::sendJson($response, $response['status']);
    }
}
