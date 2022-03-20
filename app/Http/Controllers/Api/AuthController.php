<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthPostRequest;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     * @return \Dingo\Api\Http\Response
     */
    public function login(AuthPostRequest  $request)
    {
        $credentials = $request->only(['username', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
             $this->response->error('登录失败',401);
        }
        return $this->respondWithToken($token);
    }

    /**
     *  Log the user out (Invalidate the token).
     * @return \Dingo\Api\Http\Response
     */
    public function logout()
    {
        auth('api')->logout();
        return  $this->response->noContent();
    }

    /**
     * Refresh a token.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Dingo\Api\Http\Response
     */
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60*24*31
        ]);

    }
}
