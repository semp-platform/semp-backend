<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Party\NominationController;
use App\Http\Controllers\Web\Party\CandidateDocumentController;
use App\Http\Controllers\Web\Party\CandidateWithdrawalController;
use App\Http\Controllers\Web\Party\CandidateReplacementController;
use App\Http\Controllers\Web\Party\BatchPaymentController;
use App\Http\Controllers\Web\Party\NominationBatchController;
use App\Http\Controllers\Web\Party\ReturnedNominationController;
use App\Models\Nomination\Nomination;
use App\Http\Controllers\Web\Party\PartyPrimaryNoticeController;
use App\Http\Controllers\Web\Party\ElectionNoticeController;
use App\Http\Controllers\Web\Party\InboxController;






/*
|--------------------------------------------------------------------------
| Political Party Portal
|--------------------------------------------------------------------------
*/

Route::prefix('party')
    ->name('party.')
    ->middleware('role:Political Party Officer')
    ->group(function () {
        /*
|--------------------------------------------------------------------------
| Inbox
|--------------------------------------------------------------------------
*/

Route::get(
    '/inbox',
    [InboxController::class, 'index']
)
    ->name('inbox.index');


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

      Route::get('/dashboard', function () {

    $party = auth()->user()
        ->politicalParties()
        ->wherePivot('is_active', true)
        ->where('political_parties.is_active', true)
        ->firstOrFail();

    $nominations = Nomination::query()
        ->where('political_party_id', $party->id);

    $draftNominations = (clone $nominations)
        ->where('status', Nomination::STATUS_DRAFT)
        ->count();

    $submittedNominations = (clone $nominations)
        ->whereIn('status', [
            Nomination::STATUS_BATCHED,
            Nomination::STATUS_UNDER_REVIEW,
        ])
        ->count();

    $approvedNominations = (clone $nominations)
        ->where('status', Nomination::STATUS_APPROVED)
        ->count();

    $returnedNominations = (clone $nominations)
        ->where('workflow_status', Nomination::WORKFLOW_STATUS_RETURNED)
        ->count();

    $actionRequired = $draftNominations + $returnedNominations;

    return view('party.dashboard', [
        'party' => $party,
        'draftNominations' => $draftNominations,
        'submittedNominations' => $submittedNominations,
        'approvedNominations' => $approvedNominations,
        'actionRequired' => $actionRequired,
        'returnedNominations' => $returnedNominations,
    ]);

})->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Candidate NIN Verification
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/candidates/verify-nin',
            [NominationController::class, 'verifyNin']
        )
        ->middleware('permission:party-nominations.create')
        ->name('candidates.verify-nin');

        /*
        |--------------------------------------------------------------------------
        | Candidate Documents
        |--------------------------------------------------------------------------
        */
Route::get(
    '/candidate-documents',
    [CandidateDocumentController::class, 'list']
)->name('candidates.documents.list');


        Route::prefix('candidates')
            ->name('candidates.')
            ->group(function () {



                Route::get(
                    '/{candidate}/documents',
                    [CandidateDocumentController::class, 'index']
                )->name('documents.index');

                Route::post(
                    '/{candidate}/documents',
                    [CandidateDocumentController::class, 'store']
                )->name('documents.store');

                Route::post(
    '/{candidate}/documents/complete',
    [CandidateDocumentController::class, 'complete']
)->name('documents.complete');

                Route::delete(
                    '/documents/{document}',
                    [CandidateDocumentController::class, 'destroy']
                )->name('documents.destroy');

            });

        /*
        |--------------------------------------------------------------------------
        | Party Candidate Nominations
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nominations',
            [NominationController::class, 'index']
        )
        ->middleware('permission:party-nominations.view')
        ->name('nominations.index');

        Route::get(
            '/nominations/create',
            [NominationController::class, 'create']
        )
        ->middleware('permission:party-nominations.create')
        ->name('nominations.create');

        Route::post(
            '/nominations',
            [NominationController::class, 'store']
        )
        ->middleware('permission:party-nominations.create')
        ->name('nominations.store');

        Route::get(
            '/nominations/{nomination}',
            [NominationController::class, 'show']
        )
        ->middleware('permission:party-nominations.view')
        ->name('nominations.show');

        Route::delete(
    '/nominations/{nomination}',
    [NominationController::class, 'destroy']
)
->middleware('permission:party-nominations.update')
->name('nominations.destroy');

        Route::get(
            '/nominations/{nomination}/edit',
            [NominationController::class, 'edit']
        )
        ->middleware('permission:party-nominations.update')
        ->name('nominations.edit');

        Route::put(
            '/nominations/{nomination}',
            [NominationController::class, 'update']
        )
        ->middleware('permission:party-nominations.update')
        ->name('nominations.update');

        Route::post(
            '/nominations/{nomination}/submit',
            [NominationController::class, 'markReady']
        )
        ->middleware('permission:party-nominations.submit')
        ->name('nominations.submit');



        /*
        |--------------------------------------------------------------------------
        | Nomination Batches
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'nomination-batches',
            NominationBatchController::class
        )->only([
            'index',
            'create',
            'store',
            'show',
        ]);


        Route::post(
    '/nomination-batches/{nominationBatch}/submit',
    [NominationBatchController::class, 'submit']
)
->middleware('permission:party-nominations.submit')
->name('nomination-batches.submit');

/*
|--------------------------------------------------------------------------
| Party Primary Notices
|--------------------------------------------------------------------------
*/

Route::prefix('primary-notices')
    ->name('primary-notices.')
    ->group(function () {

        Route::get(
            '/',
            [PartyPrimaryNoticeController::class, 'index']
        )
            ->middleware('permission:party-nominations.view')
            ->name('index');

        Route::get(
            '/create',
            [PartyPrimaryNoticeController::class, 'create']
        )
            ->middleware('permission:party-nominations.create')
            ->name('create');

        Route::post(
            '/',
            [PartyPrimaryNoticeController::class, 'store']
        )
            ->middleware('permission:party-nominations.create')
            ->name('store');

        Route::get(
            '/{partyPrimaryNotice}',
            [PartyPrimaryNoticeController::class, 'show']
        )
            ->middleware('permission:party-nominations.view')
            ->name('show');
    });
        /*
        |--------------------------------------------------------------------------
        | Party Payments
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/payments',
            [BatchPaymentController::class, 'index']
        )
        ->middleware('permission:party-payments.view')
        ->name('payments.index');

        Route::post(
            '/payments/{batchPayment}/pay',
            [BatchPaymentController::class, 'pay']
        )
        ->middleware('permission:party-payments.view')
        ->name('payments.pay');

        Route::get(
            '/payments/callback',
            [BatchPaymentController::class, 'callback']
        )->name('payments.callback');

        Route::get(
            '/payments/{batchPayment}',
            [BatchPaymentController::class, 'show']
        )
        ->middleware('permission:party-payments.view')
        ->name('payments.show');

        Route::get(
    '/payments/{batchPayment}/receipt',
    [\App\Http\Controllers\Web\Party\BatchPaymentController::class, 'receipt']
)
->name('payments.receipt');

Route::get(
    '/payments/{batchPayment}/receipt/download',
    [\App\Http\Controllers\Web\Party\BatchPaymentController::class, 'downloadReceipt']
)
->name('payments.receipt.download');
        /*
        |--------------------------------------------------------------------------
        | Candidate Withdrawals
        |--------------------------------------------------------------------------
        */

        Route::prefix('withdrawals')
    ->name('withdrawals.')
    ->group(function () {

        Route::get(
            '/',
            [CandidateWithdrawalController::class, 'index']
        )
        ->middleware('permission:party-withdrawals.view')
        ->name('index');

        Route::get(
            '/create/{nomination}',
            [CandidateWithdrawalController::class, 'create']
        )
        ->middleware('permission:party-withdrawals.create')
        ->name('create');

        Route::post(
            '/',
            [CandidateWithdrawalController::class, 'store']
        )
        ->middleware('permission:party-withdrawals.create')
        ->name('store');

        Route::get(
            '/{withdrawal}',
            [CandidateWithdrawalController::class, 'show']
        )
        ->middleware('permission:party-withdrawals.view')
        ->name('show');

    });

    /*
|--------------------------------------------------------------------------
| Candidate Replacements
|--------------------------------------------------------------------------
*/

Route::prefix('replacements')
    ->name('replacements.')
    ->group(function () {

        Route::get(
            '/',
            [CandidateReplacementController::class, 'index']
        )
        ->middleware('permission:party-nominations.view')
        ->name('index');

        Route::get(
            '/create/{withdrawal}',
            [CandidateReplacementController::class, 'create']
        )
        ->middleware('permission:party-nominations.create')
        ->name('create');

        Route::post(
            '/{withdrawal}',
            [CandidateReplacementController::class, 'store']
        )
        ->middleware('permission:party-nominations.create')
        ->name('store');

    });

    Route::get(
    '/election-notices',
    [ElectionNoticeController::class, 'index']
)->name('election-notices.index');

Route::get(
    '/election-notices/{electionNotice}',
    [ElectionNoticeController::class, 'show']
)->name('election-notices.show');

            /*
|--------------------------------------------------------------------------
| Returned Nominations
|--------------------------------------------------------------------------
*/

Route::get(
    '/returned-nominations',
    [ReturnedNominationController::class, 'index']
)
->middleware('permission:party-nominations.view')
->name('returned-nominations.index');
    });

