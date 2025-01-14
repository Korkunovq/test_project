<?php
namespace App\Repositories\UserRepositories;

use App\Models\User;

class UserRepositories implements UserInterface
{

    public function create(array $data): User
    {

        return User::create($data);

    }

    public function find(int $id): User
    {

        return User::findOrFail($id);

    }

    public function getInfo($topUsers)
    {

        return User::whereIn('id', $topUsers->pluck('user_id'))->get()->keyBy('id');

    }

}
