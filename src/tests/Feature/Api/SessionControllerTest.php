<?php

namespace Tests\Feature\Api;

use App\Enums\Errors\CommonError;
use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\CreatesApplication;

/**
 * @package Tests\Feature\Api
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
class SessionControllerTest extends TestCase
{
    use CreatesApplication;

    protected bool $seed = true;

    public static function providerDataPass()
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

    #[DataProvider('providerDataPass')]
    public function test_login_passed($data): void
    {
        $response = $this->post(uri: '/api/session', data: $data);
        $data = $response->json();

        $response->assertStatus(200);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('ok', $data);
        $this->assertTrue($data['ok']);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('user', $data['data']);
        $this->assertArrayHasKey('id', $data['data']['user']);
        $this->assertArrayHasKey('name', $data['data']['user']);
        $this->assertArrayHasKey('email', $data['data']['user']);
        $this->assertArrayNotHasKey('created_at', $data['data']['user']);
        $this->assertArrayNotHasKey('updated_at', $data['data']['user']);
        $this->assertArrayHasKey('access_token', $data['data']);
        $this->assertArrayHasKey('refresh_token', $data['data']);
    }

    #[DataProvider('providerDataFailed')]
    public function test_login_failed($data): void
    {
        $error = CommonError::ERR_INVALID_CREDS;
        $response = $this->post(uri: '/api/session', data: $data);
        $data = $response->json();

        $response->assertStatus($error->httpStatusCode());

        $this->assertIsArray($data);
        $this->assertArrayHasKey('ok', $data);
        $this->assertArrayHasKey('err', $data);
        $this->assertArrayHasKey('msg', $data);

        $this->assertFalse($data['ok']);
        $this->assertEquals($error->value, $data['err']);
        $this->assertEquals($error->message(), $data['msg']);

        $this->assertEquals($error->toMap(), $data);
    }


    #[DataProvider('providerDataFailed')]
    public function test_login_with_validation_failed($data): void
    {
        $error = CommonError::ERR_BAD_REQUEST;
        $response = $this->post(uri: '/api/session', data: []);
        $data = $response->json();

        $response->assertStatus($error->httpStatusCode());

        $this->assertIsArray($data);
        $this->assertArrayHasKey('ok', $data);
        $this->assertArrayHasKey('err', $data);
        $this->assertArrayHasKey('msg', $data);

        $this->assertFalse($data['ok']);
        $this->assertEquals($error->value, $data['err']);
    }


    #[DataProvider('providerDataPass')]
    public function test_refresh_token_pass($data): void
    {
        $responseCreateSession = $this->post(uri: '/api/session', data: $data);
        $responseCreateSession->assertStatus(200);
        $responseData = $responseCreateSession->json();
        $refreshToken = $responseData['data']['refresh_token'];
        $response = $this->put(uri: '/api/session', data: $data, headers: [
            'Authorization' => 'Bearer ' . $refreshToken
        ]);
        $data = $response->json();

        $response->assertStatus(200);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('ok', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('access_token', $data['data']);

        $this->assertTrue($data['ok']);
    }

    #[DataProvider('providerDataPass')]
    public function test_refresh_token_fails($data): void
    {
        $error = CommonError::ERR_INVALID_REFRESH_TOKEN;
        $refreshToken = '1999|fatkulnurkoirudin';
        $response = $this->put(uri: '/api/session', data: $data, headers: [
            'Authorization' => 'Bearer ' . $refreshToken
        ]);
        $data = $response->json();

        $response->assertStatus(401);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('ok', $data);
        $this->assertArrayHasKey('err', $data);
        $this->assertArrayHasKey('msg', $data);

        $this->assertFalse($data['ok']);
        $this->assertEquals($error->value, $data['err']);
        $this->assertEquals($error->message(), $data['msg']);
    }
}
