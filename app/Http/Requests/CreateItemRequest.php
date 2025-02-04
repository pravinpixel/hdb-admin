<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemRequest extends FormRequest
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
            'item_ref'         => 'required|unique:items',
            'title'        => ['required',Rule::unique('items')->whereNull('deleted_at')],
            'author'         => 'required',
            'isbn' => ['required','min:2',Rule::unique('items')->whereNull('deleted_at')],
            'subject'        => 'required',
            'location'       => 'required',
            'collection'       => 'required',
            'imprint'       => 'required',
            'language_id'=>'required',
            'due_period'=>'nullable|integer',
            'call_number' => 'nullable',
            'barcode' => ['nullable',Rule::unique('items')->whereNull('deleted_at')]
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
