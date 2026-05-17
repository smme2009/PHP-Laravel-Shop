<?php

namespace Tests\Unit\Services\Banner;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Banner as ModelBanner;
use App\Repositories\Banner\Banner as RepoBanner;
use App\Services\Banner\Banner as SrcBanner;
use App\Services\Tool\File as SrcToolFile;
use App\Services\Tool\Output\File as OutputFile;

/*
 * 單元測試-服務層-橫幅
 */
class BannerTest extends TestCase
{
    /**
     * 模擬資料存取層-橫幅
     * @var MockObject
     */
    private MockObject $mockRepoBanner;

    /**
     * 模擬服務層-檔案工具
     * @var MockObject
     */
    private MockObject $mockSrcToolFile;

    /**
     * 服務層-橫幅
     * @var SrcBanner
     */
    private SrcBanner $srcBanner;

    /**
     * 初始化
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // 模擬資料存取層-橫幅
        $this->mockRepoBanner = $this->createMock(RepoBanner::class);

        // 模擬服務層-檔案工具
        $this->mockSrcToolFile = $this->createMock(SrcToolFile::class);

        // 模擬服務層-檔案工具(在使用app函式建立時)
        $this->instance(SrcToolFile::class, $this->mockSrcToolFile);

        // 服務層-橫幅
        $this->srcBanner = app()->make(
            SrcBanner::class,
            [
                'repoBanner' => $this->mockRepoBanner,
            ]
        );
    }

    /**
     * 測試新增橫幅時發生欄位驗證失敗
     *
     * @return void
     */
    public function testCreateValidationFail(): void
    {
        // 模擬新增資料
        $mockData = [
            'name' => 'test banner',
        ];

        // 執行新增橫幅
        $result = $this->srcBanner->create($mockData);

        // 驗證結果狀態
        $this->assertSame(422, $result->httpCode);
    }

    /**
     * 測試新增橫幅時發生新增失敗
     *
     * @return void
     */
    public function testCreateInsertFail(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬新增資料
        $mockData = $this->makeMockData();

        // 模擬新增橫幅失敗
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('insert')
            ->willReturn(null);

        // 執行新增橫幅
        $result = $this->srcBanner->create($mockData);

        // 驗證結果狀態
        $this->assertSame(500, $result->httpCode);
    }

    /**
     * 測試新增橫幅成功
     *
     * @return void
     */
    public function testCreateSuccess(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬新增資料
        $mockData = $this->makeMockData();

        // 模擬橫幅Model
        $mockModel = $this->makeMockModel();

        // 模擬檔案資訊
        $mockFileInfo = $this->makeMockFileInfo();

        // 模擬新增橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('insert')
            ->willReturn($mockModel);

        // 模擬取得檔案資訊
        $this->mockSrcToolFile
            ->expects($this->once())
            ->method('getFileInfo')
            ->with($mockModel->photo_file_id)
            ->willReturn($mockFileInfo);

        // 執行新增橫幅
        $result = $this->srcBanner->create($mockData);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $this->assertBannerData($result->data, $mockModel, $mockFileInfo);
    }

    /**
     * 測試取得橫幅分頁成功
     *
     * @return void
     */
    public function testGetPagedSuccess(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬橫幅Model
        $mockModel = $this->makeMockModel();

        // 模擬橫幅分頁
        $mockModels = new LengthAwarePaginator(
            collect([$mockModel]),
            1,
            15,
        );

        // 模擬檔案資訊
        $mockFileInfo = $this->makeMockFileInfo();

        // 模擬取得橫幅分頁
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findPagedByAdminId')
            ->with(1)
            ->willReturn($mockModels);

        // 模擬批量取得檔案資訊
        $this->mockSrcToolFile
            ->expects($this->once())
            ->method('getFileInfos')
            ->with([$mockModel->photo_file_id])
            ->willReturn([$mockModel->photo_file_id => $mockFileInfo]);

        // 執行取得橫幅分頁
        $result = $this->srcBanner->getPaged();

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertCount(1, $resultData);
        $this->assertBannerData($resultData[0], $mockModel, $mockFileInfo);
    }

