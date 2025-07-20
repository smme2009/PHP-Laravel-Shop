<?php

namespace App\Services\Tool;

use Exception;
use Firebase\JWT\JWT as FirebaseJwt;
use Firebase\JWT\Key;

/**
 * JWT Token建構工具
 */
class Jwt
{
    /**
     * JWT密鑰
     * @var string
     */
    private string $key;

    /**
     * 建構子
     */
    public function __construct()
    {
        $this->key = env('APP_KEY');
    }

    /**
     * 編碼 JWT Token
     * 
     * @param array $data 資料
     * 
     * @return string JWT Token
     */
    public function encode(array $data): string
    {
        $timeNow = time();
        $timeLimit = $timeNow + 86400;

        $payload = [
            'iss' => env('APP_URL'), // 發行方
            'aud' => env('FRONT_URL'), // 使用方
            'iat' => $timeNow, // 發行時間
            'nbf' => $timeNow, // 生效時間
            'exp' => $timeLimit, // 失效時間
            ...$data,
        ];

        $jwtToken = '';
        try {
            // 編碼 JWT Token
            $jwtToken = FirebaseJwt::encode($payload, $this->key, 'HS256');
        } catch (Exception $e) {
            // 編碼錯誤，暫不處理，先回傳空字串
        }

        return $jwtToken;
    }

    /**
     * 解碼 JWT Token
     *
     * @param string $jwtToken JWT Token
     * 
     * @return array 資料
     */
    public function decode(string $jwtToken): array
    {
        $data = [];
        try {
            // 解碼 JWT Token
            $data = (array) FirebaseJwt::decode($jwtToken, new Key($this->key, 'HS256'));
        } catch (Exception $e) {
            // 解碼錯誤，暫不處理，先回傳空陣列
        }

        return $data;
    }
}
