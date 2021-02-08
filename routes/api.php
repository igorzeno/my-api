<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post("login", "AuthController@login");
Route::post("register", "AuthController@register");

Route::get('/user', "UserController@index");
Route::get('/tag', "TagController@index");

Route::get('/news/{news}', "NewsController@show");
Route::patch('/news/{id}', "NewsController@update");
Route::get('/news/reopen/{id}', "NewsController@reopen");

Route::group(["middleware" => "auth.jwt"], function () {
    Route::get("logout", "AuthController@logout");
    Route::resource("news", "NewsController");
});
