<?php

namespace App\Http\Repository\Mgmt\Banner;

use App\Models\Banner as ModelBanner;

/**
 * 橫幅
 */
class Banner
{
    public function __construct(
        public ModelBanner $banner,
    ) {

    }

    /**
     * 取得橫幅分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 身品分頁資料
     */
    public function getBannerPage(array $searchData)
    {
        $bannerPage = $this->banner
            ->when(
                $searchData['keyword'],
                function ($query) use ($searchData) {
                    $query->where('name', 'like', '%' . $searchData['keyword'] . '%');
                }
            )
            ->orderByDesc('banner_id')
            ->paginate();

        return $bannerPage;
    }

    /**
     * 設定橫幅資料
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return bool 是否設定成功
     */
    public function setBanner(int $bannerId)
    {
        $banner = $this->banner->find($bannerId);

        if (!$banner) {
            return false;
        }

        $this->banner = $banner;

        return true;
    }

    /**
     * 新增橫幅
     * 
     * @param array $bannerData 橫幅資料
     * 
     * @return false|int 橫幅ID
     */
    public function addBanner(array $bannerData)
    {
        $isSave = $this->saveModel($bannerData);

        if (!$isSave) {
            return false;
        }

        $bannerId = $this->banner->banner_id;

        return $bannerId;
    }

    /**
     * 編輯橫幅
     * 
     * @param array $bannerData 橫幅資料
     * 
     * @return bool 是否編輯成功
     */
    public function editBanner(array $bannerData)
    {
        $isSave = $this->saveModel($bannerData);

        return $isSave;
    }

    /**
     * 刪除橫幅
     * 
     * @return bool 是否刪除成功
     */
    public function deleteBanner()
    {
        $isDelete = $this->banner->delete();

        return $isDelete;
    }

    /**
     * 編輯橫幅狀態
     * 
     * @param bool $status 狀態
     * 
     * @return bool 是否編輯成功
     */
    public function editBannerStatus(bool $status)
    {
        $this->banner->status = $status;

        $isEdit = $this->banner->save();

        return $isEdit;
    }

    /**
     * 儲存橫幅Model
     * 
     * @param array $bannerData 橫幅資料
     * 
     * @return bool 是否儲存成功
     */
    private function saveModel(array $bannerData)
    {
        $this->banner->photo_fid = $bannerData['photoFileId'];
        $this->banner->name = $bannerData['name'];
        $this->banner->url = $bannerData['url'];
        $this->banner->start_at = $bannerData['startTime'];
        $this->banner->end_at = $bannerData['endTime'];
        $this->banner->sort = $bannerData['sort'];
        $this->banner->status = $bannerData['status'];

        $isSave = $this->banner->save();

        return $isSave;
    }
}
