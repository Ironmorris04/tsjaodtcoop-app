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
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
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

    /* Driver Detail Modal - Enhanced */
    .driver-detail-grid {
        display: grid;
        gap: 25px;
    }

    .driver-profile-header {
        display: flex;
        gap: 25px;
        align-items: flex-start;
        padding: 25px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .driver-photo-container {
        flex-shrink: 0;
    }

    .driver-photo {
        width: 140px;
        height: 140px;
        border-radius: 12px;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .driver-photo-placeholder {
        width: 140px;
        height: 140px;
        border-radius: 12px;
        background: linear-gradient(135deg, #bdc3c7, #95a5a6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 48px;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .driver-info-summary {
        flex: 1;
    }

    .driver-name-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 5px 0;
    }

    .driver-id-badge {
        display: inline-block;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .driver-quick-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .driver-quick-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #5d6d7e;
    }

    .driver-quick-item i {
        width: 20px;
        color: #3498db;
    }

    .driver-detail-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid #2ecc71;
    }

    .driver-detail-section.license-section {
        border-left-color: #3498db;
    }

    .driver-detail-section.biodata-section {
        border-left-color: #9b59b6;
    }

    .driver-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .driver-section-title i {
        font-size: 18px;
    }

    .driver-detail-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #7f8c8d;
        font-weight: 600;
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }

    .driver-detail-value {
        font-size: 15px;
        color: #2c3e50;
        font-weight: 600;
    }

    .driver-detail-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 15px;
    }

    .driver-detail-row:last-child {
        margin-bottom: 0;
    }

    /* License Status Badge */
    .license-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .license-status-badge.valid {
        background: #d4edda;
        color: #155724;
    }

    .license-status-badge.expiring_soon {
        background: #fff3cd;
        color: #856404;
    }

    .license-status-badge.expired {
        background: #f8d7da;
        color: #721c24;
    }

    /* Clickable Image Container */
    .clickable-image-container {
        position: relative;
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .clickable-image-container:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .clickable-image-container img {
        width: 100%;
        height: auto;
        display: block;
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .clickable-image-container:hover .image-overlay {
        opacity: 1;
    }

    .image-overlay i {
        color: white;
        font-size: 32px;
    }

    .image-placeholder {
        background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
        padding: 40px;
        text-align: center;
        border-radius: 8px;
        color: #7f8c8d;
    }

    .image-placeholder i {
        font-size: 48px;
        margin-bottom: 10px;
        display: block;
    }

    /* Fullscreen Image Viewer */
    .fullscreen-viewer {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 100000;
        align-items: center;
        justify-content: center;
        cursor: zoom-out;
    }

    .fullscreen-viewer.active {
        display: flex;
    }

    .fullscreen-viewer img {
        max-width: 95%;
        max-height: 95%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .fullscreen-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 32px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .fullscreen-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .fullscreen-title {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px 25px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
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

        .driver-profile-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .driver-quick-info {
            grid-template-columns: 1fr;
        }

        .driver-quick-item {
            justify-content: center;
        }
    }

    /* Journal Modal Styles */
    .loading-spinner {
        text-align: center;
        padding: 60px;
        color: #95a5a6;
    }

    .loading-spinner i {
        font-size: 48px;
        animation: spin 1s linear infinite;
        color: #9b59b6;
        margin-bottom: 15px;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

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
        color: #9b59b6;
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

    .subsidiary-journal {
        margin-top: 20px;
    }

    .journal-section-title {
        font-size: 16px;
        font-weight: 700;
        color: white;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        border-radius: 8px;
    }

    .journal-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .journal-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .journal-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        color: #495057;
        letter-spacing: 0.5px;
    }

    .journal-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #2c3e50;
    }

    .journal-table tbody tr:hover {
        background: #f8f9fa;
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

    .journal-table tfoot {
        background: #f8f9fa;
    }

    /* Penalties Section */
    .penalties-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .penalties-header {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .penalties-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .penalties-summary {
        display: flex;
        gap: 15px;
    }

    .penalty-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .penalty-badge.unpaid {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .penalty-badge.paid {
        background: rgba(46, 204, 113, 0.3);
        border: 1px solid rgba(46, 204, 113, 0.5);
    }

    .penalties-list {
        padding: 25px;
    }

    .penalty-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .penalty-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .penalty-item.status-unpaid {
        background: #fadbd8;
        border-left-color: #e74c3c;
    }

    .penalty-item.status-partial {
        background: #fef9e7;
        border-left-color: #f39c12;
    }

    .penalty-item.status-paid {
        background: #d4edda;
        border-left-color: #2ecc71;
    }

    .penalty-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .penalty-item.status-unpaid .penalty-icon {
        background: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
    }

    .penalty-item.status-partial .penalty-icon {
        background: rgba(243, 156, 18, 0.2);
        color: #f39c12;
    }

    .penalty-item.status-paid .penalty-icon {
        background: rgba(46, 204, 113, 0.2);
        color: #2ecc71;
    }

    .penalty-details {
        flex: 1;
    }

    .penalty-details h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
    }

    .penalty-reason {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #7f8c8d;
    }

    .penalty-meta {
        display: flex;
        gap: 20px;
        font-size: 12px;
        color: #95a5a6;
    }

    .penalty-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .penalty-amount-section {
        display: flex;
        gap: 15px;
        flex-shrink: 0;
    }

    .penalty-amount,
    .penalty-paid,
    .penalty-remaining {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        background: white;
    }

    .amount-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #95a5a6;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .amount-value {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
    }

    .amount-value.paid {
        color: #2ecc71;
    }

    .amount-value.remaining {
        color: #e74c3c;
    }

    .penalty-status {
        flex-shrink: 0;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.status-unpaid {
        background: #e74c3c;
        color: white;
    }

    .status-badge.status-partial {
        background: #f39c12;
        color: white;
    }

    .status-badge.status-paid {
        background: #2ecc71;
        color: white;
    }

    .penalties-footer {
        background: #f8f9fa;
        padding: 15px 25px;
        border-top: 1px solid #e9ecef;
    }

    .penalties-footer p {
        margin: 0;
        font-size: 13px;
        color: #7f8c8d;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .penalties-footer i {
        color: #3498db;
    }

    @media (max-width: 1024px) {
        .penalty-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .penalty-amount-section {
            width: 100%;
            justify-content: space-around;
        }

        .penalties-summary {
            flex-direction: column;
            gap: 8px;
        }
    }

    /* Assign Driver Button */
    .btn-assign-driver {
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
        white-space: nowrap;
    }

    .btn-assign-driver:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Assignment Table */
    .assignment-table-container {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .assignment-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .assignment-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .assignment-table thead th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .assignment-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease;
    }

    .assignment-table tbody tr:hover {
        background: #f8f9fc;
    }

    .assignment-table tbody tr:last-child {
        border-bottom: none;
    }

    .assignment-table tbody td {
        padding: 15px 20px;
        font-size: 14px;
        color: #495057;
    }

    .assignment-table tbody td strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .unit-select {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e9ecef;
        border-radius: 6px;
        font-size: 14px;
        color: #495057;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .unit-select:hover {
        border-color: #667eea;
    }

    .unit-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .unit-select option {
        padding: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        color: white;
        padding: 10px 24px;
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

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.3);
    }

    .btn-cancel {
        background: #e9ecef;
        color: #495057;
        padding: 10px 24px;
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

    .btn-cancel:hover {
        background: #dee2e6;
        transform: translateY(-1px);
    }

    .status-badge-assigned {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge-unassigned {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Drivers Table Styles */
    .drivers-table-container {
        overflow-x: auto;
        margin-top: 10px;
    }

    .drivers-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .drivers-table thead {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
    }

    .drivers-table thead th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .drivers-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease;
    }

    .drivers-table tbody tr:hover {
        background: #f8f9fc;
    }

    .drivers-table tbody tr:last-child {
        border-bottom: none;
    }

    .drivers-table tbody td {
        padding: 15px 20px;
        font-size: 14px;
        color: #495057;
    }

    .drivers-table tbody td strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .btn-view-driver:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
    }

    @media (max-width: 768px) {
        .drivers-table {
            font-size: 12px;
        }

        .drivers-table thead th,
        .drivers-table tbody td {
            padding: 10px 12px;
        }
    }

    /* Units Table Styles */
    .units-table-container {
        overflow-x: auto;
        margin-top: 10px;
    }

    .units-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .units-table thead {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .units-table thead th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .units-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease;
    }

    .units-table tbody tr:hover {
        background: #f8f9fc;
    }

    .units-table tbody tr:last-child {
        border-bottom: none;
    }

    .units-table tbody td {
        padding: 15px 20px;
        font-size: 14px;
        color: #495057;
    }

    .units-table tbody td strong {
        color: #2c3e50;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .units-table {
            font-size: 12px;
        }

        .units-table thead th,
        .units-table tbody td {
            padding: 10px 12px;
        }
    }

    /* Attendance Summary Styles */
    .attendance-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
    }

    .attendance-summary-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .attendance-summary-item i {
        font-size: 24px;
    }

    .attendance-summary-item.present i { color: #2ecc71; }
    .attendance-summary-item.absent i { color: #e74c3c; }
    .attendance-summary-item.excused i { color: #f39c12; }
    .attendance-summary-item.total i { color: #9b59b6; }

    .attendance-summary-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }

    .attendance-summary-value {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
    }

    /* Attendance Table Styles */
    .attendance-table-container {
        overflow-x: auto;
        margin-top: 10px;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .attendance-table thead {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }

    .attendance-table thead th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .attendance-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease;
    }

    .attendance-table tbody tr:hover {
        background: #f8f9fc;
    }

    .attendance-table tbody tr:last-child {
        border-bottom: none;
    }

    .attendance-table tbody td {
        padding: 15px 20px;
        font-size: 14px;
        color: #495057;
    }

    .attendance-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .attendance-status-badge.present {
        background: #d4edda;
        color: #155724;
    }

    .attendance-status-badge.absent {
        background: #f8d7da;
        color: #721c24;
    }

    .attendance-status-badge.excused {
        background: #fff3cd;
        color: #856404;
    }

    .attendance-status-badge.no_record {
        background: #e9ecef;
        color: #6c757d;
    }

    .meeting-type-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .meeting-type-badge.regular {
        background: #e3f2fd;
        color: #1565c0;
    }

    .meeting-type-badge.special {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .meeting-type-badge.emergency {
        background: #ffebee;
        color: #c62828;
    }

    .meeting-type-badge.general {
        background: #e8f5e9;
        color: #2e7d32;
    }

    @media (max-width: 768px) {
        .attendance-table {
            font-size: 12px;
        }

        .attendance-table thead th,
        .attendance-table tbody td {
            padding: 10px 12px;
        }

        .attendance-summary {
            grid-template-columns: repeat(2, 1fr);
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
        <div class="operator-stat-card orange clickable-card" id="balanceCard" onclick="openBalanceModal()">
            <div class="operator-stat-content">
                <div class="operator-stat-header">
                    <h3 class="operator-stat-title">Balance</h3>
                    <i class="fas fa-wallet operator-stat-icon"></i>
                </div>

                <!-- Will be updated dynamically by JavaScript -->
                <h2 class="operator-stat-value">
                    <i class="fas fa-spinner fa-spin"></i>
                </h2>

                <div class="operator-stat-footer">
                    <i class="fas fa-coins"></i>
                    Loading...
                </div>
            </div>
        </div>

        <div class="stat-card teal">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Cash in Bank</h3>
                    <i class="fas fa-university stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">₱{{ number_format($totalSubscriptionCapital ?? 0, 0) }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-piggy-bank"></i>
                    <span>Subscription Capital</span>
                    <!--<span>Bank deposits</span> -->
                </div>
            </div>
        </div>    

        <!-- Total Absences Card -->
        <div class="operator-stat-card red clickable-card" onclick="openAbsencesModal()">
            <div class="operator-stat-content">
                <div class="operator-stat-header">
                    <h3 class="operator-stat-title">Total Absences</h3>
                    <i class="fas fa-calendar-times operator-stat-icon"></i>
                </div>
                <h2 class="operator-stat-value">{{ $absents }}</h2>
                <div class="operator-stat-footer">
                    <i class="fas fa-exclamation-circle"></i> Meeting absence{{ $absents !== 1 ? 's' : '' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Penalties Section -->
    @if($penalties->count() > 0)
    <div class="penalties-section">
        <div class="penalties-header">
            <h3>
                <i class="fas fa-exclamation-triangle"></i>
                My Penalties
            </h3>
            <div class="penalties-summary">
                <span class="penalty-badge unpaid">
                    <i class="fas fa-money-bill-wave"></i>
                    Unpaid: ₱{{ number_format($totalUnpaidPenalties, 2) }}
                </span>
                <span class="penalty-badge paid">
                    <i class="fas fa-check-circle"></i>
                    Paid: ₱{{ number_format($totalPaidPenalties, 2) }}
                </span>
            </div>
        </div>

        <div class="penalties-list">
            @foreach($penalties as $penalty)
            <div class="penalty-item status-{{ $penalty->status }}">
                <div class="penalty-icon">
                    <i class="fas fa-{{ $penalty->status === 'paid' ? 'check-circle' : ($penalty->status === 'partial' ? 'clock' : 'exclamation-circle') }}"></i>
                </div>
                <div class="penalty-details">
                    <h4>{{ $penalty->meeting->title ?? 'Meeting Absence' }}</h4>
                    <p class="penalty-reason">{{ $penalty->reason }}</p>
                    <div class="penalty-meta">
                        <span class="penalty-date">
                            <i class="fas fa-calendar"></i>
                            Meeting: {{ $penalty->meeting->meeting_date ? \Carbon\Carbon::parse($penalty->meeting->meeting_date)->format('M d, Y') : 'N/A' }}
                        </span>
                        <span class="penalty-due">
                            <i class="fas fa-clock"></i>
                            Due: {{ $penalty->due_date ? $penalty->due_date->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
                <div class="penalty-amount-section">
                    <div class="penalty-amount">
                        <span class="amount-label">Total</span>
                        <span class="amount-value">₱{{ number_format($penalty->amount, 2) }}</span>
                    </div>
                    @if($penalty->paid_amount > 0)
                    <div class="penalty-paid">
                        <span class="amount-label">Paid</span>
                        <span class="amount-value paid">₱{{ number_format($penalty->paid_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($penalty->remaining_amount > 0)
                    <div class="penalty-remaining">
                        <span class="amount-label">Balance</span>
                        <span class="amount-value remaining">₱{{ number_format($penalty->remaining_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
                <div class="penalty-status">
                    <span class="status-badge status-{{ $penalty->status }}">
                        {{ ucfirst($penalty->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="penalties-footer">
            <p>
                <i class="fas fa-info-circle"></i>
                Contact the treasurer to pay your penalties. Payments will be deducted from your balance accordingly.
            </p>
        </div>
    </div>
    @endif

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
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 15px;">
                    <div class="modal-search" style="flex: 1; margin: 0;">
                        <input type="text" id="myDriverSearch" placeholder="Search drivers..." oninput="filterMyDrivers()">
                    </div>
                    <button class="btn-assign-driver" onclick="openAssignDriverModal()">
                        <i class="fas fa-link"></i> Assign Driver to Unit
                    </button>
                </div>
                <div class="modal-list" id="myDriversList">
                    <p style="text-align: center; color: #999;">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Driver to Unit Modal -->
    <div id="assignDriverModal" class="modal-overlay" style="z-index: 10001;" onclick="closeAssignDriverModal()">
        <div class="modal-container" style="max-width: 1000px;" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-link"></i>
                    Assign Drivers to Units
                </h2>
                <button class="modal-close" onclick="closeAssignDriverModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="background: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #1976D2; font-size: 14px;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Each unit can only be assigned to one driver. Select a unit from the dropdown to assign it to a driver.
                    </p>
                </div>
                <div class="assignment-table-container">
                    <table class="assignment-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Driver Name</th>
                                <th>License Number</th>
                                <th>Status</th>
                                <th>Assigned Unit</th>
                            </tr>
                        </thead>
                        <tbody id="assignmentTableBody">
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #999; margin-bottom: 10px;"></i>
                                    <p style="color: #999;">Loading drivers and units...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                    <button class="btn-cancel" onclick="closeAssignDriverModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn-primary" onclick="saveDriverAssignments()">
                        <i class="fas fa-save"></i> Save Assignments
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Detail Modal -->
    <div id="driverDetailModal" class="modal-overlay" onclick="closeOpModal('driverDetailModal')">
        <div class="modal-container" style="max-width: 950px;" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title" id="driverDetailTitle">
                    <i class="fas fa-id-card"></i>
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

    <!-- Fullscreen Image Viewer -->
    <div id="fullscreenViewer" class="fullscreen-viewer" onclick="closeFullscreenViewer()">
        <button class="fullscreen-close" onclick="closeFullscreenViewer()">
            <i class="fas fa-times"></i>
        </button>
        <img id="fullscreenImage" src="" alt="Full size image">
        <div class="fullscreen-title" id="fullscreenTitle">Image</div>
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
            <div class="modal-body" id="journalModalContent">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading your subsidiary journal...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Modal -->
    <div id="balanceModal" class="modal-overlay" onclick="closeOpModal('balanceModal')">
        <div class="modal-container" style="max-width: 900px;" onclick="event.stopPropagation()">
            <div class="modal-header orange">
                <h2 class="modal-title">
                    <i class="fas fa-wallet"></i>
                    Account Balance - Monthly Breakdown
                </h2>
                <button class="modal-close" onclick="closeOpModal('balanceModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="balanceModalContent">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                    <p class="mt-3">Loading balance details...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Absences Modal -->
    <div id="absencesModal" class="modal-overlay" onclick="closeOpModal('absencesModal')">
        <div class="modal-container" style="max-width: 1000px;" onclick="event.stopPropagation()">
            <div class="modal-header red">
                <h2 class="modal-title">
                    <i class="fas fa-calendar-check"></i>
                    Meeting Attendance History
                </h2>
                <button class="modal-close" onclick="closeOpModal('absencesModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="attendanceModalContent">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading attendance history...</p>
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
        const baseUrl = window.location.origin;
        fetch(`${baseUrl}/api/my-drivers`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Handle the new response format {drivers: [...]}
                const drivers = data.drivers || data;
                myDriversData = drivers;
                displayMyDrivers(drivers);
            })
            .catch(error => {
                console.error('Error fetching drivers:', error);
                document.getElementById('myDriversList').innerHTML = '<p style="text-align:center; color:#e74c3c;">Error loading drivers: ' + error.message + '</p>';
            });
    }

    // Display My Drivers
    function displayMyDrivers(drivers) {
        const listEl = document.getElementById('myDriversList');
        if (drivers.length === 0) {
            listEl.innerHTML = '<p style="text-align:center; color:#999; padding: 40px;">No drivers found</p>';
            return;
        }

        listEl.innerHTML = `
            <div class="drivers-table-container">
                <table class="drivers-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>License Number</th>
                            <th>Driver Name</th>
                            <th>Contact Number</th>
                            <th>Assigned Unit Plate</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${drivers.map((driver, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td><span style="font-family: monospace; font-weight: 600; color: #27ae60;">${driver.license_number || 'N/A'}</span></td>
                                <td><strong>${driver.first_name} ${driver.last_name}</strong></td>
                                <td>${driver.phone || 'N/A'}</td>
                                <td>
                                    ${driver.assigned_unit_plate
                                        ? `<span style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">${driver.assigned_unit_plate}</span>`
                                        : '<span style="color: #999; font-style: italic;">Not Assigned</span>'}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    // Filter My Drivers
    function filterMyDrivers() {
        const search = document.getElementById('myDriverSearch').value.toLowerCase();
        const filtered = myDriversData.filter(driver =>
            (driver.first_name && driver.first_name.toLowerCase().includes(search)) ||
            (driver.last_name && driver.last_name.toLowerCase().includes(search)) ||
            (driver.driver_id && driver.driver_id.toLowerCase().includes(search)) ||
            (driver.user_id && driver.user_id.toLowerCase().includes(search)) ||
            (driver.phone && driver.phone.toLowerCase().includes(search)) ||
            (driver.license_number && driver.license_number.toLowerCase().includes(search)) ||
            (driver.assigned_unit_plate && driver.assigned_unit_plate.toLowerCase().includes(search))
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

    // Display Driver Detail - Enhanced Version
    function displayDriverDetail(driver) {
        document.getElementById('driverDetailTitle').innerHTML = `
            <i class="fas fa-id-card"></i>
            ${driver.full_name || (driver.first_name + ' ' + driver.last_name)}
        `;

        // Get license status icon and text
        const licenseStatusInfo = getLicenseStatusInfo(driver.license_status, driver.days_until_expiry);

        const content = `
            <div class="driver-detail-grid">
                <!-- Driver Profile Header with Photo -->
                <div class="driver-profile-header">
                    <div class="driver-photo-container">
                        ${driver.photo_url
                            ? `<img src="${driver.photo_url}" alt="Driver Photo" class="driver-photo" onclick="openFullscreenImage('${driver.photo_url}', 'Driver Photo')">`
                            : `<div class="driver-photo-placeholder"><i class="fas fa-user"></i></div>`
                        }
                    </div>
                    <div class="driver-info-summary">
                        <h3 class="driver-name-title">${driver.full_name || (driver.first_name + ' ' + driver.last_name)}</h3>
                        <span class="driver-id-badge">
                            <i class="fas fa-id-badge"></i> ${driver.driver_id || 'N/A'}
                        </span>
                        <div class="driver-quick-info">
                            <div class="driver-quick-item">
                                <i class="fas fa-birthday-cake"></i>
                                <span>${driver.age ? driver.age + ' years old' : 'Age N/A'}</span>
                            </div>
                            <div class="driver-quick-item">
                                <i class="fas fa-venus-mars"></i>
                                <span>${driver.sex || 'N/A'}</span>
                            </div>
                            <div class="driver-quick-item">
                                <i class="fas fa-phone"></i>
                                <span>${driver.phone || 'N/A'}</span>
                            </div>
                            <div class="driver-quick-item">
                                <i class="fas fa-bus"></i>
                                <span>${driver.assigned_unit ? driver.assigned_unit.plate_no : 'No Unit Assigned'}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="driver-detail-section">
                    <h4 class="driver-section-title">
                        <i class="fas fa-user-circle" style="color: #2ecc71;"></i>
                        Personal Information
                    </h4>
                    <div class="driver-detail-row">
                        <div>
                            <div class="driver-detail-label">Full Name</div>
                            <div class="driver-detail-value">${driver.full_name || (driver.first_name + ' ' + driver.last_name)}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Date of Birth</div>
                            <div class="driver-detail-value">${driver.birthdate || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Age</div>
                            <div class="driver-detail-value">${driver.age ? driver.age + ' years' : 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Sex</div>
                            <div class="driver-detail-value">${driver.sex || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="driver-detail-row">
                        <div>
                            <div class="driver-detail-label">Contact Number</div>
                            <div class="driver-detail-value">${driver.phone || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Email</div>
                            <div class="driver-detail-value">${driver.email || 'N/A'}</div>
                        </div>
                        <div>
                            <div class="driver-detail-label">Emergency Contact</div>
                            <div class="driver-detail-value">${driver.emergency_contact || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="driver-detail-row">
                        <div style="grid-column: span 2;">
                            <div class="driver-detail-label">Address</div>
                            <div class="driver-detail-value">${driver.address || 'N/A'}</div>
                        </div>
                    </div>
                </div>

                <!-- Biodata Photo Section -->
                <div class="driver-detail-section biodata-section">
                    <h4 class="driver-section-title">
                        <i class="fas fa-file-alt" style="color: #9b59b6;"></i>
                        Biodata Document
                    </h4>
                    ${driver.biodata_photo_url
                        ? `<div class="clickable-image-container" onclick="openFullscreenImage('${driver.biodata_photo_url}', 'Driver Biodata')">
                            <img src="${driver.biodata_photo_url}" alt="Biodata" style="max-height: 200px; width: auto;">
                            <div class="image-overlay">
                                <i class="fas fa-search-plus"></i>
                            </div>
                        </div>
                        <p style="margin-top: 10px; font-size: 12px; color: #7f8c8d;">
                            <i class="fas fa-info-circle"></i> Click image to view full screen
                        </p>`
                        : `<div class="image-placeholder">
                            <i class="fas fa-file-image"></i>
                            <p>No biodata document uploaded</p>
                        </div>`
                    }
                </div>

                <!-- License Information Section -->
                <div class="driver-detail-section license-section">
                    <h4 class="driver-section-title">
                        <i class="fas fa-id-card" style="color: #3498db;"></i>
                        License Information
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <div class="driver-detail-row">
                                <div>
                                    <div class="driver-detail-label">License Number</div>
                                    <div class="driver-detail-value">${driver.license_number || 'N/A'}</div>
                                </div>
                                <div>
                                    <div class="driver-detail-label">License Type</div>
                                    <div class="driver-detail-value">${driver.license_type || 'N/A'}</div>
                                </div>
                            </div>
                            <div class="driver-detail-row">
                                <div>
                                    <div class="driver-detail-label">Restrictions</div>
                                    <div class="driver-detail-value">${driver.license_restrictions || 'None'}</div>
                                </div>
                                <div>
                                    <div class="driver-detail-label">DL Codes</div>
                                    <div class="driver-detail-value">${driver.dl_codes || 'N/A'}</div>
                                </div>
                            </div>
                            <div class="driver-detail-row">
                                <div>
                                    <div class="driver-detail-label">Validity / Expiry Date</div>
                                    <div class="driver-detail-value">${driver.license_expiry || 'N/A'}</div>
                                </div>
                                <div>
                                    <div class="driver-detail-label">Status</div>
                                    <div class="driver-detail-value">
                                        <span class="license-status-badge ${driver.license_status || 'valid'}">
                                            <i class="${licenseStatusInfo.icon}"></i>
                                            ${licenseStatusInfo.text}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            ${driver.license_photo_url
                                ? `<div class="clickable-image-container" onclick="openFullscreenImage('${driver.license_photo_url}', 'Driver License')">
                                    <img src="${driver.license_photo_url}" alt="License" style="max-height: 180px; width: auto;">
                                    <div class="image-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                                <p style="margin-top: 8px; font-size: 11px; color: #7f8c8d; text-align: center;">
                                    <i class="fas fa-info-circle"></i> Click to view full screen
                                </p>`
                                : `<div class="image-placeholder" style="padding: 30px;">
                                    <i class="fas fa-id-card" style="font-size: 36px;"></i>
                                    <p style="margin: 0;">No license photo uploaded</p>
                                </div>`
                            }
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('driverDetailContent').innerHTML = content;
    }

    // Get license status information
    function getLicenseStatusInfo(status, daysUntilExpiry) {
        switch(status) {
            case 'expired':
                return { icon: 'fas fa-times-circle', text: 'Expired' };
            case 'expiring_soon':
                return { icon: 'fas fa-exclamation-triangle', text: `Expiring in ${daysUntilExpiry} days` };
            default:
                return { icon: 'fas fa-check-circle', text: 'Valid' };
        }
    }

    // Fullscreen image viewer functions
    function openFullscreenImage(imageUrl, title) {
        event.stopPropagation();
        document.getElementById('fullscreenImage').src = imageUrl;
        document.getElementById('fullscreenTitle').textContent = title || 'Image';
        document.getElementById('fullscreenViewer').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFullscreenViewer() {
        document.getElementById('fullscreenViewer').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close fullscreen on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('fullscreenViewer').classList.contains('active')) {
            closeFullscreenViewer();
        }
    });

    // Open My Units Modal
    function openMyUnitsModal() {
        openOpModal('myUnitsModal');
        if (myUnitsData.length === 0) {
            fetchMyUnits();
        }
    }

    // Fetch My Units
    function fetchMyUnits() {
        const baseUrl = window.location.origin;
        fetch(`${baseUrl}/api/my-units`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => response.json())
            .then(data => {
                // Handle the new response format {units: [...]}
                const units = data.units || data;
                myUnitsData = units;
                displayMyUnits(units);
            })
            .catch(error => {
                document.getElementById('myUnitsList').innerHTML = '<p style="text-align:center; color:#e74c3c;">Error loading units</p>';
            });
    }

    // Display My Units
    function displayMyUnits(units) {
        const listEl = document.getElementById('myUnitsList');
        if (units.length === 0) {
            listEl.innerHTML = '<p style="text-align:center; color:#999; padding: 40px;">No units found</p>';
            return;
        }

        listEl.innerHTML = `
            <div class="units-table-container">
                <table class="units-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Plate Number</th>
                            <th>Driver Assigned</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${units.map((unit, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td><strong>${unit.plate_no || 'N/A'}</strong></td>
                                <td>
                                    ${unit.driver_name
                                        ? `<span style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">${unit.driver_name}</span>`
                                        : '<span style="color: #999; font-style: italic;">Not Assigned</span>'}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    // Filter My Units
    function filterMyUnits() {
        const search = document.getElementById('myUnitSearch').value.toLowerCase();
        const filtered = myUnitsData.filter(unit =>
            (unit.user_id && unit.user_id.toLowerCase().includes(search)) ||
            (unit.plate_number && unit.plate_number.toLowerCase().includes(search)) ||
            (unit.plate_no && unit.plate_no.toLowerCase().includes(search)) ||
            (unit.driver_name && unit.driver_name.toLowerCase().includes(search)) ||
            (unit.body_number && unit.body_number.toLowerCase().includes(search)) ||
            (unit.year_model && unit.year_model.toLowerCase().includes(search))
        );
        displayMyUnits(filtered);
    }

    // Open Journal Modal
    function openJournalModal() {
        openOpModal('journalModal');
        loadMyJournalEntries();
    }

    // Load Journal Entries
    function loadMyJournalEntries() {
        // Get current operator's ID from the auth user
        fetch(apiUrl('transactions/my-transactions'))
            .then(response => response.json())
            .then(data => {
                if (data.success && data.transactions) {
                    displayJournalEntries(data.transactions);
                } else {
                    displayJournalEntries([]);
                }
            })
            .catch(error => {
                console.error('Error loading journal entries:', error);
                document.getElementById('journalModalContent').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #e74c3c;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>
                        <p>Failed to load journal entries</p>
                    </div>
                `;
            });
    }

    // Display Journal Entries
    function displayJournalEntries(transactions) {
        const content = document.getElementById('journalModalContent');

        if (!transactions || transactions.length === 0) {
            content.innerHTML = `
                <div style="text-align: center; padding: 60px;">
                    <i class="fas fa-inbox" style="font-size: 64px; color: #bdc3c7; margin-bottom: 20px;"></i>
                    <h3 style="color: #7f8c8d;">No Journal Entries Found</h3>
                    <p style="color: #95a5a6;">Your subsidiary journal is currently empty.</p>
                </div>
            `;
            return;
        }

        // Calculate totals
        const totalAmount = transactions.reduce((sum, t) => sum + parseFloat(t.amount), 0);

        let html = `
            <div class="subsidiary-journal">
                <h5 class="journal-section-title">
                    <i class="fas fa-book"></i>
                    Your Subsidiary Journal Entries
                </h5>

                <table class="journal-table">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Date</th>
                            <th>Particular</th>
                            <th style="width: 130px;">Month-Year</th>
                            <th style="width: 100px;">OR/Ref#</th>
                            <th style="width: 120px; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Display all transactions sorted by date
        const sortedTransactions = [...transactions].sort((a, b) => new Date(b.date) - new Date(a.date));

        sortedTransactions.forEach(transaction => {
            const typeClass = transaction.type === 'receipt' ? 'type-receipt' : 'type-disbursement';

            html += `
                <tr class="transaction-row ${typeClass}">
                    <td>${formatJournalDate(transaction.date)}</td>
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
                            <td colspan="4" style="text-align: right; font-weight: 700;">JOURNAL TOTAL:</td>
                            <td style="text-align: right; font-weight: 700; color: #9b59b6;">₱${totalAmount.toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;

        content.innerHTML = html;
    }

    function formatJournalDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }


    // Open Balance Modal
    function openBalanceModal() {
        openOpModal('balanceModal');
        loadMonthlyBalances();
    }

    /* =======================
    LOAD BALANCE CARD DATA ON PAGE LOAD
    ======================= */
    document.addEventListener('DOMContentLoaded', function() {
        updateBalanceCard();
    });

    function updateBalanceCard() {
        const balanceCard = document.getElementById('balanceCard');
        if (!balanceCard) return;

        const valueElement = balanceCard.querySelector('.operator-stat-value');
        const footerElement = balanceCard.querySelector('.operator-stat-footer');

        if (!valueElement || !footerElement) return;

        // ✅ SHOW LOADING STATE (SPINNER)
        valueElement.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
        footerElement.innerHTML = `<i class="fas fa-coins"></i> Loading...`;

        const url = "{{ route('operator.unpaid-balance.index') }}";

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(async res => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(`HTTP ${res.status}: ${text}`);
            }
            return res.json();
        })
        .then(data => {
            let totalObligations = 0;
            let totalPayments = 0;

            if (Array.isArray(data)) {
                data.forEach(row => {
                    totalObligations += Number(row.obligations || 0);
                    totalPayments += Number(row.payments || 0);
                });
            }

            const overallBalanceRaw = totalPayments - totalObligations;
            const overallBalance = Math.round(overallBalanceRaw);

            // ✅ SHOW FINAL VALUE ONLY ONCE
            valueElement.innerHTML = `
                <span style="color:#fff;font-weight:700;">
                    ₱${Math.abs(overallBalance).toLocaleString()}
                </span>
            `;

            const statusText =
                overallBalance === 0 ? 'Paid' :
                overallBalance > 0 ? 'Available' :
                'Outstanding';

            footerElement.innerHTML = `<i class="fas fa-coins"></i> ${statusText}`;

            balanceCard.classList.remove('orange', 'red', 'green');
            balanceCard.classList.add(
                overallBalance < 0 ? 'red' :
                overallBalance > 0 ? 'green' :
                'orange'
            );
        })
        .catch(err => {
            console.error('Error updating balance card:', err);

            // ✅ ONLY FALL BACK TO ₱0 ON ERROR
            valueElement.innerHTML = `<span style="color:#fff;font-weight:700;">₱0</span>`;
            footerElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> Error`;

            balanceCard.classList.remove('green', 'red');
            balanceCard.classList.add('orange');
        });
    }


    /* =======================
    LOAD MONTHLY BALANCES (FETCH)
    ======================= */
    function loadMonthlyBalances() {
        const content = document.getElementById('balanceModalContent');

        // Loading state
        content.innerHTML = `
            <div style="text-align:center;padding:40px;">
                <i class="fas fa-spinner fa-spin" style="font-size:48px;color:#e67e22;"></i>
                <p style="margin-top:15px;color:#7f8c8d;">Loading balance details...</p>
            </div>
        `;

        // Use Blade route helper for dynamic URL
        const url = "{{ route('operator.unpaid-balance.index') }}";

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(async res => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(`HTTP ${res.status}: ${text}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Monthly balances:', data); // DEBUG: Check API response
            displayMonthlyBalances(data);
        })
        .catch(err => {
            console.error('Error fetching balances:', err);
            content.innerHTML = `
                <div style="text-align:center;padding:40px;color:#e74c3c;">
                    <i class="fas fa-exclamation-triangle" style="font-size:48px;"></i>
                    <p>Failed to load balance data.<br>
                    <small>${err.message}</small></p>
                </div>
            `;
        });
    }

   /* =======================
    RENDER MONTHLY BALANCES
    ======================= */
    function displayMonthlyBalances(monthlyBalances) {
        const content = document.getElementById('balanceModalContent');

        if (!Array.isArray(monthlyBalances) || monthlyBalances.length === 0) {
            content.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-inbox" style="font-size: 64px; color: #bdc3c7;"></i>
                    <h3 style="color: #7f8c8d;">No Balance Data Available</h3>
                </div>
            `;
            return;
        }

        let totalObligations = 0;
        let totalPayments = 0;

        monthlyBalances.forEach(row => {
            totalObligations += Number(row.obligations || 0);
            totalPayments += Number(row.payments || 0);
        });

        const overallBalance = totalPayments - totalObligations;
        const balanceColor = overallBalance >= 0 ? '#27ae60' : '#e74c3c';

        const overallStatus =
            overallBalance === 0 ? 'paid' :
            overallBalance > 0 ? 'overpaid' :
            'unpaid';

        // Replace inline styled badges with classes
        const statusBadge =
            overallStatus === 'paid'
                ? '<span class="badge badge-success">Paid</span>'
                : overallStatus === 'overpaid'
                ? '<span class="badge badge-primary">Overpaid</span>'
                : '<span class="badge badge-danger">Unpaid</span>';

        // Helper to render row badges
        function getRowStatusBadge(status) {
            return status === 'paid'
                ? '<span class="badge badge-success">Paid</span>'
                : status === 'partial'
                ? '<span class="badge badge-warning">Partial</span>'
                : status === 'overpaid'
                ? '<span class="badge badge-primary">Overpaid</span>'
                : '<span class="badge badge-danger">Unpaid</span>';
        }

        const rowsHTML = monthlyBalances.map(row => {
            const rowColor =
                row.status === 'paid' ? '#27ae60' :
                row.status === 'partial' ? '#f39c12' :
                '#e74c3c';

            return `
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 12px 15px;">
                        ${row.month} ${row.year}
                    </td>
                    <td style="padding: 12px 15px; text-align: right; color: #e74c3c;">
                        ₱${Number(row.obligations || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}
                    </td>
                    <td style="padding: 12px 15px; text-align: right; color: #3498db;">
                        ₱${Number(row.payments || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}
                    </td>
                    <td style="padding: 12px 15px; text-align: right; font-weight: 600; color: ${rowColor};">
                        ₱${Number(row.balance || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}
                    </td>
                    <td style="padding: 12px 15px; text-align: center; font-weight: 600;">
                        ${getRowStatusBadge(row.status)}
                    </td>
                </tr>
            `;
        }).join('');

        content.innerHTML = `
            <div style="padding: 20px;">
                <!-- Summary Cards -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
                    <div style="background: #e74c3c; padding: 20px; border-radius: 10px; color: white;">
                        <div style="font-size: 14px;">Total Obligations</div>
                        <div style="font-size: 26px; font-weight: 700;">
                            ₱${totalObligations.toLocaleString(undefined, { minimumFractionDigits: 2 })}
                        </div>
                    </div>
                    <div style="background: #3498db; padding: 20px; border-radius: 10px; color: white;">
                        <div style="font-size: 14px;">Total Payments</div>
                        <div style="font-size: 26px; font-weight: 700;">
                            ₱${totalPayments.toLocaleString(undefined, { minimumFractionDigits: 2 })}
                        </div>
                    </div>
                    <div style="background: #2c3e50; padding: 20px; border-radius: 10px; color: white;">
                        <div style="font-size: 14px;">Overall Balance</div>
                        <div style="font-size: 26px; font-weight: 700; color: ${balanceColor};">
                            ₱${overallBalance.toLocaleString(undefined, { minimumFractionDigits: 2 })}
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div style="margin-bottom: 15px;">
                    Overall Status: ${statusBadge}
                </div>

                <!-- Monthly Breakdown Table -->
                <div style="overflow-x: auto; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <table style="width: 100%; border-collapse: collapse; background: white;">
                        <thead>
                            <tr style="background: #2c3e50; color: white;">
                                <th style="padding: 15px; text-align: left;">Month</th>
                                <th style="padding: 15px; text-align: right;">Obligations</th>
                                <th style="padding: 15px; text-align: right;">Payments</th>
                                <th style="padding: 15px; text-align: right;">Balance</th>
                                <th style="padding: 15px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHTML}
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div style="margin-top: 20px; padding: 15px; background: #ecf0f1; border-radius: 8px; font-size: 13px; color: #7f8c8d;">
                    <strong style="color: #2c3e50;">Note:</strong>
                    <span style="color: #e74c3c; font-weight: 600;">Obligations</span> are based on particular prices set by the treasurer.
                    <span style="color: #3498db; font-weight: 600;">Payments</span> are your recorded transactions.
                    A positive <span style="color: #27ae60; font-weight: 600;">Balance</span> means you've paid more than required,
                    while a negative balance means you have outstanding obligations.
                </div>
            </div>
        `;
    }
    
    // Open Absences Modal
    function openAbsencesModal() {
        openOpModal('absencesModal');
        loadMeetingAttendance();
    }

    // Load Meeting Attendance Data
    function loadMeetingAttendance() {
        const content = document.getElementById('attendanceModalContent');
        content.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading attendance history...</p>
            </div>
        `;

        const baseUrl = window.location.origin;
        fetch(`${baseUrl}/api/my-meeting-attendance`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMeetingAttendance(data.meetings, data.summary);
            } else {
                content.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #e74c3c;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>
                        <p>Failed to load attendance data</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading attendance:', error);
            content.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #e74c3c;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p>Error loading attendance data</p>
                </div>
            `;
        });
    }

    // Display Meeting Attendance
    function displayMeetingAttendance(meetings, summary) {
        const content = document.getElementById('attendanceModalContent');

        if (!meetings || meetings.length === 0) {
            content.innerHTML = `
                <div style="text-align: center; padding: 60px;">
                    <i class="fas fa-calendar-check" style="font-size: 64px; color: #bdc3c7; margin-bottom: 20px;"></i>
                    <h3 style="color: #7f8c8d;">No Meeting Records Found</h3>
                    <p style="color: #95a5a6;">There are no meeting records in the system yet.</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="attendance-summary">
                <div class="attendance-summary-item total">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <div class="attendance-summary-label">Total Meetings</div>
                        <div class="attendance-summary-value">${summary.total_meetings}</div>
                    </div>
                </div>
                <div class="attendance-summary-item present">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <div class="attendance-summary-label">Present</div>
                        <div class="attendance-summary-value">${summary.present_count}</div>
                    </div>
                </div>
                <div class="attendance-summary-item absent">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <div class="attendance-summary-label">Absent</div>
                        <div class="attendance-summary-value">${summary.absent_count}</div>
                    </div>
                </div>
                <div class="attendance-summary-item excused">
                    <i class="fas fa-user-clock"></i>
                    <div>
                        <div class="attendance-summary-label">Excused</div>
                        <div class="attendance-summary-value">${summary.excused_count}</div>
                    </div>
                </div>
            </div>

            <div class="attendance-table-container">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Meeting Date</th>
                            <th>Meeting Title</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        meetings.forEach((meeting, index) => {
            const statusIcon = getAttendanceStatusIcon(meeting.attendance_status);
            const statusLabel = getAttendanceStatusLabel(meeting.attendance_status);

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${meeting.meeting_date}</strong></td>
                    <td>${meeting.title || 'N/A'}</td>
                    <td>
                        <span class="meeting-type-badge ${meeting.type || 'regular'}">
                            ${meeting.type || 'Regular'}
                        </span>
                    </td>
                    <td>${meeting.location}</td>
                    <td>
                        <span class="attendance-status-badge ${meeting.attendance_status}">
                            <i class="fas ${statusIcon}"></i>
                            ${statusLabel}
                        </span>
                    </td>
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

    // Get attendance status icon
    function getAttendanceStatusIcon(status) {
        switch(status) {
            case 'present': return 'fa-check-circle';
            case 'absent': return 'fa-times-circle';
            case 'excused': return 'fa-user-clock';
            default: return 'fa-question-circle';
        }
    }

    // Get attendance status label
    function getAttendanceStatusLabel(status) {
        switch(status) {
            case 'present': return 'Present';
            case 'absent': return 'Absent';
            case 'excused': return 'Excused';
            case 'no_record': return 'No Record';
            default: return 'Unknown';
        }
    }

    // Assign Driver Modal Functions
    let driversData = [];
    let unitsData = [];
    let assignedUnits = {}; // Track which units are assigned to which drivers

    function openAssignDriverModal() {
        const modal = document.getElementById('assignDriverModal');
        if (modal) {
            modal.style.display = 'flex';
            loadDriversAndUnitsForAssignment();
        }
    }

    function closeAssignDriverModal() {
        const modal = document.getElementById('assignDriverModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function loadDriversAndUnitsForAssignment() {
        const tbody = document.getElementById('assignmentTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #999; margin-bottom: 10px;"></i>
                    <p style="color: #999;">Loading drivers and units...</p>
                </td>
            </tr>
        `;

        // Fetch both drivers and units using absolute URLs
        const baseUrl = window.location.origin;

        Promise.all([
            fetch(`${baseUrl}/api/my-drivers`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            }).then(res => res.json()),
            fetch(`${baseUrl}/api/my-units`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            }).then(res => res.json())
        ])
        .then(([driversResponse, unitsResponse]) => {
            driversData = driversResponse.drivers || [];
            unitsData = unitsResponse.units || [];

            // Filter only approved units
            unitsData = unitsData.filter(unit => unit.status === 'active');

            displayAssignmentTable();
        })
        .catch(error => {
            console.error('Error loading data:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        <i class="fas fa-exclamation-circle" style="font-size: 2rem; color: #e74c3b; margin-bottom: 10px;"></i>
                        <p style="color: #e74c3b;">Error loading data. Please try again.</p>
                    </td>
                </tr>
            `;
        });
    }

    function displayAssignmentTable() {
        const tbody = document.getElementById('assignmentTableBody');

        if (driversData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        <i class="fas fa-user-slash" style="font-size: 2rem; color: #999; margin-bottom: 10px;"></i>
                        <p style="color: #999;">No drivers found</p>
                    </td>
                </tr>
            `;
            return;
        }

        // Build a map of already assigned units
        assignedUnits = {};
        driversData.forEach(driver => {
            if (driver.assigned_unit_id) {
                assignedUnits[driver.assigned_unit_id] = driver.id;
            }
        });

        tbody.innerHTML = driversData.map((driver, index) => {
            const currentUnitId = driver.assigned_unit_id;

            return `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${driver.full_name || driver.first_name + ' ' + driver.last_name}</strong></td>
                    <td>${driver.license_number || 'N/A'}</td>
                    <td>
                        ${currentUnitId
                            ? '<span class="status-badge-assigned">Assigned</span>'
                            : '<span class="status-badge-unassigned">Unassigned</span>'}
                    </td>
                    <td>
                        <select class="unit-select" data-driver-id="${driver.id}" onchange="handleUnitChange(this)">
                            <option value="">-- Select Unit --</option>
                            ${unitsData.map(unit => {
                                const isAssignedToOtherDriver = assignedUnits[unit.id] && assignedUnits[unit.id] !== driver.id;
                                const isCurrentlyAssigned = currentUnitId === unit.id;

                                return `
                                    <option value="${unit.id}"
                                        ${isCurrentlyAssigned ? 'selected' : ''}
                                        ${isAssignedToOtherDriver ? 'disabled' : ''}>
                                        ${unit.plate_no} - ${unit.year_model || 'N/A'} ${isAssignedToOtherDriver ? '(Assigned)' : ''}
                                    </option>
                                `;
                            }).join('')}
                        </select>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function handleUnitChange(selectElement) {
        const driverId = parseInt(selectElement.dataset.driverId);
        const newUnitId = selectElement.value ? parseInt(selectElement.value) : null;

        // Find the driver and update their assigned unit
        const driverIndex = driversData.findIndex(d => d.id === driverId);
        if (driverIndex !== -1) {
            const oldUnitId = driversData[driverIndex].assigned_unit_id;

            // Remove old assignment from tracking
            if (oldUnitId && assignedUnits[oldUnitId] === driverId) {
                delete assignedUnits[oldUnitId];
            }

            // Add new assignment to tracking
            if (newUnitId) {
                assignedUnits[newUnitId] = driverId;
            }

            // Update driver data
            driversData[driverIndex].assigned_unit_id = newUnitId;

            // Refresh the table to update disabled states
            displayAssignmentTable();
        }
    }

    function saveDriverAssignments() {
        const baseUrl = window.location.origin;

        // Prepare assignments data
        const assignments = driversData.map(driver => ({
            driver_id: driver.id,
            unit_id: driver.assigned_unit_id || null
        }));

        // Show loading state
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        fetch(`${baseUrl}/api/driver-assignments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ assignments })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Driver assignments saved successfully!');
                closeAssignDriverModal();
                // Reload drivers list to show updated assignments
                fetchMyDrivers();
            } else {
                alert('Error: ' + (data.message || 'Failed to save assignments'));
            }
        })
        .catch(error => {
            console.error('Error saving assignments:', error);
            alert('An error occurred while saving assignments. Please try again.');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
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
            closeAssignDriverModal();
        }
    });
</script>
@endpush
