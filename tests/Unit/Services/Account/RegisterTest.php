<?php

namespace Tests\Unit\Services\Account;

use Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Enums\Role as EnumRole;
use App\Models\Account as ModelAccount;
use App\Repositories\Account\Account as RepoAccount;
use App\Services\Account\Register as SrcRegister;

/**
 * 單元測試-服務層-帳號註冊
 */
class RegisterTest extends TestCase
{
    /**
     * 模擬資料存取層-帳號
     * @var MockObject
     */
    private MockObject $mockRepoAccount;

    /**
     * 服務層-帳號註冊
     * @var SrcRegister
     */
    private SrcRegister $srcRegister;

    /**
     * 初始化
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // 模擬資料存取層-帳號
        $this->mockRepoAccount = $this->createMock(RepoAccount::class);

        // 服務層-帳號註冊
        $this->srcRegister = app()->make(
            SrcRegister::class,
            [
                'repoAccount' => $this->mockRepoAccount,
            ]
        );
    }

    /**
     * 測試註冊成功
     * 
     * @return void
     */
    public function testRegisterSuccess(): void
    {
        // 模擬資料
        $mockData = [
            'account' => 'test@test.com',
            'password' => 'password',
            'name' => 'Test User',
        ];

        // 模擬透過帳號取得帳號資料
        $this->mockRepoAccount
            ->expects($this->once())
            ->method('findOneByAccount')
            ->with($mockData['account'])
            ->willReturn(null);

        // 模擬註冊結果資料，只模擬需要的資料
        $mockModel = new ModelAccount();
        $mockModel->account = $mockData['account'];
        $mockModel->name = $mockData['name'];

        // 模擬新增帳號並設定角色
        $this->mockRepoAccount
            ->expects($this->once())
            ->method('insertWithRole')
            ->with($mockData, EnumRole::BUYER->value)
            ->willReturn($mockModel);

        // 執行註冊
        $result = $this->srcRegister->register($mockData);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertEquals($mockData['account'], $resultData['account']);
        $this->assertEquals($mockData['name'], $resultData['name']);
    }

    /**
     * 測試驗證欄位錯誤
     * 
     * @return void
     */
    public function testValidateFieldError(): void
    {
        // 模擬資料
        $mockData = [
            'account' => 'test', // 帳號需是Email格式
            'password' => 'pass', // 密碼長度不足
            'name' => '', // 名稱未填寫
        ];

        // 執行註冊
        $result = $this->srcRegister->register($mockData);

        // 驗證結果狀態
        $this->assertSame(422, $result->httpCode);

        // 驗證結果資料
        $resultErrors = $result->errors;
        $this->assertArrayHasKey('account', $resultErrors);
        $this->assertArrayHasKey('password', $resultErrors);
        $this->assertArrayHasKey('name', $resultErrors);
    }

    /**
     * 測試帳號已被註冊
     * 
     * @return void
     */
    public function testAccountAlreadyRegistered(): void
    {
        // 模擬資料
        $mockData = [
            'account' => 'test@test.com',
            'password' => 'password',
            'name' => 'Test User',
        ];

        // 模擬透過帳號取得帳號資料
        $this->mockRepoAccount
            ->expects($this->once())
            ->method('findOneByAccount')
            ->with($mockData['account'])
            ->willReturn(new ModelAccount());

        // 執行註冊
        $result = $this->srcRegister->register($mockData);

        // 驗證結果狀態
        $this->assertSame(409, $result->httpCode);

        // 驗證結果資料
        $this->assertEmpty($result->data);
    }
}
