<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Final Approved Candidate List</title>

    <style>
        @page {
            margin: 35px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #111827;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 9px;
            color: #4b5563;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #e5e7eb;
            font-weight: bold;
            text-align: left;
        }

        th,
        td {
            border: 1px solid #9ca3af;
            padding: 5px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .certification {
            margin-top: 35px;
            border: 1px solid #9ca3af;
            padding: 15px;
        }

        .certification-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 20px;
        }

        .signature-table {
            border: none;
            width: 100%;
        }

        .signature-table td {
            border: none;
            padding: 5px 0;
        }

        .signature-line {
            margin-top: 25px;
            border-bottom: 1px solid #111827;
            width: 250px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <h1>
        FINAL APPROVED CANDIDATE LIST
    </h1>

    <div class="subtitle">
        FOR FINAL PUBLICATION
        <br>
        Generated: {{ now()->format('d M Y H:i') }}
    </div>

    <table>

        <thead>
            <tr>
                <th class="center">S/N</th>
                <th>Candidate Name</th>
                <th>Political Party</th>
                <th>Position</th>
                <th>LGA</th>
                <th>Gender</th>
                <th>Qualification</th>
                <th>Election</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @forelse($nominations as $index => $nomination)

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $nomination->candidate?->full_name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $nomination->politicalParty?->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $nomination->position?->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $nomination->lga?->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $nomination->candidate?->gender ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $nomination->candidate?->qualification ?? 'N/A' }}

                        @if($nomination->candidate?->qualification_details)
                            <br>
                            <small>
                                {{ $nomination->candidate->qualification_details }}
                            </small>
                        @endif
                    </td>

                    <td>
                        {{ $nomination->election?->name ?? 'N/A' }}
                    </td>

                    <td>
                        APPROVED
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="9" class="center">
                        No approved candidates match the selected filters.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- Chairman Certification --}}

    <div class="certification">

        <div class="certification-title">
            CHAIRMAN CERTIFICATION / APPROVAL
        </div>

        <p>
            I certify that the candidates listed above have been approved
            for final publication, subject to any applicable court order
            or other lawful directive received before publication.
        </p>

        <table class="signature-table">

            <tr>
                <td width="55%">
                    Chairman, OGSIEC

                    <div class="signature-line"></div>

                    Signature
                </td>

                <td width="45%">
                    Date

                    <div class="signature-line"></div>
                </td>
            </tr>

        </table>

    </div>


    <div class="footer">
        Official Final Publication Candidate List
        |
        Generated {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>
