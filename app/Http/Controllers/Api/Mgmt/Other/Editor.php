<?php

namespace App\Http\Controllers\Api\Mgmt\Other;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Mgmt\Other\Editor as SrcEditor;

/**
 * 編輯器
 */
class Editor extends Controller
{
    public function __construct(
        private SrcEditor $srcEditor,
    ) {
    }

    /**
     * 上傳商品圖片
     * 
     * @return JsonResponse
     */
    public function uploadEditorPhoto(): JsonResponse
    {
        $photo = request()->file('photo');

        $result = $this->srcEditor
            ->validatePhoto($photo);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
                ->get();

            return $response;
        }

        $fileInfo = $this->srcEditor
            ->uploadEditorPhoto($photo);

        if ($fileInfo === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('上傳圖片失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功上傳圖片')
            ->setData([
                'fileInfo' => $fileInfo,
            ])
            ->get();

        return $response;
    }
}
