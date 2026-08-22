<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use App\Models\Election\ElectionPosition;
use App\Models\Election\Position;
use App\Models\Party\PoliticalParty;
use App\Models\PrimaryEvent;
use App\Models\Reference\Lga;
use App\Models\Reference\Lcda;
use App\Models\Reference\Ward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\PrimaryEventMonitorAssignment;
use App\Models\User;
use App\Models\PrimaryEventMonitoringReport;
use App\Models\PrimaryEventMonitoringReportAttachment;
use Illuminate\Support\Facades\Storage;


class EpmPrimaryMonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $status = $request->input('status');

        $primaryType = $request->input('primary_type');

        $events = PrimaryEvent::query()
            ->with([
                'election',
                'politicalParty',
                'position',
                'lga',
                'lcda',
                'ward',
                'creator',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('venue', 'like', "%{$search}%")
                        ->orWhereHas(
                            'politicalParty',
                            fn ($query) => $query
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere(
                                    'acronym',
                                    'like',
                                    "%{$search}%"
                                )
                        )
                        ->orWhereHas(
                            'election',
                            fn ($query) => $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                        );
                });
            })
            ->when(
                in_array(
                    $status,
                    [
                        PrimaryEvent::STATUS_SCHEDULED,
                        PrimaryEvent::STATUS_MONITOR_ASSIGNED,
                        PrimaryEvent::STATUS_MONITORING,
                        PrimaryEvent::STATUS_REPORT_PENDING,
                        PrimaryEvent::STATUS_COMPLETED,
                        PrimaryEvent::STATUS_FLAGGED,
                        PrimaryEvent::STATUS_CLOSED,
                    ],
                    true
                ),
                fn ($query) => $query->where('status', $status)
            )
            ->when(
                in_array(
                    $primaryType,
                    [
                        PrimaryEvent::TYPE_DIRECT,
                        PrimaryEvent::TYPE_INDIRECT,
                        PrimaryEvent::TYPE_CONSENSUS,
                    ],
                    true
                ),
                fn ($query) => $query->where(
                    'primary_type',
                    $primaryType
                )
            )
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->paginate(15)
            ->withQueryString();

        return view('staff.epm.primary-monitoring.index', [
            'events' => $events,
            'search' => $search,
            'status' => $status,
            'primaryType' => $primaryType,
        ]);
    }

    public function create(): View
    {
        $elections = Election::query()
            ->where('is_active', true)
            ->orderBy('election_date')
            ->get();

        $parties = PoliticalParty::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $positions = Position::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $lgas = Lga::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('staff.epm.primary-monitoring.create', [
            'elections' => $elections,
            'parties' => $parties,
            'positions' => $positions,
            'lgas' => $lgas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'election_id' => [
                'required',
                'integer',
                'exists:elections,id',
            ],

            'political_party_id' => [
                'required',
                'integer',
                'exists:political_parties,id',
            ],

            'position_id' => [
                'required',
                'integer',
                'exists:positions,id',
            ],

            'lga_id' => [
                'nullable',
                'integer',
                'exists:lgas,id',
            ],

            'lcda_id' => [
                'nullable',
                'integer',
                'exists:lcdas,id',
            ],

            'ward_id' => [
                'nullable',
                'integer',
                'exists:wards,id',
            ],

            'primary_type' => [
                'required',
                Rule::in([
                    PrimaryEvent::TYPE_DIRECT,
                    PrimaryEvent::TYPE_INDIRECT,
                    PrimaryEvent::TYPE_CONSENSUS,
                ]),
            ],

            'scheduled_date' => [
                'required',
                'date',
            ],

            'scheduled_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'venue' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notice_received_at' => [
                'nullable',
                'date',
            ],

            'notice_status' => [
                'required',
                Rule::in([
                    PrimaryEvent::NOTICE_PENDING,
                    PrimaryEvent::NOTICE_RECEIVED,
                    PrimaryEvent::NOTICE_LATE,
                    PrimaryEvent::NOTICE_INCOMPLETE,
                    PrimaryEvent::NOTICE_VERIFIED,
                ]),
            ],
        ]);

        $positionBelongsToElection = ElectionPosition::query()
            ->where('election_id', $validated['election_id'])
            ->where('position_id', $validated['position_id'])
            ->exists();

        if (! $positionBelongsToElection) {
            throw ValidationException::withMessages([
                'position_id' =>
                    'The selected position is not configured for this election.',
            ]);
        }

        $event = PrimaryEvent::create([
            ...$validated,
            'status' => PrimaryEvent::STATUS_SCHEDULED,
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('staff.epm.primary-monitoring.show', $event)
            ->with(
                'success',
                'Primary monitoring event created successfully.'
            );
    }
public function reports(): View
{
    $reports = PrimaryEventMonitoringReport::query()
        ->with([
            'primaryEvent.election',
            'primaryEvent.politicalParty',
            'primaryEvent.position',
            'primaryEvent.lga',
            'primaryEvent.lcda',
            'primaryEvent.ward',
            'monitor',
        ])
        ->where(
            'status',
            PrimaryEventMonitoringReport::STATUS_SUBMITTED
        )
        ->latest('submitted_at')
        ->get();

    return view(
        'staff.epm.primary-monitoring.reports.index',
        [
            'reports' => $reports,
        ]
    );
}

  public function show(
    PrimaryEvent $primaryEvent
): View {
    $primaryEvent->load([
        'election',
        'politicalParty',
        'position',
        'lga',
        'lcda',
        'ward',
        'creator',
        'assignments.monitor',
        'assignments.assignedBy',
        'monitoringReports.attachments',
    ]);

    $monitors = User::role('EPM Officer')
        ->orderBy('name')
        ->get();

    $report = $primaryEvent->monitoringReports
        ->sortByDesc('created_at')
        ->first();

    return view('staff.epm.primary-monitoring.show', [
        'event' => $primaryEvent,
        'monitors' => $monitors,
        'report' => $report,
    ]);
}

    public function assignMonitor(Request $request, PrimaryEvent $primaryEvent): RedirectResponse
{
    $validated = $request->validate([
        'monitor_id' => [
            'required',
            'integer',
            'exists:users,id',
        ],

        'instructions' => [
            'nullable',
            'string',
            'max:5000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Prevent multiple active assignments
    |--------------------------------------------------------------------------
    */

    $primaryEvent->assignments()
        ->where('status', 'assigned')
        ->update([
            'status' => 'reassigned',
            'completed_at' => now(),
        ]);

    /*
    |--------------------------------------------------------------------------
    | Create assignment
    |--------------------------------------------------------------------------
    */

    PrimaryEventMonitorAssignment::create([
        'primary_event_id' => $primaryEvent->id,
        'monitor_id' => $validated['monitor_id'],
        'assigned_by' => $request->user()->id,
        'assigned_at' => now(),
        'status' => 'assigned',
        'instructions' => $validated['instructions'] ?? null,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Update primary event workflow status
    |--------------------------------------------------------------------------
    */

    $primaryEvent->update([
        'status' => PrimaryEvent::STATUS_MONITOR_ASSIGNED,
    ]);

    return redirect()
        ->route(
            'staff.epm.primary-monitoring.show',
            $primaryEvent
        )
        ->with(
            'success',
            'EPM monitor assigned successfully.'
        );
}
public function submitReport(
    Request $request,
    PrimaryEvent $primaryEvent
): RedirectResponse {
    $primaryEvent->load('currentAssignment');

    $assignment = $primaryEvent->currentAssignment;

    abort_unless(
        $assignment &&
        $assignment->monitor_id === $request->user()->id,
        403
    );

    $validated = $request->validate([
        'attendance_status' => [
            'nullable',
            'string',
            'in:orderly,disrupted,abandoned',
        ],

        'accredited_voters' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'votes_cast' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'observations' => [
            'nullable',
            'string',
            'max:10000',
        ],

        'incidents' => [
            'nullable',
            'string',
            'max:10000',
        ],

        'recommendations' => [
            'nullable',
            'string',
            'max:10000',
        ],

        'photos' => [
            'nullable',
            'array',
            'max:10',
        ],

        'photos.*' => [
            'file',
            'mimes:jpg,jpeg,png,webp',
            'max:10240',
        ],

        'documents' => [
            'nullable',
            'array',
            'max:10',
        ],

        'documents.*' => [
            'file',
            'mimes:pdf,doc,docx',
            'max:10240',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Save monitoring report
    |--------------------------------------------------------------------------
    */

    $report = $primaryEvent->monitoringReports()->updateOrCreate(
        [
            'monitor_id' => $request->user()->id,
        ],
        [
            'attendance_status' =>
                $validated['attendance_status'] ?? null,

            'accredited_voters' =>
                $validated['accredited_voters'] ?? null,

            'votes_cast' =>
                $validated['votes_cast'] ?? null,

            'observations' =>
                $validated['observations'] ?? null,

            'incidents' =>
                $validated['incidents'] ?? null,

            'recommendations' =>
                $validated['recommendations'] ?? null,

            'status' =>
                PrimaryEventMonitoringReport::STATUS_SUBMITTED,

            'submitted_at' => now(),
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Save uploaded photos
    |--------------------------------------------------------------------------
    */

    foreach ($request->file('photos', []) as $photo) {

        $path = $photo->store(
            'primary-monitoring-reports/photos',
            'public'
        );

        $report->attachments()->create([
            'uploaded_by' => $request->user()->id,
            'category' => 'photo',
            'original_name' => $photo->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $photo->getMimeType(),
            'file_size' => $photo->getSize(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save uploaded documents
    |--------------------------------------------------------------------------
    */

    foreach ($request->file('documents', []) as $document) {

        $path = $document->store(
            'primary-monitoring-reports/documents',
            'public'
        );

        $report->attachments()->create([
            'uploaded_by' => $request->user()->id,
            'category' => 'document',
            'original_name' => $document->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $document->getMimeType(),
            'file_size' => $document->getSize(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Move event into commissioner report-review stage
    |--------------------------------------------------------------------------
    */

    $primaryEvent->update([
        'status' => PrimaryEvent::STATUS_REPORT_PENDING,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Complete monitor assignment
    |--------------------------------------------------------------------------
    */

    $assignment->update([
        'completed_at' => now(),
        'status' => 'completed',
    ]);

    return redirect()
        ->route(
            'staff.epm.primary-monitoring.show',
            $primaryEvent
        )
        ->with(
            'success',
            'Primary monitoring report submitted successfully.'
        );
}
public function reportShow(
    PrimaryEventMonitoringReport $report
): View {
    $report->load([
        'primaryEvent.election',
        'primaryEvent.politicalParty',
        'primaryEvent.position',
        'primaryEvent.lga',
        'primaryEvent.lcda',
        'primaryEvent.ward',
        'monitor',
        'attachments',
    ]);

    abort_unless(
        $report->status === PrimaryEventMonitoringReport::STATUS_SUBMITTED,
        404
    );

    return view(
        'staff.epm.primary-monitoring.reports.show',
        [
            'report' => $report,
            'event' => $report->primaryEvent,
        ]
    );
}

}
