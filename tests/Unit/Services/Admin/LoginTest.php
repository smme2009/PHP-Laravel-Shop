<?php

namespace Tests\Unit\Services\Admin;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Enums\Role as EnumRole;
use App\Services\Admin\Login as SrcLogin;
use App\Services\Tool\Jwt as SrcToolJwt;

/*
 * 單元測試-服務層-管理員登入
 */

class LoginTest extends TestCase
{
    /**
     * 模擬框架底層-Auth-Guard
     * @var MockObject
     */
    private MockObject $mockGuard;

    /**
     * 模擬服務層-JWT Token工具
     * @var MockObject
     */
    private MockObject $mockSrcToolJwt;

    /**
     * 服務層-管理員登入
     * @var SrcLogin
     */
    private SrcLogin $srcLogin;

    /**
     * 初始化
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // 模擬框架底層-Auth-Guard
        $this->mockGuard = $this->createMock(StatefulGuard::class);

        // 模擬服務層-JWT Token工具
        $this->mockSrcToolJwt = $this->createMock(SrcToolJwt::class);

        // 服務層-管理員登入
        $this->srcLogin = app()->make(
            SrcLogin::class,
            [
                'toolJwt' => $this->mockSrcToolJwt,
            ]
        );
    }

    /**
     * 測試登入成功
     * 
     * @return void
     */
    public function testLoginSuccess(): void
    {
        // 角色
        $role = EnumRole::Admin->value;

        // 模擬登入資料
        $mockLoginData = [
            'account' => 'testtest',
            'password' => 'testtest',
        ];

        // 模擬帳號資料
        $mockAccountData = [
            'admin_id' => 1,
        ];

        // 模擬權限資料
        $mockAuthData = [
            'role' => $role,
            'id' => 1,
        ];

        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 模擬管理員角色
        Auth::shouldReceive('guard')
            ->with($role)
            ->twice()
            ->andReturn($this->mockGuard);

        // 模擬驗證身份
        $this->mockGuard
            ->expects($this->once())
            ->method('once')
            ->with($mockLoginData)
            ->willReturn(true);

        // 模擬取得登入者資料
        $this->mockGuard
            ->expects($this->once())
            ->method('user')
            ->willReturn((object)$mockAccountData);

        // 模擬編碼JWT Token
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('encode')
            ->with($mockAuthData)
            ->willReturn($mockJwtToken);

        // 模擬將JWT Token加入白名單
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('addToWhitelist')
            ->with($mockJwtToken)
            ->willReturn(true);

        // 執行登入
        $result = $this->srcLogin->login($mockLoginData);

        // 驗證結果狀態
        $this->assertSame(200, $result->httpCode);

        // 驗證結果資料
        $resultData = $result->data;
        $this->assertSame($mockJwtToken, $resultData['jwtToken']);
    }

    /**
     * 測試欄位驗證失敗
     * 
     * @return void
     */
    public function testValidationFail(): void
    {
        // 設定模擬登入資料
        $mockData = [
            'account' => 'test',
            'password' => 'test',
        ];

        // 執行登入
        $result = $this->srcLogin->login($mockData);

        // 驗證結果狀態
        $this->assertSame(422, $result->httpCode);
    }

    /**
     * 測試登入身份驗證失敗
     * 
     * @return void
     */
    public function testAuthFail(): void
    {
        // 角色
        $role = EnumRole::Admin->value;

        // 設定模擬登入資料
        $mockLoginData = [
            'account' => 'testtest',
            'password' => 'testtest',
        ];

        // 模擬管理員角色
        Auth::shouldReceive('guard')
            ->with($role)
            ->once()
            ->andReturn($this->mockGuard);

        // 模擬驗證身份
        $this->mockGuard
            ->expects($this->once())
            ->method('once')
            ->with($mockLoginData)
            ->willReturn(false);

        // 執行登入
        $result = $this->srcLogin->login($mockLoginData);

        // 驗證結果狀態
        $this->assertSame(400, $result->httpCode);
    }

    /**
     * 測試取得JWT Token失敗
     * 
     * @return void
     */
    public function testGetJwtTokenFail(): void
    {
        // 角色
        $role = EnumRole::Admin->value;

        // 模擬登入資料
        $mockLoginData = [
            'account' => 'testtest',
            'password' => 'testtest',
        ];

        // 模擬帳號資料
        $mockAccountData = [
            'admin_id' => 1,
        ];

        // 模擬權限資料
        $mockAuthData = [
            'role' => $role,
            'id' => 1,
        ];

        // 設定模擬JWT Token
        $mockJwtToken = '';

        // 模擬管理員角色
        Auth::shouldReceive('guard')
            ->with($role)
            ->twice()
            ->andReturn($this->mockGuard);

        // 模擬驗證登入身份
        $this->mockGuard
            ->expects($this->once())
            ->method('once')
            ->with($mockLoginData)
            ->willReturn(true);

        // 模擬取得登入者資料
        $this->mockGuard
            ->expects($this->once())
            ->method('user')
            ->willReturn((object)$mockAccountData);

        // 模擬編碼JWT Token失敗
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('encode')
            ->with($mockAuthData)
            ->willReturn($mockJwtToken);

        // 執行登入
        $result = $this->srcLogin->login($mockLoginData);

        // 驗證結果狀態
        $this->assertSame(500, $result->httpCode);
    }

    /**
     * 測試加入白名單失敗
     * 
     * @return void
     */
    public function testAddToWhitelistFail(): void
    {
        // 角色
        $role = EnumRole::Admin->value;

        // 模擬登入資料
        $mockLoginData = [
            'account' => 'testtest',
            'password' => 'testtest',
        ];

        // 模擬帳號資料
        $mockAccountData = [
            'admin_id' => 1,
        ];

        // 模擬權限資料
        $mockAuthData = [
            'role' => $role,
            'id' => 1,
        ];

        // 設定模擬JWT Token
        $mockJwtToken = 'mock.jwt.token';

        // 模擬管理員角色
        Auth::shouldReceive('guard')
            ->with($role)
            ->twice()
            ->andReturn($this->mockGuard);

        // 模擬驗證登入身份
        $this->mockGuard
            ->expects($this->once())
            ->method('once')
            ->with($mockLoginData)
            ->willReturn(true);

        // 模擬取得登入者資料
        $this->mockGuard
            ->expects($this->once())
            ->method('user')
            ->willReturn((object)$mockAccountData);

        // 模擬編碼JWT Token
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('encode')
            ->with($mockAuthData)
            ->willReturn($mockJwtToken);

        // 模擬將JWT Token加入白名單失敗
        $this->mockSrcToolJwt
            ->expects($this->once())
            ->method('addToWhitelist')
            ->with($mockJwtToken)
            ->willReturn(false);

        // 執行登入
        $result = $this->srcLogin->login($mockLoginData);

        // 驗證結果狀態
        $this->assertSame(500, $result->httpCode);
    }
}
