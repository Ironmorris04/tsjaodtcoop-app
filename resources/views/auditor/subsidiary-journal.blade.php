@extends('layouts.app')

@section('content')
<div style="padding: 0;">
    <!-- Page Header -->
    <div style="margin-bottom: 20px;">
        <h2 style="color: #343a40; font-size: 24px; font-weight: 700; margin: 0 0 8px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-book" style="color: #667eea;"></i>
            Subsidiary Journal
        </h2>
        <p style="color: #6c757d; margin: 0; font-size: 14px;">
            View and audit all operator transactions and financial records
        </p>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-receipt"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Total Transactions</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50; line-height: 1;">{{ number_format($totalTransactions) }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-peso-sign"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Total Amount</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50; line-height: 1;">₱{{ number_format($totalAmount, 2) }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-users"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Active Operators</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50; line-height: 1;">{{ number_format($operators->count()) }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow: hidden;">
        <div style="padding: 20px 25px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #343a40; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-table"></i> Transaction Records
            </h3>
            <span style="background: #f8f9fa; color: #495057; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                {{ $recentTransactions->count() }} Records
            </span>
        </div>
        <div style="padding: 25px;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="transactionsTable" style="width: 100%; margin-bottom: 0;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th style="padding: 15px; text-align: left; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-calendar"></i> Date
                            </th>
                            <th style="padding: 15px; text-align: left; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-user-tie"></i> Operator
                            </th>
                            <th style="padding: 15px; text-align: left; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-file-alt"></i> Particular
                            </th>
                            <th style="padding: 15px; text-align: left; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-tag"></i> Type
                            </th>
                            <th style="padding: 15px; text-align: right; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-dollar-sign"></i> Amount
                            </th>
                            <th style="padding: 15px; text-align: center; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-check-circle"></i> Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px; color: #495057; font-size: 14px;">
                                <strong>{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</strong>
                                <br><small style="color: #6c757d;">{{ \Carbon\Carbon::parse($transaction->date)->format('l') }}</small>
                            </td>
                            <td style="padding: 15px; color: #495057; font-size: 14px;">
                                @if($transaction->operator)
                                    <strong style="color: #2c3e50;">{{ $transaction->operator->full_name }}</strong>
                                @else
                                    <span style="color: #6c757d;">N/A</span>
                                @endif
                            </td>
                            <td style="padding: 15px; color: #495057; font-size: 14px;">
                                <span style="background: #f8f9fa; color: #495057; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $transaction->formatted_particular ?? $transaction->particular ?? 'N/A' }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: {{ $transaction->type === 'debit' ? '#d4edda' : '#d1ecf1' }}; color: {{ $transaction->type === 'debit' ? '#155724' : '#0c5460' }};">
                                    <i class="fas fa-{{ $transaction->type === 'debit' ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ ucfirst($transaction->type ?? 'N/A') }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right; font-weight: 700; color: #2ecc71; font-size: 15px;">
                                ₱{{ number_format($transaction->amount, 2) }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">
                                    <i class="fas fa-check"></i> Recorded
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center;">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #bdc3c7; margin-bottom: 15px;"></i>
                                <p style="color: #7f8c8d; margin: 0;">No transactions found in the system</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background: #f8f9fa;">
                        <tr>
                            <th colspan="4" style="padding: 15px; text-align: right; font-weight: 700; border-top: 2px solid #dee2e6;">Total Amount:</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700; color: #2ecc71; border-top: 2px solid #dee2e6;">₱{{ number_format($totalAmount, 2) }}</th>
                            <th style="border-top: 2px solid #dee2e6;"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background: #f8f9fc !important;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#transactionsTable').DataTable({
        "order": [[0, "desc"]],
        "pageLength": 25,
        "language": {
            "search": "Search transactions:",
            "lengthMenu": "Show _MENU_ transactions per page"
        }
    });
});
</script>
@endpush
@endsection
