<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
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
        $this->line('開始發送訊息...');

        try {
            Notification::route('webhook', config('services.discord.webhookUrl'))
                ->notify(app(NotificationSystemApi::class));

            $this->info('訊息發送成功!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
