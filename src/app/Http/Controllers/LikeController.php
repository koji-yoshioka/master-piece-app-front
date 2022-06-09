<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getLikes($userId): JsonResponse
    {
        $companyIds = DB::table('company_likes')->select('company_id')
            ->where('user_id', $userId)
            ->get()
            ->map(fn($companyId) => $companyId->company_id);

        $companies = Company::with(['logo', 'holidays', 'sellingPoints', 'likes'])
            ->whereIn('id', $companyIds)
            ->get();

        return response()->json($companies
            ->map(fn($company) => [
                'id' => $company->id
                , 'name' => $company->name
                , 'tel' => $company->tel
                , 'prefecture' => $company->prefecture
                , 'city' => $company->city
                , 'restAddress' => $company->rest_address
                , 'nearestStation' => $company->nearest_station
                , 'businessHoursFrom' => $company->business_hours_from
                , 'businessHoursTo' => $company->business_hours_to
                , 'comment' => $company->comment
                , 'logo' => !empty($company->logo) ? $company->logo->file_name : null
                , 'sellingPoints' => $company->sellingPoints
                    ->map(fn($sellingPoint) => [
                        'id' => $sellingPoint->id
                        , 'name' => $sellingPoint->name
                    ])
                , 'holidays' => $company->holidays
                    ->map(fn($holiday) => [
                        'id' => $holiday->id
                        , 'name' => $holiday->name
                    ])
                , 'userLike' => true
            ])
        );
    }


    public function toggleLike(LikeRequest $request)
    {
        DB::transaction(function () use ($request) {
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
        });
    }
}
