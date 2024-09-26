<?php

namespace App\Http\Service\Shop\Banner;

use App\Http\Service\Service;
use App\Http\Repository\Shop\Banner\Banner as RepoBanner;
use App\Models\Banner as ModelBanner;

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
    public function getBannerList(): array
    {
        $list = $this->repoBanner
            ->getBannerList();

        $bannerList = [];
        foreach ($list as $banner) {
            $bannerList[] = $this->setBanner($banner);
        }

        return $bannerList;
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
        $photoUrl = $this->toolFile()
            ->getFileUrl($banner->bannerPhoto->path);

        $banner = [
            'bannerId' => $banner->banner_id,
            'photoUrl' => $photoUrl,
            'name' => $banner->name,
            'url' => $banner->url,
        ];

        return $banner;
    }
}
