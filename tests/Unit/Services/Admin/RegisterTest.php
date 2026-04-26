<?php

namespace Tests\Unit\Services\Admin;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use App\Enums\Status as EnumStatus;
use App\Models\Admin as ModelAdmin;
use App\Repositories\Admin\Admin as RepoAdmin;
use App\Services\Admin\Register as SrcRegister;
use App\Services\Admin\Formatters\Admin as SvcFormatterAdmin;

/*
 * 單元測試-服務層-管理員註冊
 */

class RegisterTest extends TestCase
{
    /**
     * 模擬Model-管理員
     * @var MockObject
     */
    private MockObject $mockModelAdmin;

    /**
     * 模擬資料存取層-管理員
     * @var MockObject
     */
    private MockObject $mockRepoAdmin;

    /**
     * 模擬服務層-格式化-管理員
     * @var MockObject
     */
    private MockObject $mockSvcFormatterAdmin;

    /**
     * 服務層-管理員註冊
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

        // 模擬Model-管理員
        $this->mockModelAdmin = $this->createMock(ModelAdmin::class);

        // 模擬資料存取層-管理員
        $this->mockRepoAdmin = $this->createMock(RepoAdmin::class);

        // 模擬服務層-格式化-管理員
        $this->mockSvcFormatterAdmin = $this->createMock(SvcFormatterAdmin::class);

        // 服務層-管理員註冊
        $this->srcRegister = app()->make(
            SrcRegister::class,
            [
                'repoAdmin' => $this->mockRepoAdmin,
                'svcformatterAdmin' => $this->mockSvcFormatterAdmin,
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
        // 模擬註冊資料
        $mockRegisterData = [
            'name' => 'testname',
            'account' => 'testaccount',
            'password' => 'testpassword',
            'status' => EnumStatus::ENABLED->value,
        ];

        // 模擬格式化後的資料
        $mockFormattedData = [
            'adminId' => 1,
            'name' => 'testname',
            'account' => 'testaccount',
            'status' => EnumStatus::ENABLED->value,
        ];

        // 模擬驗證帳號是否已被使用
        $this->mockRepoAdmin
            ->expects($this->once())
            ->method('findOneByAccount')
            ->with($mockRegisterData['account'])
            ->willReturn(null);

        // 模擬新增管理員
        $this->mockRepoAdmin
            ->expects($this->once())
            ->method('insert')
            ->with($mockRegisterData)
            ->willReturn($this->mockModelAdmin);

        // 模擬格式化管理員資料
        $this->mockSvcFormatterAdmin
            ->expects($this->once())
            ->method('formatData')
            ->with($this->mockModelAdmin)
            ->willReturn($mockFormattedData);

        // 執行註冊
        $result = $this->srcRegister->register($mockRegisterData);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertSame($mockFormattedData['adminId'], $resultData['adminId']);
        $this->assertSame($mockFormattedData['name'], $resultData['name']);
        $this->assertSame($mockFormattedData['account'], $resultData['account']);
        $this->assertSame($mockFormattedData['status'], $resultData['status']);
    }

    /**
     * 測試欄位驗證失敗
     * 
     * @return void
     */
    public function testValidationFail(): void
    {
        // 模擬註冊資料
        $mockRegisterData = [
            'name' => 'error',
            'account' => 'error',
            'password' => 'error',
            'status' => EnumStatus::ENABLED->value,
        ];

        // 執行註冊
        $result = $this->srcRegister->register($mockRegisterData);

        // 驗證結果狀態
        $this->assertSame(422, $result->httpCode);
    }

    /**
     * 測試帳號已被使用
     * 
     * @return void
     */
    public function testAccountExists(): void
    {
        // 模擬註冊資料
        $mockRegisterData = [
            'name' => 'testname',
            'account' => 'testacct',
            'password' => 'testpass',
            'status' => EnumStatus::ENABLED->value,
        ];

        // 模擬驗證帳號是否已被使用
        $this->mockRepoAdmin
            ->expects($this->once())
            ->method('findOneByAccount')
            ->with($mockRegisterData['account'])
            ->willReturn($this->mockModelAdmin);

        // 執行註冊
        $result = $this->srcRegister->register($mockRegisterData);

        // 驗證結果狀態
        $this->assertSame(409, $result->httpCode);
    }

    /**
     * 測試新增管理員失敗
     * 
     * @return void
     */
    public function testInsertFail(): void
    {
        // 模擬註冊資料
        $mockRegisterData = [
            'name' => 'testname',
            'account' => 'testacct',
            'password' => 'testpass',
            'status' => EnumStatus::ENABLED->value,
        ];

        // 模擬驗證帳號是否已被使用
        $this->mockRepoAdmin
            ->expects($this->once())
            ->method('findOneByAccount')
            ->with($mockRegisterData['account'])
            ->willReturn(null);

        // 模擬新增管理員失敗
        $this->mockRepoAdmin
            ->expects($this->once())
            ->method('insert')
            ->with($mockRegisterData)
            ->willReturn(null);

        // 執行註冊
        $result = $this->srcRegister->register($mockRegisterData);

        // 驗證結果狀態
        $this->assertSame(500, $result->httpCode);
    }
}
