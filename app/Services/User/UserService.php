<?php
namespace App\Services\User;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Repositories\UserRepositories\UserRepositories;
use App\Repositories\PointsHistoryRepositories\PointsHistoryRepositories;

class UserService
{
    public function __construct(
        protected UserRepositories $userRepositories,
        protected PointsHistoryRepositories $pointsHistoryRepositories,
    ){}

    public function create($request)
    {
        $user = $this->userRepositories->create([
            'name' => $request->name
        ]);

        return response()->json([
            'id' => $user->id,
            'username' => $user->name
        ], 201);
    }

    public function addPoints($request, $id)
    {
        $user = $this->userRepositories->find($id);

        if($user){
            DB::beginTransaction();
            try {
                $points = $this->pointsHistoryRepositories->create([
                    'user_id' => $user->id,
                    'points' => $request->points,
                ]);

                $user->rating_points += $request->points;
                $user->save();

                DB::commit();

                return response()->json([
                    'message' => 'Очки успешно добавлены.',
                ], 200);
            }
            catch(\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Ошибка при добавлении очков.',
                ], 500);
            }

        }

        return response()->json([
            'message' => 'Пользователь не найден.',
        ], 404);
    }

    public function getUsersTop($request)
    {
        switch ($request->period) {
            case 'day':
                $startDate = Carbon::now()->subDay();
                break;
            case 'week':
                $startDate = Carbon::now()->subWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->subMonth();
                break;
            default:
                return response()->json(['message' => 'Некорректные параметры запроса.'], 400);
        }

        $topUsers = $this->pointsHistoryRepositories->getUsersTop($startDate);

        $users = $this->userRepositories->getInfo($topUsers);

        $scores = [];

        foreach ($topUsers as $index => $item) {
            $user = $users->get($item->user_id);
            $scores[$index + 1] = [
                'user_id' => $user->id,
                'username' => $user->name,
                'score' => $item->points,
            ];
        }

        return response()->json([
            'period' => $request->period,
            'scores' => $scores,
        ]);
    }

    public function getUserRank($request, $id){
        switch ($request->period) {
            case 'day':
                $startDate = Carbon::now()->subDay();
                break;
            case 'week':
                $startDate = Carbon::now()->subWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->subMonth();
                break;
            default:
                return response()->json(['message' => 'Некорректные параметры запроса.'], 400);
        }

        $userScores = $this->pointsHistoryRepositories->getUserRank($startDate);

        $rank = null;
        
        foreach ($userScores as $index => $item) {
            if ($item->user_id == $id) {
                $rank = $index + 1;
                break;
            }
        }

        if (is_null($rank)) {
            return response()->json(['message' => 'Пользователь не найден.'], 404);
        }

        $userScore = $userScores->firstWhere('user_id', $id);

        $score = $userScore ? $userScore->points : 0;

        return response()->json([
            'user_id' => $id,
            'period' => $request->period,
            'score' => $score,
            'rank' => $rank,
        ]);
    }
}

