<?php

namespace Tests\Feature;

use App\Models\Election\Election;
use App\Models\Election\ElectionType;
use App\Models\Election\Position;
use App\Models\Party\PoliticalParty;
use App\Models\PartyPrimaryNotice;
use App\Models\PrimaryEvent;
use App\Models\Reference\Lga;
use App\Models\Reference\Lcda;
use App\Models\Reference\State;
use App\Models\Reference\Ward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommissionerPrimaryNoticeTest extends TestCase
{
    use RefreshDatabase;

    public function test_commissioner_approval_creates_linked_primary_event(): void
    {
        /*
         * ------------------------------------------------------------------
         * Permissions
         * ------------------------------------------------------------------
         */

$approvePermission = Permission::findOrCreate(
    'party-primary-notices.approve',
    'web'
);

$primaryMonitoringViewPermission = Permission::findOrCreate(
    'primary-monitoring.view',
    'web'
);

Permission::findOrCreate(
    'nominations.view',
    'web'
);

Permission::findOrCreate(
    'nominations.review',
    'web'
);

$commissionerRole = Role::findOrCreate(
    'Commissioner',
    'web'
);

$commissionerRole->givePermissionTo($approvePermission);

$epmRole = Role::findOrCreate(
    'EPM Officer',
    'web'
);

$epmRole->givePermissionTo($primaryMonitoringViewPermission);

$epm = User::factory()->create();

$epm->assignRole($epmRole);
        /*
         * ------------------------------------------------------------------
         * Commissioner
         * ------------------------------------------------------------------
         */

        $commissioner = User::factory()->create();

        $commissioner->assignRole($commissionerRole);

        /*
         * ------------------------------------------------------------------
         * Reference data
         *
         * The test database is created empty by RefreshDatabase.
         * Therefore the test creates the reference records it requires.
         * No database IDs are hardcoded.
         * ------------------------------------------------------------------
         */

        $state = State::create([
            'name' => 'Test State',
            'code' => 'TST',
            'capital' => 'Test Capital',
            'geopolitical_zone' => 'South West',
            'is_active' => true,
        ]);

        $electionType = ElectionType::create([
            'name' => 'Test Election Type',
            'description' => 'Election type used by the feature test.',
            'is_active' => true,
        ]);

        $lga = Lga::create([
            'state_id' => $state->id,
            'name' => 'Test LGA',
            'is_active' => true,
        ]);

        $lcda = Lcda::create([
            'lga_id' => $lga->id,
            'name' => 'Test LCDA',
            'is_active' => true,
        ]);

        $ward = Ward::create([
            'lga_id' => $lga->id,
            'name' => 'Test Ward',
            'is_active' => true,
        ]);

        $politicalParty = PoliticalParty::create([
            'name' => 'Test Political Party',
            'acronym' => 'TPP',
            'is_active' => true,
        ]);

        $position = Position::create([
            'name' => 'Test Position',
            'code' => 'TEST',
            'display_order' => 1,
            'is_active' => true,
        ]);

        /*
         * ------------------------------------------------------------------
         * Election
         * ------------------------------------------------------------------
         */

        $election = Election::create([
            'election_type_id' => $electionType->id,
            'state_id' => $state->id,
            'name' => 'Test Election',
            'election_date' => now()->addMonths(3)->toDateString(),
            'status' => 'draft',
            'is_active' => true,
            'lga_id' => $lga->id,
            'lcda_id' => $lcda->id,
            'ward_id' => $ward->id,
        ]);

        /*
         * ------------------------------------------------------------------
         * Party primary notice
         * ------------------------------------------------------------------
         */

        $notice = PartyPrimaryNotice::create([
            'political_party_id' => $politicalParty->id,
            'election_id' => $election->id,
            'position_id' => $position->id,
            'primary_type' => PrimaryEvent::TYPE_DIRECT,
            'scheduled_date' => now()->addDays(2)->toDateString(),
            'scheduled_time' => '10:00:00',
            'venue' => 'Test Primary Venue',
            'lga_id' => $lga->id,
            'lcda_id' => $lcda->id,
            'ward_id' => $ward->id,
            'status' => PartyPrimaryNotice::STATUS_SUBMITTED,
            'submitted_at' => now(),
            'submitted_by' => $commissioner->id,
        ]);

        /*
         * ------------------------------------------------------------------
         * Commissioner approval
         * ------------------------------------------------------------------
         */

        $response = $this
            ->actingAs($commissioner)
            ->post(
                route(
                    'staff.commissioner.primary-notices.approve',
                    $notice
                )
            );

        $response->assertRedirect();

        /*
         * ------------------------------------------------------------------
         * Verify notice approval
         * ------------------------------------------------------------------
         */

        $notice->refresh();

        $this->assertSame(
            PartyPrimaryNotice::STATUS_APPROVED,
            $notice->status
        );

        /*
         * ------------------------------------------------------------------
         * Verify linked PrimaryEvent
         * ------------------------------------------------------------------
         */

        $this->assertDatabaseHas('primary_events', [
            'party_primary_notice_id' => $notice->id,
            'election_id' => $notice->election_id,
            'political_party_id' => $notice->political_party_id,
            'position_id' => $notice->position_id,
            'lga_id' => $notice->lga_id,
            'lcda_id' => $notice->lcda_id,
            'ward_id' => $notice->ward_id,
            'primary_type' => $notice->primary_type,
            'venue' => $notice->venue,
            'notice_status' => PrimaryEvent::NOTICE_RECEIVED,
            'status' => PrimaryEvent::STATUS_SCHEDULED,
            'created_by' => $commissioner->id,
        ]);

        /*
         * ------------------------------------------------------------------
         * Verify duplicate protection.
         * ------------------------------------------------------------------
         */

        $this->assertSame(
            1,
            PrimaryEvent::query()
                ->where(
                    'party_primary_notice_id',
                    $notice->id
                )
                ->count()
        );
    }
}
