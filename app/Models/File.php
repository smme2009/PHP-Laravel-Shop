<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 檔案
 */
class File extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'file';

    // 主鍵名稱
    protected $primaryKey = 'file_id';
}
