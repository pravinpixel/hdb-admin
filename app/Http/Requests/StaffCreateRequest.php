<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffCreateRequest extends FormRequest
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
            'member_id'  => 'required|regex:/^[0-9]{5}[A-Za-z]$/|unique:users',
            'first_name' => 'required|regex:/(^[A-Za-z ]+$)+/',
            // 'last_name'  => 'required|regex:/(^[A-Za-z0-9_-_ ]+$)+/',
            'email'      => 'required|unique:users|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'role'       => 'required',
            'designation'       => 'required',
            'group'       => 'required',
            'location'       => 'required',
            'collection'       => 'required',
            'imprint'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Name No is required',
            'member_id.regex' => 'Staff No must be 1 alphabet followed by 5 numbers',
            'first_name.regex' => 'Name should only contain alphabets and spaces.',
            // 'member_id.unique' => 'Staff No already exists',
            // 'first_name.required' => 'First Name is required',
            // 'first_name.regex' => 'First Name must contain only letters, numbers, spaces, underscores, and hyphens',
            // 'email.required' => 'Email is required',
            // 'email.unique' => 'Email already exists',
            // 'email.email' => 'Invalid email format',
            // 'role.required' => 'Role is required',
            // 'designation.required' => 'Designation is required',
            // 'group.required' => 'Group is required',
        ];
    }
}
