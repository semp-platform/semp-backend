<?php

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartyNominationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('party-nominations.create') ?? false;
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

            /*
            |--------------------------------------------------------------------------
            | Candidate supplementary information
            |--------------------------------------------------------------------------
            */

            'qualification' => [
                'nullable',
                'string',
                'max:255',
            ],

            'qualification_details' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'has_disability' => [
                'required',
                'boolean',
            ],

            'disability_description' => [
                'nullable',
                'string',
                'max:5000',
                'required_if:has_disability,1',
            ],

            /*
            |--------------------------------------------------------------------------
            | Electoral location
            |--------------------------------------------------------------------------
            */

            'lga_id' => [
                'nullable',
                'integer',
                Rule::exists('lgas', 'id')
                    ->where('is_active', true),
            ],

            'lcda_id' => [
                'nullable',
                'integer',
                Rule::exists('lcdas', 'id')
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
