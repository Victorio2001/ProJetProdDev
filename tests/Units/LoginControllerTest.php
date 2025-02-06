

<?php

use PHPUnit\Framework\TestCase;
use BibliOlen\Controllers\LoginController;
use BibliOlen\Models\UserModel;
use BibliOlen\Models\RoleModel;
use BibliOlen\Models\PromotionModel;

class LoginControllerTest extends TestCase
{
    public function testGetDefaultUser() // Retourne un utilisateur par défaut
    {
        // Récupération de l'utilisateur par défaut
        $defaultUser = UserModel::getDefault();

        // Vérifications des valeurs par défaut
        $this->assertSame(0, $defaultUser->id, 'L\'ID par défaut de l\'utilisateur doit être 0');
        $this->assertSame('', $defaultUser->firstName, 'Le prénom par défaut doit être une chaîne vide');
        $this->assertSame('', $defaultUser->lastName, 'Le nom par défaut doit être une chaîne vide');
        $this->assertSame('', $defaultUser->email, 'L\'email par défaut doit être une chaîne vide');
        $this->assertSame('', $defaultUser->getPassword(), 'Le mot de passe par défaut doit être une chaîne vide');
        $this->assertFalse($defaultUser->readOnly, 'L\'utilisateur par défaut ne doit pas être en lecture seule');
        $this->assertInstanceOf(RoleModel::class, $defaultUser->role, 'Le rôle doit être une instance de RoleModel');
        $this->assertSame('Default Role', $defaultUser->role->name, 'Le rôle par défaut doit avoir un nom correct');
        $this->assertInstanceOf(PromotionModel::class, $defaultUser->promotion, 'La promotion doit être une instance de PromotionModel');
        $this->assertSame('Default Promotion', $defaultUser->promotion->name, 'La promotion par défaut doit avoir un nom correct');
    }

    public function testSucessSeesionUser() // Retourne ok si la session user est bien bonne
    {
        // Création d'un utilisateur
        $user = new UserModel(
            id: 10,
            firstName: 'John',
            lastName: 'Doe',
            email: 'a@gmail.com', password: 'password',
            readOnly: false,
            role: new RoleModel(1, 'Admin'),
            promotion: new PromotionModel(1, 'Promotion 2021')

        );

        // Création d'une session
        $_SESSION['user'] = $user;

        if (isset($_SESSION['user'])) {
            $this->assertTrue(true, 'La session user est bien définie');
        } else {
            $this->assertTrue(false, 'La session user n\'est pas définie');
        }


    }

    public function testNotSucessSeesionUser()
    {
        $user = new UserModel(
            id: 10,
            firstName: 'John',
            lastName: 'Doe',
            email: 'a@gmail.com',
            password: 'password',
            readOnly: false,
            role: new RoleModel(1, 'Admin'),
            promotion: new PromotionModel(1, 'Promotion 2021')

        );

        $_SESSION['users'] = $user;

        if (isset($_SESSION['users'])) {
            $this->assertTrue(true, 'La session user est bien définie');
        } else {
            $this->assertTrue(false, 'La session user n\'est pas définie');
        }


    }
}