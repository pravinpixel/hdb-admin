<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffUpdateRequest extends FormRequest
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
        $rules = [
            'member_id'  => ['required', Rule::unique('users')->ignore($this->user, 'id')],
            'email'      => ['required', Rule::unique('users')->ignore($this->user, 'id')],
            'first_name' => 'required|regex:/(^[A-Za-z0-9_-_ ]+$)+/',
            'last_name'  => 'required|regex:/(^[A-Za-z0-9_-_ ]+$)+/',
            'role'       => 'required',
            'designation'       => 'required',
            'group'       => 'required',
        ];
		return $rules;
    }
}
