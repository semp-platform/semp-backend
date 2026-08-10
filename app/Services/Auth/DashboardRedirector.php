<?php

namespace App\Services\Auth;

use App\Models\User;
use RuntimeException;

class DashboardRedirector
{
    /**
     * Determine the appropriate landing route
     * for the authenticated user.
     */
    public function routeFor(User $user): string
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Super Admin')) {
            return 'dashboard';
        }

        /*
        |--------------------------------------------------------------------------
        | Finance
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Finance Officer')) {
            return 'finance.dashboard';
        }

        /*
        |--------------------------------------------------------------------------
        | ICT
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('ICT Officer')) {
            return 'staff.ict.nomination-batches.index';
        }

        /*
        |--------------------------------------------------------------------------
        | EPM
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('EPM Officer')) {
            return 'staff.epm.nominations.index';
        }

        /*
        |--------------------------------------------------------------------------
        | Legal
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Legal Officer')) {
            return 'staff.legal.nominations.index';
        }

        /*
        |--------------------------------------------------------------------------
        | Commissioner / Approving Officer
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Approving Officer')) {
            return 'staff.commissioner.nominations.index';
        }

        /*
        |--------------------------------------------------------------------------
        | Political Party
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Political Party Officer')) {
            return $this->partyDashboard($user);
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        return 'dashboard';
    }

    /**
     * Determine the Political Party landing page.
     */
    protected function partyDashboard(User $user): string
    {
        $hasActiveParty = $user->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->exists();

        if (! $hasActiveParty) {
            throw new RuntimeException(
                'Your account is not associated with an active political party.'
            );
        }

        return 'party.dashboard';
    }
}
