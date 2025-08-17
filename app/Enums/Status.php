<?php

namespace App\Enums;

/**
 * 枚舉-狀態
 */
enum Status: int
{
    case DISABLED = 0; // 停用
    case ENABLED = 1; // 啟用

    /**
     * 取得狀態名稱
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::DISABLED => '停用',
            self::ENABLED => '啟用',
        };
    }
}
