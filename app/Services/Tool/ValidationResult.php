<?php

namespace App\Services\Tool;

use App\Services\Tool\Output\ValidationResult as OutputValidationResult;

/**
 * Service的通用驗證結果構建工具
 */
class ValidationResult
{
    /**
     * 資料
     * @var bool
     */
    private array $data = [];

    /**
     * 規則
     * @var array
     */
    private array $rules = [];

    /**
     * 新增資料
     * 
     * @param string $name 名稱
     * @param mixed $value 值
     * 
     * @return self
     */
    public function addData(string $name, mixed $value): self
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * 批量新增資料
     * 
     * @param array $data 資料
     * 
     * @return self
     */
    public function bulkAddData(array $data): self
    {
        foreach ($data as $name => $value) {
            $this->addData($name, $value);
        }

        return $this;
    }

    /**
     * 新增規則
     * 
     * @param string $name 名稱
     * @param array $detail 規則細節
     * 
     * @return self
     */
    public function addRule(string $name, array $detail): self
    {
        $this->rules[$name] = $detail;
        return $this;
    }

    /**
     * 批量新增規則
     * 
     * @param array $rules 規則
     * 
     * @return self
     */
    public function bulkAddRules(array $rules): self
    {
        foreach ($rules as $name => $detail) {
            $this->addRule($name, $detail);
        }

        return $this;
    }

    /**
     * 取得結果物件
     * 
     * @return OutputValidationResult
     */
    public function build(): OutputValidationResult
    {
        // 驗證資料
        $validator = validator($this->data, $this->rules);

        return new OutputValidationResult(
            status: !$validator->fails(),
            errors: $validator->errors()->toArray(),
        );
    }
}
