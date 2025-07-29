<?php

namespace App\Services\Account;

use App\Services\Service;
use App\Services\Tool\Jwt;
use App\Services\Tool\Output\Result as OutputResult;

/**
 * 服務層-帳號登出
 */
class Logout extends Service
{
    /**
     * 建構子
     * 
     * @param Jwt $toolJwt 工具-JWT Token
     */
    public function __construct(
        private readonly Jwt $toolJwt,
    ) {
    }

    /**
     * 登出帳號
     * 
     * @return OutputResult 處理結果
     */
    public function logout(): OutputResult
    {
        // 取得JWT Token
        $accountAuth = context()->get('accountAuth');
        $jwtToken = $accountAuth['jwtToken'];

        // 將JWT Token從白名單中移除
        $result = $this->toolJwt->removeFromWhitelist($jwtToken);

        // 移除失敗，回傳錯誤資料
        if ($result === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('登出失敗，Jwt Token無效')
                ->build();
        }

        // 登出成功，回傳成功資料
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('登出成功')
            ->build();
    }
}