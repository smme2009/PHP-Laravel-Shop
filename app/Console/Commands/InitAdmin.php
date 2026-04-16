<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\Status as EnumStatus;
use App\Services\Admin\Admin as SvcAdmin;

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
        $this->line('開始初始化管理員帳號...');

        // 檢查是否不存在管理員帳號
        $this->checkNoExistingAdmins();

        // 取得輸入資料
        $inputData = $this->getInputData();

        // 新增管理員帳號
        $this->createAdmin($inputData);

        $this->info('管理員帳號初始化成功!');
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
        if(count($result->data) > 0) {
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
        $account = $this->ask('請輸入管理員帳號');

        // 取得密碼
        $password = $this->secret('請輸入管理員密碼');
        $confirmPassword = $this->secret('請再次輸入管理員密碼');

        // 確認密碼錯誤，回傳錯誤資料
        if($password !== $confirmPassword) {
            $this->fail('密碼不一致');
        }

        // 取得帳號名稱
        $name = $this->ask('請輸入管理員名稱');

        return [
            'account' => $account,
            'password' => $password,
            'name' => $name,
        ];
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
