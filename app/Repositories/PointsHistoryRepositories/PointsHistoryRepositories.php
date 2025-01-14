<?php
namespace App\Repositories\PointsHistoryRepositories;

use Carbon\Carbon;

use App\Models\PointsHistory;

class PointsHistoryRepositories implements PointsHistoryInterface
{

    public function create(array $data): PointsHistory
    {

        return PointsHistory::create($data);

    }

    public function getUsersTop(Carbon $date)
    {

        return PointsHistory::select('user_id', \DB::raw('SUM(points) as points'))
                                ->where('created_at', '>=', $date)
                                ->groupBy('user_id')
                                ->orderBy('points', 'desc')
                                ->limit(10)
                                ->get();

    }

    public function getUserRank(Carbon $date)
    {

        return PointsHistory::select('user_id', \DB::raw('SUM(points) as points'))
                                ->where('created_at', '>=', $date)
                                ->groupBy('user_id')
                                ->orderBy('points', 'desc')
                                ->limit(10)
                                ->get();

    }

}
