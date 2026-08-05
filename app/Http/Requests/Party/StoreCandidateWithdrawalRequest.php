<?php

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reference\CandidateChangeReason;

class StoreCandidateWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('party-withdrawals.create') ?? false;
    }

    public function rules(): array
{
    return [

        'nomination_id' => [
            'required',
            'integer',
            Rule::exists('nominations', 'id'),
        ],

        'candidate_change_reason_id' => [
            'required',
            'integer',
            Rule::exists('candidate_change_reasons', 'id')
                ->where('change_type', 'withdrawal')
                ->where('is_active', true),
        ],

        'remarks' => [
            'required',
            'string',
            'min:10',
            'max:5000',
        ],

        'supporting_evidence' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx,jpg,jpeg,png',
            'max:10240', // 10 MB
        ],

    ];
}
public function withValidator($validator): void
{
    $validator->after(function ($validator) {

        $reason = CandidateChangeReason::find(
            $this->candidate_change_reason_id
        );

        if (
            $reason &&
            $reason->requires_document &&
            ! $this->hasFile('supporting_evidence')
        ) {
            $validator->errors()->add(
                'supporting_evidence',
                'Supporting evidence is required for the selected withdrawal reason.'
            );
        }

    });
}
}
