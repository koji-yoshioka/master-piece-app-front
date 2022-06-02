<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'currentPassword' => 'required|string|min:8',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);
        $user = $request->user();
        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json([
                'message' => '現在のパスワードが間違っています'
            ], 401);
        }
        $user->password = bcrypt($request->newPassword);
        if ($user->save()) {
            return response()->json([
                'message' => 'パスワードが変更されました'
            ]);
        } else {
            return response()->json([
                'message' => 'エラーが発生しました。再度実行してください。'
            ], 500);
        }
    }

}
