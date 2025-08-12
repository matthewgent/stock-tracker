<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemValueRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                'after_or_equal:1970-01-01',
                'before_or_equal:today'
            ],
            'currency' => [
                'required',
                'integer',
                'exists:currencies,id',
            ],
            'value' => [
                'required',
                'regex' => '',
                'gte:0',
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'value' => str_replace(',', '', $this->value),
        ]);
    }
}
