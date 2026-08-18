<?php

namespace App\Http\Controllers\Web\Results;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use App\Models\Election\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Party\PoliticalParty;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use App\Models\ResultImport;
use App\Models\ResultEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class IctResultController extends Controller
{
   public function index(): View
{
    $imports = ResultImport::query()
        ->with([
            'election',
            'position',
            'uploader',
        ])
        ->latest('created_at')
        ->get();

    $draftResults = $imports
        ->whereIn('status', ['imported', 'importing'])
        ->count();

    $publishedResults = $imports
        ->where('status', 'published')
        ->count();

    $archivedResults = $imports
        ->where('status', 'archived')
        ->count();

    $publishedVotes = ResultEntry::query()
        ->whereHas('resultImport', function ($query) {
            $query->where('status', 'published');
        })
        ->sum('votes');

    return view('staff.ict.results.index', [
        'imports' => $imports,

        'draftResults' => $draftResults,

        'publishedResults' => $publishedResults,

        'archivedResults' => $archivedResults,

        'publishedVotes' => $publishedVotes,

        'elections' => Election::query()
            ->with([
                'electionType',
                'state',
            ])
            ->latest('election_date')
            ->get(),
    ]);
}

public function show(ResultImport $resultImport): View
{
    $resultImport->load([
        'election',
        'position',
        'uploader',
    ]);

    $entries = ResultEntry::query()
        ->with([
            'lga',
            'ward',
            'politicalParty',
        ])
        ->where('result_import_id', $resultImport->id)
        ->orderBy('lga_id')
        ->orderBy('ward_id')
        ->orderBy('polling_unit_code')
        ->orderBy('political_party_id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Overall totals
    |--------------------------------------------------------------------------
    */

    $totalVotes = $entries->sum('votes');

    $pollingUnits = $entries
        ->groupBy(function ($entry) {
            return $entry->lga_id . ':' .
                $entry->ward_id . ':' .
                $entry->polling_unit_code;
        })
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Overall party totals
    |--------------------------------------------------------------------------
    */

    $parties = $entries
        ->groupBy('political_party_id')
        ->map(function ($partyEntries) {
            return [
                'party' => $partyEntries->first()->politicalParty,
                'votes' => $partyEntries->sum('votes'),
            ];
        })
        ->filter(function ($item) {
            return ($item['votes'] ?? 0) > 0;
        })
        ->sortByDesc('votes')
        ->values();


    /*
    |--------------------------------------------------------------------------
    | LGA → Ward → Party totals
    |--------------------------------------------------------------------------
    */

    $lgas = $entries
        ->groupBy('lga_id')
        ->map(function ($lgaEntries) {

            /*
            |--------------------------------------------------------------------------
            | Group this LGA into wards
            |--------------------------------------------------------------------------
            */

            $wards = $lgaEntries
                ->groupBy('ward_id')
                ->map(function ($wardEntries) {

                    /*
                    |--------------------------------------------------------------------------
                    | Calculate party totals for this ward
                    |--------------------------------------------------------------------------
                    */

                    $wardParties = $wardEntries
                        ->groupBy('political_party_id')
                        ->map(function ($partyEntries) {
                            return [
                                'party' => $partyEntries->first()->politicalParty,
                                'votes' => $partyEntries->sum('votes'),
                            ];
                        })
                        ->filter(function ($item) {
                            return ($item['votes'] ?? 0) > 0;
                        })
                        ->sortByDesc('votes')
                        ->values();


                    /*
                    |--------------------------------------------------------------------------
                    | First party after sorting = ward winner
                    |--------------------------------------------------------------------------
                    */

                    $winner = $wardParties->first();


                    return [
                        'ward' => $wardEntries->first()->ward,

                        'polling_units' => $wardEntries
                            ->groupBy('polling_unit_code')
                            ->count(),

                        'votes' => $wardEntries->sum('votes'),

                        'parties' => $wardParties,

                        'winner' => $winner,
                    ];
                })
                ->sortBy(function ($item) {
                    return $item['ward']->name ?? '';
                })
                ->values();


            /*
            |--------------------------------------------------------------------------
            | LGA party totals
            |--------------------------------------------------------------------------
            */

            $lgaParties = $lgaEntries
                ->groupBy('political_party_id')
                ->map(function ($partyEntries) {
                    return [
                        'party' => $partyEntries->first()->politicalParty,
                        'votes' => $partyEntries->sum('votes'),
                    ];
                })
                ->filter(function ($item) {
                    return ($item['votes'] ?? 0) > 0;
                })
                ->sortByDesc('votes')
                ->values();


            return [
                'lga' => $lgaEntries->first()->lga,

                'polling_units' => $lgaEntries
                    ->groupBy(function ($entry) {
                        return $entry->ward_id . ':' .
                            $entry->polling_unit_code;
                    })
                    ->count(),

                'votes' => $lgaEntries->sum('votes'),

                'parties' => $lgaParties,

                'winner' => $lgaParties->first(),

                'wards' => $wards,
            ];
        })
        ->sortBy(function ($item) {
            return $item['lga']->name ?? '';
        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Return result page
    |--------------------------------------------------------------------------
    */

    return view('staff.ict.results.show', [
        'resultImport' => $resultImport,
        'entries' => $entries,
        'totalVotes' => $totalVotes,
        'pollingUnits' => $pollingUnits,
        'parties' => $parties,
        'lgas' => $lgas,
    ]);
}
public function ward(
    ResultImport $resultImport,
    Ward $ward
): View {
    $resultImport->load([
        'election',
        'position',
        'uploader',
    ]);

    $entries = ResultEntry::query()
        ->with([
            'lga',
            'ward',
            'politicalParty',
        ])
        ->where('result_import_id', $resultImport->id)
        ->where('ward_id', $ward->id)
        ->orderBy('polling_unit_code')
        ->orderBy('political_party_id')
        ->get();

    abort_if($entries->isEmpty(), 404);

    $totalVotes = $entries->sum('votes');

    /*
    |--------------------------------------------------------------------------
    | Polling unit totals
    |--------------------------------------------------------------------------
    */

    $pollingUnits = $entries
        ->groupBy('polling_unit_code')
        ->map(function ($unitEntries) {

            $parties = $unitEntries
                ->groupBy('political_party_id')
                ->map(function ($partyEntries) {
                    return [
                        'party' => $partyEntries->first()->politicalParty,
                        'votes' => $partyEntries->sum('votes'),
                    ];
                })
                ->filter(function ($item) {
                    return ($item['votes'] ?? 0) > 0;
                })
                ->sortByDesc('votes')
                ->values();

            return [
                'code' => $unitEntries->first()->polling_unit_code,
                'name' => $unitEntries->first()->polling_unit_name,
                'votes' => $unitEntries->sum('votes'),
                'parties' => $parties,
                'winner' => $parties->first(),
            ];
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Ward party totals
    |--------------------------------------------------------------------------
    */

    $parties = $entries
        ->groupBy('political_party_id')
        ->map(function ($partyEntries) {
            return [
                'party' => $partyEntries->first()->politicalParty,
                'votes' => $partyEntries->sum('votes'),
            ];
        })
        ->filter(function ($item) {
            return ($item['votes'] ?? 0) > 0;
        })
        ->sortByDesc('votes')
        ->values();

    return view('staff.ict.results.ward', [
        'resultImport' => $resultImport,
        'ward' => $ward,
        'entries' => $entries,
        'pollingUnits' => $pollingUnits,
        'parties' => $parties,
        'totalVotes' => $totalVotes,
    ]);
}

public function publish(ResultImport $resultImport): RedirectResponse
{
    if ($resultImport->status !== 'imported') {
        return redirect()
            ->route('staff.ict.results.show', $resultImport)
            ->withErrors([
                'result' => 'Only imported results can be published.',
            ]);
    }

    $resultImport->update([
        'status' => 'published',
        'published_at' => Carbon::now(),
    ]);

    return redirect()
        ->route('staff.ict.results.show', $resultImport)
        ->with('success', 'Election results published successfully.');
}

    public function create(): View
    {
        return view('staff.ict.results.create', [
            'elections' => Election::query()
                ->with([
                    'electionType',
                    'state',
                ])
                ->latest('election_date')
                ->get(),

            'positions' => Position::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(),
        ]);
    }

    public function analyse(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'election_id' => [
                'required',
                'integer',
                'exists:elections,id',
            ],

            'position_id' => [
                'required',
                'integer',
                'exists:positions,id',
            ],

            'result_file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:20480',
            ],
        ]);

        $election = Election::query()
            ->with([
                'electionType',
                'state',
            ])
            ->findOrFail($validated['election_id']);

        $position = Position::query()
            ->where('is_active', true)
            ->findOrFail($validated['position_id']);

        $file = $request->file('result_file');

        /*
        |--------------------------------------------------------------------------
        | Store the exact uploaded workbook temporarily
        |--------------------------------------------------------------------------
        |
        | The workbook will be analysed now and imported later.
        | We therefore preserve the exact file that was analysed.
        |
        */

        $storedPath = $file->store(
            'result-imports/pending'
        );

        /*
        |--------------------------------------------------------------------------
        | Load workbook
        |--------------------------------------------------------------------------
        */

        try {

            $spreadsheet = IOFactory::load(
                $file->getRealPath()
            );

        } catch (\Throwable $exception) {

            Storage::delete($storedPath);

            return back()
                ->withInput()
                ->withErrors([
                    'result_file' =>
                        'The spreadsheet could not be read. Please check that the file is a valid Excel or CSV file.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Analyse worksheets
        |--------------------------------------------------------------------------
        */

        $worksheets = [];

        $totalRows = 0;
        $totalPollingUnits = 0;
        $totalMissingNames = 0;
        $totalDuplicateCodes = 0;
        $totalInvalidVotes = 0;
        $totalPartyColumns = 0;

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {

            $analysis = $this->analyseWorksheet($worksheet);

            $worksheets[] = $analysis;

            $totalRows += $analysis['data_rows'];

            $totalPollingUnits += $analysis['polling_units'];

            $totalMissingNames += $analysis['missing_names'];

            $totalDuplicateCodes += $analysis['duplicate_codes'];

            $totalInvalidVotes += $analysis['invalid_votes'];

            $totalPartyColumns = max(
                $totalPartyColumns,
                count($analysis['parties'])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Determine validation status
        |--------------------------------------------------------------------------
        */

        $hasErrors = collect($worksheets)
            ->flatMap(
                fn ($worksheet) => $worksheet['issues']
            )
            ->contains(
                fn ($issue) =>
                    $issue['severity'] === 'error'
            );

        $hasWarnings = collect($worksheets)
            ->flatMap(
                fn ($worksheet) => $worksheet['issues']
            )
            ->contains(
                fn ($issue) =>
                    $issue['severity'] === 'warning'
            );

        $totalIssues = collect($worksheets)
            ->sum(
                fn ($worksheet) =>
                    count($worksheet['issues'])
            );

        $totalErrors = collect($worksheets)
            ->sum(
                fn ($worksheet) =>
                    collect($worksheet['issues'])
                        ->where('severity', 'error')
                        ->count()
            );

        $totalWarnings = collect($worksheets)
            ->sum(
                fn ($worksheet) =>
                    collect($worksheet['issues'])
                        ->where('severity', 'warning')
                        ->count()
            );

        /*
        |--------------------------------------------------------------------------
        | Import eligibility
        |--------------------------------------------------------------------------
        */

        $canImport = ! $hasErrors;

        /*
        |--------------------------------------------------------------------------
        | Analysis view
        |--------------------------------------------------------------------------
        */

        return view('staff.ict.results.analysis', [
            'election' => $election,

            'position' => $position,

            'fileName' => $file->getClientOriginalName(),

            'fileSize' => $file->getSize(),

            'pendingFile' => $storedPath,

            'worksheetCount' => count($worksheets),

            'totalRows' => $totalRows,

            'totalPollingUnits' => $totalPollingUnits,

            'totalMissingNames' => $totalMissingNames,

            'totalDuplicateCodes' => $totalDuplicateCodes,

            'totalInvalidVotes' => $totalInvalidVotes,

            'totalPartyColumns' => $totalPartyColumns,

            'totalIssues' => $totalIssues,

            'worksheets' => $worksheets,

            'hasErrors' => $hasErrors,

            'hasWarnings' => $hasWarnings,

            'totalErrors' => $totalErrors,

            'totalWarnings' => $totalWarnings,

            'canImport' => $canImport,
        ]);
    }

    public function import(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'pending_file' => [
            'required',
            'string',
        ],

        'election_id' => [
            'required',
            'integer',
            'exists:elections,id',
        ],

        'position_id' => [
            'required',
            'integer',
            'exists:positions,id',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Security: pending workbook must be inside our result-import folder
    |--------------------------------------------------------------------------
    */

    $pendingFile = $validated['pending_file'];

    if (
        ! str_starts_with(
            $pendingFile,
            'result-imports/pending/'
        )
    ) {
        abort(403);
    }

    if (! Storage::exists($pendingFile)) {
        return redirect()
            ->route('staff.ict.results.create')
            ->withErrors([
                'result_file' =>
                    'The pending result workbook could not be found. Please upload it again.',
            ]);
    }

    $election = Election::query()
        ->findOrFail($validated['election_id']);

    $position = Position::query()
        ->where('is_active', true)
        ->findOrFail($validated['position_id']);

    /*
    |--------------------------------------------------------------------------
    | Load the exact workbook that was analysed
    |--------------------------------------------------------------------------
    */

    try {
        $absolutePath = Storage::path($pendingFile);

        $spreadsheet = IOFactory::load($absolutePath);

    } catch (\Throwable $exception) {

        Storage::delete($pendingFile);

        return redirect()
            ->route('staff.ict.results.create')
            ->withErrors([
                'result_file' =>
                    'The analysed workbook could not be loaded. Please upload it again.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Import everything in one transaction
    |--------------------------------------------------------------------------
    */

    try {

        $resultImport = DB::transaction(function () use (
            $spreadsheet,
            $election,
            $position,
            $pendingFile,
            $request
        ) {

            $resultImport = ResultImport::create([
                'election_id' => $election->id,
                'position_id' => $position->id,
                'uploaded_by' => $request->user()->id,
                'original_filename' => basename($pendingFile),
                'status' => 'importing',
                'worksheet_count' => 0,
                'polling_unit_count' => 0,
                'result_entry_count' => 0,
                'analysed_at' => Carbon::now(),
            ]);

            $worksheetCount = 0;
            $pollingUnitCount = 0;
            $resultEntryCount = 0;

            foreach (
                $spreadsheet->getWorksheetIterator()
                as $worksheet
            ) {

                $worksheetCount++;

                $analysis = $this->analyseWorksheet(
                    $worksheet
                );

                /*
                |--------------------------------------------------------------------------
                | Do not import worksheets containing errors
                |--------------------------------------------------------------------------
                */

                $errors = collect(
                    $analysis['issues']
                )
                    ->where('severity', 'error');

                if ($errors->isNotEmpty()) {
                    throw new \RuntimeException(
                        "Worksheet {$worksheet->getTitle()} contains validation errors."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Resolve LGA
                |--------------------------------------------------------------------------
                */

                $lga = Lga::query()
                    ->get()
                    ->first(function ($item) use ($analysis) {
                        return $this->normalise($item->name)
                            === $this->normalise($analysis['lga']);
                    });

                if (! $lga) {
                    throw new \RuntimeException(
                        "LGA '{$analysis['lga']}' could not be matched to the database."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Resolve Ward
                |--------------------------------------------------------------------------
                */

                $ward = null;

                if (
                    $analysis['ward_name'] !== null &&
                    $analysis['ward_name'] !== ''
                ) {

                    $ward = Ward::query()
                        ->where('lga_id', $lga->id)
                        ->get()
                        ->first(function ($item) use ($analysis) {
                            return $this->normaliseWardName($item->name)
    === $this->normaliseWardName(
        $analysis['ward_name']
    );
                        });

                    if (! $ward) {
                        throw new \RuntimeException(
                            "Ward '{$analysis['ward_name']}' could not be matched to LGA '{$lga->name}'."
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Resolve party columns
                |--------------------------------------------------------------------------
                */

                $partyMap = [];

                foreach ($analysis['parties'] as $partyAcronym) {

                    $party = PoliticalParty::query()
                        ->whereRaw(
                            'UPPER(acronym) = ?',
                            [
                                strtoupper(
                                    trim($partyAcronym)
                                ),
                            ]
                        )
                        ->first();

                    if (! $party) {
                        throw new \RuntimeException(
                            "Political party '{$partyAcronym}' could not be matched to the database."
                        );
                    }

                    $partyMap[$partyAcronym] = $party;
                }

                /*
                |--------------------------------------------------------------------------
                | Locate actual result rows
                |--------------------------------------------------------------------------
                */

                $headerRow = $analysis['header_row'];
                $partyHeaderRow = $headerRow + 1;
                $highestRow = $worksheet->getHighestRow();

                /*
                |--------------------------------------------------------------------------
                | Import polling-unit × party results
                |--------------------------------------------------------------------------
                */

                for (
                    $row = $partyHeaderRow + 1;
                    $row <= $highestRow;
                    $row++
                ) {

                    $sn = $worksheet
                        ->getCell('A' . $row)
                        ->getValue();

                    $code = $worksheet
                        ->getCell('B' . $row)
                        ->getValue();

                    $name = $worksheet
                        ->getCell('C' . $row)
                        ->getValue();

                    if (
                        ! is_numeric($sn) ||
                        $code === null ||
                        trim((string) $code) === ''
                    ) {
                        continue;
                    }

                    $pollingUnitCode =
                        trim((string) $code);

                    $pollingUnitName =
                        $name !== null
                            ? trim((string) $name)
                            : null;

                    $pollingUnitCount++;

                    foreach (
                        $analysis['parties']
                        as $column => $partyAcronym
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | NOTE:
                        | analysis['parties'] contains values only.
                        | Re-resolve the column from the header row.
                        |--------------------------------------------------------------------------
                        */

                        $partyColumn = null;

                        $highestColumn =
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                                $worksheet->getHighestColumn()
                            );

                        for (
                            $candidateColumn = 4;
                            $candidateColumn <= $highestColumn;
                            $candidateColumn++
                        ) {

                            $headerValue =
                                $worksheet->getCell(
                                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                                        $candidateColumn
                                    ) . $partyHeaderRow
                                )->getValue();

                            if (
                                is_string($headerValue) &&
                                trim($headerValue) === $partyAcronym
                            ) {
                                $partyColumn = $candidateColumn;
                                break;
                            }
                        }

                        if ($partyColumn === null) {
                            continue;
                        }

                        $value = $worksheet
                            ->getCell(
                                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                                    $partyColumn
                                ) . $row
                            )
                            ->getValue();

                        /*
                        |--------------------------------------------------------------------------
                        | Empty vote cell = zero
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $value === null ||
                            trim((string) $value) === ''
                        ) {
                            $votes = 0;
                        } else {
                            $votes = (int) $value;
                        }

                        ResultEntry::create([
                            'result_import_id' =>
                                $resultImport->id,

                            'lga_id' =>
                                $lga->id,

                            'ward_id' =>
                                $ward?->id,

                            'political_party_id' =>
                                $partyMap[$partyAcronym]->id,

                            'polling_unit_code' =>
                                $pollingUnitCode,

                            'polling_unit_name' =>
                                $pollingUnitName,

                            'votes' =>
                                $votes,
                        ]);

                        $resultEntryCount++;
                    }
                }
            }

            $resultImport->update([
                'status' => 'imported',
                'worksheet_count' => $worksheetCount,
                'polling_unit_count' => $pollingUnitCount,
                'result_entry_count' => $resultEntryCount,
                'imported_at' => Carbon::now(),
            ]);

            return $resultImport;
        });

    } catch (\Throwable $exception) {

        report($exception);

        return redirect()
            ->route('staff.ict.results.index')
            ->withErrors([
                'result_file' =>
                    'The result workbook could not be imported. No result data was saved.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete temporary workbook after successful import
    |--------------------------------------------------------------------------
    */

    Storage::delete($pendingFile);

    return redirect()
        ->route('staff.ict.results.index')
        ->with(
            'success',
            "Result workbook imported successfully. {$resultImport->result_entry_count} result entries were saved."
        );
}

    /**
     * Analyse one ward worksheet.
     */
    private function analyseWorksheet($worksheet): array
    {
        $rows = $worksheet->toArray(
            null,
            true,
            true,
            true
        );

        $highestRow = $worksheet->getHighestRow();

        /*
        |--------------------------------------------------------------------------
        | Find the actual result header
        |--------------------------------------------------------------------------
        */

        $headerRow = null;

        for ($row = 1; $row <= $highestRow; $row++) {

            $a = $this->normalise(
                $worksheet->getCell("A{$row}")->getValue()
            );

            $b = $this->normalise(
                $worksheet->getCell("B{$row}")->getValue()
            );

            $c = $this->normalise(
                $worksheet->getCell("C{$row}")->getValue()
            );

            if (
                $a === 'S/N' &&
                $b === 'CODE' &&
                $c === 'NAME'
            ) {
                $headerRow = $row;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Could not identify result table
        |--------------------------------------------------------------------------
        */

        if ($headerRow === null) {

            return [
                'name' => $worksheet->getTitle(),

                'ward_name' => null,

                'ward_code' => null,

                'lga' => null,

                'header_row' => null,

                'parties' => [],

                'data_rows' => 0,

                'polling_units' => 0,

                'missing_names' => 0,

                'duplicate_codes' => 0,

                'invalid_votes' => 0,

                'issues' => [
                    [
                        'severity' => 'error',

                        'message' =>
                            'The result table header could not be identified.',
                    ],
                ],

                'preview' => [],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Identify party columns
        |--------------------------------------------------------------------------
        |
        | Party abbreviations are stored on the row immediately after
        | S/N / CODE / NAME.
        |
        */

        $partyHeaderRow = $headerRow + 1;

        $parties = [];

        $highestColumn =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                $worksheet->getHighestColumn()
            );

        for (
            $column = 4;
            $column <= $highestColumn;
            $column++
        ) {

            $cellAddress =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $column
                )
                . $partyHeaderRow;

            $value = $worksheet
                ->getCell($cellAddress)
                ->getValue();

            if (
                is_string($value) &&
                trim($value) !== ''
            ) {
                $parties[$column] = trim($value);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Extract ward/LGA information
        |--------------------------------------------------------------------------
        */

        $lga = null;

        $wardName = null;

        $wardCode = null;

        for (
            $row = 1;
            $row <= min($headerRow, 8);
            $row++
        ) {

            for (
                $column = 1;
                $column <= 3;
                $column++
            ) {

                $value = $worksheet
                    ->getCell(
                        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                            $column
                        ) . $row
                    )
                    ->getValue();

                if (! is_string($value)) {
                    continue;
                }

                $text = trim(
                    preg_replace('/\s+/', ' ', $value)
                );

                if (
                    preg_match(
                        '/L\.?G\.?A\.?\s*[:.â€¦\s-]*([A-Z ]+?)(?=\s+NAME OF WARD|\s*$)/i',
                        $text,
                        $match
                    )
                ) {
                    $lga = trim($match[1]);
                }

                if (
                    preg_match(
                        '/NAME OF WARD\s*[:.â€¦\s-]*([A-Z0-9\/\' .-]+?)(?=\s+CODE|\s*$)/i',
                        $text,
                        $match
                    )
                ) {
                    $wardName = trim($match[1]);
                }

                if (
                    preg_match(
                        '/CODE(?: NO)?\s*[:.â€¦\s-]*([0-9]+)/i',
                        $text,
                        $match
                    )
                ) {
                    $wardCode = trim($match[1]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback ward code from worksheet name
        |--------------------------------------------------------------------------
        */

        if ($wardCode === null) {

            if (
                preg_match(
                    '/WARD\s+(\d+)/i',
                    $worksheet->getTitle(),
                    $match
                )
            ) {
                $wardCode = $match[1];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Analyse polling-unit rows
        |--------------------------------------------------------------------------
        */

        $dataRows = 0;

        $pollingUnits = 0;

        $missingNames = 0;

        $duplicateCodes = 0;

        $invalidVotes = 0;

        $codes = [];

        $issues = [];

        $preview = [];

        for (
            $row = $partyHeaderRow + 1;
            $row <= $highestRow;
            $row++
        ) {

            $sn = $worksheet
                ->getCell('A' . $row)
                ->getValue();

            $code = $worksheet
                ->getCell('B' . $row)
                ->getValue();

            $name = $worksheet
                ->getCell('C' . $row)
                ->getValue();

            /*
            |--------------------------------------------------------------------------
            | A result row must have a numeric S/N and polling-unit code.
            |--------------------------------------------------------------------------
            */

            if (
                ! is_numeric($sn) ||
                $code === null ||
                trim((string) $code) === ''
            ) {
                continue;
            }

            $dataRows++;

            $pollingUnits++;

            $code = trim((string) $code);

            $name = $name !== null
                ? trim((string) $name)
                : '';

            /*
            |--------------------------------------------------------------------------
            | Missing polling-unit name
            |--------------------------------------------------------------------------
            */

            if ($name === '') {

                $missingNames++;

                $issues[] = [
                    'severity' => 'warning',

                    'row' => $row,

                    'message' =>
                        "Polling unit code {$code} has no polling station name.",
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate polling-unit code
            |--------------------------------------------------------------------------
            */

            if (isset($codes[$code])) {

                $duplicateCodes++;

                $issues[] = [
                    'severity' => 'error',

                    'row' => $row,

                    'message' =>
                        "Polling unit code {$code} is duplicated.",
                ];
            }

            $codes[$code] = true;

            /*
            |--------------------------------------------------------------------------
            | Validate party vote values
            |--------------------------------------------------------------------------
            */

            $partyValues = [];

            foreach ($parties as $column => $party) {

                $value = $worksheet
                    ->getCell(
                        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                            $column
                        ) . $row
                    )
                    ->getValue();

                $partyValues[$party] = $value;


                if (
                    $value === null ||
                    trim((string) $value) === ''
                ) {
                    continue;
                }

                if (
                    ! is_numeric($value) ||
                    (float) $value < 0 ||
                    floor((float) $value) != (float) $value
                ) {

                    $invalidVotes++;

                    $issues[] = [
                        'severity' => 'error',

                        'row' => $row,

                        'message' =>
                            "Invalid vote value for {$party} at polling unit {$code}.",
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Preview first five valid polling-unit rows
            |--------------------------------------------------------------------------
            */

            if (count($preview) < 5) {

                $preview[] = [
                    'row' => $row,

                    'sn' => $sn,

                    'code' => $code,

                    'name' => $name,

                    'votes' => $partyValues,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Missing metadata
        |--------------------------------------------------------------------------
        */

        if (
            $wardName === null ||
            $wardName === ''
        ) {

            $issues[] = [
                'severity' => 'warning',

                'message' =>
                    'Ward name could not be identified from the worksheet.',
            ];
        }

        if (
            $lga === null ||
            $lga === ''
        ) {

            $issues[] = [
                'severity' => 'warning',

                'message' =>
                    'LGA could not be identified from the worksheet.',
            ];
        }

        return [
            'name' => $worksheet->getTitle(),

            'ward_name' => $wardName,

            'ward_code' => $wardCode,

            'lga' => $lga,

            'header_row' => $headerRow,

            'parties' => array_values($parties),

            'data_rows' => $dataRows,

            'polling_units' => $pollingUnits,

            'missing_names' => $missingNames,

            'duplicate_codes' => $duplicateCodes,

            'invalid_votes' => $invalidVotes,

            'issues' => $issues,

            'preview' => $preview,
        ];
    }


    private function normalise(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        return strtoupper(
            trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    (string) $value
                )
            )
        );
    }

    private function normaliseWardName(mixed $value): string
{
    $value = $this->normalise($value);

    return preg_replace_callback(
        '/\b(II|III|IV|V|VI|VII|VIII|IX|X|I)\b/',
        function ($match) {
            return match ($match[1]) {
                'I' => '1',
                'II' => '2',
                'III' => '3',
                'IV' => '4',
                'V' => '5',
                'VI' => '6',
                'VII' => '7',
                'VIII' => '8',
                'IX' => '9',
                'X' => '10',
                default => $match[1],
            };
        },
        $value
    );
}
}
