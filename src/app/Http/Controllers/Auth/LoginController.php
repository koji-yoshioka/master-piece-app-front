<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
    use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private const GUEST_USER_ID = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override authenticated of AuthenticatesUsers Class.
     *
     * @param Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user): mixed
    {
        return $user;
    }

    /**
     * Override authenticated of AuthenticatesUsers Class.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function loggedOut(Request $request): JsonResponse
    {
        // セッションを再生成する
        $request->session()->regenerate();

        return response()->json();
    }

    /**
     * Auth as guest.
     *
     */
    public function guestLogin()
    {
        if (Auth::loginUsingId(self::GUEST_USER_ID)) {
            return Auth::user();
        }
        return response()->json(['message' => 'ゲストユーザは存在しません。'], 401);
    }
}
