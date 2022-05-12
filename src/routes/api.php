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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ユーザ登録
Route::post('/sign-up', 'Auth\RegisterController@register')->name('sign-up');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// 市区町村取得
Route::get('/city/{prefectureId}', 'CityController@getByPrefectureId')->name('city.get-by-prefecture-id');

// 企業情報取得
Route::get('/company', 'CompanyController@getByConditions')->name('company.get-by-conditions');
