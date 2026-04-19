<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Enums\Status as EnumStatus;
use App\Services\Admin\Admin as SvcAdmin;
use App\Notifications\Admin\Init as NotificationAdminInit;

/**
 * 指令-初始化管理員帳號
 */
class InitAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化管理員帳號';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        $this->line('開始初始化管理員帳號...');

        // 檢查是否不存在管理員帳號
        $this->checkNoExistingAdmins();

        // 取得輸入資料
        $inputData = $this->getInputData();

        // 新增管理員帳號
        $this->createAdmin($inputData);

        // 發送通知
        Notification::route('webhook', config('services.discord.webhookUrl'))
            ->notify(app(NotificationAdminInit::class));

        $this->info('管理員帳號初始化成功!');

        DB::commit();
    }

    /**
     * 檢查是否存在管理員帳號
     * 
     * @return void
     */
    private function checkNoExistingAdmins(): void
    {
        // 取得所有管理員
        $result = app(SvcAdmin::class)->getAll();

        // 取得失敗，回傳錯誤資料
        if ($result->httpCode !== 200) {
            $this->fail($result->message);
        }

        // 管理員帳號已存在，回傳錯誤資料
        if (count($result->data) > 0) {
            $this->fail('已存在管理員帳號');
        }
    }

    /**
     * 取得輸入資料
     * 
     * @return array 輸入資料
     */
    private function getInputData(): array
    {
        // 取得帳號
        while (true) {
            $account = $this->ask('請輸入管理員帳號');
            if ($this->validateStringLength($account) === true) break;
            $this->error('帳號長度需為8～20個字，請重新輸入');
        }

        // 取得密碼
        while (true) {
            $password = $this->secret('請輸入管理員密碼');
            if ($this->validateStringLength($password) === true) break;
            $this->error('密碼長度需為8～20個字，請重新輸入');
        }

        // 再次確認密碼
        while (true) {
            $confirmPassword = $this->secret('請再次輸入管理員密碼');
            if ($password === $confirmPassword) break;
            $this->error('密碼不一致，請重新輸入');
        }

        // 取得帳號名稱
        while (true) {
            $name = $this->ask('請輸入管理員名稱');
            if ($this->validateStringLength($name) === true) break;
            $this->error('名稱長度需為8～20個字，請重新輸入');
        }

        return [
            'account' => $account,
            'password' => $password,
            'name' => $name,
        ];
    }

    /**
     * 驗證字串長度
     * 
     * @param string $string 字串
     * 
     * @return bool 是否驗證成功
     */
    private function validateStringLength(string $string): bool
    {
        $strLength = mb_strlen($string);
        return ($strLength >= 8 && $strLength <= 20);
    }

    /**
     * 新增管理員帳號
     * 
     * @param array $inputData 輸入資料
     * 
     * @return void
     */
    private function createAdmin(array $inputData): void
    {
        // 新增帳號
        $result = app(SvcAdmin::class)->create([
            'account' => $inputData['account'],
            'password' => $inputData['password'],
            'name' => $inputData['name'],
            'status' => EnumStatus::ENABLED->value,
        ]);

        // 新增失敗，回傳錯誤資料
        if ($result->httpCode !== 200) {
            $this->fail($result->message);
        }
    }
}
