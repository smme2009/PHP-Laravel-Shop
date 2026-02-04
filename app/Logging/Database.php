<?php

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use App\Models\LogApi as ModelLog;

/**
 * 紀錄-資料庫
 */
class Database extends AbstractProcessingHandler
{
    /**
     * 寫入紀錄
     * 
     * @param LogRecord $record 紀錄資料
     * 
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        // 取得要紀錄的資料
        $data = $record->context;

        // 將紀錄寫入資料庫
        ModelLog::create([
            'uri' => $data['uri'],
            'method' => $data['method'],
        ]);
    }
}