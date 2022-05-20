<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'customerEmail' => ['required', 'string', 'email', 'max:255'],
            'comment' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'customerEmail'           => 'メールアドレス',
            'comment'        => 'お問い合わせ内容',
        ];
    }
}
