<?php

namespace App\Http\Requests\Election;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Election\ElectionType;
use App\Models\Reference\LcdaWard;

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

            'lcda_id' => [
                'nullable',
                'integer',
                Rule::exists('lcdas', 'id')
                    ->where('is_active', true),
            ],

            'lcda_ward_id' => [
                'nullable',
                'integer',
                Rule::exists('lcda_wards', 'id')
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

            'nomination_open_date' => [
                'nullable',
                'date',
                'before_or_equal:nomination_close_date',
            ],

            'nomination_close_date' => [
                'nullable',
                'date',
                'after_or_equal:nomination_open_date',
                'before_or_equal:screening_date',
            ],

            'screening_date' => [
                'nullable',
                'date',
                'after_or_equal:nomination_close_date',
                'before_or_equal:appeal_deadline',
            ],

            'appeal_deadline' => [
                'nullable',
                'date',
                'after_or_equal:screening_date',
                'before_or_equal:result_declaration_date',
            ],

            'result_declaration_date' => [
                'nullable',
                'date',
                'after_or_equal:appeal_deadline',
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

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $electionType = ElectionType::query()
                ->select('id', 'name')
                ->find($this->election_type_id);

            if (! $electionType) {
                return;
            }

            switch ($electionType->name) {

                /*
                |--------------------------------------------------------------------------
                | Statewide elections
                |--------------------------------------------------------------------------
                */

                case 'Local Government Election':
                case 'LCDA Election':
                case 'LGA/LCDA Election':

                    // No LGA, Ward, LCDA or LCDA Ward required.
                    break;


                /*
                |--------------------------------------------------------------------------
                | LGA-level elections
                |--------------------------------------------------------------------------
                */

                case 'Bye Election':
                case 'Re-run Election':
                case 'Supplementary Election':

                    if (! $this->filled('lga_id')) {
                        $validator->errors()->add(
                            'lga_id',
                            'Please select the LGA.'
                        );
                    }

                    if (! $this->filled('ward_id')) {
                        $validator->errors()->add(
                            'ward_id',
                            'Please select the Ward.'
                        );
                    }

                    break;


                /*
                |--------------------------------------------------------------------------
                | LCDA Bye Election
                |--------------------------------------------------------------------------
                */

                case 'LCDA Bye Election':

    if (! $this->filled('lga_id')) {
        $validator->errors()->add(
            'lga_id',
            'Please select the LGA.'
        );
    }

    if (! $this->filled('lcda_id')) {
        $validator->errors()->add(
            'lcda_id',
            'Please select the LCDA.'
        );
    }

    if (! $this->filled('lcda_ward_id')) {
        $validator->errors()->add(
            'lcda_ward_id',
            'Please select the LCDA Ward.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Verify LCDA Ward → LCDA → LGA relationship
    |--------------------------------------------------------------------------
    */

    if (
        $this->filled('lga_id') &&
        $this->filled('lcda_id') &&
        $this->filled('lcda_ward_id')
    ) {

        $validLocation = LcdaWard::query()
            ->where('id', $this->lcda_ward_id)
            ->where('lcda_id', $this->lcda_id)
            ->whereHas('lcda', function ($query) {
                $query->where('lga_id', $this->lga_id);
            })
            ->exists();

        if (! $validLocation) {
            $validator->errors()->add(
                'lcda_ward_id',
                'The selected LCDA Ward does not belong to the selected LCDA and LGA.'
            );
        }
    }

    break;
            }
        });
    }
}
