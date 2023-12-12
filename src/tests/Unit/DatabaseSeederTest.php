<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\CreatesApplication;

/**
 * @package Tests\Unit
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
class DatabaseSeederTest extends TestCase
{
    use CreatesApplication;

    protected bool $seed = true;

    public static function providerData()
    {
        return [
            [
                'email' => 'alice@mail.com',
                'password' => '123456'
            ],
            [
                'email' => 'bob@mail.com',
                'password' => '123456'
            ]
        ];
    }

    public function test_count_user_after_migrate(): void
    {
        $this->assertDatabaseCount('users', 2);
    }

    #[dataProvider('providerData')]
    public function test_has_valid_user_by_email($email, $password)
    {
        $this->assertDatabaseHas('users', compact('email'));
    }

    #[dataProvider('providerData')]
    public function test_has_valid_user_by_email_with_failed_scenario($email, $password)
    {
        $email = $email . '__'; // sengaja di append, biar emailnya tidak valid
        $this->assertDatabaseMissing('users', compact('email'));
    }

    #[dataProvider('providerData')]
    public function test_has_password_valid($email, $password)
    {
        $user = User::query()
            ->select(['id', 'email', 'password'])
            ->where('email', $email)
            ->first();

        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertEquals($email, $user->email);
    }

    #[dataProvider('providerData')]
    public function test_has_password_valid_with_failed_scenario($email, $password)
    {
        $password = $password . '__'; // sengaja di append, biar passwordnya tidak valid
        $user = User::query()
            ->select(['id', 'email', 'password'])
            ->where('email', $email)
            ->first();

        $this->assertFalse(Hash::check($password, $user->password));
        $this->assertEquals($email, $user->email);
    }
}
