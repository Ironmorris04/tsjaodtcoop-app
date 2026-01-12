@extends('layouts.app')

@section('title', 'Cash Treasurer\'s Book')

@section('page-title', 'Cash Treasurer\'s Book')

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

    .transactions-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 14px;
    }

    .transactions-table thead {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
    }

    .transactions-table th {
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .transactions-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s;
    }

    .transactions-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .transactions-table td {
        padding: 14px 12px;
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

    .btn-add-entry {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-entry:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    /* Month/Year Filter */
    .filter-section {
        background: #f8f9fa;
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
        background: linear-gradient(135deg, #6366f1, #4f46e5);
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
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .period-display {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        margin-left: auto;
    }

    /* Add Entry Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .entry-modal {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease-out;
    }

    .entry-modal-header {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .entry-modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .entry-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .entry-modal-close:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .entry-modal-body {
        padding: 24px;
        overflow-y: auto;
        max-height: calc(90vh - 150px);
    }

    .read-only-badge {
        background: #f0fdf4;
        color: #4f46e5;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #374151;
        font-size: 14px;
    }

    .form-group label span.required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .entry-modal-footer {
        padding: 16px 24px;
        background: #f9fafb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-cancel, .btn-save {
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-cancel {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-cancel:hover {
        background: #f3f4f6;
    }

    .btn-save {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
    }

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="book-container">
    <div class="book-header">
        <h1 class="book-title">
            <i class="fas fa-book"></i> Cash Treasurer's Book
        </h1>

        <div style="display: flex; align-items: center; gap: 15px;">
            {{-- Admin: Read-only --}}
            @if(auth()->user()->role === 'admin')

                {{-- Download only --}}
                <button
                    onclick="downloadCashTreasurersBook()"
                    class="btn-download"
                    style="background: linear-gradient(135deg, #6366f1, #4f46e5);
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 6px;
                        cursor: pointer;
                        font-weight: 600;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s;">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </button>
            @endif

            {{-- Read-only badge --}}
            <div class="read-only-badge">
                <i class="fas fa-lock"></i> Read Only
            </div>

            {{-- Treasurer: Can add entry --}}
            @if(auth()->user()->role === 'treasurer')
                <button class="btn-add-entry" onclick="openAddEntryModal()">
                    <i class="fas fa-plus-circle"></i> Add New Entry
                </button>
            @endif
        </div>
    </div>



    <!-- Month/Year Filter -->
    @php
        $routeName = auth()->user()->role === 'admin' ? 'admin.cash-treasurers-book' :
                     (auth()->user()->role === 'operator' ? 'operator.cash-treasurers-book' : 'treasurer.cash-treasurers-book');
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

        <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Apply Filter
        </button>

        <div class="period-display">
            <i class="fas fa-calendar-alt"></i>
            {{ \Carbon\Carbon::create(null, $month)->format('F') }} {{ $year }}
        </div>
    </form>

    <table class="transactions-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Particulars</th>
                <th>Receipt #</th>
                <th style="text-align: right;">Amount</th>
                <th style="text-align: right;">IN</th>
                <th style="text-align: right;">OUT</th>
                <th style="text-align: right;">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $runningBalance = 0;
            @endphp
            @forelse($transactions as $transaction)
                @php
                    if ($transaction->type === 'receipt') {
                        $runningBalance += $transaction->amount;
                    } else {
                        $runningBalance -= $transaction->amount;
                    }
                @endphp
                <tr>
                    <td>{{ $transaction->date->format('M d, Y') }}</td>
                    <td class="operator-name">{{ $transaction->operator ? $transaction->operator->full_name : 'Coop' }}</td>
                    <td>{{ $transaction->particular }}</td>
                    <td>
                        <span style="font-family: monospace; background: {{ $transaction->type === 'receipt' ? '#f0fdf4' : '#fef2f2' }}; padding: 4px 8px; border-radius: 4px; color: {{ $transaction->type === 'receipt' ? '#059669' : '#dc2626' }};">
                            {{ $transaction->or_number ?? 'N/A' }}
                        </span>
                    </td>
                    <td style="text-align: right; font-weight: 600;">₱{{ number_format($transaction->amount, 2) }}</td>
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
                    <td class="balance-cell">₱{{ number_format($runningBalance, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #95a5a6;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        No transactions recorded yet
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($transactions->count() > 0)
        <tfoot style="background: #f8f9fa; font-weight: 700;">
            <tr>
                <td colspan="5" style="padding: 15px; text-align: right;">TOTAL:</td>
                <td class="amount-in" style="padding: 15px;">₱{{ number_format($totalIn, 2) }}</td>
                <td class="amount-out" style="padding: 15px;">₱{{ number_format($totalOut, 2) }}</td>
                <td class="balance-cell" style="padding: 15px;">₱{{ number_format($runningBalance, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    @if($transactions->hasPages())
        <div class="pagination-container">
            {{ $transactions->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

<!-- Add Entry Modal -->
<div class="modal-overlay" id="addEntryModal">
    <div class="entry-modal">
        <div class="entry-modal-header">
            <h3><i class="fas fa-plus-circle"></i> Add New Transaction Entry</h3>
            <button class="entry-modal-close" onclick="closeAddEntryModal()">&times;</button>
        </div>
        <form id="addEntryForm" onsubmit="submitNewEntry(event)">
            <div class="entry-modal-body">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" class="form-control" readonly value="{{ date('Y-m-d') }}" style="background-color: #f3f4f6; cursor: not-allowed;">
                    <small style="color: #6b7280; font-size: 12px;">Date is automatically set to today</small>
                </div>

                <div class="form-group">
                    <label for="category">Category <span class="required">*</span></label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="cash_advance">Cash Advance</option>
                        <option value="due_to_regulatory">Due to Regulatory</option>
                        <option value="miscellaneous_expense">Miscellaneous Expense</option>
                        <option value="cbu_withdrawal">CBU Withdrawal</option>
                        <option value="notarial_fees">Notarial Fees</option>
                        <option value="license_and_taxes">License and Taxes</option>
                        <option value="cash_in_bank">Cash in Bank</option>
                        <option value="penalties_and_charges">Penalties and Charges</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="particular">Particulars <span class="required">*</span></label>
                    <input type="text" id="particular" name="particular" class="form-control" placeholder="Enter specific details of the transaction" required>
                    <small style="color: #6b7280; font-size: 12px;">Provide specific details about this transaction</small>
                </div>

                <div class="form-group">
                    <label for="or_number">Receipt # <span class="required">*</span></label>
                    <input type="text" id="or_number" name="or_number" class="form-control" placeholder="OR-2024-0001" required>
                    <small style="color: #6b7280; font-size: 12px;">Official Receipt number for this transaction</small>
                </div>

                <div class="form-group">
                    <label for="amount">Amount <span class="required">*</span></label>
                    <input type="number" id="amount" name="amount" class="form-control" step="0.01" min="0.01" placeholder="0.00" required>
                    <small style="color: #6b7280; font-size: 12px;">This amount will appear in the OUT column</small>
                </div>
            </div>
            <div class="entry-modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAddEntryModal()">Cancel</button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Save Entry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function downloadCashTreasurersBook() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        let url = '{{ route("admin.cash-treasurers-book.download-pdf") }}';
        url += `?month=${month}&year=${year}`;

        // Open download in new window
        window.open(url, '_blank');
    }

    function openAddEntryModal() {
        document.getElementById('addEntryModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeAddEntryModal() {
        document.getElementById('addEntryModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        document.getElementById('addEntryForm').reset();
    }

    function submitNewEntry(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData);

        // Set transaction type to disbursement (OUT)
        data.type = 'disbursement';

        // Set operator_id to null for coop-level transactions
        data.operator_id = null;

        // Set month to current month if not provided
        if (!data.month) {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            const currentDate = new Date();
            data.month = monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
        }

        // Submit to API
        fetch('/api/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Server error occurred');
                });
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                closeAddEntryModal();

                // Show success message
                alert('Transaction entry added successfully!');

                // Reload the page to show new entry
                window.location.reload();
            } else {
                alert('Error: ' + (result.message || 'Failed to add entry'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the entry: ' + error.message);
        });
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('addEntryModal');
        if (event.target === modal) {
            closeAddEntryModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('addEntryModal');
            if (modal.classList.contains('active')) {
                closeAddEntryModal();
            }
        }
    });
</script>
@endsection
