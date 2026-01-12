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

    .btn-take-attendance {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-take-attendance:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 167, 81, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 64px;
        opacity: 0.3;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        margin-bottom: 10px;
        color: #495057;
    }

    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
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
        color: #495057;
    }

    .required {
        color: #f5576c;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(102, 126, 234, 0.5);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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

    .attendance-excused {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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

    /* Responsive design */
    @media (max-width: 768px) {
        .attendance-header {
            padding: 20px;
        }

        .attendance-header h2 {
            font-size: 22px;
        }

        .btn-add-meeting {
            padding: 10px 20px;
            font-size: 14px;
        }

        .meetings-table th,
        .meetings-table td {
            padding: 10px;
            font-size: 12px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .meeting-info-grid {
            grid-template-columns: 1fr;
        }

        .modal-container {
            width: 95%;
            max-height: 95vh;
        }

        .modal-body {
            padding: 20px;
        }
    }

    /* Table improvements */
    .meetings-table tbody tr:last-child {
        border-bottom: none;
    }

    .meetings-table td:first-child {
        font-weight: 600;
        color: #667eea;
    }

    /* Loading state */
    .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .loading-spinner i {
        font-size: 40px;
        color: #667eea;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="attendance-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2><i class="fas fa-calendar-check"></i> Meeting Management</h2>
            <p>Create meetings and take attendance</p>
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
                            $now = \Carbon\Carbon::now();
                            $meetingDateTime = \Carbon\Carbon::parse($meeting->meeting_date->format('Y-m-d') . ' ' . $meeting->end_time);
                        @endphp

                        @if($meeting->status === 'completed')
                            <span class="badge badge-done">Done</span>
                        @elseif($hasAttendance && $meeting->status === 'ongoing')
                            <span class="badge badge-ongoing">Ongoing</span>
                        @elseif($now->gt($meetingDateTime))
                            <span class="badge badge-done">Done</span>
                        @else
                            <span class="badge badge-scheduled">Scheduled</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            @php
                                $hasAttendance = $meeting->meetingAttendances()->exists();
                                $now = \Carbon\Carbon::now();
                                $meetingDateTime = \Carbon\Carbon::parse($meeting->meeting_date->format('Y-m-d') . ' ' . $meeting->end_time);
                                $isPastMeeting = $now->gt($meetingDateTime);
                            @endphp

                            @if($meeting->status === 'completed' || $isPastMeeting)
                                <button type="button" class="btn-action btn-view" onclick="openViewModal({{ $meeting->id }})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            @else
                                @if($hasAttendance && $meeting->status === 'ongoing')
                                    <button type="button" class="btn-action btn-view" onclick="openViewModal({{ $meeting->id }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                @else
                                    <a href="{{ route('meetings.take-attendance', $meeting) }}" class="btn-action btn-take-attendance">
                                        <i class="fas fa-clipboard-check"></i> Take Attendance
                                    </a>
                                @endif
                                <button type="button" class="btn-action btn-edit" onclick="editMeeting({{ $meeting->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            @endif
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

        <!-- View Meeting Modals -->
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
                                    $now = \Carbon\Carbon::now();
                                    $meetingDateTime = \Carbon\Carbon::parse($meeting->meeting_date->format('Y-m-d') . ' ' . $meeting->end_time);
                                @endphp
                                @if($meeting->status === 'completed')
                                    Done
                                @elseif($hasAttendance && $meeting->status === 'ongoing')
                                    Ongoing
                                @elseif($now->gt($meetingDateTime))
                                    Done
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
                                        <td><strong>{{ $attendance->operator->full_name ?? $attendance->operator->user->name ?? 'N/A' }}</strong></td>
                                        <td>{{ $attendance->operator->business_name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="attendance-badge attendance-{{ $attendance->status }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->status === 'absent')
                                                @if($attendance->penalty)
                                                    @if($attendance->penalty->status === 'paid')
                                                        <span style="color: #28a745; font-weight: 600;">Paid</span>
                                                    @elseif($attendance->penalty->status === 'partial')
                                                        <span style="color: #ffc107; font-weight: 600;">Partial</span>
                                                    @else
                                                        <span style="color: #dc3545; font-weight: 600;">Unpaid</span>
                                                    @endif
                                                @else
                                                    <span style="color: #6c757d; font-weight: 600;">No Penalty</span>
                                                @endif
                                            @else
                                                {{ $attendance->remarks ?? '-' }}
                                            @endif
                                        </td>
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

<!-- Add Meeting Modal -->
<div class="modal-overlay" id="meetingModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle"><i class="fas fa-calendar-plus"></i> Add New Meeting</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="meetingForm">
                @csrf
                <input type="hidden" id="meetingId" name="meetingId" value="">
                <input type="hidden" id="formMethod" name="_method" value="POST">

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

                <!-- Location -->
                <div class="form-group">
                    <label for="location">Location <span class="required">*</span></label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="e.g., Cooperative Main Hall" required>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Address <span class="required">*</span></label>
                    <textarea id="address" name="address" class="form-control" rows="2" placeholder="Full address of the meeting venue" required></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> Create Meeting
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('meeting_date').setAttribute('min', today);
    });

    function openAddModal() {
        document.getElementById('meetingForm').reset();
        document.getElementById('meetingId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-plus"></i> Add New Meeting';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Create Meeting';
        document.getElementById('meetingModal').classList.add('active');
        document.querySelector('.type-btn.active')?.classList.remove('active');
        document.querySelector('.type-btn[data-type="general_assembly"]').classList.add('active');
        document.getElementById('meetingType').value = 'general_assembly';
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

                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Meeting';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Update Meeting';
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('meetingModal').classList.add('active');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to load meeting details');
        }
    }

    function closeModal() {
        document.getElementById('meetingModal').classList.remove('active');
    }

    function setMeetingType(type) {
        document.querySelectorAll('.type-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.type-btn[data-type="${type}"]`)?.classList.add('active');
        document.getElementById('meetingType').value = type;
    }

    // Close modal when clicking outside
    document.getElementById('meetingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Handle form submission
    document.getElementById('meetingForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const meetingId = document.getElementById('meetingId').value;
        const method = document.getElementById('formMethod').value;
        const isEdit = meetingId && method === 'PUT';

        submitBtn.disabled = true;
        submitBtn.innerHTML = isEdit ? '<i class="fas fa-spinner fa-spin"></i> Updating...' : '<i class="fas fa-spinner fa-spin"></i> Creating...';

        const formData = new FormData(this);

        try {
            const url = isEdit ? `${window.location.origin}/meetings/${meetingId}` : '{{ route('president.meetings.store') }}';
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alert(isEdit ? 'Meeting updated successfully!' : 'Meeting created successfully!');
                closeModal();
                window.location.reload();
            } else if (response.status === 422) {
                let errorMessage = 'Validation errors:\n';
                if (data.errors) {
                    for (let field in data.errors) {
                        errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                    }
                } else {
                    errorMessage = data.message || 'Validation failed';
                }
                console.error('Validation errors:', data.errors);
                alert(errorMessage);
            } else {
                alert(data.message || (isEdit ? 'Error updating meeting. Please try again.' : 'Error creating meeting. Please try again.'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = isEdit ? '<i class="fas fa-save"></i> Update Meeting' : '<i class="fas fa-save"></i> Create Meeting';
        }
    });

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

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
        }
    });
</script>
@endpush