<?php

namespace App\Http\Controllers\Api\Mgmt\Banner;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Mgmt\Banner\Banner as SrcBanner;

/**
 * 橫幅
 */
class Banner extends Controller
{
    public function __construct(
        private SrcBanner $srcBanner,
    ) {
    }

    /**
     * 取得橫幅分頁
     * 
     * @return JsonResponse
     */
    public function getBannerPage(): JsonResponse
    {
        // 取得搜尋資料
        $searchData = [
            'keyword' => request()->get('keyword'),
        ];

        $bannerPage = $this->srcBanner
            ->getBannerPage($searchData);

        $response = $this->toolResponseJson()
            ->setMessage('成功取得橫幅分頁資料')
            ->setData([
                'bannerPage' => $bannerPage,
            ])
            ->get();

        return $response;
    }

    /**
     * 取得橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return JsonResponse
     */
    public function getBanner(int $bannerId): JsonResponse
    {
        $banner = $this->srcBanner
            ->getBanner($bannerId);

        if ($banner === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(404)
                ->setMessage('取得橫幅資料失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setMessage('成功取得橫幅資料')
            ->setData([
                'banner' => $banner,
            ])
            ->get();

        return $response;
    }

    /**
     * 上傳橫幅圖片
     * 
     * @return JsonResponse
     */
    public function uploadBannerPhoto(): JsonResponse
    {
        $photo = request()->file('photo');

        $result = $this->srcBanner
            ->validatePhoto($photo);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
                ->get();

            return $response;
        }

        $fileInfo = $this->srcBanner
            ->uploadBannerPhoto($photo);

        if ($fileInfo === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('上傳橫幅圖片失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功上傳橫幅圖片')
            ->setData([
                'fileInfo' => $fileInfo,
            ])
            ->get();

        return $response;
    }

    /**
     * 新增橫幅
     * 
     * @return JsonResponse
     */
    public function addBanner(): JsonResponse
    {
        $bannerData = $this->setBannerData();

        $result = $this->srcBanner
            ->validateData($bannerData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData(['errorList' => $result->error])
                ->get();

            return $response;
        }

        $bannerId = $this->srcBanner
            ->addBanner($bannerData);

        if ($bannerId === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('新增橫幅失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功新增橫幅')
            ->setData([
                'bannerId' => $bannerId,
            ])
            ->get();

        return $response;
    }

    /**
     * 編輯橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return JsonResponse
     */
    public function editBanner(int $bannerId): JsonResponse
    {
        $bannerData = $this->setBannerData();

        $result = $this->srcBanner
            ->validateData($bannerData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData(['errorList' => $result->error])
                ->get();

            return $response;
        }

        $isEdit = $this->srcBanner
            ->editBanner($bannerId, $bannerData);

        if ($isEdit === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯橫幅失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功編輯橫幅')
            ->get();

        return $response;
    }

    /**
     * 刪除橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return JsonResponse
     */
    public function deleteBanner(int $bannerId): JsonResponse
    {
        $isDelete = $this->srcBanner
            ->deleteBanner($bannerId);

        if ($isDelete === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('刪除橫幅失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功刪除橫幅')
            ->get();

        return $response;
    }

    /**
     * 編輯橫幅狀態
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return JsonResponse
     */
    public function editBannerStatus(int $bannerId): JsonResponse
    {
        $status = request()->get('status');

        $isEdit = $this->srcBanner
            ->editBannerStatus($bannerId, $status);

        if ($isEdit === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯橫幅狀態失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功編輯橫幅狀態')
            ->get();

        return $response;
    }

    /**
     * 設定橫幅資料
     * 
     * @return array 橫幅資料
     */
    private function setBannerData(): array
    {
        $bannerData = [
            'photoFileId' => request()->get('photoFileId'),
            'name' => request()->get('name'),
            'url' => request()->get('url'),
            'startTime' => request()->get('startTime'),
            'endTime' => request()->get('endTime'),
            'sort' => request()->get('sort'),
            'status' => request()->get('status'),
        ];

        return $bannerData;
    }
}
