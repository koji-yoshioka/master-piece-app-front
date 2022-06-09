<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestDataController extends Controller
{
    public function storeCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'zipCode' => 'string|nullable|regex:/^\d{7}$/',
            'logo' => 'file|mimes:jpg,jpeg,png',
            'images' => 'array',
            'images.*.*' => 'file|mimes:jpg,jpeg,png',
        ]);

        // 企業情報を登録
        $id = DB::table('companies')
            ->insertGetId([
                'name' => $request->name
                , 'tel' => $request->tel
                , 'zip_code' => $request->zipCode
                , 'prefecture' => $request->prefecture
                , 'city' => $request->city
                , 'rest_address' => $request->restAddress
                , 'nearest_station' => $request->nearestStation
                , 'business_hours_from' => $request->businessHoursFrom
                , 'business_hours_to' => $request->businessHoursTo
                , 'comment' => $request->comment
                , 'note' => $request->note
                , 'created_at' => now()
                , 'updated_at' => now()
            ]);

        // ロゴがあればS3へ登録
        if ($request->has('logo') && !empty(request()->file('logo'))) {
            $file = request()->file('logo');
            $fileName = Str::random(16) . '.' . $file->extension();
            $originalFileName = $file->getClientOriginalName();
            // テーブル登録
            DB::table('company_logos')
                ->insert(
                    [
                        'company_id' => $id
                        , 'file_name' => $fileName
                        , 'original_file_name' => $originalFileName
                        , 'created_at' => now()
                        , 'updated_at' => now()
                    ]
                );
            // S3登録
            Storage::cloud()
                ->putFileAs("companies/$id/logo", $file, $fileName, 'public');
        }
    }
}
