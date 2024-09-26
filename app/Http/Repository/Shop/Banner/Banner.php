<?php

namespace App\Http\Repository\Shop\Banner;

use Illuminate\Database\Eloquent\Collection;
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
     * 取得橫幅列表
     * 
     * @return Collection 橫幅列表資料
     */
    public function getBannerList(): Collection
    {
        $bannerList = $this->banner
            ->where(function ($query) {
                $query->where('start_at', '>=', time())
                    ->orWhereNull('start_at');
            })
            ->where(function ($query) {
                $query->where('end_at', '<=', time())
                    ->orWhereNull('end_at');
            })
            ->where('status', true)
            ->orderBy('sort')
            ->get();

        return $bannerList;
    }
}
