<?php

namespace Tests\Unit\Services\Account;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Models\AccountAddress as ModelAccountAddress;
use App\Repositories\Account\Address as RepoAddress;
use App\Services\Account\Address as SrcAddress;

/*
 * 單元測試-服務層-帳號地址
 */
class AddressTest extends TestCase
{
    /**
     * 模擬資料存取層-帳號地址
     * @var MockObject
     */
    private MockObject $mockRepoAddress;

    /**
     * 服務層-帳號地址
     * @var SrcAddress
     */
    private SrcAddress $srcAddress;

    /**
     * 初始化
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // 模擬資料存取層-帳號地址
        $this->mockRepoAddress = $this->createMock(RepoAddress::class);

        // 服務層-帳號地址
        $this->srcAddress = app()->make(
            SrcAddress::class,
            [
                'repoAddress' => $this->mockRepoAddress,
            ]
        );
    }

    /**
     * 測試新增時發生欄位驗證錯誤
     * 
     * @return void
     */
    public function testCreateFailsWithValidation(): void
    {
        // 模擬資料
        $mockData = [
            'accountId' => 1,
            'address' => 1,
        ];

        // 執行新增帳號地址
        $result = $this->srcAddress->create($mockData);

        // 驗證結果狀態
        $this->assertFalse($result->status);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertArrayHasKey('address', $resultData);
    }

    /**
     * 測試新增時發生資料庫錯誤
     * 
     * @return void
     */
    public function testCreateFailsWithDatabase(): void
    {
        // 模擬資料
        $mockData = [
            'accountId' => 1,
            'address' => '地址1',
        ];

        // 模擬帳號地址資料，只模擬需要的資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('insert')
            ->with($mockData)
            ->willReturn(null);

        // 執行新增帳號地址
        $result = $this->srcAddress->create($mockData);

        // 驗證結果狀態
        $this->assertFalse($result->status);
    }

    /**
     * 測試新增成功
     * 
     * @return void
     */
    public function testCreateSuccess(): void
    {
        // 模擬資料
        $mockData = [
            'accountAddressId' => 1,
            'address' => '地址1',
        ];

        // 模擬帳號地址資料，只模擬需要的資料
        $model = new ModelAccountAddress();
        $model->account_address_id = $mockData['accountAddressId'];
        $model->address = $mockData['address'];

        // 模擬帳號地址資料，只模擬需要的資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('insert')
            ->with($mockData)
            ->willReturn($model);

        // 執行新增帳號地址
        $result = $this->srcAddress->create($mockData);

        // 驗證結果狀態
        $this->assertTrue($result->status);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertEquals($mockData['accountAddressId'], $resultData['accountAddressId']);
        $this->assertEquals($mockData['address'], $resultData['address']);
    }

    /**
     * 測試取得帳號地址列表成功
     * 
     * @return void
     */
    public function testGetListSuccess(): void
    {
        // 模擬帳號ID
        $mockAccountId = 1;

        // 模擬資料
        $mockData = [
            [
                'accountAddressId' => 1,
                'address' => '地址1',
            ],
            [
                'accountAddressId' => 2,
                'address' => '地址2',
            ],
        ];

        // 模擬帳號地址資料，只模擬需要的資料
        $mockModelList = array_map(function ($item) {
            $model = new ModelAccountAddress();
            $model->account_address_id = $item['accountAddressId'];
            $model->address = $item['address'];
            return $model;
        }, $mockData);

        // 模擬透過帳號ID取得帳號地址資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('findAllByAccountId')
            ->with($mockAccountId)
            ->willReturn(new EloquentCollection($mockModelList));

        // 執行取得帳號地址資料
        $result = $this->srcAddress->getList($mockAccountId);

        // 驗證結果狀態
        $this->assertTrue($result->status);

        // 驗證結果資料
        foreach ($result->data as $key => $item) {
            $this->assertEquals($mockData[$key]['accountAddressId'], $item['accountAddressId']);
            $this->assertEquals($mockData[$key]['address'], $item['address']);
        }
    }

    /**
     * 測試刪除時查無資料
     * 
     * @return void
     */
    public function testDeleteFailsWhenDataNotFound(): void
    {
        // 模擬帳號ID
        $mockAccountId = 1;

        // 模擬帳號地址ID
        $mockAccountAddressId = 1;

        // 模擬資料
        $mockData = [
            'accountAddressId' => 1,
            'accountId' => 2,
            'address' => '地址1',
        ];

        // 模擬帳號地址資料，只模擬需要的資料
        $model = new ModelAccountAddress();
        $model->account_address_id = $mockData['accountAddressId'];
        $model->account_id = $mockData['accountId'];
        $model->address = $mockData['address'];

        // 模擬透過帳號ID取得帳號地址資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('findOneByAccountAddressId')
            ->with($mockAccountAddressId)
            ->willReturn($model);

        // 執行刪除帳號地址資料
        $result = $this->srcAddress->delete($mockAccountId, $mockAccountAddressId);

        // 驗證結果狀態
        $this->assertFalse($result->status);
    }

    /**
     * 測試刪除時遇到資料庫異常
     * 
     * @return void
     */
    public function testDeleteFailsWithDatabase(): void
    {
        // 模擬帳號ID
        $mockAccountId = 1;

        // 模擬帳號地址ID
        $mockAccountAddressId = 1;

        // 模擬資料
        $mockData = [
            'accountAddressId' => 1,
            'accountId' => 1,
            'address' => '地址1',
        ];

        // 模擬帳號地址資料，只模擬需要的資料
        $model = new ModelAccountAddress();
        $model->account_address_id = $mockData['accountAddressId'];
        $model->account_id = $mockData['accountId'];
        $model->address = $mockData['address'];

        // 模擬透過帳號ID取得帳號地址資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('findOneByAccountAddressId')
            ->with($mockAccountAddressId)
            ->willReturn($model);

        // 模擬透過帳號地址ID刪除資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('delete')
            ->with($model)
            ->willReturn(false);

        // 執行刪除帳號地址資料
        $result = $this->srcAddress->delete($mockAccountId, $mockAccountAddressId);

        // 驗證結果狀態
        $this->assertFalse($result->status);
    }

    /**
     * 測試刪除成功
     * 
     * @return void
     */
    public function testDeleteSuccess(): void
    {
        // 模擬帳號ID
        $mockAccountId = 1;

        // 模擬帳號地址ID
        $mockAccountAddressId = 1;

        // 模擬資料
        $mockData = [
            'accountAddressId' => 1,
            'accountId' => 1,
            'address' => '地址1',
        ];

        // 模擬帳號地址資料，只模擬需要的資料
        $model = new ModelAccountAddress();
        $model->account_address_id = $mockData['accountAddressId'];
        $model->account_id = $mockData['accountId'];
        $model->address = $mockData['address'];

        // 模擬透過帳號ID取得帳號地址資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('findOneByAccountAddressId')
            ->with($mockAccountAddressId)
            ->willReturn($model);

        // 模擬透過帳號地址ID刪除資料
        $this->mockRepoAddress
            ->expects($this->once())
            ->method('delete')
            ->with($model)
            ->willReturn(true);

        // 執行刪除帳號地址資料
        $result = $this->srcAddress->delete($mockAccountId, $mockAccountAddressId);

        // 驗證結果狀態
        $this->assertTrue($result->status);
    }
}