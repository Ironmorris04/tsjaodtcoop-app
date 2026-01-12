@extends('layouts.app')

@section('title', 'Cash Receipts Journal')

@section('page-title', 'Cash Receipts Journal')

@push('styles')
<style>
    .journal-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .journal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e0e0e0;
    }

    .journal-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
    }

    .read-only-badge {
        background: #f0fdf4;
        color: #059669;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .receipts-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 13px;
    }

    .receipts-table thead {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .receipts-table th {
        padding: 14px 10px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .receipts-table tbody tr {
        border-bottom: 1px solid #e9ecef;
    }

    .receipts-table tbody tr:hover {
        background-color: #f0fdf4;
    }

    .receipts-table td {
        padding: 12px 10px;
        color: #495057;
        vertical-align: middle;
    }

    .amount-cell {
        text-align: right;
        font-weight: 600;
        color: #059669;
    }

    .operator-name {
        font-weight: 600;
        color: #2c3e50;
    }

    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    /* Month/Year Filter */
    .filter-section {
        background: #f0fdf4;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-section label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        min-width: 150px;
    }

    .btn-filter {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .period-display {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        margin-left: auto;
    }

    .search-section {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-input {
        flex: 1;
        padding: 10px 15px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .search-label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="journal-container">
    <div class="journal-header">
        <h1 class="journal-title">
            <i class="fas fa-receipt"></i> Cash Receipts Journal
        </h1>
        <div style="display: flex; align-items: center; gap: 15px;">
            @if(auth()->user()->role === 'admin')
            <button onclick="downloadCashReceiptsJournal()" class="btn-download" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
            @endif
            <div class="read-only-badge">
                <i class="fas fa-lock"></i> Read Only
            </div>
        </div>
    </div>

    <!-- Month/Year Filter and Search -->
    @php
        $routeName = auth()->user()->role === 'admin' ? 'admin.cash-receipts-journal' :
                     (auth()->user()->role === 'operator' ? 'operator.cash-receipts-journal' : 'treasurer.cash-receipts-journal');
    @endphp
    <form method="GET" action="{{ route($routeName) }}" class="filter-section">
        <label for="month">Month:</label>
        <select name="month" id="month" class="filter-select">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                </option>
            @endfor
        </select>

        <label for="year">Year:</label>
        <select name="year" id="year" class="filter-select">
            @for($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>

        <label for="searchInput">Search:</label>
        <input
            type="text"
            id="searchInput"
            name="search"
            class="filter-select"
            placeholder="Reference/OR Number..."
            value="{{ request('search') }}"
            style="min-width: 200px;"
        >

        <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i> Filter
        </button>

        @if(request('search'))
            <a href="{{ route($routeName, ['month' => $month, 'year' => $year]) }}" class="btn-filter" style="background: #ef4444; text-decoration: none;">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif

        <div class="period-display">
            <i class="fas fa-calendar-alt"></i>
            {{ \Carbon\Carbon::create(null, $month)->format('F') }} {{ $year }}
        </div>
    </form>

    <table class="receipts-table">
        <thead>
            <tr>
                <th>Date</th>
                @if(auth()->user()->role !== 'operator')
                <th>Name</th>
                @endif
                <th>Particulars</th>
                <th>Ref#</th>
                <th style="text-align: right;">Cash IN</th>
                <th style="text-align: right;">CBU</th>
                <th style="text-align: right;">Monthly Dues</th>
                <th style="text-align: right;">Office Rental</th>
                <th style="text-align: right;">Contribution</th>
                <th style="text-align: right;">Membership Fee</th>
                <th style="text-align: right;">Fine</th>
                <th style="text-align: right;">Legal Fee</th>
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
            @forelse($receipts as $receipt)
                @php
                    $totals['cash_in'] += $receipt->amount;

                    // Categorize amount by particular type
                    switch($receipt->particular) {
                        case 'subscription_capital':
                            $totals['cbu'] += $receipt->amount;
                            break;
                        case 'monthly_dues':
                            $totals['monthly_dues'] += $receipt->amount;
                            break;
                        case 'office_rental':
                            $totals['office_rental'] += $receipt->amount;
                            break;
                        case 'management_fee':
                            $totals['contribution'] += $receipt->amount;
                            break;
                        case 'membership_fee':
                            $totals['membership_fee'] += $receipt->amount;
                            break;
                    }
                @endphp
                <tr>
                    <td style="white-space: nowrap;">{{ $receipt->date->format('M d, Y') }}</td>
                    @if(auth()->user()->role !== 'operator')
                    <td class="operator-name">{{ $receipt->operator ? $receipt->operator->full_name : 'Treasurer' }}</td>
                    @endif
                    <td>{{ $receipt->operator ? $receipt->formatted_particular : $receipt->particular }}</td>
                    <td>
                        <span style="font-family: monospace; background: #f0fdf4; padding: 3px 6px; border-radius: 3px; color: #059669; font-size: 11px;">
                            {{ $receipt->or_number ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="amount-cell">₱{{ number_format($receipt->amount, 2) }}</td>
                    <td class="amount-cell">
                        {{ $receipt->particular === 'subscription_capital' ? '₱' . number_format($receipt->amount, 2) : '-' }}
                    </td>
                    <td class="amount-cell">
                        {{ $receipt->particular === 'monthly_dues' ? '₱' . number_format($receipt->amount, 2) : '-' }}
                    </td>
                    <td class="amount-cell">
                        {{ $receipt->particular === 'office_rental' ? '₱' . number_format($receipt->amount, 2) : '-' }}
                    </td>
                    <td class="amount-cell">
                        {{ $receipt->particular === 'management_fee' ? '₱' . number_format($receipt->amount, 2) : '-' }}
                    </td>
                    <td class="amount-cell">
                        {{ $receipt->particular === 'membership_fee' ? '₱' . number_format($receipt->amount, 2) : '-' }}
                    </td>
                    <td class="amount-cell">-</td>
                    <td class="amount-cell">-</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'operator' ? '11' : '12' }}" style="text-align: center; padding: 40px; color: #95a5a6;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        No receipts recorded yet
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($receipts->count() > 0)
        <tfoot style="background: #f0fdf4; font-weight: 700; color: #059669;">
            <tr>
                <td colspan="{{ auth()->user()->role === 'operator' ? '3' : '4' }}" style="padding: 12px 8px; text-align: right;">TOTAL:</td>
                <td class="amount-cell" style="padding: 12px 8px;">₱{{ number_format($totals['cash_in'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 8px;">₱{{ number_format($totals['cbu'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 8px;">₱{{ number_format($totals['monthly_dues'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 8px;">₱{{ number_format($totals['office_rental'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 8px;">₱{{ number_format($totals['contribution'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 8px;">₱{{ number_format($totals['membership_fee'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 8px;">-</td>
                <td class="amount-cell" style="padding: 12px 8px;">-</td>
            </tr>
        </tfoot>
        @endif
    </table>

    @if($receipts->hasPages())
        <div class="pagination-container">
            {{ $receipts->appends(['search' => request('search'), 'month' => $month, 'year' => $year])->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function downloadCashReceiptsJournal() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        let url = '{{ route("admin.cash-receipts-journal.download-pdf") }}';
        url += `?month=${month}&year=${year}`;

        // Open download in new window
        window.open(url, '_blank');
    }
</script>
@endpush
