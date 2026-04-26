<?php

namespace App\Services\Admin;

use App\Enums\Status as EnumStatus;
use App\Repositories\Admin\Admin as RepoAdmin;
use App\Services\Service;
use App\Services\Admin\Formatters\Admin as SvcFormatterAdmin;
use App\Services\Tool\Output\ValidationResult as OutputValidationResult;
use App\Services\Tool\Output\Result as OutputResult;

/**
 * 服務層-管理員註冊
 */
class Register extends Service
{
    /**
     * 建構子
     * 
     * @param RepoAdmin $repoAdmin 資料存取層-管理員
     * @param SvcFormatterAdmin $svcFormatterAdmin 服務層-格式化-管理員
     */
    public function __construct(
        private readonly RepoAdmin $repoAdmin,
        private readonly SvcFormatterAdmin $svcformatterAdmin,
    ) {}

    /**
     * 註冊管理員
     * 
     * @param array $data 資料
     * 
     * @return OutputResult 處理結果
     */
    public function register(array $data): OutputResult
    {
        // 驗證新增資料
        $validationResult = $this->getValidationResult($data);

        // 驗證失敗，回傳錯誤資料
        if ($validationResult->status === false) {
            return $this->toolResult()
                ->setHttpCode(422)
                ->setMessage('新增管理員失敗，欄位填寫錯誤')
                ->bulkAddErrors($validationResult->errors)
                ->build();
        }

        // 驗證帳號是否已被使用
        $model = $this->repoAdmin->findOneByAccount($data['account']);

        // 驗證失敗，回傳錯誤資料
        if ($model !== null) {
            return $this->toolResult()
                ->setHttpCode(409)
                ->setMessage('新增管理員失敗，帳號已被使用')
                ->build();
        }

        // 新增管理員
        $model = $this->repoAdmin->insert($data);

        // 新增失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setHttpCode(500)
                ->setMessage('新增管理員失敗，系統異常')
                ->build();
        }

        // 格式化管理員資料
        $data = $this->svcformatterAdmin->formatData($model);

        // 新增成功，回傳管理員資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('新增管理員成功')
            ->bulkAddData($data)
            ->build();
    }

    /**
     * 取得驗證結果
     * 
     * @param array $data 資料
     * 
     * @return OutputValidationResult 驗證結果
     */
    private function getValidationResult(array $data): OutputValidationResult
    {
        // 狀態規則
        $ruleStatus = collect(EnumStatus::cases())
            ->pluck('value')
            ->implode(',');

        // 取得驗證結果
        return $this->toolValidator()
            ->bulkAddData($data)
            ->addRule('name', ['required', 'string', 'min:8', 'max:20'])
            ->addRule('account', ['required', 'string', 'min:8', 'max:20'])
            ->addRule('password', ['required', 'string', 'min:8', 'max:20'])
            ->addRule('status', ['required', 'integer', "in:{$ruleStatus}"])
            ->build();
    }
}
