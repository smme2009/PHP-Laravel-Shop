<?php

namespace App\Services\Account;

use App\Services\Service;
use App\Services\Tool\Jwt;
use App\Services\Tool\Output\Result as OutputResult;

/**
 * 服務層-帳號驗證
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
    ) {
    }

    /**
     * 透過JWT Token驗證帳號
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

        // 解碼成功，回傳資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('驗證成功')
            ->bulkAddData($data)
            ->build();
    }
}
