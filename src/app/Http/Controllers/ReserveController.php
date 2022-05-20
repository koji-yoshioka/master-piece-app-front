<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReserveController extends Controller
{

    public function getBaseInfo(Request $request): JsonResponse
    {
        $companyId = $request->companyId;
        $menuId = $request->menuId;

        $company = Company::with([
            'menus' => function ($query) use ($menuId) {
                $query->where('id', '=', $menuId);
            }
            , 'reserves' => function ($query) {
                $query->where('date', '>=', date('Ymd'));
            }
            , 'holidays'])->find($companyId);
        return response()->json(
            (object)
            [
                'id' => $company->id
                , 'name' => $company->name
                , 'businessHoursFrom' => $company->business_hours_from
                , 'businessHoursTo' => $company->business_hours_to
                , 'menu' => $company->menus
                ->map(fn($menu) => [
                    'id' => $menu->id
                    , 'name' => $menu->name
                    , 'minutesLength' => $menu->minutes_length
                ])[0]
                , 'reserves' => $company->reserves
                ->mapToGroups(fn($reserve) => [$reserve['date'] =>
                    [
                        'date' => $reserve['date']
                        , 'from' => $reserve['from']
                        , 'to' => $reserve['to']
                    ]
                ])
                , 'holidays' => $company->holidays
                ->map(fn($holiday) => [
                    'id' => $holiday->id
                    , 'name' => $holiday->name
                    , 'abbreviation' => $holiday->abbreviation
                ])
            ]
        );
    }

    public function reserve(Request $request)
    {
        DB::table('company_reserves')->insert(
            [
                'company_id' => $request->companyId
                , 'user_id' => $request->userId
                , 'company_menu_id' => $request->userId
                , 'date' => $request->date
                , 'from' => $request->from
                , 'to' => $request->to
                , 'created_at' => now()
                , 'updated_at' => now()
            ]
        );
    }
}
