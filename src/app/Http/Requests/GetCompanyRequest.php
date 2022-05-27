<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCompanyRequest extends FormRequest
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
            'companyId' => 'required|integer',
            'userId' => 'nullable|integer',
        ];
    }

    public function attributes()
    {
        return [
            'companyId'           => '企業ID',
            'userId'        => 'ユーザID',
        ];
    }
}
