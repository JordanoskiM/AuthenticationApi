<?php

namespace App\Modules;

use App\Models\User;

class UserManagement implements IUserManagement
{
    private $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    public function getAllUsers()
    {
        return $this->userModel->all();
    }

    public function findUserById($id)
    {
        return $this->userModel->find($id);
    }

    public function update($user, $userData)
    {
        if (!($user instanceof User)) {
            $user = $this->findUserById($user);
        }

        return $user->update($userData);
    }

}
