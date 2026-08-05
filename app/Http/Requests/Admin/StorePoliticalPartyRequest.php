<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePoliticalPartyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                'unique:political_parties,name',
            ],

            'acronym' => [
                'required',
                'string',
                'max:20',
                'unique:political_parties,acronym',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}
