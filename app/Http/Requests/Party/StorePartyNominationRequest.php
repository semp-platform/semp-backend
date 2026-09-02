<?php

namespace App\Http\Requests\Party;

use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\Reference\Lcda;
use App\Models\Reference\LcdaWard;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
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

            'lcda_ward_id' => [
                'nullable',
                'integer',
                Rule::exists('lcda_wards', 'id')
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

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $election = Election::query()
                ->with('electionType')
                ->where('is_active', true)
                ->find($this->election_id);

            $position = Position::query()
                ->where('is_active', true)
                ->find($this->position_id);

            if (! $election || ! $position) {
                return;
            }

            $electionType = $election->electionType?->name;
            $positionCode = $position->code;

            /*
            |--------------------------------------------------------------------------
            | LCDA Bye Election
            |
            | The administrator already configured the LCDA and LCDA Ward.
            | Party users do not select the electoral location again.
            |--------------------------------------------------------------------------
            */

            if ($electionType === 'LCDA Bye Election') {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LGA/LCDA Election
            |
            | The election itself is statewide.
            |
            | LGA contests:
            |   CHAIR  -> LGA
            |   VICE   -> LGA
            |   COUNC  -> LGA + Ward
            |
            | LCDA contests:
            |   LCDA_CHAIR -> LCDA
            |   LCDA_VICE  -> LCDA
            |   LCDA_COUNC -> LCDA + LCDA Ward
            |--------------------------------------------------------------------------
            */

            if ($electionType === 'LGA/LCDA Election') {

                switch ($positionCode) {

                    case 'CHAIR':
                    case 'VICE':

                        $this->validateLga(
                            $validator,
                            $election,
                            requireWard: false
                        );

                        break;

                    case 'COUNC':

                        $this->validateLga(
                            $validator,
                            $election,
                            requireWard: true
                        );

                        break;

                    case 'LCDA_CHAIR':
                    case 'LCDA_VICE':

                        $this->validateLcda(
                            $validator,
                            $election
                        );

                        break;

                    case 'LCDA_COUNC':

                        $this->validateLcda(
                            $validator,
                            $election,
                            requireWard: true
                        );

                        break;

                    default:

                        $validator->errors()->add(
                            'position_id',
                            'Unsupported position for this election.'
                        );

                        break;
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LCDA Election
            |
            | Existing LCDA Election positions remain:
            |   CHAIR -> LCDA
            |   VICE  -> LCDA
            |   COUNC -> LCDA + LCDA Ward
            |--------------------------------------------------------------------------
            */

            if ($electionType === 'LCDA Election') {

                switch ($positionCode) {

                    case 'CHAIR':
                    case 'VICE':

                        $this->validateLcda(
                            $validator,
                            $election
                        );

                        break;

                    case 'COUNC':

                        $this->validateLcda(
                            $validator,
                            $election,
                            requireWard: true
                        );

                        break;

                    default:

                        $validator->errors()->add(
                            'position_id',
                            'Unsupported position for this election.'
                        );

                        break;
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Normal LGA elections
            |
            | CHAIR  -> LGA
            | VICE   -> LGA
            | COUNC  -> LGA + Ward
            |--------------------------------------------------------------------------
            */

            if (in_array($electionType, [
                'Local Government Election',
                'Bye Election',
                'Re-run Election',
                'Supplementary Election',
            ], true)) {

                switch ($positionCode) {

                    case 'CHAIR':
                    case 'VICE':

                        $this->validateLga(
                            $validator,
                            $election,
                            requireWard: false
                        );

                        break;

                    case 'COUNC':

                        $this->validateLga(
                            $validator,
                            $election,
                            requireWard: true
                        );

                        break;

                    default:

                        $validator->errors()->add(
                            'position_id',
                            'Unsupported position for this election.'
                        );

                        break;
                }
            }
        });
    }

    private function validateLga(
        $validator,
        Election $election,
        bool $requireWard = false
    ): void {
        if (! $this->filled('lga_id')) {
            $validator->errors()->add(
                'lga_id',
                'An LGA is required.'
            );

            return;
        }

        $lga = Lga::query()
            ->where('id', $this->lga_id)
            ->where('state_id', $election->state_id)
            ->where('is_active', true)
            ->first();

        if (! $lga) {
            $validator->errors()->add(
                'lga_id',
                'The selected LGA does not belong to the election state.'
            );

            return;
        }

        if (! $requireWard) {
            return;
        }

        if (! $this->filled('ward_id')) {
            $validator->errors()->add(
                'ward_id',
                'A Ward is required.'
            );

            return;
        }

        $ward = Ward::query()
            ->where('id', $this->ward_id)
            ->where('lga_id', $lga->id)
            ->where('is_active', true)
            ->first();

        if (! $ward) {
            $validator->errors()->add(
                'ward_id',
                'The selected Ward does not belong to the selected LGA.'
            );
        }
    }

    private function validateLcda(
        $validator,
        Election $election,
        bool $requireWard = false
    ): void {
        if (! $this->filled('lga_id')) {
            $validator->errors()->add(
                'lga_id',
                'An LGA is required.'
            );

            return;
        }

        $lga = Lga::query()
            ->where('id', $this->lga_id)
            ->where('state_id', $election->state_id)
            ->where('is_active', true)
            ->first();

        if (! $lga) {
            $validator->errors()->add(
                'lga_id',
                'The selected LGA does not belong to the election state.'
            );

            return;
        }

        if (! $this->filled('lcda_id')) {
            $validator->errors()->add(
                'lcda_id',
                'An LCDA is required.'
            );

            return;
        }

        $lcda = Lcda::query()
            ->where('id', $this->lcda_id)
            ->where('lga_id', $lga->id)
            ->where('is_active', true)
            ->first();

        if (! $lcda) {
            $validator->errors()->add(
                'lcda_id',
                'The selected LCDA does not belong to the selected LGA.'
            );

            return;
        }

        if (! $requireWard) {
            return;
        }

        if (! $this->filled('lcda_ward_id')) {
            $validator->errors()->add(
                'lcda_ward_id',
                'An LCDA Ward is required.'
            );

            return;
        }

        $lcdaWard = LcdaWard::query()
            ->where('id', $this->lcda_ward_id)
            ->where('lcda_id', $lcda->id)
            ->where('is_active', true)
            ->first();

        if (! $lcdaWard) {
            $validator->errors()->add(
                'lcda_ward_id',
                'The selected LCDA Ward does not belong to the selected LCDA.'
            );
        }
    }
}
