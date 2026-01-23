@extends('layouts.app')

@section('title', 'Cash Disbursement Book')

@section('page-title', 'Cash Disbursement Book')

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
        background: #fef2f2;
        color: #dc2626;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .disbursements-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 12px;
    }

    .disbursements-table thead {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .disbursements-table th {
        padding: 14px 8px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .disbursements-table tbody tr {
        border-bottom: 1px solid #e9ecef;
    }

    .disbursements-table tbody tr:hover {
        background-color: #fef2f2;
    }

    .disbursements-table td {
        padding: 12px 8px;
        color: #495057;
        vertical-align: middle;
    }

    .amount-cell {
        text-align: right;
        font-weight: 600;
        color: #dc2626;
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

    /* Year Filter */
    .filter-section {
        background: #fef2f2;
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
        background: linear-gradient(135deg, #ef4444, #dc2626);
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
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .period-display {
        background: linear-gradient(135deg, #ef4444, #dc2626);
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
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
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
            <i class="fas fa-file-invoice-dollar"></i> Cash Disbursement Book
        </h1>
        <div style="display: flex; align-items: center; gap: 15px;">
            @if(auth()->user()->role === 'admin')
            <button onclick="downloadCashDisbursementBook()" class="btn-download" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
            @endif
            <div class="read-only-badge">
                <i class="fas fa-lock"></i> Read Only
            </div>
        </div>
    </div>

    <!-- Year Filter and Search -->
    @php
        $routeName = auth()->user()->role === 'admin' ? 'admin.cash-disbursement-book' :
                     (auth()->user()->role === 'operator' ? 'operator.cash-disbursement-book' : 'treasurer.cash-disbursement-book');
    @endphp

    <form method="GET" action="{{ route($routeName) }}" class="filter-section">
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

        <!-- SORT DROPDOWN -->
        <label for="sort">Sort:</label>
        <select name="sort" id="sort" class="filter-select">
            <option value="desc" {{ request('sort', 'desc') === 'desc' ? 'selected' : '' }}>
                Newest First
            </option>
            <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>
                Oldest First
            </option>
        </select>

        <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i> Filter
        </button>

        @if(request('search'))
            <a href="{{ route($routeName, ['year' => $year]) }}" class="btn-filter" style="background: #ef4444; text-decoration: none;">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif

        <div class="period-display">
            <i class="fas fa-calendar-alt"></i>
            Year {{ $year }} - Annual Totals
        </div>
    </form>

    <table class="disbursements-table">
        <thead>
            <tr>
                <th>Date</th>
                @if(auth()->user()->role !== 'operator')
                <th>Name</th>
                @endif
                <th>Particulars</th>
                <th>Ref#</th>
                <th style="text-align: right;">Cash OUT</th>
                <th style="text-align: right;">Cash Advance</th>
                <th style="text-align: right;">Due to Reg. Agencies</th>
                <th style="text-align: right;">Misc Expense</th>
                <th style="text-align: right;">CBU Withdrawal</th>
                <th style="text-align: right;">Notarial Fees</th>
                <th style="text-align: right;">License & Taxes</th>
                <th style="text-align: right;">Cash in Bank</th>
                <th style="text-align: right;">Penalties</th>
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
            @forelse($disbursements as $disbursement)
                @php
                    $totals['cash_out'] += $disbursement->amount;

                    // Categorize by category field
                    switch($disbursement->category) {
                        case 'cash_advance':
                            $totals['cash_advance'] += $disbursement->amount;
                            break;
                        case 'due_to_regulatory':
                            $totals['due_agencies'] += $disbursement->amount;
                            break;
                        case 'miscellaneous_expense':
                            $totals['misc_expense'] += $disbursement->amount;
                            break;
                        case 'cbu_withdrawal':
                            $totals['cbu_withdrawal'] += $disbursement->amount;
                            break;
                        case 'notarial_fees':
                            $totals['notarial_fees'] += $disbursement->amount;
                            break;
                        case 'license_and_taxes':
                            $totals['license_taxes'] += $disbursement->amount;
                            break;
                        case 'cash_in_bank':
                            $totals['cash_in_bank'] += $disbursement->amount;
                            break;
                        case 'penalties_and_charges':
                            $totals['penalties'] += $disbursement->amount;
                            break;
                    }
                @endphp
                <tr>
                    <td style="white-space: nowrap;">{{ $disbursement->date->format('M d, Y') }}</td>
                    @if(auth()->user()->role !== 'operator')
                    <td class="operator-name">{{ $disbursement->operator ? $disbursement->operator->full_name : 'Coop' }}</td>
                    @endif
                    <td>{{ $disbursement->particular }}</td>
                    <td>
                        <span style="font-family: monospace; background: #fef2f2; padding: 3px 6px; border-radius: 3px; color: #dc2626; font-size: 10px;">
                            {{ $disbursement->or_number ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="amount-cell">₱{{ number_format($disbursement->amount, 2) }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'cash_advance' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'due_to_regulatory' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'miscellaneous_expense' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'cbu_withdrawal' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'notarial_fees' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'license_and_taxes' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'cash_in_bank' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                    <td class="amount-cell">{{ $disbursement->category === 'penalties_and_charges' ? '₱' . number_format($disbursement->amount, 2) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'operator' ? '12' : '13' }}" style="text-align: center; padding: 40px; color: #95a5a6;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        No disbursements recorded yet
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($disbursements->count() > 0)
        <tfoot style="background: #fef2f2; font-weight: 700; color: #dc2626;">
            <tr>
                <td colspan="{{ auth()->user()->role === 'operator' ? '3' : '4' }}" style="padding: 12px 6px; text-align: right;">TOTAL:</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['cash_out'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['cash_advance'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['due_agencies'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['misc_expense'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['cbu_withdrawal'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['notarial_fees'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['license_taxes'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['cash_in_bank'], 2) }}</td>
                <td class="amount-cell" style="padding: 12px 6px;">₱{{ number_format($totals['penalties'], 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    @if($disbursements->hasPages())
        <div class="pagination-container">
            {{ $disbursements->appends(['search' => request('search'), 'year' => $year, 'sort' => request('sort', 'desc')])->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function downloadCashDisbursementBook() {
        const year = document.getElementById('year').value;
        const sort = document.getElementById('sort').value;

        let url = '{{ route("admin.cash-disbursement-book.download-pdf") }}';
        url += `?year=${year}&sort=${sort}`;

        // Open download in new window
        window.open(url, '_blank');
    }
</script>
@endpush
