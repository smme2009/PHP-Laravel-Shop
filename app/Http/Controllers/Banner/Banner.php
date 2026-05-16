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
        // 接收請求資料
        $data = [
            'name' => request('name'),
            'photoFileId' => request('photoFileId'),
            'url' => request('url'),
            'startAt' => request('startAt'),
            'endAt' => request('endAt'),
            'sort' => request('sort'),
            'status' => request('status'),
        ];

        // 新增橫幅
        $result = $this->srcBanner->create($data);

        // 回傳結果
        return $this->getJsonResponse($result);
    }

    /**
     * 取得橫幅分頁
     * 
     * @return JsonResponse
     */
    public function getPaged(): JsonResponse
    {
        // 取得橫幅分頁
        $result = $this->srcBanner->getPaged();

        // 回傳結果
        return $this->getJsonResponse($result);
    }

    /**
     * 取得橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return JsonResponse
     */
    public function get(int $bannerId): JsonResponse
    {
        // 取得橫幅
        $result = $this->srcBanner->get($bannerId);

        // 回傳結果
        return $this->getJsonResponse($result);
    }

    /**
     * 修改橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return JsonResponse
     */
    public function modify(int $bannerId): JsonResponse
    {
        // 接收請求資料
        $data = [
            'name' => request('name'),
            'photoFileId' => request('photoFileId'),
            'url' => request('url'),
            'startAt' => request('startAt'),
            'endAt' => request('endAt'),
            'sort' => request('sort'),
            'status' => request('status'),
        ];

        // 修改橫幅
        $result = $this->srcBanner->modify($bannerId, $data);

        // 回傳結果
        return $this->getJsonResponse($result);
    }

    /**
     * 移除橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return JsonResponse
     */
    public function remove(int $bannerId): JsonResponse
    {
        // 移除橫幅
        $result = $this->srcBanner->remove($bannerId);

        // 回傳結果
        return $this->getJsonResponse($result);
    }
}