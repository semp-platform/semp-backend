<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment\BatchPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;

class FinanceReceiptController extends Controller
{
    /**
     * Receipt register.
     */
    public function index(): View
    {
        $receipts = BatchPayment::query()
            ->whereNotNull('receipt_number')
            ->with([
                'politicalParty',
                'batch.election',
                'confirmedBy',
            ])
            ->latest('confirmed_at')
            ->get();

        return view(
            'finance.receipts.index',
            compact('receipts')
        );
    }

    /**
     * Display a receipt.
     */
    public function show(
        BatchPayment $batchPayment
    ): View {

        abort_unless(
            $batchPayment->receipt_number,
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
            'finance.receipts.show',
            [
                'payment' => $batchPayment,
            ]
        );
    }

    /**
     * Download receipt.
     */
    public function download(
        BatchPayment $batchPayment
    ) {

        abort_unless(
            $batchPayment->receipt_number,
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
            $batchPayment->receipt_number.'.pdf'
        );
    }
}
