<?php

namespace App\Tool\Response;

/**
 * 回應
 */
class Json
{
    /**
     * HttpCode
     */
    private int $httpCode = 200;

    /**
     * Header
     */
    private array $header = [];

    /**
     * 訊息
     */
    private array $message = [];

    /**
     * 資料
     */
    private array $data = [];

    /**
     * 設定HttpCode
     * 
     * @param int $httpCode HttpCode
     * 
     * @return self
     */
    public function setHttpCode(int $httpCode)
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    /**
     * 設定Header
     * 
     * @param string $name 名稱
     * @param string $value 值
     * 
     * @return self
     */
    public function setHeader(string $name, string $value)
    {
        $this->header[$name] = $value;

        return $this;
    }

    /**
     * 設定訊息 
     * 
     * @param string|array $message 訊息
     * 
     * @return self
     */
    public function setMessage(string|array $message)
    {
        if (is_string($message)) {
            $this->message[] = $message;
        }

        if (is_array($message)) {
            $this->message = array_merge($this->message, $message);
        }

        return $this;
    }

    /**
     * 設定資料
     * 
     * @param array $data
     * 
     * @return self
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 取得結果
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $jsonData = [
            'message' => $this->message,
            'data' => $this->data,
        ];

        $response = response()
            ->json($jsonData, $this->httpCode)
            ->withHeaders($this->header);

        return $response;
    }
}
