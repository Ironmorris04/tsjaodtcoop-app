@extends('layouts.app')
@include('layouts.operator-app')

@section('title', 'Operators')
@section('page-title', 'Operators Management')

@section('breadcrumb')
<li><a href="{{ route('dashboard') }}">Home</a></li>
<li>Operators</li>
@endsection

@push('styles')
<style>
    .operators-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .operators-header h2 {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: 600;
    }

    .operators-header p {
        margin: 0;
        opacity: 0.9;
    }

    .btn-add-operator {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-operator:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(245, 87, 108, 0.5);
        color: white;
    }
    
    .btn-archive {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-archive:hover {
        background: #495057;
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(73, 80, 87, 0.5);
        color: white;
    }

    .operators-table-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .operators-table {
        width: 100%;
        border-collapse: collapse;
    }

    .operators-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .operators-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .operators-table th:first-child {
        border-top-left-radius: 8px;
    }

    .operators-table th:last-child {
        border-top-right-radius: 8px;
    }

    .operators-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .operators-table tbody tr:hover {
        background: #f8f9ff;
        transform: scale(1.01);
    }

    .operators-table td {
        padding: 15px;
        color: #495057;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-active {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .badge-inactive {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 15px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
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
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        white-space: nowrap;
    }

    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-clear {
        background: #ef4444;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        text-decoration: none;
    }

    .btn-clear:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
        color: white;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background: white;
        border-radius: 15px;
        width: 90%;
        max-width: 900px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
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

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px 15px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
    }

    .modal-body p {
        font-size: 15px; /* normal paragraphs slightly bigger */
    }
    
    .operator-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 16px;
        color: #343a40;
        font-weight: 500;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.purple {
        border-color: #667eea;
    }

    .stat-card.purple .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.green {
        border-color: #43e97b;
    }

    .stat-card.green .stat-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stat-card.blue {
        border-color: #4facfe;
    }

    .stat-card.blue .stat-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card.orange {
        border-color: #f6d365;
    }

    .stat-card.orange .stat-icon {
        background: linear-gradient(135deg, #fda085 0%, #f6d365 100%);
    }

    .stat-card.pink {
        border-color: #f093fb;
    }

    .stat-card.pink .stat-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 24px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }

    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    /* New Dashboard-style classes */
    .operator-info-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .operator-info-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .operator-info-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .operator-basic-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .operator-basic-info .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
        background: transparent;
        padding: 0;
        border: none;
    }

    .operator-basic-info .info-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 0;
    }

    .operator-basic-info .info-label i {
        color: #667eea;
        font-size: 14px;
    }

    .operator-basic-info .info-value {
        font-size: 15px;
        color: #2c3e50;
        font-weight: 500;
    }

    .attendance-stats-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .operator-stat-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .operator-stat-card:hover {
        transform: translateY(-2px);
    }

    .operator-stat-card i {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .operator-stat-card.stat-total i {
        color: #667eea;
    }

    .operator-stat-card.stat-present i {
        color: #1cc88a;
    }

    .operator-stat-card.stat-absent i {
        color: #e74a3b;
    }

    .operator-stat-card .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        display: block;
    }

    .operator-stat-card .stat-label {
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Attendance Details Table Container */
    .attendance-details-table-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-top: 25px;
    }

    .attendance-details-table-container h4 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    /* Attendance Table */
    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .attendance-table thead {
        background: #f8f9fc;
    }

    .attendance-table thead th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2c3e50;
        border-bottom: 2px solid #e3e6f0;
    }

    .attendance-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .attendance-table tbody tr:hover {
        background: #f8f9fc;
    }

    .attendance-table tbody tr:last-child {
        border-bottom: none;
    }

    .attendance-table tbody td {
        padding: 15px 20px;
        color: #495057;
        font-size: 14px;
    }

    .attendance-table tbody td strong {
        color: #2c3e50;
        font-weight: 600;
    }

    /* Empty State */
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

    /* Button View Details Small */
    .btn-view-details-small {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-view-details-small:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
    }

    /* Button Restore */
    .btn-restore {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-restore:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
    }

    /* Button Delete */
    .btn-delete {
        background: linear-gradient(135deg, #ff5f6d 0%, #ff0000 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
    }

    /* Status Badge */
    .status-badge-1 {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-badge-1.status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-badge-1.status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge-1.status-pending {
        background: #fff3cd;
        color: #856404;
    }

     /* Blur + dim effect for background modal */
    .modal-blur {
        filter: blur(4px);
        pointer-events: none;
        transition: all 0.3s ease;
    }

    /* ===== Modal Alignment with Operator Design ===== */

    .modal-content {
        border-radius: 14px;
        border: none;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
    }

    /* Modal header styled like section headers */
    .modal-header {
        padding: 20px 25px;
        border-bottom: 2px solid #e9ecef;
    }

    .modal-header .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Modal body spacing */
    .modal-body {
        padding: 25px;
        color: #495057;
        font-size: 14px;
    }

    /* Modal footer */
    .modal-footer {
        padding: 20px 25px;
        border-top: 2px solid #e9ecef;
    }

    /* Table inside modal matches attendance table */
    .modal .table {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .modal .table thead {
        background: #f8f9fc;
    }

    .modal .table thead th {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2c3e50;
        padding: 15px 20px;
        border-bottom: 2px solid #e3e6f0;
    }

    .modal .table tbody td {
        padding: 15px 20px;
        font-size: 14px;
        color: #495057;
    }

    .modal .table tbody tr:hover {
        background: #f8f9fc;
    }

    /* Buttons align with your gradients */
    .btn-modal-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }

    .btn-modal-primary:hover {
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        transform: translateY(-1px);
    }


    /* ===== Modal Themes ===== */
    .modal-header-success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .modal-header-danger {
        background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
        color: #fff;
    }

    .modal-header-success .modal-title,
    .modal-header-danger .modal-title {
        color: #fff;
    }

    /* Success Button */
    .btn-modal-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: #fff;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }

    .btn-modal-success:hover {
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.35);
        transform: translateY(-1px);
        color: white;
    }

    /* Danger Button */
    .btn-modal-danger {
        background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
        color: #fff;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }

    .btn-modal-danger:hover {
        box-shadow: 0 3px 10px rgba(220, 53, 69, 0.35);
        transform: translateY(-1px);
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        color: #fff;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }

    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        color: white;
    }

</style>
@endpush

@section('content')
<div class="operators-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2><i class="fas fa-building"></i> Operators Management</h2>
            <p>Manage all cooperative operators and view their statistics</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" 
                    class="btn-archive" 
                    data-toggle="modal" 
                    data-target="#archivedOperatorsModal">
                <i class="fas fa-archive"></i> Archived Operators
            </button>
            <a href="{{ route('operators.create') }}" class="btn-add-operator">
                <i class="fas fa-plus"></i> Add New Operator
            </a>
        </div>
    </div>
</div>

<!-- Search Bar -->
<form method="GET" action="{{ route('operators.index') }}" class="search-section">
    <label for="searchInput" class="search-label">
        <i class="fas fa-search"></i> Search Operators:
    </label>
    <input
        type="text"
        id="searchInput"
        name="search"
        class="search-input"
        placeholder="Search by name, business name, phone, or email..."
        value="{{ request('search') }}"
    >
    <button type="submit" class="btn-search">
        <i class="fas fa-search"></i> Search
    </button>
    @if(request('search'))
        <a href="{{ route('operators.index') }}" class="btn-clear">
            <i class="fas fa-times"></i> Clear
        </a>
    @endif
</form>

<div class="operators-table-container">
    @if($operators->count() > 0)
        <table class="operators-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Operator Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($operators as $operator)
                <tr onclick="viewOperatorDetails({{ $operator->id }})">
                    <td><strong>{{ $operator->user->user_id ?? 'N/A' }}</strong></td>
                    <td><strong>{{ $operator->full_name }}</strong></td>
                    <td><i class="fas fa-phone"></i> {{ $operator->phone }}</td>
                    <td><i class="fas fa-envelope"></i> {{ $operator->email }}</td>
                    <td>
                        @if($operator->status == 'active')
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-action btn-view" onclick="event.stopPropagation(); viewOperatorDetails({{ $operator->id }})">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($operators->hasPages())
            <div class="pagination-container">
                {{ $operators->appends(['search' => request('search')])->links('vendor.pagination.custom') }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px 20px; color: #6c757d;">
            <i class="fas fa-building" style="font-size: 64px; opacity: 0.3; margin-bottom: 20px;"></i>
            @if(request('search'))
                <h3 style="margin-bottom: 10px; color: #495057;">No Results Found</h3>
                <p>No operators match your search "{{ request('search') }}"</p>
                <a href="{{ route('operators.index') }}" class="btn-clear" style="margin-top: 15px; display: inline-flex;">
                    <i class="fas fa-times"></i> Clear Search
                </a>
            @else
                <h3 style="margin-bottom: 10px; color: #495057;">No Operators Found</h3>
                <p>Click the "Add New Operator" button to add your first operator</p>
            @endif
        </div>
    @endif
</div>

<!-- Operator Details Modal -->
<div class="modal-overlay" id="operatorModal">
    <div style="background: white; border-radius: 15px; width: 95%; max-width: 1400px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px 30px; border-radius: 15px 15px 0 0; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            <h3 style="margin: 0; font-size: 22px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-building"></i>
                <span id="operatorModalTitle">Operator Details</span>
            </h3>
            <button class="modal-close" onclick="closeModal()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; font-size: 28px; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="attendance-modal-body" id="modalContent">
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #667eea;"></i>
                <p style="margin-top: 20px; color: #6c757d;">Loading operator details...</p>
            </div>
        </div>
    </div>
</div>


<!-- Archived Operators Modal -->
<div class="modal fade" id="archivedOperatorsModal" tabindex="-1" keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-archive"></i>
                    Archived Operators
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 30px;">
                @php
                    $archivedOperators = \App\Models\Operator::onlyTrashed()
                        ->orderBy('deleted_at', 'desc')
                        ->get();
                @endphp

                @if($archivedOperators->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-archive" ></i>
                        <h5 class="text-muted">No Archived Operators</h5>
                        <p class="text-muted">All operators are currently registered.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>Business Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Archived Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($archivedOperators as $operator)
                                <tr>
                                    <td>
                                        <strong>{{ $operator->business_name }}</strong>
                                    </td>
                                    <td>{{ $operator->contact_person }}</td>
                                    <td>
                                        <i class="fas fa-phone text-muted"></i>
                                        {{ $operator->phone }}
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted"></i>
                                        {{ $operator->email }}
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="fas fa-calendar"></i>
                                            {{ $operator->deleted_at->format('M d, Y') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $operator->deleted_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <!-- Restore Button -->
                                            <button type="button" 
                                                    class="btn-restore"
                                                    onclick="confirmRestore({{ $operator->id }}, '{{ $operator->business_name }}')"
                                                    title="Restore Operator">
                                                <i class="fas fa-undo"></i> Restore
                                            </button>

                                            <!-- Permanent Delete Button -->
                                            <button type="button" 
                                                    class="btn-delete"
                                                    onclick="confirmPermanentDelete({{ $operator->id }}, '{{ $operator->business_name }}')"
                                                    title="Permanently Delete">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreOperatorModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-success">
            
            <div class="modal-header modal-header-success">
                <h5 class="modal-title">
                    <i class="fas fa-undo"></i>
                    Confirm Restore Operator
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form method="POST" id="restoreOperatorForm">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <p>
                        Are you sure you want to restore
                        <strong id="restoreOperatorName"></strong>?
                    </p>
                    <p class="text-muted">
                        This will reactivate the operator account.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-modal-success">
                        <i class="fas fa-undo"></i> Restore Operator
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Permanent Delete Confirmation Modal -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-danger">

            <div class="modal-header modal-header-danger">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Permanently Delete Operator
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form method="POST" id="permanentDeleteForm">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p class="text-danger font-weight-bold mb-3">
                        ⚠️ Permanently delete <strong id="deleteOperatorName"></strong> and all related data? This action cannot be undone.
                    </p>

                    <div class="form-group mt-3">
                        <label>Enter admin password to confirm</label>
                        <div class="input-group">
                            <input type="password"
                                   id="permanentDeletePassword"
                                   name="admin_password"
                                   class="form-control"
                                   required>

                            <div class="input-group-append">
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePermanentDeletePassword()">
                                    <i class="fas fa-eye" id="permanentDeletePasswordIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-modal-danger">
                        <i class="fas fa-trash-alt"></i> Permanently Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

function confirmRestore(operatorId, operatorName) {
    document.getElementById('restoreOperatorName').textContent = operatorName;
    document.getElementById('restoreOperatorForm').action = `/operators/${operatorId}/restore`;
    $('#restoreOperatorModal').modal('show');
}

function confirmPermanentDelete(operatorId, operatorName) {
    document.getElementById('deleteOperatorName').textContent = operatorName;
    document.getElementById('permanentDeleteForm').action = `/operators/${operatorId}/force-delete`;
    $('#permanentDeleteModal').modal('show');
}

function togglePermanentDeletePassword() {
    const input = document.getElementById('permanentDeletePassword');
    const icon = document.getElementById('permanentDeletePasswordIcon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

const archivedModal = document.querySelector('#archivedOperatorsModal .modal-content');

    // When confirmation modals open → blur background modal
    $('#restoreOperatorModal, #permanentDeleteModal').on('show.bs.modal', function () {
        archivedModal.classList.add('modal-blur');
    });

    // When confirmation modals close → restore background modal
    $('#restoreOperatorModal, #permanentDeleteModal').on('hidden.bs.modal', function () {
        archivedModal.classList.remove('modal-blur');
    });

async function viewOperatorDetails(operatorId) {
    console.log('Fetching operator details for ID:', operatorId);

    const modal = document.getElementById('operatorModal');
    const modalContent = document.getElementById('modalContent');
    const modalTitle = document.getElementById('operatorModalTitle');

    // Show modal with loading state
    modal.classList.add('active');
    modalTitle.textContent = 'Operator Details';
    modalContent.innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #667eea;"></i>
            <p style="margin-top: 20px; color: #6c757d;">Loading operator details...</p>
        </div>
    `;

    try {
        const baseUrl = window.location.origin;
        const apiUrl = `${baseUrl}/api/operator/${operatorId}/details`;
        console.log('API URL:', apiUrl);

        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status);

        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Operator data received:', data);

        if (data.success) {
            const operator = data.operator;

            window.currentOperatorData = operator;

            // Update modal title with operator's name
            const operatorFullName = operator.operator_detail ?
                [operator.operator_detail.first_name, operator.operator_detail.middle_name, operator.operator_detail.last_name].filter(Boolean).join(' ') :
                (operator.contact_person || (operator.user ? operator.user.name : 'Unknown'));
            modalTitle.textContent = `Cooperative Details - ${operatorFullName}`;

            // Generate drivers HTML
            const driversHtml = (operator.drivers || []).map((driver, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${driver.full_name || (driver.first_name + ' ' + driver.last_name)}</strong></td>
                    <td>${driver.license_number || 'N/A'}</td>
                    <td>${driver.license_type || 'N/A'}</td>
                    <td>${driver.phone || 'N/A'}</td>
                    <td>
                        <span class="status-badge-1 status-${driver.status}">
                            ${driver.status ? driver.status.charAt(0).toUpperCase() + driver.status.slice(1) : 'N/A'}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn-view-details-small" onclick="viewDriverDetails(${driver.id})">
                            <i class="fas fa-eye"></i> Details
                        </button>
                    </td>

                </tr>
            `).join('');

            // Generate units HTML
            const unitsHtml = (operator.units || []).map((unit, index) => `
                <tr>
                    <td>${index + 1}</td>
           <!--     <td><span style="font-family: monospace; font-weight: 600; color: #667eea;">${unit.unit_id || 'N/A'}</span></td> -->
                    <td><strong>${unit.plate_no || 'N/A'}</strong></td>
                    <td>${unit.year_model || 'N/A'}</td>
                    <td>${unit.franchise_case || 'N/A'}</td>
                    <td>${unit.lto_or_number || 'N/A'}</td>
                    <td>${unit.lto_cr_number || 'N/A'}</td>
                    <td>
                        <span class="status-badge-1 status-${unit.status}">
                            ${unit.status ? unit.status.charAt(0).toUpperCase() + unit.status.slice(1) : 'N/A'}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn-view-details-small" onclick="viewUnitDetails(${unit.id})">
                            <i class="fas fa-eye"></i> Details
                        </button>
                    </td>
                </tr>
            `).join('');

            // Get basic info for display
            const operatorName = operator.operator_detail ? operator.operator_detail.full_name : (operator.contact_person || (operator.user ? operator.user.name : 'N/A'));
            const operatorAge = (operator.operator_detail && operator.operator_detail.age && operator.operator_detail.age !== 'N/A') ? operator.operator_detail.age : 'N/A';
            const operatorSex = (operator.operator_detail && operator.operator_detail.sex && operator.operator_detail.sex !== 'N/A') ? operator.operator_detail.sex : 'N/A';
            const operatorContact = operator.phone || 'N/A';
            const operatorEmail = operator.email || (operator.user ? operator.user.email : 'N/A');

            modalContent.innerHTML = `
                <!-- SECTION 1: Operator Information -->
                <div class="operator-info-card">
                    <div class="operator-info-header operator-header-flex">
                    <h4>
                        <i class="fas fa-user-circle"></i>
                        Operator Information
                    </h4>
            
                    <a href="/operators/${operator.id}/edit"
                    class="btn-action" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                        <i class="fas fa-edit"></i>
                        Edit Operator
                    </a>
                </div>

                    ${operator.operator_detail && operator.operator_detail.profile_photo_url ? `
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="${operator.operator_detail.profile_photo_url}" alt="Operator Photo" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 4px solid #667eea; cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                    ` : ''}
                    ${!operator.operator_detail ? `
                        <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404; margin-right: 8px;"></i>
                            <span style="color: #856404; font-weight: 500;">This operator has not completed their detailed profile information yet.</span>
                        </div>
                    ` : ''}
                    <div class="operator-basic-info">
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-user"></i> Name:</span>
                            <span class="info-value">${operatorName}</span>
                        </div>
                        ${operator.operator_detail ? `
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-calendar-alt"></i> Age:</span>
                                <span class="info-value">${operatorAge}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-venus-mars"></i> Sex:</span>
                                <span class="info-value">${operatorSex}</span>
                            </div>
                        ` : ''}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-phone"></i> Contact Number:</span>
                            <span class="info-value">${operatorContact}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-envelope"></i> Email Address:</span>
                            <span class="info-value">${operatorEmail}</span>
                        </div>
                        ${operator.address ? `
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <span class="info-label"><i class="fas fa-map-marker-alt"></i> Address:</span>
                                <span class="info-value">${operator.address}</span>
                            </div>
                        ` : ''}
                    </div>

                    <div class="attendance-stats-summary">
                        <div class="operator-stat-card stat-total">
                            <i class="fas fa-id-card"></i>
                            <div>
                                <span class="stat-number">${(operator.drivers || []).length}</span>
                                <span class="stat-label">Total Drivers</span>
                            </div>
                        </div>
                        <div class="operator-stat-card stat-present">
                            <i class="fas fa-bus"></i>
                            <div>
                                <span class="stat-number">${(operator.units || []).length}</span>
                                <span class="stat-label">Total Units</span>
                            </div>
                        </div>
                        <div class="operator-stat-card stat-absent">
                            <i class="fas fa-toggle-${operator.status === 'active' ? 'on' : 'off'}"></i>
                            <div>
                                <span class="stat-number">${operator.status ? operator.status.charAt(0).toUpperCase() + operator.status.slice(1) : 'N/A'}</span>
                                <span class="stat-label">Status</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Drivers -->
                <div class="attendance-details-table-container">
                    <h4><i class="fas fa-id-card"></i> Drivers (${(operator.drivers || []).length})</h4>
                    ${(operator.drivers || []).length > 0 ? `
                        <table class="attendance-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Driver Name</th>
                                    <th>License Number</th>
                                    <th>License Type</th>
                                    <th>Phone</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${driversHtml}
                            </tbody>
                        </table>
                    ` : `
                        <div class="empty-state">
                            <i class="fas fa-user-slash"></i>
                            <p>No drivers registered</p>
                        </div>
                    `}
                </div>

                <!-- SECTION 3: Units -->
                <div class="attendance-details-table-container">
                    <h4><i class="fas fa-bus"></i> Units (${(operator.units || []).length})</h4>
                    ${(operator.units || []).length > 0 ? `
                        <table class="attendance-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                               <!-- <th>Unit User ID</th> -->
                                    <th>Plate Number</th>
                                    <th>Year Model</th>
                                    <th>Franchise Case</th>
                                    <th>LTO OR</th>
                                    <th>LTO CR</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${unitsHtml}
                            </tbody>
                        </table>
                    ` : `
                        <div class="empty-state">
                            <i class="fas fa-bus-slash"></i>
                            <p>No units registered</p>
                        </div>
                    `}
                </div>
                
                <!-- 
                Edit Button
                <div style="margin-top: 30px; padding-top: 25px; border-top: 2px solid #f0f0f0; display: flex; gap: 10px; justify-content: flex-end;">
                    <a href="/operators/${operator.id}/edit" class="btn-action" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                        <i class="fas fa-edit"></i> Edit Operator
                    </a>
                </div> -->
            `;
        } else {
            modalContent.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #dc3545;">
                    <i class="fas fa-exclamation-circle" style="font-size: 48px;"></i>
                    <p style="margin-top: 20px;">Failed to load operator details</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading operator details:', error);

        modalContent.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-exclamation-circle" style="font-size: 48px; color: #e74c3c; margin-bottom: 20px;"></i>
                <p style="color: #e74c3c; font-weight: 600; font-size: 18px; margin-bottom: 10px;">Error Loading Operator Details</p>
                <p style="color: #6c757d; font-size: 14px; margin-bottom: 20px;">${error.message || 'An unexpected error occurred. Please check your connection and try again.'}</p>
                <button onclick="closeModal()" style="padding: 10px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        `;
    }
}

function closeModal() {
    document.getElementById('operatorModal').classList.remove('active');
}

// View Driver Details
function viewDriverDetails(driverId) {
    const operator = window.currentOperatorData;
    if (!operator) {
        showErrorModal('Error', 'Operator data is not loaded.');
        return;
    }

    const driver = operator.drivers.find(d => d.id === driverId);
    if (!driver) {
        showErrorModal('Error', 'Driver not found');
        return;
    }

    // Remove existing modal if it exists
    const existingModal = document.getElementById('driverDetailsModal');
    if (existingModal) existingModal.remove();

    // Format birthdate and calculate age
    let age = 'N/A';
    let formattedDOB = 'N/A';
    if (driver.birthdate) {
        const birth = new Date(driver.birthdate);
        const today = new Date();
        let years = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) years--;
        age = years >= 0 ? years : 'N/A';
        formattedDOB = birth.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: '2-digit' });
    }

    const modal = document.createElement('div');
    modal.id = 'driverDetailsModal';
    modal.className = 'attendance-modal nested-modal';
    modal.style.display = 'flex';
    modal.style.zIndex = '10001';

    modal.innerHTML = `
        <div class="attendance-modal-container" style="max-width: 900px;">
            <div class="attendance-modal-header">
                <h3><i class="fas fa-id-card"></i> Driver Details - ${driver.full_name || driver.first_name + ' ' + driver.last_name}</h3>
                <button class="modal-close-btn" onclick="closeDriverDetailsModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="attendance-modal-body">
                <!-- Photo -->
                ${driver.photo_url ? `<div style="text-align: center; margin-bottom: 25px;">
                    <h4><i class="fas fa-camera"></i> Driver Photo</h4>
                    <img src="${driver.photo_url}" alt="Driver Photo" onclick="viewImageFullscreen('${driver.photo_url}', 'Driver Photo')" style="max-width: 250px; cursor: pointer; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </div>` : ''}

                <!-- Personal Info -->
                <div class="full-details-section">
                    <h4><i class="fas fa-user"></i> Personal Information</h4>
                    <div class="full-details-grid">
                        <div class="detail-item"><span class="detail-label">Full Name:</span><span class="detail-value">${driver.full_name || driver.first_name + ' ' + driver.last_name}</span></div>
                        <div class="detail-item"><span class="detail-label">Age:</span><span class="detail-value">${age}</span></div>
                        <div class="detail-item"><span class="detail-label">Sex:</span><span class="detail-value">${driver.sex || 'N/A'}</span></div>
                        <div class="detail-item"><span class="detail-label">Date of Birth:</span><span class="detail-value">${formattedDOB}</span></div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="full-details-section">
                    <h4><i class="fas fa-address-card"></i> Contact Information</h4>
                    <div class="full-details-grid">
                        <div class="detail-item"><span class="detail-label">Phone:</span><span class="detail-value">${driver.phone || 'N/A'}</span></div>
                        <div class="detail-item" style="grid-column: 1 / -1;"><span class="detail-label">Address:</span><span class="detail-value">${driver.address || 'N/A'}</span></div>
                        <div class="detail-item" style="grid-column: 1 / -1;"><span class="detail-label">Emergency Contact:</span><span class="detail-value">${driver.emergency_contact || 'N/A'}</span></div>
                    </div>
                </div>

                <!-- License Info -->
                <div class="full-details-section">
                    <h4><i class="fas fa-car"></i> License Information</h4>
                    <div class="full-details-grid">
                        <div class="detail-item"><span class="detail-label">License Number:</span><span class="detail-value">${driver.license_number || 'N/A'}</span></div>
                        <div class="detail-item"><span class="detail-label">License Expiry:</span><span class="detail-value">${driver.license_expiry || 'N/A'}</span></div>
                    </div>
                </div>

                <!-- Employment Info -->
                <div class="full-details-section">
                    <h4><i class="fas fa-calendar"></i> Employment Information</h4>
                    <div class="full-details-grid">
                        <div class="detail-item"><span class="detail-label">Date Approved:</span><span class="detail-value">${driver.approved_at || 'N/A'}</span></div>
                        <div class="detail-item"><span class="detail-label">Driver Status:</span>
                            <span class="status-badge status-${driver.status || 'N/A'}">${driver.status ? driver.status.charAt(0).toUpperCase() + driver.status.slice(1) : 'N/A'}</span>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                ${driver.biodata_photo_url || driver.license_photo_url ? `
                <div class="full-details-section">
                    <h4><i class="fas fa-images"></i> Documents</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 15px;">
                        ${driver.biodata_photo_url ? `
                            <div style="text-align: center;">
                                <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-id-card"></i> Biodata</h5>
                                <img src="${driver.biodata_photo_url}" alt="Biodata" onclick="viewImageFullscreen('${driver.biodata_photo_url}', 'Driver Biodata')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer;">
                            </div>
                        ` : ''}
                        ${driver.license_photo_url ? `
                            <div style="text-align: center;">
                                <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-id-card-alt"></i> License</h5>
                                <img src="${driver.license_photo_url}" alt="License" onclick="viewImageFullscreen('${driver.license_photo_url}', 'Driver License')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer;">
                            </div>
                        ` : ''}
                    </div>
                </div>` : ''}
            </div>
        </div>
    `;

    document.getElementById('modalContent').appendChild(modal);
}

// Close Driver Modal
function closeDriverDetailsModal() {
    const modal = document.getElementById('driverDetailsModal');
    if (modal) modal.remove();
}

// View Unit Details
    function viewUnitDetails(unitId) {
        const operator = window.currentOperatorData;
        if (!operator) return;

        const unit = operator.units.find(u => u.id === unitId);
        if (!unit) {
            showErrorModal('Error', 'Unit not found');
            return;
        }

        const modal = document.createElement('div');
        modal.id = 'unitDetailsModal';
        modal.className = 'attendance-modal nested-modal';
        modal.style.display = 'flex';
        modal.style.zIndex = '10001';

        modal.innerHTML = `
            <div class="attendance-modal-container" style="max-width: 1000px;">
                <div class="attendance-modal-header">
                    <h3>
                        <i class="fas fa-bus"></i>
                        <span>Unit Details - ${unit.plate_no}</span>
                    </h3>
                    <button class="modal-close-btn" onclick="closeUnitDetailsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="attendance-modal-body">
                    ${unit.unit_photo_url ? `
                        <div style="text-align: center; margin-bottom: 25px;">
                            <h4 style="margin-bottom: 15px;"><i class="fas fa-camera"></i> Unit Photo</h4>
                            <img src="${unit.unit_photo_url}" alt="Unit Photo" onclick="viewImageFullscreen('${unit.unit_photo_url}', 'Unit Photo')" style="max-width: 400px; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                    ` : ''}

                    <div class="full-details-section">
                        <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Plate Number:</span>
                                <span class="detail-value"><strong>${unit.plate_no}</strong></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Year Model:</span>
                                <span class="detail-value">${unit.year_model || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Color:</span>
                                <span class="detail-value">${unit.color || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-cog"></i> Vehicle Identification</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Body Number:</span>
                                <span class="detail-value">${unit.body_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Engine Number:</span>
                                <span class="detail-value">${unit.engine_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Chassis Number:</span>
                                <span class="detail-value">${unit.chassis_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Coding Number:</span>
                                <span class="detail-value">${unit.chassis_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Police Number:</span>
                                <span class="detail-value">${unit.chassis_number || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-file-alt"></i> LTO Documents (OR & CR)</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">LTO OR Number:</span>
                                <span class="detail-value">${unit.lto_or_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">LTO CR Number:</span>
                                <span class="detail-value">${unit.lto_cr_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">LTO OR Date Issued:</span>
                                <span class="detail-value">${unit.lto_or_date_issued || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">LTO CR Date Issued:</span>
                                <span class="detail-value">${unit.lto_cr_date_issued || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-bus"></i> Unit Documents (OR & CR)</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Unit OR Number:</span>
                                <span class="detail-value">${unit.unit_or_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Unit CR Number:</span>
                                <span class="detail-value">${unit.unit_cr_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Unit OR Validity:</span>
                                <span class="detail-value">${unit.unit_or_date_validity || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Unit CR Validity:</span>
                                <span class="detail-value">${unit.unit_cr_date_validity || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-certificate"></i> Registration Details</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Business Permit Number</span>
                                <span class="detail-value">${unit.business_permit_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Business Permit Validity</span>
                                <span class="detail-value">${unit.business_permit_validity || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Franchise Case:</span>
                                <span class="detail-value">${unit.franchise_case || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">MV File:</span>
                                <span class="detail-value">${unit.mv_file || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge status-${unit.status}">
                                        ${unit.status.charAt(0).toUpperCase() + unit.status.slice(1)}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    ${unit.business_permit_photo_url || unit.or_photo_url || unit.cr_photo_url ? `
                        <div class="full-details-section">
                            <h4><i class="fas fa-images"></i> Documents</h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 15px;">
                                ${unit.business_permit_photo_url ? `
                                    <div style="text-align: center;">
                                        <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-file-contract"></i> Business Permit</h5>
                                        <img src="${unit.business_permit_photo_url}" alt="Business Permit" onclick="viewImageFullscreen('${unit.business_permit_photo_url}', 'Business Permit')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                ` : ''}
                                ${unit.or_photo_url ? `
                                    <div style="text-align: center;">
                                        <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-receipt"></i> OR (Official Receipt)</h5>
                                        <img src="${unit.or_photo_url}" alt="OR" onclick="viewImageFullscreen('${unit.or_photo_url}', 'Official Receipt (OR)')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                ` : ''}
                                ${unit.cr_photo_url ? `
                                    <div style="text-align: center;">
                                        <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-file-alt"></i> CR (Certificate of Registration)</h5>
                                        <img src="${unit.cr_photo_url}" alt="CR" onclick="viewImageFullscreen('${unit.cr_photo_url}', 'Certificate of Registration (CR)')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.body.appendChild(modal);
    }

    function closeUnitDetailsModal() {
        const modal = document.getElementById('unitDetailsModal');
        if (modal) {
            modal.remove();
        }
    }


// Close nested modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('nested-modal')) {
            e.target.remove();
        }
    });

    function viewImageFullscreen(imageUrl, title = 'Image') {
        const fullscreenModal = document.createElement('div');
        fullscreenModal.className = 'fullscreen-image-modal';
        fullscreenModal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        `;

        fullscreenModal.innerHTML = `
            <div style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); color: white; font-size: 18px; font-weight: 600; text-align: center;">
                ${title}
            </div>
            <button onclick="this.parentElement.remove()" style="position: absolute; top: 20px; right: 20px; border: none; color: white; font-size: 30px; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;" class="modal-close-btn">
                <i class="fas fa-times"></i>
            </button>
            <img src="${imageUrl}" alt="${title}" style="max-width: 95%; max-height: 95%; object-fit: contain; border-radius: 8px; box-shadow: 0 8px 32px rgba(0,0,0,0.5);">
            <div style="position: absolute; bottom: 20px; color: white; font-size: 14px; opacity: 0.7;">
                Click anywhere outside the image to close
            </div>
        `;

        // Close when clicking outside the image
        fullscreenModal.addEventListener('click', function(e) {
            if (e.target === fullscreenModal || e.target.tagName === 'BUTTON') {
                fullscreenModal.remove();
            }
        });

        // Close with Escape key
        const escapeHandler = function(e) {
            if (e.key === 'Escape') {
                fullscreenModal.remove();
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);

        document.body.appendChild(fullscreenModal);
    }

function deleteOperator(id) {
    if (!confirm('Are you sure you want to delete this operator? This will also delete all associated drivers and units.')) {
        return;
    }

    // Create and submit a delete form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/operators/${id}`;

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

// Close modal when clicking outside
document.getElementById('operatorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
