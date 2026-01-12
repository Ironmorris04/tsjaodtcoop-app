@extends('layouts.app')

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

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-badge.status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.status-pending {
        background: #fff3cd;
        color: #856404;
    }

    /* Attendance Modal Body */
    .attendance-modal-body {
        padding: 30px;
        overflow-y: auto;
        flex: 1;
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
        <a href="{{ route('operators.create') }}" class="btn-add-operator">
            <i class="fas fa-plus"></i> Add New Operator
        </a>
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
@endsection

@push('scripts')
<script>
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
                        <span class="status-badge status-${driver.status}">
                            ${driver.status ? driver.status.charAt(0).toUpperCase() + driver.status.slice(1) : 'N/A'}
                        </span>
                    </td>
                    <td>
                        <a href="/drivers/${driver.id}" class="btn-view-details-small">
                            <i class="fas fa-eye"></i> Details
                        </a>
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
                        <span class="status-badge status-${unit.status}">
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

// View Unit Details using API
function viewUnitDetails(unitId) {
    fetch(`/api/units/${unitId}`)
        .then(res => {
            if (!res.ok) throw new Error('Failed to fetch unit data');
            return res.json();
        })
        .then(unit => {
            if (unit.error) {
                showErrorModal('Error', unit.error);
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
                            <span>Unit Details - ${unit.plate_no || 'N/A'}</span>
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
                                    <span class="detail-value"><strong>${unit.plate_no || 'N/A'}</strong></span>
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
                                    <span class="detail-value">${unit.coding_number || 'N/A'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Police Number:</span>
                                    <span class="detail-value">${unit.police_no || 'N/A'}</span>
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

                        <!-- Add more sections here, just like in your original code -->
                        <!-- e.g., Unit Documents, Registration Details, Photos, Status, etc. -->
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
        })
        .catch(err => {
            console.error(err);
            showErrorModal('Error', 'Unable to fetch unit details. Please try again later.');
        });
}

function closeUnitDetailsModal() {
    const modal = document.getElementById('unitDetailsModal');
    if (modal) modal.remove();
}

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

// Close modal when clicking outside
document.getElementById('operatorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
