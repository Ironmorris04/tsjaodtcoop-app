@extends('layouts.app')

@section('title', 'President Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-container fullscreen">

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
            <div class="stat-card meetings">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number">{{ $totalMeetings ?? 0 }}</div>
                    <div class="stat-label">Total Meetings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Monthly Attendance Chart -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="fas fa-chart-line"></i> Monthly Attendance ({{ now()->year }})</h3>
            </div>
            <div class="chart-card-body">
                <canvas id="monthlyAttendanceChart"></canvas>
            </div>
        </div>

        <!-- Annual Collection Chart -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="fas fa-chart-bar"></i> Annual Collection ({{ now()->year }})</h3>
            </div>
            <div class="chart-card-body">
                <canvas id="annualCollectionChart"></canvas>
            </div>
        </div>
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

<style>
    /* Override the parent content wrapper */
    .content {
        padding: 0 !important;
    }

    .dashboard-container.fullscreen {
        width: 100%;
        min-height: calc(100vh - 130px);
        margin: 0;
        padding: 20px;
        box-sizing: border-box;
        background: #f8f9fc;
        display: flex;
        flex-direction: column;
        gap: 20px;
        overflow-y: auto;
    }

    /* Statistics Section */
    .stats-section {
        flex-shrink: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    /* Charts Section */
    .charts-section {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        flex-shrink: 0;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .chart-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 25px;
        border-bottom: none;
    }

    .chart-card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card-body {
        padding: 25px;
        flex: 1;
    }

    .chart-card-body canvas {
        max-height: 300px;
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

    .stat-card.meetings::before {
        background: linear-gradient(135deg, #f6c23e 0%, #f4a942 100%);
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

    .stat-card.meetings .stat-icon {
        background: linear-gradient(135deg, #f6c23e 0%, #f4a942 100%);
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
        flex-shrink: 0;
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

    .attendance-card {
        background: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
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

    /* Attendance Details Modal */
    .attendance-details-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        z-index: 10001;
        display: none;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .attendance-details-modal.active {
        display: flex;
    }

    .attendance-details-container {
        background: white;
        border-radius: 15px;
        width: 95%;
        max-width: 1200px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    .attendance-details-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px 15px 0 0;
        flex-shrink: 0;
    }

    .attendance-details-header h3 {
        margin: 0 0 15px 0;
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-close-details {
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

    .modal-close-details:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .meeting-info-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        opacity: 0.95;
    }

    .info-detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .info-detail-item i {
        font-size: 14px;
    }

    .attendance-details-body {
        padding: 30px;
        overflow-y: auto;
        flex: 1;
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

    @media (max-width: 1024px) {
        .dashboard-container.fullscreen {
            padding: 15px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .charts-section {
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

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 18px 25px;
        }

        .btn-take-attendance {
            align-self: flex-end;
        }

        .meeting-table-container {
            margin: 15px 20px;
        }

        .meeting-header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .meeting-summary-stats {
            width: 100%;
            justify-content: space-around;
        }

        .stat-item {
            flex: 1;
        }

        .attendance-table {
            font-size: 13px;
        }

        .attendance-table thead th,
        .attendance-table tbody td {
            padding: 12px 15px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container.fullscreen {
            padding: 10px;
        }

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

        .meeting-table-container {
            margin: 15px 15px;
            border-radius: 8px;
        }

        .meeting-header-section {
            padding: 20px;
        }

        .meeting-title-info h3 {
            font-size: 18px;
        }

        .meeting-meta-info {
            flex-direction: column;
            gap: 8px;
        }

        .meeting-summary-stats {
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

        /* Make table responsive with horizontal scroll */
        .attendance-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .attendance-table thead,
        .attendance-table tbody,
        .attendance-table tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .attendance-table thead th,
        .attendance-table tbody td {
            padding: 10px 12px;
            font-size: 12px;
        }

        .status-badge {
            padding: 5px 10px;
            font-size: 11px;
        }

        /* Attendance Details Modal Responsive */
        .meeting-info-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .attendance-stats-summary {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .stat-card {
            padding: 15px;
        }

        .stat-card .stat-number {
            font-size: 24px;
        }

        .attendance-details-table-container {
            padding: 15px;
        }

        .attendance-details-table-container h4 {
            font-size: 16px;
        }
    }
</style>

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

    /* Attendance Details Modal Specific Styles */
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

    .info-item i {
        font-size: 20px;
        margin-top: 3px;
        opacity: 0.9;
    }

    .info-item strong {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        opacity: 0.8;
    }

    .info-item p {
        margin: 0;
        font-size: 15px;
        font-weight: 500;
    }

    .meeting-description-box {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    .meeting-description-box strong {
        display: block;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .meeting-description-box p {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
        opacity: 0.95;
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

    @media (max-width: 768px) {
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

    function viewAttendanceDetail(meetingId) {
        // For now, just show an alert. This will be implemented later
        alert('Viewing attendance details for meeting #' + meetingId + '\n\nThis feature will be implemented soon.');
        // Later: window.location.href = '/meetings/' + meetingId + '/attendance-detail';
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
                <h4><i class="fas fa-list"></i> Attendance Records</h4>
                <table class="attendance-table">
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
        `;

        operators.forEach((operator, index) => {
            const attendance = existingAttendance[operator.id];
            const status = attendance ? attendance.status : 'absent';
            const remarks = attendance && attendance.remarks ? attendance.remarks : '-';
            const penaltyStatus = attendance && attendance.penalty_status ? attendance.penalty_status : null;
            const operatorName = operator.user ? operator.user.name : 'Unknown';

            // Build remarks display with penalty status
            let remarksDisplay = remarks;
            if (status === 'absent' && penaltyStatus) {
                let penaltyBadge = '';
                let penaltyIcon = '';
                let penaltyColor = '';

                if (penaltyStatus === 'paid') {
                    penaltyBadge = 'Fine Paid';
                    penaltyIcon = 'check-circle';
                    penaltyColor = '#27ae60';
                } else if (penaltyStatus === 'partial') {
                    penaltyBadge = 'Fine Partially Paid';
                    penaltyIcon = 'clock';
                    penaltyColor = '#f39c12';
                } else if (penaltyStatus === 'unpaid') {
                    penaltyBadge = 'Fine Unpaid';
                    penaltyIcon = 'exclamation-circle';
                    penaltyColor = '#e74c3c';
                }

                remarksDisplay = `
                    ${remarks !== '-' ? remarks + '<br>' : ''}
                    <span style="display: inline-flex; align-items: center; gap: 5px; background: ${penaltyColor}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-top: 4px;">
                        <i class="fas fa-${penaltyIcon}"></i> ${penaltyBadge}
                    </span>
                `;
            }

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
                    <td>${remarksDisplay}</td>
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

    // Operator Details Modal
    function viewOperatorDetails(operatorId) {
        // Fetch operator details using absolute URL
        const baseUrl = window.location.origin;
        fetch(`${baseUrl}/api/operator/${operatorId}/details`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showOperatorDetailsModal(data.operator);
                } else {
                    alert('Failed to load operator details. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading operator details. Please try again.');
            });
    }

    function showOperatorDetailsModal(operator) {
        // Create modal HTML
        const modal = document.createElement('div');
        modal.id = 'operatorDetailsModal';
        modal.className = 'attendance-modal';
        modal.style.display = 'flex';

        const driversHtml = operator.drivers.map((driver, index) => `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${driver.first_name} ${driver.last_name}</strong></td>
                <td>${driver.license_number}</td>
                <td>${driver.license_type || 'N/A'}</td>
                <td>${driver.phone || 'N/A'}</td>
                <td>
                    <span class="status-badge status-${driver.status}">
                        ${driver.status.charAt(0).toUpperCase() + driver.status.slice(1)}
                    </span>
                </td>
            </tr>
        `).join('');

        const unitsHtml = operator.units.map((unit, index) => `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${unit.plate_no}</strong></td>
                <td>${unit.type}</td>
                <td>${unit.brand} ${unit.model}</td>
                <td>${unit.year}</td>
                <td>${unit.capacity}</td>
                <td>
                    <span class="status-badge status-${unit.status}">
                        ${unit.status.charAt(0).toUpperCase() + unit.status.slice(1)}
                    </span>
                </td>
            </tr>
        `).join('');

        modal.innerHTML = `
            <div class="attendance-modal-container" style="max-width: 1400px;">
                <div class="attendance-modal-header">
                    <h3>
                        <i class="fas fa-user"></i>
                        <span>Cooperative Details - ${operator.full_name || operator.user.name}</span>
                    </h3>
                    <button class="modal-close-btn" onclick="closeOperatorDetailsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="attendance-modal-body">
                    <!-- Operator Information -->
                    <div class="meeting-info-section">
                        <h4><i class="fas fa-info-circle"></i> Operator Information</h4>
                        <div class="meeting-details-grid">
                            <div class="meeting-detail">
                                <i class="fas fa-id-badge"></i>
                                <span><strong>User ID:</strong> ${operator.user.user_id}</span>
                            </div>
                            <div class="meeting-detail">
                                <i class="fas fa-user"></i>
                                <span><strong>Name:</strong> ${operator.full_name || operator.user.name}</span>
                            </div>
                            <div class="meeting-detail">
                                <i class="fas fa-user-tie"></i>
                                <span><strong>Contact Person:</strong> ${operator.contact_person || operator.user.name}</span>
                            </div>
                            <div class="meeting-detail">
                                <i class="fas fa-phone"></i>
                                <span><strong>Phone:</strong> ${operator.phone || 'N/A'}</span>
                            </div>
                            <div class="meeting-detail">
                                <i class="fas fa-envelope"></i>
                                <span><strong>Email:</strong> ${operator.email || operator.user.email}</span>
                            </div>
                            <div class="meeting-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><strong>Address:</strong> ${operator.address || 'N/A'}</span>
                            </div>
                            <div class="meeting-detail">
                                <i class="fas fa-file-alt"></i>
                                <span><strong>Business Permit:</strong> ${operator.business_permit_no || 'N/A'}</span>
                            </div>
                        </div>

                        <div class="attendance-stats-summary" style="margin-top: 20px;">
                            <div class="stat-card stat-total">
                                <i class="fas fa-id-card"></i>
                                <div>
                                    <span class="stat-number">${operator.drivers.length}</span>
                                    <span class="stat-label">Total Drivers</span>
                                </div>
                            </div>
                            <div class="stat-card stat-present">
                                <i class="fas fa-bus"></i>
                                <div>
                                    <span class="stat-number">${operator.units.length}</span>
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

                    <!-- Drivers Table -->
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

                    <!-- Units Table -->
                    <div class="attendance-details-table-container">
                        <h4><i class="fas fa-bus"></i> Units (${operator.units.length})</h4>
                        ${operator.units.length > 0 ? `
                            <table class="attendance-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Plate Number</th>
                                        <th>Type</th>
                                        <th>Make & Model</th>
                                        <th>Year</th>
                                        <th>Capacity</th>
                                        <th style="width: 100px;">Status</th>
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

        document.body.appendChild(modal);
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

    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Attendance Chart
        const attendanceCtx = document.getElementById('monthlyAttendanceChart').getContext('2d');
        const attendanceData = @json($attendanceData);

        new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: attendanceData.labels,
                datasets: [{
                    label: 'Attendance Rate (%)',
                    data: attendanceData.data,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Attendance: ' + context.parsed.y + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            },
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Annual Collection Chart
        const collectionCtx = document.getElementById('annualCollectionChart').getContext('2d');
        const collectionsData = @json($collectionsData);

        new Chart(collectionCtx, {
            type: 'bar',
            data: {
                labels: collectionsData.labels,
                datasets: [
                    {
                        label: 'Receipts',
                        data: collectionsData.receipts,
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: '#1cc88a',
                        borderWidth: 2,
                        borderRadius: 6
                    },
                    {
                        label: 'Disbursements',
                        data: collectionsData.disbursements,
                        backgroundColor: 'rgba(231, 74, 59, 0.8)',
                        borderColor: '#e74a3b',
                        borderWidth: 2,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 13,
                                weight: '600'
                            },
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ₱';
                                }
                                label += context.parsed.y.toLocaleString();
                                return label;
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
                            },
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
