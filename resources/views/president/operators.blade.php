@extends('layouts.app')

@section('title', 'Operators Directory')

@section('page-title', 'Operators Directory')

@section('content')
<div class="operators-page-container">

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card operators">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number">{{ $totalOperators }}</div>
                    <div class="stat-label">Total Operators</div>
                </div>
            </div>
            <div class="stat-card drivers">
                <div class="stat-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number">{{ $totalDrivers }}</div>
                    <div class="stat-label">Total Drivers</div>
                </div>
            </div>
            <div class="stat-card units">
                <div class="stat-icon">
                    <i class="fas fa-bus"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number">{{ $totalUnits }}</div>
                    <div class="stat-label">Total Units</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operators Directory -->
    <div class="operators-directory-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-users"></i>
                All Operators
            </h2>
        </div>

        <!-- Search Bar -->
        <div class="search-bar-container" style="padding: 20px; background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
            <div style="position: relative; max-width: 500px;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                <input
                    type="text"
                    id="operatorSearchInput"
                    placeholder="Search by name, email, contact, or User ID..."
                    style="width: 100%; padding: 12px 15px 12px 45px; border: 2px solid #dee2e6; border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                    onkeyup="searchOperators()"
                    onfocus="this.style.borderColor='#4e73df'; this.style.boxShadow='0 0 0 3px rgba(78, 115, 223, 0.1)';"
                    onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none';"
                >
            </div>
            <div id="searchResultCount" style="margin-top: 10px; font-size: 13px; color: #6c757d;"></div>
        </div>

        <div class="table-container">
            <table class="operators-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">User ID</th>
                        <th>Operator Name</th>
                        <th>Email</th>
                        <th style="width: 130px;">Contact</th>
                        <th style="width: 80px;" class="text-center">Drivers</th>
                        <th style="width: 80px;" class="text-center">Units</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operators as $operator)
                        <tr>
                            <td><span class="user-id-badge">{{ $operator->user->user_id ?? 'N/A' }}</span></td>
                            <td><strong>{{ $operator->full_name }}</strong></td>
                            <td>{{ $operator->email ?? ($operator->user ? $operator->user->email : 'N/A') }}</td>
                            <td>{{ $operator->phone ?? 'N/A' }}</td>
                            <td class="text-center">
                                <span class="count-badge drivers-count">{{ $operator->drivers->count() }}</span>
                            </td>
                            <td class="text-center">
                                <span class="count-badge units-count">{{ $operator->units->count() }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $operator->status }}">
                                    {{ ucfirst($operator->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-view-details" onclick="viewOperatorDetails({{ $operator->id }})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px;">
                                <div class="empty-state-table">
                                    <i class="fas fa-users-slash"></i>
                                    <p>No operators found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($operators->count() > 0)
            <div class="card-footer-custom">
                {{ $operators->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>

<style>
    .operators-page-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Statistics Section */
    .stats-section {
        flex-shrink: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.operators::before {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.drivers::before {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .stat-card.units::before {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
        flex-shrink: 0;
    }

    .stat-card.operators .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.drivers .stat-icon {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .stat-card.units .stat-icon {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .stat-details {
        flex: 1;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Operators Directory Card */
    .operators-directory-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .card-header {
        padding: 20px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .card-title {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-container {
        overflow-x: auto;
        flex: 1;
    }

    .operators-table {
        width: 100%;
        border-collapse: collapse;
    }

    .operators-table thead {
        background: #f8f9fc;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .operators-table thead th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2c3e50;
        border-bottom: 2px solid #e3e6f0;
    }

    .operators-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .operators-table tbody tr:hover {
        background: #f8f9fc;
    }

    .operators-table tbody tr:last-child {
        border-bottom: none;
    }

    .operators-table tbody td {
        padding: 15px 20px;
        color: #495057;
        font-size: 14px;
    }

    .operators-table tbody td strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .text-center {
        text-align: center;
    }

    .user-id-badge {
        display: inline-block;
        padding: 4px 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 35px;
        height: 35px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
    }

    .count-badge.drivers-count {
        background: #d4edda;
        color: #155724;
    }

    .count-badge.units-count {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
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

    .btn-view-details {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-view-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .empty-state-table {
        color: #7f8c8d;
    }

    .empty-state-table i {
        font-size: 48px;
        color: #bdc3c7;
        margin-bottom: 15px;
    }

    .empty-state-table p {
        font-size: 16px;
        color: #2c3e50;
        margin: 0;
    }

    .card-footer-custom {
        padding: 20px 30px;
        background: #f8f9fc;
        border-top: 1px solid #e3e6f0;
        flex-shrink: 0;
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .operators-table {
            font-size: 13px;
        }

        .operators-table thead th,
        .operators-table tbody td {
            padding: 12px 15px;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            gap: 10px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .stat-number {
            font-size: 28px;
        }

        .stat-label {
            font-size: 12px;
        }

        .operators-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .operators-table thead,
        .operators-table tbody,
        .operators-table tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .operators-table thead th,
        .operators-table tbody td {
            padding: 10px 12px;
            font-size: 12px;
        }

        .user-id-badge {
            font-size: 11px;
            padding: 3px 8px;
        }

        .count-badge {
            min-width: 30px;
            height: 30px;
            font-size: 12px;
        }

        .btn-view-details {
            padding: 6px 12px;
            font-size: 12px;
        }
    }

    /* Modal Styles - Same as dashboard */
    .attendance-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .attendance-modal-container {
        background: white;
        border-radius: 15px;
        width: 95%;
        max-width: 1400px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
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

    .attendance-modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px 15px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .attendance-modal-header h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        line-height: 1;
    }

    .modal-close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .attendance-modal-body {
        padding: 30px;
        overflow-y: auto;
        flex: 1;
    }

    .meeting-info-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .meeting-info-section h4 {
        margin: 0 0 15px 0;
        font-size: 20px;
        font-weight: 600;
    }

    .meeting-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .meeting-detail {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .meeting-detail i {
        font-size: 16px;
        opacity: 0.9;
    }

    .attendance-stats-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .stat-card.stat-total i {
        color: #667eea;
    }

    .stat-card.stat-present i {
        color: #1cc88a;
    }

    .stat-card.stat-absent i {
        color: #e74a3b;
    }

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

    /* Enhanced Modal Styles */
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

    .btn-view-full-details {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-view-full-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

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

    .operator-basic-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-label i {
        color: #667eea;
        font-size: 14px;
    }

    .info-value {
        font-size: 15px;
        color: #2c3e50;
        font-weight: 500;
    }

    /* Full Details Styles */
    .full-details-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .full-details-section h4 {
        margin: 0 0 15px 0;
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .full-details-section h4 i {
        color: #667eea;
    }

    .full-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 15px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .detail-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .detail-value {
        font-size: 14px;
        color: #2c3e50;
        font-weight: 500;
    }

    /* Nested Modal */
    .nested-modal {
        background: rgba(0, 0, 0, 0.7);
    }

    @media (max-width: 768px) {
        .operator-basic-info,
        .full-details-grid {
            grid-template-columns: 1fr;
        }

        .operator-info-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .btn-view-full-details {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Helper function to calculate age from birthdate
    function calculateDriverAge(birthdate) {
        if (!birthdate) return 'N/A';
        const today = new Date();
        const birth = new Date(birthdate);
        if (isNaN(birth.getTime())) return 'N/A';
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age + ' years old';
    }

    // Helper function to format birthdate for display
    function formatDriverBirthdate(birthdate) {
        if (!birthdate) return 'N/A';
        const date = new Date(birthdate);
        if (isNaN(date.getTime())) return 'N/A';
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }

    // Helper function to format sex/gender
    function formatDriverSex(sex) {
        if (!sex) return 'N/A';
        return sex.charAt(0).toUpperCase() + sex.slice(1).toLowerCase();
    }

    // Reuse the same modal functions from the dashboard
    function viewOperatorDetails(operatorId) {
        console.log('Fetching operator details for ID:', operatorId);
        console.log('Current origin:', window.location.origin);
        console.log('Current pathname:', window.location.pathname);

        // Show loading modal first
        showLoadingModal();

        // Fetch operator details using absolute URL
        const baseUrl = window.location.origin;
        const apiUrl = `${baseUrl}/api/operator/${operatorId}/details`;
        console.log('API URL:', apiUrl);

        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response URL:', response.url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Operator data received:', data);

                // Close loading modal
                closeLoadingModal();

                if (data.success) {
                    showOperatorDetailsModal(data.operator);
                } else {
                    showErrorModal('Failed to load operator details', 'The server returned an unsuccessful response. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error loading operator details:', error);
                closeLoadingModal();
                showErrorModal('Error Loading Operator Details', error.message || 'An unexpected error occurred. Please check your connection and try again.');
            });
    }

    function showLoadingModal() {
        const modal = document.createElement('div');
        modal.id = 'loadingModal';
        modal.className = 'attendance-modal';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="attendance-modal-container" style="max-width: 400px; padding: 40px; text-align: center;">
                <i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: #667eea; margin-bottom: 20px;"></i>
                <p style="font-size: 16px; color: #2c3e50; margin: 0;">Loading operator details...</p>
                <p style="font-size: 13px; color: #999; margin-top: 10px;">Please wait</p>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function closeLoadingModal() {
        const modal = document.getElementById('loadingModal');
        if (modal) {
            modal.remove();
        }
    }

    function showErrorModal(title, message) {
        const modal = document.createElement('div');
        modal.id = 'errorModal';
        modal.className = 'attendance-modal';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="attendance-modal-container" style="max-width: 500px;">
                <div class="attendance-modal-header" style="background: linear-gradient(135deg, #e74a3b 0%, #d42a1a 100%);">
                    <h3>
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>${title}</span>
                    </h3>
                    <button class="modal-close-btn" onclick="closeErrorModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="attendance-modal-body" style="text-align: center; padding: 40px;">
                    <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: #e74a3b; margin-bottom: 20px;"></i>
                    <p style="font-size: 16px; color: #2c3e50; margin: 0 0 10px 0; font-weight: 600;">${message}</p>
                    <p style="font-size: 13px; color: #999;">Please try refreshing the page or contact support if the issue persists.</p>
                    <button onclick="closeErrorModal()" style="margin-top: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function closeErrorModal() {
        const modal = document.getElementById('errorModal');
        if (modal) {
            modal.remove();
        }
    }

    function showOperatorDetailsModal(operator) {
        console.log('showOperatorDetailsModal called with operator:', operator);

        // Validate operator data
        if (!operator) {
            console.error('No operator data provided');
            showErrorModal('Invalid Data', 'No operator data was provided to display.');
            return;
        }

        if (!operator.user) {
            console.error('Operator missing user data:', operator);
            showErrorModal('Invalid Data', 'The operator data is missing user information.');
            return;
        }

        // Store operator data globally for nested modals
        window.currentOperatorData = operator;

        // Create modal HTML
        const modal = document.createElement('div');
        modal.id = 'operatorDetailsModal';
        modal.className = 'attendance-modal';
        modal.style.display = 'flex';

        // Get basic info for display
        const operatorName = operator.operator_detail ? operator.operator_detail.full_name : (operator.contact_person || operator.user.name);
        const operatorAge = (operator.operator_detail && operator.operator_detail.age && operator.operator_detail.age !== 'N/A') ? operator.operator_detail.age : 'N/A';
        const operatorSex = (operator.operator_detail && operator.operator_detail.sex && operator.operator_detail.sex !== 'N/A') ? operator.operator_detail.sex : 'N/A';
        const operatorContact = operator.phone || 'N/A';
        const operatorEmail = operator.email || operator.user.email;

        const driversHtml = (operator.drivers || []).map((driver, index) => `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${driver.full_name || driver.first_name + ' ' + driver.last_name}</strong></td>
                <td>${driver.license_number}</td>
                <td>${driver.license_type || 'N/A'}</td>
                <td>${driver.phone || 'N/A'}</td>
                <td>
                    <span class="status-badge status-${driver.status}">
                        ${driver.status.charAt(0).toUpperCase() + driver.status.slice(1)}
                    </span>
                </td>
                <td>
                    <button class="btn-view-details-small" onclick="viewDriverDetails(${driver.id})">
                        <i class="fas fa-eye"></i> Details
                    </button>
                </td>
            </tr>
        `).join('');

        const unitsHtml = (operator.units || []).map((unit, index) => `
            <tr>
                <td>${index + 1}</td>
                <td><span style="font-family: monospace; font-weight: 600; color: #667eea;">${unit.user_id}</span></td>
                <td><strong>${unit.plate_no}</strong></td>
                <td>${unit.year_model}</td>
                <td>${unit.franchise_case}</td>
                <td>${unit.lto_or_number}</td>
                <td>${unit.lto_cr_number}</td>
                <td>
                    <span class="status-badge status-${unit.status}">
                        ${unit.status.charAt(0).toUpperCase() + unit.status.slice(1)}
                    </span>
                </td>
                <td>
                    <button class="btn-view-details-small" onclick="viewUnitDetails(${unit.id})">
                        <i class="fas fa-eye"></i> Details
                    </button>
                </td>
            </tr>
        `).join('');

        modal.innerHTML = `
            <div class="attendance-modal-container" style="max-width: 1400px;">
                <div class="attendance-modal-header">
                    <h3>
                        <i class="fas fa-user"></i>
                        <span>Cooperative Details - ${operatorName}</span>
                    </h3>
                    <button class="modal-close-btn" onclick="closeOperatorDetailsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="attendance-modal-body">
                    <!-- SECTION 1: Operator Information -->
                    <div class="operator-info-card">
                        <div class="operator-info-header">
                            <h4><i class="fas fa-user-circle"></i> Operator Information</h4>
                            ${operator.operator_detail ? `
                                <button class="btn-view-full-details" onclick="viewFullOperatorDetails()">
                                    <i class="fas fa-info-circle"></i> View Full Details
                                </button>
                            ` : ''}
                        </div>
                        ${operator.operator_detail && operator.operator_detail.profile_photo_url ? `
                            <div style="text-align: center; margin-bottom: 20px;">
                                <img src="${operator.operator_detail.profile_photo_url}" alt="Operator Photo" onclick="viewImageFullscreen('${operator.operator_detail.profile_photo_url}', 'Operator Photo')" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 4px solid #667eea; cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
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

                        <div class="attendance-stats-summary" style="margin-top: 20px;">
                            <div class="stat-card stat-total">
                                <i class="fas fa-id-card"></i>
                                <div>
                                    <span class="stat-number">${(operator.drivers || []).length}</span>
                                    <span class="stat-label">Total Drivers</span>
                                </div>
                            </div>
                            <div class="stat-card stat-present">
                                <i class="fas fa-bus"></i>
                                <div>
                                    <span class="stat-number">${(operator.units || []).length}</span>
                                    <span class="stat-label">Total Units</span>
                                </div>
                            </div>
                            <div class="stat-card stat-absent">
                                <i class="fas fa-toggle-${operator.status === 'active' ? 'on' : 'off'}"></i>
                                <div>
                                    <span class="stat-number">${operator.status.charAt(0).toUpperCase() + operator.status.slice(1)}</span>
                                    <span class="stat-label">Status</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Drivers -->
                    <div class="attendance-details-table-container">
                        <h4><i class="fas fa-id-card"></i> Drivers (${operator.drivers.length})</h4>
                        ${operator.drivers.length > 0 ? `
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
                        <h4><i class="fas fa-bus"></i> Units (${operator.units.length})</h4>
                        ${operator.units.length > 0 ? `
                            <table class="attendance-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Unit User ID</th>
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
                </div>
            </div>
        `;

        console.log('Appending modal to document body...');
        document.body.appendChild(modal);
        console.log('Modal appended successfully. Modal ID:', modal.id);
    }

    function closeOperatorDetailsModal() {
        const modal = document.getElementById('operatorDetailsModal');
        if (modal) {
            modal.remove();
        }
    }

    // Close operator details modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('operatorDetailsModal');
        if (modal && e.target === modal) {
            closeOperatorDetailsModal();
        }
    });

    // NESTED MODAL FUNCTIONS

    // View Full Operator Details
    function viewFullOperatorDetails() {
        const operator = window.currentOperatorData;
        if (!operator) {
            showErrorModal('Error', 'Operator data not available');
            return;
        }

        const detail = operator.operator_detail;
        const dependents = operator.dependents || [];

        const modal = document.createElement('div');
        modal.id = 'fullOperatorDetailsModal';
        modal.className = 'attendance-modal nested-modal';
        modal.style.display = 'flex';
        modal.style.zIndex = '10001';

        const dependentsHtml = dependents.length > 0 ? dependents.map((dep, index) => `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${dep.name}</strong></td>
                <td>${dep.age || 'N/A'}</td>
                <td>${dep.relation || 'N/A'}</td>
            </tr>
        `).join('') : '<tr><td colspan="4" style="text-align: center; padding: 20px;">No dependents registered</td></tr>';

        modal.innerHTML = `
            <div class="attendance-modal-container" style="max-width: 900px;">
                <div class="attendance-modal-header">
                    <h3>
                        <i class="fas fa-user-circle"></i>
                        <span>Complete Operator Information</span>
                    </h3>
                    <button class="modal-close-btn" onclick="closeFullOperatorDetailsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="attendance-modal-body">
                    ${detail && detail.profile_photo_url ? `
                        <div style="text-align: center; margin-bottom: 25px;">
                            <img src="${detail.profile_photo_url}" alt="Profile Photo" onclick="viewImageFullscreen('${detail.profile_photo_url}', 'Profile Photo')" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #667eea; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                    ` : ''}

                    <div class="full-details-section">
                        <h4><i class="fas fa-user"></i> Personal Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Full Name:</span>
                                <span class="detail-value">${detail ? detail.full_name : operator.user.name}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Age:</span>
                                <span class="detail-value">${detail ? detail.age : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Sex:</span>
                                <span class="detail-value">${detail ? detail.sex : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Birthdate:</span>
                                <span class="detail-value">${detail ? detail.birthdate : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Birthplace:</span>
                                <span class="detail-value">${detail ? detail.birthplace : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Civil Status:</span>
                                <span class="detail-value">${detail ? detail.civil_status : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Religion:</span>
                                <span class="detail-value">${detail ? detail.religion : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Citizenship:</span>
                                <span class="detail-value">${detail ? detail.citizenship : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Occupation:</span>
                                <span class="detail-value">${detail ? detail.occupation : 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-address-card"></i> Contact Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value">${operator.phone || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">${operator.email || operator.user.email}</span>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <span class="detail-label">Address:</span>
                                <span class="detail-value">${operator.address || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-briefcase"></i> Business Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">User ID:</span>
                                <span class="detail-value" style="font-family: monospace; font-weight: 700; color: #667eea;">${operator.user.user_id}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Contact Person:</span>
                                <span class="detail-value">${operator.contact_person || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Business Permit No:</span>
                                <span class="detail-value">${operator.business_permit_no ? `<span style="font-family: monospace; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; color: #2e7d32; font-weight: 600;">${operator.business_permit_no}</span>` : '<span style="color: #999; font-style: italic;">Not provided</span>'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge status-${operator.status}">
                                        ${operator.status.charAt(0).toUpperCase() + operator.status.slice(1)}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-id-card"></i> Identification</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">ID Type:</span>
                                <span class="detail-value">${detail ? detail.id_type : 'N/A'}</span> 
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">ID Number:</span>
                                <span class="detail-value">${detail ? detail.id_number : 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-check-circle"></i> Additional Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Indigenous People:</span>
                                <span class="detail-value">${detail ? detail.indigenous_people : 'No'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">PWD:</span>
                                <span class="detail-value">${detail ? detail.pwd : 'No'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Senior Citizen:</span>
                                <span class="detail-value">${detail ? detail.senior_citizen : 'No'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">4Ps Beneficiary:</span>
                                <span class="detail-value">${detail ? detail.fourps_beneficiary : 'No'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-users"></i> Dependents (${dependents.length})</h4>
                        <table class="attendance-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Relation</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${dependentsHtml}
                            </tbody>
                        </table>
                    </div>

                    ${detail && (detail.valid_id_url || operator.membership_form_url) ? `
                        <div class="full-details-section">
                            <h4><i class="fas fa-images"></i> Documents</h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 15px;">
                                ${detail && detail.valid_id_url ? `
                                    <div style="text-align: center;">
                                        <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-id-card"></i> Valid ID</h5>
                                        <img src="${detail.valid_id_url}" alt="Valid ID" onclick="viewImageFullscreen('${detail.valid_id_url}', 'Valid ID')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                ` : ''}
                                ${operator.membership_form_url ? `
                                    <div style="text-align: center;">
                                        <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-file-alt"></i> Membership Application Form</h5>
                                        <img src="${operator.membership_form_url}" alt="Membership Form" onclick="viewImageFullscreen('${operator.membership_form_url}', 'Membership Application Form')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
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

    function closeFullOperatorDetailsModal() {
        const modal = document.getElementById('fullOperatorDetailsModal');
        if (modal) {
            modal.remove();
        }
    }

    // View Driver Details
    function viewDriverDetails(driverId) {
        const operator = window.currentOperatorData;
        if (!operator) return;

        const driver = operator.drivers.find(d => d.id === driverId);
        if (!driver) {
            showErrorModal('Error', 'Driver not found');
            return;
        }

        const modal = document.createElement('div');
        modal.id = 'driverDetailsModal';
        modal.className = 'attendance-modal nested-modal';
        modal.style.display = 'flex';
        modal.style.zIndex = '10001';

        modal.innerHTML = `
            <div class="attendance-modal-container" style="max-width: 900px;">
                <div class="attendance-modal-header">
                    <h3>
                        <i class="fas fa-id-card"></i>
                        <span>Driver Details - ${driver.full_name || driver.first_name + ' ' + driver.last_name}</span>
                    </h3>
                    <button class="modal-close-btn" onclick="closeDriverDetailsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="attendance-modal-body">
                    ${driver.photo_url ? `
                        <div style="text-align: center; margin-bottom: 25px;">
                            <h4 style="margin-bottom: 15px;"><i class="fas fa-camera"></i> Driver Photo</h4>
                            <img src="${driver.photo_url}" alt="Driver Photo" onclick="viewImageFullscreen('${driver.photo_url}', 'Driver Photo')" style="max-width: 250px; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                    ` : ''}

                    <div class="full-details-section">
                        <h4><i class="fas fa-user"></i> Personal Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Full Name:</span>
                                <span class="detail-value">${driver.full_name || driver.first_name + ' ' + driver.last_name}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Age:</span>
                                <span class="detail-value">${calculateDriverAge(driver.birthdate)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Sex:</span>
                                <span class="detail-value">${formatDriverSex(driver.sex)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Date of Birth:</span>
                                <span class="detail-value">${formatDriverBirthdate(driver.birthdate)}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-address-card"></i> Contact Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value">${driver.phone || 'N/A'}</span>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <span class="detail-label">Address:</span>
                                <span class="detail-value">${driver.address || 'N/A'}</span>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <span class="detail-label">Emergency Contact:</span>
                                <span class="detail-value">${driver.emergency_contact || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-car"></i> License Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">License Number:</span>
                                <span class="detail-value">${driver.license_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">License Expiry:</span>
                                <span class="detail-value">${driver.license_expiry || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge status-${driver.status}">
                                        ${driver.status.charAt(0).toUpperCase() + driver.status.slice(1)}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-calendar"></i> Employment Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Date Approved:</span>
                                <span class="detail-value">${driver.approved_at || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    ${driver.biodata_photo_url || driver.license_photo_url ? `
                        <div class="full-details-section">
                            <h4><i class="fas fa-images"></i> Documents</h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 15px;">
                                ${driver.biodata_photo_url ? `
                                    <div style="text-align: center;">
                                        <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-id-card"></i> Biodata</h5>
                                        <img src="${driver.biodata_photo_url}" alt="Biodata" onclick="viewImageFullscreen('${driver.biodata_photo_url}', 'Driver Biodata')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                ` : ''}
                                ${driver.license_photo_url ? `
                                    <div style="text-align: center;">
                                        <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-id-card-alt"></i> License</h5>
                                        <img src="${driver.license_photo_url}" alt="License" onclick="viewImageFullscreen('${driver.license_photo_url}', 'Driver License')" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
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

    function closeDriverDetailsModal() {
        const modal = document.getElementById('driverDetailsModal');
        if (modal) {
            modal.remove();
        }
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
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <span class="detail-label">Chassis Number:</span>
                                <span class="detail-value">${unit.chassis_number || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-file-alt"></i> LTO Documents</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">LTO CR Number:</span>
                                <span class="detail-value">${unit.lto_cr_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">LTO CR Date Issued:</span>
                                <span class="detail-value">${unit.lto_cr_date_issued || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">LTO OR Number:</span>
                                <span class="detail-value">${unit.lto_or_number || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">LTO OR Date Issued:</span>
                                <span class="detail-value">${unit.lto_or_date_issued || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-certificate"></i> Registration Details</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">CR Validity:</span>
                                <span class="detail-value">${unit.lto_cr_date_issued || 'N/A'}</span>
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

    // Fullscreen Image Viewer
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
            <button onclick="this.parentElement.remove()" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: none; color: white; font-size: 30px; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.3s;">
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

    // Close nested modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('nested-modal')) {
            e.target.remove();
        }
    });

    // Search/Filter Operators Function
    function searchOperators() {
        const input = document.getElementById('operatorSearchInput');
        const filter = input.value.toLowerCase().trim();
        const table = document.querySelector('.operators-table tbody');
        const rows = table.getElementsByTagName('tr');
        let visibleCount = 0;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];

            // Skip the empty state row
            if (row.querySelector('.empty-state-table')) {
                continue;
            }

            // Get all searchable content from the row
            const userId = row.cells[0]?.textContent || '';
            const operatorName = row.cells[1]?.textContent || '';
            const email = row.cells[2]?.textContent || '';
            const contact = row.cells[3]?.textContent || '';

            // Combine all searchable fields
            const searchableText = (userId + ' ' + operatorName + ' ' + email + ' ' + contact).toLowerCase();

            // Show/hide row based on search match
            if (searchableText.includes(filter)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }

        // Update result count display
        const resultCountDiv = document.getElementById('searchResultCount');
        if (filter) {
            resultCountDiv.textContent = `Showing ${visibleCount} of {{ $operators->total() }} operators`;
            resultCountDiv.style.color = visibleCount > 0 ? '#28a745' : '#dc3545';
        } else {
            resultCountDiv.textContent = '';
        }
    }
</script>
@endsection
