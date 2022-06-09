<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required|string|min:8',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);
        DB::transaction(function () use($request) {
            $user = $request->user();
            if (!Hash::check($request->currentPassword, $user->password)) {
                return response()->json(['message' => '現在のパスワードが間違っています。'], 401);
            }
            $user->password = bcrypt($request->newPassword);

            $user->save();
        });
    }
}
