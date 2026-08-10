<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/debug-auth', function () {
    return response()->json([
        'auth_check' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'host' => request()->getHost(),
    ]);
});

Route::middleware('auth')->group(function () {

    require __DIR__.'/locations.php';
    require __DIR__.'/elections.php';
    require __DIR__.'/nominations.php';
    require __DIR__.'/party.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/finance.php';
    require __DIR__.'/ict.php';
require __DIR__.'/epm.php';
require __DIR__.'/legal.php';
require __DIR__.'/commissioner.php';

});
