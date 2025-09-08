<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: monospace;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 80mm;
            padding: 5px;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 24px;
        }

        th,
        td {
            text-align: center;
            padding: 2px 0;
            border-bottom: 1px dashed #000;
            font-size: 24px;
        }

        .text-right {
            text-align: center;
        }

        .total {
            font-weight: bold;
            font-size: 24px;
        }

        .company-name {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 24px;
        }

        .company-address {
            text-align: start;
            margin-top: 5px;
            font-size: 20px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printable-area,
            #printable-area * {
                visibility: visible;
            }

            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</head>

<body>
    <div id="printable-area" class="receipt">
        <!-- Company Name -->
        <div class="company-name">VIP Cash Receipt</div>

        <!-- Current Date/Time and Receipt Info -->
        <p>Date: {{ $currentDateTime->format('Y-m-d') }} Time: {{ $currentDateTime->format('h:i:s A') }}</p>
        <p style="margin-top: -10px;">Receipt #: {{ $dailyNumber }} <small>({{ ucfirst($member->type) }})</small></p>

        <!-- Member Info -->
        @if ($lockNumber && $lockNumber !== 'N/A')
            <p style="margin-top: -10px;">Lock #: {{ $lockNumber }}</p>
        @endif
        @if (!empty($planInfo))
            <p style="margin-top: -10px;">{{ $planInfo }}</p>
            <p style="margin-top: -10px;">Customer Name: {{ ucfirst($member->name) }}</p>
        @endif
        <p style="margin-top: -10px;">Cashier Name: {{ ucfirst($cashierName) }}</p>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Item&nbsp;</th>
                    <th class="text-right">Qty&nbsp;</th>
                    <th class="text-right"> Price&nbsp;</th>
                    {{-- @if ($member->type == 'daily') --}}
                    <th class="text-right">Total&nbsp;</th>
                    {{-- @endif --}}
                </tr>
            </thead>
            <tbody>
                @if ($member->type == 'daily')
                    <tr>
                        <td>Pool</td>
                        <td class="text-right">{{ $dailyQuantity }}</td>
                        <td class="text-right">{{ $dailyPrice }}</td>
                        <td class="text-right">{{ $dailyQuantity * $dailyPrice }}</td>
                    </tr>
                @endif
                @foreach ($expenses as $index => $expense)
                    <tr>
                        <td>{{ $expense->service_name ?? ($expense->service->name ?? ($expense->custom_name ?? 'Total Food')) }}
                        </td>
                        <td class="text-right">{{ $expense->quantity ?? 1 }}</td>
                        <td class="text-right">
                            {{ $expense->price }}
                        </td>
                        @if ($member->type == 'daily')
                            @if ($expense->price == 0)
                                <td class="text-right">{{ $dailyPrice }}</td>
                            @else
                                <td class="text-right">{{ $expense->quantity * $expense->price }}</td>
                            @endif
                        @else
                            <td class="text-right">
                                {{ $expense->price * $expense->quantity }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total -->
        @if ($totalAmountWithPrice > 0)
            <p class="total text-right">Total: {{ number_format($totalAmountWithPrice, 0) }}</p>
        @else
            <p class="total text-right">Total: {{ number_format($totalAmount, 0) }}</p>
        @endif

        <!-- Company Address -->
        <div class="company-address">
            Address: Kabul, Karte 4, Between 3th Police Station & Pole Surkh Square <br>
            Email: viplounge.kbl@gmail.com <br />
            Phone: +93(0) 728 779 779
        </div>
    </div>

    <script>
        // Automatically trigger print
        window.addEventListener('DOMContentLoaded', function() {
            window.print();
        });
    </script>
</body>

</html>
