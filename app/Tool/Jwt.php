<?php

namespace App\Tool;

use Exception;

use Firebase\JWT\JWT as FirebaseJwt;
use Firebase\JWT\Key;

/**
 * Jwt
 */
class Jwt
{
    /**
     * JWT編碼
     * 
     * @param array $data 資料
     * 
     * @return array
     */
    public static function encode(array $data): string
    {
        $key = env('APP_KEY');

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

        $jwtToken = FirebaseJwt::encode($payload, $key, 'HS256');

        return $jwtToken;
    }

    /**
     * JWT解碼
     * 
     * @param string $jwtToken Jwt Token
     * 
     * @return array
     */
    public static function decode(string $jwtToken): array
    {
        $key = env('APP_KEY');

        $data = [];
        try {
            $data = (array)FirebaseJwt::decode($jwtToken, new Key($key, 'HS256'));
        } catch (Exception $e) {
            // 解碼錯誤，暫不處理，先回傳空陣列
        }

        return $data;
    }
}
