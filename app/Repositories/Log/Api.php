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
     * 透過讀取狀態取得API紀錄（分組統計）
     *
     * @param bool $isRead 是否已讀
     *
     * @return Collection 統計後的API紀錄
     */
    public function findGroupedByIsRead(bool $isRead): Collection
    {
        $logApiIdsSQL = DB::connection()->getDriverName() === 'pgsql'
            ? DB::raw("STRING_AGG(log_api_id::text, ',') as log_api_ids")
            : DB::raw('GROUP_CONCAT(log_api_id) as log_api_ids');

        return ModelLogApi::select(['uri', 'method'])
            ->addSelect(DB::raw('COUNT(*) as count'))
            ->addSelect(DB::raw('MIN(created_at) as start_time'))
            ->addSelect(DB::raw('MAX(created_at) as end_time'))
            ->addSelect($logApiIdsSQL)
            ->where('is_read', $isRead)
            ->groupBy(['uri', 'method'])
            ->orderByDesc('count')
            ->get();
    }

    /**
     * 更新紀錄的讀取狀態
     * 
     * @param array $logApiIds 紀錄ID
     * @param bool $isRead 是否已讀
     * 
     * @return bool 是否更新成功
     */
    public function updateIsRead(array $logApiIds, bool $isRead): bool
    {
        $updatedCount = ModelLogApi::whereIn('log_api_id', $logApiIds)
            ->update(['is_read' => $isRead]);

        return ($updatedCount === 0) ? false : true;
    }
}
