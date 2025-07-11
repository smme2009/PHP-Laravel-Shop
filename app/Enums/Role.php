<?php

namespace App\Enums;

/**
 * 枚舉-角色
 */
enum Role: int
{
    case ADMIN = 1; // 管理者

    /**
     * 取得角色名稱
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => '管理者',
        };
    }
}
