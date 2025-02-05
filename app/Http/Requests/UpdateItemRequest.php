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
            'isbn'        => 'required|min:2',
            'subject'        => 'required',
            'location'       => 'required',
            'collection'       => 'required',
            'imprint'       => 'required',
            'language_id'=>'required',
            'due_period'=>'nullable|integer',
            'call_number' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'item_ref.required' => 'The RFID field is required.',
            'item_ref.unique' => 'The RFID has already been taken.',
            'isbn.min' => 'The ISBN must be at least 2 Characters '
        ];
    }
}
