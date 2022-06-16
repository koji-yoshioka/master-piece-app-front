<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserReservesRequest;
use App\Http\Requests\RegisterReserveRequest;
use App\Models\Company;
use App\Models\CompanyReserve;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReserveController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['reserve']);
    }

    public function getReserveInfo(Request $request): JsonResponse
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

        if (!$company) {
            abort(404);
        } else if (sizeof($company->menus) == 0) {
            abort(404);
        }

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

    public function store(RegisterReserveRequest $request)
    {
        DB::table('company_reserves')->insert(
            [
                'company_id' => $request->companyId
                , 'company_menu_id' => $request->menuId
                , 'user_id' => $request->userId
                , 'date' => $request->date
                , 'from' => $request->from
                , 'to' => $request->to
                , 'created_at' => now()
                , 'updated_at' => now()
            ]
        );
    }

    public function getReservesByUserId($userId): JsonResponse
    {
        return response()->json(
            DB::table('company_reserves')
                ->join('companies', 'company_reserves.company_id', '=', 'companies.id')
                ->join('company_menus', 'company_reserves.company_menu_id', '=', 'company_menus.id')
                ->select(
                    'company_reserves.id'
                    , 'company_reserves.date'
                    , 'company_reserves.from'
                    , 'company_reserves.to'
                    , 'companies.name as companyName'
                    , 'company_menus.name as menuName'
                    , 'company_menus.price'
                )
                ->where([
                    ['company_reserves.user_id', '=', $userId],
                    ['company_reserves.date', '>=', date('Ymd')],
                ])
                ->orderBy('company_reserves.date', 'desc')
                ->get()
        );
    }

    public function delete($reserveId) {
        CompanyReserve::destroy($reserveId);
    }
}
