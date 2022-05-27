<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCompaniesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cities' => 'array',
            'cities.*' => 'string',
            'priceMin' => 'nullable|integer',
            'priceMax' => 'nullable|integer',
            'userId' => 'nullable|integer',
            'sellingPointIds' => 'array',
            'sellingPointIds.*' => 'integer',
            'prefectureId' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'cities' => '市区町村',
            'priceMin' => '金額(下限)',
            'priceMax' => '金額(上限)',
            'userId' => 'ユーザID',
            'sellingPointIds' => 'セールスポイント',
            'prefectureId' => '都道府県ID',
        ];
    }
}
