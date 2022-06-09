<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PrefectureController extends Controller
{
    public function getCitiesByPrefectureId($prefectureId): JsonResponse
    {
        return response()->json(
            DB::table('cities')
                ->select(
                    'id'
                    , 'name'
                )
                ->when($prefectureId, function ($query, $prefectureId) {
                    return $query->where('prefecture_id', $prefectureId);
                })
                ->get()
        );
    }
}
