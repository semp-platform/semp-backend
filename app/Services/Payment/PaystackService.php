<?php

namespace App\Services\Payment;

use App\Models\Payment\BatchPayment;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    /**
     * Initialise a Paystack transaction.
     */
    public function initialize(
        BatchPayment $payment
    ): Response {

        return Http::withToken(
            config('services.paystack.secret_key')
        )->post(
            config('services.paystack.payment_url').'/transaction/initialize',
            [

                'reference' => $payment->payment_reference,

                'email' => auth()->user()->email,

                'amount' => $payment->amount * 100,

                'callback_url' => route(
                    'party.payments.callback'
                ),

                'metadata' => [

                    'payment_id' => $payment->id,

                    'batch_id' => $payment->nomination_batch_id,

                ],

            ]
        );

    }

    /**
     * Verify a Paystack transaction.
     */
    public function verify(
        string $reference
    ): Response {

        return Http::withToken(
            config('services.paystack.secret_key')
        )->get(
            config('services.paystack.payment_url')
            .'/transaction/verify/'
            .$reference
        );

    }
}
