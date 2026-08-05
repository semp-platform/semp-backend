<?php

namespace App\Services\Payment;

use App\Models\Nomination\NominationBatch;
use App\Models\Payment\BatchPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BatchPaymentService
{
    /**
     * Create a payment record for a nomination batch.
     */
    public function create(
        NominationBatch $batch
    ): BatchPayment {

        return DB::transaction(function () use ($batch) {

            if ($batch->payments()->exists()) {

                return $batch->payments()->latest()->first();

            }

            return BatchPayment::create([

                'nomination_batch_id' => $batch->id,

                'political_party_id' => $batch->political_party_id,

                'payment_reference' => $this->generateReference(),

                'amount' => $batch->total_nomination_fee,

                'status' => BatchPayment::STATUS_PENDING,

            ]);

        });

    }

    /**
     * Generate an internal payment reference.
     */
    protected function generateReference(): string
    {
        return 'PAY-'
            . now()->format('YmdHis')
            . '-'
            . strtoupper(Str::random(6));
    }
}
