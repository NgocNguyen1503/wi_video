<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\Users\UserRepositoryInterface;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * Implement abstract method and base model
     *
     * @return mixed | model
     */
    public function getModel()
    {
        return User::class;
    }

    // Deploy special methods.
    /**
     * Implement method find user using email
     *
     * @param mixed $email
     * @return null | Collection user
     */
    public function findUserUsingEmail($email)
    {
        return $this->_model->where('email', $email)->first();
    }
}