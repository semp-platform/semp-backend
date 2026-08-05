<?php

namespace App\Services\Nomination;

use App\Models\Election\Election;
use App\Models\Nomination\Nomination;
use App\Models\Nomination\NominationBatch;
use App\Models\Party\PoliticalParty;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Payment\BatchPayment;

class NominationBatchService
{
    /**
     * Elections that have nominations belonging to this party.
     */
    public function availableElections(
        PoliticalParty $party
    ): Collection {

        return Election::query()
            ->whereHas('nominations', function ($query) use ($party) {

                $query->where(
                    'political_party_id',
                    $party->id
                );

            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Ready nominations available for batching.
     */
    public function readyNominations(
        PoliticalParty $party,
        Election $election
    ): Collection {

        return Nomination::query()
            ->where('political_party_id', $party->id)
            ->where('election_id', $election->id)
            ->where('status', Nomination::STATUS_READY)
            ->whereNull('nomination_batch_id')
            ->with([
                'candidate',
                'position',
            ])
            ->orderBy('position_id')
            ->get();
    }

    /**
     * Calculate total nomination fees.
     */
    public function calculateFee(
        Collection $nominations
    ): float {

        return $nominations->sum(function ($nomination) {

            return $nomination->nomination_fee;

        });

    }

    /**
     * Generate batch number.
     */
    public function generateBatchNumber(): string
    {
        return 'NB-'
            . now()->format('Y')
            . '-'
            . strtoupper(Str::random(6));
    }

    /**
     * Create a draft batch.
     *
     * (Implementation comes next.)
     */
   public function createBatch(
    PoliticalParty $party,
    Election $election
): NominationBatch {

    return DB::transaction(function () use ($party, $election) {

        $nominations = $this->readyNominations(
            $party,
            $election
        );

        if ($nominations->isEmpty()) {

            throw new \RuntimeException(
                'There are no ready nominations to batch.'
            );

        }

        $batch = NominationBatch::create([

            'batch_number' => $this->generateBatchNumber(),

            'election_id' => $election->id,

            'political_party_id' => $party->id,

            'candidate_count' => $nominations->count(),

            'total_nomination_fee' => $this->calculateFee($nominations),

            'created_by' => auth()->id(),

        ]);

        Nomination::query()
            ->whereIn(
                'id',
                $nominations->pluck('id')
            )
            ->update([
                'nomination_batch_id' => $batch->id,
            ]);

        BatchPayment::create([

            'nomination_batch_id' => $batch->id,

            'political_party_id' => $party->id,

            'payment_reference' => $this->generatePaymentReference(),

            'amount' => $batch->total_nomination_fee,

            'status' => BatchPayment::STATUS_PENDING,

        ]);

        return $batch;

    });

}

protected function generatePaymentReference(): string
{
    return 'PAY-'
        . now()->format('YmdHis')
        . '-'
        . strtoupper(\Illuminate\Support\Str::random(6));
}
}
