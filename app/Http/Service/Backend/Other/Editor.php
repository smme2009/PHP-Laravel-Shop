<?php

namespace App\Http\Service\Backend\Other;

use App\Http\Service\Service;

/**
 * 編輯器
 */
class Editor extends Service
{
    public function __construct()
    {
    }

    /**  
     * 驗證照片
     * 
     * @param mixed $photo 照片
     * 
     * @return \App\Tool\Validation\Result 驗證結果
     */
    public function validatePhoto(mixed $photo)
    {
        // 驗證資料
        $data = [
            'photo' => $photo,
        ];

        // 驗證規則
        $rule = [
            'photo' => ['required', 'image', 'max:10240'],
        ];

        $result = $this->toolValidation()->validateData($data, $rule);

        return $result;
    }

    /**
     * 上傳照片
     * 
     * @param mixed $photo 照片
     * 
     * @return array
     */
    public function uploadEditorPhoto(mixed $photo)
    {
        $fileInfo = $this->toolFile()->uploadFile($photo, 'editor/photo');

        return $fileInfo;
    }
}
