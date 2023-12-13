<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @link https://github.com/riandyrn/remindme-laravel/blob/3f565565ca94205cffe01d3827c0478a1ae9db4c/docs/rest_api.md?plain=1#L27
     * @link https://github.com/riandyrn/remindme-laravel/blob/3f565565ca94205cffe01d3827c0478a1ae9db4c/docs/rest_api.md?plain=1#L28
     */
    public function run(): void
    {
        User::factory()
            ->count(2)
            ->sequence(
                ['name' => 'alice', 'email' => 'alice@mail.com'],
                ['name' => 'bob', 'email' => 'bob@mail.com'],
            )
            ->state([
                'password' => Hash::make('123456')
            ])
            ->create();
    }
}
