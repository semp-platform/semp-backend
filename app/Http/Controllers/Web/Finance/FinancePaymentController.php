<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\Nomination\NominationBatch;
use App\Models\Payment\BatchPayment;
use App\Services\Finance\ReceiptService;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class FinancePaymentController extends Controller
{
    protected ReceiptService $receiptService;

    public function __construct(
        ReceiptService $receiptService
    ) {
        $this->receiptService = $receiptService;
    }

    /**
     * Display all batch payments.
     */
   public function index(): View
{
    $payments = BatchPayment::query()
        ->with([
            'politicalParty',
            'batch.election',
            'confirmedBy',
        ])
        ->whereIn('status', [
            BatchPayment::STATUS_PAID,
            BatchPayment::STATUS_CONFIRMED,
        ])
        ->latest()
        ->get();

    return view(
        'finance.payments.index',
        compact('payments')
    );
}
    /**
     * Confirm a payment.
     */
    public function confirm(
    BatchPayment $batchPayment
)
{
    // Prevent confirming twice.
    if ($batchPayment->status === BatchPayment::STATUS_CONFIRMED) {

        return back()->with(
            'success',
            'Payment has already been confirmed.'
        );

    }

    if ($batchPayment->status !== BatchPayment::STATUS_PAID) {

        return back()->withErrors([
            'payment' => 'Only paid payments can be confirmed.',
        ]);

    }

    $batchPayment->update([

        'status' => BatchPayment::STATUS_CONFIRMED,

        'confirmed_by' => auth()->id(),

        'confirmed_at' => now(),

        'receipt_number' => $batchPayment->receipt_number
            ?: sprintf(
                'RCT-%s-%06d',
                now()->format('Y'),
                $batchPayment->id
            ),

        'receipt_generated_at' => now(),

    ]);

    $batchPayment->batch->update([

        'payment_status' => NominationBatch::PAYMENT_PAID,

        'status' => NominationBatch::STATUS_PAID,

    ]);

    return back()->with(
        'success',
        'Payment confirmed successfully.'
    );
}
/**
 * Display payment receipt.
 */
public function receipt(
    BatchPayment $batchPayment
): View {

   abort_unless(
    in_array(
        $batchPayment->status,
        [
            BatchPayment::STATUS_PAID,
            BatchPayment::STATUS_CONFIRMED,
        ]
    ),
    404
);

    $batchPayment->load([

        'politicalParty',

        'batch.election',

        'batch.nominations.candidate',

        'batch.nominations.position',

        'confirmedBy',

    ]);

    return view(
        'finance.payments.receipt',
        [
            'payment' => $batchPayment,
        ]
    );
}

/**
 * Download payment receipt.
 */
public function downloadReceipt(
    BatchPayment $batchPayment
)
{
    abort_unless(
        in_array(
            $batchPayment->status,
            [
                BatchPayment::STATUS_PAID,
                BatchPayment::STATUS_CONFIRMED,
            ]
        ),
        404
    );

    $batchPayment->load([

        'politicalParty',

        'batch.election',

        'batch.nominations.candidate',

        'batch.nominations.position',

        'confirmedBy',

    ]);

    $pdf = Pdf::loadView(

        'finance.payments.pdf',

        [

            'payment' => $batchPayment,

        ]

    );

    return $pdf->download(

        $batchPayment->receipt_number . '.pdf'

    );
}
}
