<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Disbursement Book - TSJAODTC</title>
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
            background-color: #ef4444;
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
            color: #ef4444;
            font-weight: 600;
            text-align: right;
        }
        .total-row {
            background-color: #fee2e2;
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
        <h2>Cash Disbursement Book</h2>
        <p class="period-info">Year {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 7%;">Date</th>
                <th style="width: 10%;">Name</th>
                <th style="width: 12%;">Particulars</th>
                <th style="width: 7%;">Ref#</th>
                <th style="width: 8%;">Cash OUT</th>
                <th style="width: 8%;">Cash Advance</th>
                <th style="width: 9%;">Due to Reg. Agencies</th>
                <th style="width: 8%;">Misc Expense</th>
                <th style="width: 8%;">CBU Withdrawal</th>
                <th style="width: 8%;">Notarial Fees</th>
                <th style="width: 8%;">License & Taxes</th>
                <th style="width: 7%;">Cash in Bank</th>
                <th style="width: 7%;">Penalties</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totals = [
                    'cash_out' => 0,
                    'cash_advance' => 0,
                    'due_agencies' => 0,
                    'misc_expense' => 0,
                    'cbu_withdrawal' => 0,
                    'notarial_fees' => 0,
                    'license_taxes' => 0,
                    'cash_in_bank' => 0,
                    'penalties' => 0
                ];
            @endphp
            @forelse($transactions as $transaction)
                @php
                    $totals['cash_out'] += $transaction->amount;

                    // Categorize by category field
                    switch($transaction->category) {
                        case 'cash_advance':
                            $totals['cash_advance'] += $transaction->amount;
                            break;
                        case 'due_to_regulatory':
                            $totals['due_agencies'] += $transaction->amount;
                            break;
                        case 'miscellaneous_expense':
                            $totals['misc_expense'] += $transaction->amount;
                            break;
                        case 'cbu_withdrawal':
                            $totals['cbu_withdrawal'] += $transaction->amount;
                            break;
                        case 'notarial_fees':
                            $totals['notarial_fees'] += $transaction->amount;
                            break;
                        case 'license_and_taxes':
                            $totals['license_taxes'] += $transaction->amount;
                            break;
                        case 'cash_in_bank':
                            $totals['cash_in_bank'] += $transaction->amount;
                            break;
                        case 'penalties_and_charges':
                            $totals['penalties'] += $transaction->amount;
                            break;
                    }
                @endphp
                <tr>
                    <td class="center">{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
                    <td>{{ $transaction->operator ? $transaction->operator->full_name : 'Coop' }}</td>
                    <td>{{ $transaction->particular }}</td>
                    <td class="center">{{ $transaction->or_number ?? 'N/A' }}</td>
                    <td class="amount">Php {{ number_format($transaction->amount, 2) }}</td>
                    <td class="amount">{{ $transaction->category === 'cash_advance' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->category === 'due_to_regulatory' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->category === 'miscellaneous_expense' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->category === 'cbu_withdrawal' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->category === 'notarial_fees' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->category === 'license_and_taxes' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->category === 'cash_in_bank' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                    <td class="amount">{{ $transaction->category === 'penalties_and_charges' ? 'Php ' . number_format($transaction->amount, 2) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="no-data">
                        No disbursement transactions found for this period
                    </td>
                </tr>
            @endforelse

            @if($transactions->count() > 0)
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;">TOTAL:</td>
                    <td class="amount">Php {{ number_format($totals['cash_out'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['cash_advance'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['due_agencies'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['misc_expense'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['cbu_withdrawal'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['notarial_fees'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['license_taxes'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['cash_in_bank'], 2) }}</td>
                    <td class="amount">Php {{ number_format($totals['penalties'], 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y h:i A') }} | TSJAODTC Cash Disbursement Book
    </div>
</body>
</html>