<?php

namespace App\Services\Admin;

use App\Repositories\Admin\Admin as RepoAdmin;
use App\Services\Service;
use App\Services\Admin\Formatters\Admin as SvcFormatterAdmin;
use App\Services\Tool\Output\Result as OutputResult;

/**
 * 服務層-管理員列表
 */
class Lister extends Service
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
     * 取得所有管理員
     * 
     * @return OutputResult 處理結果
     */
    public function getAll(): OutputResult
    {
        // 取得所有管理員
        $models = $this->repoAdmin->findAll();

        // 格式化管理員資料
        $data = $models
            ->map(fn($item) => $this->svcformatterAdmin->formatData($item))
            ->toArray();

        // 取得成功，回傳管理員資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('取得管理員資料成功')
            ->bulkAddData($data)
            ->build();
    }
}
