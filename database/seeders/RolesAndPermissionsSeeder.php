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

            // OGSIEC nomination administration
            'nominations.view',
            'nominations.review',
            'nominations.approve',
            'nominations.reject',

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

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions(Permission::all());


        /*
        |--------------------------------------------------------------------------
        | Election Administrator
        |--------------------------------------------------------------------------
        */

        $electionAdministrator = Role::firstOrCreate([
            'name' => 'Election Administrator',
            'guard_name' => 'web',
        ]);

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

        $nominationOfficer = Role::firstOrCreate([
            'name' => 'Nomination Officer',
            'guard_name' => 'web',
        ]);

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

        $financeOfficer = Role::firstOrCreate([
            'name' => 'Finance Officer',
            'guard_name' => 'web',
        ]);

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
        | Approving Officer
        |--------------------------------------------------------------------------
        */

        $approvingOfficer = Role::firstOrCreate([
            'name' => 'Approving Officer',
            'guard_name' => 'web',
        ]);

        $approvingOfficer->syncPermissions([
            'elections.view',

            'nominations.view',
            'nominations.review',
            'nominations.approve',
            'nominations.reject',
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

        $partyOfficer = Role::firstOrCreate([
            'name' => 'Political Party Officer',
            'guard_name' => 'web',
        ]);

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

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }


    }

