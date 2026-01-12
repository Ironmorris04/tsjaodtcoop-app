<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Receipts Journal - TSJAODTC</title>
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
            background-color: #10b981;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 6px 3px;
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
        .amount {
            color: #10b981;
            font-weight: 600;
            text-align: right;
        }
        .total-row {
            background-color: #d1fae5;
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
        <h2>Cash Receipts Journal</h2>
        <p class="period-info">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Date</th>
                <th style="width: 12%;">Name</th>
                <th style="width: 15%;">Particulars</th>
                <th style="width: 8%;">Ref#</th>
                <th style="width: 8%;">Cash IN</th>
                <th style="width: 8%;">CBU</th>
                <th style="width: 8%;">Monthly Dues</th>
                <th style="width: 8%;">Office Rental</th>
                <th style="width: 8%;">Contribution</th>
                <th style="width: 8%;">Membership Fee</th>
                <th style="width: 5%;">Fine</th>
                <th style="width: 6%;">Legal Fee</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totals = [
                    'cash_in' => 0,
                    'cbu' => 0,
                    'monthly_dues' => 0,
                    'office_rental' => 0,
                    'contribution' => 0,
                    'membership_fee' => 0,
                    'fine' => 0,
                    'legal_fee' => 0
                ];
            @endphp
            @forelse($transactions as $transaction)
                @php
                    $totals['cash_in'] += $transaction->amount;

                    // Categorize amount by particular type
                    switch($transaction->particular) {
                        case 'subscription_capital':
                            $totals['cbu'] += $transaction->amount;
                            break;
                        case 'monthly_dues':
                            $totals['monthly_dues'] += $transaction->amount;
                            break;
                        case 'office_rental':
                            $totals['office_rental'] += $transaction->amount;
                            break;
                        case 'management_fee':
                            $totals['contribution'] += $transaction->amount;
                            break;
                        case 'membership_fee':
                            $totals['membership_fee'] += $transaction->amount;
                            break;
                    }
                @endphp
                <tr>
                    <td class="center">{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
                    <td>{{ $transaction->operator ? $transaction->operator->full_name : 'Coop' }}</td>
                    <td>{{ $transaction->operator ? $transaction->formatted_particular : $transaction->particular }}</td>
                    <td class="center">{{ $transaction->or_number ?? 'N/A' }}</td>
                    <td class="amount">Php {{ number_format($transaction->amount, 2) }}</td>
                    <td class="amount">{{ $transaction->particular === 'subscription_capital' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->particular === 'monthly_dues' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->particular === 'office_rental' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->particular === 'management_fee' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->particular === 'membership_fee' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">-</td>
                    <td class="amount">-</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="no-data">
                        No receipt transactions found for this period
                    </td>
                </tr>
            @endforelse

            @if($transactions->count() > 0)
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;">TOTAL:</td>
                    <td class="amount">Php {{ number_format($totals['cash_in'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['cbu'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['monthly_dues'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['office_rental'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['contribution'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['membership_fee'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['fine'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['legal_fee'], 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y h:i A') }} | TSJAODTC Cash Receipts Journal
    </div>
</body>
</html>
