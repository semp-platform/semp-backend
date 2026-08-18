<?php

namespace App\Exports;

use App\Models\Nomination\Nomination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FinalPublicationExport
{
    public function __construct(
        protected array $filters = []
    ) {
    }

    public function download()
    {
        $query = Nomination::query()
            ->with([
                'candidate',
                'politicalParty',
                'position',
                'election',
                'lga',
            ])
            ->where(
                'status',
                Nomination::STATUS_APPROVED
            );

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if (! empty($this->filters['political_party_id'])) {
            $query->where(
                'political_party_id',
                (int) $this->filters['political_party_id']
            );
        }

        if (! empty($this->filters['position_id'])) {
            $query->where(
                'position_id',
                (int) $this->filters['position_id']
            );
        }

        if (! empty($this->filters['lga_id'])) {
            $query->where(
                'lga_id',
                (int) $this->filters['lga_id']
            );
        }

        if (! empty($this->filters['gender'])) {
            $query->whereHas('candidate', function ($candidateQuery) {
                $candidateQuery->where(
                    'gender',
                    $this->filters['gender']
                );
            });
        }

        if (! empty($this->filters['qualification'])) {
            $query->whereHas('candidate', function ($candidateQuery) {
                $candidateQuery->where(
                    'qualification',
                    $this->filters['qualification']
                );
            });
        }

        if (! empty($this->filters['election_id'])) {
            $query->where(
                'election_id',
                (int) $this->filters['election_id']
            );
        }

        $nominations = $query
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Spreadsheet
        |--------------------------------------------------------------------------
        */

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Final Publication');

        /*
        |--------------------------------------------------------------------------
        | Title
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:I1');

        $sheet->setCellValue(
            'A1',
            'FINAL APPROVED CANDIDATE LIST - FOR PUBLICATION'
        );

        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);

        /*
        |--------------------------------------------------------------------------
        | Generated date
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:I2');

        $sheet->setCellValue(
            'A2',
            'Generated: ' . now()->format('d M Y H:i')
        );

        /*
        |--------------------------------------------------------------------------
        | Headers
        |--------------------------------------------------------------------------
        */

        $headers = [
            'S/N',
            'Candidate Name',
            'Political Party',
            'Position',
            'LGA',
            'Gender',
            'Qualification',
            'Election',
            'Status',
        ];

        $headerRow = 4;

        foreach ($headers as $column => $header) {
            $sheet->setCellValue(
                $this->columnLetter($column + 1) . $headerRow,
                $header
            );
        }

        $sheet
            ->getStyle("A{$headerRow}:I{$headerRow}")
            ->getFont()
            ->setBold(true);

        /*
        |--------------------------------------------------------------------------
        | Candidate rows
        |--------------------------------------------------------------------------
        */

        $row = $headerRow + 1;
        $serial = 1;

        foreach ($nominations as $nomination) {

            $candidate = $nomination->candidate;

            $sheet->setCellValue("A{$row}", $serial++);

            $sheet->setCellValue(
                "B{$row}",
                $candidate?->full_name ?? 'N/A'
            );

            $sheet->setCellValue(
                "C{$row}",
                $nomination->politicalParty?->name ?? 'N/A'
            );

            $sheet->setCellValue(
                "D{$row}",
                $nomination->position?->name ?? 'N/A'
            );

            $sheet->setCellValue(
                "E{$row}",
                $nomination->lga?->name ?? 'N/A'
            );

            $sheet->setCellValue(
                "F{$row}",
                $candidate?->gender ?? 'N/A'
            );

            $sheet->setCellValue(
                "G{$row}",
                $candidate?->qualification ?? 'N/A'
            );

            $sheet->setCellValue(
                "H{$row}",
                $nomination->election?->name ?? 'N/A'
            );

            $sheet->setCellValue(
                "I{$row}",
                'Approved'
            );

            $row++;
        }

        /*
        |--------------------------------------------------------------------------
        | Formatting
        |--------------------------------------------------------------------------
        */

        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->freezePane('A5');

        /*
        |--------------------------------------------------------------------------
        | Chairman certification
        |--------------------------------------------------------------------------
        */

        $row += 2;

        $sheet->mergeCells("A{$row}:I{$row}");

        $sheet->setCellValue(
            "A{$row}",
            'CHAIRMAN CERTIFICATION / APPROVAL'
        );

        $sheet->getStyle("A{$row}")
            ->getFont()
            ->setBold(true);

        $row += 2;

        $sheet->mergeCells("A{$row}:I{$row}");

        $sheet->setCellValue(
            "A{$row}",
            'Chairman, OGSIEC'
        );

        $row += 2;

        $sheet->mergeCells("A{$row}:D{$row}");

        $sheet->setCellValue(
            "A{$row}",
            'Signature: ______________________________'
        );

        $sheet->mergeCells("F{$row}:I{$row}");

        $sheet->setCellValue(
            "F{$row}",
            'Date: ____________________'
        );

        /*
        |--------------------------------------------------------------------------
        | Output
        |--------------------------------------------------------------------------
        */

        $filename =
            'final-approved-candidates-' .
            now()->format('Y-m-d-His') .
            '.xlsx';

        $writer = new Xlsx($spreadsheet);

$directory = storage_path('app/temp');

if (! is_dir($directory)) {
    mkdir($directory, 0775, true);
}

$tempFile = $directory . DIRECTORY_SEPARATOR . $filename;

$writer->save($tempFile);

return response()->download(
    $tempFile,
    $filename,
    [
        'Content-Type' =>
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]
)->deleteFileAfterSend(true);
}
    protected function columnLetter(int $number): string
    {
        $letter = '';

        while ($number > 0) {
            $remainder = ($number - 1) % 26;

            $letter =
                chr(65 + $remainder) .
                $letter;

            $number =
                intdiv($number - 1, 26);
        }

        return $letter;
    }
}
