<?php
use \App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('login', [Api\AuthController::class, 'login']);
        $api->post('logout', [Api\AuthController::class, 'logout']);
        $api->post('refresh', [Api\AuthController::class, 'refresh']);
    });
    $api->group(['prefix' => 'factory','middleware' => 'api.auth'], function ($api) {
        $api->post('user', function () {
            $dd= auth('api')->user();
            dd($dd);
        });
    });
});

