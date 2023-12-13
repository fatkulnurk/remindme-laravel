<?php

namespace App\Providers;

use App\Enums\Errors\CommonError;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('jsonFailed', function (
            string $err,
            string|null $message = null,
            bool $ok = false,
            int $status = 400
        ) {

            $error = CommonError::tryFrom($err);

            if (filled($error)) {
                return Response::json(
                    data: $error->toMap(),
                    status: $error->httpStatusCode()
                );
            }

            return Response::json([
                'ok' => false,
                'err' => 'EXCEPTION_ERROR',
                'msg' => $message ?? $err
            ], $status);
        });
    }
}
