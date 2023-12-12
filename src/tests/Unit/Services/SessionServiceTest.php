<?php

namespace Tests\Unit\Services;

use App\Enums\Errors\CommonError;
use App\Services\Sessions\SessionService;
use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\CreatesApplication;

class SessionServiceTest extends TestCase
{
    use CreatesApplication;

    protected bool $seed = true;

    public static function providerDataPassed()
    {
        return [
            [
                [
                    'email' => 'alice@mail.com',
                    'password' => '123456'
                ]
            ],
            [
                [
                    'email' => 'bob@mail.com',
                    'password' => '123456'
                ]
            ]
        ];
    }

    public static function providerDataFailed()
    {
        return [
            [
                [
                    'email' => 'fatkul@mail.com',
                    'password' => '123456'
                ]
            ],
            [
                [
                    'email' => 'nur@mail.com',
                    'password' => '123456'
                ]
            ],
            [
                [
                    'email' => 'koi@mail.com',
                    'password' => '123456'
                ]
            ],
            [
                [
                    'email' => 'rudin@mail.com',
                    'password' => '123456'
                ]
            ],
        ];
    }

    public function test_create_instance()
    {
        $this->assertInstanceOf(SessionService::class, (new SessionService()));
    }

    #[dataProvider('providerDataPassed')]
    public function test_login_passed($data)
    {
        $tokenData = (new SessionService())->login($data);

        $this->assertIsArray($tokenData);
        $this->assertArrayHasKey('user', $tokenData);
        $this->assertArrayHasKey('access_token', $tokenData);
        $this->assertArrayHasKey('refresh_token', $tokenData);
    }

    #[dataProvider('providerDataFailed')]
    public function test_login_failed($data)
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(CommonError::ERR_INVALID_CREDS->value);
        $this->expectExceptionCode(0);

        (new SessionService())->login($data);
    }

    #[DataProvider('providerDataPassed')]
    public function test_refresh_token_success($data)
    {
        $tokenData = (new SessionService())->login($data);
        $this->assertIsArray($tokenData);

        $refreshToken = $tokenData['refresh_token'];
        $tokenData = (new SessionService())->refreshToken($refreshToken);

        $this->assertIsArray($tokenData);
        $this->assertArrayHasKey('access_token', $tokenData);
    }

    public function test_refresh_token_failed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(CommonError::ERR_INVALID_REFRESH_TOKEN->value);
        $this->expectExceptionCode(0);

        $refreshToken = '1999|fatkulnurk';
        (new SessionService())->refreshToken($refreshToken);
    }
}
