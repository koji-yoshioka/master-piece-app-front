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
Route::post('/sign-up', 'Auth\RegisterController@register')->name('sign-up');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
// ログインユーザー取得
Route::get('/user', fn() => Auth::user())->name('user');
// パスワード変更
Route::post('/password-change', 'Auth\ChangePasswordController@changePassword')->name('change-password');

// 市区町村取得
Route::get('/cities', 'CityController@getByConditions')->name('city.get-by-conditions');

// 企業情報一覧取得
Route::get('/companies', 'CompanyController@getByConditions')->name('company.get-by-conditions');
// 企業情報取得
Route::get('/company', 'CompanyController@getById')->name('company.get-by-id');
// お気に入り企業情報取得
Route::get('/like/companies', 'CompanyController@getLikeCompanies')->name('company.get-like-companies');


// メニュー取得
Route::get('/company/menu/{companyId}', 'CompanyController@getMenusById')->name('company.get-menus-by-id');

// 予約に必要な諸々の情報を取得
Route::get('/reserve/base-info', 'ReserveController@getBaseInfo')->name('reserve.get-base-info');
// 予約
Route::post('/reserve', 'ReserveController@reserve')->name('reserve.reserve');
// ユーザの予約履歴
Route::get('/user-reserves', 'ReserveController@getUserReserves')->name('reserve.get-user-reserves');


// 曜日マスタ取得
Route::get('/day-of-week', 'DayOfWeekController@getAll')->name('day-of-week.get-all');
// セールスポイントマスタ取得
Route::get('/selling-point', 'SellingPointController@getAll')->name('selling-point.get-all');

// 問い合わせ登録
Route::post('/contact', 'ContactController@register')->name('contact.register');

// お気に入り
Route::post('/like', 'LikeController@toggleLike')->name('toggle-like');
