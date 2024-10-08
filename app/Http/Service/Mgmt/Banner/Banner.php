<?php

namespace App\Http\Service\Mgmt\Banner;

use App\Http\Service\Service;
use App\Http\Repository\Mgmt\Banner\Banner as RepoBanner;
use App\Models\Banner as ModelBanner;
use App\Tool\Validation\Result;

/**
 * 橫幅
 */
class Banner extends Service
{
    public function __construct(
        private RepoBanner $repoBanner,
    ) {
    }

    /**  
     * 取得橫幅分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return array 橫幅分頁
     */
    public function getBannerPage(array $searchData): array
    {
        $page = $this->repoBanner
            ->getBannerPage($searchData);

        $data = [];
        foreach ($page as $banner) {
            $data[] = $this->setBanner($banner);
        }

        $bannerPage = [
            'data' => $data,
            'total' => $page->total(),
        ];

        return $bannerPage;
    }

    /**
     * 取得橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return bool|array 橫幅
     */
    public function getBanner(int $bannerId): bool|array
    {
        $isSet = $this->repoBanner
            ->setBanner($bannerId);

        if ($isSet === false) {
            return false;
        }

        $bannerModel = $this->repoBanner->banner;
        $banner = $this->setBanner($bannerModel);

        return $banner;
    }

    /**  
     * 驗證資料
     * 
     * @param array $bannerData 橫幅資料
     * 
     * @return Result 驗證結果
     */
    public function validateData(array $bannerData): Result
    {
        // 驗證規則
        $rule = [
            'photoFileId' => ['required', 'integer', 'exists:file,file_id'],
            'name' => ['required', 'string'],
            'url' => ['nullable', 'string', 'url'],
            'startTime' => ['nullable', 'date', 'date_format:Y-m-d H:i:s'],
            'endTime' => ['nullable', 'date', 'date_format:Y-m-d H:i:s', 'after:startTime'],
            'sort' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'boolean'],
        ];

        $result = $this->toolValidation()
            ->validateData($bannerData, $rule);

        return $result;
    }

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
     * 上傳橫幅圖片
     * 
     * @param mixed $photo 照片
     * 
     * @return bool|array 圖片資訊
     */
    public function uploadBannerPhoto(mixed $photo): bool|array
    {
        $fileInfo = $this->toolFile()
            ->uploadFile($photo, 'banner');

        return $fileInfo;
    }

    /**
     * 新增橫幅
     * 
     * @param array $bannerData 橫幅資料
     * 
     * @return bool|int 橫幅ID
     */
    public function addBanner(array $bannerData): bool|int
    {
        // 新增橫幅
        $banner_id = $this->repoBanner
            ->addBanner($bannerData);

        return $banner_id;
    }

    /**
     * 編輯橫幅
     * 
     * @param int $bannerId 橫幅ID
     * @param array $bannerData 橫幅資料
     * 
     * @return bool 是否編輯成功
     */
    public function editBanner(int $bannerId, array $bannerData): bool
    {
        $isSet = $this->repoBanner
            ->setBanner($bannerId);

        if ($isSet === false) {
            return false;
        }

        // 編輯橫幅
        $isEdit = $this->repoBanner
            ->editBanner($bannerData);

        return $isEdit;
    }

    /**
     * 刪除橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return bool 是否刪除成功
     */
    public function deleteBanner(int $bannerId): bool
    {
        $isSet = $this->repoBanner
            ->setBanner($bannerId);

        if ($isSet === false) {
            return false;
        }

        // 刪除橫幅
        $isDelete = $this->repoBanner
            ->deleteBanner();

        return $isDelete;
    }

    /**
     * 編輯橫幅狀態
     * 
     * @param int $bannerId 橫幅ID
     * @param bool $status 狀態
     * 
     * @return bool 是否編輯成功
     */
    public function editBannerStatus(int $bannerId, bool $status): bool
    {
        $isSet = $this->repoBanner
            ->setBanner($bannerId);

        if ($isSet === false) {
            return false;
        }

        $isEdit = $this->repoBanner
            ->editBannerStatus($status);

        return $isEdit;
    }

    /**
     * 設定橫幅資料結構
     * 
     * @param ModelBanner $banner 橫幅資料
     * 
     * @return array 橫幅資料結構
     */
    private function setBanner(ModelBanner $banner): array
    {
        // 上架時間
        $startTime = $banner->start_at;
        $startTime = is_null($startTime)
            ? null
            : strtotime($startTime);

        // 下架時間
        $endTime = $banner->end_at;
        $endTime = is_null($endTime)
            ? null
            : strtotime($endTime);

        // 圖片網址
        $photoUrl = $this->toolFile()
            ->getFileUrl($banner->bannerPhoto->path);

        $banner = [
            'bannerId' => $banner->banner_id,
            'photoFileId' => $banner->photo_fid,
            'photoUrl' => $photoUrl,
            'name' => $banner->name,
            'url' => $banner->url,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'sort' => $banner->sort,
            'status' => (bool) $banner->status,
        ];

        return $banner;
    }
}
