<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Public\HomeController;
use App\Http\Controllers\Web\Public\ElectionController;
use App\Http\Controllers\Web\PublicContentController;
use App\Http\Controllers\Web\Public\ResultController;

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Debug
|--------------------------------------------------------------------------
*/

Route::get('/debug-auth', function () {
    return response()->json([
        'auth_check' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'host' => request()->getHost(),
    ]);
});

/*
|--------------------------------------------------------------------------
| PUBLIC OGSIEC WEBSITE
|--------------------------------------------------------------------------
|
| These routes are available without authentication.
|
*/

/*
|--------------------------------------------------------------------------
| Public Homepage
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');

/*
|--------------------------------------------------------------------------
| Public Elections
|--------------------------------------------------------------------------
*/

Route::prefix('public')
    ->name('public.')
    ->group(function () {

Route::get(
    '/about',
    function () {
        return view('public.about');
    }
)->name('about');
        Route::get(
            '/elections',
            [ElectionController::class, 'index']
        )->name('elections.index');

        Route::get(
            '/elections/{election}',
            [ElectionController::class, 'show']
        )->name('elections.show');

            Route::get(
        '/candidates',
        [\App\Http\Controllers\Web\Public\CandidateController::class, 'index']
        )->name('candidates.index');

        Route::get(
        '/candidates/{nomination}',
        [\App\Http\Controllers\Web\Public\CandidateController::class, 'show']
        )->name('candidates.show');

            Route::get(
        '/content',
        [\App\Http\Controllers\Web\Public\PublicContentController::class, 'index']
    )->name('content.index');

    Route::get(
        '/content/{slug}',
        [\App\Http\Controllers\Web\Public\PublicContentController::class, 'show']
    )->name('content.show');
Route::get(
    '/results',
    [ResultController::class, 'index']
)->name('results.index');

Route::get(
    '/results/{resultImport}/ward/{ward}',
    [ResultController::class, 'ward']
)->name('results.ward');

Route::get(
    '/results/{resultImport}',
    [ResultController::class, 'show']
)->name('results.show');
    });

/*
|--------------------------------------------------------------------------
| AUTHENTICATED SEMP APPLICATION
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Reference / Location Routes
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/locations.php';

    /*
    |--------------------------------------------------------------------------
    | Election Administration
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/elections.php';

    /*
    |--------------------------------------------------------------------------
    | Nominations
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/nominations.php';

    /*
    |--------------------------------------------------------------------------
    | Political Party Portal
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/party.php';

    /*
    |--------------------------------------------------------------------------
    | Administration
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/admin.php';

    /*
    |--------------------------------------------------------------------------
    | Finance
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/finance.php';

    /*
    |--------------------------------------------------------------------------
    | ICT
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/ict.php';

    /*
    |--------------------------------------------------------------------------
    | EPM
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/epm.php';

    /*
    |--------------------------------------------------------------------------
    | Legal
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/legal.php';

    /*
    |--------------------------------------------------------------------------
    | Commissioner
    |--------------------------------------------------------------------------
    */

    require __DIR__.'/commissioner.php';

    /*
    |--------------------------------------------------------------------------
    | Public Content Management
    |--------------------------------------------------------------------------
    |
    | This is the internal CMS used by authorised OGSIEC staff to manage
    | news, notices, announcements and press releases appearing on the
    | public website.
    |
    */

    Route::prefix('public-content')
        ->name('web.public-content.')
        ->controller(PublicContentController::class)
        ->group(function () {

            Route::get(
                '/',
                'index'
            )->name('index');

            Route::get(
                '/create',
                'create'
            )->name('create');

            Route::post(
                '/',
                'store'
            )->name('store');

            Route::get(
                '/{publicContent}',
                'show'
            )->name('show');

            Route::get(
                '/{publicContent}/edit',
                'edit'
            )->name('edit');

            Route::put(
                '/{publicContent}',
                'update'
            )->name('update');

            Route::delete(
                '/{publicContent}',
                'destroy'
            )->name('destroy');

            Route::patch(
                '/{publicContent}/publish',
                'publish'
            )->name('publish');

            Route::patch(
                '/{publicContent}/unpublish',
                'unpublish'
            )->name('unpublish');

            Route::patch(
                '/{publicContent}/featured',
                'toggleFeatured'
            )->name('featured');

            Route::patch(
                '/{publicContent}/ticker',
                'toggleTicker'
            )->name('ticker');
        });

        Route::middleware('auth')->get('/notifications/{notification}/read', function ($notification) {
    $user = request()->user();

    $record = $user->notifications()
        ->whereKey($notification)
        ->firstOrFail();

    $record->markAsRead();

    return redirect($record->data['url'] ?? '/');
})->name('notifications.read');



});
