<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Book - TSJAODTC</title>
    <style>
        @page {
            margin: 0.5in;
            size: A4 landscape;
        }
        .footer {
            position: fixed;
            bottom: -0.5in;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 8pt;
            line-height: 20px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
        }
        .header h1 {
            font-size: 14pt;
            margin: 0 0 5px 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .header p {
            font-size: 10pt;
            margin: 3px 0;
            font-weight: bold;
        }
        .period-info {
            font-size: 9pt;
            font-weight: normal;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: black;
            font-weight: bold;
            text-align: center;
            padding: 5px 3px;
            font-size: 7pt;
            text-transform: uppercase;
            line-height: 1.2;
        }
        th.group-header {
            font-size: 7.5pt;
            border-bottom: none;
        }
        th.sub-header {
            font-size: 6.5pt;
            padding: 4px 2px;
        }
        td {
            padding: 4px 3px;
            font-size: 7pt;
            vertical-align: middle;
            line-height: 1.2;
            word-wrap: break-word;
        }
        .amount-in {
            color: #10b981;
            font-weight: 600;
            text-align: right;
        }
        .amount-out {
            color: #ef4444;
            font-weight: 600;
            text-align: right;
        }
        .balance-cell {
            color: #3b82f6;
            font-weight: 700;
            text-align: right;
        }
        .operator-name {
            font-weight: 600;
            color: #2c3e50;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        tfoot {
            background: #fef3c7;
            font-weight: 700;
        }
        .or-number {
            font-family: monospace;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 7pt;
        }
        .or-receipt {
            background: #f0fdf4;
            color: #059669;
        }
        .or-disbursement {
            background: #fef2f2;
            color: #dc2626;
        }
        .remarks {
            font-size: 7pt;
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Tacloban San Jose Airport Operators and Drivers</h1>
        <h2>Transport Cooperative (TSJAODTC)</h2>
        <p>CASH BOOK</p>
        <p class="period-info">Year {{ $year }} - Annual Totals</p>
        @if($search)
        <p class="period-info">Filtered by: {{ $search }}</p>
        @endif
        <p style="font-size: 8pt; font-weight: normal;">Generated on {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y - h:i A') }}</p>
    </div>

    <!-- Cash Book Table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">Date</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Particulars</th>
                <th rowspan="2">Ref#</th>
                <th colspan="2" class="group-header">Cash on Hand</th>
                <th rowspan="2">Beg. Balance</th>
                <th colspan="2" class="group-header">Cash in Bank</th>
                <th rowspan="2">Beg. Balance</th>
                <th rowspan="2">Remarks</th>
            </tr>
            <tr>
                <th class="sub-header">IN</th>
                <th class="sub-header">OUT</th>
                <th class="sub-header">IN</th>
                <th class="sub-header">OUT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cashOnHandBalance = 0;
                $cashInBankBalance = 0;
            @endphp
            @forelse($transactions as $transaction)
                @php
                    if ($transaction->type === 'receipt') {
                        $cashOnHandBalance += $transaction->amount;
                    } else {
                        $cashOnHandBalance -= $transaction->amount;
                    }
                @endphp
                <tr>
                    <td style="white-space: nowrap;">{{ $transaction->date->format('M d, Y') }}</td>
                    <td class="operator-name">{{ $transaction->operator ? $transaction->operator->full_name : 'Coop' }}</td>
                    <td>{{ $transaction->operator ? $transaction->formatted_particular : $transaction->particular }}</td>
                    <td class="text-center">
                        <span class="or-number {{ $transaction->type === 'receipt' ? 'or-receipt' : 'or-disbursement' }}">
                            {{ $transaction->or_number ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="amount-in">
                        @if($transaction->type === 'receipt')
                            Php {{ number_format($transaction->amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount-out">
                        @if($transaction->type === 'disbursement')
                            Php {{ number_format($transaction->amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="balance-cell">Php {{ number_format($cashOnHandBalance, 2) }}</td>
                    <td class="amount-in">-</td>
                    <td class="amount-out">-</td>
                    <td class="balance-cell">Php {{ number_format($cashInBankBalance, 2) }}</td>
                    <td class="remarks">{{ $transaction->month }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px; color: #95a5a6; font-style: italic;">
                        No transactions recorded yet
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($transactions) > 0)
        <tfoot>
            <tr>
                <td colspan="4" style="padding: 6px 4px; text-align: right; font-weight: bold;">TOTAL:</td>
                <td class="amount-in" style="padding: 6px 4px;">Php {{ number_format($totalIn, 2) }}</td>
                <td class="amount-out" style="padding: 6px 4px;">Php {{ number_format($totalOut, 2) }}</td>
                <td class="balance-cell" style="padding: 6px 4px;">Php {{ number_format($cashOnHandBalance, 2) }}</td>
                <td class="amount-in" style="padding: 6px 4px;">-</td>
                <td class="amount-out" style="padding: 6px 4px;">-</td>
                <td class="balance-cell" style="padding: 6px 4px;">Php {{ number_format($cashInBankBalance, 2) }}</td>
                <td style="padding: 6px 4px;"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Footer -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Generated on {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y - h:i A') }} | Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("DejaVu Sans", "normal");
            $size = 8;
            $pageWidth = $pdf->get_width();
            $pageHeight = $pdf->get_height();
            $textWidth = $fontMetrics->get_text_width($text, $font, $size);
            $x = ($pageWidth - $textWidth) / 2;
            $y = $pageHeight - 30;
            $pdf->page_text($x, $y, $text, $font, $size, array(0,0,0));
        }
    </script>

</body>
</html>
