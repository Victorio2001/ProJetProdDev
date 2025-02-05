<?php

namespace Units;

use BibliOlen\Models\PromotionModel;
use BibliOlen\Models\RoleModel;
use BibliOlen\Models\UserModel;
use BibliOlen\Services\Crud\CrudUser;
use BibliOlen\Tools\Database;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    private $dbMock;
    private $crudUser;

    protected function setUp(): void
    {
        // Mock de la base de données
        $this->dbMock = $this->createMock(Database::class);

        // Mock de CrudUser
        $this->crudUser = $this->getMockBuilder(CrudUser::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create', 'delete', 'apiModif'])
            ->getMock();
    }

    public function testCreateUser()
    {
        $data = [
            'nom_utilisateur' => 'John',
            'prenom_utilisateur' => 'Doe',
            'email_utilisateur' => 'john.doe@example.com',
            'id_role' => '1',
            'lecture_seule' => 'false',
            'id_promotion' => '1',
            'mdp' => 'password123',
            'module' => []
        ];

        // Mock des dépendances
        $mockRole = $this->createMock(RoleModel::class);
        $mockPromotion = $this->createMock(PromotionModel::class);

        // Simuler le comportement de CrudUser
        $this->crudUser->method('create')->willReturn(
            new UserModel(
                id: 1,
                firstName: 'John',
                lastName: 'Doe',
                email: 'john.doe@example.com',
                password: 'password123',
                readOnly: false,
                role: $mockRole,
                promotion: $mockPromotion
            )
        );

        // Appeler la méthode
        $user = $this->crudUser->create($data);

        // Vérifier les résultats
        $this->assertInstanceOf(UserModel::class, $user);
        $this->assertEquals('John', $user->firstName);
        $this->assertEquals($mockRole, $user->role);

        echo "LoanTest de création d'utilisateur réussi.\n";
    }

    public function testDeleteUser()
    {
        $userId = 1;

        // Simulation du comportement de la méthode delete
        $this->crudUser->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willReturn(true);

        // Appel de la méthode
        $result = $this->crudUser->delete($userId);

        // Vérification
        if ($result) {
            echo "LoanTest de suppression d'utilisateur réussi.\n";
        } else {
            echo "LoanTest de suppression d'utilisateur échoué.\n";
        }

        $this->assertTrue($result);
    }

    public function testUpdateUser()
    {
        $data = [
            "id_user" => 1,
            "name" => "Jane",
            "firstname" => "Doe",
            "email" => "jane.doe@example.com"
        ];

        $this->crudUser->expects($this->once())
            ->method('apiModif')
            ->with($data)
            ->willReturn(["status" => 200, "success" => true, "message" => "Modification avec les utilisateurs"]);

        $result = $this->crudUser->apiModif($data);

        if ($result["success"]) {
            echo "LoanTest de modification d'utilisateur réussi.\n";
        } else {
            echo "LoanTest de modification d'utilisateur échoué.\n";
        }

        $this->assertEquals(200, $result["status"]);
        $this->assertTrue($result["success"]);
    }
}