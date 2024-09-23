<?php

namespace App\Http\Controllers\Api\Shop\Banner;

use App\Http\Controllers\Controller;

use App\Http\Service\Shop\Banner\Banner as SrcBanner;

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
     * 取得橫幅列表
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBannerList()
    {
        $bannerList = $this->srcBanner->getBannerList();

        $response = $this->toolResponseJson()
            ->setMessage('成功取得橫幅列表資料')
            ->setData([
                'bannerList' => $bannerList,
            ])
            ->get();

        return $response;
    }
}
