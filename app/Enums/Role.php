<?php

namespace App\Enums;

/**
 * 枚舉-角色
 */
enum Role: string
{
    case Admin = 'admin'; // 管理員
    case Member = 'member'; // 會員

    /**
     * 取得角色名稱
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => '管理員',
            self::Member => '會員',
        };
    }
}
