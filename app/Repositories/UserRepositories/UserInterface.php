<?php
namespace App\Repositories\UserRepositories;

use App\Models\User;

interface UserInterface
{

    public function create(array $data): User;

    public function find(int $id): User;

    public function getInfo($topUsers);
    
}
