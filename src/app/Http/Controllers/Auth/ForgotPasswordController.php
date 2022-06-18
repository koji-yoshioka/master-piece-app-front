<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function forgot(Request $request)
    {
        $this->validateEmail($request);

        if (User::where('email', $request->email)->doesntExist()) {
            // セキュリティを考慮して、メールアドレスに紐づくデータがなくても正常なレスポンスを返す
            return response()->json([]);
        }

        DB::transaction(function () use($request) {
            $token = $this->createToken();

            PasswordReset::destroy($request->email);
            $passwordReset = new PasswordReset($request->all());
            $passwordReset->token = $token;
            $passwordReset->save();

            $this->sendPasswordResetMail($request->email, $token);

            return $passwordReset;
        });
    }

    private function createToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    private function sendPasswordResetMail($email, $token)
    {
        Mail::to($email)
            ->send(new PasswordResetMail($token));
    }
}
