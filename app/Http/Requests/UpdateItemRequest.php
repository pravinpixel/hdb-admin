<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
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
            'item_ref'         => ['required', Rule::unique('items')->ignore($this->item, 'id')],
            'title'        => ['required',Rule::unique('items')->ignore($this->item, 'id')->whereNull('deleted_at')],
            'author'         => 'required',
            'location'            => 'required',
            'isbn'        => 'required',
            'subject'        => 'required',
            'location'       => 'required',
            'language_id'=>'required',
            'due_period'=>'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'item_ref.required' => 'The RFID field is required.',
            'item_ref.unique' => 'The RFID has already been taken.',
        ];
    }
}
