<?php

namespace App\Notifications\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;
use App\Repositories\Log\Api as RepoLogApi;

/**
 * 通知-系統-API
 */
class Api extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * 建構子
     */
    public function __construct(
        private readonly RepoLogApi $repoLogApi, // 資料存取層-紀錄-API
    ) {}

    /**
     * 頻道
     *
     * @return array 頻道列表
     */
    public function via(): array
    {
        return [WebhookChannel::class];
    }

    /**
     * Webhook訊息
     *
     * @return WebhookMessage Webhook訊息
     */
    public function toWebhook()
    {
        // 取得統計時間區間
        $timeRange = $this->getRangeTime();
        $startTime = $timeRange['startTime'];
        $endTime = $timeRange['endTime'];

        // 取得紀錄
        $log = $this->getLog($startTime, $endTime);

        // 取得發送時間
        $sendTime = now()->toDateTimeString();

        return WebhookMessage::create()
            ->header('Content-Type', 'application/json')
            ->data([
                'embeds' => [
                    [
                        'description' => "# 📢 系統通知\n### API呼叫紀錄",
                        'color' => 0x3498DB,
                        'fields' => [
                            [
                                'name' => '** 🕒 統計時間 **',
                                'value' => "`{$startTime} ~ {$endTime}`",
                            ],
                            [
                                'name' => '** 📝 呼叫紀錄 **',
                                'value' => "```fix\n{$log}\n```",
                            ],
                            [
                                'name' => '** ⏰ 通知時間 **',
                                'value' => "`{$sendTime}`",
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * 取得統計時間區間
     * 
     * @return array 統計時間區間
     */
    private function getRangeTime(): array
    {
        return [
            'startTime' => now()->subDay()->toDateTimeString(),
            'endTime' => now()->toDateTimeString(),
        ];
    }

    /**
     * 取得紀錄
     * 
     * @param string $startTime 開始時間
     * @param string $endTime 結束時間
     * 
     * @return string 紀錄
     */
    private function getLog(string $startTime, string $endTime): string
    {
        // 取得統計後的API紀錄
        $models = $this->repoLogApi->findGroupedByTimeRange($startTime, $endTime);

        // 取得各欄位的最大字數
        $uriCharCount = $models->max(fn($row) => mb_strlen($row->uri));
        $methodCharCount = $models->max(fn($row) => mb_strlen($row->method));
        $countCharCount = $models->max(fn($row) => mb_strlen($row->count));

        // 格式化訊息
        // 會根據最大字數來對齊欄位
        $rows = $models->map(function ($row) use ($uriCharCount, $methodCharCount, $countCharCount) {
            $uri = str_pad($row->uri, $uriCharCount, ' ', STR_PAD_RIGHT);
            $method = str_pad($row->method, $methodCharCount, ' ', STR_PAD_RIGHT);
            $count = str_pad($row->count, $countCharCount, ' ', STR_PAD_LEFT);
            return "路由：{$uri}｜方法：{$method}｜次數：{$count}";
        });

        // 組合訊息
        return (($rows->isEmpty() === true) ? '(無紀錄)' : $rows->join("\n"));
    }
}
