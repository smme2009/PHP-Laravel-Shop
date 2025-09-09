<?php

namespace App\Http\Controllers\Banner;

use Illuminate\Http\JsonResponse;
use App\Services\Banner\Banner as SrcBanner;
use App\Http\Controllers\Controller;

/**
 * 控制層-橫幅
 */
class Banner extends Controller
{
    /**
     * 建構子
     * 
     * @param SrcBanner $srcBanner 服務層-橫幅
     */
    public function __construct(
        private readonly SrcBanner $srcBanner,
    ) {
    }

    /**
     * 新增橫幅
     * 
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        // 取得帳號資料
        $accountAuth = context()->get('accountAuth');

        // 接收請求資料
        $data = [
            'name' => request('name'),
            'photoFileId' => request('photoFileId'),
            'url' => request('url'),
            'startAt' => request('startAt'),
            'endAt' => request('endAt'),
            'sort' => request('sort'),
            'status' => request('status'),
            'accountId' => $accountAuth['accountId']
        ];

        // 新增橫幅
        $result = $this->srcBanner->create($data);

        // 回傳結果
        return $this->toolResponse()
            ->setHttpCodeByStatus($result->status)
            ->setMessage($result->message)
            ->bulkAddData($result->data)
            ->build();
    }
}