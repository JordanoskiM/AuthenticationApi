<?php

namespace App\Modules;

interface IUserManagement
{

    public function getAllUsers();

    public function findUserById($id);

    public function update($user,  $userData);

}
