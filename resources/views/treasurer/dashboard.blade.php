@extends('layouts.app')

@section('title', 'Treasurer Dashboard')

@section('page-title', 'Treasurer Dashboard')

@push('styles')
<style>
    /* Dashboard Styles */
    .treasurer-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--start-color), var(--end-color));
        border-radius: 12px;
        padding: 25px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.blue {
        --start-color: #4e73df;
        --end-color: #224abe;
    }

    .stat-card.purple {
        --start-color: #6f42c1;
        --end-color: #4e2d87;
    }

    .stat-card.teal {
        --start-color: #36b9cc;
        --end-color: #258391;
    }

    .stat-card-content {
        position: relative;
        z-index: 1;
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .stat-card-title {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.95;
        margin: 0;
    }

    .stat-card-icon {
        font-size: 32px;
        opacity: 0.3;
    }

    .stat-card-value {
        font-size: 36px;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .stat-card-footer {
        margin-top: 12px;
        font-size: 12px;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Chart Card */
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
        margin-bottom: 30px;
    }

    .chart-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .chart-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card-title i {
        color: #4e73df;
    }

    .chart-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .chart-container {
        position: relative;
        height: 350px;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-container {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 1200px;
        max-height: 85vh;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modal-body {
        padding: 25px;
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
        position: relative;
    }

    /* Custom scrollbar for modal body */
    .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
        margin: 5px 0;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #4e73df;
        border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #224abe;
    }

    .operator-grid {
        display: grid;
        gap: 15px;
    }

    .operator-item {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: 4px solid #4e73df;
    }

    .operator-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .operator-info h4 {
        margin: 0 0 5px 0;
        color: #2c3e50;
        font-size: 16px;
        font-weight: 600;
    }

    .operator-info p {
        margin: 0;
        color: #7f8c8d;
        font-size: 13px;
    }

    .operator-arrow {
        color: #4e73df;
        font-size: 18px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 64px;
        color: #bdc3c7;
        margin-bottom: 20px;
    }

    .empty-state p {
        font-size: 18px;
        margin: 10px 0;
        color: #2c3e50;
    }

    /* Operator Detail Styles */
    .detail-section {
        margin-bottom: 25px;
    }

    .detail-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .detail-section-title i {
        color: #4e73df;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .detail-item {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 8px;
        border-left: 3px solid #4e73df;
    }

    .detail-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
    }

    .btn-add-transaction {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-add-transaction:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .btn-unpaid-balance {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }

    .btn-unpaid-balance:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
    }


    .btn-view-absences {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-view-absences:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }

    /* Transaction Table Styles */
    .transaction-form-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .transaction-form-table thead {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
    }

    .transaction-form-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .transaction-form-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e9ecef;
    }

    .transaction-form-table tbody tr {
        transition: background 0.2s ease;
    }

    .transaction-form-table tbody tr:hover {
        background: #f8f9fa;
    }

    .transaction-form-table input,
    .transaction-form-table select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .transaction-form-table input:focus,
    .transaction-form-table select:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .transaction-form-table input[readonly] {
        background: #e9ecef;
        cursor: not-allowed;
    }

    .btn-delete-row {
        background: #dc3545;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-delete-row:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .btn-add-row {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .btn-add-row:hover {
        background: #138496;
        transform: translateY(-2px);
    }

    .transaction-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #5a6268;
    }

    .btn-submit {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .transactions-list {
        margin-top: 20px;
    }

    .transactions-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .transactions-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .transactions-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        color: #495057;
        letter-spacing: 0.5px;
    }

    .transactions-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #2c3e50;
    }

    .transactions-table tbody tr:hover {
        background: #f8f9fa;
    }

    .no-transactions {
        text-align: center;
        padding: 30px;
        color: #6c757d;
        font-style: italic;
    }

    /* Transaction Summary Styles */
    .transaction-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .summary-item i {
        font-size: 28px;
        color: #4e73df;
    }

    .summary-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }

    .summary-value {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
    }

    /* Subsidiary Journal Styles */
    .subsidiary-journal {
        margin-top: 20px;
    }

    .journal-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        border-radius: 8px;
    }

    .journal-section-title i {
        color: white;
    }

    .transaction-type {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .transaction-type.type-receipt {
        background: #d4edda;
        color: #155724;
    }

    .transaction-type.type-disbursement {
        background: #f8d7da;
        color: #721c24;
    }

    .transaction-type i {
        font-size: 10px;
    }

    .transaction-row.type-receipt {
        background: rgba(212, 237, 218, 0.1);
    }

    .transaction-row.type-disbursement {
        background: rgba(248, 215, 218, 0.1);
    }

    .particular-cell {
        font-weight: 500;
    }

    .total-row {
        background: #f8f9fa;
        border-top: 2px solid #dee2e6;
        font-size: 15px;
    }

    .total-row td {
        padding: 15px 12px !important;
        border-bottom: none !important;
    }

    .transactions-table tfoot {
        background: #f8f9fa;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .treasurer-dashboard-grid {
            grid-template-columns: 1fr;
        }

        .stat-card-value {
            font-size: 28px;
        }

        .chart-container {
            height: 250px;
        }

        .modal-container {
            width: 95%;
            max-height: 90vh;
        }
    }

    /* Operators Table Styles */
    .table-responsive {
        overflow-x: auto;
        margin: 0;
    }

    .operators-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .operators-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .operators-table thead th {
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .operators-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s ease;
    }

    .operators-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .operators-table tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        color: #495057;
        font-size: 14px;
    }

    .operators-table tbody tr:last-child {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .operators-table {
            font-size: 12px;
        }

        .operators-table thead th,
        .operators-table tbody td {
            padding: 10px 8px;
        }
    }

    /* Modal Search Bar Styles */
    .modal-search-bar {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-search-bar i {
        color: #6c757d;
        font-size: 16px;
    }

    .modal-search-bar input {
        flex: 1;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .modal-search-bar input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .modal-search-bar input::placeholder {
        color: #adb5bd;
    }
</style>
@endpush

@section('content')
    <!-- Statistics Cards -->
    <div class="treasurer-dashboard-grid">
        <!-- Total Operators Card -->
        <div class="stat-card blue" onclick="openOperatorsModal()">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Total Operators</h3>
                    <i class="fas fa-building stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">{{ $totalOperators }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-arrow-up"></i>
                    <span>Registered operators</span>
                </div>
            </div>
        </div>

        <!-- Cash on Hand Card -->
        <div class="stat-card purple">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Cash on Hand</h3>
                    <i class="fas fa-wallet stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">₱{{ number_format($cashOnHand, 0) }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-coins"></i>
                    <span>Available cash</span>
                </div>
            </div>
        </div>

        <!-- Cash in Bank Card -->
        <div class="stat-card teal">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Cash in Bank</h3>
                    <i class="fas fa-university stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">₱{{ number_format($cashInBank, 0) }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-piggy-bank"></i>
                    <span>Bank deposits</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Books Navigation -->
    <div style="margin: 30px 0;">
        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #2c3e50;">
                <i class="fas fa-book-open"></i> Financial Books & Journals
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                <a href="{{ route('treasurer.cash-treasurers-book') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #6366f1, #4f46e5); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(99, 102, 241, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-book" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Treasurer's Book</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Overview of all operator cash positions</p>
                    </div>
                </a>
                <a href="{{ route('treasurer.cash-receipts-journal') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-receipt" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Receipts Journal</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">All incoming cash transactions</p>
                    </div>
                </a>
                <a href="{{ route('treasurer.cash-disbursement-book') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #ef4444, #dc2626); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-file-invoice-dollar" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Disbursement Book</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">All outgoing cash transactions</p>
                    </div>
                </a>
                <a href="{{ route('treasurer.cash-book') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #f59e0b, #d97706); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(245, 158, 11, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-book-open" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Book</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Combined receipts and disbursements</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Annual Collections Chart -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">
                <i class="fas fa-chart-line"></i>
                Annual Collections
            </h3>
            <span class="chart-badge">₱{{ number_format(array_sum($collectionsData['receipts']) + array_sum($collectionsData['disbursements']), 0) }} Total</span>
        </div>
        <div class="chart-container">
            <canvas id="collectionsChart"></canvas>
        </div>
    </div>

    <!-- Operators Modal -->
    <div class="modal-overlay" id="operatorsModal" onclick="closeOperatorsModal(event)">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-building"></i>
                    All Operators
                </h3>
                <button class="modal-close" onclick="closeOperatorsModal()">&times;</button>
            </div>
            <div class="modal-search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="operatorSearchInput" placeholder="Search by User ID, Name, Email, or Contact Number..." onkeyup="filterOperators()">
            </div>
            <div class="modal-body" id="operatorsModalContent">
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading operators...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Operator Detail Modal -->
    <div class="modal-overlay" id="operatorDetailModal" onclick="closeOperatorDetailModal(event)">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-user-circle"></i>
                    Operator Details
                </h3>
                <button class="modal-close" onclick="closeOperatorDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="operatorDetailContent">
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading operator details...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Modal -->
    <div class="modal-overlay" id="transactionModal" onclick="closeTransactionModal(event)">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-receipt"></i>
                    Add Transaction - <span id="transactionOperatorName"></span>
                </h3>
                <button class="modal-close" onclick="closeTransactionModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="transactionForm">
                    <input type="hidden" id="transaction_operator_id" name="operator_id">

                    <table class="transaction-form-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particular</th>
                                <th>From Month</th>
                                <th>To Month</th>
                                <th>Year</th>
                                <th>OR# <span style="color: red;">*</span></th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="transactionRows">
                            <!-- Rows will be added dynamically -->
                        </tbody>
                    </table>

                    <button type="button" class="btn-add-row" onclick="addTransactionRow()">
                        <i class="fas fa-plus"></i>
                        Add Row
                    </button>

                    <div class="transaction-modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeTransactionModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-check"></i>
                            Complete Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Unpaid Balance Modal -->
    <div class="modal-overlay" id="unpaidBalanceModal" onclick="closeUnpaidBalanceModal(event)">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Update Unpaid Balance
                </h3>
                <button class="modal-close" onclick="closeUnpaidBalanceModal()">&times;</button>
            </div>

            <div class="modal-body">
                <form id="unpaidBalanceForm">
                    @csrf  <!-- ← ADD THIS LINE -->
                    <input type="hidden" id="unpaid_operator_id" name="operator_id">

                    <table class="transaction-form-table">
                        <thead>
                            <tr>
                                <th>From Month</th>
                                <th>To Month</th>
                                <th>Year</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="unpaidBalanceRows">
                            <!-- Rows added dynamically -->
                        </tbody>
                    </table>

                    <button type="button" class="btn-add-row" onclick="addUnpaidBalanceRow()">
                        <i class="fas fa-plus"></i>
                        Add Row
                    </button>

                    <div class="transaction-modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeUnpaidBalanceModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-check"></i>
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Annual Collections Chart
const collectionsCtx = document.getElementById('collectionsChart').getContext('2d');
const collectionsChart = new Chart(collectionsCtx, {
    type: 'bar',
    data: {
        labels: @json($collectionsData['labels']),
        datasets: [
            {
                label: 'Receipts (₱)',
                data: @json($collectionsData['receipts']),
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: '#28a745',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            },
            {
                label: 'Disbursements (₱)',
                data: @json($collectionsData['disbursements']),
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: '#dc3545',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: {
                        size: 12,
                        weight: '600'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Modal Functions
function openOperatorsModal() {
    document.getElementById('operatorsModal').classList.add('active');
    loadOperators();
}

function closeOperatorsModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('operatorsModal').classList.remove('active');
}

// Unpaid Balance Modal Functions
function openUnpaidBalanceModal(operatorId) {
    const modal = document.getElementById('unpaidBalanceModal');
    if (!modal) return;

    document.getElementById('unpaid_operator_id').value = operatorId;
    const tbody = document.getElementById('unpaidBalanceRows');
    tbody.innerHTML = ''; // clear existing rows

    // Fetch current unpaid balance for this operator
    fetch(apiUrl(`operator/${operatorId}/unpaid-balance`), {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.balance) {
            addUnpaidBalanceRow(data.balance);
        } else {
            addUnpaidBalanceRow(); // empty row if no balance
        }
    })
    .catch(() => {
        addUnpaidBalanceRow(); // fallback
    });

    modal.classList.add('active'); // <-- add class for proper centering
}

function closeUnpaidBalanceModal(event) {
    const modal = document.getElementById('unpaidBalanceModal');
    if (!event || event.target === modal) {
        modal.classList.remove('active'); // <-- remove class
    }
}

function addUnpaidBalanceRow(balance = null) {
    const tbody = document.getElementById('unpaidBalanceRows');

    const row = document.createElement('tr');

    // Dynamic years: current + previous 6 years
    const currentYear = new Date().getFullYear();
    let yearOptions = `<option value="${currentYear}" selected>${currentYear}</option>`;
    for (let i = currentYear - 1; i >= currentYear - 6; i--) {
        yearOptions += `<option value="${i}">${i}</option>`;
    }

    // Prefill amount if balance provided
    const amountValue = balance ? balance.balance : '';

    row.innerHTML = `
        <td>
            <select class="form-control" name="from_month[]" required>
                <option value="">-- Select --</option>
                <option>January</option>
                <option>February</option>
                <option>March</option>
                <option>April</option>
                <option>May</option>
                <option>June</option>
                <option>July</option>
                <option>August</option>
                <option>September</option>
                <option>October</option>
                <option>November</option>
                <option>December</option>
            </select>
        </td>
        <td>
            <select class="form-control" name="to_month[]" required>
                <option value="">-- Select --</option>
                <option>January</option>
                <option>February</option>
                <option>March</option>
                <option>April</option>
                <option>May</option>
                <option>June</option>
                <option>July</option>
                <option>August</option>
                <option>September</option>
                <option>October</option>
                <option>November</option>
                <option>December</option>
            </select>
        </td>
        <td>
            <select class="form-control" name="year[]" required>
                ${yearOptions}
            </select>
        </td>
        <td>
            <input type="number"
                   class="form-control"
                   name="amount[]"
                   min="0"
                   step="0.01"
                   placeholder="0.00"
                   value="${amountValue}"
                   required>
        </td>
        <td style="text-align:center;">
            <button type="button"
                    class="btn-delete-row"
                    onclick="deleteUnpaidBalanceRow(this)">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
}

function deleteUnpaidBalanceRow(button) {
    const tbody = document.getElementById('unpaidBalanceRows');

    if (tbody.children.length > 1) {
        button.closest('tr').remove();
    } else {
        alert('At least one unpaid balance row is required.');
    }
}

// Unpaid Balance Form Submission
document.getElementById('unpaidBalanceForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const url = '/operator/unpaid-balance/update';
    
    // Debug: Check what's being sent
    console.log('Submitting payment data:');
    for (let [key, value] of formData.entries()) {
        console.log(key, ':', value);
    }

    fetch('/operator/unpaid-balance/update', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // ← Add CSRF token
        },
        credentials: 'same-origin'
    })
    .then(res => {
        console.log('Response status:', res.status); // Debug
        if (!res.ok) {
            throw new Error('Server returned ' + res.status);
        }
        return res.json();
    })
    .then(response => {
        console.log('Payment response:', response);
        
        if (response.success) {
            alert(response.message);
            closeUnpaidBalanceModal();
            
            // IMPORTANT: Refresh the monthly balances table
            const operatorId = formData.get('operator_id');
            if (operatorId) {
                console.log('Refreshing balances for operator:', operatorId);
                loadInlineMonthlyBalances(operatorId);
            }
        } else {
            alert('Error: ' + (response.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Payment submission error:', error);
        alert('Failed to update unpaid balance: ' + error.message);
    });
});

// Operators Modal Functions
let currentOperatorId = null;
let allOperators = []; // Store all operators for filtering

function openOperatorDetailModal(operatorId) {
    closeOperatorsModal();
    currentOperatorId = operatorId;
    document.getElementById('operatorDetailModal').classList.add('active');
    loadOperatorDetail(operatorId);
}

function closeOperatorDetailModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('operatorDetailModal').classList.remove('active');
}

function loadOperators() {
    fetch(apiUrl('operators'))
        .then(response => response.json())
        .then(data => {
            allOperators = data; // Store the data globally
            displayOperators(data);
            // Clear search input
            document.getElementById('operatorSearchInput').value = '';
        })
        .catch(error => {
            console.error('Error loading operators:', error);
            document.getElementById('operatorsModalContent').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Failed to load operators</p>
                    <small>Please try again later</small>
                </div>
            `;
        });
}

function filterOperators() {
    const searchTerm = document.getElementById('operatorSearchInput').value.toLowerCase();

    if (!searchTerm) {
        displayOperators(allOperators);
        return;
    }

    const filtered = allOperators.filter(operator => {
        const userId = (operator.user_id || '').toLowerCase();
        const operatorName = (operator.full_name || operator.contact_person || '').toLowerCase();
        const email = (operator.email || '').toLowerCase();
        const contactNumber = (operator.phone || '').toLowerCase();

        return userId.includes(searchTerm) ||
               operatorName.includes(searchTerm) ||
               email.includes(searchTerm) ||
               contactNumber.includes(searchTerm);
    });

    displayOperators(filtered);
}

function displayOperators(operators) {
    const content = document.getElementById('operatorsModalContent');
    const searchTerm = document.getElementById('operatorSearchInput').value;

    if (!operators || operators.length === 0) {
        const message = searchTerm
            ? `No operators match your search "${searchTerm}"`
            : 'There are no registered operators yet';

        content.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-${searchTerm ? 'search' : 'inbox'}"></i>
                <p>No operators found</p>
                <small>${message}</small>
            </div>
        `;
        return;
    }

    let html = `
        <div class="table-responsive">
            <table class="operators-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Operator Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th width="80">Action</th>
                    </tr>
                </thead>
                <tbody>
    `;

    operators.forEach(operator => {
        const userId = operator.user_id || 'N/A';
        const operatorName = operator.full_name || operator.contact_person || 'Unnamed Operator';
        const email = operator.email || 'N/A';
        const contactNumber = operator.phone || 'N/A';

        html += `
            <tr class="operator-row" onclick="openOperatorDetailModal(${operator.id})" style="cursor: pointer;">
                <td><strong style="color: #667eea;">${userId}</strong></td>
                <td><strong>${operatorName}</strong></td>
                <td><i class="fas fa-envelope" style="color: #6c757d; margin-right: 5px;"></i>${email}</td>
                <td><i class="fas fa-phone" style="color: #6c757d; margin-right: 5px;"></i>${contactNumber}</td>
                <td><i class="fas fa-chevron-right" style="color: #6c757d;"></i></td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
    `;

    content.innerHTML = html;
}

function loadOperatorDetail(operatorId) {
    fetch(apiUrl(`operators/${operatorId}`))
        .then(response => response.json())
        .then(data => {
            displayOperatorDetail(data);
        })
        .catch(error => {
            console.error('Error loading operator details:', error);
            document.getElementById('operatorDetailContent').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Failed to load operator details</p>
                    <small>Please try again later</small>
                </div>
            `;
        });
}

function displayOperatorDetail(data) {
    const content = document.getElementById('operatorDetailContent');
    const operator = data.operator;

    let html = `
        <div class="detail-section">
            <h4 class="detail-section-title">
                <i class="fas fa-info-circle"></i>
                Basic Information
            </h4>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Name</div>
                    <div class="detail-value">${operator.full_name || operator.contact_person || 'N/A'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">${operator.email || 'N/A'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Contact</div>
                    <div class="detail-value">${operator.phone || 'N/A'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">${operator.status || 'N/A'}</div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h4 class="detail-section-title">
                <i class="fas fa-chart-line"></i>
                Financial Summary
            </h4>
            <div class="detail-grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
                <div class="detail-item">
                    <div class="detail-label">Total Drivers</div>
                    <div class="detail-value">${data.total_drivers || 0}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Total Units</div>
                    <div class="detail-value">${data.total_units || 0}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Meeting Absences</div>
                    <div class="detail-value">${data.absences || 0}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Fine Balance</div>
                    <div class="detail-value" style="color: ${(data.remaining_fine || 0) > 0 ? '#dc3545' : '#28a745'}; font-weight: 700;">
                        ₱${(data.remaining_fine || 0).toLocaleString()}
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <button class="btn-add-transaction"
                    onclick="openTransactionModal(${operator.id}, '${operator.full_name || operator.contact_person || 'Operator'}')">
                <i class="fas fa-plus-circle"></i>
                Add Transaction
            </button>

            <button class="btn-unpaid-balance"
                    style="margin-left: 10px;"
                    onclick="openUnpaidBalanceModal(${operator.id})">
                <i class="fas fa-exclamation-circle"></i>
                Unpaid Balance
            </button>
        </div>

        <!-- JOURNAL ENTRIES -->
        <div class="detail-section transactions-list">
            <h4 class="detail-section-title">
                <i class="fas fa-history"></i>
                Transaction History
            </h4>
            <div id="transactionsListContent">
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading transactions...</p>
                </div>
            </div>
        </div>

        <!-- MONTHLY BALANCE BREAKDOWN (AFTER JOURNAL ENTRIES) -->
        <div class="detail-section">
            <h4 class="journal-section-title">
                <i class="fas fa-calendar-alt"></i>
                Monthly Balance Breakdown
            </h4>

            <div style="overflow-x: auto;">
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th style="text-align:right;">Obligations</th>
                            <th style="text-align:right;">Payments</th>
                            <th style="text-align:right;">Balance</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="monthlyBalanceTbody">
                        <tr>
                            <td colspan="5" style="text-align:center;">
                                <i class="fas fa-spinner fa-spin"></i>
                                Loading monthly balances...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    `;

    content.innerHTML = html;
    loadOperatorTransactions(operator.id);
    // Load Monthly Balances AFTER everything is rendered
    loadInlineMonthlyBalances(operator.id);
}

function loadOperatorTransactions(operatorId) {
    fetch(apiUrl(`transactions/operator/${operatorId}`))
        .then(response => response.json())
        .then(data => {
            if (data.success && data.transactions) {
                displayOperatorTransactions(data.transactions);
            } else {
                displayOperatorTransactions([]);
            }
        })
        .catch(error => {
            console.error('Error loading transactions:', error);
            document.getElementById('transactionsListContent').innerHTML = `
                <div class="no-transactions">
                    <i class="fas fa-exclamation-triangle"></i>
                    Failed to load transactions
                </div>
            `;
        });
}

// Load Monthly Balances Inline
function loadInlineMonthlyBalances(operatorId) {
    const tbody = document.getElementById('monthlyBalanceTbody');
    if (!tbody) return;

    fetch(apiUrl(`operator/${operatorId}/monthly-balances`), {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(balances => {
        tbody.innerHTML = '';

        if (!balances || balances.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center;">
                        No monthly balance data available
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        balances.forEach(b => {
            let statusBadge;

            if (b.status === 'paid') {
                statusBadge = '<span class="badge badge-success">Paid</span>';
            } else if (b.status === 'partial') {
                statusBadge = '<span class="badge badge-warning">Partial</span>';
            } else if (b.status === 'overpaid') {
                statusBadge = '<span class="badge badge-primary">Overpaid</span>';
            } else {
                statusBadge = '<span class="badge badge-danger">Unpaid</span>';
            }

            html += `
                <tr>
                    <td>${b.month} ${b.year}</td>
                    <td style="text-align:right;">₱${b.obligations.toFixed(2)}</td>
                    <td style="text-align:right;">₱${b.payments.toFixed(2)}</td>
                    <td style="text-align:right; font-weight:600;">₱${b.balance.toFixed(2)}</td>
                    <td style="text-align:center;">${statusBadge}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

    })
    .catch(() => {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center; color:red;">
                    Failed to load balances
                </td>
            </tr>
        `;
    });
}

function displayOperatorTransactions(transactions) {
    const content = document.getElementById('transactionsListContent');

    if (!transactions || transactions.length === 0) {
        content.innerHTML = `
            <div class="no-transactions">
                <i class="fas fa-inbox"></i>
                No transactions found for this operator
            </div>
        `;
        return;
    }

    // Calculate totals
    const totalAmount = transactions.reduce((sum, t) => sum + parseFloat(t.amount), 0);
    const receiptCount = transactions.filter(t => t.type === 'receipt').length;
    const disbursementCount = transactions.filter(t => t.type === 'disbursement').length;

    // Group transactions by type
    const receipts = transactions.filter(t => t.type === 'receipt');
    const disbursements = transactions.filter(t => t.type === 'disbursement');

    let html = `
        <div class="transaction-summary">
            <div class="summary-item">
                <i class="fas fa-receipt"></i>
                <div>
                    <div class="summary-label">Total Transactions</div>
                    <div class="summary-value">${transactions.length}</div>
                </div>
            </div>
            <div class="summary-item">
                <i class="fas fa-arrow-down" style="color: #2ecc71;"></i>
                <div>
                    <div class="summary-label">Receipts</div>
                    <div class="summary-value">${receiptCount}</div>
                </div>
            </div>
            <div class="summary-item">
                <i class="fas fa-arrow-up" style="color: #e74c3c;"></i>
                <div>
                    <div class="summary-label">Disbursements</div>
                    <div class="summary-value">${disbursementCount}</div>
                </div>
            </div>
            <div class="summary-item">
                <i class="fas fa-money-bill-wave" style="color: #4e73df;"></i>
                <div>
                    <div class="summary-label">Total Amount</div>
                    <div class="summary-value">₱${totalAmount.toFixed(2)}</div>
                </div>
            </div>
        </div>

        <div class="subsidiary-journal">
            <h5 class="journal-section-title">
                <i class="fas fa-book"></i>
                Subsidiary Journal Entries
            </h5>

            <table class="transactions-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Date</th>
                        <th style="width: 80px;">Type</th>
                        <th>Particular</th>
                        <th style="width: 100px;">Month</th>
                        <th style="width: 100px;">OR/Ref#</th>
                        <th style="width: 120px; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
    `;

    // Display all transactions sorted by date
    const sortedTransactions = [...transactions].sort((a, b) => new Date(a.date) - new Date(b.date));

    sortedTransactions.forEach(transaction => {
        const typeClass = transaction.type === 'receipt' ? 'type-receipt' : 'type-disbursement';
        const typeIcon = transaction.type === 'receipt' ? 'fa-arrow-down' : 'fa-arrow-up';
        const typeLabel = transaction.type === 'receipt' ? 'Receipt' : 'Disbursement';

        html += `
            <tr class="transaction-row ${typeClass}">
                <td>${formatDate(transaction.date)}</td>
                <td>
                    <span class="transaction-type ${typeClass}">
                        <i class="fas ${typeIcon}"></i>
                        ${typeLabel}
                    </span>
                </td>
                <td class="particular-cell">${transaction.formatted_particular || transaction.particular}</td>
                <td>${transaction.month || 'N/A'}</td>
                <td>${transaction.or_number || 'N/A'}</td>
                <td style="text-align: right; font-weight: 600;">₱${parseFloat(transaction.amount).toFixed(2)}</td>
            </tr>
        `;
    });

    html += `
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5" style="text-align: right; font-weight: 700;">TOTAL:</td>
                        <td style="text-align: right; font-weight: 700; color: #4e73df;">₱${totalAmount.toFixed(2)}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    `;

    content.innerHTML = html;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Transaction Modal Functions
function openTransactionModal(operatorId, operatorName) {
    document.getElementById('transactionOperatorName').textContent = operatorName;
    document.getElementById('transaction_operator_id').value = operatorId;
    document.getElementById('transactionRows').innerHTML = '';
    addTransactionRow();
    document.getElementById('transactionModal').classList.add('active');
}

function closeTransactionModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('transactionModal').classList.remove('active');
    document.getElementById('transactionForm').reset();
}

function getMonthOptions() {
    const months = ['January', 'February', 'March', 'April', 'May', 'June',
                   'July', 'August', 'September', 'October', 'November', 'December'];

    let options = '';
    months.forEach(month => {
        options += `<option value="${month}">${month}</option>`;
    });
    return options;
}

function getYearOptions() {
    const currentYear = new Date().getFullYear();
    let options = '';

    // Show current year and previous 5 years
    for (let i = 0; i <= 5; i++) {
        const year = currentYear - i;
        options += `<option value="${year}"${i === 0 ? ' selected' : ''}>${year}</option>`;
    }
    return options;
}

function addTransactionRow() {
    const tbody = document.getElementById('transactionRows');
    const today = new Date().toISOString().split('T')[0];
    const formattedToday = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="text" value="${formattedToday}" readonly>
        </td>
        <td>
            <select class="particular-select" required onchange="calculateRowPrice(this)">
                <option value="">-- Select --</option>

                <!-- Monthly group -->
                <optgroup label="-- Monthly --">
                    <option value="subscription_capital">CBU/Subscription Capital</option>
                    <option value="management_fee">Management Fee</option>
                    <option value="monthly_dues">Monthly Dues</option>
                    <option value="office_rental">Office Rental</option>
                </optgroup>

                <!-- Other selections -->
                <optgroup label="-- Other Fees --">
                    <option value="membership_fee">Membership Fee</option>
                    <option value="business_permit">Business Permit</option>
                    <option value="fine">Fine</option>
                    <option value="misc">Miscellaneous</option>
                </optgroup>
            </select>
        </td>
        <td>
            <select class="from-month-select" required onchange="calculateRowPrice(this)">
                <option value="">-- Select --</option>
                ${getMonthOptions()}
            </select>
        </td>
        <td>
            <select class="to-month-select" required onchange="calculateRowPrice(this)">
                <option value="">-- Select --</option>
                ${getMonthOptions()}
            </select>
        </td>
        <td>
            <select class="year-select" required onchange="calculateRowPrice(this)">
                <option value="">-- Select --</option>
                ${getYearOptions()}
            </select>
        </td>
        <td>
            <input type="text" class="or-number" placeholder="OR Number" required>
        </td>
        <td>
            <input type="number" step="0.01" min="0" class="price-input" placeholder="0.00" readonly required style="background-color: #f3f4f6; cursor: not-allowed;" title="Price is auto-calculated">
        </td>
        <td>
            <button type="button" class="btn-delete-row" onclick="deleteTransactionRow(this)">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
}

// Calculate price for a transaction row
async function calculateRowPrice(element) {
    const row = element.closest('tr');
    const particular = row.querySelector('.particular-select').value;
    const fromMonth = row.querySelector('.from-month-select').value;
    const toMonth = row.querySelector('.to-month-select').value;
    const year = row.querySelector('.year-select').value;
    const priceInput = row.querySelector('.price-input');

    // For misc, office_rental, business permit and fine - allow manual entry
    if (particular === 'misc' || particular === 'office_rental' || particular === 'fine' || particular === 'business_permit') {
        priceInput.readOnly = false;
        priceInput.style.backgroundColor = '#ffffff';
        priceInput.style.cursor = 'text';
        priceInput.title = 'Enter price manually';
        priceInput.value = '';
        return;
    }

    // Reset if any field is empty
    if (!particular || !fromMonth || !toMonth || !year) {
        priceInput.value = '';
        return;
    }

    // Make price field read-only for managed particulars
    priceInput.readOnly = true;
    priceInput.style.backgroundColor = '#f3f4f6';
    priceInput.style.cursor = 'not-allowed';
    priceInput.title = 'Price is auto-calculated based on settings';

    try {
        const response = await fetch('{{ route('treasurer.particular-prices.calculate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                particular: particular,
                from_month: fromMonth,
                to_month: toMonth,
                year: parseInt(year)
            })
        });

        const result = await response.json();

        if (result.success && result.data) {
            priceInput.value = result.data.amount.toFixed(2);
        } else {
            priceInput.value = '0.00';
        }
    } catch (error) {
        console.error('Error calculating price:', error);
        priceInput.value = '0.00';
    }
}

function deleteTransactionRow(button) {
    const tbody = document.getElementById('transactionRows');
    if (tbody.children.length > 1) {
        button.closest('tr').remove();
    } else {
        alert('At least one transaction row is required.');
    }
}

function showAbsencesSummary(totalAbsences, absencePenalty, finePaid, remainingFine) {
    if (totalAbsences === 0) {
        const message = 'This operator has perfect attendance! No absences recorded.';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Perfect Attendance!',
                html: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#667eea'
            });
        } else {
            alert(message);
        }
        return;
    }

    // Build detailed message with payment tracking
    const message = `
        <div style="text-align: left; line-height: 1.8;">
            <p><strong>Total Meeting Absences:</strong> ${totalAbsences}</p>
            <hr style="margin: 15px 0; border: none; border-top: 1px solid #e0e0e0;">
            <p><strong>Total Fine Owed:</strong> ₱${absencePenalty.toLocaleString()}</p>
            <p><strong>Fine Paid:</strong> <span style="color: #28a745;">₱${finePaid.toLocaleString()}</span></p>
            <hr style="margin: 15px 0; border: none; border-top: 2px solid #667eea;">
            <p style="font-size: 18px;"><strong>Remaining Balance:</strong> <span style="color: ${remainingFine > 0 ? '#dc3545' : '#28a745'};">₱${remainingFine.toLocaleString()}</span></p>
            <p style="font-size: 12px; color: #6c757d; margin-top: 10px;"><em>Fine rate: ₱100 per absence</em></p>
        </div>
    `;

    const icon = remainingFine > 0 ? 'warning' : 'success';
    const title = remainingFine > 0 ? 'Fine Balance Outstanding' : 'Fine Fully Paid!';

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: icon,
            title: title,
            html: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#667eea',
            width: '500px'
        });
    } else {
        alert(`Total Absences: ${totalAbsences}\nTotal Fine: ₱${absencePenalty}\nPaid: ₱${finePaid}\nRemaining: ₱${remainingFine}`);
    }
}

// Handle transaction form submission
document.getElementById('transactionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const operatorId = document.getElementById('transaction_operator_id').value;
    const rows = document.getElementById('transactionRows').querySelectorAll('tr');

    const transactions = [];
    let isValid = true;

    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let validationMessage = '';

    rows.forEach((row, index) => {
        const particular = row.querySelector('.particular-select').value;
        const fromMonth = row.querySelector('.from-month-select').value;
        const toMonth = row.querySelector('.to-month-select').value;
        const year = row.querySelector('.year-select').value;
        const orNumber = row.querySelector('.or-number').value;
        const price = row.querySelector('.price-input').value;

        if (!particular || !fromMonth || !toMonth || !year || !orNumber || !price) {
            isValid = false;
            validationMessage = 'Please fill in all required fields (Particular, From Month, To Month, Year, OR#, and Price) for all rows.';
            return;
        }

        // Validate that from_month is not after to_month
        const fromMonthIndex = months.indexOf(fromMonth);
        const toMonthIndex = months.indexOf(toMonth);

        if (fromMonthIndex > toMonthIndex) {
            isValid = false;
            validationMessage = `From Month cannot be after To Month in transaction row ${index + 1}`;
            return;
        }

        // Format month range: "January - March 2025" or just "January 2025" if same month
        const monthRange = fromMonth === toMonth
            ? `${fromMonth} ${year}`
            : `${fromMonth} - ${toMonth} ${year}`;

        transactions.push({
            particular: particular,
            month: monthRange,
            or_number: orNumber,
            amount: parseFloat(price),
            from_month: fromMonth,
            to_month: toMonth
        });
    });

    if (!isValid) {
        alert(validationMessage);
        return;
    }

    if (transactions.length === 0) {
        alert('Please add at least one transaction.');
        return;
    }

    // Submit transactions
    fetch('/api/transactions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            operator_id: operatorId,
            transactions: transactions
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeTransactionModal();
            // Reload operator details to show new transactions
            if (currentOperatorId) {
                loadOperatorDetail(currentOperatorId);
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to save transactions'));
        }
    })
    .catch(error => {
        console.error('Error submitting transactions:', error);
        alert('An error occurred while saving transactions. Please try again.');
    });
});

// Close modals on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeOperatorsModal();
        closeOperatorDetailModal();
        closeTransactionModal();
    }
});
</script>
@endpush
