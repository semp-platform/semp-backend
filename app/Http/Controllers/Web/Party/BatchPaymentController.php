<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Nomination\NominationBatch;
use App\Services\Payment\BatchPaymentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\Payment\PaystackService;
use App\Models\Payment\BatchPayment;
use Barryvdh\DomPDF\Facade\Pdf;

class BatchPaymentController extends Controller
{
    protected PaystackService $paystack;

public function __construct(
    PaystackService $paystack
) {

    $this->paystack = $paystack;

}

    protected function currentParty(Request $request)
    {
        return $request->user()
            ->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->firstOrFail();
    }

    /**
     * Display payment summary.
     */
    public function index(
    Request $request
): View {

    $party = $this->currentParty($request);

    $payments = \App\Models\Payment\BatchPayment::query()
        ->where('political_party_id', $party->id)
        ->with([
            'batch.election',
        ])
        ->latest()
        ->get();

    return view(
        'party.payments.index',
        [
            'party' => $party,
            'payments' => $payments,
        ]
    );

}


    public function show(
    Request $request,
    \App\Models\Payment\BatchPayment $batchPayment
): View {

    $party = $this->currentParty($request);

    abort_unless(
        $batchPayment->political_party_id === $party->id,
        403
    );

    $batchPayment->load([

        'batch.election',

        'batch.nominations.candidate',

        'batch.nominations.position',

    ]);

    return view(
        'party.payments.show',
        [
            'party' => $party,
            'payment' => $batchPayment,
        ]
    );
}
public function pay(
    Request $request,
    BatchPayment $batchPayment
) {

    abort_if(
        $batchPayment->status === BatchPayment::STATUS_PAID,
        403,
        'This payment has already been completed.'
    );

    $party = $this->currentParty($request);

    abort_unless(
        $batchPayment->political_party_id === $party->id,
        403
    );

    $batchPayment->update([
        'payment_reference' =>
            'PAY-'
            . now()->format('YmdHis')
            . '-'
            . strtoupper(\Illuminate\Support\Str::random(6)),
    ]);

    $response = $this->paystack->initialize($batchPayment);

    logger()->info('Paystack response', [
        'status' => $response->status(),
        'body'   => $response->json(),
    ]);

    if (! $response->successful()) {

        return back()->withErrors([
            'payment' => $response->json('message')
                ?? 'Unable to initialise payment.',
        ]);

    }

    return redirect()->away(
        $response->json('data.authorization_url')
    );

}

public function callback(Request $request)
{
    $reference = $request->reference;

    if (! $reference) {
        return redirect()
            ->route('party.payments.index')
            ->withErrors([
                'payment' => 'Invalid payment reference.'
            ]);
    }

    $response = $this->paystack->verify($reference);

    if (! $response->successful()) {
        return redirect()
            ->route('party.payments.index')
            ->withErrors([
                'payment' => 'Unable to verify payment.'
            ]);
    }

    $data = $response->json('data');

    if ($data['status'] !== 'success') {
        return redirect()
            ->route('party.payments.index')
            ->withErrors([
                'payment' => 'Payment was not successful.'
            ]);
    }

    $payment = BatchPayment::where(
        'payment_reference',
        $reference
    )->firstOrFail();

    $payment->update([

    'status' => BatchPayment::STATUS_PAID,

    'paid_at' => now(),

    'gateway_reference' => $data['reference'],

    'receipt_number' => sprintf(
        'RCT-%s-%06d',
        now()->format('Y'),
        $payment->id
    ),

    'receipt_generated_at' => now(),

]);

    $payment->batch->update([
        'payment_status' => NominationBatch::PAYMENT_PAID,
        'amount_paid' => $payment->amount,
        'paid_at' => now(),
    ]);

    return redirect()
        ->route('party.payments.show', $payment)
        ->with(
            'success',
            'Payment completed successfully.'
        );
}

public function receipt(
    BatchPayment $batchPayment,
    Request $request
): View {

    $party = $this->currentParty($request);

    abort_unless(
        $batchPayment->political_party_id === $party->id,
        403
    );

    $batchPayment->load([
        'politicalParty',
        'batch.election',
        'batch.nominations.candidate',
        'batch.nominations.position',
    ]);

    return view('party.payments.receipt', [
        'party'   => $party,
        'payment' => $batchPayment,
    ]);
}
public function downloadReceipt(
    Request $request,
    BatchPayment $batchPayment
) {

    $party = $this->currentParty($request);

    abort_unless(
        $batchPayment->political_party_id === $party->id,
        403
    );

    abort_unless(
        $batchPayment->receipt_number,
        404
    );

    $batchPayment->load([

        'politicalParty',

        'batch.election',

        'batch.nominations.candidate',

        'batch.nominations.position',

    ]);

    $pdf = Pdf::loadView(
    'party.payments.pdf',
    [
        'party'   => $party,
        'payment' => $batchPayment,
    ]
);

    return $pdf->download(
        $batchPayment->receipt_number . '.pdf'
    );

}

}
