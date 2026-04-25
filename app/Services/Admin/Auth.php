<?php

namespace App\Services\Admin;

use App\Enums\Role as EnumRole;
use App\Services\Service;
use App\Services\Tool\Jwt;
use App\Services\Tool\Output\Result as OutputResult;

/**
 * 服務層-管理員驗證
 */
class Auth extends Service
{
    /**
     * 建構函式
     *
     * @param Jwt $toolJwt 工具-JWT Token
     */
    public function __construct(
        private readonly Jwt $toolJwt,
    ) {}

    /**
     * 透過JWT Token驗證管理員
     *
     * @param string $jwtToken JWT Token
     *
     * @return OutputResult 處理結果
     */
    public function checkByJwtToken(string $jwtToken): OutputResult
    {
        // 解碼Jwt Token
        $data = $this->toolJwt->decode($jwtToken);

        // 解碼失敗，回傳錯誤資料
        if (empty($data) === true) {
            return $this->toolResult()
                ->setHttpCode(401)
                ->setMessage('驗證失敗，Jwt Token無效')
                ->build();
        }

        // 驗證Jwt Token是否在白名單中
        $isInWhitelist = $this->toolJwt->isInWhitelist($jwtToken);

        // 驗證失敗，回傳錯誤資料
        if ($isInWhitelist === false) {
            return $this->toolResult()
                ->setHttpCode(401)
                ->setMessage('驗證失敗，Jwt Token無效')
                ->build();
        }

        // 驗證角色
        $role = EnumRole::Admin->value;
        $isAdmin = ($data['role'] === $role);

        // 驗證失敗，回傳錯誤資料
        if ($isAdmin === false) {
            return $this->toolResult()
                ->setHttpCode(401)
                ->setMessage('驗證失敗，角色錯誤')
                ->build();
        }

        // 解碼成功，回傳資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('驗證成功')
            ->bulkAddData($data)
            ->build();
    }
}
