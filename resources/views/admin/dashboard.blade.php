@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard Overview')

@push('styles')
<style>
    /* Modern Dashboard Styles */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
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

    .stat-card.blue {
        --start-color: #4e73df;
        --end-color: #224abe;
    }

    .stat-card.green {
        --start-color: #1cc88a;
        --end-color: #13855c;
    }

    .stat-card.orange {
        --start-color: #f6c23e;
        --end-color: #dda20a;
    }

    .stat-card.purple {
        --start-color: #6f42c1;
        --end-color: #4e2d87;
    }

    .stat-card.teal {
        --start-color: #36b9cc;
        --end-color: #258391;
    }

    .stat-card.red {
        --start-color: #e74c3c;
        --end-color: #c0392b;
    }

    .stat-card.yellow {
        --start-color: #f39c12;
        --end-color: #e67e22;
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

    /* Status Badges */
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

    /* Chart Cards */
    .chart-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .chart-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card-title i {
        color: #4e73df;
    }

    .chart-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    /* Activity Sections Grid */
    .activity-sections-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Recent Activity Section */
    .activity-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .activity-title {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .activity-title i {
        color: #4e73df;
    }

    .activity-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .activity-tab {
        padding: 8px 20px;
        border: none;
        background: #f8f9fa;
        color: #6c757d;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .activity-tab.active {
        background: #4e73df;
        color: white;
    }

    .activity-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease;
    }

    .activity-item:hover {
        background: #f8f9fa;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 15px;
    }

    .activity-icon.blue {
        background: #e3f2fd;
        color: #1976d2;
    }

    .activity-icon.green {
        background: #e8f5e9;
        color: #388e3c;
    }

    .activity-icon.orange {
        background: #fff3e0;
        color: #f57c00;
    }

    .activity-icon.red {
        background: #ffebee;
        color: #d32f2f;
    }

    .activity-icon.yellow {
        background: #fff8e1;
        color: #f57f17;
    }

    .expiring-badge {
        background: #ffebee;
        color: #d32f2f;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .expiring-badge.warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .activity-details {
        flex: 1;
    }

    .activity-name {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 3px;
    }

    .activity-meta {
        font-size: 13px;
        color: #6c757d;
    }

    .activity-time {
        font-size: 12px;
        color: #adb5bd;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #adb5bd;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 16px;
    }

    /* Clickable Cards */
    .stat-card.clickable {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card.clickable:active {
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
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 10000;
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex !important;
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
        width: 95vw;          /* was 90% */
        max-width: 1400px;    /* was 900px */
        max-height: 85vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 25px 30px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid rgba(255, 255, 255, 0.2);
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
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
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
        border-color: #4e73df;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.15);
        transform: translateX(5px);
    }

    .modal-list-item-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
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

    /* Table Styles for Operators Modal */
    .modal-table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .modal-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .modal-table thead {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
    }

    .modal-table thead th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 3px solid rgba(255, 255, 255, 0.2);
    }

    .modal-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modal-table tbody tr:hover {
        background: #f8f9fa;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.1);
    }

    .modal-table tbody tr:last-child {
        border-bottom: none;
    }

    .modal-table tbody td {
        padding: 18px 20px;
        font-size: 14px;
        color: #2c3e50;
    }

    .modal-table tbody td:first-child {
        font-weight: 600;
        color: #4e73df;
    }

    .modal-table tbody td:nth-child(2) {
        font-weight: 600;
    }

    .modal-table .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #adb5bd;
        font-size: 16px;
    }

    .modal-table .no-data i {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
        opacity: 0.5;
    }

    /* Operator Detail Modal */
    .operator-detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .operator-detail-card {
        background: linear-gradient(135deg, var(--detail-start), var(--detail-end));
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }

    .operator-detail-card.blue {
        --detail-start: #3498db;
        --detail-end: #2980b9;
    }

    .operator-detail-card.green {
        --detail-start: #2ecc71;
        --detail-end: #27ae60;
    }

    .operator-detail-card.purple {
        --detail-start: #9b59b6;
        --detail-end: #8e44ad;
    }

    .operator-detail-card.orange {
        --detail-start: #e67e22;
        --detail-end: #d35400;
    }

    .operator-detail-card.red {
        --detail-start: #e74c3c;
        --detail-end: #c0392b;
    }

    .operator-detail-card.yellow {
        --detail-start: #f39c12;
        --detail-end: #e67e22;
    }

    .operator-detail-label {
        font-size: 12px;
        text-transform: uppercase;
        opacity: 0.9;
        margin-bottom: 8px;
    }

    .operator-detail-value {
        font-size: 28px;
        font-weight: 700;
    }

    .operator-detail-section {
        margin-top: 25px;
    }

    .operator-detail-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .operator-detail-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .operator-detail-item {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .operator-detail-item-name {
        font-weight: 600;
        color: #2c3e50;
    }

    .operator-detail-item-info {
        font-size: 13px;
        color: #7f8c8d;
    }

    /* Document expiry badges */
    .document-expiry-badge {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .document-expiry-badge.expiring-warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .document-expiry-badge.expiring-critical {
        background: #ffebee;
        color: #d32f2f;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .chart-section {
            grid-template-columns: 1fr;
        }

        .activity-sections-grid {
            grid-template-columns: 1fr;
        }

        .stat-card-value {
            font-size: 28px;
        }

        .modal-container {
            width: 95%;
            max-height: 90vh;
        }

        .operator-detail-grid {
            grid-template-columns: 1fr;
        }

        /* Responsive table */
        .modal-table thead {
            display: none;
        }

        .modal-table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
        }

        .modal-table tbody tr:hover {
            border-color: #4e73df;
        }

        .modal-table tbody td {
            display: block;
            padding: 8px 0;
            text-align: left;
            border: none;
        }

        .modal-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #4e73df;
            display: inline-block;
            width: 120px;
            margin-right: 10px;
        }

        .modal-table tbody td:first-child {
            font-size: 16px;
            padding-bottom: 12px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }
    }

    /* Attendance Modal Styles (for nested modals) */
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

    /* Attendance Table Styles */
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

    /* Status Badge Styles */
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

    .status-badge.status-pending {
        background: #fff3cd;
        color: #856404;
    }

    /* Button Styles */
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

    /* Nested Modal */
    .nested-modal {
        background: rgba(0, 0, 0, 0.7);
    }

    /* Operator Info Card */
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

    /* Attendance Stats Summary */
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

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        display: block;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
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

    @media (max-width: 768px) {
        .full-details-grid {
            grid-template-columns: 1fr;
        }

        .attendance-modal-container {
            width: 98%;
            max-height: 95vh;
        }

        .attendance-modal-body {
            padding: 20px;
        }

        .operator-basic-info {
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

        .attendance-stats-summary {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    <!-- Statistics Cards -->
    <div class="dashboard-grid">
        <!-- Total Operators Card -->
        <div class="stat-card blue clickable" onclick="openOperatorsModal()">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Total Operators</h3>
                    <i class="fas fa-building stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">{{ $totalOperators }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-arrow-up"></i>
                    <span>Registered operators</span>
                </div>
            </div>
        </div>

        <!-- Total Drivers Card -->
        <div class="stat-card green clickable" onclick="openDriversModal()">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Total Drivers</h3>
                    <i class="fas fa-id-card stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">{{ $totalDrivers }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-users"></i>
                    <span>Active & Inactive</span>
                </div>
            </div>
        </div>

        <!-- Total Units Card -->
        <div class="stat-card orange clickable" onclick="openUnitsModal()">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Total Units</h3>
                    <i class="fas fa-bus stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">{{ $totalUnits }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-road"></i>
                    <span>Transport vehicles</span>
                </div>
            </div>
        </div>

        <!-- Cash on Hand Card -->
        <div class="stat-card purple">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Cash on Hand</h3>
                    <i class="fas fa-wallet stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">₱{{ number_format($cashOnHand, 0) }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-coins"></i>
                    <span>Available cash</span>
                </div>
            </div>
        </div>

        <!-- Cash in Bank Card -->
        <div class="stat-card teal">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Cash in Bank</h3>
                    <i class="fas fa-university stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">₱{{ number_format($cashInBank, 0) }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-piggy-bank"></i>
                    <span>Bank deposits</span>
                </div>
            </div>
        </div>

        <!-- Renewed Documents Card -->
        <div class="stat-card red clickable" onclick="openRenewedDocumentsModal()">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Renewed Documents</h3>
                    <i class="fas fa-file-contract stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">{{ $renewedDocuments ?? 0 }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Pending Approval</span>
                </div>
            </div>
        </div>

        <!-- Expiring Soon Card -->
        <div class="stat-card yellow clickable" onclick="openExpiringSoonModal()">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Expiring Soon</h3>
                    <i class="fas fa-exclamation-triangle stat-card-icon"></i>
                </div>
                <h2 class="stat-card-value">{{ $expiringSoon ?? 0 }}</h2>
                <div class="stat-card-footer">
                    <i class="fas fa-clock"></i>
                    <span>Within 30 days</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Books Navigation -->
    <div style="margin: 30px 0;">
        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #2c3e50;">
                <i class="fas fa-book-open"></i> Financial Books & Journals (Read-Only)
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px;">
                <a href="{{ route('admin.cash-treasurers-book') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #6366f1, #4f46e5); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(99, 102, 241, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-book" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Treasurer's Book</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Overview of all operator cash positions</p>
                    </div>
                </a>
                <a href="{{ route('admin.cash-receipts-journal') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-receipt" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Receipts Journal</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">All incoming cash transactions</p>
                    </div>
                </a>
                <a href="{{ route('admin.cash-disbursement-book') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #ef4444, #dc2626); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-file-invoice-dollar" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Disbursement Book</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">All outgoing cash transactions</p>
                    </div>
                </a>
                <a href="{{ route('admin.cash-book') }}" style="text-decoration: none;">
                    <div style="background: linear-gradient(135deg, #f59e0b, #d97706); padding: 20px; border-radius: 10px; color: white; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(245, 158, 11, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas fa-book-open" style="font-size: 24px;"></i>
                            <span style="font-size: 16px; font-weight: 600;">Cash Book</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Combined receipts and disbursements</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="chart-section">
        <!-- Attendance Chart -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h3 class="chart-card-title">
                    <i class="fas fa-calendar-check"></i>
                    Monthly Attendance
                </h3>
                <span class="chart-badge">{{ date('Y') }}</span>
            </div>
            <div class="chart-container">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Collections Chart -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h3 class="chart-card-title">
                    <i class="fas fa-chart-line"></i>
                    Annual Collections
                </h3>
                <span class="chart-badge">₱{{ number_format(array_sum($collectionsData['receipts']), 0) }} Total Receipts</span>
            </div>
            <div class="chart-container">
                <canvas id="collectionsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- NEW: Gender and Age Demographics Section -->
    <div class="chart-section">
        <!-- Gender Distribution Chart -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h3 class="chart-card-title">
                    <i class="fas fa-venus-mars"></i>
                    Gender Distribution
                </h3>
                <span class="chart-badge">{{ array_sum($genderData['counts']) }} Total</span>
            </div>
            <div class="chart-container">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        <!-- Age Bracket Chart -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h3 class="chart-card-title">
                    <i class="fas fa-users"></i>
                    Age Distribution
                </h3>
                <span class="chart-badge">By Age Bracket</span>
            </div>
            <div class="chart-container">
                <canvas id="ageBracketChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity Sections Grid -->
    <div class="activity-sections-grid">
        <!-- Recent Activity Section -->
        <div class="activity-section">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-history"></i>
                    Recent Activity
                </h3>
            </div>

            <div class="activity-list">
                @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon {{ $activity->color }}">
                            <i class="fas {{ $activity->icon }}"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-name">{{ $activity->formatted_type }}</div>
                            <div class="activity-meta">
                                <i class="fas fa-user"></i> {{ $activity->user ? $activity->user->name : 'System' }}
                                | {{ $activity->description }}
                            </div>
                        </div>
                        <div class="activity-time">
                            {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Renewed Documents Section -->
        <div class="activity-section">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-file-contract"></i>
                    Renewed Documents
                </h3>
            </div>

            <div class="activity-list">
                @forelse($renewedDocumentsList ?? [] as $renewal)
                    @if($renewal && is_object($renewal))
                        @php
                            $daysAgo = $renewal->days_ago ?? 0;
                            $operatorName = $renewal->operator_name ?? 'Unknown';
                            //$entityName = $renewal->entity_name ?? 'Unknown';
                            $documentType = $renewal->document_type ?? 'Document';

                            // Determine icon based on document type
                            $iconClass = 'fa-file-alt';
                            if (str_contains(strtolower($documentType), 'license')) {
                                $iconClass = 'fa-id-card';
                            } elseif (str_contains(strtolower($documentType), 'or') || str_contains(strtolower($documentType), 'cr')) {
                                $iconClass = 'fa-car';
                            } elseif (str_contains(strtolower($documentType), 'permit')) {
                                $iconClass = 'fa-certificate';
                            }
                            //- {{ $entityName }}
                        @endphp
                        <div class="activity-item clickable" onclick="openRenewalReviewModal({{ $renewal->id }})">
                            <div class="activity-icon blue">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-name">{{ $documentType }}</div>
                                <div class="activity-meta">
                                    <i class="fas fa-user"></i> {{ $operatorName }}
                                    | Submitted {{ $daysAgo }} {{ $daysAgo == 1 ? 'day' : 'days' }} ago
                                </div>
                            </div>
                            <span class="expiring-badge warning">
                                Pending Review
                            </span>
                        </div>
                    @endif
                @empty
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>No renewed documents pending approval</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Expiring Documents Section -->
        <div class="activity-section">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-file-alt"></i>
                    Expiring Documents
                </h3>
            </div>

            <div class="activity-list">
                @forelse($expiringDocuments ?? [] as $document)
                    @if($document && is_object($document))
                        @php
                            $daysUntilExpiry = round($document->days_remaining ?? 0); // <-- use days_remaining, not days_until_expiry
                            $ownerName = $document->owner_name ?? 'Unknown';
                            $documentType = $document->document_type ?? 'Document';

                            // Determine icon based on document type
                            $iconClass = 'fa-file-alt';
                            if (str_contains(strtolower($documentType), 'license')) {
                                $iconClass = 'fa-id-card';
                            } elseif (str_contains(strtolower($documentType), 'registration') || str_contains(strtolower($documentType), 'or/cr')) {
                                $iconClass = 'fa-car';
                            } elseif (str_contains(strtolower($documentType), 'insurance')) {
                                $iconClass = 'fa-shield-alt';
                            } elseif (str_contains(strtolower($documentType), 'permit')) {
                                $iconClass = 'fa-certificate';
                            }
                        @endphp
                        <div class="activity-item">
                            <div class="activity-icon {{ $daysUntilExpiry <= 7 ? 'red' : 'yellow' }}">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-name">{{ $documentType }}</div>
                                <div class="activity-meta">
                                    <i class="fas fa-user"></i> {{ $ownerName }}
                                    @if($daysUntilExpiry == 0)
                                        | <strong style="color: #d32f2f;">Expires Today!</strong>
                                    @elseif($daysUntilExpiry < 0)
                                        | <strong style="color: #c0392b;">Expired {{ abs($daysUntilExpiry) }} {{ abs($daysUntilExpiry) == 1 ? 'day' : 'days' }} ago</strong>
                                    @else
                                        | Expires in {{ $daysUntilExpiry }} {{ $daysUntilExpiry == 1 ? 'day' : 'days' }}
                                    @endif
                                </div>
                            </div>
                            <span class="expiring-badge {{ $daysUntilExpiry <= 7 ? '' : 'warning' }}">
                                @if($daysUntilExpiry == 0)
                                    Today
                                @elseif($daysUntilExpiry < 0)
                                    Overdue
                                @else
                                    {{ $daysUntilExpiry }} {{ $daysUntilExpiry == 1 ? 'day' : 'days' }}
                                @endif
                            </span>
                        </div>
                    @endif
                @empty
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>No expiring documents</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Operators Modal -->
    <div id="operatorsModal" class="modal-overlay" onclick="closeModal('operatorsModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-building"></i>
                    All Operators
                </h2>
                <button class="modal-close" onclick="closeModal('operatorsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="operatorSearch" placeholder="Search by Operator ID, Name, Phone, or Email..." oninput="filterOperators()">
                </div>
                <div class="modal-table-wrapper">
                    <table class="modal-table" id="operatorsTable">
                        <thead>
                            <tr>
                                <th>Operator ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact No.</th>
                                <th>Email</th>
                                <th>TIN#</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="operatorsList">
                            <tr>
                                <td colspan="7" class="no-data">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <div>Loading operators...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Operator Detail Modal -->
    <div id="operatorDetailModal" class="modal-overlay" onclick="closeModal('operatorDetailModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title" id="operatorDetailTitle">
                    <i class="fas fa-building"></i>
                    Operator Details
                </h2>
                <button class="modal-close" onclick="closeModal('operatorDetailModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="operatorDetailContent">
                <p style="text-align: center; color: #999;">Loading...</p>
            </div>
        </div>
    </div>

    <!-- Drivers Modal -->
    <div id="driversModal" class="modal-overlay" onclick="closeModal('driversModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-id-card"></i>
                    All Drivers
                </h2>
                <button class="modal-close" onclick="closeModal('driversModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="driverSearch" placeholder="Search by License Number, Name, Plate Number, or Operator..." oninput="filterDrivers()">
                </div>
                <div class="modal-table-wrapper">
                    <table class="modal-table" id="driversTable">
                        <thead>
                            <tr>
                                <th>License Number</th>
                                <th>Name</th>
                                <th>Plate Number</th>
                                <th>Operator</th>
                            </tr>
                        </thead>
                        <tbody id="driversList">
                            <tr>
                                <td colspan="4" class="no-data">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <div>Loading drivers...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Units Modal -->
    <div id="unitsModal" class="modal-overlay" onclick="closeModal('unitsModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-bus"></i>
                    All Units
                </h2>
                <button class="modal-close" onclick="closeModal('unitsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="unitSearch" placeholder="Search by Plate Number, Driver, or Operator..." oninput="filterUnits()">
                </div>
                <div class="modal-table-wrapper">
                    <table class="modal-table" id="unitsTable">
                        <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Driver</th>
                                <th>Operator</th>
                                <th>Business Permit#</th>
                                <th>Police#</th>
                                <th>Coding#</th>
                            </tr>
                        </thead>
                        <tbody id="unitsList">
                            <tr>
                                <td colspan="6" class="no-data">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <div>Loading units...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Soon Modal -->
    <div id="expiringSoonModal" class="modal-overlay" onclick="closeModal('expiringSoonModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <h2 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Expiring Documents (Next 30 Days)
                </h2>
                <button class="modal-close" onclick="closeModal('expiringSoonModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="expiringSearch" placeholder="Search by Owner Name, Document Type..." oninput="filterExpiringDocuments()">
                </div>
                <div class="modal-table-wrapper">
                    <table class="modal-table" id="expiringDocumentsTable">
                        <thead>
                            <tr>
                                <th>Owner Name</th>
                                <th>Document Type</th>
                                <th>Days Remaining</th>
                            </tr>
                        </thead>
                        <tbody id="expiringDocumentsList">
                            <tr>
                                <td colspan="3" class="no-data">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <div>Loading expiring documents...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Renewed Documents Modal -->
    <div id="renewedDocumentsModal" class="modal-overlay" onclick="closeModal('renewedDocumentsModal')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <h2 class="modal-title">
                    <i class="fas fa-file-contract"></i>
                    Renewed Documents (Pending Approval)
                </h2>
                <button class="modal-close" onclick="closeModal('renewedDocumentsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="renewedSearch" placeholder="Search by Operator, ID, Document Type..." oninput="filterRenewedDocuments()">
                </div>
                <div class="modal-table-wrapper">
                    <table class="modal-table" id="renewedDocumentsTable">
                        <thead>
                            <tr>
                                <th>Operator</th>
                                <th>ID</th>
                                <th>Document Type</th>
                                <th>Original Expiry</th>
                                <th>New Expiry</th>
                                <th>Submitted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="renewedDocumentsList">
                            <tr>
                                <td colspan="7" class="no-data">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <div>Loading renewed documents...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Renewal Review Modal -->
    <div id="renewalReviewModal" class="modal-overlay" onclick="closeModal('renewalReviewModal')">
        <div class="modal-container" onclick="event.stopPropagation()" style="max-width: 700px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <h2 class="modal-title">
                    <i class="fas fa-file-contract"></i>
                    Review Document Renewal
                </h2>
                <button class="modal-close" onclick="closeModal('renewalReviewModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <div id="renewalReviewContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Tab switching functionality
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: @json($attendanceData['labels']),
            datasets: [{
                label: 'Attendance Rate (%)',
                data: @json($attendanceData['data']),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
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
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + '%';
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
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
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

    // Collections Chart
    const collectionsCtx = document.getElementById('collectionsChart').getContext('2d');
    const collectionsChart = new Chart(collectionsCtx, {
        type: 'bar',
        data: {
            labels: @json($collectionsData['labels']),
            datasets: [
                {
                    label: 'Receipts (₱)',
                    data: @json($collectionsData['receipts']),
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: '#1cc88a',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                },
                {
                    label: 'Disbursements (₱)',
                    data: @json($collectionsData['disbursements']),
                    backgroundColor: 'rgba(231, 74, 59, 0.8)',
                    borderColor: '#e74a3b',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }
            ]
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
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
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
                        color: 'rgba(0, 0, 0, 0.05)'
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

    // Gender Distribution Chart (Donut Chart)
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    const genderChart = new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: @json($genderData['labels']),
            datasets: [{
                data: @json($genderData['counts']),
                backgroundColor: @json($genderData['colors']),
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            const counts = @json($genderData['counts']);
                            const percentages = @json($genderData['percentages']);

                            return data.labels.map((label, i) => ({
                                text: label + ': ' + counts[i] + ' (' + percentages[i] + '%)',
                                fillStyle: data.datasets[0].backgroundColor[i],
                                hidden: false,
                                index: i
                            }));
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const percentage = @json($genderData['percentages'])[context.dataIndex];
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Age Bracket Chart (Bar Chart)
    const ageBracketCtx = document.getElementById('ageBracketChart').getContext('2d');
    const ageBracketChart = new Chart(ageBracketCtx, {
        type: 'bar',
        data: {
            labels: @json($ageBracketData['labels']),
            datasets: [{
                label: 'Number of People',
                data: @json($ageBracketData['data']),
                backgroundColor: @json($ageBracketData['colors']),
                borderColor: @json($ageBracketData['colors']).map(color => color.replace('0.8', '1')),
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Count: ' + context.parsed.y + ' people';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        },
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11,
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Modal Functions
    let allOperators = [];
    let allDrivers = [];
    let allUnits = [];
    let allExpiringDocuments = [];

    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Open Operators Modal
    function openOperatorsModal() {
        openModal('operatorsModal');
        if (allOperators.length === 0) {
            fetchOperators();
        }
    }

    // Open Drivers Modal
    function openDriversModal() {
        openModal('driversModal');
        if (allDrivers.length === 0) {
            fetchDrivers();
        }
    }

    // Open Units Modal
    function openUnitsModal() {
        openModal('unitsModal');
        if (allUnits.length === 0) {
            fetchUnits();
        }
    }

    // Fetch Operators
    function fetchOperators() {
        fetch(apiUrl('operators'))
            .then(response => response.json())
            .then(data => {
                allOperators = data;
                displayOperators(data);
            })
            .catch(error => {
                document.getElementById('operatorsList').innerHTML = `
                    <tr>
                        <td colspan="4" class="no-data">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div style="color: #e74c3c;">Error loading operators</div>
                        </td>
                    </tr>
                `;
            });
    }

    // Display Operators
    function displayOperators(operators) {
        const listEl = document.getElementById('operatorsList');
        if (!listEl) {
            console.error('Operators list element not found');
            return;
        }

        if (!Array.isArray(operators) || operators.length === 0) {
            listEl.innerHTML = `
                <tr>
                    <td colspan="4" class="no-data">
                        <i class="fas fa-inbox"></i>
                        <div>No operators found</div>
                    </td>
                </tr>
            `;
            return;
        }

        listEl.innerHTML = operators.map(op => {
            const id = op.id || 0;
            const userId = op.user_id || 'N/A';
            const operatorName = op.full_name || op.contact_person || 'N/A';
            const address = op.address || 'N/A';
            const phone = op.phone || 'N/A';
            const email = op.email || 'N/A';
            const id_number = op.id_number || 'N/A';
            const status = op.status || 'inactive';

            // Status badge HTML
            const statusBadge = status === 'active'
                ? '<span class="badge badge-active">Active</span>'
                : '<span class="badge badge-inactive">Inactive</span>';

            return `
                <tr onclick="openOperatorDetail(${id})">
                    <td data-label="Operator ID:">${userId}</td>
                    <td data-label="Name:">${operatorName}</td>
                    <td data-label="Address:">${address}</td>
                    <td data-label="Phone:">${phone}</td>
                    <td data-label="Email:">${email}</td>
                    <td data-label="TIN#:">${id_number}</td>
                    <td data-label="Status:">${statusBadge}</td>
                </tr>
            `;
        }).join('');

    }

    // Filter Operators
    function filterOperators() {
        const search = document.getElementById('operatorSearch').value.toLowerCase();
        const filtered = allOperators.filter(op =>
            (op.user_id && op.user_id.toLowerCase().includes(search)) ||
            (op.full_name && op.full_name.toLowerCase().includes(search)) ||
            (op.contact_person && op.contact_person.toLowerCase().includes(search)) ||
            (op.phone && op.phone.includes(search)) ||
            (op.email && op.email.toLowerCase().includes(search))
        );
        displayOperators(filtered);
    }

    // Open Operator Detail
    function openOperatorDetail(operatorId) {
        console.log('Fetching operator details for ID:', operatorId);
        console.log('Current origin:', window.location.origin);

        closeModal('operatorsModal');

        // Show loading modal
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

    // Display Operator Detail
    function displayOperatorDetail(data) {
        console.log('displayOperatorDetail called with:', data);

        // Validate data structure
        if (!data || !data.operator) {
            console.error('Invalid data structure:', data);
            document.getElementById('operatorDetailContent').innerHTML =
                `<div style="text-align:center; padding:40px;">
                    <i class="fas fa-exclamation-circle" style="font-size:3rem; color:#ff9800; margin-bottom:15px;"></i>
                    <p style="color:#e74c3c; font-weight:600;">Invalid operator data received</p>
                    <p style="color:#999; font-size:0.9rem; margin-top:10px;">The server returned unexpected data format</p>
                </div>`;
            return;
        }

        // Safely get operator name
        const operatorName = data.operator.full_name || data.operator.contact_person || 'Unknown Operator';

        document.getElementById('operatorDetailTitle').innerHTML = `
            <i class="fas fa-user"></i>
            ${operatorName}
        `;

        // Safely extract values with defaults
        const totalDrivers = data.total_drivers || 0;
        const totalUnits = data.total_units || 0;
        const journalTotal = Number(data.journal_total || 0).toLocaleString();
        const balance = Number(data.balance || 0).toLocaleString();
        const absences = data.absences || 0;
        const absencePenalty = Number(data.absence_penalty || 0).toLocaleString();
        const documentsDue = data.documents_due || 0;
        const drivers = Array.isArray(data.drivers) ? data.drivers : [];
        const units = Array.isArray(data.units) ? data.units : [];
        const documents = Array.isArray(data.documents) ? data.documents : [];

        const content = `
            <div class="operator-detail-grid">
                <div class="operator-detail-card green">
                    <div class="operator-detail-label">Total Drivers</div>
                    <div class="operator-detail-value">${totalDrivers}</div>
                </div>
                <div class="operator-detail-card blue">
                    <div class="operator-detail-label">Total Units</div>
                    <div class="operator-detail-value">${totalUnits}</div>
                </div>
                <div class="operator-detail-card purple">
                    <div class="operator-detail-label">Journal Total</div>
                    <div class="operator-detail-value">₱${journalTotal}</div>
                </div>
                <div class="operator-detail-card orange">
                    <div class="operator-detail-label">Balance</div>
                    <div class="operator-detail-value">₱${balance}</div>
                </div>
                <div class="operator-detail-card red">
                    <div class="operator-detail-label">Absence Penalty</div>
                    <div class="operator-detail-value">₱${absencePenalty}</div>
                    <div style="font-size: 11px; margin-top: 5px; opacity: 0.9;">${absences} absence${absences !== 1 ? 's' : ''} × ₱100</div>
                </div>
                <div class="operator-detail-card yellow">
                    <div class="operator-detail-label">Documents Due</div>
                    <div class="operator-detail-value">${documentsDue}</div>
                    <div style="font-size: 11px; margin-top: 5px; opacity: 0.9;">Expiring within 30 days</div>
                </div>
            </div>

            ${drivers.length > 0 ? `
            <div class="operator-detail-section">
                <h3 class="operator-detail-section-title">
                    <i class="fas fa-users"></i>
                    Drivers (${drivers.length})
                </h3>
                <div class="operator-detail-list">
                    ${drivers.map(driver => `
                        <div class="operator-detail-item">
                            <div>
                                <div class="operator-detail-item-name">${driver.first_name || ''} ${driver.last_name || ''}</div>
                                <div class="operator-detail-item-info">License: ${driver.license_number || 'N/A'}</div>
                            </div>
                            <span class="modal-list-item-badge ${driver.status === 'active' ? '' : 'inactive'}">${driver.status || 'unknown'}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : '<div class="empty-state" style="padding: 20px;"><i class="fas fa-users"></i><p>No drivers assigned</p></div>'}

            ${units.length > 0 ? `
            <div class="operator-detail-section">
                <h3 class="operator-detail-section-title">
                    <i class="fas fa-bus"></i>
                    Transport Units (${units.length})
                </h3>
                <div class="operator-detail-list">
                    ${units.map(unit => `
                        <div class="operator-detail-item">
                            <div>
                                <div class="operator-detail-item-name">${unit.plate_number || 'N/A'}</div>
                                <div class="operator-detail-item-info">Model: ${unit.model || 'N/A'}</div>
                            </div>
                            <span class="modal-list-item-badge ${unit.status === 'active' ? '' : 'inactive'}">${unit.status || 'unknown'}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : '<div class="empty-state" style="padding: 20px;"><i class="fas fa-bus"></i><p>No units assigned</p></div>'}

            <div class="operator-detail-section">
                <h3 class="operator-detail-section-title">
                    <i class="fas fa-file-alt"></i>
                    Documents${documents.length > 0 ? ` (${documents.length})` : ' (0)'}
                </h3>
                ${documents.length > 0 ? `
                    <div class="operator-detail-list">
                        ${documents.map(doc => {
                            const daysLeft = Math.round(doc.days_until_expiry || 0);
                            const isExpiringSoon = daysLeft <= 7;
                            const isExpiringWarning = daysLeft > 7 && daysLeft <= 30;
                            const badgeClass = isExpiringSoon ? 'expiring-critical' : (isExpiringWarning ? 'expiring-warning' : '');
                            const ownerType = doc.documentable_type ? doc.documentable_type.split('\\').pop() : '';

                            return `
                                <div class="operator-detail-item">
                                    <div style="flex: 1;">
                                        <div class="operator-detail-item-name">
                                            ${doc.formatted_type || doc.type || 'Document'}
                                            ${ownerType ? `<span style="font-size: 11px; opacity: 0.7; margin-left: 8px;">(${ownerType})</span>` : ''}
                                        </div>
                                        <div class="operator-detail-item-info">
                                            Owner: ${doc.owner_name || 'N/A'} |
                                            ${doc.document_number !== 'N/A' ? 'No: ' + doc.document_number + ' | ' : ''}
                                            Expires: ${doc.expiry_date || 'No expiry'}
                                        </div>
                                    </div>
                                    <span class="document-expiry-badge ${badgeClass}">
                                        ${daysLeft > 0 ? daysLeft + (daysLeft === 1 ? ' day' : ' days') : (daysLeft === 0 ? 'Today' : 'Expired')}
                                    </span>
                                </div>
                            `;
                        }).join('')}
                    </div>
                ` : '<div class="empty-state" style="padding: 20px;"><i class="fas fa-file-alt"></i><p>No expiring documents</p></div>'}
            </div>
        `;

        document.getElementById('operatorDetailContent').innerHTML = content;
    }

    // Loading Modal Functions (from president's operators page)
    function showLoadingModal() {
        const modal = document.createElement('div');
        modal.id = 'loadingModal';
        modal.className = 'modal-overlay';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div style="background: white; border-radius: 15px; padding: 40px; text-align: center; max-width: 400px;">
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
        modal.className = 'modal-overlay';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div style="background: white; border-radius: 15px; max-width: 500px; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #e74a3b 0%, #d42a1a 100%); color: white; padding: 25px 30px; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>${title}</span>
                    </h3>
                    <button onclick="closeErrorModal()" style="background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; cursor: pointer; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div style="text-align: center; padding: 40px;">
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
        modal.className = 'modal-overlay';
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
            <div style="background: white; border-radius: 15px; width: 95%; max-width: 1400px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px 30px; border-radius: 15px 15px 0 0; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                    <h3 style="margin: 0; font-size: 22px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user"></i>
                        <span>Cooperative Details - ${operatorName}</span>
                    </h3>
                    <button class="modal-close" onclick="closeOperatorDetailsModal()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; font-size: 28px; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
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

    // NESTED MODAL FUNCTIONS (copied from president's operators page)

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
                                    <img src="${detail.valid_id_url}" 
                                        alt="Valid ID" 
                                        onclick="viewImageFullscreen('${detail.valid_id_url}', 'Valid ID')" 
                                        style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" 
                                        onmouseover="this.style.transform='scale(1.02)'" 
                                        onmouseout="this.style.transform='scale(1)'">
                                </div>
                                ` : ''}

                                ${operator.membership_form_url ? (() => {
                                    const ext = operator.membership_form_url.split('.').pop().toLowerCase();
                                    const isImage = ['jpg','jpeg','png'].includes(ext);
                                    const isPdf = ext === 'pdf';

                                    if (isImage) {
                                        // Show image inline
                                        return `
                                        <div style="text-align: center;">
                                            <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-file-image"></i> Membership Application Form</h5>
                                            <img src="${operator.membership_form_url}" 
                                                alt="Membership Application Form" 
                                                onclick="viewImageFullscreen('${operator.membership_form_url}', 'Membership Application Form')" 
                                                style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" 
                                                onmouseover="this.style.transform='scale(1.02)'" 
                                                onmouseout="this.style.transform='scale(1)'">
                                        </div>
                                        `;
                                    } else if (isPdf) {
                                        // Compute expected preview URL from PDF name
                                        const pdfBase = operator.membership_form_url.replace(/\.pdf$/i, '');
                                        const previewUrl = operator.membership_form_preview_url || (pdfBase + '.png');

                                        return `
                                        <div style="text-align: center;">
                                            <h5 style="margin-bottom: 10px; color: #667eea;"><i class="fas fa-file-pdf"></i> Membership Application Form</h5>
                                            <img src="${previewUrl}" 
                                                alt="Membership Application Form Preview" 
                                                onerror="this.style.display='none'" 
                                                onclick="viewImageFullscreen('${operator.membership_form_preview_url}', 'Membership Application Form PDF')" 
                                                style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.3s;" 
                                                onmouseover="this.style.transform='scale(1.02)'" 
                                                onmouseout="this.style.transform='scale(1)'">
                                            <div style="margin-top: 10px;">
                                                <a href="${operator.membership_form_url}" target="_blank" style="text-decoration: none; color: #667eea;">
                                                    <i class="fas fa-external-link-alt"></i> View Original PDF
                                                </a>
                                            </div>
                                        </div>
                                        `;
                                    }
                                })() : ''}

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

        // Format birthdate and calculate age
        let age = 'N/A';
        let formattedDOB = 'N/A';
        if (driver.birthdate) {
            const birth = new Date(driver.birthdate);
            const today = new Date();

            // Age calculation
            let years = today.getFullYear() - birth.getFullYear();
            const m = today.getMonth() - birth.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
                years--;
            }
            age = years >= 0 ? years : 'N/A';

            // Birthdate formatting
            const options = { year: 'numeric', month: 'long', day: '2-digit' };
            formattedDOB = birth.toLocaleDateString('en-US', options);
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
                                <span class="detail-value">${age}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Sex:</span>
                                <span class="detail-value">${driver.sex || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Date of Birth:</span>
                                <span class="detail-value">${formattedDOB}</span>
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
                        </div>
                    </div>

                    <div class="full-details-section">
                        <h4><i class="fas fa-calendar"></i> Employment Information</h4>
                        <div class="full-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Date Approved:</span>
                                <span class="detail-value">${driver.approved_at || 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Driver Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge status-${driver.status}">
                                        ${driver.status.charAt(0).toUpperCase() + driver.status.slice(1)}
                                    </span>
                                </span>
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
                                <span class="detail-value">${unit.police_number || 'N/A'}</span>
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

    // Fetch Drivers
    function fetchDrivers() {
        fetch(apiUrl('drivers'))
            .then(response => response.json())
            .then(data => {
                allDrivers = data;
                displayDrivers(data);
            })
            .catch(error => {
                document.getElementById('driversList').innerHTML = '<p style="text-align:center; color:#e74c3c;">Error loading drivers</p>';
            });
    }

    // Display Drivers
    function displayDrivers(drivers) {
        const listEl = document.getElementById('driversList');
        if (!listEl) {
            console.error('Drivers list element not found');
            return;
        }

        if (!Array.isArray(drivers) || drivers.length === 0) {
            listEl.innerHTML = `
                <tr>
                    <td colspan="4" class="no-data">
                        <i class="fas fa-inbox"></i>
                        <div>No drivers found</div>
                    </td>
                </tr>
            `;
            return;
        }

        listEl.innerHTML = drivers.map(driver => {
            const licenseNumber = driver.license_number || 'N/A';
            const fullName = driver.full_name || (driver.first_name + ' ' + driver.last_name);
            const plateNumber = driver.plate_number || 'Not Assigned';
            const operatorName = (driver.operator && driver.operator.full_name) ? driver.operator.full_name : 'N/A';

            return `
                <tr>
                    <td data-label="License Number:">${licenseNumber}</td>
                    <td data-label="Name:">${fullName}</td>
                    <td data-label="Plate Number:">${plateNumber}</td>
                    <td data-label="Operator:">${operatorName}</td>
                </tr>
            `;
        }).join('');
    }

    // Filter Drivers
    function filterDrivers() {
        const search = document.getElementById('driverSearch').value.toLowerCase();
        const filtered = allDrivers.filter(driver =>
            (driver.license_number && driver.license_number.toLowerCase().includes(search)) ||
            (driver.first_name && driver.first_name.toLowerCase().includes(search)) ||
            (driver.last_name && driver.last_name.toLowerCase().includes(search)) ||
            (driver.full_name && driver.full_name.toLowerCase().includes(search)) ||
            (driver.plate_number && driver.plate_number.toLowerCase().includes(search)) ||
            (driver.operator && driver.operator.full_name && driver.operator.full_name.toLowerCase().includes(search))
        );
        displayDrivers(filtered);
    }

    // Fetch Units
    function fetchUnits() {
        fetch(apiUrl('units'))
            .then(response => response.json())
            .then(data => {
                allUnits = data;
                displayUnits(data);
            })
            .catch(error => {
                document.getElementById('unitsList').innerHTML = '<p style="text-align:center; color:#e74c3c;">Error loading units</p>';
            });
    }

    // Display Units
    function displayUnits(units) {
        const listEl = document.getElementById('unitsList');
        if (!listEl) {
            console.error('Units list element not found');
            return;
        }

        if (!Array.isArray(units) || units.length === 0) {
            listEl.innerHTML = `
                <tr>
                    <td colspan="3" class="no-data">
                        <i class="fas fa-inbox"></i>
                        <div>No units found</div>
                    </td>
                </tr>
            `;
            return;
        }

        listEl.innerHTML = units.map(unit => {
            const plateNumber = unit.plate_number || 'N/A';
            const driverName = (unit.driver && unit.driver.full_name) ? unit.driver.full_name : 'No driver assigned';
            const operatorName = (unit.operator && unit.operator.full_name) ? unit.operator.full_name : 'N/A';
            const business_permit_no = unit.business_permit_number || 'N/A';
            const policeNumber = unit.police_number || 'N/A';
            const codingNumber = unit.coding_number || 'N/A';

            return `
                <tr>
                    <td data-label="Plate Number:">${plateNumber}</td>
                    <td data-label="Driver:">${driverName}</td>
                    <td data-label="Operator:">${operatorName}</td>
                    <td data-label="Business Permit No:">${business_permit_no}</td>
                    <td data-label="Police Number:">${policeNumber}</td>
                    <td data-label="Body Number:">${codingNumber}</td>
                </tr>
            `;
        }).join('');
    }

    // Filter Units
    function filterUnits() {
        const search = document.getElementById('unitSearch').value.toLowerCase();
        const filtered = allUnits.filter(unit =>
            (unit.plate_number && unit.plate_number.toLowerCase().includes(search)) ||
            (unit.driver && unit.driver.full_name && unit.driver.full_name.toLowerCase().includes(search)) ||
            (unit.operator && unit.operator.full_name && unit.operator.full_name.toLowerCase().includes(search))
        );
        displayUnits(filtered);
    }

    // Open Expiring Soon Modal
    function openExpiringSoonModal() {
        openModal('expiringSoonModal');
        if (allExpiringDocuments.length === 0) {
            fetchExpiringDocuments();
        }
    }

    // Fetch Expiring Documents
    function fetchExpiringDocuments() {
        fetch(apiUrl('expiring-documents'))
            .then(response => response.json())
            .then(data => {
                allExpiringDocuments = data;
                displayExpiringDocuments(data);
            })
            .catch(error => {
                document.getElementById('expiringDocumentsList').innerHTML = `
                    <tr>
                        <td colspan="3" class="no-data">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div style="color: #e74c3c;">Error loading expiring documents</div>
                        </td>
                    </tr>
                `;
            });
    }

    // Display Expiring Documents
    function displayExpiringDocuments(documents) {
        const listEl = document.getElementById('expiringDocumentsList');
        if (!listEl) {
            console.error('Expiring documents list element not found');
            return;
        }

        if (!Array.isArray(documents) || documents.length === 0) {
            listEl.innerHTML = `
                <tr>
                    <td colspan="3" class="no-data">
                        <i class="fas fa-check-circle"></i>
                        <div>No expiring documents</div>
                    </td>
                </tr>
            `;
            return;
        }

        listEl.innerHTML = documents.map(doc => {
            const ownerName = doc.owner_name || 'Unknown';
            const documentType = doc.document_type || 'Document';
            const daysRemaining = doc.days_remaining ?? 0;

            // Determine badge class based on days remaining
            let badgeClass = '';
            let badgeStyle = '';
            if (daysRemaining < 0) {
                badgeClass = 'document-expiry-badge expiring-critical';
                badgeStyle = 'background: #ffebee; color: #d32f2f; font-weight: 700;';
            } else if (daysRemaining <= 7) {
                badgeClass = 'document-expiry-badge expiring-critical';
                badgeStyle = 'background: #ffebee; color: #d32f2f; font-weight: 700;';
            } else if (daysRemaining <= 30) {
                badgeClass = 'document-expiry-badge expiring-warning';
                badgeStyle = 'background: #fff3e0; color: #f57c00; font-weight: 600;';
            } else {
                badgeClass = 'document-expiry-badge';
                badgeStyle = 'background: #e8f5e9; color: #2e7d32; font-weight: 600;';
            }

            let daysText = '';
            if (daysRemaining < 0) {
                daysText = 'Expired ' + Math.abs(daysRemaining) + ' days ago';
            } else if (daysRemaining === 0) {
                daysText = 'Expires Today!';
            } else if (daysRemaining === 1) {
                daysText = '1 day';
            } else {
                daysText = daysRemaining + ' days';
            }

            return `
                <tr>
                    <td data-label="Owner Name:">${ownerName}</td>
                    <td data-label="Document Type:">${documentType}</td>
                    <td data-label="Days Remaining:">
                        <span class="${badgeClass}" style="${badgeStyle}">
                            ${daysText}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Filter Expiring Documents
    function filterExpiringDocuments() {
        const search = document.getElementById('expiringSearch').value.toLowerCase();
        const filtered = allExpiringDocuments.filter(doc =>
            (doc.owner_name && doc.owner_name.toLowerCase().includes(search)) ||
            (doc.document_type && doc.document_type.toLowerCase().includes(search))
        );
        displayExpiringDocuments(filtered);
    }

    // ============================================
    // RENEWED DOCUMENTS MODAL
    // ============================================

    let allRenewedDocuments = @json($renewedDocumentsList ?? []);

    // Open Renewed Documents Modal
    function openRenewedDocumentsModal() {
        openModal('renewedDocumentsModal');
        displayRenewedDocuments(allRenewedDocuments);
    }

    // Display Renewed Documents
    function displayRenewedDocuments(documents) {
        const listEl = document.getElementById('renewedDocumentsList');
        if (!listEl) {
            console.error('Renewed documents list element not found');
            return;
        }

        if (!Array.isArray(documents) || documents.length === 0) {
            listEl.innerHTML = `
                <tr>
                    <td colspan="7" class="no-data">
                        <i class="fas fa-check-circle"></i>
                        <div>No renewed documents pending approval</div>
                    </td>
                </tr>
            `;
            return;
        }

        listEl.innerHTML = documents.map(renewal => {
            const operatorName = renewal.operator_name || 'Unknown';
            const entityIdentifier = renewal.entity_identifier || 'N/A';
            const documentType = renewal.document_type || 'Document';
            const originalExpiry = renewal.original_expiry || 'N/A';
            const newExpiry = renewal.new_expiry || 'N/A';
            const submittedAt = renewal.submitted_at || 'N/A';
            const daysAgo = renewal.days_ago || 'Unknown';

            return `
                <tr style="cursor: pointer;" onclick="viewRenewalDetails(${renewal.id})">
                    <td data-label="Operator:">${operatorName}</td>
                    <td data-label="ID:">${entityIdentifier}</td>
                    <td data-label="Document Type:">${documentType}</td>
                    <td data-label="Original Expiry:">${originalExpiry}</td>
                    <td data-label="New Expiry:">
                        <strong style="color: #27ae60;">${newExpiry}</strong>
                    </td>
                    <td data-label="Submitted:">${daysAgo}</td>
                    <td data-label="Action:">
                        <button class="btn-review" onclick="event.stopPropagation(); reviewRenewal(${renewal.id})" style="background: #3498db; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                            <i class="fas fa-eye"></i> Review
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Filter Renewed Documents
    function filterRenewedDocuments() {
        const search = document.getElementById('renewedSearch').value.toLowerCase();
        const filtered = allRenewedDocuments.filter(renewal =>
            (renewal.operator_name && renewal.operator_name.toLowerCase().includes(search)) ||
            (renewal.entity_identifier && renewal.entity_identifier.toLowerCase().includes(search)) ||
            (renewal.document_type && renewal.document_type.toLowerCase().includes(search))
        );
        displayRenewedDocuments(filtered);
    }

    // View Renewal Details
    function viewRenewalDetails(renewalId) {
        reviewRenewal(renewalId);
    }

    // Review Renewal
    function reviewRenewal(renewalId) {
        // Fetch renewal details
        fetch(`/admin/document-renewals/${renewalId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRenewalReview(data.renewal);
                    closeModal('renewedDocumentsModal');
                    openModal('renewalReviewModal');
                } else {
                    showError(data.message || 'Failed to load renewal details');
                }
            })
            .catch(error => {
                console.error('Error loading renewal:', error);
                showError('Failed to load renewal details');
            });
    }

    // Display Renewal Review
    function displayRenewalReview(renewal) {
        const content = document.getElementById('renewalReviewContent');

        content.innerHTML = `
            <div style="margin-bottom: 25px;">
                <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #e74c3c; padding-bottom: 10px;">
                    <i class="fas fa-info-circle"></i> Renewal Information
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; color: #7f8c8d; font-size: 12px; margin-bottom: 5px; font-weight: 600;">OPERATOR</label>
                        <p style="color: #2c3e50; font-size: 15px; margin: 0;">${renewal.operator_name}</p>
                    </div>
                    <div>
                        <label style="display: block; color: #7f8c8d; font-size: 12px; margin-bottom: 5px; font-weight: 600;">ID</label>
                        <p style="color: #2c3e50; font-size: 15px; margin: 0; font-weight: 600;">${renewal.entity_identifier}</p>
                    </div>
                    <div>
                        <label style="display: block; color: #7f8c8d; font-size: 12px; margin-bottom: 5px; font-weight: 600;">DOCUMENT TYPE</label>
                        <p style="color: #2c3e50; font-size: 15px; margin: 0;">${renewal.document_type}</p>
                    </div>
                    <div>
                        <label style="display: block; color: #7f8c8d; font-size: 12px; margin-bottom: 5px; font-weight: 600;">SUBMITTED</label>
                        <p style="color: #2c3e50; font-size: 15px; margin: 0;">${renewal.submitted_at}</p>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #e74c3c; padding-bottom: 10px;">
                    <i class="fas fa-calendar-alt"></i> Expiry Date Changes
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="background: #ecf0f1; padding: 15px; border-radius: 8px;">
                        <label style="display: block; color: #7f8c8d; font-size: 12px; margin-bottom: 8px; font-weight: 600;">ORIGINAL EXPIRY DATE</label>
                        <p style="color: #e74c3c; font-size: 16px; margin: 0; font-weight: 600;">${renewal.original_expiry}</p>
                    </div>
                    <div style="background: #d5f4e6; padding: 15px; border-radius: 8px;">
                        <label style="display: block; color: #7f8c8d; font-size: 12px; margin-bottom: 8px; font-weight: 600;">NEW EXPIRY DATE</label>
                        <p style="color: #27ae60; font-size: 16px; margin: 0; font-weight: 600;">${renewal.new_expiry}</p>
                    </div>
                </div>
            </div>

            ${renewal.document_photo_url ? `
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #e74c3c; padding-bottom: 10px;">
                        <i class="fas fa-image"></i> Uploaded Document
                    </h3>
                    <div style="text-align: center;">
                        <img src="${renewal.document_photo_url}" alt="Document Photo" style="max-width: 100%; max-height: 400px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    </div>
                </div>
            ` : ''}

            <div style="margin-top: 30px; display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="rejectRenewal(${renewal.id})" style="background: #e74c3c; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <i class="fas fa-times-circle"></i> Reject
                </button>
                <button onclick="approveRenewal(${renewal.id})" style="background: #27ae60; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <i class="fas fa-check-circle"></i> Approve
                </button>
            </div>
        `;
    }

    // Approve Renewal
    function approveRenewal(renewalId) {
        if (!confirm('Are you sure you want to approve this document renewal? The new expiry date will be applied to the record.')) {
            return;
        }

        fetch(`/admin/document-renewals/${renewalId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message || 'Document renewal approved successfully');
                closeModal('renewalReviewModal');
                // Reload page to refresh the renewed documents list
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showError(data.message || 'Failed to approve renewal');
            }
        })
        .catch(error => {
            console.error('Error approving renewal:', error);
            showError('Failed to approve renewal');
        });
    }

    // Reject Renewal
    function rejectRenewal(renewalId) {
        const reason = prompt('Please provide a reason for rejecting this renewal request:');

        if (!reason || reason.trim() === '') {
            showError('Rejection reason is required');
            return;
        }

        fetch(`/admin/document-renewals/${renewalId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                rejection_reason: reason.trim()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message || 'Document renewal rejected successfully');
                closeModal('renewalReviewModal');
                // Reload page to refresh the renewed documents list
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showError(data.message || 'Failed to reject renewal');
            }
        })
        .catch(error => {
            console.error('Error rejecting renewal:', error);
            showError('Failed to reject renewal');
        });
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('operatorsModal');
            closeModal('operatorDetailModal');
            closeModal('driversModal');
            closeModal('unitsModal');
            closeModal('expiringSoonModal');
            closeModal('renewedDocumentsModal');
            closeModal('renewalReviewModal');
        }
    });
</script>
@endpush