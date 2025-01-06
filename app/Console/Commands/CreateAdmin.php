<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Http\Service\Account\Account as SrcAccount;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin {account} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新增管理者';

    // 建構子
    public function __construct(
        private SrcAccount $srcAccount,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $account = $this->argument('account');
        $password = $this->argument('password');

        // 管理者ID
        $roleId = config('role.admin');

        $data = [
            'account' => $account,
            'password' => Hash::make($password),
            'name' => '管理者',
            'status' => 1,
        ];

        // 驗證欄位
        $result = $this->srcAccount->validateData($data);

        if ($result->status === false) {
            $this->showErrorMessage($result->errorList);
            return;
        }

        // 新增帳號
        $data = $this->srcAccount->insert($roleId, $data);

        if ($data === null) {
            $this->error('新增管理者失敗');
            return;
        }

        $this->info('新增管理者成功');
    }

    /**
     * 顯示錯誤訊息
     * 
     * @param array $errorList 錯誤列表
     * 
     * @return void
     */
    private function showErrorMessage(array $errorList): void
    {
        foreach ($errorList as $messageList) {
            foreach ($messageList as $message) {
                $this->error($message);
            }
        }
    }
}
