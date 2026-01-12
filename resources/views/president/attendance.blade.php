@extends('layouts.app')

@section('title', 'Meeting Attendance')

@section('page-title', 'Meeting Attendance Records')

@section('content')
<div class="attendance-page-container">

    <!-- Past Meetings Attendance List -->
    <div class="attendance-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-history"></i>
                Past Meeting Attendance Records
            </h2>
            <button onclick="checkForUpcomingMeeting()" class="btn-take-attendance">
                <i class="fas fa-clipboard-check"></i>
                Take Attendance
            </button>
        </div>

        <div class="attendance-list">
            @forelse($pastMeetings as $meeting)
                <div class="meeting-list-item" onclick="openAttendanceDetailsModal({{ $meeting->id }})">
                    <div class="meeting-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="meeting-info">
                        <h3 class="meeting-title">{{ $meeting->title }}</h3>
                        @if($meeting->description)
                            <p class="meeting-description">{{ Str::limit($meeting->description, 100) }}</p>
                        @endif
                        <div class="meeting-details">
                            <span class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $meeting->meeting_date ? \Carbon\Carbon::parse($meeting->meeting_date)->format('F d, Y') : 'N/A' }}
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-clock"></i>
                                {{ $meeting->start_time ? \Carbon\Carbon::parse($meeting->start_time, 'UTC')->format('g:i A') : 'N/A' }}
                                - {{ $meeting->end_time ? \Carbon\Carbon::parse($meeting->end_time, 'UTC')->format('g:i A') : 'N/A' }}
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $meeting->location ?? 'N/A' }}
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-building"></i>
                                {{ $meeting->address ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="meeting-stats">
                        <div class="stat-badge present">
                            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $meeting->meetingAttendances->where('status', 'present')->count() }}</div>
                                <div class="stat-label">Present</div>
                            </div>
                        </div>
                        <div class="stat-badge absent">
                            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $meeting->meetingAttendances->where('status', 'absent')->count() }}</div>
                                <div class="stat-label">Absent</div>
                            </div>
                        </div>
                        <div class="stat-badge total">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $meeting->meetingAttendances->count() }}</div>
                                <div class="stat-label">Total</div>
                            </div>
                        </div>
                    </div>
                    <div class="meeting-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>No past meeting records found</p>
                    <small>Attendance records will appear here after meetings</small>
                </div>
            @endforelse
        </div>

        @if($pastMeetings->count() > 0)
            <div class="card-footer">
                Showing {{ $pastMeetings->count() }} meeting{{ $pastMeetings->count() !== 1 ? 's' : '' }}
            </div>
        @endif
    </div>
</div>

<!-- Attendance Details Modal -->
<div id="attendanceDetailsModal" class="attendance-modal" style="display: none;">
    <div class="attendance-modal-container">
        <div class="attendance-modal-header">
            <h3 id="attendanceDetailsTitle">
                <i class="fas fa-clipboard-list"></i>
                <span>Meeting Attendance Details</span>
            </h3>
            <button class="modal-close-btn" onclick="closeAttendanceDetailsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="attendance-modal-body" id="attendanceDetailsContent">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading attendance details...</p>
            </div>
        </div>
    </div>
</div>

<!-- Take Attendance Modal -->
<div id="attendanceModal" class="attendance-modal" style="display: none;">
    <div class="attendance-modal-container">
        <div class="attendance-modal-header">
            <h3><i class="fas fa-clipboard-check"></i> <span id="modalMeetingTitle">Take Attendance</span></h3>
            <button class="modal-close-btn" onclick="closeAttendanceModal()">&times;</button>
        </div>
        <div class="attendance-modal-body" id="attendanceModalContent">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading meeting details...</p>
            </div>
        </div>
    </div>
</div>

