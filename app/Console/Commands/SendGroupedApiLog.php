<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Log\Api as RepoLogApi;
use App\Notifications\System\Api as NotificationSystemApi;

/**
 * 指令-發送API紀錄統計通知
 */
class SendGroupedApiLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:grouped-api-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '發送API紀錄統計通知';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->line('開始發送訊息...');

            // 取得紀錄
            $log = $this->getGroupedLog();

            if ($log === null) {
                $this->info('無未讀紀錄!');
                return;
            }

            // 格式化紀錄訊息
            $logMessage = $this->formatLogMessage($log);

            // 取得紀錄時間範圍
            $logTimeRange = $this->getLogTimeRange($log);

            // 通知參數
            $params = [
                'logMessage' => $logMessage,
                'logTimeRange' => $logTimeRange,
            ];

            // 發送通知
            Notification::route('webhook', config('services.discord.webhookUrl'))
                ->notify(app(NotificationSystemApi::class, $params));

            // 更新紀錄的讀取狀態
            $this->setLogStatus($log);

            $this->info('訊息發送成功!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 取得紀錄
     * 
     * @return ?Collection 紀錄
     */
    private function getGroupedLog(): ?Collection
    {
        // 取得統計後的API紀錄
        $models = app(RepoLogApi::class)->findGroupedByIsRead(false);

        if ($models->isEmpty() === true) {
            return null;
        }

        return $models;
    }

    /**
     * 格式化紀錄訊息
     * 
     * @param Collection $logs 紀錄
     * 
     * @return string 紀錄訊息
     */
    private function formatLogMessage(Collection $logs): string
    {
        // 取得各欄位的最大字數
        $uriCharCount = $logs->max(fn($row) => mb_strlen($row->uri));
        $methodCharCount = $logs->max(fn($row) => mb_strlen($row->method));
        $countCharCount = $logs->max(fn($row) => mb_strlen($row->count));

        // 格式化訊息
        // 會根據最大字數來對齊欄位
        $logMessage = $logs
            ->map(function ($row) use ($uriCharCount, $methodCharCount, $countCharCount) {
                $uri = str_pad($row->uri, $uriCharCount, ' ', STR_PAD_RIGHT);
                $method = str_pad($row->method, $methodCharCount, ' ', STR_PAD_RIGHT);
                $count = str_pad($row->count, $countCharCount, ' ', STR_PAD_LEFT);
                return "路由：{$uri}｜方法：{$method}｜次數：{$count}";
            })
            ->join("\n");

        // 組合訊息
        return $logMessage;
    }

    /**
     * 取得紀錄時間範圍
     * 
     * @param Collection $logs 紀錄
     * 
     * @return string 紀錄時間範圍
     */
    private function getLogTimeRange(Collection $logs): string
    {
        $startTime = $logs->min('start_time');
        $endTime = $logs->max('end_time');
        return "{$startTime} ~ {$endTime}";
    }

    /**
     * 更新紀錄的讀取狀態
     * 
     * @param Collection $logs 紀錄
     * 
     * @return void 是否更新成功
     */
    private function setLogStatus(Collection $logs): void
    {
        $logApiIds = $logs
            ->map(fn($row) => explode(',', $row->log_api_ids))
            ->flatten()
            ->toArray();

        app(RepoLogApi::class)->updateIsRead($logApiIds, true);
    }
}
