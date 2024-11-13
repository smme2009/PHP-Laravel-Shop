<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Repository\Mgmt\Product\Product as RepoProduct;
use App\Http\Service\Mgmt\Product\Product as SrcProduct;

class ProductTest extends TestCase
{
    use RefreshDatabase; // 測試資料刪除

    private SrcProduct $srcProduct; // Service

    private RepoProduct $repoProduct; // Repository

    // 測試商品資料
    private array $productData = [
        'name' => '測試商品',
        'photoFileId' => 1,
        'price' => 100,
        'description' => '測試商品說明',
        'pageHtml' => '<div>Test Product Html</div>',
        'status' => 1,
        'startTime' => '2024-11-11 15:00:00',
        'endTime' => '2024-11-12 16:00:00',
        'productTypeId' => 1,
    ];

    /**
     * 初始化
     * 
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->srcProduct = $this->app->make(SrcProduct::class);
        $this->repoProduct = $this->app->make(RepoProduct::class);
    }

    /**
     * 測試新增商品(Repository)
     * 
     * @return void
     */
    public function testRepoAddProduct(): void
    {
        $productId = $this->repoProduct
            ->addProduct($this->productData);

        $this->assertIsInt($productId);
    }

    /**
     * 測試新增商品(Service)
     * 
     * @return void
     */
    public function testSrcAddProduct(): void
    {
        $productId = $this->srcProduct
            ->addProduct($this->productData);

        $this->assertIsInt($productId);
    }
}
