<?php

namespace App\Http\Requests\Election;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'election_type_id' => [
                'required',
                'integer',
                Rule::exists('election_types', 'id')
                    ->where('is_active', true),
            ],

            'state_id' => [
                'required',
                'integer',
                Rule::exists('states', 'id')
                    ->where('is_active', true),
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'election_date' => [
                'required',
                'date',
            ],

            'positions' => [
                'required',
                'array',
                'min:1',
            ],

            'positions.*.position_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('positions', 'id')
                    ->where('is_active', true),
            ],

            'positions.*.nomination_fee' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
}
