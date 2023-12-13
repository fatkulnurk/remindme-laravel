<?php

namespace App\Enums\Tokens;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

/**
 * The TokenAbility enum represents the abilities associated with a token.
 *
 * @package App\Enums\Tokens
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#refresh-access-token
 * */
enum TokenAbility: string
{
    case SESSION_REFRESH = 'session:refresh';

    case REMINDER_CREATE = 'reminder:create';
    case REMINDER_VIEW = 'reminder:view';
    case REMINDER_UPDATE = 'reminder:update';
    case REMINDER_DELETE = 'reminder:delete';

    /**
     * Generates an access token.
     *
     * @return array
     */
    public static function accessToken(): array
    {
        return Cache::remember(
            key: TokenAbility::class . '_accessToken',
            ttl: CarbonImmutable::now()->addMinute(),
            callback: function () {
                $data = [];
                foreach (self::cases() as $case) {
                    if ($case != self::SESSION_REFRESH) {
                        $data[] = $case->value;
                    }
                }

                return $data;
            });
    }

    /**
     * Refreshes the token and returns an array.
     *
     * @return array
     */
    public static function refreshToken(): array
    {
        return Cache::remember(
            key: TokenAbility::class . '_refreshToken',
            ttl: CarbonImmutable::now()->addMinute(),
            callback: function () {
                return [self::SESSION_REFRESH->value];
            }
        );
    }
}
