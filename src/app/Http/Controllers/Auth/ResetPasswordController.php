<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    // server\config\auth.phpで設定していない場合のデフォルト
    protected $expires = 600 * 5;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        // guestミドルウェアはRedirectIfAuthenticatedクラスを指定しているので
        // 認証済み（ログイン済み）の状態でログインページにアクセスすると、ログイン後のトップページにリダイレクトする
        $this->middleware('guest');

        // server\config\auth.phpで設定した値を取得、ない場合はもとの値
        $this->expires = config('auth.reset_password_expires', $this->expires);
    }

    public function reset(Request $request) {
        $validator = $this->validator($request->all());
        $token = $request->token;
        $passwordReset = PasswordReset::where('token', $token)->first();
        // 追加のバリデーション
        $validator->after(function ($validator) use ($passwordReset, &$user) {
            if (!$passwordReset) {
                $validator->errors()->add('token', __('invalid token.'));
            }
            // トークン期限切れチェック
            $isExpired = $this->tokenExpired($passwordReset->created_at);
            if ($isExpired) {
                $validator->errors()->add('token', __('Expired token.'));
            }
            // ユーザの取得
            $user = User::where('email', $passwordReset->email)->first();
            // ユーザの存在チェック
            if (!$user) {
                $validator->errors()->add('token', __('is not user.'));
            }
        });
        $validator->validate();

        $user = DB::transaction(function () use ($request, $passwordReset, $user) {
            // リセットパスワードテーブルからデータを削除
            PasswordReset::destroy($passwordReset->email);
            $user->password = Hash::make($request->password);
            $user->setRememberToken(Str::random(60));
            $user->save();
        });
        event(new PasswordResetEvent($user));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'token' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
    }

    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }

    public function getByToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
        ]);
        $passwordResets = PasswordReset::where('token', $request->token)->get();
        if (sizeof($passwordResets) == 1) {
            return response()->json(
                (object)
                [
                    'email' => $passwordResets[0]->email,
                    'token' => $passwordResets[0]->token,
                ]
            );
        } else {
            return response()->json(['message' => '無効なトークンです。'], 401);
        }
    }
}
