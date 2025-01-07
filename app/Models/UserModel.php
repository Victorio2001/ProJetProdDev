<?php

namespace BibliOlen\Models;

class UserModel
{
    public int $id;
    public string $firstName;
    public string $lastName;
    public string $email;
    private string $password;
    public bool $readOnly;
    public RoleModel $role;
    public PromotionModel $promotion;


    public function __construct(int $id, string $firstName, string $lastName, string $email, string $password, bool $readOnly, RoleModel $role, PromotionModel $promotion)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->readOnly = $readOnly;
        $this->role = $role;
        $this->promotion = $promotion;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function getDefault(): UserModel
    {
        return new UserModel(0, '', '', '', '', false, RoleModel::getDefault(), PromotionModel::getDefault());
    }
}
