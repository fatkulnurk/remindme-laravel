<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\LoginRequest;
use App\Services\Sessions\SessionService;
use Illuminate\Http\Request;

/**
 * @package App\Http\Controllers\Api
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#login
 * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#refresh-access-token
 */
class SessionController extends Controller
{
    /**
     * Constructs a new instance of the class.
     *
     * @param SessionService $sessionService The session service to use. Defaults to a new instance of SessionService.
     */
    public function __construct(private readonly SessionService $sessionService = new SessionService())
    {
    }

    /**
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#login
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->sessionService->login(
                data: $request->validated()
            );
        } catch (\Exception $exception) {

        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }

    /**
     * Refreshes the session token.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the refreshed token.
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#refresh-access-token
     */
    public function refresh(Request $request)
    {
        try {
            $data = $this->sessionService->refreshToken(
                refreshToken: $request->bearerToken() ?? ''
            );
        } catch (\Exception $exception) {

        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }
}
