<?php

namespace App\Enums;

/**
 * 枚舉-角色
 */
enum Role: int
{
    case BUYER = 1; // 買家
    case SELLER = 2; // 賣家

    /**
     * 取得角色名稱
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::BUYER => '買家',
            self::SELLER => '賣家',
        };
    }
}