<style>
    .attendance-page-container {
        width: 100%;
        height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    .attendance-card {
        background: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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

    .btn-take-attendance {
        background: white;
        color: #667eea;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-take-attendance:hover {
        background: #f8f9fc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-take-attendance i {
        font-size: 16px;
    }

    .attendance-list {
        padding: 0;
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .attendance-list::-webkit-scrollbar {
        width: 8px;
    }

    .attendance-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .attendance-list::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 4px;
    }

    .attendance-list::-webkit-scrollbar-thumb:hover {
        background: #764ba2;
    }

    /* Meeting List Item */
    .meeting-list-item {
        display: flex;
        align-items: center;
        padding: 20px 30px;
        border-bottom: 1px solid #e3e6f0;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .meeting-list-item:hover {
        background: #f8f9fc;
        padding-left: 35px;
    }

    .meeting-list-item:last-child {
        border-bottom: none;
    }

    .meeting-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 26px;
        margin-right: 20px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .meeting-info {
        flex: 1;
        min-width: 0;
    }

    .meeting-title {
        margin: 0 0 5px 0;
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }

    .meeting-description {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #6c757d;
        line-height: 1.5;
    }

    .meeting-details {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #7f8c8d;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-item i {
        font-size: 12px;
        color: #667eea;
    }

    .meeting-stats {
        display: flex;
        gap: 15px;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .stat-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 10px;
        min-width: 90px;
    }

    .stat-badge.present {
        background: #d4edda;
    }

    .stat-badge.absent {
        background: #f8d7da;
    }

    .stat-badge.total {
        background: #d1ecf1;
    }

    .stat-badge .stat-icon {
        font-size: 20px;
    }

    .stat-badge.present .stat-icon {
        color: #155724;
    }

    .stat-badge.absent .stat-icon {
        color: #721c24;
    }

    .stat-badge.total .stat-icon {
        color: #0c5460;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-badge .stat-number {
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
    }

    .stat-badge.present .stat-number {
        color: #155724;
    }

    .stat-badge.absent .stat-number {
        color: #721c24;
    }

    .stat-badge.total .stat-number {
        color: #0c5460;
    }

    .stat-badge .stat-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 2px;
    }

    .stat-badge.present .stat-label {
        color: #155724;
    }

    .stat-badge.absent .stat-label {
        color: #721c24;
    }

    .stat-badge.total .stat-label {
        color: #0c5460;
    }

    .meeting-arrow {
        color: #667eea;
        font-size: 20px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .meeting-list-item:hover .meeting-arrow {
        transform: translateX(5px);
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

    .empty-state small {
        font-size: 14px;
        color: #95a5a6;
    }

    .card-footer {
        padding: 12px 30px;
        background: #f8f9fc;
        border-top: 1px solid #e3e6f0;
        font-size: 13px;
        color: #7f8c8d;
        text-align: center;
        flex-shrink: 0;
    }

    /* Modal Styles */
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

    .loading-spinner {
        text-align: center;
        padding: 60px 20px;
        color: #667eea;
    }

    .loading-spinner i {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .loading-spinner p {
        font-size: 16px;
        color: #6c757d;
    }

    /* Additional modal styles reused from dashboard */
    .meeting-info-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .meeting-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
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

    .attendance-stats-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-card i {
        font-size: 32px;
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

    .stat-card .stat-number {
        display: block;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 5px;
        color: #2c3e50;
    }

    .stat-card .stat-label {
        display: block;
        font-size: 13px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .text-center {
        text-align: center;
    }

    .status-badge-table {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge-table i {
        font-size: 12px;
    }

    .status-badge-table.status-present {
        background: #d4edda;
        color: #155724;
    }

    .status-badge-table.status-absent {
        background: #f8d7da;
        color: #721c24;
    }

    /* Payment Status Badge Styles */
    .payment-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .payment-status-badge i {
        font-size: 12px;
    }

    .payment-status-badge.payment-paid {
        background: #d4edda;
        color: #155724;
    }

    .payment-status-badge.payment-unpaid {
        background: #f8d7da;
        color: #721c24;
    }

    .payment-status-badge.payment-partial {
        background: #fff3cd;
        color: #856404;
    }

    /* Additional Take Attendance Modal Styles */
    .operators-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .operators-section-header h4 {
        margin: 0;
        font-size: 18px;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-actions-btns {
        display: flex;
        gap: 10px;
    }

    .btn-quick {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-all-present {
        background: #1cc88a;
        color: white;
    }

    .btn-all-present:hover {
        background: #13855c;
        transform: translateY(-2px);
    }

    .btn-all-absent {
        background: #e74a3b;
        color: white;
    }

    .btn-all-absent:hover {
        background: #be2617;
        transform: translateY(-2px);
    }

    .operators-grid {
        display: grid;
        gap: 15px;
        margin-bottom: 20px;
    }

    .operator-card {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .operator-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }

    .operator-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .operator-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .operator-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .operator-details h5 {
        margin: 0 0 3px 0;
        font-size: 16px;
        color: #2c3e50;
    }

    .operator-details p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
    }

    .operator-controls {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .status-buttons {
        grid-column: 1 / -1;
        display: flex;
        gap: 10px;
    }

    .status-btn {
        flex: 1;
        padding: 10px 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .status-btn.present {
        border-color: #1cc88a;
        color: #1cc88a;
    }

    .status-btn.present.active {
        background: #1cc88a;
        color: white;
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.3);
    }

    .status-btn.absent {
        border-color: #e74a3b;
        color: #e74a3b;
    }

    .status-btn.absent.active {
        background: #e74a3b;
        color: white;
        box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);
    }

    .remarks-field {
        grid-column: 1 / -1;
    }

    .remarks-field input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .remarks-field input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 2px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
        border-radius: 0 0 15px 15px;
        flex-shrink: 0;
    }

    .attendance-summary-footer {
        display: flex;
        gap: 20px;
        font-size: 14px;
    }

    .summary-item-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
    }

    .summary-item-footer strong {
        color: #2c3e50;
        font-size: 18px;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
    }

    .btn-modal {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel-modal {
        background: #6c757d;
        color: white;
    }

    .btn-cancel-modal:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-submit-attendance {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-submit-attendance:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(102, 126, 234, 0.4);
    }

    @media (max-width: 1024px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 18px 25px;
        }

        .btn-take-attendance {
            align-self: flex-end;
        }
    }

    @media (max-width: 768px) {
        .card-header {
            padding: 15px 20px;
        }

        .card-title {
            font-size: 18px;
        }

        .btn-take-attendance {
            width: 100%;
            justify-content: center;
        }

        .meeting-stats {
            gap: 10px;
        }

        .stat-item {
            padding: 12px 15px;
            min-width: 70px;
        }

        .stat-item .stat-number {
            font-size: 24px;
        }

        .stat-item .stat-label {
            font-size: 11px;
        }

        .attendance-modal-container {
            width: 100%;
            max-height: 100vh;
            border-radius: 0;
        }

        .attendance-modal-header {
            border-radius: 0;
        }

        .modal-footer {
            flex-direction: column;
            gap: 15px;
            border-radius: 0;
        }

        .attendance-summary-footer {
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .modal-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn-modal {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentMeetingId = null;

    function checkForUpcomingMeeting() {
        // Check if there's an upcoming meeting scheduled
        fetch(apiUrl('check-upcoming-meeting'))
            .then(response => response.json())
            .then(data => {
                if (data.hasUpcomingMeeting) {
                    // Load meeting data and open modal
                    currentMeetingId = data.meetingId;
                    loadMeetingAttendance(data.meetingId);
                } else {
                    alert('No upcoming meeting scheduled. Please ask the admin to create a meeting first.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error checking for upcoming meetings. Please try again.');
            });
    }

    function loadMeetingAttendance(meetingId) {
        // Show modal with loading state
        const modal = document.getElementById('attendanceModal');
        modal.style.display = 'flex';

        // Fetch meeting data and operators
        fetch(apiUrl(`meeting/${meetingId}/attendance-data`))
            .then(response => response.json())
            .then(data => {
                renderAttendanceForm(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading meeting data. Please try again.');
                closeAttendanceModal();
            });
    }

    function renderAttendanceForm(data) {
        const { meeting, operators, existingAttendance } = data;

        document.getElementById('modalMeetingTitle').textContent = meeting.title;

        const content = `
            <form id="attendanceModalForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Meeting Info -->
                <div class="meeting-info-section">
                    <h4><i class="fas fa-info-circle"></i> Meeting Information</h4>
                    <div class="meeting-details-grid">
                        <div class="meeting-detail">
                            <i class="fas fa-calendar-alt"></i>
                            <span>${formatDate(meeting.meeting_date)}</span>
                        </div>
                        <div class="meeting-detail">
                            <i class="fas fa-clock"></i>
                            <span>${meeting.start_time} - ${meeting.end_time}</span>
                        </div>
                        <div class="meeting-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${meeting.location}</span>
                        </div>
                        <div class="meeting-detail">
                            <i class="fas fa-building"></i>
                            <span>${meeting.address}</span>
                        </div>
                    </div>
                    ${meeting.description ? `
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.2);">
                        <strong>Description:</strong> ${meeting.description}
                    </div>
                    ` : ''}
                </div>

                <!-- Operators List -->
                <div class="operators-section-header">
                    <h4><i class="fas fa-users"></i> Mark Attendance (${operators.length} Operators)</h4>
                    <div class="quick-actions-btns">
                        <button type="button" class="btn-quick btn-all-present" onclick="markAllStatus('present')">
                            <i class="fas fa-check-circle"></i> All Present
                        </button>
                        <button type="button" class="btn-quick btn-all-absent" onclick="markAllStatus('absent')">
                            <i class="fas fa-times-circle"></i> All Absent
                        </button>
                    </div>
                </div>

                <div class="operators-grid">
                    ${operators.map((operator, index) => {
                        const attendance = existingAttendance[operator.id] || {};
                        const status = attendance.status || 'absent';
                        const remarks = attendance.remarks || '';

                        return `
                        <div class="operator-card">
                            <div class="operator-header">
                                <div class="operator-info">
                                    <div class="operator-number">${index + 1}</div>
                                    <div class="operator-details">
                                        <h5>${operator.full_name || operator.user.name}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="operator-controls">
                                <input type="hidden" name="attendances[${index}][operator_id]" value="${operator.id}">
                                <input type="hidden" name="attendances[${index}][status]" id="status-${index}" value="${status}">

                                <div class="status-buttons">
                                    <button type="button" class="status-btn present ${status === 'present' ? 'active' : ''}" onclick="setStatus(${index}, 'present')">
                                        <i class="fas fa-check-circle"></i> Present
                                    </button>
                                    <button type="button" class="status-btn absent ${status === 'absent' ? 'active' : ''}" onclick="setStatus(${index}, 'absent')">
                                        <i class="fas fa-times-circle"></i> Absent
                                    </button>
                                </div>

                                <div class="remarks-field">
                                    <input type="text" name="attendances[${index}][remarks]" placeholder="Remarks (optional)" value="${remarks}">
                                </div>
                            </div>
                        </div>
                        `;
                    }).join('')}
                </div>

                <div class="modal-footer">
                    <div class="attendance-summary-footer">
                        <div class="summary-item-footer">
                            <i class="fas fa-users"></i>
                            <span><strong id="totalCount">${operators.length}</strong> Total</span>
                        </div>
                        <div class="summary-item-footer">
                            <i class="fas fa-check-circle" style="color: #1cc88a;"></i>
                            <span><strong id="presentCount">0</strong> Present</span>
                        </div>
                        <div class="summary-item-footer">
                            <i class="fas fa-times-circle" style="color: #e74a3b;"></i>
                            <span><strong id="absentCount">${operators.length}</strong> Absent</span>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-modal btn-cancel-modal" onclick="closeAttendanceModal()">
                            Cancel
                        </button>
                        <button type="button" class="btn-modal btn-submit-attendance" onclick="submitAttendance()">
                            <i class="fas fa-save"></i> Save Attendance
                        </button>
                    </div>
                </div>
            </form>
        `;

        document.getElementById('attendanceModalContent').innerHTML = content;
        updateSummary();
    }

    function setStatus(index, status) {
        document.getElementById('status-' + index).value = status;

        const operatorCard = document.querySelectorAll('.operator-card')[index];
        const presentBtn = operatorCard.querySelector('.status-btn.present');
        const absentBtn = operatorCard.querySelector('.status-btn.absent');

        if (status === 'present') {
            presentBtn.classList.add('active');
            absentBtn.classList.remove('active');
        } else {
            absentBtn.classList.add('active');
            presentBtn.classList.remove('active');
        }

        updateSummary();
    }

    function markAllStatus(status) {
        const operatorCards = document.querySelectorAll('.operator-card');
        operatorCards.forEach((card, index) => {
            setStatus(index, status);
        });
    }

    function updateSummary() {
        const statusInputs = document.querySelectorAll('[id^="status-"]');
        let presentCount = 0;
        let absentCount = 0;

        statusInputs.forEach(input => {
            if (input.value === 'present') {
                presentCount++;
            } else {
                absentCount++;
            }
        });

        document.getElementById('presentCount').textContent = presentCount;
        document.getElementById('absentCount').textContent = absentCount;
    }

    function submitAttendance() {
        if (!confirm('Are you sure you want to submit the attendance? This will save the records.')) {
            return;
        }

        const form = document.getElementById('attendanceModalForm');
        const formData = new FormData(form);

        fetch(`/meetings/${currentMeetingId}/submit-attendance`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data && data.success) {
                alert('Attendance recorded successfully!');
                closeAttendanceModal();
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting attendance. Please try again.');
        });
    }

    function closeAttendanceModal() {
        document.getElementById('attendanceModal').style.display = 'none';
        currentMeetingId = null;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('attendanceModal');
        if (e.target === modal) {
            closeAttendanceModal();
        }

        const detailsModal = document.getElementById('attendanceDetailsModal');
        if (e.target === detailsModal) {
            closeAttendanceDetailsModal();
        }
    });

    // Attendance Details Modal Functions
    function openAttendanceDetailsModal(meetingId) {
        const modal = document.getElementById('attendanceDetailsModal');
        const content = document.getElementById('attendanceDetailsContent');

        // Show modal with loading state
        modal.style.display = 'flex';
        content.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading attendance details...</p>
            </div>
        `;

        // Fetch meeting attendance data
        fetch(apiUrl(`meeting/${meetingId}/attendance-data`))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderAttendanceDetails(data.meeting, data.operators, data.existingAttendance);
                } else {
                    content.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Failed to load attendance details</p>
                            <small>Please try again later</small>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error loading attendance details</p>
                        <small>${error.message}</small>
                    </div>
                `;
            });
    }

    function renderAttendanceDetails(meeting, operators, existingAttendance) {
        const content = document.getElementById('attendanceDetailsContent');

        // Update modal title
        document.querySelector('#attendanceDetailsTitle span').textContent = meeting.title;

        // Count present and absent
        let presentCount = 0;
        let absentCount = 0;

        operators.forEach(operator => {
            const attendance = existingAttendance[operator.id];
            if (attendance && attendance.status === 'present') {
                presentCount++;
            } else {
                absentCount++;
            }
        });

        // Format dates
        const meetingDate = new Date(meeting.meeting_date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const startTime = meeting.start_time ? formatTime(meeting.start_time) : 'N/A';
        const endTime = meeting.end_time ? formatTime(meeting.end_time) : 'N/A';

        // Build HTML
        let html = `
            <div class="meeting-info-section">
                <div class="meeting-info-grid">
                    <div class="info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div>
                            <strong>Date</strong>
                            <p>${meetingDate}</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Time</strong>
                            <p>${startTime} - ${endTime}</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Location</strong>
                            <p>${meeting.location || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-building"></i>
                        <div>
                            <strong>Address</strong>
                            <p>${meeting.address || 'N/A'}</p>
                        </div>
                    </div>
                </div>
                ${meeting.description ? `
                    <div class="meeting-description-box">
                        <strong><i class="fas fa-info-circle"></i> Description</strong>
                        <p>${meeting.description}</p>
                    </div>
                ` : ''}

                <div class="attendance-stats-summary">
                    <div class="stat-card stat-total">
                        <i class="fas fa-users"></i>
                        <div>
                            <span class="stat-number">${operators.length}</span>
                            <span class="stat-label">Total Operators</span>
                        </div>
                    </div>
                    <div class="stat-card stat-present">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <span class="stat-number">${presentCount}</span>
                            <span class="stat-label">Present</span>
                        </div>
                    </div>
                    <div class="stat-card stat-absent">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            <span class="stat-number">${absentCount}</span>
                            <span class="stat-label">Absent</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="attendance-details-table-container">
                <h4><i class="fas fa-list"></i> Attendance & Fine Payment Status</h4>
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Operator Name</th>
                            <th>Business Name</th>
                            <th style="width: 120px;">Attendance Status</th>
                            <th style="width: 120px;">Fine Amount</th>
                            <th style="width: 150px;">Payment Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        operators.forEach((operator, index) => {
            const attendance = existingAttendance[operator.id];
            const status = attendance ? attendance.status : 'absent';
            const remarks = attendance && attendance.remarks ? attendance.remarks : '-';
            const operatorName = operator.user ? operator.user.name : 'Unknown';

            // Fine and payment status - will be fetched from backend
            let fineAmount = '-';
            let paymentStatus = '-';

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${operatorName}</strong></td>
                    <td>
                        <span class="status-badge-table status-${status}">
                            <i class="fas fa-${status === 'present' ? 'check' : 'times'}-circle"></i>
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </td>
                    <td class="fine-amount-${operator.id}">${fineAmount}</td>
                    <td class="payment-status-${operator.id}">${paymentStatus}</td>
                    <td>${remarks}</td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        content.innerHTML = html;

        // Fetch penalty/fine data for each operator
        fetchPenaltyData(meeting.id, operators, existingAttendance);
    }

    function fetchPenaltyData(meetingId, operators, existingAttendance) {
        // Fetch the meeting data with penalty information
        fetch(`/meetings/${meetingId}`)
            .then(response => response.json())
            .then(result => {
                if (result.success && result.attendances) {
                    result.attendances.forEach(attendance => {
                        const operatorId = attendance.operator_id;

                        // Update fine amount
                        const fineCell = document.querySelector(`.fine-amount-${operatorId}`);
                        if (fineCell) {
                            if (attendance.penalty && attendance.status === 'absent') {
                                fineCell.innerHTML = `PHP ${parseFloat(attendance.penalty.amount).toFixed(2)}`;
                            } else {
                                fineCell.innerHTML = '-';
                            }
                        }

                        // Update payment status
                        const paymentCell = document.querySelector(`.payment-status-${operatorId}`);
                        if (paymentCell) {
                            if (attendance.penalty && attendance.status === 'absent') {
                                const penalty = attendance.penalty;

                                if (penalty.status === 'paid') {
                                    const paymentDate = penalty.latest_payment_date
                                        ? new Date(penalty.latest_payment_date).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric'
                                        })
                                        : '';
                                    paymentCell.innerHTML = `
                                        <span class="payment-status-badge payment-paid">
                                            <i class="fas fa-check-circle"></i> Paid
                                        </span>
                                        ${paymentDate ? `<br><small style="color: #6c757d;">Paid on ${paymentDate}</small>` : ''}
                                    `;
                                } else if (penalty.status === 'partial') {
                                    paymentCell.innerHTML = `
                                        <span class="payment-status-badge payment-partial">
                                            <i class="fas fa-hourglass-half"></i> Partial
                                        </span>
                                        <br><small style="color: #6c757d;">
                                            PHP ${parseFloat(penalty.paid_amount).toFixed(2)} /
                                            PHP ${parseFloat(penalty.amount).toFixed(2)}
                                        </small>
                                    `;
                                } else {
                                    const dueDate = new Date(penalty.due_date).toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric'
                                    });
                                    paymentCell.innerHTML = `
                                        <span class="payment-status-badge payment-unpaid">
                                            <i class="fas fa-exclamation-circle"></i> Unpaid
                                        </span>
                                        <br><small style="color: #6c757d;">Due: ${dueDate}</small>
                                    `;
                                }
                            } else {
                                paymentCell.innerHTML = '-';
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching penalty data:', error);
            });
    }

    function formatTime(timeString) {
        try {
            const date = new Date(`1970-01-01T${timeString}Z`);
            return date.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        } catch (e) {
            return timeString;
        }
    }

    function closeAttendanceDetailsModal() {
        document.getElementById('attendanceDetailsModal').style.display = 'none';
    }
</script>
@endsection
