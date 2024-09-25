<?php

namespace App\Http\Service\Mgmt\Other;

use App\Http\Service\Service;
use App\Tool\Validation\Result;

/**
 * 編輯器
 */
class Editor extends Service
{
    /**  
     * 驗證照片
     * 
     * @param mixed $photo 照片
     * 
     * @return Result 驗證結果
     */
    public function validatePhoto(mixed $photo): Result
    {
        // 驗證資料
        $data = [
            'photo' => $photo,
        ];

        // 驗證規則
        $rule = [
            'photo' => ['required', 'image', 'max:10240'],
        ];

        $result = $this->toolValidation()
            ->validateData($data, $rule);

        return $result;
    }

    /**
     * 上傳照片
     * 
     * @param mixed $photo 照片
     * 
     * @return bool|array 照片資訊
     */
    public function uploadEditorPhoto(mixed $photo): bool|array
    {
        $fileInfo = $this->toolFile()
            ->uploadFile($photo, 'editor/photo');

        return $fileInfo;
    }
}
