<?php
namespace App\Repositories\PointsHistoryRepositories;

use Carbon\Carbon;

use App\Models\PointsHistory;

interface PointsHistoryInterface
{

    public function create(array $data): PointsHistory;

    public function getUsersTop(Carbon $date);

    public function getUserRank(Carbon $date);
    
}
