<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

            'election_type' => [
                'id' => $this->electionType->id,
                'name' => $this->electionType->name,
            ],

            'state' => [
                'id' => $this->state->id,
                'name' => $this->state->name,
            ],

            'election_date' => $this->election_date?->format('Y-m-d'),
            'status' => $this->status,

            'positions' => $this->electionPositions->map(function ($electionPosition) {
                return [
                    'id' => $electionPosition->position->id,
                    'name' => $electionPosition->position->name,
                    'code' => $electionPosition->position->code,
                    'nomination_fee' => $electionPosition->nomination_fee,
                ];
            }),
        ];
    }
}
