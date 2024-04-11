<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventorySourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:inventory_sources', 'max:100'],
            'name' => ['required', 'unique:inventory_sources', 'max:100'],
            'status' => ['required'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'Inventory Source code is required.',
            'code.required' => 'Inventory Source code should be max 100 character.',
            'name.required' => 'Inventory Source name is required.',
            'name.max' => 'Inventory Source name should be max 100 character',
            'status:required' => 'Inventory Source is required.'
        ];
    }
}
