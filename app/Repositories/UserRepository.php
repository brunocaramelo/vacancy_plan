<?php

namespace App\Repositories;

use App\Models\{User, Person};
use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    private $model = User::class;

    public function findByEmail(string $email)
    {
        return $this->model::where('email', $email)
                ->get();
    }
}
