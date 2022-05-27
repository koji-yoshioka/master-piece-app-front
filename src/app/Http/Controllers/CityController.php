<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCitiesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{

    public function getByConditions(GetCitiesRequest $request): JsonResponse
    {
        $prefectureId = $request->prefectureId;

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
