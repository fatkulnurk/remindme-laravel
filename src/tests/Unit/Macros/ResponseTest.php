<?php

namespace Tests\Unit\Macros;

use App\Enums\Errors\CommonError;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Traits\Macroable;
use Tests\CreatesApplication;


/**
 * @package Tests\Unit\Macros
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
class ResponseTest extends TestCase
{
    use CreatesApplication, Macroable;


    public function test_json_Failed_method_is_passed(): void
    {
        foreach (CommonError::cases() as $error) {
            $response = response()->jsonFailed($error->value);
            $this->assertEquals($error->httpStatusCode(), $response->getStatusCode());
            $this->assertJson($response->getContent());
            $this->assertEquals($error->toMap(), json_decode($response->getContent(), true));
        }
    }
}
