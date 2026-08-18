<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Elections
            'elections.view',
            'elections.create',
            'elections.update',
            'elections.activate',

            // Nomination workflow
            'nominations.view',
            'nominations.review',
            'nominations.approve',

            // Political party nomination activities
            'party-nominations.view',
            'party-nominations.create',
            'party-nominations.update',
            'party-nominations.submit',
            'party-nominations.withdraw',
            'party-nominations.replace',

            // Candidate withdrawals
            'party-withdrawals.view',
            'party-withdrawals.create',
            'party-withdrawals.submit',
            'party-payments.view',

            'withdrawals.view',
            'withdrawals.review',
            'withdrawals.approve',
            'withdrawals.reject',

            // Candidate information
            'candidates.view',

            // Political parties
            'parties.view',
            'parties.manage',

            // Document Management
            'document-types.view',
            'document-types.create',
            'document-types.update',
            'document-types.activate',
            'document-types.deactivate',

            // Payments
            'payments.view',
            'payments.verify',

            // Reports and audit
'reports.view',
'audit.view',

// Election Results
'results.manage',

            // Department-specific nomination permissions
            'legal.view',
            'legal.review',

            // System administration
            'reference-data.manage',
            'users.manage',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::updateOrCreate(
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'dashboard',
            ]
        );

        $superAdmin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );

        /*
        |--------------------------------------------------------------------------
        | Election Administrator
        |--------------------------------------------------------------------------
        */

        $electionAdministrator = Role::updateOrCreate(
            [
                'name' => 'Election Administrator',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'dashboard',
            ]
        );

        $electionAdministrator->syncPermissions([
            'elections.view',
            'elections.create',
            'elections.update',
            'elections.activate',

            'nominations.view',
            'candidates.view',
            'parties.view',

            'reports.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Nomination Officer
        |--------------------------------------------------------------------------
        */

        $nominationOfficer = Role::updateOrCreate(
            [
                'name' => 'Nomination Officer',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'dashboard',
            ]
        );

        $nominationOfficer->syncPermissions([
            'elections.view',

            'nominations.view',
            'nominations.review',

            'candidates.view',
            'parties.view',

            'payments.view',
            'reports.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Finance Officer
        |--------------------------------------------------------------------------
        */

        $financeOfficer = Role::updateOrCreate(
            [
                'name' => 'Finance Officer',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'finance.dashboard',
            ]
        );

        $financeOfficer->syncPermissions([
            'elections.view',
            'nominations.view',
            'parties.view',

            'payments.view',
            'payments.verify',

            'reports.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ICT Officer
        |--------------------------------------------------------------------------
        */

        $ictOfficer = Role::updateOrCreate(
            [
                'name' => 'ICT Officer',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'staff.ict.nomination-batches.index',
            ]
        );

        $ictOfficer->syncPermissions([
    'nominations.view',
    'nominations.review',

    'candidates.view',
    'parties.view',

    'results.manage',
]);

        /*
        |--------------------------------------------------------------------------
        | Election and Party Monitoring Officer
        |--------------------------------------------------------------------------
        */

        $epmOfficer = Role::updateOrCreate(
            [
                'name' => 'EPM Officer',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'staff.epm.nominations.index',
            ]
        );

        $epmOfficer->syncPermissions([
            'nominations.view',
            'nominations.review',

            'candidates.view',
            'parties.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Legal Officer
        |--------------------------------------------------------------------------
        */

        $legalOfficer = Role::updateOrCreate(
            [
                'name' => 'Legal Officer',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'staff.legal.nominations.index',
            ]
        );

        $legalOfficer->syncPermissions([
            'legal.view',
            'legal.review',

            'nominations.view',
            'nominations.review',

            'candidates.view',
            'parties.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Commissioner
        |--------------------------------------------------------------------------
        |
        | This role represents the Commissioner / approving authority.
        |
        | IMPORTANT:
        | The Commissioner does NOT have nominations.reject.
        |
        | The Commissioner can:
        | - approve
        | - return based on screening recommendations
        |
        */

        $commissioner = Role::updateOrCreate(
            [
                'name' => 'Commissioner',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'staff.commissioner.nominations.index',
            ]
        );

        $commissioner->syncPermissions([
            'elections.view',

            'nominations.view',
            'nominations.review',
            'nominations.approve',

            'withdrawals.view',
            'withdrawals.review',
            'withdrawals.approve',
            'withdrawals.reject',

            'candidates.view',
            'parties.view',

            'payments.view',
            'reports.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Political Party Officer
        |--------------------------------------------------------------------------
        */

        $partyOfficer = Role::updateOrCreate(
            [
                'name' => 'Political Party Officer',
                'guard_name' => 'web',
            ],
            [
                'dashboard_route' => 'party.dashboard',
            ]
        );

        $partyOfficer->syncPermissions([
            'elections.view',

            'party-nominations.view',
            'party-nominations.create',
            'party-nominations.update',
            'party-nominations.submit',
            'party-nominations.withdraw',
            'party-nominations.replace',

            'party-withdrawals.view',
            'party-withdrawals.create',
            'party-withdrawals.submit',

            'party-payments.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Clear permission cache
        |--------------------------------------------------------------------------
        */

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
