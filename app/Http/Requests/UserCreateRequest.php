<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'member_id'  => 'required|unique:users',
            'mobile'     => 'unique:users',
            'first_name' => 'required|regex:/(^[A-Za-z0-9_-_ ]+$)+/',
            'last_name'  => 'required|regex:/(^[A-Za-z0-9_-_ ]+$)+/',
            'email'      => 'required|unique:users|email',
            'role'       => 'required',
            'password'   => 'required|confirmed|min:5',
        ];
    }
}
