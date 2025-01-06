<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// 帳號
class Account extends Authenticatable
{
    use HasFactory;

    /**
     * 資料表名稱
     */
    protected $table = 'account';

    /**
     * 主鍵
     */
    protected $primaryKey = 'account_id';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * 取得角色
     * 
     * @return BelongsToMany
     */
    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'account_role', 'account_id', 'role_id');
    }
}
