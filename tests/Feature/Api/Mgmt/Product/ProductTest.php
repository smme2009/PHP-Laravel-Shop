<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Admin;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 測試新增商品
     * 
     * @return void
     */
    public function testAddProduct(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->post('/api/mgmt/login', [
            'account' => $admin->account,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $jwtToken = $response->json('data.jwtToken');

        $header = [
            'Authorization' => 'Bearer ' . $jwtToken,
        ];

        // 建立商品分類
        $response = $this->withHeaders($header)
            ->post('/api/mgmt/product/type', [
                'name' => '測試商品類型',
                'status' => 1,
            ]);

        $response->assertStatus(200);

        $productTypeId = $response->json('data.productTypeId');

        // 上傳商品圖片
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->withHeaders($header)
            ->post('/api/mgmt/product/photo', [
                'photo' => $file,
            ]);

        $response->assertStatus(200);

        // 檢測檔案
        $filePath = '/public/product/' . $file->hashName();
        $fileSystem = Storage::disk('test');
        $fileSystem->assertExists('/public/product/' . $file->hashName());

        $photoFileId = $response->json('data.fileInfo.fileId');

        $response = $this->withHeaders($header)
            ->post('/api/mgmt/product', [
                'name' => '測試商品名稱',
                'photoFileId' => $photoFileId,
                'price' => 1000,
                'description' => '測試商品介紹',
                'pageHtml' => '<div>Test Product Html</div>',
                'status' => 1,
                'startTime' => '2024-11-11 15:00:00',
                'endTime' => '2024-11-12 16:00:00',
                'productTypeId' => $productTypeId,
            ]);

        $response->assertStatus(200);

        // 刪除檔案
        $fileSystem->delete($filePath);
    }
}
