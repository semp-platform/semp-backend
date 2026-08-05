<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:14px;
            color:#222;
        }

        h1{
            margin-bottom:0;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:25px;
        }

        td{
            padding:10px;
            border:1px solid #ddd;
        }

        .label{
            width:220px;
            font-weight:bold;
            background:#f8fafc;
        }

    </style>

</head>

<body>

<h1>OGSIEC Payment Receipt</h1>

<p>
Receipt Number:
<strong>{{ $payment->receipt_number }}</strong>
</p>

<table>

<tr>
<td class="label">Payment Reference</td>
<td>{{ $payment->payment_reference }}</td>
</tr>

<tr>
<td class="label">Political Party</td>
<td>{{ $payment->politicalParty->name }}</td>
</tr>

<tr>
<td class="label">Election</td>
<td>{{ $payment->batch->election->name }}</td>
</tr>

<tr>
<td class="label">Batch Number</td>
<td>{{ $payment->batch->batch_number }}</td>
</tr>

<tr>
<td class="label">Amount</td>
<td>₦{{ number_format($payment->amount,2) }}</td>
</tr>

<tr>
<td class="label">Confirmed By</td>
<td>{{ optional($payment->confirmedBy)->name }}</td>
</tr>

<tr>
<td class="label">Confirmed At</td>
<td>{{ optional($payment->confirmed_at)->format('d M Y H:i') }}</td>
</tr>

</table>

</body>
</html>
