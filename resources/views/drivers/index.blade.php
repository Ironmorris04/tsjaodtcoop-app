@extends('layouts.app')

@section('title', 'Drivers Management')
@section('page-title', 'Drivers Management')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Drivers</li>
@endsection

@section('content')
<style>
.driver-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.driver-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.driver-header {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); /* Green gradient for drivers */
    color: white;
    padding: 1.5rem;
    position: relative;
}

.driver-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto 1rem;
    backdrop-filter: blur(10px);
}

.driver-name-display {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
}

.driver-status-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.25);
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.driver-body {
    padding: 1.5rem;
    background: white;
}

.spec-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.spec-row:last-child {
    border-bottom: none;
}

.spec-label {
    font-size: 0.85rem;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.spec-label i {
    width: 20px;
    color: #2ecc71; /* Green icon color for drivers */
}

.spec-value {
    font-weight: 600;
    color: #333;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-indicator.active {
    background: #e8f5e9;
    color: #4caf50;
}

.status-indicator.inactive {
    background: #ffebee;
    color: #f44336;
}

.unit-assigned {
    color: #667eea;
    font-weight: 600;
}

.no-unit {
    color: #999;
    font-style: italic;
}

.status-indicator::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

.driver-actions {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    flex: 1;
    padding: 0.6rem;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.action-btn.view {
    background: #2196f3;
    color: white;
}

.action-btn.view:hover {
    background: #1976d2;
}

.action-btn.edit {
    background: #ff9800;
    color: white;
}

.action-btn.edit:hover {
    background: #f57c00;
}

.action-btn.delete {
    background: #f44336;
    color: white;
}

.action-btn.delete:hover {
    background: #d32f2f;
}

.page-header {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #666;
    margin-bottom: 1.5rem;
}

.add-driver-btn {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); /* Green gradient for drivers */
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.add-driver-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(46, 204, 113, 0.4); /* Green shadow */
    color: white;
}

.assign-driver-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Purple gradient */
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.assign-driver-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4); /* Purple shadow */
    color: white;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #999;
    margin-bottom: 1.5rem;
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
    max-height: 90vh;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.modal-container > form {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
}

.modal-container.small {
    max-width: 500px;
}

.modal-header {
    padding: 25px 30px;
    background: linear-gradient(135deg, #2ecc71, #27ae60); /* Green gradient */
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid rgba(255, 255, 255, 0.2);
}

.modal-header.blue {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.modal-header.red {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.modal-header.orange {
    background: linear-gradient(135deg, #e67e22, #d35400);
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
    overflow-x: hidden;
    flex: 1;
    position: relative;
    min-height: 0;
}

/* Custom scrollbar for modal body */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
    margin: 5px 0;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #2ecc71;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #27ae60;
}

.modal-footer {
    padding: 20px 30px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex !important;
    justify-content: flex-end;
    gap: 12px;
    flex-shrink: 0;
    z-index: 10;
    visibility: visible !important;
}

/* Driver Detail Sections */
.driver-detail-grid {
    display: grid;
    gap: 20px;
}

.driver-detail-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #2ecc71; /* Green border for drivers */
}

.driver-detail-section.blue {
    border-left-color: #3498db;
}

.driver-detail-section.orange {
    border-left-color: #f39c12;
}

.driver-detail-section h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 700;
}

.driver-detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 15px;
}

