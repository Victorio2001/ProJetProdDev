<?php

namespace BibliOlen\Controllers;

use BibliOlen\Core\Mailer;
use BibliOlen\Core\Table;
use BibliOlen\Services\Crud\CrudUser;
use BibliOlen\Services\Repositories\ModulesRepository;
use BibliOlen\Services\Repositories\PromotionRepository;
use BibliOlen\Services\Repositories\RoleRepository;
use BibliOlen\Services\Repositories\UserRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;
use BibliOlen\Tools\Http;
use Exception;


class UserController
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

    public function index(): void
    {
        $params = Http::getUriParams();
        $page = isset($params['page']) ? (int)$params['page'] : 1;

        $users = [];
        $userRepo = UserRepository::getInstance(database: $this->db);

        $total = 0;
        $linesPerPage = 10;

        if (isset($params['filter']))
            $userData = $userRepo->findByFilter($params['filter'], $page, $linesPerPage);
        else
            $userData = $userRepo->findWithPagination($page, $linesPerPage);

        foreach ($userData['users'] as $user) {
            if ($user->readOnly === false)
                $archiv = Table::createActions([
                    Table::createButton('openUserModal', 'Modifier l\'utilisateur', dataContext: "$user->id ; $user->lastName ; $user->firstName ; $user->email ")
                ]);
            else
                $archiv = 'Pas d\'action';

            $total++;

            $users[] = [
                'Prénom' => $user->firstName,
                'Nom' => $user->lastName,
                'Rôle' => $user->role->name,
                'Promotion' => $user->promotion->name,
                'Archivé ?' => ($user->readOnly === true) ? 'Oui' : 'Non',
                'Action' => $archiv

            ];
        }

        $totalPages = ceil($total / $linesPerPage);
        $currentPage = $page;

        $columnName = ['Prénom', 'Nom', 'Rôle', 'Promotion', 'Archivé ?', 'Action'];
        $UserTable = new Table($users, $columnName);

        $header_data = [
            'titre' => 'Utilisateur',
            'description' => 'Retrouvez ici les différents utilisateurs de la bibliothèque.',
            'img' => '/public/img/user.jpg'
        ];

        View::render(
            category: 'User',
            view: 'user',
            data: [
                'pageAction' => ['path' => "/utilisateur/recherche", 'messageFilter' => 'Entrez le nom / le prénom de l\'utilisateur / le rôle / la promotion'],
                'add' => ['path' => "/utilisateur/ajout", 'messageAdd' => 'Ajout utilisateur'],
                'UserTable' => $UserTable,
                'header_data' => $header_data,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
            ]
        );
    }

    public function info(): void
    {
        $header_data = [
            'titre' => 'Utilisateur Informartion',
            'description' => 'Retrouvez ici les différents utilisateurs de la bibliothèque.',
            'img' => '/public/img/user.jpg'
        ];

        View::render(
            category: 'User',
            view: 'userInfo',
            data: [
                'header_data' => $header_data,
            ]
        );
    }

    public function add(): void
    {
        $header_data = [
            'titre' => 'Ajout d\'un utilisateur',
            'description' => '',
            'img' => '/public/img/addLivre.jpg'
        ];

        $role = RoleRepository::getInstance(database: $this->db);
        $promotion = PromotionRepository::getInstance(database: $this->db);
        $module = ModulesRepository::getInstance(database: $this->db);
        $optionsModule = $module->findAll();
        $optionsPromotion = $promotion->findAll();
        $optionsRole = $role->findAll();

        View::render(
            category: 'User',
            view: 'addUser',
            data: [
                'header_data' => $header_data,
                'optionsRole' => $optionsRole,
                'optionsPromotion' => $optionsPromotion,
                'optionsModule' => $optionsModule,

            ]
        );
    }

    public function addUtilisateur(): void
    {
        $data = $_POST;

        $mdp = Tools::generateRandomTemporaryPassword();
        $data["mdp"] = $mdp;
        $data['lecture_seule'] = 'false';
        $crudUser = CrudUser::getInstance(database: $this->db);
        try {
            $newUser = $crudUser->create($data);
            $mail = new  Mailer();
            $mail->sendRegisterMail($newUser);
            Tools::setFlash("success", "L'utilisateur a bien été ajouter.");
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            if (str_contains(strtolower($errorMessage), "Path cannot be empty")) {
                Tools::setFlash("error", "Path cannot be empty.");
            } else {
                Tools::setFlash("error", "Une erreur est survenue lors de l'ajout de l'utilisateur.");
                header('Location: /utilisateur/ajout');
            }
        }
        header('Location: /utilisateur/recherche');
    }
}
