<?php

namespace Tests\Unit\Services\Admin;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use App\Enums\Role as EnumRole;
use App\Services\Admin\Auth as SrcAuth;
use App\Services\Tool\Jwt as SrcToolJwt;

/*
 * 單元測試-服務層-管理員驗證
 */

class AuthTest extends TestCase
{
    /**
     * 模擬服務層-JWT Token工具
     * @var MockObject
     */
    private MockObject $mockSrcToolJwt;

    /**
     * 服務層-帳號驗證
     * @var SrcAuth
     */
    private SrcAuth $srcAuth;

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

        // 服務層-帳號驗證
        $this->srcAuth = app()->make(
            SrcAuth::class,
            [
                'toolJwt' => $this->mockSrcToolJwt,
            ]
        );
    }

    /**
     * 測試驗證帳號成功
     * 
     * @return void
     */
    public function testCheckAuthSuccess(): void
    {
        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 設定模擬JWT資料
        $mockJwtData = [
            'id' => 1,
            'role' => EnumRole::Admin->value,
        ];

        // 模擬解碼JWT Token
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('decode')
            ->with($mockJwtToken)
            ->willReturn($mockJwtData);

        // 模擬驗證JWT Token是否在白名單中
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('isInWhitelist')
            ->with($mockJwtToken)
            ->willReturn(true);

        // 執行驗證帳號
        $result = $this->srcAuth->checkByJwtToken($mockJwtToken);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertEquals($mockJwtData['id'], $resultData['id']);
        $this->assertEquals($mockJwtData['role'], $resultData['role']);
    }

    /**
     * 測試解碼JWT Token失敗
     * 
     * @return void
     */
    public function testDecodeFail(): void
    {
        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 模擬解碼JWT Token
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('decode')
            ->with($mockJwtToken)
            ->willReturn([]);

        // 執行驗證帳號
        $result = $this->srcAuth->checkByJwtToken($mockJwtToken);

        // 驗證結果狀態
        $this->assertSame(401, $result->httpCode);
    }

    /**
     * 測試JWT Token不在白名單中
     * 
     * @return void
     */
    public function testIsNotInWhitelist(): void
    {
        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 設定模擬JWT資料
        $mockJwtData = [
            'id' => 1,
            'role' => EnumRole::Admin->value,
        ];

        // 模擬解碼JWT Token
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('decode')
            ->with($mockJwtToken)
            ->willReturn($mockJwtData);

        // 模擬驗證JWT Token是否在白名單中
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('isInWhitelist')
            ->with($mockJwtToken)
            ->willReturn(false);

        // 執行驗證帳號
        $result = $this->srcAuth->checkByJwtToken($mockJwtToken);

        // 驗證結果狀態
        $this->assertSame(401, $result->httpCode);
    }

    /**
     * 測試驗證角色失敗
     * 
     * @return void
     */
    public function testCheckRoleFail(): void
    {
        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 設定模擬JWT資料
        $mockJwtData = [
            'id' => 1,
            'role' => EnumRole::Member->value,
        ];

        // 模擬解碼JWT Token
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('decode')
            ->with($mockJwtToken)
            ->willReturn($mockJwtData);

        // 模擬驗證JWT Token是否在白名單中
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('isInWhitelist')
            ->with($mockJwtToken)
            ->willReturn(true);

        // 執行驗證帳號
        $result = $this->srcAuth->checkByJwtToken($mockJwtToken);

        // 驗證結果狀態
        $this->assertSame(401, $result->httpCode);
    }
}
