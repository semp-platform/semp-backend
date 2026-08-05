<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePoliticalPartyRequest extends FormRequest
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
                Rule::unique('political_parties', 'name')
                    ->ignore($this->politicalParty),
            ],

            'acronym' => [
                'required',
                'string',
                'max:20',
                Rule::unique('political_parties', 'acronym')
                    ->ignore($this->politicalParty),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}
