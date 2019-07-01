<?php

use Illuminate\Http\Request;

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

Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'], function ($router) {
    $router->post('login', [
        'as' => 'login',
        'middleware' => ['throttle'],
        'uses' => 'AccessTokenController@issueToken',
    ]);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
