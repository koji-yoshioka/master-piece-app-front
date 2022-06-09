<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SellingPointController extends Controller
{
    public function getAll(): JsonResponse
    {
        return response()->json(
            DB::table('selling_points')
                ->select(
                    'id'
                    , 'name'
                )
                ->get());
    }
}
