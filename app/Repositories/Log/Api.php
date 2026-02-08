<?php

namespace App\Repositories\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Models\LogApi as ModelLogApi;

/**
 *  資料存取層-紀錄-API
 */
class Api
{
    /**
     * 透過時間區間取得統計後的API紀錄
     * 
     * @param string $startTime 開始時間
     * @param string $endTime 結束時間
     * 
     * @return Collection 被統計的API紀錄
     */
    public function findGroupedByTimeRange(string $startTime, string $endTime): Collection
    {
        return ModelLogApi::select(['uri', 'method'])
            ->addSelect(DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startTime, $endTime])
            ->groupBy(['uri', 'method'])
            ->get();
    }
}