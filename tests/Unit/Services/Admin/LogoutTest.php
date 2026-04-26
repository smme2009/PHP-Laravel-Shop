<?php

namespace Tests\Unit\Services\Admin;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use App\Services\Admin\Logout as SrcLogout;
use App\Services\Tool\Jwt as SrcToolJwt;

/*
 * 單元測試-服務層-管理員登出
 */

class LogoutTest extends TestCase
{
    /**
     * 模擬服務層-JWT Token工具
     * @var MockObject
     */
    private MockObject $mockSrcToolJwt;

    /**
     * 服務層-管理員登出
     * @var SrcLogout
     */
    private SrcLogout $srcLogout;

    /**
     * 初始化
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // 模擬服務層-JWT Token工具
        $this->mockSrcToolJwt = $this->createMock(SrcToolJwt::class);

        // 服務層-管理員登出
        $this->srcLogout = app()->make(
            SrcLogout::class,
            [
                'toolJwt' => $this->mockSrcToolJwt,
            ]
        );
    }

    /**
     * 測試登出成功
     * 
     * @return void
     */
    public function testLogoutSuccess(): void
    {
        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 設定模擬驗證資料至上下文
        context()->add('authData', [
            'jwtToken' => $mockJwtToken,
        ]);

        // 模擬將JWT Token從白名單中移除
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('removeFromWhitelist')
            ->with($mockJwtToken)
            ->willReturn(true);

        // 執行登出
        $result = $this->srcLogout->logout();

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);
    }

    /**
     * 測試將JWT Token從白名單中移除失敗
     * 
     * @return void
     */
    public function testRemoveFromWhitelistFail(): void
    {
        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 設定模擬驗證資料至上下文
        context()->add('authData', [
            'jwtToken' => $mockJwtToken,
        ]);

        // 模擬將JWT Token從白名單中移除失敗
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('removeFromWhitelist')
            ->with($mockJwtToken)
            ->willReturn(false);

        // 執行登出
        $result = $this->srcLogout->logout();

        // 驗證結果狀態
        $this->assertSame(400, $result->httpCode);
    }
}
