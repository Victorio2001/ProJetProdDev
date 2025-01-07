<?php

namespace BibliOlen\Core;

use BibliOlen\Models\UserModel;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mail;
    private string $mailFrom;
    private string $mailFromName;

    public function __construct()
    {
    }

    private function init(): void
    {
        $this->mail = new PHPMailer(true);

        // server settings
        //$this->mail->SMTPDebug = strtolower(getenv('APP_ENV')) === 'dev' ? 3 : 0;
        $this->mail->isSMTP();
        $this->mail->Host = getenv('MAIL_HOST');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = getenv('MAIL_USERNAME');
        $this->mail->Password = getenv('MAIL_PASSWORD');
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port = getenv('MAIL_PORT');

        // email settings
        $this->mail->isHTML(true);
        $this->mail->CharSet = 'UTF-8';

        $this->mailFrom = getenv('MAIL_USERNAME');
        $this->mailFromName = getenv('APP_NAME');
    }

    public function sendRegisterMail(UserModel $user): array
    {
        $this->init();

        try {
            $this->mail->setFrom($this->mailFrom, $this->mailFromName);
            $this->mail->addAddress($user->email, $user->firstName . ' ' . $user->lastName);

            $this->mail->AddEmbeddedImage(Tools::getFileFromRoot(['public', 'img', 'logos', 'logo-email.png']), 'logo');
            $this->mail->AddEmbeddedImage(Tools::getFileFromRoot(['public', 'img', 'emails', 'inscription.png']), 'banniere');

            // load email template
            $htmlTemplate = View::getEmailTemplate('register');
            $htmlTemplate = str_replace('{{logo}}', "cid:logo", $htmlTemplate);
            $htmlTemplate = str_replace('{{banniere}}', "cid:banniere", $htmlTemplate);
            $htmlTemplate = str_replace('{{username}}', $user->firstName . ' ' . $user->lastName, $htmlTemplate);
            $htmlTemplate = str_replace('{{email}}', $user->email, $htmlTemplate);
            $htmlTemplate = str_replace('{{resetLink}}', getenv('APP_URL') . '/forgot-password/' , $htmlTemplate);

            $this->mail->Subject = 'Inscription à la bibliothèque de l\'école';
            $this->mail->Body = $htmlTemplate;

            $this->mail->send();

            return array('status' => 'success', 'message' => 'Le mail a bien été envoyé');
        } catch (Exception) {
            return array('status' => 'error', 'message' => "Le mail n'a pas pu être envoyé. Erreur: {$this->mail->ErrorInfo}");
        }
    }

    public function sendResetPasswordMail(UserModel $user, string $token): array
    {
        $this->init();

        try {

            $this->mail->setFrom($this->mailFrom, $this->mailFromName);
            $this->mail->addAddress($user->email, $user->firstName . ' ' . $user->lastName);

            $this->mail->AddEmbeddedImage(Tools::getFileFromRoot(['public', 'img', 'logos', 'logo-email.png']), 'logo');
            $this->mail->AddEmbeddedImage(Tools::getFileFromRoot(['public', 'img', 'emails', 'reset-password.png']), 'banniere');

            // load email template
            $htmlTemplate = View::getEmailTemplate('reset-password');
            $htmlTemplate = str_replace('{{logo}}', "cid:logo", $htmlTemplate);
            $htmlTemplate = str_replace('{{banniere}}', "cid:banniere", $htmlTemplate);
            $htmlTemplate = str_replace('{{username}}', $user->firstName . ' ' . $user->lastName, $htmlTemplate);
            $htmlTemplate = str_replace('{{resetLink}}', getenv('APP_URL') . '/forgot-password/' . $token, $htmlTemplate);

            $this->mail->Subject = 'Réinitialisation de votre mot de passe';
            $this->mail->Body = $htmlTemplate;

            $this->mail->send();

            return array('status' => 'success', 'message' => 'Le mail a bien été envoyé');
        } catch (Exception) {
            return array('status' => 'error', 'message' => "Le mail n'a pas pu être envoyé. Erreur: {$this->mail->ErrorInfo}");
        }
    }

}
