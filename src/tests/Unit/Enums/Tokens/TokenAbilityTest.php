<?php

namespace Tests\Unit\Enums\Tokens;

use App\Enums\Tokens\TokenAbility;
use Tests\TestCase;
use Tests\CreatesApplication;

/**
 * @package Tests\Unit\Services
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
class TokenAbilityTest extends TestCase
{
    use CreatesApplication;

    public function test_get_values_as_array()
    {
        $cases = TokenAbility::cases();
        $this->assertIsArray($cases);
    }

    public function test_get_value()
    {
        $tokenName = TokenAbility::SESSION_REFRESH;
        $this->assertEquals('session:refresh', $tokenName->value);
    }

    public function test_get_name()
    {
        $tokenName = TokenAbility::SESSION_REFRESH;
        $this->assertEquals(TokenAbility::SESSION_REFRESH->name, $tokenName->name);
    }
}
