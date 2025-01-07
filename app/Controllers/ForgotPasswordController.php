<?php

namespace BibliOlen\Controllers;

use BibliOlen\Core\Mailer;
use BibliOlen\Services\Crud\CrudTokenVerification;
use BibliOlen\Services\Crud\CrudUser;
use BibliOlen\Services\Repositories\TokenVerificationRepository;
use BibliOlen\Services\Repositories\UserRepository;
use BibliOlen\Tools\Database;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;
use DateInterval;
use DateTime;
use Exception;


class ForgotPasswordController
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
            category: "Auth",
            view: 'forgotPassword'
        );
    }

    public function renderWithToken(string $resetToken): void
    {
        if ($resetToken == '') {
            header('Location: /login');
            return;
        }

        $getToken = TokenVerificationRepository::getInstance(database: $this->db);
        $checkToken = $getToken->findById($resetToken);
        $currentDate = time();

        try {
            $expirationTime = new DateTime($checkToken[0]["created_at"]);
            $expirationTime->add(new DateInterval('PT2H'));
        } catch (Exception) {
            Tools::setFlash("error", "Une erreur est survenue lors de la réinitialisation du mot de passe.");
            header('Location: /forgot-password');
            return;
        }

        if ($checkToken[0]["used"] || $expirationTime->getTimestamp() <= $currentDate) {
            Tools::setFlash("error", "Le lien de réinitialisation du mot de passe a expiré.");
            header('Location: /forgot-password');
            return;
        }

        View::render(
            category: "Auth",
            view: 'changePassword',
            data: [
                'token' => $resetToken
            ]
        );
    }

    public function handle(): void
    {
        $data = $_POST;
        $user = UserRepository::getInstance(database: $this->db);
        $findByMail = $user->getByEmail(strtolower($data["email"]));

        if ($findByMail == '') {
            Tools::setFlash("error", "Les données saisies sont invalides.");
            header('Location: /forgot-password');
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

        header('Location: /forgot-password');
    }

    public function handleWithToken(string $resetToken): void
    {
        $data = $_POST;

        if ($data["mdp"] != $data["mdpConfirm"]) {
            Tools::setFlash("error", "Le mot de passe et la confirmation ne correspondent pas.");
            header('Location: /forgot-password');
            return;
        }

        if (
            strlen($data["mdp"]) >= 14 && preg_match('/[A-Z]/', $data["mdp"]) &&
            preg_match('/[a-z]/', $data["mdp"]) && preg_match('/[^a-zA-Z0-9]/', $data["mdp"]) &&
            preg_match('/[0-9]/', $data["mdp"])
        ) {
            $crudTokenVerification = CrudTokenVerification::getInstance(database: $this->db);
            $data['token'] = $resetToken;
            $data['used'] = 'true';
            $data["id_utilisateur"] = $crudTokenVerification->editUsed($data);
            $crudUser = CrudUser::getInstance(database: $this->db);
            $data["mdp"] = Tools::hashPassword($data["mdp"]);
            $crudUser->editPassword($data);
            Tools::setFlash("success", "Le mot de passe à bien été modifié.");

            header('Location: /login');
            return;
        }

        Tools::setFlash("error", "Le mot de passe ne respecte pas les critères de sécurité.");
        header('Location: /forgot-password');
    }
}
