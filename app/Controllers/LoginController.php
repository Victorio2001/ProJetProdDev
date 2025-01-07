<?php

namespace BibliOlen\Controllers;

use BibliOlen\Services\Repositories\UserRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

class LoginController
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

    public function render(): void
    {
        View::render(
            category: "Auth",
            view: 'login'
        );
    }

    public function handle(): void
    {
        if (!isset($_SESSION['token']) || !isset($_POST['token']) || $_SESSION['token'] !== $_POST['token']) {
            Tools::setFlash("error", "Une erreur est survenue lors de la validation du formulaire.");
            header('Location: /login');
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($email) || empty($password)) {
            Tools::setFlash("error", "Veuillez remplir tous les champs du formulaire avant de soumettre.");
            header('Location: /login');
            return;
        }

        $authRepo = UserRepository::getInstance(database: $this->db);
        $user = $authRepo->getByEmail($email);

        if (!$user) {
            Tools::setFlash("error", "Le nom d'utilisateur ou le mot de passe est incorrect.");
            header('Location: /login');
            return;
        }

        $authentication = Tools::verifyPassword($password, $user->getPassword());

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['rememberMe'])) {
                $rememberMeExpire = time() + 30 * 24 * 3600;
                $userId = $user->id;
                setcookie('rememberMe', $userId, $rememberMeExpire, '/');
            } else {
                if (isset($_COOKIE['rememberMe'])) {
                    setcookie('rememberMe', '', time() - 3600, '/');
                }
            }
        }

        if (!$authentication) {
            Tools::setFlash("error", "Le nom d'utilisateur ou le mot de passe est incorrect.");
            header('Location: /login');
            return;
        }

        $_SESSION['user'] = $user;

        header('Location: /accueil');
    }
}
