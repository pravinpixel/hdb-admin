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
            'rfid'      => 'required',
            'location'            => 'required',
            'isbn'        => 'required',
            'subject'        => 'required',
            'location'       => 'required',
            'language_id'=>'required',
        ];
    }
}
