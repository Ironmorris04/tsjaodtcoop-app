@extends('layouts.app')

@section('title', 'Pending Registrations')

@section('page-title', 'Pending Registrations')

@push('styles')
<style>
    .registrations-container {
        padding: 20px;
    }

    .registrations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }

    .header-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        color: #1f2937;
        font-size: 28px;
        font-weight: 700;
    }

    .header-title i {
        color: #667eea;
        font-size: 32px;
    }

    .total-pending-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 10px 24px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Modern Tab Styling */
    .modern-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
        border-bottom: none;
        background: #f9fafb;
        padding: 8px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .tab-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 24px;
        border: none;
        background: transparent;
        color: #6b7280;
        font-weight: 600;
        font-size: 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .tab-btn:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        transform: translateY(-2px);
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transform: translateY(-2px);
    }

    .tab-btn i {
        font-size: 18px;
    }

    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.3);
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        min-width: 24px;
    }

    .tab-btn.active .tab-badge {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .tab-btn:not(.active) .tab-badge {
        background: #667eea;
        color: white;
    }

    /* Tab Content */
    .tab-content-wrapper {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .tab-content-wrapper.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .registrations-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .registrations-table {
        width: 100%;
        border-collapse: collapse;
    }

    .registrations-table thead {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .registrations-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .registrations-table td {
        padding: 16px;
        border-bottom: 1px solid #e9ecef;
    }

    .registrations-table tbody tr {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .registrations-table tbody tr:hover {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .applicant-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .applicant-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .applicant-details h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
    }

    .applicant-details p {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: #6b7280;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border: 1px solid #fbbf24;
    }

    .status-badge i {
        font-size: 10px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .btn-review {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .btn-review:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        color: white;
        text-decoration: none;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 72px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #1f2937;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 15px;
    }

    .date-text {
        color: #6b7280;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .date-text i {
        color: #667eea;
    }

    /* Custom Pagination Styles */
    .custom-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding: 20px 30px;
        border-top: 2px solid #e5e7eb;
    }

    .pagination-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 8px;
    }

    .pagination-item {
        display: inline-block;
    }

    .pagination-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #ffffff;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        min-width: 44px;
        justify-content: center;
    }

    .pagination-link:hover {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .pagination-item.active .pagination-link {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-color: #667eea;
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .pagination-item.disabled .pagination-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination-item.disabled .pagination-link:hover {
        transform: none;
        box-shadow: none;
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
    }

    .pagination-text {
        font-size: 14px;
    }

    .pagination-link i {
        font-size: 12px;
    }

    .pagination-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination-info i {
        color: #667eea;
    }

    @media (max-width: 768px) {
        .registrations-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-title {
            font-size: 22px;
        }

        .modern-tabs {
            flex-direction: column;
            gap: 8px;
        }

        .tab-btn {
            padding: 14px 20px;
        }

        .registrations-table {
            font-size: 12px;
        }

        .registrations-table th,
        .registrations-table td {
            padding: 12px 8px;
        }

        .applicant-avatar {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .custom-pagination {
            flex-direction: column;
            gap: 16px;
            padding: 20px;
        }

        .pagination-text {
            display: none;
        }

        .pagination-link {
            padding: 10px 12px;
            min-width: 40px;
        }

        .pagination-info {
            font-size: 13px;
        }
    }
</style>
@endpush

@section('content')
<div class="registrations-container">
    <div class="registrations-header">
        <h1 class="header-title">
            <i class="fas fa-user-clock"></i> Pending Approvals
        </h1>
        <div class="total-pending-badge">
            <i class="fas fa-tasks"></i>
            <span>{{ $pendingRegistrations->total() + $pendingDrivers->total() + $pendingUnits->total() }} Total</span>
        </div>
    </div>

    <!-- Modern Tabs Navigation -->
    <div class="modern-tabs">
        <button class="tab-btn active" onclick="switchTab('operators')">
            <i class="fas fa-building"></i>
            <span>Operators</span>
            @if($pendingRegistrations->total() > 0)
                <span class="tab-badge">{{ $pendingRegistrations->total() }}</span>
            @endif
        </button>
        <button class="tab-btn" onclick="switchTab('drivers')">
            <i class="fas fa-id-card"></i>
            <span>Drivers</span>
            @if($pendingDrivers->total() > 0)
                <span class="tab-badge">{{ $pendingDrivers->total() }}</span>
            @endif
        </button>
        <button class="tab-btn" onclick="switchTab('units')">
            <i class="fas fa-bus"></i>
            <span>Units</span>
            @if($pendingUnits->total() > 0)
                <span class="tab-badge">{{ $pendingUnits->total() }}</span>
            @endif
        </button>
    </div>

    <!-- Operators Tab Content -->
    <div id="operators-content" class="tab-content-wrapper active">
        <div class="registrations-table-card">
        @if($pendingRegistrations->count() > 0)
            <table class="registrations-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Contact Information</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRegistrations as $registration)
                        <tr onclick="window.location.href='{{ route('registrations.show', $registration->id) }}'">
                            <td>
                                <div class="applicant-info">
                                    <div class="applicant-avatar">
                                        {{ strtoupper(substr($registration->contact_person, 0, 1)) }}
                                    </div>
                                    <div class="applicant-details">
                                        <h4>{{ $registration->contact_person }}</h4>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="margin-bottom: 5px;">
                                    <i class="fas fa-envelope" style="color: #4e73df; margin-right: 6px;"></i>
                                    {{ $registration->email }}
                                </div>
                                <div>
                                    <i class="fas fa-phone" style="color: #4e73df; margin-right: 6px;"></i>
                                    {{ $registration->phone }}
                                </div>
                            </td>
                            <td>
                                <div class="date-text">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $registration->created_at->timezone('Asia/Manila')->format('M d, Y') }}
                                </div>
                                <div class="date-text" style="margin-top: 3px;">
                                    <i class="fas fa-clock"></i>
                                    {{ $registration->created_at->timezone('Asia/Manila')->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge pending">
                                    <i class="fas fa-circle"></i>
                                    Pending Review
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('registrations.show', $registration->id) }}"
                                   class="btn-review"
                                   onclick="event.stopPropagation()">
                                    <i class="fas fa-eye"></i>
                                    Review Application
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Pending Registrations</h3>
                <p>All operator registrations have been reviewed.</p>
            </div>
        @endif
        </div>

        @if($pendingRegistrations->hasPages())
            @include('vendor.pagination.custom', ['paginator' => $pendingRegistrations])
        @endif
    </div>

    <!-- Drivers Tab Content -->
    <div id="drivers-content" class="tab-content-wrapper">
        <div class="registrations-table-card">
        @if($pendingDrivers->count() > 0)
            <table class="registrations-table">
                <thead>
                    <tr>
                        <th>Driver Name</th>
                        <th>Operator</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingDrivers as $driver)
                        <tr onclick="window.location.href='{{ route('registrations.drivers.show', $driver->id) }}'">
                            <td>
                                <div class="applicant-info">
                                    <div class="applicant-avatar">
                                        {{ strtoupper(substr($driver->first_name, 0, 1)) }}
                                    </div>
                                    <div class="applicant-details">
                                        <h4>{{ $driver->full_name }}</h4>
                                        <p>{{ $driver->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $driver->operator->contact_person }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="date-text">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $driver->created_at->timezone('Asia/Manila')->format('M d, Y') }}
                                </div>
                                <div class="date-text" style="margin-top: 3px;">
                                    <i class="fas fa-clock"></i>
                                    {{ $driver->created_at->timezone('Asia/Manila')->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge pending">
                                    <i class="fas fa-circle"></i>
                                    Pending Review
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('registrations.drivers.show', $driver->id) }}"
                                   class="btn-review"
                                   onclick="event.stopPropagation()">
                                    <i class="fas fa-eye"></i>
                                    Review Application
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Pending Drivers</h3>
                <p>All driver applications have been reviewed.</p>
            </div>
        @endif
        </div>

        @if($pendingDrivers->hasPages())
            @include('vendor.pagination.custom', ['paginator' => $pendingDrivers])
        @endif
    </div>

    <!-- Units Tab Content -->
    <div id="units-content" class="tab-content-wrapper">
        <div class="registrations-table-card">
        @if($pendingUnits->count() > 0)
            <table class="registrations-table">
                <thead>
                    <tr>
                        <th>Plate Number</th>
                        <th>Operator</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUnits as $unit)
                        <tr onclick="window.location.href='{{ route('registrations.units.show', $unit->id) }}'">
                            <td>
                                <div class="applicant-info">
                                    <div class="applicant-avatar" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                        <i class="fas fa-bus"></i>
                                    </div>
                                    <div class="applicant-details">
                                        <h4>{{ $unit->plate_no }}</h4>
                                        <p>{{ $unit->model ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $unit->operator->contact_person }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="date-text">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $unit->created_at->timezone('Asia/Manila')->format('M d, Y') }}
                                </div>
                                <div class="date-text" style="margin-top: 3px;">
                                    <i class="fas fa-clock"></i>
                                    {{ $unit->created_at->timezone('Asia/Manila')->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge pending">
                                    <i class="fas fa-circle"></i>
                                    Pending Review
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('registrations.units.show', $unit->id) }}"
                                   class="btn-review"
                                   onclick="event.stopPropagation()">
                                    <i class="fas fa-eye"></i>
                                    Review Application
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Pending Units</h3>
                <p>All unit applications have been reviewed.</p>
            </div>
        @endif
        </div>

        @if($pendingUnits->hasPages())
            @include('vendor.pagination.custom', ['paginator' => $pendingUnits])
        @endif
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Remove active class from all tabs and content
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content-wrapper').forEach(content => content.classList.remove('active'));

        // Add active class to selected tab and content
        event.currentTarget.classList.add('active');
        document.getElementById(tabName + '-content').classList.add('active');

        // Update URL with active tab (optional, for maintaining state on refresh)
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    }

    // On page load, check if there's a tab parameter in URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');

        if (activeTab) {
            // Remove active from all
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content-wrapper').forEach(content => content.classList.remove('active'));

            // Activate the specific tab
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabNames = ['operators', 'drivers', 'units'];
            const tabIndex = tabNames.indexOf(activeTab);

            if (tabIndex !== -1) {
                tabButtons[tabIndex].classList.add('active');
                document.getElementById(activeTab + '-content').classList.add('active');
            }
        }
    });
</script>
@endsection
