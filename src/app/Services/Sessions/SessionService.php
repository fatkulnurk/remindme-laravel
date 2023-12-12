<?php

namespace App\Services\Sessions;

use App\Enums\Errors\CommonError;
use App\Enums\Tokens\TokenName;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


/**
 * The TokenName enum represents the name of the token.
 *
 * @package App\Enums\Tokens
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#login
 * */
class SessionService
{
    private int $expires = 20;

    /**
     * Retrieves user information and creates access and refresh tokens.
     *
     * @param array $data The user data for authentication.
     * @throws \Exception When the credentials are invalid.
     * @return array The user information along with the access and refresh tokens.
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#login
     */
    public function login(array $data): array
    {
        Log::info('Retrying login', ['email' => $data['email']]);
        $user = User::query()
            ->select(['id', 'email', 'name', 'password'])
            ->where('email', $data['email'])
            ->first();

        // all message it same, for prevent https://owasp.org/www-project-web-security-testing-guide/v42/4-Web_Application_Security_Testing/03-Identity_Management_Testing/04-Testing_for_Account_Enumeration_and_Guessable_User_Account
        if (blank($user) || !Hash::check($data['password'], $user->password)) {
            Log::error('invalid credentials', ['email' => $data['email']]);

            throw new \Exception(CommonError::ERR_INVALID_CREDS->value);
        }

        Log::info('retrying create access token & refresh token', ['email' => $data['email']]);

        // destroy all tokens
        $user->tokens()->delete();

        // create access token
        $accessToken = $user->createToken(
            name: TokenName::ACCESS_TOKEN->value,
            abilities: TokenName::ACCESS_TOKEN->getAbilities(),
            expiresAt: CarbonImmutable::now()->addSecond($this->expires)
        )->plainTextToken;

        // create refresh token
        $refreshToken = $user->createToken(
            name: TokenName::REFRESH_TOKEN->value,
            abilities: TokenName::REFRESH_TOKEN->getAbilities(),
        )->plainTextToken;

        Log::info('success create access token & refresh token', ['email' => $data['email']]);

        return [
            'user' => $user->only(['id', 'email', 'name']),
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }
}
