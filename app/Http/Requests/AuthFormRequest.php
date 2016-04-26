<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AuthFormRequest extends Request
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
            'mobile' => 'required|max:11',
            'password' => 'required|min:6|confirmed'
        ];
    }
}