    /**
     * 測試取得橫幅時發生橫幅不存在
     *
     * @return void
     */
    public function testGetNotFound(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬取得橫幅失敗
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn(null);

        // 執行取得橫幅
        $result = $this->srcBanner->get(1);

        // 驗證結果狀態
        $this->assertSame(404, $result->httpCode);
    }

    /**
     * 測試取得橫幅時發生管理員ID不符
     *
     * @return void
     */
    public function testGetAdminIdMismatch(): void
    {
        // 設定模擬驗證資料至上下文(不存在的管理員ID)
        context()->add('authData', ['id' => 2]);

        // 模擬橫幅Model (admin_id = 1)
        $mockModel = $this->makeMockModel();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 執行取得橫幅
        $result = $this->srcBanner->get(1);

        // 驗證結果狀態
        $this->assertSame(404, $result->httpCode);
    }

    /**
     * 測試取得橫幅成功
     *
     * @return void
     */
    public function testGetSuccess(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬橫幅Model
        $mockModel = $this->makeMockModel();

        // 模擬檔案資訊
        $mockFileInfo = $this->makeMockFileInfo();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 模擬取得檔案資訊
        $this->mockSrcToolFile
            ->expects($this->once())
            ->method('getFileInfo')
            ->with($mockModel->photo_file_id)
            ->willReturn($mockFileInfo);

        // 執行取得橫幅
        $result = $this->srcBanner->get(1);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $this->assertBannerData($result->data, $mockModel, $mockFileInfo);
    }

    /**
     * 測試修改橫幅時發生橫幅不存在
     *
     * @return void
     */
    public function testModifyNotFound(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬修改資料
        $mockData = $this->makeMockData();

        // 模擬取得橫幅失敗
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn(null);

        // 執行修改橫幅
        $result = $this->srcBanner->modify(1, $mockData);

        // 驗證結果狀態
        $this->assertSame(404, $result->httpCode);
    }

    /**
     * 測試修改橫幅時發生管理員ID不符
     *
     * @return void
     */
    public function testModifyAdminIdMismatch(): void
    {
        // 設定模擬驗證資料至上下文 (不同的管理員ID)
        context()->add('authData', ['id' => 2]);

        // 模擬修改資料
        $mockData = $this->makeMockData();

        // 模擬橫幅Model (admin_id = 1)
        $mockModel = $this->makeMockModel();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 執行修改橫幅
        $result = $this->srcBanner->modify(1, $mockData);

        // 驗證結果狀態
        $this->assertSame(404, $result->httpCode);
    }

    /**
     * 測試修改橫幅時發生更新失敗
     *
     * @return void
     */
    public function testModifyUpdateFail(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬修改資料
        $mockData = $this->makeMockData();

        // 模擬橫幅Model
        $mockModel = $this->makeMockModel();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 模擬更新橫幅失敗
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('update')
            ->willReturn(null);

        // 執行修改橫幅
        $result = $this->srcBanner->modify(1, $mockData);

        // 驗證結果狀態
        $this->assertSame(500, $result->httpCode);
    }

    /**
     * 測試修改橫幅成功
     *
     * @return void
     */
    public function testModifySuccess(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬修改資料
        $mockData = $this->makeMockData();

        // 模擬橫幅Model
        $mockModel = $this->makeMockModel();

        // 模擬檔案資訊
        $mockFileInfo = $this->makeMockFileInfo();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 模擬更新橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('update')
            ->with($mockModel, $mockData)
            ->willReturn($mockModel);

        // 模擬取得檔案資訊
        $this->mockSrcToolFile
            ->expects($this->once())
            ->method('getFileInfo')
            ->with($mockModel->photo_file_id)
            ->willReturn($mockFileInfo);

        // 執行修改橫幅
        $result = $this->srcBanner->modify(1, $mockData);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $this->assertBannerData($result->data, $mockModel, $mockFileInfo);
    }

    /**
     * 測試移除橫幅時發生橫幅不存在
     *
     * @return void
     */
    public function testRemoveNotFound(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬取得橫幅失敗
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn(null);

        // 執行移除橫幅
        $result = $this->srcBanner->remove(1);

        // 驗證結果狀態
        $this->assertSame(404, $result->httpCode);
    }

