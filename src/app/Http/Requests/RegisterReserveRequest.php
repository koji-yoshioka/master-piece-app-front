<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterReserveRequest extends FormRequest
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
            'menuId' => 'required|integer',
            'userId' => 'required|integer',
            'date' => 'required',
            'from' => 'required',
            'to' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'companyId' => '企業ID',
            'menuId' => 'メニューID',
            'userId' => 'ユーザID',
            'date' => '日付',
            'from' => '開始時間',
            'to' => '終了時間',
        ];
    }
}
