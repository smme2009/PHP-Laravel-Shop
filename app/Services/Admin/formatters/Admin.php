<?php

namespace App\Services\Admin\Formatters;

use App\Models\Admin as ModelAdmin;

/**
 * 服務層-格式化-管理員
 */
class Admin
{
    /**
     * 格式化資料
     * 
     * @param ModelAdmin $model Model
     * 
     * @return array 格式化後的資料
     */
    public function formatData(ModelAdmin $model): array
    {
        return [
            'adminId' => $model->admin_id,
            'name' => $model->name,
            'account' => $model->account,
            'status' => $model->status,
        ];
    }
}
