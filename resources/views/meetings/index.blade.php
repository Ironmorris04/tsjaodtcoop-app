@extends('layouts.app')

@section('title', 'Meeting Management')

@section('page-title', 'Meeting Management')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Home</a></li>
    <li>Meeting Management</li>
@endsection

@push('styles')
<style>
    .attendance-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .attendance-header h2 {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: 600;
    }

    .attendance-header p {
        margin: 0;
        opacity: 0.9;
    }

    .btn-add-meeting {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
    }

    .btn-add-meeting:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(245, 87, 108, 0.5);
    }

    .btn-add-meeting i {
        margin-right: 8px;
    }

    .meetings-table-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .meetings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .meetings-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .meetings-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meetings-table th:first-child {
        border-top-left-radius: 8px;
    }

    .meetings-table th:last-child {
        border-top-right-radius: 8px;
    }

    .meetings-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .meetings-table tbody tr:hover {
        background: #f8f9ff;
        transform: scale(1.01);
    }

    .meetings-table td {
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

    .badge-general {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .badge-board {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .badge-scheduled {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .badge-done {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }

    .badge-ongoing {
        background: linear-gradient(135deg, #f5af19 0%, #f12711 100%);
        color: white;
    }

    .badge-cancelled {
        background: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%);
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
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-edit {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(250, 112, 154, 0.4);
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
        max-width: 650px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    /* Wider modal for view meetings with attendance table */
    #viewMeetingModal .modal-container {
        max-width: 1200px;
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

    .meeting-type-toggle {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        background: #f8f9fa;
        padding: 5px;
        border-radius: 10px;
    }

    .type-btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        background: transparent;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #6c757d;
    }

    .type-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #343a40;
        font-size: 14px;
    }

    .form-group label .required {
        color: #f5576c;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel {
        padding: 12px 25px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-submit {
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(102, 126, 234, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-state h3 {
        margin-bottom: 10px;
        color: #495057;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding: 20px 0;
    }

    .pagination a,
    .pagination span {
        padding: 8px 15px;
        border-radius: 6px;
        color: #667eea;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
    }

    .pagination .active span {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .pagination .disabled span {
        color: #adb5bd;
        cursor: not-allowed;
    }

    /* View Modal Styles */
    .view-modal-body {
        padding: 0;
    }

    .meeting-detail-section {
        padding: 25px 30px;
        border-bottom: 1px solid #e9ecef;
    }

    .meeting-detail-section:last-child {
        border-bottom: none;
    }

    .meeting-detail-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .meeting-detail-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .meeting-detail-title h4 {
        margin: 0 0 5px 0;
        font-size: 20px;
        font-weight: 700;
        color: #343a40;
    }

    .meeting-detail-title p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
    }

    .detail-item {
        display: flex;
        align-items: start;
        gap: 12px;
    }

    .detail-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 18px;
        flex-shrink: 0;
    }

    .detail-item-content {
        flex: 1;
    }

    .detail-item-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .detail-item-value {
        font-size: 15px;
        color: #343a40;
        font-weight: 500;
    }

    .meeting-description-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .meeting-description-box p {
        margin: 0;
        color: #495057;
        line-height: 1.6;
    }

    .meeting-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .meeting-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .status-scheduled {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .status-ongoing {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .status-completed {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }

    /* Attendance Table Styles */
    .attendance-table-container {
        margin-top: 20px;
        overflow-x: auto;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .attendance-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .attendance-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .attendance-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .attendance-table tbody tr:hover {
        background: #f8f9ff;
    }

    .attendance-table td {
        padding: 12px;
        color: #495057;
    }

    .attendance-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
    }

    .status-present {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .status-absent {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .payment-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
    }

    .payment-paid {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .payment-unpaid {
        background: linear-gradient(135deg, #f5576c 0%, #fa709a 100%);
        color: white;
    }

    .payment-partial {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .no-attendance-message {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
        background: #f8f9fa;
        border-radius: 8px;
        margin-top: 20px;
    }

    .no-attendance-message i {
        font-size: 48px;
        opacity: 0.3;
        margin-bottom: 15px;
    }

    .no-attendance-message h5 {
        margin-bottom: 5px;
        color: #495057;
    }

    /* View Meeting Modal Styles */
    .meeting-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-item label {
        display: block;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
        font-size: 12px;
        text-transform: uppercase;
    }

    .info-item .value {
        color: #495057;
        font-size: 14px;
        font-weight: 500;
    }

    .attendance-list {
        margin-top: 20px;
    }

    .attendance-list h4 {
        margin-bottom: 15px;
        color: #495057;
        font-size: 18px;
        font-weight: 600;
    }

    .attendance-table-view {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .attendance-table-view thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .attendance-table-view th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
    }

    .attendance-table-view tbody tr {
        border-bottom: 1px solid #e9ecef;
    }

    .attendance-table-view tbody tr:hover {
        background: #f8f9ff;
    }

    .attendance-table-view td {
        padding: 12px;
        font-size: 14px;
        color: #495057;
    }

    .attendance-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .attendance-present {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .attendance-absent {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .no-attendance {
        text-align: center;
        padding: 40px;
        color: #6c757d;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .no-attendance i {
        font-size: 48px;
        opacity: 0.3;
        margin-bottom: 10px;
    }

    .no-attendance p {
        margin: 10px 0 0 0;
        font-size: 14px;
    }

    /* Scrollbar styling for modal */
    .modal-container::-webkit-scrollbar {
        width: 8px;
    }

    .modal-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .modal-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .modal-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    /* Responsive design updates */
    @media (max-width: 768px) {
        .meeting-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="attendance-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2><i class="fas fa-calendar-check"></i> Meeting Management</h2>
            <p>Schedule and track meeting attendance for your cooperative</p>
        </div>
        <button class="btn-add-meeting" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add New Meeting
        </button>
    </div>
</div>

<div class="meetings-table-container">
    @if($meetings->count() > 0)
        <table class="meetings-table">
            <thead>
                <tr>
                    <th>Meeting Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meetings as $meeting)
                <tr>
                    <td>
                        <strong>{{ $meeting->title }}</strong>
                        @if($meeting->description)
                            <br><small style="color: #6c757d;">{{ Str::limit($meeting->description, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        <i class="far fa-calendar"></i>
                        {{ $meeting->meeting_date->format('M d, Y') }}
                    </td>
                    <td>
                        <i class="far fa-clock"></i>
                        @if($meeting->start_time && $meeting->end_time)
                            {{ date('h:i A', strtotime($meeting->start_time)) }} - {{ date('h:i A', strtotime($meeting->end_time)) }}
                        @else
                            {{ $meeting->meeting_time->format('h:i A') }}
                        @endif
                    </td>
                    <td>{{ $meeting->location }}</td>
                    <td>{{ $meeting->address ?? 'N/A' }}</td>
                    <td>
                        @if($meeting->type === 'general_assembly')
                            <span class="badge badge-general">General Assembly</span>
                        @else
                            <span class="badge badge-board">Board of Directors</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $hasAttendance = $meeting->meetingAttendances()->exists();

                            // Combine meeting date + end time to determine if meeting is done
                            $meetingEnd = null;
                            if($meeting->meeting_date && $meeting->end_time){
                                $meetingEnd = \Carbon\Carbon::parse($meeting->meeting_date->format('Y-m-d') . ' ' . $meeting->end_time);
                            }

                            $isDone = $meeting->status === 'completed' || ($meetingEnd && $meetingEnd->isPast());
                            $isOngoing = !$isDone && $hasAttendance;
                        @endphp

                        @if($isDone)
                            <span class="badge badge-done">Done</span>
                        @elseif($isOngoing)
                            <span class="badge badge-ongoing">Ongoing</span>
                        @else
                            <span class="badge badge-scheduled">Scheduled</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <button type="button" class="btn-action btn-view" onclick="openViewModal({{ $meeting->id }})">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            {{ $meetings->links() }}
        </div>

        <!-- View Meeting Modals (Outside table structure) -->
        @foreach($meetings as $meeting)
        <div class="modal-overlay" id="viewModal{{ $meeting->id }}" style="display: none;">
            <div class="modal-container" style="max-width: 1000px;">
                <div class="modal-header">
                    <h3><i class="fas fa-calendar-alt"></i> Meeting Details</h3>
                    <button class="modal-close" onclick="closeViewModal({{ $meeting->id }})">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="meeting-info-grid">
                        <div class="info-item">
                            <label>Meeting Title</label>
                            <div class="value">{{ $meeting->title }}</div>
                        </div>
                        <div class="info-item">
                            <label>Type</label>
                            <div class="value">
                                @if($meeting->type === 'general_assembly')
                                    General Assembly
                                @else
                                    Board of Directors
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <label>Date</label>
                            <div class="value">{{ $meeting->meeting_date->format('F d, Y') }}</div>
                        </div>
                        <div class="info-item">
                            <label>Time</label>
                            <div class="value">
                                @if($meeting->start_time && $meeting->end_time)
                                    {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}
                                @else
                                    {{ $meeting->meeting_time->format('h:i A') }}
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <label>Location</label>
                            <div class="value">{{ $meeting->location ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <div class="value">
                                @php
                                    $hasAttendance = $meeting->meetingAttendances()->exists();
                                    $isPastDate = $meeting->meeting_date->isPast();
                                @endphp
                                @if($meeting->status === 'completed' || $isPastDate)
                                    Done
                                @elseif($hasAttendance)
                                    Ongoing
                                @else
                                    Scheduled
                                @endif
                            </div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label>Address</label>
                            <div class="value">{{ $meeting->address ?? 'N/A' }}</div>
                        </div>
                        @if($meeting->description)
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <label>Description</label>
                                <div class="value">{{ $meeting->description }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="attendance-list">
                        @php
                            $attendances = $meeting->meetingAttendances;
                            $presentCount = $attendances->where('status', 'present')->count();
                            $absentCount = $attendances->where('status', 'absent')->count();
                        @endphp
                        <h4><i class="fas fa-users"></i> Attendance Records ({{ $presentCount }} Present, {{ $absentCount }} Absent)</h4>
                        <table class="attendance-table-view">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Operator Name</th>
                                    <th>Business Name</th>
                                    <th style="width: 120px;">Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $attendance->operator->full_name ?? 'N/A' }}</strong></td>
                                        <td>
                                            <span class="attendance-badge attendance-{{ $attendance->status }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->remarks ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="no-attendance">
                                            <i class="fas fa-clipboard-list"></i>
                                            <p>No attendance records yet</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h3>No Meetings Scheduled</h3>
            <p>Click the "Add New Meeting" button to schedule your first meeting</p>
        </div>
    @endif
</div>

<!-- Add/Edit Meeting Modal -->
<div class="modal-overlay" id="meetingModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle"><i class="fas fa-calendar-plus"></i> Add New Meeting</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="meetingForm">
                @csrf
                <input type="hidden" id="meetingId" name="meeting_id">
                <input type="hidden" id="formMethod" value="POST">

                <!-- Meeting Type Toggle -->
                <div class="meeting-type-toggle">
                    <button type="button" class="type-btn active" data-type="general_assembly" onclick="setMeetingType('general_assembly')">
                        <i class="fas fa-users"></i> General Assembly
                    </button>
                    <button type="button" class="type-btn" data-type="board_of_directors" onclick="setMeetingType('board_of_directors')">
                        <i class="fas fa-user-tie"></i> Board of Directors
                    </button>
                </div>
                <input type="hidden" id="meetingType" name="type" value="general_assembly">

                <!-- Meeting Title -->
                <div class="form-group">
                    <label for="title">Meeting Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="e.g., Monthly General Assembly" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Brief description of the meeting agenda"></textarea>
                </div>

                <!-- Date and Time -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="meeting_date">Date <span class="required">*</span></label>
                        <input type="date" id="meeting_date" name="meeting_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Time <span class="required">*</span></label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <input type="time" id="start_time" name="start_time" class="form-control" placeholder="Start" required>
                            <input type="time" id="end_time" name="end_time" class="form-control" placeholder="End" required>
                        </div>
                    </div>
                </div>

                <!-- Location and Address -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location <span class="required">*</span></label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="e.g., Room 101, Building A" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address <span class="required">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="e.g., Brgy. San Jose, Tacloban City" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-submit" onclick="submitMeeting()">
                <i class="fas fa-save"></i> Save Meeting
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let editingMeetingId = null;

    function openAddModal() {
        document.getElementById('meetingModal').classList.add('active');
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-plus"></i> Add New Meeting';
        document.getElementById('meetingForm').reset();
        document.getElementById('meetingId').value = '';
        document.getElementById('formMethod').value = 'POST';
        editingMeetingId = null;
        setMeetingType('general_assembly');
    }

    function closeModal() {
        document.getElementById('meetingModal').classList.remove('active');
    }

    function setMeetingType(type) {
        document.getElementById('meetingType').value = type;
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.type === type) {
                btn.classList.add('active');
            }
        });
    }

    async function submitMeeting() {
        const form = document.getElementById('meetingForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        const baseUrl = window.location.origin;
        const url = editingMeetingId
            ? `${baseUrl}/meetings/${editingMeetingId}`
            : '{{ route("meetings.store") }}';

        const method = editingMeetingId ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                closeModal();
                window.location.reload();
            } else {
                alert('Error: ' + (result.message || 'Failed to save meeting'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while saving the meeting');
        }
    }

    // Open View Modal
    function openViewModal(meetingId) {
        const modal = document.getElementById('viewModal' + meetingId);
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    // Close View Modal
    function closeViewModal(meetingId) {
        const modal = document.getElementById('viewModal' + meetingId);
        if (modal) {
            modal.style.display = 'none';
        }
    }

    async function editMeeting(id) {
        try {
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/meetings/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            const result = await response.json();

            if (result.success) {
                const meeting = result.meeting;

                document.getElementById('meetingId').value = meeting.id;
                document.getElementById('title').value = meeting.title;
                document.getElementById('description').value = meeting.description || '';
                document.getElementById('meeting_date').value = meeting.meeting_date;
                document.getElementById('start_time').value = meeting.start_time || '';
                document.getElementById('end_time').value = meeting.end_time || '';
                document.getElementById('location').value = meeting.location;
                document.getElementById('address').value = meeting.address || '';

                setMeetingType(meeting.type);

                editingMeetingId = id;
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Meeting';
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('meetingModal').classList.add('active');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to load meeting details');
        }
    }


    async function deleteMeeting(id) {
        if (!confirm('Are you sure you want to delete this meeting?')) {
            return;
        }

        try {
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/meetings/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Error: Failed to delete meeting');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting the meeting');
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Close modal when clicking outside
        const meetingModal = document.getElementById('meetingModal');
        if (meetingModal) {
            meetingModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }

        // Set minimum date to today
        const meetingDateInput = document.getElementById('meeting_date');
        if (meetingDateInput) {
            meetingDateInput.min = new Date().toISOString().split('T')[0];
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
        }
    });
</script>
@endpush