.driver-detail-row:last-child {
    margin-bottom: 0;
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

/* Form Styles */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group label .required {
    color: #e74c3c;
    margin-left: 3px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    font-family: inherit;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #2ecc71;
    box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
}

.form-control:disabled {
    background: #f8f9fa;
    cursor: not-allowed;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
    margin-bottom: 5px;
}

/* Button Styles */
.btn {
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #27ae60, #229954);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover:not(:disabled) {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.btn-danger:hover:not(:disabled) {
    background: linear-gradient(135deg, #c0392b, #a93226);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

.btn-warning {
    background: linear-gradient(135deg, #e67e22, #d35400);
    color: white;
}

.btn-warning:hover:not(:disabled) {
    background: linear-gradient(135deg, #d35400, #ba4a00);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(230, 126, 34, 0.3);
}

/* Status Badge */
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-badge.inactive {
    background: #ffebee;
    color: #c62828;
}

/* Delete Confirmation Modal Content */
.delete-confirmation {
    text-align: center;
    padding: 20px 0;
}

.delete-confirmation i {
    font-size: 64px;
    color: #e74c3c;
    margin-bottom: 20px;
}

.delete-confirmation h3 {
    color: #2c3e50;
    font-size: 24px;
    margin-bottom: 12px;
    font-weight: 700;
}

.delete-confirmation p {
    color: #7f8c8d;
    font-size: 15px;
    margin-bottom: 8px;
}

.delete-confirmation .driver-name {
    color: #e74c3c;
    font-weight: 700;
    font-size: 18px;
}

.delete-confirmation .warning-text {
    background: #fff3cd;
    border: 2px solid #ffc107;
    border-radius: 10px;
    padding: 15px;
    margin-top: 20px;
    color: #856404;
    font-weight: 600;
}

/* Action Buttons in Table */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.action-btn.view {
    background: #3498db;
    color: white;
}

.action-btn.view:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
}

.action-btn.edit {
    background: #e67e22;
    color: white;
}

.action-btn.edit:hover {
    background: #d35400;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(230, 126, 34, 0.3);
}

.action-btn.delete {
    background: #e74c3c;
    color: white;
}

.action-btn.delete:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
}

/* Loading Spinner */
.loading-spinner {
    text-align: center;
    padding: 40px;
    color: #95a5a6;
}

.loading-spinner i {
    font-size: 48px;
    animation: spin 1s linear infinite;
    color: #2ecc71;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #999;
    margin-bottom: 1.5rem;
}

/* Search Bar */
.search-bar {
    margin-bottom: 20px;
}

.search-input {
    width: 100%;
    max-width: 400px;
    padding: 12px 20px 12px 45px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%237f8c8d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='m21 21-4.35-4.35'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: 15px center;
}

.search-input:focus {
    outline: none;
    border-color: #2ecc71;
    box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
}

/* Alert Messages */
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert i {
    font-size: 20px;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .driver-actions {
        flex-direction: column;
    }

    .modal-container {
        width: 95%;
        max-height: 95vh;
        margin: 10px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-header {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
        flex-direction: column-reverse;
    }

    .modal-footer .btn {
        width: 100%;
        justify-content: center;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .form-group {
        margin-bottom: 15px;
    }
}

@media (max-width: 576px) {
    .modal-overlay {
        padding: 0;
    }

    .modal-container {
        width: 100%;
        max-height: 100vh;
        border-radius: 0;
        margin: 0;
    }

    .modal-header {
        border-radius: 0;
    }

    .modal-body {
        padding: 15px;
    }

    .form-control {
        padding: 10px 14px;
        font-size: 16px; /* Prevents zoom on iOS */
    }
}

/* Enhanced Driver Detail Modal Styles */
.driver-profile-header {
    display: flex;
    align-items: center;
    gap: 25px;
    padding: 25px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    margin-bottom: 25px;
    border: 1px solid #dee2e6;
}

.driver-photo-container {
    flex-shrink: 0;
}

.driver-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #2ecc71;
    box-shadow: 0 8px 25px rgba(46, 204, 113, 0.3);
}

.driver-photo-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: white;
    border: 4px solid #27ae60;
    box-shadow: 0 8px 25px rgba(46, 204, 113, 0.3);
}

.driver-info-header {
    flex: 1;
}

.driver-info-header h3 {
    margin: 0 0 8px 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
}

.driver-info-header .driver-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    color: #6c757d;
    font-size: 0.95rem;
}

.driver-info-header .driver-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.driver-info-header .driver-meta i {
    color: #2ecc71;
}

/* Enhanced Section Styling */
.driver-detail-section.enhanced {
    background: white;
    padding: 25px;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.driver-detail-section.enhanced h4 {
    margin: 0 0 20px 0;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.1rem;
    font-weight: 700;
}

.driver-detail-section.enhanced h4 i {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.driver-detail-section.enhanced.personal h4 i {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
}

.driver-detail-section.enhanced.biodata h4 i {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    color: white;
}

.driver-detail-section.enhanced.license h4 i {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #2ecc71;
}

.info-item.blue {
    border-left-color: #3498db;
}

.info-item.purple {
    border-left-color: #9b59b6;
}

.info-item.orange {
    border-left-color: #e67e22;
}

.info-item .label {
    font-size: 11px;
    text-transform: uppercase;
    color: #7f8c8d;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.info-item .value {
    font-size: 1rem;
    color: #2c3e50;
    font-weight: 600;
}

/* Clickable Image Container */
.clickable-image-container {
    position: relative;
    cursor: pointer;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.clickable-image-container:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.clickable-image-container img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
    background: #f8f9fa;
    display: block;
}

.clickable-image-container .image-overlay {
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

.clickable-image-container .image-overlay i {
    color: white;
    font-size: 2rem;
}

.clickable-image-container .image-overlay span {
    color: white;
    font-weight: 600;
    margin-left: 10px;
}

/* No Image Placeholder */
.no-image-placeholder {
    padding: 40px;
    background: #f8f9fa;
    border-radius: 12px;
    text-align: center;
    color: #95a5a6;
    border: 2px dashed #dee2e6;
}

.no-image-placeholder i {
    font-size: 3rem;
    margin-bottom: 10px;
    display: block;
}

/* License Status Badges */
.license-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
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

.license-status-badge i {
    font-size: 0.9rem;
}

/* License Image Section */
.license-image-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-top: 20px;
}

.license-image-section .image-wrapper {
    flex: 1;
}

.license-image-section .license-details {
    flex: 1;
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
    z-index: 99999;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.fullscreen-viewer.active {
    display: flex;
}

.fullscreen-viewer img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 10px 50px rgba(0,0,0,0.5);
}

.fullscreen-viewer .close-fullscreen {
    position: absolute;
    top: 20px;
    right: 30px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.fullscreen-viewer .close-fullscreen:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.fullscreen-viewer .image-title {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    background: rgba(0, 0, 0, 0.5);
    padding: 10px 25px;
    border-radius: 25px;
}

/* Responsive adjustments for enhanced modal */
@media (max-width: 768px) {
    .driver-profile-header {
        flex-direction: column;
        text-align: center;
    }

    .driver-info-header .driver-meta {
        justify-content: center;
    }

    .license-image-section {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-users"></i> My Drivers</h1>
            <p class="mb-0">Manage your team of drivers</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="openAssignDriverModal()" class="assign-driver-btn">
                <i class="fas fa-link"></i> Assign Driver to Unit
            </button>
            <button type="button" onclick="openAddDriverModal()" class="add-driver-btn">
                <i class="fas fa-plus"></i> Add New Driver
            </button>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs-container mb-4">
    <ul class="nav nav-tabs" id="driverTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="approved-tab" data-toggle="tab" data-target="#approved" type="button" role="tab">
                <i class="fas fa-check-circle"></i> Approved Drivers <span class="badge badge-success ml-2">{{ $approvedDrivers->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-toggle="tab" data-target="#pending" type="button" role="tab">
                <i class="fas fa-clock"></i> Pending Applications <span class="badge badge-warning ml-2">{{ $pendingDrivers->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="driverTabsContent">
        <!-- Approved Drivers Tab -->
        <div class="tab-pane fade show active" id="approved" role="tabpanel">
            <div class="row">
                @forelse($approvedDrivers as $driver)
                <div class="col-md-6 col-lg-4">
                    <div class="driver-card">
                        <div class="driver-header">
                            <div class="driver-icon">
                                @if($driver->photo_url)
                                    <img src="{{ $driver->photo_url }}" 
                                        alt="{{ $driver->first_name }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div class="driver-name-display">{{ $driver->first_name }} {{ $driver->last_name }}</div>
                            <div class="text-center">
                                <span class="driver-status-badge">{{ ucfirst($driver->status) }}</span>
                            </div>
                        </div>
                        <div class="driver-body">
                            <div class="spec-row">
                                <div class="spec-label"><i class="fas fa-id-card"></i> License No.</div>
                                <div class="spec-value">{{ $driver->license_number ?? 'N/A' }}</div>
                            </div>
                            <div class="spec-row">
                                <div class="spec-label"><i class="fas fa-phone"></i> Phone</div>
                                <div class="spec-value">{{ $driver->phone ?? 'N/A' }}</div>
                            </div>
                            <div class="spec-row">
                                <div class="spec-label"><i class="fas fa-bus"></i> Assigned Unit</div>
                                <div class="spec-value">
                                    @if($driver->unit)
                                        <span class="unit-assigned">{{ $driver->unit->plate_no }}</span>
                                    @else
                                        <span class="no-unit">Not Assigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="driver-actions">
                            <button type="button" class="action-btn view" onclick="viewDriver({{ $driver->id }})"><i class="fas fa-eye"></i> View</button>
                            <button type="button" class="action-btn edit" onclick="editDriver({{ $driver->id }})"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" class="action-btn delete" onclick="confirmDeleteDriver({{ $driver->id }}, '{{ $driver->first_name }} {{ $driver->last_name }}')"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12"><div class="empty-state"><i class="fas fa-users"></i><p>No approved drivers yet</p></div></div>
                @endforelse
            </div>
        </div>

        <!-- Pending Applications Tab -->
        <div class="tab-pane fade" id="pending" role="tabpanel">
            <div class="row">
                @forelse($pendingDrivers as $driver)
                <div class="col-md-6 col-lg-4">
                    <div class="driver-card">
                        <div class="driver-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                            <div class="driver-icon">
                                @if($driver->photo_url)
                                    <img src="{{ $driver->photo_url }}" 
                                        alt="{{ $driver->first_name }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                @else
                                    <i class="fas fa-user-clock"></i>
                                @endif
                            </div>
                            <div class="driver-name-display">{{ $driver->first_name }} {{ $driver->last_name }}</div>
                            <div class="text-center"><span class="driver-status-badge">Pending Approval</span></div>
                        </div>
                        <div class="driver-body">
                            <div class="spec-row">
                                <div class="spec-label"><i class="fas fa-id-card"></i> License No.</div>
                                <div class="spec-value">{{ $driver->license_number ?? 'N/A' }}</div>
                            </div>
                            <div class="spec-row">
                                <div class="spec-label"><i class="fas fa-phone"></i> Phone</div>
                                <div class="spec-value">{{ $driver->phone ?? 'N/A' }}</div>
                            </div>
                            <div class="spec-row">
                                <div class="spec-label"><i class="fas fa-calendar"></i> Submitted</div>
                                <div class="spec-value">{{ $driver->created_at->timezone('Asia/Manila')->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="driver-actions">
                            <button type="button" class="action-btn view" onclick="viewDriver({{ $driver->id }})"><i class="fas fa-eye"></i> View</button>
                            <button type="button" class="action-btn edit" onclick="editDriver({{ $driver->id }})"><i class="fas fa-edit"></i> Edit</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12"><div class="empty-state"><i class="fas fa-clock"></i><p>No pending driver applications</p></div></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Old grid hidden for backward compatibility -->
<div class="row" id="driversGrid" style="display: none;">
    @forelse($drivers as $driver)
    <div class="col-md-6 col-lg-4">
        <div class="driver-card">
            <div class="driver-header">
                <div class="driver-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="driver-name-display">{{ $driver->first_name }} {{ $driver->last_name }}</div>
                <div class="text-center">
                    <span class="driver-status-badge">{{ ucfirst($driver->status) }}</span>
                </div>
            </div>

            <div class="driver-body">
                <div class="spec-row">
                    <div class="spec-label">
                        <i class="fas fa-id-card"></i>
                        License No.
                    </div>
                    <div class="spec-value">{{ $driver->license_number ?? 'N/A' }}</div>
                </div>

                <div class="spec-row">
                    <div class="spec-label">
                        <i class="fas fa-phone"></i>
                        Phone
                    </div>
                    <div class="spec-value">{{ $driver->phone ?? 'N/A' }}</div>
                </div>

            </div>

            <div class="driver-actions">
                <button type="button" class="action-btn view" onclick="viewDriver({{ $driver->id }})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button type="button" class="action-btn edit" onclick="editDriver({{ $driver->id }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button type="button" class="action-btn delete" onclick="confirmDeleteDriver({{ $driver->id }}, '{{ $driver->first_name }} {{ $driver->last_name }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>No Drivers Yet</h3>
            <p>Start building your team by adding your first driver</p>
            <button type="button" onclick="openAddDriverModal()" class="add-driver-btn">
                <i class="fas fa-plus"></i> Add Your First Driver
            </button>
        </div>
    </div>
    @endforelse
</div>

@if($drivers->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $drivers->links() }}
</div>
@endif

<!-- View Driver Modal -->
<div id="viewDriverModal" class="modal-overlay" onclick="closeModal('viewDriverModal')">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title" id="viewDriverTitle">
                <i class="fas fa-user"></i>
                Driver Details
            </h2>
            <button class="modal-close" onclick="closeModal('viewDriverModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="viewDriverContent">
            <div class="loading-spinner">
                <i class="fas fa-spinner"></i>
                <p>Loading driver details...</p>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Image Viewer -->
<div id="fullscreenImageViewer" class="fullscreen-viewer" onclick="closeFullscreenImage()">
    <button class="close-fullscreen" onclick="closeFullscreenImage()">
        <i class="fas fa-times"></i>
    </button>
    <img id="fullscreenImage" src="" alt="Fullscreen View" onclick="event.stopPropagation()">
    <div class="image-title" id="fullscreenImageTitle"></div>
</div>

<!-- Edit Driver Modal -->
<div id="editDriverModal" class="modal-overlay" onclick="closeModal('editDriverModal')">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header orange">
            <h2 class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Driver Information
            </h2>
            <button class="modal-close" onclick="closeModal('editDriverModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editDriverForm" onsubmit="submitEditDriver(event)">
            <div class="modal-body" id="editDriverContent">
                <div class="loading-spinner">
                    <i class="fas fa-spinner"></i>
                    <p>Loading driver information...</p>
                </div>
            </div>
            <div class="modal-footer" id="editDriverFooter">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editDriverModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-warning" id="updateDriverBtn">
                    <i class="fas fa-save"></i> Update Driver
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Driver Modal -->
<div id="addDriverModal" class="modal-overlay" onclick="closeModal('addDriverModal')">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header blue">
            <h2 class="modal-title">
                <i class="fas fa-user-plus"></i>
                Add New Driver
            </h2>
            <button class="modal-close" onclick="closeModal('addDriverModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="addDriverForm" onsubmit="submitAddDriver(event)">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Birthdate <span class="required" style="min-height: 46px;">*</span></label>
                        <input type="date" class="form-control" name="birthdate" required>
                    </div>
                    <div class="form-group">
                        <label>Sex <span class="required">*</span></label>
                        <select class="form-control"  style="min-height: 46px;" name="sex" required>
                            <option value="">Select Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Driver Photo</label>
                        <input type="file" class="form-control" style="min-height: 46px;" name="photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewDriverImage(this, 'driverPhotoPreview')">
                        <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                        <div id="driverPhotoPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                            <img src="" alt="Driver Photo Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Biodata Photo</label>
                        <input type="file" class="form-control" style="min-height: 46px;" name="biodata_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewDriverImage(this, 'biodataPreview')">
                        <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                        <div id="biodataPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                            <img src="" alt="Biodata Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>License Number <span class="required">*</span></label>
                        <input type="text" class="form-control" name="license_no" required>
                    </div>
                    <div class="form-group">
                        <label>License Type</label>
                        <select class="form-control" name="license_type">
                            <option value="">Select License Type</option>
                            <option value="Professional">Professional</option>
                            <option value="Non-Professional">Non-Professional</option>
                            <option value="Student Permit">Student Permit</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>License Expiry <span class="required">*</span></label>
                        <input type="date" class="form-control" name="license_expiry" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>License Restrictions</label>
                        <input type="text" class="form-control" name="license_restrictions" placeholder="e.g., 1, 2, 3">
                    </div>
                    <div class="form-group">
                        <label>DL Codes</label>
                        <input type="text" class="form-control" name="dl_codes" placeholder="e.g., A, B, C">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>License Photo</label>
                        <input type="file" class="form-control" style="min-height: 46px;" name="license_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewDriverImage(this, 'licensePreview')">
                        <small class="form-text text-muted" style="min-height: 46px;">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                        <div id="licensePreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                            <img src="" alt="License Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select class="form-control" style="min-height: 46px;" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Emergency Contact</label>
                        <input type="tel" class="form-control" name="emergency_contact">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addDriverModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="addDriverBtn">
                    <i class="fas fa-plus"></i> Add Driver
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteDriverModal" class="modal-overlay" onclick="closeModal('deleteDriverModal')">
    <div class="modal-container small" onclick="event.stopPropagation()">
        <div class="modal-header red">
            <h2 class="modal-title">
                <i class="fas fa-exclamation-triangle"></i>
                Confirm Deletion
            </h2>
            <button class="modal-close" onclick="closeModal('deleteDriverModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="delete-confirmation">
                <i class="fas fa-exclamation-circle"></i>
                <h3>Delete Driver?</h3>
                <p>Are you sure you want to delete this driver?</p>
                <p class="driver-name" id="deleteDriverName"></p>
                <div class="warning-text">
                    <i class="fas fa-info-circle"></i>
                    This action cannot be undone!
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteDriverModal')">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                <i class="fas fa-trash"></i> Yes, Delete
            </button>
        </div>
    </div>
</div>

<!-- Driver Registration Confirmation Modal -->
<div id="driverConfirmationModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 600px; margin: 0;" onclick="event.stopPropagation()">
        <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <h2 class="modal-title" style="color: white;">
                <i class="fas fa-check-circle"></i>
                Driver Registration Submitted!
            </h2>
        </div>
        <div class="modal-body" style="text-align: center; padding: 2rem;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; animation: successPulse 2s ease-in-out infinite;">
                <i class="fas fa-clock" style="font-size: 40px; color: white;"></i>
            </div>

            <h3 style="color: #667eea; font-weight: 700; margin-bottom: 1rem; font-size: 1.5rem;">
                Registration Pending Approval
            </h3>

            <p style="color: #6c757d; margin-bottom: 1.5rem; line-height: 1.6;">
                Thank you for submitting your driver registration. Your application is now pending review by the administrator.
            </p>

            <div style="background: #f8f9fa; border-left: 4px solid #667eea; padding: 1rem; border-radius: 8px; margin: 1rem 0; text-align: left;">
                <h4 style="color: #667eea; font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-info-circle"></i>
                    What happens next?
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 0.5rem 0; color: #495057; display: flex; align-items: start; gap: 10px; font-size: 0.875rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-top: 3px;"></i>
                        <span>The admin team will review your driver's information and submitted documents.</span>
                    </li>
                    <li style="padding: 0.5rem 0; color: #495057; display: flex; align-items: start; gap: 10px; font-size: 0.875rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-top: 3px;"></i>
                        <span>You will be notified once the driver registration has been reviewed.</span>
                    </li>
                    <li style="padding: 0.5rem 0; color: #495057; display: flex; align-items: start; gap: 10px; font-size: 0.875rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-top: 3px;"></i>
                        <span>The review process typically takes 1-3 business days.</span>
                    </li>
                </ul>
            </div>

            <p style="font-size: 14px; color: #6c757d; margin-top: 1rem;">
                <i class="fas fa-question-circle"></i>
                If you have any questions, please contact the administrator.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="closeDriverConfirmation()" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; padding: 0.75rem 2rem;">
                <i class="fas fa-check"></i> OK, Got It!
            </button>
        </div>
    </div>
</div>

<!-- Assign Driver to Unit Modal -->
<div class="modal fade" id="assignDriverModal" tabindex="-1" role="dialog" style="z-index: 10001;">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; padding: 1.5rem;">
                <h2 class="modal-title" style="color: white; display: flex; align-items: center; gap: 10px; margin: 0;">
                    <i class="fas fa-link"></i>
                    Assign Drivers to Units
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
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
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 1.5rem;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="padding: 0.75rem 1.5rem;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveDriverAssignments()" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none; padding: 0.75rem 1.5rem;">
                    <i class="fas fa-save"></i> Save Assignments
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Assignment Table Styles */
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
</style>

<style>
@keyframes successPulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 20px rgba(102, 126, 234, 0);
    }
}

/* Tabs Styling */
.tabs-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-tabs {
    border-bottom: 2px solid #e0e0e0;
    margin-bottom: 0;
}

.nav-tabs .nav-item {
    margin-bottom: -2px;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #495057;
    background-color: #f8f9fa;
}

.nav-tabs .nav-link.active {
    color: #2c3e50;
    background-color: transparent;
    border-bottom: 3px solid #667eea;
}

.nav-tabs .nav-link i {
    margin-right: 5px;
}

.tab-content {
    padding: 20px 0;
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

.empty-state p {
    font-size: 18px;
    margin: 0;
}
</style>

@endsection

@push('scripts')
<script>
    let currentDriverId = null;

    // Modal Functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Show and close driver confirmation modal
    function showDriverConfirmation() {
        const modal = document.getElementById('driverConfirmationModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            console.error('driverConfirmationModal element not found!');
        }
    }

    function closeDriverConfirmation() {
        document.getElementById('driverConfirmationModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        // Store that we should show pending tab after reload
        sessionStorage.setItem('showPendingTab', 'true');
        // Reload the page to show the newly added driver in pending
        window.location.reload();
    }

    // View Driver
    function viewDriver(driverId) {
        openModal('viewDriverModal');

        fetch(apiUrl(`drivers/${driverId}`))
            .then(response => response.json())
            .then(data => {
                // Extract driver from the response data
                displayDriverDetails(data.driver || data);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('viewDriverContent').innerHTML =
                    '<p style="text-align:center; color:#e74c3c;"><i class="fas fa-exclamation-triangle"></i><br><br>Error loading driver details</p>';
            });
    }

    function displayDriverDetails(driver) {
        document.getElementById('viewDriverTitle').innerHTML = `
            <i class="fas fa-user"></i>
            Driver Details
        `;

        // Generate photo HTML
        const photoHtml = driver.photo_url
            ? `<img src="${driver.photo_url}" alt="Driver Photo" class="driver-photo">`
            : `<div class="driver-photo-placeholder"><i class="fas fa-user"></i></div>`;

        // Generate biodata image HTML
        const biodataImageHtml = driver.biodata_photo_url
            ? `<div class="clickable-image-container" onclick="openFullscreenImage('${driver.biodata_photo_url}', 'Biodata Document')">
                    <img src="${driver.biodata_photo_url}" alt="Biodata Document">
                    <div class="image-overlay">
                        <i class="fas fa-expand"></i>
                        <span>Click to view fullscreen</span>
                    </div>
               </div>`
            : `<div class="no-image-placeholder">
                    <i class="fas fa-file-image"></i>
                    <p>No biodata image uploaded</p>
               </div>`;

        // Generate license image HTML
        const licenseImageHtml = driver.license_photo_url
            ? `<div class="clickable-image-container" onclick="openFullscreenImage('${driver.license_photo_url}', 'Driver License')">
                    <img src="${driver.license_photo_url}" alt="Driver License">
                    <div class="image-overlay">
                        <i class="fas fa-expand"></i>
                        <span>Click to view fullscreen</span>
                    </div>
               </div>`
            : `<div class="no-image-placeholder">
                    <i class="fas fa-id-card"></i>
                    <p>No license image uploaded</p>
               </div>`;

        // License status badge
        const licenseStatus = driver.license_status || 'valid';
        let statusIcon = 'fa-check-circle';
        let statusText = 'Valid';
        if (licenseStatus === 'expired') {
            statusIcon = 'fa-times-circle';
            statusText = 'Expired';
        } else if (licenseStatus === 'expiring_soon') {
            statusIcon = 'fa-exclamation-triangle';
            statusText = 'Expiring Soon';
        }

        const content = `
            <!-- Driver Profile Header with Photo -->
            <div class="driver-profile-header">
                <div class="driver-photo-container">
                    ${photoHtml}
                </div>
                <div class="driver-info-header">
                    <h3>${driver.first_name} ${driver.last_name}</h3>
                    <div class="driver-meta">
                        <span><i class="fas fa-birthday-cake"></i> ${driver.age ? driver.age + ' years old' : 'Age N/A'}</span>
                        <span><i class="fas fa-venus-mars"></i> ${driver.sex || 'N/A'}</span>
                        <span><i class="fas fa-phone"></i> ${driver.phone || 'N/A'}</span>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="driver-detail-section enhanced personal">
                <h4>
                    <i class="fas fa-user-circle"></i>
                    Personal Information
                </h4>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">Full Name</div>
                        <div class="value">${driver.first_name} ${driver.last_name}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Age</div>
                        <div class="value">${driver.age ? driver.age + ' years old' : 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Sex</div>
                        <div class="value">${driver.sex || 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Contact Number</div>
                        <div class="value">${driver.phone || 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Address</div>
                        <div class="value">${driver.address || 'N/A'}</div>
                    </div>
                    <div class="info-item orange">
                        <div class="label">Emergency Contact</div>
                        <div class="value">${driver.emergency_contact || 'N/A'}</div>
                    </div>
                </div>
            </div>

            <!-- Biodata Section -->
            <div class="driver-detail-section enhanced biodata">
                <h4>
                    <i class="fas fa-file-alt"></i>
                    Biodata Document
                </h4>
                ${biodataImageHtml}
            </div>

            <!-- License Information Section -->
            <div class="driver-detail-section enhanced license">
                <h4>
                    <i class="fas fa-id-card"></i>
                    License Information
                </h4>
                <div class="license-image-section">
                    <div class="image-wrapper">
                        ${licenseImageHtml}
                    </div>
                    <div class="license-details">
                        <div class="info-grid" style="grid-template-columns: 1fr;">
                            <div class="info-item blue">
                                <div class="label">License Number</div>
                                <div class="value">${driver.license_number || 'N/A'}</div>
                            </div>
                            <div class="info-item blue">
                                <div class="label">Restriction</div>
                                <div class="value">${driver.license_restrictions || 'N/A'}</div>
                            </div>
                            <div class="info-item blue">
                                <div class="label">DL Codes</div>
                                <div class="value">${driver.dl_codes || 'N/A'}</div>
                            </div>
                            <div class="info-item blue">
                                <div class="label">Validity / Expiry Date</div>
                                <div class="value">
                                    ${driver.license_expiry || 'N/A'}
                                    <br>
                                    <span class="license-status-badge ${licenseStatus}" style="margin-top: 8px;">
                                        <i class="fas ${statusIcon}"></i>
                                        ${statusText}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('viewDriverContent').innerHTML = content;
    }

    // Fullscreen image viewer functions
    function openFullscreenImage(imageUrl, title) {
        const viewer = document.getElementById('fullscreenImageViewer');
        const img = document.getElementById('fullscreenImage');
        const titleEl = document.getElementById('fullscreenImageTitle');

        img.src = imageUrl;
        titleEl.textContent = title;
        viewer.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFullscreenImage() {
        const viewer = document.getElementById('fullscreenImageViewer');
        viewer.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Edit Driver
    function editDriver(driverId) {
    currentDriverId = driverId;
    openModal('editDriverModal');
    
    // Ensure footer is visible when modal opens
    const footer = document.getElementById('editDriverFooter');
    if (footer) {
        footer.style.display = 'flex';
        footer.style.visibility = 'visible';
    }
    
    fetch(apiUrl(`drivers/${driverId}`))
        .then(response => response.json())
        .then(data => {
            displayEditForm(data);
            // Ensure footer is still visible after content loads
            const footer = document.getElementById('editDriverFooter');
            if (footer) {
                footer.style.display = 'flex';
                footer.style.visibility = 'visible';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('editDriverContent').innerHTML = 
                '<p style="text-align:center; color:#e74c3c;"><i class="fas fa-exclamation-triangle"></i><br><br>Error loading driver information</p>';
        });
}

    function displayEditForm(driver) {
        const content = `
            <div class="form-row">
                <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input type="text" class="form-control" name="first_name" value="${driver.first_name || ''}" required>
                </div>
                <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" class="form-control" name="last_name" value="${driver.last_name || ''}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" class="form-control" name="date_of_birth" value="${driver.birthdate_raw || ''}">
                </div>
                <div class="form-group">
                    <label>Phone Number <span class="required">*</span></label>
                    <input type="tel" class="form-control" name="phone" value="${driver.phone || ''}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" value="${driver.address || ''}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Driver Photo</label>
                    <input type="file" class="form-control" style="min-height: 46px;" name="photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewDriverImage(this, 'editDriverPhotoPreview')">
                    <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                    ${driver.photo_url ? `
                        <div class="image-preview-container" style="margin-top: 10px;">
                            <img src="${driver.photo_url}" alt="Current Driver Photo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
                        </div>
                    ` : ''}
                    <div id="editDriverPhotoPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                        <img src="" alt="New Driver Photo Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                    </div>
                </div>
                <div class="form-group">
                    <label>Biodata Photo</label>
                    <input type="file" class="form-control" style="min-height: 46px;" name="biodata_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewDriverImage(this, 'editBiodataPreview')">
                    <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                    ${driver.biodata_photo_url ? `
                        <div class="image-preview-container" style="margin-top: 10px;">
                            <img src="${driver.biodata_photo_url}" alt="Current Biodata Photo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
                        </div>
                    ` : ''}
                    <div id="editBiodataPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                        <img src="" alt="New Biodata Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>License Number <span class="required">*</span></label>
                    <input type="text" class="form-control" name="license_number" value="${driver.license_number || ''}" required>
                </div>
                <div class="form-group">
                    <label>License Type</label>
                    <select class="form-control" name="license_type">
                        <option value="">Select Type</option>
                        <option value="professional" ${driver.license_type === 'professional' ? 'selected' : ''}>Professional</option>
                        <option value="non-professional" ${driver.license_type === 'non-professional' ? 'selected' : ''}>Non-Professional</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>License Expiry</label>
                    <input type="date" class="form-control" name="license_expiry" value="${driver.license_expiry_raw || ''}">
                </div>
                <div class="form-group">
                    <label>License Restrictions</label>
                    <input type="text" class="form-control" name="license_restrictions" value="${driver.license_restrictions || ''}" placeholder="e.g., 1, 2, 3">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>DL Codes</label>
                    <input type="text" class="form-control" name="dl_codes" value="${driver.dl_codes || ''}" placeholder="e.g., A, B, C">
                </div>
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select class="form-control" name="status" required>
                        <option value="active" ${driver.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="inactive" ${driver.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>License Photo</label>
                    <input type="file" class="form-control" style="min-height: 46px;" name="license_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewDriverImage(this, 'editLicensePreview')">
                    <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                    ${driver.license_photo_url ? `
                        <div class="image-preview-container" style="margin-top: 10px;">
                            <img src="${driver.license_photo_url}" alt="Current License Photo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
                        </div>
                    ` : ''}
                    <div id="editLicensePreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                        <img src="" alt="New License Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                    </div>
                </div>
                <div class="form-group">
                    <label>Emergency Contact</label>
                    <input type="tel" class="form-control" name="emergency_contact" value="${driver.emergency_contact || ''}">
                </div>
            </div>
        `;

        document.getElementById('editDriverContent').innerHTML = content;

            // Force the footer to be visible after content loads
    const modalFooter = document.querySelector('#editDriverModal .modal-footer');
    if (modalFooter) {
        modalFooter.style.display = 'flex';
        modalFooter.style.visibility = 'visible';
    }
    }

    function submitEditDriver(event) {
        event.preventDefault();

        const btn = document.getElementById('updateDriverBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

        const formData = new FormData(event.target);

        // Add _method field for Laravel to recognize PUT request
        formData.append('_method', 'PUT');

        fetch(`/api/drivers/${currentDriverId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                closeModal('editDriverModal');
                // Show success message
                showSuccess('Driver updated successfully!');
                // Reload the page after a short delay to show the success message
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showError('Error updating driver: ' + (result.message || 'Unknown error'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Update Driver';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Error updating driver. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Update Driver';
        });
    }

    // Add Driver
    function openAddDriverModal() {
        openModal('addDriverModal');
    }

    // Reload drivers grid dynamically
    function reloadDriversGrid() {
        fetch(apiUrl('my-drivers'))
            .then(response => response.json())
            .then(data => {
                const grid = document.getElementById('driversGrid');

                if (!grid) {
                    console.log('driversGrid element not found, page might be using pagination layout');
                    // Reload the page to show the new driver
                    window.location.reload();
                    return;
                }

                const drivers = data.drivers || data;

                if (!drivers || drivers.length === 0) {
                    grid.innerHTML = `
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h3>No Drivers Yet</h3>
                                <p>Start building your team by adding your first driver</p>
                                <button type="button" onclick="openAddDriverModal()" class="add-driver-btn">
                                    <i class="fas fa-plus"></i> Add Your First Driver
                                </button>
                            </div>
                        </div>
                    `;
                    return;
                }

                let html = '';
                drivers.forEach(driver => {
                    const statusBadge = driver.status === 'active' ? 'active' : 'inactive';
                    html += `
                        <div class="col-md-6 col-lg-4">
                            <div class="driver-card">
                                <div class="driver-header">
                                    <div class="driver-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="driver-name-display">${driver.first_name} ${driver.last_name}</div>
                                    <div class="text-center">
                                        <span class="driver-status-badge">${driver.status.charAt(0).toUpperCase() + driver.status.slice(1)}</span>
                                    </div>
                                </div>

                                <div class="driver-body">
                                    <div class="spec-row">
                                        <div class="spec-label">
                                            <i class="fas fa-id-card"></i>
                                            License No.
                                        </div>
                                        <div class="spec-value">${driver.license_number || 'N/A'}</div>
                                    </div>

                                    <div class="spec-row">
                                        <div class="spec-label">
                                            <i class="fas fa-phone"></i>
                                            Phone
                                        </div>
                                        <div class="spec-value">${driver.phone || 'N/A'}</div>
                                    </div>
                                </div>

                                <div class="driver-actions">
                                    <button type="button" class="action-btn view" onclick="viewDriver(${driver.id})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button type="button" class="action-btn edit" onclick="editDriver(${driver.id})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="action-btn delete" onclick="confirmDeleteDriver(${driver.id}, '${driver.first_name} ${driver.last_name}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                grid.innerHTML = html;
            })
            .catch(error => {
                console.error('Error reloading drivers:', error);
                showAlert('Error reloading drivers list', 'error');
            });
    }

    function submitAddDriver(event) {
        event.preventDefault();

        const btn = document.getElementById('addDriverBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        const formData = new FormData(event.target);

        fetch('/api/drivers', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw { status: response.status, data: data };
                });
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                closeModal('addDriverModal');
                document.getElementById('addDriverForm').reset();
                // Hide all image previews
                document.querySelectorAll('.image-preview-container').forEach(el => el.style.display = 'none');
                // Show confirmation modal
                showDriverConfirmation();
            } else {
                showError('Error adding driver: ' + (result.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Handle validation errors
            if (error.status === 422 && error.data && error.data.errors) {
                const errors = error.data.errors;
                let errorMessages = [];

                for (let field in errors) {
                    const fieldName = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    errors[field].forEach(msg => {
                        errorMessages.push(msg);
                    });
                }

                // Show first error with details
                if (errorMessages.length > 0) {
                    showError(errorMessages[0] + (errorMessages.length > 1 ? ` (and ${errorMessages.length - 1} more errors)` : ''));

                    // Log all errors to console for debugging
                    console.error('Validation errors:', errors);
                }
            } else if (error.data && error.data.message) {
                showError('Error: ' + error.data.message);
            } else {
                showError('Error adding driver. Please try again.');
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Add Driver';
        });
    }

    // Delete Driver
    function confirmDeleteDriver(driverId, driverName) {
        currentDriverId = driverId;
        document.getElementById('deleteDriverName').textContent = driverName;
        openModal('deleteDriverModal');
        
        document.getElementById('confirmDeleteBtn').onclick = function() {
            deleteDriver(driverId);
        };
    }

    function deleteDriver(driverId) {
        const btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

        fetch(`/api/drivers/${driverId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                closeModal('deleteDriverModal');
                showAlert('Driver deleted successfully!', 'success');
                reloadDriversGrid();
            } else {
                showAlert('Error deleting driver: ' + (result.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting driver. Please try again.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash"></i> Yes, Delete';
        });
    }

    // Search Functionality (needs adaptation for cards)
    const searchDriversElement = document.getElementById('searchDrivers');
    if (searchDriversElement) {
        searchDriversElement.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const driverCards = document.querySelectorAll('.driver-card');

            driverCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.closest('.col-md-6.col-lg-4').style.display = '';
                } else {
                    card.closest('.col-md-6.col-lg-4').style.display = 'none';
                }
            });
        });
    }

    // Alert Function
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${type}`;
        alertDiv.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            ${message}
        `;

        // Insert at the top of the content
        const container = document.querySelector('.content-wrapper') || document.querySelector('.container-fluid') || document.body;
        if (container.firstChild) {
            container.insertBefore(alertDiv, container.firstChild);
        } else {
            container.appendChild(alertDiv);
        }

        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.5s ease';
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                alertDiv.remove();
            }, 500);
        }, 5000);
    }

    // Allowed image extensions
    const allowedImageExtensions = ['png', 'jpg', 'jpeg'];

    // Validate file extension
    function validateFileExtension(file, allowedExtensions) {
        const fileName = file.name.toLowerCase();
        const extension = fileName.split('.').pop();
        return allowedExtensions.includes(extension);
    }

    // Preview Image Function with PNG, JPG, JPEG validation
    function validateAndPreviewDriverImage(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        const previewImg = previewContainer.querySelector('img');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes

            // Validate file extension - only PNG, JPG, JPEG allowed
            if (!validateFileExtension(file, allowedImageExtensions)) {
                showError('Invalid file type. Please upload a PNG, JPG, or JPEG image only.');
                input.value = ''; // Clear the input
                previewContainer.style.display = 'none';
                previewImg.src = '';
                return;
            }

            // Validate file size
            if (file.size > maxSize) {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                showError(`File size (${fileSizeMB}MB) exceeds the maximum allowed size of 2MB. Please choose a smaller image.`);
                input.value = ''; // Clear the input
                previewContainer.style.display = 'none';
                previewImg.src = '';
                return;
            }

            // File is valid, proceed with preview
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };

            reader.onerror = function() {
                showError('Error reading file. Please try again.');
                input.value = '';
                previewContainer.style.display = 'none';
                previewImg.src = '';
            };

            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            previewImg.src = '';
        }
    }

    // Legacy function for backward compatibility
    function previewImage(input, previewId) {
        validateAndPreviewDriverImage(input, previewId);
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('viewDriverModal');
            closeModal('editDriverModal');
            closeModal('deleteDriverModal');
            closeModal('addDriverModal');
        }
    });

    // Auto-hide success/error messages
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });

        // Check if we should show the pending tab after adding a driver
        if (sessionStorage.getItem('showPendingTab') === 'true') {
            sessionStorage.removeItem('showPendingTab');
            // Switch to pending tab
            const pendingTab = document.getElementById('pending-tab');
            if (pendingTab) {
                pendingTab.click();
            }
        }
    });

    // Assign Driver Modal Functions
    let driversData = [];
    let unitsData = [];
    let assignedUnits = {}; // Track which units are assigned to which drivers

    function openAssignDriverModal() {
        $('#assignDriverModal').modal('show');
        loadDriversAndUnitsForAssignment();
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

            // Filter only approved/active units
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

        // Show loading state on button
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
                $('#assignDriverModal').modal('hide');
                showSuccessMessage('Driver assignments saved successfully!');
                // Reload the page to show updated assignments
                setTimeout(() => {
                    location.reload();
                }, 1500);
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

    function showSuccessMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '10000';
        alertDiv.style.minWidth = '300px';
        alertDiv.innerHTML = `
            <strong><i class="fas fa-check-circle"></i> Success!</strong> ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.5s ease';
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 500);
        }, 3000);
    }
</script>
@endpush