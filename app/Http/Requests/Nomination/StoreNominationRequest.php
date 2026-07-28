<?php

namespace App\Http\Requests\Nomination;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNominationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'election_id' => [
                'required',
                'integer',
                Rule::exists('elections', 'id')
                    ->where('is_active', true),
            ],

            'political_party_id' => [
                'required',
                'integer',
                Rule::exists('political_parties', 'id')
                    ->where('is_active', true),
            ],

            'position_id' => [
                'required',
                'integer',
                Rule::exists('positions', 'id')
                    ->where('is_active', true),
            ],

            'nin' => [
                'required',
                'string',
                'regex:/^\d{11}$/',
            ],

            'lga_id' => [
                'nullable',
                'integer',
                Rule::exists('lgas', 'id')
                    ->where('is_active', true),
            ],

            'ward_id' => [
                'nullable',
                'integer',
                Rule::exists('wards', 'id')
                    ->where('is_active', true),
            ],
        ];
    }
}
