<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\LoginRequest;
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
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#login
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return response()->json([]);
    }

    /**
     * Refreshes the session token.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the refreshed token.
     *
     *  @throws \Exception If there is an error refreshing the token.
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#refresh-access-token
     */
    public function refresh(Request $request)
    {
        return response()->json([]);
    }
}
