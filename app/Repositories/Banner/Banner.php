<?php

namespace App\Repositories\Banner;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Banner as ModelBanner;

/**
 * 資料存取層-橫幅
 */
class Banner
{
    /**
     * 新增橫幅
     * 
     * @param array $data 橫幅資料
     * 
     * @return ?ModelBanner Model
     */
    public function insert(array $data): ?ModelBanner
    {
        // 新增橫幅
        $model = new ModelBanner();
        $model->name = $data['name'];
        $model->photo_file_id = $data['photoFileId'];
        $model->url = $data['url'];
        $model->start_at = $data['startAt'];
        $model->end_at = $data['endAt'];
        $model->sort = $data['sort'];
        $model->status = $data['status'];
        $model->account_id = $data['accountId'];
        $result = $model->save();

        // 新增失敗
        if ($result === false) {
            return null;
        }

        return $model;
    }

    /**
     * 透過帳號ID取得橫幅分頁
     * 
     * @param int $accountId 帳號ID
     * 
     * @return LengthAwarePaginator 橫幅資料
     */
    public function findPagedByAccountId(int $accountId): LengthAwarePaginator
    {
        return ModelBanner::where('account_id', $accountId)->paginate();
    }

    /**
     * 透過橫幅ID取得橫幅資料
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return ?ModelBanner Model
     */
    public function findOneByBannerId(int $bannerId): ?ModelBanner
    {
        return ModelBanner::where('banner_id', $bannerId)->first();
    }
}