<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggleLike(LikeRequest $request)
    {
        $companyId = $request->companyId;
        $userId = $request->userId;

        $like = DB::table('company_likes')
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();
        if ($like) {
            // あれば削除
            DB::table('company_likes')
                ->where('company_id', $companyId)
                ->where('user_id', $userId)
                ->delete();
        } else {
            // なければ登録
            DB::table('company_likes')
                ->insert(
                    [
                        'company_id' => $companyId
                        , 'user_id' => $userId
                        , 'created_at' => now()
                        , 'updated_at' => now()
                    ]
                );
        }
    }
}
