<?php

namespace App\Services\Finance;

use App\Models\Payment\BatchPayment;

class ReceiptService
{
    /**
     * Generate a receipt number.
     */
    public function generateReceiptNumber(
        BatchPayment $payment
    ): string {

        return sprintf(
            'RCT-%s-%06d',
            now()->format('Y'),
            $payment->id
        );

    }
}
