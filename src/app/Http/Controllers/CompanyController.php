<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Prefecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function getByConditions(Request $request): JsonResponse
    {
        // オプション条件：市区町村
        $cities = $request->cities;
        $prefecture = Prefecture::find($request->prefectureId);
        $companies = Company::with(['logo', 'sellingPoints', 'paymentMethods', 'holidays'])
            ->where('companies.prefecture', $prefecture->name)
            ->when($cities, function ($query, $cities) {
                $query->where(function ($query) use ($cities) {
                    foreach ($cities as $value) {
                        $query->orWhere('companies.city', 'like', "{$value}%");
                    }
                });
            })
            ->get();

        return response()->json($companies->map(fn($company) => [
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
            , 'sellingPoints' => $company->sellingPoints->map(fn($sellingPoint) => [
                'id' => $sellingPoint->id
                , 'name' => $sellingPoint->name
            ]
            )
            , 'paymentMethods' => $company->paymentMethods->map(fn($paymentMethod) => [
                'id' => $paymentMethod->id
                , 'name' => $paymentMethod->name
            ]
            )
            , 'holidays' => $company->holidays->map(fn($holiday) => [
                'id' => $holiday->id
                , 'name' => $holiday->name
            ]
            )
        ]
        ));
    }
}
