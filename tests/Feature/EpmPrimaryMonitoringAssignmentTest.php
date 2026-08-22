<?php

namespace Tests\Feature;

use App\Models\Election\Election;
use App\Models\Election\ElectionType;
use App\Models\Election\Position;
use App\Models\Party\PoliticalParty;
use App\Models\PartyPrimaryNotice;
use App\Models\PrimaryEvent;
use App\Models\PrimaryEventMonitorAssignment;
use App\Models\Reference\Lga;
use App\Models\Reference\Lcda;
use App\Models\Reference\State;
use App\Models\Reference\Ward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EpmPrimaryMonitoringAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_epm_can_assign_monitor_to_primary_event(): void
    {
        /*
         * ------------------------------------------------------------------
         * Permission
         * ------------------------------------------------------------------
         */

        $permission = Permission::findOrCreate(
            'primary-monitoring.assign',
            'web'
        );

        $epmRole = Role::findOrCreate(
            'EPM Officer',
            'web'
        );

        $epmRole->givePermissionTo($permission);

        /*
         * ------------------------------------------------------------------
         * EPM user
         * ------------------------------------------------------------------
         */

        $epmOfficer = User::factory()->create();

        $epmOfficer->assignRole($epmRole);

        /*
         * ------------------------------------------------------------------
         * Monitor
         * ------------------------------------------------------------------
         */

        $monitor = User::factory()->create([
            'name' => 'Test EPM Monitor',
        ]);

        $monitor->assignRole($epmRole);

        /*
         * ------------------------------------------------------------------
         * Reference data
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
         * Primary event
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
            'status' => PartyPrimaryNotice::STATUS_APPROVED,
            'submitted_at' => now(),
            'submitted_by' => $epmOfficer->id,
        ]);

        $event = PrimaryEvent::create([
            'party_primary_notice_id' => $notice->id,
            'election_id' => $election->id,
            'political_party_id' => $politicalParty->id,
            'position_id' => $position->id,
            'lga_id' => $lga->id,
            'lcda_id' => $lcda->id,
            'ward_id' => $ward->id,
            'primary_type' => PrimaryEvent::TYPE_DIRECT,
            'scheduled_date' => $notice->scheduled_date,
            'scheduled_time' => $notice->scheduled_time,
            'venue' => $notice->venue,
            'notice_received_at' => now(),
            'notice_status' => PrimaryEvent::NOTICE_RECEIVED,
            'status' => PrimaryEvent::STATUS_SCHEDULED,
            'created_by' => $epmOfficer->id,
        ]);

        /*
         * ------------------------------------------------------------------
         * Assign monitor
         * ------------------------------------------------------------------
         */

        $response = $this
            ->actingAs($epmOfficer)
            ->post(
                route(
                    'staff.epm.primary-monitoring.assign-monitor',
                    $event
                ),
                [
                    'monitor_id' => $monitor->id,
                    'instructions' => 'Report to the venue before the primary begins.',
                ]
            );

        $response->assertRedirect();

        /*
         * ------------------------------------------------------------------
         * Verify event status
         * ------------------------------------------------------------------
         */

        $event->refresh();

        $this->assertSame(
            PrimaryEvent::STATUS_MONITOR_ASSIGNED,
            $event->status
        );

        /*
         * ------------------------------------------------------------------
         * Verify assignment
         * ------------------------------------------------------------------
         */

        $this->assertDatabaseHas(
            'primary_event_monitor_assignments',
            [
                'primary_event_id' => $event->id,
                'monitor_id' => $monitor->id,
                'assigned_by' => $epmOfficer->id,
                'status' => 'assigned',
                'instructions' => 'Report to the venue before the primary begins.',
            ]
        );

        $this->assertSame(
            1,
            PrimaryEventMonitorAssignment::query()
                ->where('primary_event_id', $event->id)
                ->where('status', 'assigned')
                ->count()
        );
    }
}
