<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'image' => 'nullable|file|max:1024',
        ]);

        $imageFileName = (function () use ($request) {
            // キーのみ→削除なのでnullを入れる
            // キーと値→ファイル名を入れる
            // キーがない→もともとの値
            $hasKey = $request->has('image');
            $hasValue = $request->has('image') && !empty(request()->file('image'));
            if ($hasKey && !$hasValue) {
                return null;
            } else if ($hasValue) {
                return $request->file('image')->getClientOriginalName();
            } else {
                return $request->user()->image_file_name;
            }
        })();

        // テーブル更新
        DB::transaction(function () use ($request, $imageFileName) {
            $user = $request->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->image_file_name = $imageFileName;
            $user->save();
        });
        if ($request->has('image')) {
            Storage::cloud()->deleteDirectory("users/{$request->user()->id}");
        }
        if ($request->has('image') && $imageFileName) {
            Storage::cloud()
                ->putFileAs("users/{$request->user()->id}", $request->file('image'), $imageFileName, 'public');
        }
    }

    public function delete(Request $request)
    {
        $userId = $request->user()->id;
        // テーブル削除
        DB::transaction(function () use ($userId) {
            User::find($userId)->delete();
        });
        // イメージ画像削除
        Storage::cloud()->deleteDirectory("users/{$userId}");
    }
}
