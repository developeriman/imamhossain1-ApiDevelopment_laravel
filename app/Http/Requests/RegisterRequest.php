<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|between:3,30',
            'last_name' => 'required|string|between:3,30',
            'username' => 'required|string|unique:tbl_users',
            'email' => 'required|string|unique:tbl_users',
            'password' => 'required|min:5|max:15'
        ];
    }
}
