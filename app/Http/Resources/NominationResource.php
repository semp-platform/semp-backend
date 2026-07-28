<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NominationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'election' => [
                'id' => $this->election->id,
                'name' => $this->election->name,
            ],

            'political_party' => [
                'id' => $this->politicalParty->id,
                'name' => $this->politicalParty->name,
                'acronym' => $this->politicalParty->acronym,
            ],

            'candidate' => [
                'id' => $this->candidate->id,
                'first_name' => $this->candidate->first_name,
                'middle_name' => $this->candidate->middle_name,
                'last_name' => $this->candidate->last_name,
                'gender' => $this->candidate->gender,
                'date_of_birth' => $this->candidate->date_of_birth?->format('Y-m-d'),
            ],

            'position' => [
                'id' => $this->position->id,
                'name' => $this->position->name,
                'code' => $this->position->code,
            ],

            'lga' => $this->lga ? [
                'id' => $this->lga->id,
                'name' => $this->lga->name,
            ] : null,

            'ward' => $this->ward ? [
                'id' => $this->ward->id,
                'name' => $this->ward->name,
            ] : null,

            'status' => $this->status,
        ];
    }
}
