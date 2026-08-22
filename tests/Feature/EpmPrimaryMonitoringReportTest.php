<?php

namespace Tests\Feature;

use App\Models\Election\Election;
use App\Models\Election\ElectionType;
use App\Models\Election\Position;
use App\Models\Party\PoliticalParty;
use App\Models\PartyPrimaryNotice;
use App\Models\PrimaryEvent;
use App\Models\PrimaryEventMonitorAssignment;
use App\Models\PrimaryEventMonitoringReport;
use App\Models\Reference\Lga;
use App\Models\Reference\Lcda;
use App\Models\Reference\State;
use App\Models\Reference\Ward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EpmPrimaryMonitoringReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_monitor_can_submit_primary_monitoring_report(): void
    {
        /*
         * ------------------------------------------------------------------
         * Permission
         * ------------------------------------------------------------------
         */

        $permission = Permission::findOrCreate(
            'primary-monitoring.view',
            'web'
        );

        $epmRole = Role::findOrCreate(
            'EPM Officer',
            'web'
        );

        $epmRole->givePermissionTo($permission);

        /*
         * ------------------------------------------------------------------
         * Users
         * ------------------------------------------------------------------
         */

        $epm = User::factory()->create();

        $epm->assignRole($epmRole);

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
            'submitted_by' => $epm->id,
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
            'scheduled_date' => now()->addDays(2)->toDateString(),
            'scheduled_time' => '10:00:00',
            'venue' => 'Test Primary Venue',
            'notice_received_at' => now(),
            'notice_status' => PrimaryEvent::NOTICE_RECEIVED,
            'status' => PrimaryEvent::STATUS_MONITOR_ASSIGNED,
            'created_by' => $epm->id,
        ]);

        /*
         * ------------------------------------------------------------------
         * Monitor assignment
         * ------------------------------------------------------------------
         */

        PrimaryEventMonitorAssignment::create([
            'primary_event_id' => $event->id,
            'monitor_id' => $epm->id,
            'assigned_by' => $epm->id,
            'assigned_at' => now(),
            'status' => 'assigned',
        ]);

        /*
         * ------------------------------------------------------------------
         * Submit monitoring report
         * ------------------------------------------------------------------
         */

        $response = $this
            ->actingAs($epm)
            ->post(
                route(
                    'staff.epm.primary-monitoring.submit-report',
                    $event
                ),
                [
                    'attendance_status' => 'orderly',
                    'accredited_voters' => 500,
                    'votes_cast' => 450,
                    'observations' => 'Primary exercise proceeded normally.',
                    'incidents' => null,
                    'recommendations' => 'No immediate action required.',
                ]
            );

        $response->assertRedirect();

        /*
         * ------------------------------------------------------------------
         * Verify monitoring report
         * ------------------------------------------------------------------
         */

        $this->assertDatabaseHas(
            'primary_event_monitoring_reports',
            [
                'primary_event_id' => $event->id,
                'monitor_id' => $epm->id,
                'attendance_status' => 'orderly',
                'accredited_voters' => 500,
                'votes_cast' => 450,
                'status' => PrimaryEventMonitoringReport::STATUS_SUBMITTED,
            ]
        );

        /*
         * ------------------------------------------------------------------
         * Verify event workflow status
         * ------------------------------------------------------------------
         */

        $event->refresh();

        $this->assertSame(
            PrimaryEvent::STATUS_REPORT_PENDING,
            $event->status
        );

        /*
         * ------------------------------------------------------------------
         * Verify assignment completion
         * ------------------------------------------------------------------
         */

        $assignment = $event
            ->assignments()
            ->where('monitor_id', $epm->id)
            ->first();

        $this->assertNotNull($assignment);

        $this->assertSame(
            'completed',
            $assignment->status
        );

        $this->assertNotNull(
            $assignment->completed_at
        );
    }
}
