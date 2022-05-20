<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DayOfWeekController extends Controller
{

    public function getAll(): JsonResponse
    {
        return response()->json(
            DB::table('days_of_week')
                ->select(
                    'id'
                    , 'name'
                    , 'abbreviation'
                )
                ->get());
    }
}
