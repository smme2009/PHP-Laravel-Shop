<?php

namespace App\Http\Service\Frontend\Banner;

use App\Http\Service\Service;

use App\Http\Repository\Frontend\Banner\Banner as RepoBanner;

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
     * 取得橫幅列表
     * 
     * @return array
     */
    public function getBannerList()
    {
        $list = $this->repoBanner->getBannerList();

        $photoFidList = $list->pluck('photo_fid')->all();
        $fileInfoList = $this->toolFile()->getFileInfoList($photoFidList);

        $bannerList = [];
        foreach ($list as $banner) {
            $fileInfo = $fileInfoList[$banner->photo_fid];

            $bannerList[] = $this->setBanner($banner, $fileInfo);
        }

        return $bannerList;
    }

    /**
     * 設定橫幅資料結構
     * 
     * @param mixed $banner 橫幅資料
     * @param array $fileInfo 橫幅照片檔案資訊
     * 
     * @return array 橫幅資料結構
     */
    private function setBanner(mixed $banner, array $fileInfo)
    {
        $banner = [
            'bannerId' => $banner->banner_id,
            'photoUrl' => $fileInfo['url'],
            'name' => $banner->name,
            'url' => $banner->url,
        ];

        return $banner;
    }
}
