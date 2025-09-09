<?php

namespace App\Repositories\Banner;

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
}