<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Treasurer's Book - TSJAODTC</title>
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
        .summary-row {
            background-color: #f3f4f6;
            font-weight: 700;
        }
        .total-row {
            background-color: #dbeafe;
            font-weight: 900;
            font-size: 9pt;
        }
        .center {
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TSJAODTC</h1>
        <h2>Cash Treasurer's Book</h2>
        <p class="period-info">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Date</th>
                <th style="width: 12%;">OR Number</th>
                <th style="width: 20%;">Operator</th>
                <th style="width: 30%;">Particulars</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 10%;">Amount In</th>
                <th style="width: 10%;">Amount Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td class="center">{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
                    <td class="center">{{ $transaction->or_number ?? 'N/A' }}</td>
                    <td>{{ $transaction->operator ? $transaction->operator->full_name : 'Coop' }}</td>
                    <td>{{ $transaction->operator ? $transaction->formatted_particular : $transaction->particular }}</td>
                    <td class="center">
                        @if($transaction->type === 'receipt')
                            <span style="color: #10b981; font-weight: 600;">Receipt</span>
                        @else
                            <span style="color: #ef4444; font-weight: 600;">Disbursement</span>
                        @endif
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
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">
                        No transactions found for this period
                    </td>
                </tr>
            @endforelse

            @if($transactions->count() > 0)
                <tr class="total-row">
                    <td colspan="5" style="text-align: right;">TOTAL:</td>
                    <td class="amount-in">Php {{ number_format($totalIn, 2) }}</td>
                    <td class="amount-out">Php {{ number_format($totalOut, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="6" style="text-align: right; font-weight: bold;">NET BALANCE:</td>
                    <td style="text-align: right; font-weight: bold; color: {{ ($totalIn - $totalOut) >= 0 ? '#10b981' : '#ef4444' }};">
                        Php {{ number_format($totalIn - $totalOut, 2) }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y h:i A') }} | TSJAODTC Cash Treasurer's Book
    </div>
</body>
</html>
