<?php

namespace Units;

use BibliOlen\Models\UserModel;
use BibliOlen\Models\RoleModel;
use BibliOlen\Models\PromotionModel;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase // Correction ici (UsersTest au lieu de UserTest)
{
    public function testConstructor() // Vérifier le constructeur
    {
        $id = 1;
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@example.com';
        $password = 'securepassword';
        $readOnly = true;
        $role = RoleModel::getDefault();
        $promotion = PromotionModel::getDefault();

        $user = new UserModel(
            $id,
            $firstName,
            $lastName,
            $email,
            $password,
            $readOnly,
            $role,
            $promotion
        );

        $this->assertSame($id, $user->id);
        $this->assertSame($firstName, $user->firstName);
        $this->assertSame($lastName, $user->lastName);
        $this->assertSame($email, $user->email);
        $this->assertSame($readOnly, $user->readOnly);
        $this->assertSame($role, $user->role);
        $this->assertSame($promotion, $user->promotion);
        $this->assertSame($password, $user->getPassword());
    }

    public function testGetDefault() // Retourne un utilisateur par défaut
    {
        $defaultUser = UserModel::getDefault();

        $this->assertSame(0, $defaultUser->id);
        $this->assertSame('', $defaultUser->firstName);
        $this->assertSame('', $defaultUser->lastName);
        $this->assertSame('', $defaultUser->email);
        $this->assertSame('', $defaultUser->getPassword());
        $this->assertFalse($defaultUser->readOnly);
        $this->assertInstanceOf(RoleModel::class, $defaultUser->role);
        $this->assertInstanceOf(PromotionModel::class, $defaultUser->promotion);
    }
}

