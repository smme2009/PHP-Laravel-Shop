<?php

namespace Tests\Unit\Services\Account;

use Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Models\Account as ModelAccount;
use App\Repositories\Account\Account as RepoAccount;
use App\Services\Account\Info as SrcInfo;

/*
 * 單元測試-服務層-帳號資訊
 */
class InfoTest extends TestCase
{
    /**
     * 模擬資料存取層-帳號
     * @var MockObject
     */
    private MockObject $mockRepoAccount;

    /**
     * 服務層-帳號資訊
     * @var SrcInfo
     */
    private SrcInfo $srcInfo;

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

        // 服務層-帳號資訊
        $this->srcInfo = app()->make(
            SrcInfo::class,
            [
                'repoAccount' => $this->mockRepoAccount,
            ]
        );
    }

    /**
     * 測試取得帳號資料成功
     * 
     * @return void
     */
    public function testGetProfileSuccess(): void
    {
        // 模擬資料
        $mockData = [
            'accountId' => 1,
            'account' => 'test@test.com',
            'name' => 'Test User',
        ];

        // 模擬帳號資料，只模擬需要的資料
        $mockModel = new ModelAccount();
        $mockModel->accountId = $mockData['accountId'];
        $mockModel->account = $mockData['account'];
        $mockModel->name = $mockData['name'];

        // 模擬透過帳號ID取得帳號資料
        $this->mockRepoAccount
            ->expects($this->once())
            ->method('findOneByAccountId')
            ->with($mockData['accountId'])
            ->willReturn($mockModel);

        // 執行取得帳號資料
        $result = $this->srcInfo->getProfile($mockData['accountId']);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertEquals($mockData['account'], $resultData['account']);
        $this->assertEquals($mockData['name'], $resultData['name']);
    }

    /**
     * 測試取得帳號資料失敗
     * 
     * @return void
     */
    public function testGetProfileFail(): void
    {
        $accountId = 1;

        // 模擬透過帳號ID取得帳號資料
        $this->mockRepoAccount
            ->expects($this->once())
            ->method('findOneByAccountId')
            ->with($accountId)
            ->willReturn(null);

        // 執行取得帳號資料
        $result = $this->srcInfo->getProfile($accountId);

        // 驗證結果狀態
        $this->assertSame(404, $result->httpCode);
    }
}