    /**
     * 測試移除橫幅時發生管理員ID不符
     *
     * @return void
     */
    public function testRemoveAdminIdMismatch(): void
    {
        // 設定模擬驗證資料至上下文 (不同的管理員ID)
        context()->add('authData', ['id' => 2]);

        // 模擬橫幅Model (admin_id = 1)
        $mockModel = $this->makeMockModel();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 執行移除橫幅
        $result = $this->srcBanner->remove(1);

        // 驗證結果狀態
        $this->assertSame(404, $result->httpCode);
    }

    /**
     * 測試移除橫幅時發生刪除失敗
     *
     * @return void
     */
    public function testRemoveDeleteFail(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬橫幅Model
        $mockModel = $this->makeMockModel();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 模擬刪除橫幅失敗
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('delete')
            ->with($mockModel)
            ->willReturn(false);

        // 執行移除橫幅
        $result = $this->srcBanner->remove(1);

        // 驗證結果狀態
        $this->assertSame(500, $result->httpCode);
    }

    /**
     * 測試移除橫幅成功
     *
     * @return void
     */
    public function testRemoveSuccess(): void
    {
        // 設定模擬驗證資料至上下文
        context()->add('authData', ['id' => 1]);

        // 模擬橫幅Model
        $mockModel = $this->makeMockModel();

        // 模擬取得橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('findOneByBannerId')
            ->with(1)
            ->willReturn($mockModel);

        // 模擬刪除橫幅
        $this->mockRepoBanner
            ->expects($this->once())
            ->method('delete')
            ->with($mockModel)
            ->willReturn(true);

        // 執行移除橫幅
        $result = $this->srcBanner->remove(1);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);
    }

    /**
     * 建立模擬橫幅輸入資料
     *
     * @return array
     */
    private function makeMockData(): array
    {
        return [
            'name' => 'test banner',
            'photoFileId' => 1,
            'url' => 'https://example.com/testpage',
            'startAt' => '2024-01-01',
            'endAt' => '2024-12-31',
            'sort' => 1,
            'status' => true,
        ];
    }

    /**
     * 建立模擬橫幅Model
     *
     * @return ModelBanner
     */
    private function makeMockModel(): ModelBanner
    {
        $data = $this->makeMockData();

        $model = new ModelBanner();
        $model->banner_id = 1;
        $model->photo_file_id = $data['photoFileId'];
        $model->name = $data['name'];
        $model->url = $data['url'];
        $model->start_at = $data['startAt'];
        $model->end_at = $data['endAt'];
        $model->sort = $data['sort'];
        $model->status = $data['status'];
        $model->admin_id = 1;

        return $model;
    }

    /**
     * 建立模擬檔案資訊
     *
     * @param int $fileId 檔案ID
     *
     * @return OutputFile
     */
    private function makeMockFileInfo(int $fileId = 1): OutputFile
    {
        return new OutputFile(
            fileId: $fileId,
            name: 'test.jpg',
            extension: 'jpg',
            type: 'image/jpeg',
            size: 1024,
            path: 'public/test.jpg',
            url: 'https://example.com/test.jpg',
        );
    }

    /**
     * 驗證橫幅結果資料
     *
     * @param array $resultData 結果資料
     * @param ModelBanner $mockModel 模擬橫幅Model
     * @param OutputFile $mockFileInfo 模擬檔案資訊
     *
     * @return void
     */
    private function assertBannerData(array $resultData, ModelBanner $mockModel, OutputFile $mockFileInfo): void
    {
        $this->assertSame($mockModel->banner_id, $resultData['bannerId']);
        $this->assertSame($mockModel->photo_file_id, $resultData['photoFileId']);
        $this->assertSame($mockModel->name, $resultData['name']);
        $this->assertSame($mockModel->url, $resultData['url']);
        $this->assertSame($mockModel->start_at, $resultData['startAt']);
        $this->assertSame($mockModel->end_at, $resultData['endAt']);
        $this->assertSame($mockModel->sort, $resultData['sort']);
        $this->assertSame($mockModel->status, $resultData['status']);
        $this->assertSame($mockFileInfo->url, $resultData['photoUrl']);
    }
}