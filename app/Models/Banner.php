<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 橫幅
 */
class Banner extends Model
{
    use HasFactory;

    // 軟刪除
    use SoftDeletes;

    // 資料表名稱
    protected $table = 'banner';

    // 主鍵名稱
    protected $primaryKey = 'banner_id';

    /**
     * 取得橫幅圖片
     * 
     * @return BelongsTo
     */
    public function bannerPhoto(): BelongsTo
    {
        $bannerPhoto = $this->belongsTo(File::class, 'photo_fid', 'file_id');

        return $bannerPhoto;
    }
}
