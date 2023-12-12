<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class HelperTest extends TestCase
{
    use CreatesApplication;

    public function test_to_timestamp_second_exist(): void
    {
        $this->assertTrue(function_exists('to_timestamp_second'));
    }

    public function test_to_timestamp_second_passed(): void
    {
        $this->assertEquals(1234567890, to_timestamp_second(1234567890));
        $this->assertEquals(1702404750, to_timestamp_second(1702404750090));
    }
}
