<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function getByPrefectureId($prefectureId): JsonResponse
    {
        return response()->json(
            DB::table('cities')
                ->select(
                    'id'
                    , 'name'
                )
                ->where('prefecture_id', $prefectureId)
                ->get());
    }
}
