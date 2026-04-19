<?php

namespace App\Notifications\Admin;

use Illuminate\Notifications\Notification;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;

/**
 * 通知-管理員初始化
 */
class Init extends Notification
{
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
        // 通知時間
        $sendTime = now()->toDateTimeString();

        return WebhookMessage::create()
            ->header('Content-Type', 'application/json')
            ->data([
                'embeds' => [
                    [
                        'description' => "# 📢 系統通知\n### 帳號初始化",
                        'color' => 0x3498DB,
                        'fields' => [
                            [
                                'name' => '** ℹ️ 狀態 **',
                                'value' => '`成功`',
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
