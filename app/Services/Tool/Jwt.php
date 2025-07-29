<?php

namespace App\Services\Tool;

use Exception;
use Illuminate\Support\Facades\Redis;
use Firebase\JWT\JWT as FirebaseJwt;
use Firebase\JWT\Key;

/**
 * JWT Token工具
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

    /**
     * 將JWT Token加入白名單
     *
     * @param string $jwtToken JWT Token
     *
     * @return bool 是否加入成功
     */
    public function addToWhitelist(string $jwtToken): bool
    {
        // 取得儲存的key
        $whitelistKey = $this->getWhitelistKey($jwtToken);

        // 將JWT Token存入Redis
        $result = Redis::setex($whitelistKey, 86400, $jwtToken);

        // 回傳是否成功
        return $result->getPayload() === 'OK';
    }

    /**
     * 確認JWT Token是否存在白名單中
     *
     * @param string $jwtToken JWT Token
     *
     * @return bool 是否存在白名單中
     */
    public function isInWhitelist(string $jwtToken): bool
    {
        // 取得儲存的key
        $whitelistKey = $this->getWhitelistKey($jwtToken);

        // 確認是否存在白名單中
        return $jwtToken === Redis::get($whitelistKey);
    }

    /**
     * 取得白名單的key
     *
     * @param string $jwtToken JWT Token
     *
     * @return string 白名單的key
     */
    private function getWhitelistKey(string $jwtToken): string
    {
        $hashKey = hash('sha256', $jwtToken);
        return "jwtToken:{$hashKey}";
    }
}
