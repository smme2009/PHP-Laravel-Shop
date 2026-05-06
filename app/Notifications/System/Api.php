<?php

namespace App\Notifications\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;

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
        private readonly string $logMessage, // 紀錄訊息
        private readonly string $logTimeRange, // 紀錄時間範圍
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
                                'value' => "`{$this->logTimeRange}`",
                            ],
                            [
                                'name' => '** 📝 呼叫紀錄 **',
                                'value' => "```fix\n{$this->logMessage}\n```",
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
}
