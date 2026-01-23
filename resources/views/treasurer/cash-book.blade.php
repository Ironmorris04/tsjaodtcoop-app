@extends('layouts.app')

@section('title', 'Cash Book')

@section('page-title', 'Cash Book')

@push('styles')
<style>
    .book-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .book-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e0e0e0;
    }

    .book-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
    }

    .cashbook-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 12px;
    }

    .cashbook-table thead {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .cashbook-table th {
        padding: 14px 8px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .cashbook-table th.group-header {
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        border-bottom: none;
    }

    .cashbook-table th.sub-header {
        text-align: center;
        font-size: 10px;
    }

    .cashbook-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s;
    }

    .cashbook-table tbody tr:hover {
        background-color: #fef3c7;
    }

    .cashbook-table td {
        padding: 12px 8px;
        color: #495057;
        vertical-align: middle;
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

    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    /* Year Filter */
    .filter-section {
        background: #fef9e7;
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
        background: linear-gradient(135deg, #f59e0b, #d97706);
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
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .period-display {
        background: linear-gradient(135deg, #f59e0b, #d97706);
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
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .search-label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        white-space: nowrap;
    }

    .btn-download-cashbook {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-download-cashbook:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(17, 153, 142, 0.4);
        color: white;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="book-container">
    <div class="book-header">
        <h1 class="book-title">
            <i class="fas fa-book-open"></i> Cash Book
        </h1>
        @if(auth()->user()->role !== 'operator')
        <button onclick="downloadCashBook()" class="btn-download-cashbook">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
        @endif
    </div>

    <!-- Year Filter and Search -->
    @php
        $routeName = auth()->user()->role === 'admin' ? 'admin.cash-book' :
                     (auth()->user()->role === 'operator' ? 'operator.cash-book' : 'treasurer.cash-book');
    @endphp

    <form method="GET" action="{{ route($routeName) }}" class="filter-section">
        <label for="year">Year:</label>
        <select name="year" id="year" class="filter-select">
            @for($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>

        <label for="sort">Sort:</label>
        <select name="sort" id="sort" class="filter-select">
            <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Newest First</option>
            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest First</option>
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
            <a href="{{ route($routeName, ['year' => $year, 'sort' => request('sort', 'desc')]) }}" class="btn-filter" style="background: #ef4444; text-decoration: none;">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif

        <div class="period-display">
            <i class="fas fa-calendar-alt"></i>
            Year {{ $year }} - Annual Totals
        </div>
    </form>

    <table class="cashbook-table">
        <thead>
            <tr>
                <th rowspan="2">Date</th>
                @if(auth()->user()->role !== 'operator')
                <th rowspan="2">Name</th>
                @endif
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
                    @if(auth()->user()->role !== 'operator')
                    <td class="operator-name">{{ $transaction->operator ? $transaction->operator->full_name : 'Coop' }}</td>
                    @endif
                    <td>{{ $transaction->operator ? $transaction->formatted_particular : $transaction->particular }}</td>
                    <td>
                        <span style="font-family: monospace; background: {{ $transaction->type === 'receipt' ? '#f0fdf4' : '#fef2f2' }}; padding: 3px 6px; border-radius: 3px; color: {{ $transaction->type === 'receipt' ? '#059669' : '#dc2626' }}; font-size: 10px;">
                            {{ $transaction->or_number ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="amount-in">
                        @if($transaction->type === 'receipt')
                            ₱{{ number_format($transaction->amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount-out">
                        @if($transaction->type === 'disbursement')
                            ₱{{ number_format($transaction->amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="balance-cell">₱{{ number_format($cashOnHandBalance, 2) }}</td>
                    <td class="amount-in">-</td>
                    <td class="amount-out">-</td>
                    <td class="balance-cell">₱{{ number_format($cashInBankBalance, 2) }}</td>
                    <td style="font-size: 10px; color: #95a5a6;">{{ $transaction->month }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'operator' ? '10' : '11' }}" style="text-align: center; padding: 40px; color: #95a5a6;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        No transactions recorded yet
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($transactions->count() > 0)
        <tfoot style="background: #fef3c7; font-weight: 700;">
            <tr>
                <td colspan="{{ auth()->user()->role === 'operator' ? '3' : '4' }}" style="padding: 12px 6px; text-align: right;">TOTAL:</td>
                <td class="amount-in" style="padding: 12px 6px;">₱{{ number_format($totalIn, 2) }}</td>
                <td class="amount-out" style="padding: 12px 6px;">₱{{ number_format($totalOut, 2) }}</td>
                <td class="balance-cell" style="padding: 12px 6px;">₱{{ number_format($cashOnHandBalance, 2) }}</td>
                <td class="amount-in" style="padding: 12px 6px;">-</td>
                <td class="amount-out" style="padding: 12px 6px;">-</td>
                <td class="balance-cell" style="padding: 12px 6px;">₱{{ number_format($cashInBankBalance, 2) }}</td>
                <td style="padding: 12px 6px;"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    @if($transactions->hasPages())
        <div class="pagination-container">
            {{ $transactions->appends([
                'search' => request('search'),
                'year' => $year,
                'sort' => request('sort', 'desc')
            ])->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function downloadCashBook() {
        const year = document.getElementById('year').value;
        const search = document.getElementById('searchInput').value;
        const sort = document.getElementById('sort').value;

        @php
            $downloadRouteName = auth()->user()->role === 'admin' ? 'admin.cash-book.download-pdf' :
                                (auth()->user()->role === 'operator' ? 'operator.cash-book.download-pdf' : 'treasurer.cash-book.download-pdf');
        @endphp

        let url = '{{ route($downloadRouteName) }}';
        url += `?year=${year}&sort=${sort}`;
        if (search) {
            url += `&search=${encodeURIComponent(search)}`;
        }

        window.open(url, '_blank');
    }
</script>
@endpush
