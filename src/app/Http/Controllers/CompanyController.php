<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCompanyRequest;
use App\Models\Company;
use App\Models\Prefecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['getLikeCompanies']);
    }

    public function getByConditions(GetCompanyRequest $request): JsonResponse
    {
        // オプション条件：市区町村
        $cities = $request->cities;

        // オプション条件：料金(下限)
        $priceMin = $request->priceMin;
        // オプション条件：料金(上限)
        $priceMax = $request->priceMax;
        $companyIdsByPrice = [];
        if ($priceMin || $priceMax) {
            $companyIdsByPrice = DB::table('company_menus')->select('company_id')
                ->when($priceMin, function ($query, $priceMin) {
                    return $query->where('price', '>=', $priceMin);
                })
                ->when($priceMax, function ($query, $priceMax) {
                    return $query->where('price', '<=', $priceMax);
                })
                ->get()
                ->map(fn($companyId) => $companyId->company_id);
            if (sizeof($companyIdsByPrice) == 0) {
                response()->json([]);
            }
        }

        // オプション条件：セールスポイントID
        $sellingPointIds = $request->sellingPointIds;
        $companyIdsBySellingPoint = [];
        if ($sellingPointIds) {
            // セールスポイント
            $companyIdsBySellingPoint = DB::table('company_selling_point')->select('company_id')
                ->whereIn('selling_point_id', $sellingPointIds)
                ->distinct()
                ->get()
                ->map(fn($companyId) => $companyId->company_id);
            if (sizeof($companyIdsBySellingPoint) == 0) {
                response()->json([]);
            }
        }
        // ユーザID
        $userId = $request->userId;
        $prefecture = Prefecture::find($request->prefectureId);
        $companies = Company::with(['logo', 'holidays', 'menus', 'sellingPoints',
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
            ->when($companyIdsByPrice, function ($query, $companyIdsByPrice) {
                $query->where(function ($query) use ($companyIdsByPrice) {
                    return $query->whereIn('id', $companyIdsByPrice);
                });
            })
            ->when($companyIdsBySellingPoint, function ($query, $companyIdsBySellingPoint) {
                $query->where(function ($query) use ($companyIdsBySellingPoint) {
                    return $query->whereIn('id', $companyIdsBySellingPoint);
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
                    ])
                , 'paymentMethods' => $company->paymentMethods
                    ->map(fn($paymentMethod) => [
                        'id' => $paymentMethod->id
                        , 'name' => $paymentMethod->name
                    ])
                , 'holidays' => $company->holidays
                    ->map(fn($holiday) => [
                        'id' => $holiday->id
                        , 'name' => $holiday->name
                    ])
                , 'userLike' => sizeof($company->likes) > 0
            ])
        );
    }

    public function getById(Request $request, $id): JsonResponse
    {
        $request->validate([
            'userId' => 'nullable|integer',
        ]);
        // ユーザID　※未ログイン時は空
        $userId = $request->userId;

        $company = Company::with(['logo', 'images', 'sellingPoints', 'paymentMethods', 'holidays',
            'likes' => function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                });
            }
        ])->find($id);

        if (!$company) {
            abort(404);
        }

        return response()->json(
            (object)
            [
                'id' => $company->id
                , 'name' => $company->name
                , 'email' => $company->email
                , 'tel' => $company->tel
                , 'zipCode' => $company->zip_code
                , 'prefecture' => $company->prefecture
                , 'city' => $company->city
                , 'restAddress' => $company->rest_address
                , 'nearestStation' => $company->nearest_station
                , 'businessHoursFrom' => $company->business_hours_from
                , 'businessHoursTo' => $company->business_hours_to
                , 'comment' => $company->comment
                , 'note' => $company->note
                , 'logo' => !empty($company->logo) ? $company->logo->file_name : null
                , 'images' => $company->images
                ->map(fn($image) => [
                    'displayNo' => $image->display_no
                    , 'fileName' => $image->file_name
                ])
                , 'sellingPoints' => $company->sellingPoints
                ->map(fn($sellingPoint) => [
                    'id' => $sellingPoint->id
                    , 'name' => $sellingPoint->name
                ])
                , 'paymentMethods' => $company->paymentMethods
                ->map(fn($paymentMethod) => [
                    'id' => $paymentMethod->id
                    , 'name' => $paymentMethod->name
                ])
                , 'holidays' => $company->holidays
                ->map(fn($holiday) => [
                    'id' => $holiday->id
                    , 'name' => $holiday->name
                ])
                , 'userLike' => sizeof($company->likes) > 0
            ]
        );
    }

    public function getMenusById($companyId): JsonResponse
    {
        $company = Company::with('menus')->find($companyId);
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
