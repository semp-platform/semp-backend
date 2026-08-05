<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->onlyInput('email');
    }

    $request->session()->regenerate();

    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | Finance Officer
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('Finance Officer')) {

        return redirect()->route(
    'finance.dashboard'
);

    }

    /*
    |--------------------------------------------------------------------------
    | Political Party Officer
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('Political Party Officer')) {

        $hasActiveParty = $user->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->exists();

        if (! $hasActiveParty) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Your account is not associated with an active political party.',
                ])
                ->onlyInput('email');

        }
return redirect()->route(
    'party.dashboard'
);

    }

    /*
    |--------------------------------------------------------------------------
    | Default (Super Admin & Other Staff)
    |--------------------------------------------------------------------------
    */

    return redirect()->route(
    'dashboard'
);
}

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
