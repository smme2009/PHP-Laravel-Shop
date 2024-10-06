<?php

namespace App\Tool\Jwt;

use Exception;

use Firebase\JWT\JWT as FirebaseJwt;
use Firebase\JWT\Key;

/**
 * Jwt
 */
class Jwt
{
    private string $key;

    public function __construct()
    {
        $this->key = env('APP_KEY');
    }

    /**
     * JWT編碼
     * 
     * @param array $data 資料
     * 
     * @return string
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
            $jwtToken = FirebaseJwt::encode($payload, $this->key, 'HS256');
        } catch (Exception $e) {
            // 編碼錯誤，暫不處理，先回傳空字串
        }

        return $jwtToken;
    }

    /**
     * JWT解碼
     * 
     * @param string $jwtToken Jwt Token
     * 
     * @return array
     */
    public function decode(string $jwtToken): array
    {
        $data = [];
        try {
            $data = (array) FirebaseJwt::decode($jwtToken, new Key($this->key, 'HS256'));
        } catch (Exception $e) {
            // 解碼錯誤，暫不處理，先回傳空陣列
        }

        return $data;
    }
}
