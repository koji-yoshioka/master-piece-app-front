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

Route::middleware('auth:api')->get('/user', fn(Request $request) => $request->user()
);

// ユーザ登録
Route::post('/sign-up', 'Auth\RegisterController@register');
// ログイン
Route::post('/login', 'Auth\LoginController@login');
// ゲストログイン
Route::post('/login/guest', 'Auth\LoginController@guestLogin');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout');
// ログインユーザー取得
Route::get('/user', fn() => Auth::user());
// パスワード変更
Route::post('/password-change', 'Auth\ChangePasswordController@changePassword');
// ユーザ更新
Route::put('/user', 'UserController@update');
// 退会
Route::delete('/user', 'UserController@delete');

// 市区町村取得
Route::get('/prefecture/city/{prefectureId}', 'PrefectureController@getCitiesByPrefectureId')
    ->where('prefectureId', '[0-9]+');

// 企業情報一覧取得
Route::get('/company', 'CompanyController@getByConditions');
// 企業情報取得
Route::get('/company/{id}', 'CompanyController@getById')
    ->where('id', '[0-9]+');

// メニュー取得
Route::get('/company/menu/{companyId}', 'CompanyController@getMenusById')
    ->where('companyId', '[0-9]+');

// 予約に必要な諸々の情報を取得
Route::get('/reserve/reserve-info', 'ReserveController@getReserveInfo');
// 予約
Route::post('/reserve', 'ReserveController@store');
// 予約取得
Route::get('/reserve/{userId}', 'ReserveController@getReservesByUserId')
    ->where('userId', '[0-9]+');
// 予約キャンセル
Route::delete('/reserve/{reserveId}', 'ReserveController@delete')
    ->where('reserveId', '[0-9]+');

// 曜日マスタ取得
Route::get('/day-of-week', 'DayOfWeekController@getAll');
// セールスポイントマスタ取得
Route::get('/selling-point', 'SellingPointController@getAll');

// 問い合わせ登録
Route::post('/contact', 'ContactController@store');

// お気に入り企業情報取得
Route::get('/like/{userId}', 'LikeController@getLikes')
    ->where('userId', '[0-9]+');
// お気に入り
Route::post('/like', 'LikeController@toggleLike');
