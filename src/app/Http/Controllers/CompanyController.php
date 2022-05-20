<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Prefecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function getByConditions(Request $request): JsonResponse
    {
        // ユーザID　※未ログイン時は空
        $userId = $request->userId;
        // オプション条件：市区町村
        $cities = $request->cities;
        // オプション条件：料金(下限)
        $priceMin = $request->priceMin;
        // オプション条件：料金(上限)
        $priceMax = $request->priceMax;
        // オプション条件：セールスポイントID
        $sellingPointIds = $request->sellingPointIds;
        $prefecture = Prefecture::find($request->prefectureId);
        $companies = Company::with(['logo', 'paymentMethods', 'holidays',
            'menus' => function ($query) use ($priceMin, $priceMax) {
                $query
                    ->when($priceMin, function ($query, $priceMin) {
                        $query->where(function ($query) use ($priceMin) {
                            return $query->where('price', '>=', $priceMin);
                        });
                    })
                    ->when($priceMax, function ($query, $priceMax) {
                        $query->where(function ($query) use ($priceMax) {
                            return $query->where('price', '<=', $priceMax);
                        });
                    });
            },
            'sellingPoints' => function ($query) use ($sellingPointIds) {
                $query
                    ->when($sellingPointIds, function ($query, $sellingPointIds) {
                        $query->where(function ($query) use ($sellingPointIds) {
                            return $query->whereIn('selling_point_id', $sellingPointIds);
                        });
                    });
            },
            'likes' => function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                });
            }])
            ->where('companies.prefecture', $prefecture->name)
            ->when($cities, function ($query, $cities) {
                $query->where(function ($query) use ($cities) {
                    foreach ($cities as $value) {
                        $query->orWhere('companies.city', 'like', "{$value}%");
                    }
                });
            })
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
                    ]
                    )
                , 'paymentMethods' => $company->paymentMethods
                    ->map(fn($paymentMethod) => [
                        'id' => $paymentMethod->id
                        , 'name' => $paymentMethod->name
                    ]
                    )
                , 'holidays' => $company->holidays
                    ->map(fn($holiday) => [
                        'id' => $holiday->id
                        , 'name' => $holiday->name
                    ]
                    )
                , 'userLike' => sizeof($company->likes) > 0
            ]
            ));
    }

    public function getMenusById($id): JsonResponse
    {
        $company = Company::with('menus')->find($id);
        return response()->json(
            (object)
            [
                'id' => $company->id
                , 'name' => $company->name
                , 'menus' => $company->menus
                ->map(fn($menu) => [
                    'id' => $menu->id
                    , 'name' => $menu->name
                    , 'comment' => $menu->comment
                    , 'price' => $menu->price
                ])
            ]
        );
    }
}
