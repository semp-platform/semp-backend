<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route modules
|
*/

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    require __DIR__.'/locations.php';

    require __DIR__.'/elections.php';

    require __DIR__.'/nominations.php';

    require __DIR__.'/party.php';

    require __DIR__.'/admin.php';

    require __DIR__.'/finance.php';

});
