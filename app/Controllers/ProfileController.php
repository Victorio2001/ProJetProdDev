<?php

namespace BibliOlen\Controllers;

use BibliOlen\Core\Mailer;
use BibliOlen\Core\Table;
use BibliOlen\Services\Crud\CrudTokenVerification;
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


class ProfileController
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

    public function render(): void
    {
        View::render(
            category: "User",
            view: 'profile'
        );
    }

    public function handle(): void
    {
        $user = UserRepository::getInstance(database: $this->db);
        $findByMail = $user->getByEmail(strtolower($_SESSION["user"]->email));

        if ($findByMail == '') {
            Tools::setFlash("error", "erreur, contacter un administrateur.");
            header('Location: /profile');
            return;
        }

        Tools::setFlash("success", "Un mail de réinitialisation de mot de passe vous a été envoyé.");

        $mail = new  Mailer();
        $token = Tools::generateRandomString(25);
        $mail->sendResetPasswordMail($findByMail, $token);
        $crudTokenVerification = CrudTokenVerification::getInstance(database: $this->db);
        $data['user_id'] = $findByMail->id;
        $data['token'] = $token;
        $data['used'] = 'false';
        $crudTokenVerification->create($data);

        header('Location: /profile');
    }
}
