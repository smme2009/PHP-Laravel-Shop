<?php

namespace App\Http\Controllers\Tool;

use Illuminate\Http\JsonResponse;

/**
 * 控制器共用工具-回傳
 */
class Response
{
    /**
     * HTTP狀態碼
     * @var int
     */
    private int $httpCode = 200;

    /**
     * 訊息
     * @var string
     */
    private string $message = '';

    /**
     * 資料
     * @var array
     */
    private array $data = [];

    /**
     * 設定HTTP狀態碼
     *
     * @param int $httpCode HTTP 狀態碼
     * 
     * @return Response
     */
    public function setHttpCode(int $httpCode): Response
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    /**
     * 透過狀態設定HTTP狀態碼
     *
     * @param bool $status 狀態
     * 
     * @return Response
     */
    public function setHttpCodeByStatus(bool $status): Response
    {
        $this->httpCode = $status ? 200 : 400;
        return $this;
    }

    /**
     * 設定訊息
     *
     * @param string $message 訊息
     * 
     * @return Response
     */
    public function setMessage(string $message): Response
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 新增資料
     *
     * @param string $name 名稱
     * @param mixed $value 值
     * 
     * @return Response
     */
    public function addData(string $name, mixed $value): Response
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * 批量新增資料
     *
     * @param array $data 資料
     * 
     * @return Response
     */
    public function bulkAddData(array $data): Response
    {
        foreach ($data as $name => $value) {
            $this->addData($name, $value);
        }

        return $this;
    }

    /**
     * 取得回應物件
     *
     * @return JsonResponse
     */
    public function build(): JsonResponse
    {
        $data = [
            'message' => $this->message,
            'data' => $this->data,
        ];

        return response()->json($data, $this->httpCode);
    }
}