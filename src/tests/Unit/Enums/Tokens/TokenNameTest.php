<?php

namespace Tests\Unit\Enums\Tokens;

use App\Enums\Tokens\TokenName;
use Tests\TestCase;
use Tests\CreatesApplication;

/**
 * @package Tests\Unit\Services
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
class TokenNameTest extends TestCase
{
    use CreatesApplication;

    public function test_get_values_as_array()
    {
        $cases = TokenName::cases();
        $this->assertIsArray($cases);
    }

    public function test_get_value()
    {
        $tokenName = TokenName::ACCESS_TOKEN;
        $this->assertEquals(TokenName::ACCESS_TOKEN->value, $tokenName->value);

        $tokenName = TokenName::REFRESH_TOKEN;
        $this->assertEquals(TokenName::REFRESH_TOKEN->value, $tokenName->value);
    }

    public function test_get_name()
    {
        $tokenName = TokenName::ACCESS_TOKEN;
        $this->assertEquals(TokenName::ACCESS_TOKEN->name, $tokenName->name);

        $tokenName = TokenName::REFRESH_TOKEN;
        $this->assertEquals(TokenName::REFRESH_TOKEN->name, $tokenName->name);
    }
}
