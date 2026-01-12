@extends('layouts.app')

@section('title', 'Operator Dashboard')

@section('page-title', 'My Dashboard')

@push('styles')
<style>
    /* Operator Dashboard Styles */
    .operator-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .operator-stat-card {
        background: linear-gradient(135deg, var(--start-color), var(--end-color));
        border-radius: 12px;
        padding: 25px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .operator-stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .operator-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .operator-stat-card.blue {
        --start-color: #3498db;
        --end-color: #2980b9;
    }

    .operator-stat-card.green {
        --start-color: #2ecc71;
        --end-color: #27ae60;
    }

    .operator-stat-card.purple {
        --start-color: #9b59b6;
        --end-color: #8e44ad;
    }

    .operator-stat-card.orange {
        --start-color: #e67e22;
        --end-color: #d35400;
    }

    .operator-stat-card.red {
        --start-color: #e74c3c;
        --end-color: #c0392b;
    }

    .operator-stat-content {
        position: relative;
        z-index: 1;
    }

    .operator-stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .operator-stat-title {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.95;
        margin: 0;
    }

    .operator-stat-icon {
        font-size: 28px;
        opacity: 0.3;
    }

    .operator-stat-value {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .operator-stat-footer {
        margin-top: 10px;
        font-size: 11px;
        opacity: 0.9;
    }

    /* Dashboard Layout */
    .dashboard-main-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Notifications Panel */
    .notifications-panel {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .notifications-title {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .notifications-title i {
        color: #e67e22;
    }

    .notification-badge {
        background: #e74c3c;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .notifications-list {
        max-height: 500px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        border-left: 4px solid;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .notification-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .notification-item.warning {
        border-left-color: #f39c12;
        background: #fef9e7;
    }

    .notification-item.danger {
        border-left-color: #e74c3c;
        background: #fadbd8;
    }

    .notification-item.info {
        border-left-color: #3498db;
        background: #ebf5fb;
    }

    .notification-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .notification-icon.orange {
        background: #f39c12;
        color: white;
    }

    .notification-icon.red {
        background: #e74c3c;
        color: white;
    }

    .notification-icon.blue {
        background: #3498db;
        color: white;
    }

    .notification-content {
        flex: 1;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 5px;
    }

    .notification-title {
        font-weight: 700;
        color: #2c3e50;
        font-size: 14px;
        margin: 0;
    }

    .notification-time {
        font-size: 11px;
        color: #95a5a6;
    }

    .notification-message {
        font-size: 13px;
        color: #5d6d7e;
        margin: 0;
    }

    .empty-notifications {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
    }

    .empty-notifications i {
        font-size: 64px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    /* Spending Chart Card */
    .spending-chart-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .spending-chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .spending-chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .spending-chart-title i {
        color: #9b59b6;
    }

    .spending-badge {
        background: #f3e5f5;
        color: #7b1fa2;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .chart-container-operator {
        position: relative;
        height: 300px;
    }

    /* Recent Activity */
    .recent-activity-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .activity-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .activity-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .activity-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .activity-tab-btn {
        padding: 8px 18px;
        border: none;
        background: #f8f9fa;
        color: #6c757d;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .activity-tab-btn.active {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .activity-items {
        max-height: 300px;
        overflow-y: auto;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease;
    }

    .activity-item:hover {
        background: #f8f9fa;
    }

    .activity-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-right: 12px;
    }

    .activity-item-icon.green {
        background: #d5f4e6;
        color: #27ae60;
    }

    .activity-item-icon.orange {
        background: #fdebd0;
        color: #e67e22;
    }

    .activity-item-details {
        flex: 1;
    }

    .activity-item-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .activity-item-meta {
        font-size: 12px;
        color: #7f8c8d;
    }

    /* Clickable Cards */
    .clickable-card {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .clickable-card:active {
        transform: translateY(-3px) scale(0.98);
    }

    /* Modal Overlay Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 10000;
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-container {
        background: white;
        border-radius: 16px;
        max-width: 900px;
        width: 90%;
        max-height: 85vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 25px 30px;
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid rgba(255, 255, 255, 0.2);
    }

    .modal-header.blue {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .modal-header.purple {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
    }

    .modal-header.orange {
        background: linear-gradient(135deg, #e67e22, #d35400);
    }

    .modal-header.red {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        width: 40px;
        height: 40px;
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
        overflow-y: auto;
        flex: 1;
    }

    .modal-search {
        margin-bottom: 25px;
    }

    .modal-search input {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .modal-search input:focus {
        outline: none;
        border-color: #2ecc71;
        box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
    }

    .modal-list {
        display: grid;
        gap: 15px;
    }

    .modal-list-item {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .modal-list-item:hover {
        background: white;
        border-color: #2ecc71;
        box-shadow: 0 4px 15px rgba(46, 204, 113, 0.15);
        transform: translateX(5px);
    }

    .modal-list-item-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .modal-list-item-icon.blue {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .modal-list-item-content {
        flex: 1;
    }

    .modal-list-item-title {
        font-weight: 700;
        color: #2c3e50;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .modal-list-item-meta {
        font-size: 13px;
        color: #7f8c8d;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .modal-list-item-badge {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .modal-list-item-badge.inactive {
        background: #ffebee;
        color: #c62828;
    }

    /* Driver Detail Modal */
    .driver-detail-grid {
        display: grid;
        gap: 20px;
    }

    .driver-detail-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid #2ecc71;
    }

    .driver-detail-label {
        font-size: 12px;
        text-transform: uppercase;
        color: #7f8c8d;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .driver-detail-value {
        font-size: 16px;
        color: #2c3e50;
        font-weight: 600;
    }

    .driver-detail-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 15px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-main-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .operator-dashboard-grid {
            grid-template-columns: 1fr;
        }

        .operator-stat-value {
            font-size: 24px;
        }

        .modal-container {
            width: 95%;
            max-height: 90vh;
        }

        .driver-detail-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    <!-- Statistics Cards -->
    <div class="operator-dashboard-grid">
        <!-- Total Drivers Card -->
        <div class="operator-stat-card green clickable-card" onclick="openMyDriversModal()">
            <div class="operator-stat-content">
                <div class="operator-stat-header">
                    <h3 class="operator-stat-title">Total Drivers</h3>
                    <i class="fas fa-users operator-stat-icon"></i>
                </div>
                <h2 class="operator-stat-value">{{ $totalDrivers }}</h2>
                <div class="operator-stat-footer">
                    <i class="fas fa-id-card"></i> All drivers
                </div>
            </div>
        </div>

        <!-- Total Units Card -->
        <div class="operator-stat-card blue clickable-card" onclick="openMyUnitsModal()">
            <div class="operator-stat-content">
                <div class="operator-stat-header">
                    <h3 class="operator-stat-title">Total Units</h3>
                    <i class="fas fa-bus operator-stat-icon"></i>
                </div>
                <h2 class="operator-stat-value">{{ $totalUnits }}</h2>
                <div class="operator-stat-footer">
                    <i class="fas fa-road"></i> Fleet vehicles
                </div>
            </div>
        </div>

        <!-- Subsidiary Journal Total Card -->
        <div class="operator-stat-card purple clickable-card" onclick="openJournalModal()">
            <div class="operator-stat-content">
                <div class="operator-stat-header">
                    <h3 class="operator-stat-title">Journal Total</h3>
                    <i class="fas fa-book operator-stat-icon"></i>
                </div>
                <h2 class="operator-stat-value">₱{{ number_format($subsidiaryJournalTotal, 0) }}</h2>
                <div class="operator-stat-footer">
                    <i class="fas fa-chart-line"></i> This year
                </div>
            </div>
        </div>

        <!-- Balance Card -->
        <div class="operator-stat-card orange clickable-card" onclick="openBalanceModal()">
            <div class="operator-stat-content">
                <div class="operator-stat-header">
                    <h3 class="operator-stat-title">Balance</h3>
                    <i class="fas fa-wallet operator-stat-icon"></i>
                </div>
                <h2 class="operator-stat-value">₱{{ number_format($balance, 0) }}</h2>
                <div class="operator-stat-footer">
                    <i class="fas fa-coins"></i> Available
                </div>
            </div>
        </div>

        <!-- Meeting Absences Card -->
        <div class="operator-stat-card red clickable-card" onclick="openAbsencesModal()">
            <div class="operator-stat-content">
                <div class="operator-stat-header">
                    <h3 class="operator-stat-title">Meeting Absences</h3>
                    <i class="fas fa-calendar-times operator-stat-icon"></i>
                </div>
                <h2 class="operator-stat-value">{{ $absents }}</h2>
                <div class="operator-stat-footer">
                    <i class="fas fa-users-slash"></i> Coop meetings
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dashboard-main-content">
        <!-- Monthly Spending Chart -->
        <div class="spending-chart-card">
            <div class="spending-chart-header">
                <h3 class="spending-chart-title">
                    <i class="fas fa-chart-area"></i>
                    Monthly Spending
                </h3>
                <span class="spending-badge">₱{{ number_format(array_sum($monthlySpending['data']), 0) }} Total</span>
            </div>
            <div class="chart-container-operator">
                <canvas id="spendingChart"></canvas>
            </div>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0;">
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">
                    <i class="fas fa-info-circle" style="color: #9b59b6;"></i>
                    Data sourced from subsidiary journal entries
                </p>
            </div>
        </div>

        <!-- Notifications Panel -->
        <div class="notifications-panel">
            <div class="notifications-header">
                <h3 class="notifications-title">
                    <i class="fas fa-bell"></i>
                    Notifications
                </h3>
                @if($notifications->count() > 0)
                    <span class="notification-badge">{{ $notifications->count() }}</span>
                @endif
            </div>

            <div class="notifications-list">
                @forelse($notifications as $notification)
                    <div class="notification-item {{ $notification['type'] }}">
                        <div class="notification-icon {{ $notification['color'] }}">
                            <i class="fas {{ $notification['icon'] }}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-header">
                                <h4 class="notification-title">{{ $notification['title'] }}</h4>
                                <span class="notification-time">{{ $notification['time'] }}</span>
                            </div>
                            <p class="notification-message">{{ $notification['message'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-notifications">
                        <i class="fas fa-check-circle"></i>
                        <p>No notifications at this time</p>
                        <small style="color: #95a5a6;">You're all caught up!</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity-card">
        <div class="activity-card-header">
            <h3 class="activity-card-title">
                <i class="fas fa-clock"></i>
                Recent Activity
            </h3>
        </div>

        <div class="activity-tabs">
            <button class="activity-tab-btn active" onclick="showActivityTab('drivers')">
                <i class="fas fa-users"></i> Drivers
            </button>
            <button class="activity-tab-btn" onclick="showActivityTab('units')">
                <i class="fas fa-bus"></i> Units
            </button>
        </div>

        <!-- Drivers Tab -->
        <div id="drivers-activity" class="activity-items">
            @forelse($recentDrivers as $driver)
                <div class="activity-item">
                    <div class="activity-item-icon green">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="activity-item-details">
                        <div class="activity-item-name">{{ $driver->first_name }} {{ $driver->last_name }}</div>
                        <div class="activity-item-meta">
                            License: {{ $driver->license_number }} | Status: {{ ucfirst($driver->status) }}
                        </div>
                    </div>
                    <span style="font-size: 11px; color: #95a5a6;">{{ $driver->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: #95a5a6;">
                    <i class="fas fa-users" style="font-size: 48px; opacity: 0.3; margin-bottom: 10px;"></i>
                    <p>No drivers yet</p>
                </div>
            @endforelse
        </div>

        <!-- Units Tab -->
        <div id="units-activity" class="activity-items" style="display: none;">
            @forelse($recentUnits as $unit)
                <div class="activity-item">
                    <div class="activity-item-icon orange">
                        <i class="fas fa-bus"></i>
                    </div>
                    <div class="activity-item-details">
                        <div class="activity-item-name">{{ $unit->plate_number }}</div>
                        <div class="activity-item-meta">
                            Model: {{ $unit->model }} | Status: {{ ucfirst($unit->status) }}
                        </div>
                    </div>
                    <span style="font-size: 11px; color: #95a5a6;">{{ $unit->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: #95a5a6;">
                    <i class="fas fa-bus" style="font-size: 48px; opacity: 0.3; margin-bottom: 10px;"></i>
                    <p>No units yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- My Drivers Modal -->
    <div id="myDriversModal" class="modal-overlay" onclick="closeOpModal('myDriversModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-users"></i>
                    My Drivers
                </h2>
                <button class="modal-close" onclick="closeOpModal('myDriversModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="myDriverSearch" placeholder="Search drivers..." oninput="filterMyDrivers()">
                </div>
                <div class="modal-list" id="myDriversList">
                    <p style="text-align: center; color: #999;">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Detail Modal -->
    <div id="driverDetailModal" class="modal-overlay" onclick="closeOpModal('driverDetailModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title" id="driverDetailTitle">
                    <i class="fas fa-user"></i>
                    Driver Details
                </h2>
                <button class="modal-close" onclick="closeOpModal('driverDetailModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="driverDetailContent">
                <p style="text-align: center; color: #999;">Loading...</p>
            </div>
        </div>
    </div>

    <!-- My Units Modal -->
    <div id="myUnitsModal" class="modal-overlay" onclick="closeOpModal('myUnitsModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header blue">
                <h2 class="modal-title">
                    <i class="fas fa-bus"></i>
                    My Transport Units
                </h2>
                <button class="modal-close" onclick="closeOpModal('myUnitsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="myUnitSearch" placeholder="Search units..." oninput="filterMyUnits()">
                </div>
                <div class="modal-list" id="myUnitsList">
                    <p style="text-align: center; color: #999;">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Journal Modal -->
    <div id="journalModal" class="modal-overlay" onclick="closeOpModal('journalModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header purple">
                <h2 class="modal-title">
                    <i class="fas fa-book"></i>
                    Subsidiary Journal
                </h2>
                <button class="modal-close" onclick="closeOpModal('journalModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-book" style="font-size: 64px; color: #9b59b6; margin-bottom: 20px;"></i>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">Total Journal Entries</h3>
                    <p style="font-size: 36px; font-weight: 700; color: #9b59b6; margin: 0;">₱{{ number_format($subsidiaryJournalTotal, 2) }}</p>
                    <p style="color: #7f8c8d; margin-top: 20px;">Detailed journal entries feature coming soon!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Modal -->
    <div id="balanceModal" class="modal-overlay" onclick="closeOpModal('balanceModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header orange">
                <h2 class="modal-title">
                    <i class="fas fa-wallet"></i>
                    Account Balance
                </h2>
                <button class="modal-close" onclick="closeOpModal('balanceModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-wallet" style="font-size: 64px; color: #e67e22; margin-bottom: 20px;"></i>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">Available Balance</h3>
                    <p style="font-size: 36px; font-weight: 700; color: #e67e22; margin: 0;">₱{{ number_format($balance, 2) }}</p>
                    <p style="color: #7f8c8d; margin-top: 20px;">Transaction history feature coming soon!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Absences Modal -->
    <div id="absencesModal" class="modal-overlay" onclick="closeOpModal('absencesModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header red">
                <h2 class="modal-title">
                    <i class="fas fa-calendar-times"></i>
                    Meeting Absences
                </h2>
                <button class="modal-close" onclick="closeOpModal('absencesModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-calendar-times" style="font-size: 64px; color: #e74c3c; margin-bottom: 20px;"></i>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">Total Absences</h3>
                    <p style="font-size: 36px; font-weight: 700; color: #e74c3c; margin: 0;">{{ $absents }}</p>
                    <p style="color: #7f8c8d; margin-top: 20px;">Detailed attendance history feature coming soon!</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Activity tab switching
    function showActivityTab(tabName) {
        // Hide all tabs
        document.getElementById('drivers-activity').style.display = 'none';
        document.getElementById('units-activity').style.display = 'none';

        // Remove active class from all buttons
        document.querySelectorAll('.activity-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        document.getElementById(tabName + '-activity').style.display = 'block';

        // Add active class to clicked button
        event.target.closest('.activity-tab-btn').classList.add('active');
    }

    // Monthly Spending Chart
    const spendingCtx = document.getElementById('spendingChart').getContext('2d');
    const spendingChart = new Chart(spendingCtx, {
        type: 'line',
        data: {
            labels: @json($monthlySpending['labels']),
            datasets: [{
                label: 'Monthly Spending (₱)',
                data: @json($monthlySpending['data']),
                borderColor: '#9b59b6',
                backgroundColor: 'rgba(155, 89, 182, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#9b59b6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#8e44ad',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        usePointStyle: true
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
                            return 'Spending: ₱' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + (value / 1000) + 'k';
                        },
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Operator Modal Functions
    let myDriversData = [];
    let myUnitsData = [];

    function openOpModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeOpModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Open My Drivers Modal
    function openMyDriversModal() {
        openOpModal('myDriversModal');
        if (myDriversData.length === 0) {
            fetchMyDrivers();
        }
    }

    // Fetch My Drivers
    function fetchMyDrivers() {
        fetch(apiUrl('my-drivers'))
            .then(response => response.json())
            .then(data => {
                myDriversData = data;
                displayMyDrivers(data);
            })
            .catch(error => {
                document.getElementById('myDriversList').innerHTML = '<p style="text-align:center; color:#e74c3c;">Error loading drivers</p>';
            });
    }

    // Display My Drivers
    function displayMyDrivers(drivers) {
        const listEl = document.getElementById('myDriversList');
        if (drivers.length === 0) {
            listEl.innerHTML = '<p style="text-align:center; color:#999;">No drivers found</p>';
            return;
        }

        listEl.innerHTML = drivers.map(driver => `
            <div class="modal-list-item" onclick="openDriverDetail(${driver.id})">
                <div class="modal-list-item-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="modal-list-item-content">
                    <div class="modal-list-item-title">${driver.first_name} ${driver.last_name}</div>
                    <div class="modal-list-item-meta">
                        <span><i class="fas fa-id-badge"></i> ${driver.license_number || 'N/A'}</span>
                        <span><i class="fas fa-phone"></i> ${driver.phone || 'N/A'}</span>
                    </div>
                </div>
                <span class="modal-list-item-badge ${driver.status === 'active' ? '' : 'inactive'}">
                    ${driver.status}
                </span>
            </div>
        `).join('');
    }

    // Filter My Drivers
    function filterMyDrivers() {
        const search = document.getElementById('myDriverSearch').value.toLowerCase();
        const filtered = myDriversData.filter(driver =>
            (driver.first_name && driver.first_name.toLowerCase().includes(search)) ||
            (driver.last_name && driver.last_name.toLowerCase().includes(search)) ||
            (driver.license_number && driver.license_number.toLowerCase().includes(search))
        );
        displayMyDrivers(filtered);
    }

    // Open Driver Detail
    function openDriverDetail(driverId) {
        closeOpModal('myDriversModal');
        openOpModal('driverDetailModal');

        fetch(apiUrl(`drivers/${driverId}`))
            .then(response => response.json())
            .then(data => {
                displayDriverDetail(data);
            })
            .catch(error => {
                document.getElementById('driverDetailContent').innerHTML = '<p style="text-align:center; color:#e74c3c;">Error loading driver details</p>';
            });
    }

    // Display Driver Detail
    function displayDriverDetail(driver) {
        document.getElementById('driverDetailTitle').innerHTML = `
            <i class="fas fa-user"></i>
            ${driver.first_name} ${driver.last_name}
        `;

        const content = `
            <div class="driver-detail-grid">
                <div class="driver-detail-section">
                    <h4 style="margin: 0 0 15px 0; color: #2c3e50; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user-circle" style="color: #2ecc71;"></i>
                        Personal Information
                    </h4>
                    <div class="driver-detail-row">
                        <div>
                            <div class="driver-detail-label">Full Name</div>
                            <div class="driver-detail-value">${driver.first_name} ${driver.last_name}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Date of Birth</div>
                            <div class="driver-detail-value">${driver.date_of_birth || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Phone Number</div>
                            <div class="driver-detail-value">${driver.phone || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="driver-detail-row">
                        <div>
                            <div class="driver-detail-label">Email</div>
                            <div class="driver-detail-value">${driver.email || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Address</div>
                            <div class="driver-detail-value">${driver.address || 'N/A'}</div>
                        </div>
                    </div>
                </div>

                <div class="driver-detail-section" style="border-left-color: #3498db;">
                    <h4 style="margin: 0 0 15px 0; color: #2c3e50; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-id-card" style="color: #3498db;"></i>
                        License Information
                    </h4>
                    <div class="driver-detail-row">
                        <div>
                            <div class="driver-detail-label">License Number</div>
                            <div class="driver-detail-value">${driver.license_number || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">License Type</div>
                            <div class="driver-detail-value">${driver.license_type || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">License Expiry</div>
                            <div class="driver-detail-value">${driver.license_expiry || 'N/A'}</div>
                        </div>
                    </div>
                </div>

                <div class="driver-detail-section" style="border-left-color: #f39c12;">
                    <h4 style="margin: 0 0 15px 0; color: #2c3e50; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-briefcase" style="color: #f39c12;"></i>
                        Employment Status
                    </h4>
                    <div class="driver-detail-row">
                        <div>
                            <div class="driver-detail-label">Status</div>
                            <div class="driver-detail-value">
                                <span class="modal-list-item-badge ${driver.status === 'active' ? '' : 'inactive'}">
                                    ${driver.status}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Hire Date</div>
                            <div class="driver-detail-value">${driver.hire_date || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Emergency Contact</div>
                            <div class="driver-detail-value">${driver.emergency_contact || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('driverDetailContent').innerHTML = content;
    }

    // Open My Units Modal
    function openMyUnitsModal() {
        openOpModal('myUnitsModal');
        if (myUnitsData.length === 0) {
            fetchMyUnits();
        }
    }

    // Fetch My Units
    function fetchMyUnits() {
        fetch(apiUrl('my-units'))
            .then(response => response.json())
            .then(data => {
                myUnitsData = data;
                displayMyUnits(data);
            })
            .catch(error => {
                document.getElementById('myUnitsList').innerHTML = '<p style="text-align:center; color:#e74c3c;">Error loading units</p>';
            });
    }

    // Display My Units
    function displayMyUnits(units) {
        const listEl = document.getElementById('myUnitsList');
        if (units.length === 0) {
            listEl.innerHTML = '<p style="text-align:center; color:#999;">No units found</p>';
            return;
        }

        listEl.innerHTML = units.map(unit => `
            <div class="modal-list-item">
                <div class="modal-list-item-icon blue">
                    <i class="fas fa-bus"></i>
                </div>
                <div class="modal-list-item-content">
                    <div class="modal-list-item-title">${unit.plate_number}</div>
                    <div class="modal-list-item-meta">
                        <span><i class="fas fa-car"></i> ${unit.model}</span>
                        <span><i class="fas fa-calendar"></i> ${unit.year || 'N/A'}</span>
                        <span><i class="fas fa-palette"></i> ${unit.color || 'N/A'}</span>
                    </div>
                </div>
                <span class="modal-list-item-badge ${unit.status === 'active' ? '' : 'inactive'}">
                    ${unit.status}
                </span>
            </div>
        `).join('');
    }

    // Filter My Units
    function filterMyUnits() {
        const search = document.getElementById('myUnitSearch').value.toLowerCase();
        const filtered = myUnitsData.filter(unit =>
            (unit.plate_number && unit.plate_number.toLowerCase().includes(search)) ||
            (unit.model && unit.model.toLowerCase().includes(search))
        );
        displayMyUnits(filtered);
    }

    // Open Journal Modal
    function openJournalModal() {
        openOpModal('journalModal');
    }

    // Open Balance Modal
    function openBalanceModal() {
        openOpModal('balanceModal');
    }

    // Open Absences Modal
    function openAbsencesModal() {
        openOpModal('absencesModal');
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeOpModal('myDriversModal');
            closeOpModal('driverDetailModal');
            closeOpModal('myUnitsModal');
            closeOpModal('journalModal');
            closeOpModal('balanceModal');
            closeOpModal('absencesModal');
        }
    });
</script>
@endpush
