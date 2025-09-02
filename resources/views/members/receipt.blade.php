<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }

        h1,
        h2 {
            text-align: center;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px dashed black;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <center>
        <h3>Cash Receipt</h3>
        <h5 style="margin-top: -10px">{{ config('app.name') }}</h5>
    </center>

    {{-- <p>Date:
        @if ($expenses->isNotEmpty())
            {{ \Carbon\Carbon::parse($expenses->first()->service_date)->format('Y/m/d') }}
        @else
            {{ now()->format('Y/m/d') }}
        @endif
    </p> --}}

    <p>Date: {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}</p>
    <p>Time: {{ \Carbon\Carbon::parse($time)->format('h:i:s A') }}</p>

    <p style="margin-top: -5px;">Receipt #: {{ $receiptNumber }}</p>
    <p style="margin-top: -5px;">Member: {{ $member->name }}</p>
    <p style="margin-top: -5px;">Lock Number: {{ $lockNumber ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($expenses as $index => $e)
                @php
                    $total = $e->quantity * $e->service->price;
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $e->service->name }}</td>
                    <td>{{ $e->quantity }}</td>
                    <td>{{ number_format($e->service->price, 2) }}</td>
                    <td>{{ number_format($total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total: AFN {{ number_format($grandTotal, 2) }}</p>

    <div class="footer">
        <p>🏠 Kabul, Karte-4, Between 3th police Station & Pule Surkh Square</p>
        <p>📞 +93 700 123 456</p>
        <p>Thank you for your visit!</p>
    </div>

</body>

</html>
