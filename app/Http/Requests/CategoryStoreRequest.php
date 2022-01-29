<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class CategoryStoreRequest extends FormRequest
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
            // 'name' => ['required', 'unique:categories', 'max:100'],
            'name' => [
                'required',
                'unique:category_translations',
                Rule::unique('category_translations')->where(function ($query) {
                    return $query->where('locale', Lang::locale());
                }),
                'max:100'
            ],
            'slug' => [
                'required',
                'unique:category_translations',
                Rule::unique('category_translations')->where(function ($query) {
                    return $query->where('locale', Lang::locale());
                }),
                'max:100'
            ],
            'description' => ['required', 'max:500'],
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
            'name.required' => 'Category name is required.',
            'name.max' => 'Category name should be max 100 character',
            'description:required' => 'Category description is required.'
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'trim|lowercase|escape'
        ];
    }
}
