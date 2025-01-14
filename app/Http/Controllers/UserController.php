<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\PointsRequest;

use App\Models\User;

use App\Services\User\UserService;


class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ){}

    public function create(UserRequest $request){

        return $this->userService->create($request);

    }

    public function addPoints(PointsRequest $request, int $id){

        return $this->userService->addPoints($request, $id);

    }

    public function getUsersTop(Request $request){

        return $this->userService->getUsersTop($request);

    }

    public function getUserRank(Request $request, int $id){

        return $this->userService->getUserRank($request, $id);

    }
}
