<?php

namespace App\Repositories\Users;

interface UserRepositoryInterface
{
    // Define Specialized methods.
    // Define function get user using email
    public function findUserUsingEmail($email);
}