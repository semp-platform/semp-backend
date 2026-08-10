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

        if (! Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
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
        | Political Party Officer
        |--------------------------------------------------------------------------
        |
        | Political Party Officers must be attached to an active political
        | party before they can access the system.
        |
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
        }

        /*
        |--------------------------------------------------------------------------
        | Dynamic Role Dashboard
        |--------------------------------------------------------------------------
        |
        | The user's role determines where they go after login.
        |
        | Example:
        |
        | Legal Officer
        |     ↓
        | staff.legal.nominations.index
        |
        | Approving Officer
        |     ↓
        | staff.commissioner.nominations.index
        |
        | No department-specific code is required here.
        |
        */

        $role = $user->roles()
            ->whereNotNull('dashboard_route')
            ->first();

        if ($role?->dashboard_route) {
            return redirect()->route($role->dashboard_route);
        }

        /*
        |--------------------------------------------------------------------------
        | No Dashboard Configured
        |--------------------------------------------------------------------------
        |
        | If an administrator creates a role but does not configure a
        | dashboard route, send the user to the general dashboard instead
        | of producing an error.
        |
        */

        return redirect()
            ->route('dashboard')
            ->with(
                'warning',
                'Your account is active, but no department dashboard has been configured for your role.'
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
