@extends('layouts.app')

@section('title', 'Transport Units')
@section('page-title', 'Transport Units Management')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Transport Units</li>
@endsection

@section('content')
<style>
/* ========================================
   UNIT CARDS
   ======================================== */
.unit-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.unit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.unit-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    position: relative;
}

.unit-icon {
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

.unit-plate {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    letter-spacing: 2px;
    margin-bottom: 0.5rem;
}

.unit-type-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.25);
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.unit-body {
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
    color: #667eea;
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

.status-indicator.maintenance {
    background: #fff3e0;
    color: #ff9800;
}

.status-indicator.inactive {
    background: #ffebee;
    color: #f44336;
}

.status-indicator::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

.unit-actions {
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
    text-decoration: none;
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

.action-btn.assign {
    background: #4caf50;
    color: white;
}

.action-btn.assign:hover {
    background: #388e3c;
}

.driver-assigned {
    color: #4caf50;
    font-weight: 600;
}

.no-driver {
    color: #999;
    font-style: italic;
}

/* ========================================
   PAGE HEADER
   ======================================== */
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

.add-unit-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.add-unit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
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

/* ========================================
   MODAL SYSTEM
   ======================================== */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease;
    overflow: hidden;
}

.modal-overlay.active {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto; /* Add this */
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
    width: 100%;
    max-width: 900px;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    margin: auto; /* Add this */
}

.modal-container.small {
    max-width: 500px;
}

/* Modal Header - Fixed */
.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 25px 30px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
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
    margin: 0;
    font-size: 22px;
    font-weight: 700;
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
    padding: 0;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* Modal Body - Scrollable */
.modal-body {
    padding: 30px;
    overflow-y: auto;
    overflow-x: hidden;
    flex: 1;
    min-height: 0;
    max-height: calc(85vh - 180px); /* Ensure space for header and footer */
}

/* Custom scrollbar for modal body */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Modal Footer - Fixed */
.modal-footer {
    display: flex !important;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 30px;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    flex-shrink: 0;
    visibility: visible !important;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
    position: relative; /* Add this */
    z-index: 10; /* Add this */
}

/* ========================================
   UNIT DETAIL SECTIONS
   ======================================== */
.unit-detail-grid {
    display: grid;
    gap: 20px;
}

.unit-detail-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.unit-detail-section.blue {
    border-left-color: #3498db;
}

.unit-detail-section.orange {
    border-left-color: #f39c12;
}

.unit-detail-section h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 700;
}

.unit-detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 15px;
}

.unit-detail-row:last-child {
    margin-bottom: 0;
}

.unit-detail-label {
    font-size: 12px;
    text-transform: uppercase;
    color: #7f8c8d;
    font-weight: 600;
    margin-bottom: 5px;
}

.unit-detail-value {
    font-size: 16px;
    color: #2c3e50;
    font-weight: 600;
}

/* ========================================
   FORM STYLES
   ======================================== */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #2c3e50;
    font-size: 13px;
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
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control:disabled {
    background: #f8f9fa;
    cursor: not-allowed;
}

.form-text {
    font-size: 12px;
    color: #6c757d;
    margin-top: 5px;
}

/* Form Subsection */
.form-subsection {
    margin-top: 24px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.form-subsection-title {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-subsection-title i {
    color: #667eea;
}

/* Image Preview */
.image-preview-container {
    margin-top: 10px;
    display: none;
}

.image-preview-container.active {
    display: block;
}

.image-preview-container img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
}

/* ========================================
   BUTTON STYLES
   ======================================== */
.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #5a6fd8, #6a4092);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
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

/* ========================================
   STATUS BADGES
   ======================================== */
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

.status-badge.maintenance {
    background: #fff3e0;
    color: #ef6c00;
}

.status-badge.inactive {
    background: #ffebee;
    color: #c62828;
}

/* ========================================
   DELETE CONFIRMATION MODAL
   ======================================== */
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

.delete-confirmation .unit-name {
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

/* ========================================
   LOADING & ALERTS
   ======================================== */
.loading-spinner {
    text-align: center;
    padding: 40px;
    color: #95a5a6;
}

.loading-spinner i {
    font-size: 48px;
    animation: spin 1s linear infinite;
    color: #667eea;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

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

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */
@media (max-width: 768px) {
    .modal-container {
        max-height: 90vh;
        margin: 10px;
        max-width: 100%;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .modal-header {
        padding: 16px 20px;
    }

    .modal-body {
        padding: 20px;
        max-height: calc(90vh - 160px);
    }

    .modal-footer {
        padding: 12px 20px;
        flex-direction: column-reverse;
    }

    .modal-footer .btn {
        width: 100%;
        justify-content: center;
    }

    .unit-actions {
        flex-direction: column;
    }

    .unit-detail-row {
        grid-template-columns: 1fr;
    }
}

/* ========================================
   TABS STYLING
   ======================================== */
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

/* Enhanced Unit Detail Modal Styles */
.unit-profile-header {
    display: flex;
    align-items: center;
    gap: 25px;
    padding: 25px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
    margin-bottom: 25px;
    border: 1px solid #dee2e6;
}

.unit-photo-container {
    flex-shrink: 0;
}

.unit-main-photo {
    width: 180px;
    height: 130px;
    border-radius: 12px;
    object-fit: cover;
    border: 4px solid #667eea;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
}

.unit-main-photo:hover {
    transform: scale(1.05);
}

.unit-photo-placeholder {
    width: 180px;
    height: 130px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: white;
    border: 4px solid #764ba2;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.unit-info-header {
    flex: 1;
}

.unit-info-header h3 {
    margin: 0 0 8px 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
    letter-spacing: 2px;
}

.unit-info-header .unit-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    color: #6c757d;
    font-size: 0.95rem;
}

.unit-info-header .unit-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.unit-info-header .unit-meta i {
    color: #667eea;
}

/* Enhanced Section Styling for Units */
.unit-detail-section.enhanced {
    background: white;
    padding: 25px;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.unit-detail-section.enhanced h4 {
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

.unit-detail-section.enhanced h4 i {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.unit-detail-section.enhanced.info h4 i {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.unit-detail-section.enhanced.documents h4 i {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.unit-detail-section.enhanced.permit h4 i {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: white;
}

/* Unit Info Grid */
.unit-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.unit-info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.unit-info-item.blue {
    border-left-color: #3498db;
}

.unit-info-item.green {
    border-left-color: #27ae60;
}

.unit-info-item.orange {
    border-left-color: #e67e22;
}

.unit-info-item.purple {
    border-left-color: #9b59b6;
}

.unit-info-item .label {
    font-size: 11px;
    text-transform: uppercase;
    color: #7f8c8d;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.unit-info-item .value {
    font-size: 1rem;
    color: #2c3e50;
    font-weight: 600;
}

/* Document Card Grid */
.document-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.document-card {
    background: #f8f9fa;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.document-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.document-card-header {
    padding: 15px 20px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.document-card-header.blue {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.document-card-header.green {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
}

.document-card-body {
    padding: 20px;
}

.document-card-image {
    position: relative;
    cursor: pointer;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.document-card-image img {
    width: 100%;
    height: 150px;
    object-fit: contain;
    background: white;
    display: block;
}

.document-card-image .image-overlay {
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

.document-card-image:hover .image-overlay {
    opacity: 1;
}

.document-card-image .image-overlay i {
    color: white;
    font-size: 1.5rem;
}

.document-card-image .image-overlay span {
    color: white;
    font-weight: 600;
    margin-left: 8px;
}

.document-card-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.document-card-info .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 8px;
}

.document-card-info .info-row .label {
    font-size: 11px;
    text-transform: uppercase;
    color: #7f8c8d;
    font-weight: 700;
}

.document-card-info .info-row .value {
    font-size: 0.95rem;
    color: #2c3e50;
    font-weight: 600;
}

/* No Image Placeholder for Units */
.unit-no-image-placeholder {
    padding: 30px;
    background: white;
    border-radius: 12px;
    text-align: center;
    color: #95a5a6;
    border: 2px dashed #dee2e6;
    height: 150px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.unit-no-image-placeholder i {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

/* Validity Status Badges */
.validity-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.validity-badge.valid {
    background: #d4edda;
    color: #155724;
}

.validity-badge.expiring {
    background: #fff3cd;
    color: #856404;
}

.validity-badge.expired {
    background: #f8d7da;
    color: #721c24;
}

/* Fullscreen Image Viewer for Units */
.unit-fullscreen-viewer {
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

.unit-fullscreen-viewer.active {
    display: flex;
}

.unit-fullscreen-viewer img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 10px 50px rgba(0,0,0,0.5);
}

.unit-fullscreen-viewer .close-fullscreen {
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

.unit-fullscreen-viewer .close-fullscreen:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.unit-fullscreen-viewer .image-title {
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

/* Responsive adjustments for enhanced unit modal */
@media (max-width: 768px) {
    .unit-profile-header {
        flex-direction: column;
        text-align: center;
    }

    .unit-info-header .unit-meta {
        justify-content: center;
    }

    .document-cards-grid {
        grid-template-columns: 1fr;
    }

    .unit-info-grid {
        grid-template-columns: 1fr;
    }

    .unit-main-photo,
    .unit-photo-placeholder {
        width: 150px;
        height: 110px;
    }
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-bus"></i> My Transport Units</h1>
            <p class="mb-0">Manage your fleet of transport vehicles</p>
        </div>
        <button type="button" onclick="openAddUnitModal()" class="add-unit-btn">
            <i class="fas fa-plus"></i> Add New Unit
        </button>
    </div>
</div>

<!-- Tabs -->
<div class="tabs-container mb-4">
    <ul class="nav nav-tabs" id="unitTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="approved-tab" data-toggle="tab" data-target="#approved" type="button" role="tab">
                <i class="fas fa-check-circle"></i> Approved Units <span class="badge badge-success ml-2">{{ $approvedUnits->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-toggle="tab" data-target="#pending" type="button" role="tab">
                <i class="fas fa-clock"></i> Pending Applications <span class="badge badge-warning ml-2">{{ $pendingUnits->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="unitTabsContent">
        <!-- Approved Units Tab -->
        <div class="tab-pane fade show active" id="approved" role="tabpanel">
            <div class="row">
                @forelse($approvedUnits as $unit)
                <div class="col-md-6 col-lg-4">
                    <div class="unit-card">
                        <div class="unit-header">
                            <div class="unit-icon">
                               @if($unit->unit_photo_url)
                                    <img src="{{ $unit->unit_photo_url }}" 
                                        alt="{{ $unit->plate_no }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                @else
                                    <i class="fas fa-bus"></i>
                                @endif
                            </div>
                            <div class="unit-plate">{{ $unit->plate_no }}</div>
                            <div class="text-center">
                                <span class="unit-type-badge">{{ ucfirst($unit->type) }}</span>
                            </div>
                        </div>

                        <div class="unit-body">
                            <div class="spec-row">
                                <div class="spec-label">
                                    <i class="fas fa-calendar"></i>
                                    Year Model
                                </div>
                                <div class="spec-value">{{ $unit->year_model }}</div>
                            </div>

                            <div class="spec-row">
                                <div class="spec-label">
                                    <i class="fas fa-info-circle"></i>
                                    Status
                                </div>
                                <div class="spec-value">
                                    @if($unit->status == 'active')
                                    <span class="status-indicator active">Active</span>
                                    @elseif($unit->status == 'maintenance')
                                    <span class="status-indicator maintenance">Maintenance</span>
                                    @else
                                    <span class="status-indicator inactive">Inactive</span>
                                    @endif
                                </div>
                            </div>

                            <div class="spec-row">
                                <div class="spec-label">
                                    <i class="fas fa-user"></i>
                                    Assigned Driver
                                </div>
                                <div class="spec-value">
                                    @if($unit->driver)
                                        <span class="driver-assigned">{{ $unit->driver->full_name }}</span>
                                    @else
                                        <span class="no-driver">Not Assigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="unit-actions">
                            <button type="button" class="action-btn view" onclick="viewUnit({{ $unit->id }})">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button type="button" class="action-btn edit" onclick="editUnit({{ $unit->id }})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="action-btn assign" onclick="openAssignDriverModal({{ $unit->id }}, '{{ $unit->plate_no }}', {{ $unit->driver_id ?? 'null' }})">
                                <i class="fas fa-user-plus"></i> {{ $unit->driver_id ? 'Reassign' : 'Assign' }}
                            </button>
                            <button type="button" class="action-btn delete" onclick="confirmDeleteUnit({{ $unit->id }}, '{{ $unit->plate_no }}')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-bus"></i>
                        <h3>No Approved Units Yet</h3>
                        <p>Your approved transport units will appear here</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Applications Tab -->
        <div class="tab-pane fade" id="pending" role="tabpanel">
            <div class="row">
                @forelse($pendingUnits as $unit)
                <div class="col-md-6 col-lg-4">
                    <div class="unit-card">
                        <div class="unit-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                            <div class="unit-icon">
                                @if($unit->unit_photo_url)
                                    <img src="{{ $unit->unit_photo_url }}" 
                                        alt="{{ $unit->plate_no }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                @else
                                    <i class="fas fa-clock"></i>
                                @endif
                            </div>
                            <div class="unit-plate">{{ $unit->plate_no }}</div>
                            <div class="text-center">
                                <span class="unit-type-badge">{{ ucfirst($unit->type) }}</span>
                            </div>
                        </div>

                        <div class="unit-body">
                            <div class="alert alert-warning mb-3" style="font-size: 0.85rem; padding: 0.5rem;">
                                <i class="fas fa-info-circle"></i> <strong>Pending Admin Approval</strong>
                            </div>

                            <div class="spec-row">
                                <div class="spec-label">
                                    <i class="fas fa-calendar"></i>
                                    Year Model
                                </div>
                                <div class="spec-value">{{ $unit->year_model }}</div>
                            </div>

                            <div class="spec-row">
                                <div class="spec-label">
                                    <i class="fas fa-info-circle"></i>
                                    Status
                                </div>
                                <div class="spec-value">
                                    <span class="badge badge-warning">Pending Approval</span>
                                </div>
                            </div>
                        </div>

                        <div class="unit-actions">
                            <button type="button" class="action-btn view" onclick="viewUnit({{ $unit->id }})">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-clock"></i>
                        <h3>No Pending Applications</h3>
                        <p>You don't have any transport units pending approval</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($units->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $units->links() }}
</div>
@endif

<!-- View Unit Modal -->
<div id="viewUnitModal" class="modal-overlay" onclick="closeModal('viewUnitModal')">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title" id="viewUnitTitle">
                <i class="fas fa-bus"></i>
                Unit Details
            </h2>
            <button class="modal-close" onclick="closeModal('viewUnitModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="viewUnitContent">
            <div class="loading-spinner">
                <i class="fas fa-spinner"></i>
                <p>Loading unit details...</p>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Image Viewer for Units -->
<div id="unitFullscreenViewer" class="unit-fullscreen-viewer" onclick="closeUnitFullscreenImage()">
    <button class="close-fullscreen" onclick="closeUnitFullscreenImage()">
        <i class="fas fa-times"></i>
    </button>
    <img id="unitFullscreenImage" src="" alt="Fullscreen View" onclick="event.stopPropagation()">
    <div class="image-title" id="unitFullscreenImageTitle"></div>
</div>

<!-- Edit Unit Modal -->
<div id="editUnitModal" class="modal-overlay" onclick="closeModal('editUnitModal')">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header orange">
            <h2 class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Unit Information
            </h2>
            <button class="modal-close" onclick="closeModal('editUnitModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editUnitForm" onsubmit="submitEditUnit(event)">
            <div class="modal-body" id="editUnitContent">
                <div class="loading-spinner">
                    <i class="fas fa-spinner"></i>
                    <p>Loading unit information...</p>
                </div>
            </div>
            <div class="modal-footer" id="editUnitFooter">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editUnitModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-warning" id="updateUnitBtn">
                    <i class="fas fa-save"></i> Update Unit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Unit Modal -->
<div id="addUnitModal" class="modal-overlay" onclick="closeModal('addUnitModal')">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header blue">
            <h2 class="modal-title">
                <i class="fas fa-bus"></i>
                Add New Transport Unit
            </h2>
            <button class="modal-close" onclick="closeModal('addUnitModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="addUnitForm" onsubmit="submitAddUnit(event)">
            <div class="modal-body">
                <!-- Basic Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Plate Number <span class="required">*</span></label>
                        <input type="text" class="form-control" name="plate_no" required>
                    </div>
                    <div class="form-group">
                        <label>Year Model</label>
                        <input type="text" class="form-control" name="year_model" placeholder="e.g., 2020">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" class="form-control" name="model" placeholder="e.g., Hiace, L300">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="text" class="form-control" name="color">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Body Number</label>
                        <input type="text" class="form-control" name="body_number">
                    </div>
                    <div class="form-group">
                        <label>Engine Number</label>
                        <input type="text" class="form-control" name="engine_number">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Chassis Number</label>
                        <input type="text" class="form-control" name="chassis_number">
                    </div>
                    <div class="form-group">
                        <label for="coding_number">Coding Number</label>
                        <input 
                            type="number" 
                            name="coding_number" 
                            id="coding_number" 
                            class="form-control" 
                            min="1" 
                            max="5" 
                            step="1"
                            placeholder="1-5"
                            required
                            oninput="this.value = this.value.slice(0, 1)">
                    </div>

                    <div class="form-group">
                        <label for="police_number">Police Number</label>
                        <input
                            type="tel"
                            class="form-control"
                            name="police_number"
                            id="police_number"
                            maxlength="4"
                            pattern="[0-9]{4}"
                            placeholder="e.g. 1234"
                            required
                            oninput="this.value = this.value.replace(/\D/g,'').slice(0,4)"
                        >
                        <small class="form-text text-muted">
                            Must be exactly 4 digits
                        </small>
                    </div>

                </div>

                <div class="form-group">
                    <label>Unit Photo</label>
                    <input type="file" class="form-control" name="unit_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewImage(this, 'unitPhotoPreview')">
                    <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                    <div id="unitPhotoPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                        <img src="" alt="Unit Photo Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                    </div>
                </div>

                <!-- LTO Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label>LTO CR Number</label>
                        <input type="text" class="form-control" name="lto_cr_number">
                    </div>
                    <div class="form-group">
                        <label>LTO CR Date Issued</label>
                        <input type="date" class="form-control" name="lto_cr_date_issued">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>LTO OR Number</label>
                        <input type="text" class="form-control" name="lto_or_number">
                    </div>
                    <div class="form-group">
                        <label>LTO OR Date Issued</label>
                        <input type="date" class="form-control" name="lto_or_date_issued">
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Franchise Case Number</label>
                        <input type="text" class="form-control" name="franchise_case">
                    </div>
                    <div class="form-group">
                        <!-- Empty space for alignment -->
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>MV File Number</label>
                        <input type="text" class="form-control" name="mv_file">
                    </div>
                    <div class="form-group">
                        <!-- Empty space for alignment -->
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>MBP No. (Previous Year)</label>
                        <input type="text" class="form-control" name="mbp_no_prev_year">
                    </div>
                    <div class="form-group">
                        <label>MCH No. (Previous Year)</label>
                        <input type="text" class="form-control" name="mch_no_prev_year">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select class="form-control" name="status" required>
                        <option value="active">Active</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Document Uploads Subsection -->
                <div class="form-subsection">
                    <h4 class="form-subsection-title">
                        <i class="fas fa-file-upload"></i>
                        Document Uploads
                    </h4>

                    <!-- Business Permit -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Business Permit Photo</label>
                            <input type="file" class="form-control" name="business_permit_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewImage(this, 'businessPermitPreview')">
                            <small class="form-text">Accepted formats: PNG, JPG, JPEG | Max 10MB</small>
                            <div id="businessPermitPreview" class="image-preview-container">
                                <img src="" alt="Business Permit Preview">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Business Permit Number</label>
                            <input type="text" class="form-control" name="business_permit_no">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Business Permit Validity</label>
                        <input type="date" class="form-control" name="business_permit_validity">
                    </div>

                    <!-- Official Receipt -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Official Receipt Photo</label>
                            <input type="file" class="form-control" name="or_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewImage(this, 'officialReceiptPreview')">
                            <small class="form-text">Accepted formats: PNG, JPG, JPEG | Max 10MB</small>
                            <div id="officialReceiptPreview" class="image-preview-container">
                                <img src="" alt="Official Receipt Preview">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Official Receipt Number</label>
                            <input type="text" class="form-control" name="or_number">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Official Receipt Validity</label>
                        <input type="date" class="form-control" name="or_date_issued">
                    </div>

                    <!-- Certificate of Registration -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Certificate of Registration (CR) Photo <span class="required">*</span></label>
                            <input type="file" class="form-control" name="cr_photo" accept=".png,.jpg,.jpeg" required onchange="validateAndPreviewImage(this, 'crPhotoPreview')">
                            <small class="form-text">Accepted formats: PNG, JPG, JPEG | Max 10MB</small>
                            <div id="crPhotoPreview" class="image-preview-container">
                                <img src="" alt="CR Photo Preview">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Certificate of Registration Number</label>
                            <input type="text" class="form-control" name="cr_number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>CR Validity</label>
                            <input type="date" class="form-control" name="cr_validity">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addUnitModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="addUnitBtn">
                    <i class="fas fa-plus"></i> Add Unit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteUnitModal" class="modal-overlay" onclick="closeModal('deleteUnitModal')">
    <div class="modal-container small" onclick="event.stopPropagation()">
        <div class="modal-header red">
            <h2 class="modal-title">
                <i class="fas fa-exclamation-triangle"></i>
                Confirm Deletion
            </h2>
            <button class="modal-close" onclick="closeModal('deleteUnitModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="delete-confirmation">
                <i class="fas fa-exclamation-circle"></i>
                <h3>Delete Transport Unit?</h3>
                <p>Are you sure you want to delete this transport unit?</p>
                <p class="unit-name" id="deleteUnitName"></p>
                <div class="warning-text">
                    <i class="fas fa-info-circle"></i>
                    This action cannot be undone!
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteUnitModal')">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                <i class="fas fa-trash"></i> Yes, Delete
            </button>
        </div>
    </div>
</div>

<!-- Assign Driver Modal -->
<div id="assignDriverModal" class="modal-overlay" onclick="closeModal('assignDriverModal')">
    <div class="modal-container small" onclick="event.stopPropagation()">
        <div class="modal-header" style="background: linear-gradient(135deg, #4caf50, #388e3c);">
            <h2 class="modal-title">
                <i class="fas fa-user-plus"></i>
                Assign Driver
            </h2>
            <button class="modal-close" onclick="closeModal('assignDriverModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="unit-info-box" style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #4caf50;">
                <h4 style="margin: 0 0 10px 0; color: #333; font-size: 14px;">
                    <i class="fas fa-bus" style="color: #4caf50; margin-right: 8px;"></i>
                    Unit Information
                </h4>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 12px; color: #666;">Plate Number</span>
                        <div style="font-size: 18px; font-weight: 700; color: #333;" id="assignModalPlateNo">-</div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Select Driver <span class="required">*</span></label>
                <select class="form-control" id="driverSelect" name="driver_id" required style="min-height: 46px;">
                    <option value="">-- Select a Driver --</option>
                </select>
                <small class="form-text text-muted" id="driverSelectHelp">Loading available drivers...</small>
            </div>

            <div id="currentDriverInfo" style="display: none; background: #fff3cd; padding: 15px; border-radius: 10px; border-left: 4px solid #ffc107; margin-top: 15px;">
                <h5 style="margin: 0 0 10px 0; color: #856404; font-size: 14px;">
                    <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                    Currently Assigned Driver
                </h5>
                <div id="currentDriverName" style="font-weight: 600; color: #856404;"></div>
                <small style="color: #856404;">Selecting a new driver will reassign this unit.</small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('assignDriverModal')">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn btn-primary" id="assignDriverBtn" onclick="submitAssignDriver()" style="background: linear-gradient(135deg, #4caf50, #388e3c);">
                <i class="fas fa-user-check"></i> Assign Driver
            </button>
        </div>
    </div>
</div>

<!-- Unit Registration Confirmation Modal -->
<div id="unitConfirmationModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 600px; margin: 0;" onclick="event.stopPropagation()">
        <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <h2 class="modal-title" style="color: white;">
                <i class="fas fa-check-circle"></i>
                Unit Registration Submitted!
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
                Thank you for submitting your unit registration. Your application is now pending review by the administrator.
            </p>

            <div style="background: #f8f9fa; border-left: 4px solid #667eea; padding: 1rem; border-radius: 8px; margin: 1rem 0; text-align: left;">
                <h4 style="color: #667eea; font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-info-circle"></i>
                    What happens next?
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 0.5rem 0; color: #495057; display: flex; align-items: start; gap: 10px; font-size: 0.875rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-top: 3px;"></i>
                        <span>The admin team will review your transport unit's information and submitted documents.</span>
                    </li>
                    <li style="padding: 0.5rem 0; color: #495057; display: flex; align-items: start; gap: 10px; font-size: 0.875rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-top: 3px;"></i>
                        <span>You will be notified once the unit registration has been reviewed.</span>
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
            <button type="button" class="btn btn-primary" onclick="closeUnitConfirmation()" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; padding: 0.75rem 2rem;">
                <i class="fas fa-check"></i> OK, Got It!
            </button>
        </div>
    </div>
</div>

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
</style>

@endsection

@push('scripts')
<script>
    let currentUnitId = null;

    // Modal Functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Show and close unit confirmation modal
    function showUnitConfirmation() {
        const modal = document.getElementById('unitConfirmationModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            console.error('unitConfirmationModal element not found!');
        }
    }

    function closeUnitConfirmation() {
        document.getElementById('unitConfirmationModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        // Store that we should show pending tab after reload
        sessionStorage.setItem('showPendingTab', 'true');
        // Reload the page to show the newly added unit in pending
        window.location.reload();
    }

    // View Unit
    function viewUnit(unitId) {
        openModal('viewUnitModal');

        fetch(apiUrl(`units/${unitId}`))
            .then(response => response.json())
            .then(data => {
                displayUnitDetails(data);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('viewUnitContent').innerHTML =
                    '<p style="text-align:center; color:#e74c3c;"><i class="fas fa-exclamation-triangle"></i><br><br>Error loading unit details</p>';
            });
    }

    function formatDate(dateString) {
        if (!dateString || dateString === 'N/A') return 'N/A';

        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString; // Return original if invalid

        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }

    function formatDateTime(dateString) {
        if (!dateString || dateString === 'N/A') return 'N/A';

        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString; // Return original if invalid

        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${month}-${day}-${year} ${hours}:${minutes}:${seconds}`;
    }

    function displayUnitDetails(unit) {
        document.getElementById('viewUnitTitle').innerHTML = `
            <i class="fas fa-bus"></i>
            Unit Details
        `;

        // Generate unit photo HTML
        const unitPhotoHtml = unit.unit_photo_url
            ? `<img src="${unit.unit_photo_url}" alt="Unit Photo" class="unit-main-photo" onclick="openUnitFullscreenImage('${unit.unit_photo_url}', 'Unit Photo - ${unit.plate_no}')">`
            : `<div class="unit-photo-placeholder"><i class="fas fa-bus"></i></div>`;

        // Generate Business Permit card
        const businessPermitCard = `
            <div class="document-card">
                <div class="document-card-header green">
                    <i class="fas fa-file-certificate"></i>
                    Business Permit
                </div>
                <div class="document-card-body">
                    ${unit.business_permit_photo_url ? `
                        <div class="document-card-image" onclick="openUnitFullscreenImage('${unit.business_permit_photo_url}', 'Business Permit')">
                            <img src="${unit.business_permit_photo_url}" alt="Business Permit">
                            <div class="image-overlay">
                                <i class="fas fa-expand"></i>
                                <span>View Fullscreen</span>
                            </div>
                        </div>
                    ` : `
                        <div class="unit-no-image-placeholder">
                            <i class="fas fa-file-certificate"></i>
                            <p>No image uploaded</p>
                        </div>
                    `}
                    <div class="document-card-info">
                        <div class="info-row">
                            <span class="label">Number</span>
                            <span class="value">${unit.business_permit_number || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Validity</span>
                            <span class="value">${unit.business_permit_validity || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Generate OR card
        const orCard = `
            <div class="document-card">
                <div class="document-card-header blue">
                    <i class="fas fa-receipt"></i>
                    Official Receipt (OR)
                </div>
                <div class="document-card-body">
                    ${unit.or_photo_url ? `
                        <div class="document-card-image" onclick="openUnitFullscreenImage('${unit.or_photo_url}', 'Official Receipt (OR)')">
                            <img src="${unit.or_photo_url}" alt="OR Photo">
                            <div class="image-overlay">
                                <i class="fas fa-expand"></i>
                                <span>View Fullscreen</span>
                            </div>
                        </div>
                    ` : `
                        <div class="unit-no-image-placeholder">
                            <i class="fas fa-receipt"></i>
                            <p>No image uploaded</p>
                        </div>
                    `}
                    <div class="document-card-info">
                        <div class="info-row">
                            <span class="label">OR Number</span>
                            <span class="value">${unit.or_number || unit.lto_or_number || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Date Issued</span>
                            <span class="value">${unit.or_date_issued || unit.lto_or_date_issued || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Generate CR card
        const crCard = `
            <div class="document-card">
                <div class="document-card-header">
                    <i class="fas fa-id-card"></i>
                    Certificate of Registration (CR)
                </div>
                <div class="document-card-body">
                    ${unit.cr_photo_url ? `
                        <div class="document-card-image" onclick="openUnitFullscreenImage('${unit.cr_photo_url}', 'Certificate of Registration (CR)')">
                            <img src="${unit.cr_photo_url}" alt="CR Photo">
                            <div class="image-overlay">
                                <i class="fas fa-expand"></i>
                                <span>View Fullscreen</span>
                            </div>
                        </div>
                    ` : `
                        <div class="unit-no-image-placeholder">
                            <i class="fas fa-id-card"></i>
                            <p>No image uploaded</p>
                        </div>
                    `}
                    <div class="document-card-info">
                        <div class="info-row">
                            <span class="label">CR Number</span>
                            <span class="value">${unit.cr_number || unit.lto_cr_number || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Validity</span>
                            <span class="value">${unit.cr_validity || unit.lto_cr_date_issued || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const content = `
            <!-- Unit Profile Header with Photo -->
            <div class="unit-profile-header">
                <div class="unit-photo-container">
                    ${unitPhotoHtml}
                </div>
                <div class="unit-info-header">
                    <h3>${unit.plate_no || 'N/A'}</h3>
                    <div class="unit-meta">
                        <span><i class="fas fa-hashtag"></i> Body #${unit.body_number || 'N/A'}</span>
                        <span><i class="fas fa-car"></i> ${unit.model || 'N/A'}</span>
                        <span><i class="fas fa-calendar"></i> ${unit.year_model || 'N/A'}</span>
                        <span class="status-indicator ${unit.status === 'active' ? 'active' : unit.status === 'maintenance' ? 'maintenance' : 'inactive'}">
                            ${unit.status || 'N/A'}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Unit Information Section -->
            <div class="unit-detail-section enhanced info">
                <h4>
                    <i class="fas fa-info-circle"></i>
                    Unit Information
                </h4>
                <div class="unit-info-grid">
                    <div class="unit-info-item">
                        <div class="label">Plate Number</div>
                        <div class="value">${unit.plate_no || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item">
                        <div class="label">Body Number</div>
                        <div class="value">${unit.body_number || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item">
                        <div class="label">Engine Number</div>
                        <div class="value">${unit.engine_number || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item">
                        <div class="label">Coding Number</div>
                        <div class="value">${unit.coding_number || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item">
                        <div class="label">Police Number</div>
                        <div class="value">${unit.police_number || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item blue">
                        <div class="label">LTO CR</div>
                        <div class="value">${unit.lto_cr_number || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item blue">
                        <div class="label">LTO OR</div>
                        <div class="value">${unit.lto_or_number || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item orange">
                        <div class="label">Franchise Case</div>
                        <div class="value">${unit.franchise_case || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item orange">
                        <div class="label">MBP No. (Prev. Year)</div>
                        <div class="value">${unit.mbp_no_prev_year || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item green">
                        <div class="label">Year Model</div>
                        <div class="value">${unit.year_model || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item green">
                        <div class="label">Model</div>
                        <div class="value">${unit.model || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item purple">
                        <div class="label">Chassis Number</div>
                        <div class="value">${unit.chassis_number || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item blue">
                        <div class="label">Date Issued (CR)</div>
                        <div class="value">${unit.lto_cr_date_issued || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item blue">
                        <div class="label">LTO OR Date Issued</div>
                        <div class="value">${unit.lto_or_date_issued || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item orange">
                        <div class="label">MV Files</div>
                        <div class="value">${unit.mv_file || 'N/A'}</div>
                    </div>
                    <div class="unit-info-item orange">
                        <div class="label">MCH No. (Prev. Year)</div>
                        <div class="value">${unit.mch_no_prev_year || 'N/A'}</div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="unit-detail-section enhanced documents">
                <h4>
                    <i class="fas fa-folder-open"></i>
                    Documents
                </h4>
                <div class="document-cards-grid">
                    ${businessPermitCard}
                    ${orCard}
                    ${crCard}
                </div>
            </div>
        `;

        document.getElementById('viewUnitContent').innerHTML = content;
    }

    // Fullscreen image viewer functions for units
    function openUnitFullscreenImage(imageUrl, title) {
        const viewer = document.getElementById('unitFullscreenViewer');
        const img = document.getElementById('unitFullscreenImage');
        const titleEl = document.getElementById('unitFullscreenImageTitle');

        img.src = imageUrl;
        titleEl.textContent = title;
        viewer.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeUnitFullscreenImage() {
        const viewer = document.getElementById('unitFullscreenViewer');
        viewer.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Edit Unit
    function editUnit(unitId) {
        currentUnitId = unitId;
        openModal('editUnitModal');

        const footer = document.getElementById('editUnitFooter');
        if (footer) {
            footer.style.display = 'flex';
            footer.style.visibility = 'visible';
        }

        fetch(apiUrl(`units/${unitId}`))
            .then(response => response.json())
            .then(data => {
                displayEditForm(data);
                const footer = document.getElementById('editUnitFooter');
                if (footer) {
                    footer.style.display = 'flex';
                    footer.style.visibility = 'visible';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('editUnitContent').innerHTML =
                    '<p style="text-align:center; color:#e74c3c;"><i class="fas fa-exclamation-triangle"></i><br><br>Error loading unit information</p>';
            });
    }

    function displayEditForm(unit) {
        const content = `
            <!-- Basic Information -->
            <div class="form-row">
                <div class="form-group">
                    <label>Plate Number <span class="required">*</span></label>
                    <input type="text" class="form-control" name="plate_no" value="${unit.plate_no || ''}" required>
                </div>
                <div class="form-group">
                    <label>Year Model</label>
                    <input type="text" class="form-control" name="year_model" value="${unit.year_model || ''}" placeholder="e.g., 2020">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" class="form-control" name="model" value="${unit.model || ''}" placeholder="e.g., Hiace, L300">
                </div>
                <div class="form-group">
                    <label>Color</label>
                    <input type="text" class="form-control" name="color" value="${unit.color || ''}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Body Number</label>
                    <input type="text" class="form-control" name="body_number" value="${unit.body_number || ''}">
                </div>
                <div class="form-group">
                    <label>Engine Number</label>
                    <input type="text" class="form-control" name="engine_number" value="${unit.engine_number || ''}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Chassis Number</label>
                    <input type="text" class="form-control" name="chassis_number" value="${unit.chassis_number || ''}">
                </div>
                <div class="form-group">
                    <label for="coding_number">Coding Number</label>
                    <input 
                        type="number" 
                        name="coding_number" 
                        id="coding_number" 
                        class="form-control" 
                        min="1" 
                        max="5" 
                        step="1"
                        placeholder="1-5"
                        required
                        value="${unit.coding_number || ''}"
                        oninput="this.value = this.value.slice(0, 1)">
                </div>

                <div class="form-group">
                    <label for="police_number">Police Number</label>
                    <input
                        type="tel"
                        class="form-control"
                        name="police_number"
                        id="police_number"
                        maxlength="4"
                        pattern="[0-9]{4}"
                        placeholder="e.g. 1234"
                        value="${unit.police_number || ''}"
                        required
                        oninput="this.value = this.value.replace(/\D/g,'').slice(0,4)"
                    >
                    <small class="form-text text-muted">
                        Must be exactly 4 digits
                    </small>
                </div>
            </div>

            <!-- LTO Information -->
            <div class="form-row">
                <div class="form-group">
                    <label>LTO CR Number</label>
                    <input type="text" class="form-control" name="lto_cr_number" value="${unit.lto_cr_number || ''}">
                </div>
                <div class="form-group">
                    <label>LTO CR Date Issued</label>
                    <input type="date" class="form-control" name="lto_cr_date_issued" value="${unit.lto_cr_date_issued_raw || ''}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>LTO OR Number</label>
                    <input type="text" class="form-control" name="lto_or_number" value="${unit.lto_or_number || ''}">
                </div>
                <div class="form-group">
                    <label>LTO OR Date Issued</label>
                    <input type="date" class="form-control" name="lto_or_date_issued" value="${unit.lto_or_date_issued_raw || ''}">
                </div>
            </div>

            <!-- Additional Information -->
            <div class="form-row">
                <div class="form-group">
                    <label>Franchise Case Number</label>
                    <input type="text" class="form-control" name="franchise_case" value="${unit.franchise_case || ''}">
                </div>
                <div class="form-group">
                    <!-- Empty space for alignment -->
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>MV File Number</label>
                    <input type="text" class="form-control" name="mv_file" value="${unit.mv_file || ''}">
                </div>
                <div class="form-group">
                    <!-- Empty space for alignment -->
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>MBP No. (Previous Year)</label>
                    <input type="text" class="form-control" name="mbp_no_prev_year" value="${unit.mbp_no_prev_year || ''}">
                </div>
                <div class="form-group">
                    <label>MCH No. (Previous Year)</label>
                    <input type="text" class="form-control" name="mch_no_prev_year" value="${unit.mch_no_prev_year || ''}">
                </div>
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select class="form-control" name="status" required>
                    <option value="active" ${unit.status === 'active' ? 'selected' : ''}>Active</option>
                    <option value="maintenance" ${unit.status === 'maintenance' ? 'selected' : ''}>Maintenance</option>
                    <option value="inactive" ${unit.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                </select>
            </div>

            <!-- Unit Photo -->
            <div class="form-group">
                <label>Unit Photo</label>
                <input type="file" class="form-control" style="min-height: 46px;" name="unit_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewImage(this, 'editUnitPhotoPreview')">
                <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                ${unit.unit_photo_url ? `
                    <div class="image-preview-container" style="margin-top: 10px;">
                        <img src="${unit.unit_photo_url}" alt="Current Unit Photo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                        <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
                    </div>
                ` : ''}
                <div id="editUnitPhotoPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                    <img src="" alt="New Unit Photo Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                </div>
            </div>

            <!-- Document Information Subsection -->
            <div class="form-subsection" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 10px; border: 1px solid #e9ecef;">
                <h4 style="margin: 0 0 15px 0; color: #495057; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-file-alt" style="margin-right: 8px; color: #667eea;"></i>
                    Document Information
                </h4>

                <!-- Business Permit -->
                <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #dee2e6;">
                    <label style="font-weight: 600; color: #333; margin-bottom: 10px; display: block;">
                        <i class="fas fa-building" style="margin-right: 6px; color: #28a745;"></i>
                        Business Permit
                    </label>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Permit Number</label>
                            <input type="text" class="form-control" name="business_permit_no" value="${unit.business_permit_no || ''}">
                        </div>
                        <div class="form-group">
                            <label>Validity Date</label>
                            <input type="date" class="form-control" name="business_permit_validity" value="${unit.business_permit_validity_raw || ''}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Business Permit Photo</label>
                        <input type="file" class="form-control" style="min-height: 46px;" name="business_permit_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewImage(this, 'editBusinessPermitPreview')">
                        <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 10MB</small>
                        ${unit.business_permit_photo_url ? `
                            <div class="image-preview-container" style="margin-top: 10px;">
                                <img src="${unit.business_permit_photo_url}" alt="Current Business Permit Photo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
                            </div>
                        ` : ''}
                        <div id="editBusinessPermitPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                            <img src="" alt="New Business Permit Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                        </div>
                    </div>
                </div>

                <!-- Official Receipt (OR) -->
                <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #dee2e6;">
                    <label style="font-weight: 600; color: #333; margin-bottom: 10px; display: block;">
                        <i class="fas fa-receipt" style="margin-right: 6px; color: #17a2b8;"></i>
                        Official Receipt (OR)
                    </label>
                    <div class="form-row">
                        <div class="form-group">
                            <label>OR Number</label>
                            <input type="text" class="form-control" name="or_number" value="${unit.or_number || ''}">
                        </div>
                        <div class="form-group">
                            <label>OR Validity</label>
                            <input type="date" class="form-control" name="or_date_issued" value="${unit.or_date_issued_raw || ''}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Official Receipt Photo</label>
                        <input type="file" class="form-control" style="min-height: 46px;" name="or_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewImage(this, 'editOrPhotoPreview')">
                        <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 10MB</small>
                        ${unit.or_photo_url ? `
                            <div class="image-preview-container" style="margin-top: 10px;">
                                <img src="${unit.or_photo_url}" alt="Current OR Photo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
                            </div>
                        ` : ''}
                        <div id="editOrPhotoPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                            <img src="" alt="New OR Photo Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                        </div>
                    </div>
                </div>

                <!-- Certificate of Registration (CR) -->
                <div>
                    <label style="font-weight: 600; color: #333; margin-bottom: 10px; display: block;">
                        <i class="fas fa-certificate" style="margin-right: 6px; color: #ffc107;"></i>
                        Certificate of Registration (CR)
                    </label>
                    <div class="form-row">
                        <div class="form-group">
                            <label>CR Number</label>
                            <input type="text" class="form-control" name="cr_number" value="${unit.cr_number || ''}">
                        </div>
                        <div class="form-group">
                            <label>Validity Date</label>
                            <input type="date" class="form-control" name="cr_validity" value="${unit.cr_validity_raw || ''}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Certificate of Registration Photo</label>
                        <input type="file" class="form-control" style="min-height: 46px;" name="cr_photo" accept=".png,.jpg,.jpeg" onchange="validateAndPreviewImage(this, 'editCrPhotoPreview')">
                        <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 10MB</small>
                        ${unit.cr_photo_url ? `
                            <div class="image-preview-container" style="margin-top: 10px;">
                                <img src="${unit.cr_photo_url}" alt="Current CR Photo" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">Current Photo</p>
                            </div>
                        ` : ''}
                        <div id="editCrPhotoPreview" class="image-preview-container" style="margin-top: 10px; display: none;">
                            <img src="" alt="New CR Photo Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('editUnitContent').innerHTML = content;

        const modalFooter = document.querySelector('#editUnitModal .modal-footer');
        if (modalFooter) {
            modalFooter.style.display = 'flex';
            modalFooter.style.visibility = 'visible';
        }
    }

    function submitEditUnit(event) {
        event.preventDefault();

        const btn = document.getElementById('updateUnitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

        const formData = new FormData(event.target);

        // Add _method field for Laravel to recognize PUT request
        formData.append('_method', 'PUT');

        fetch(`/api/units/${currentUnitId}`, {
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
                closeModal('editUnitModal');
                showSuccess('Unit updated successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showError('Error updating unit: ' + (result.message || 'Unknown error'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Update Unit';
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Handle validation errors
            if (error.status === 422 && error.data && error.data.errors) {
                const errors = error.data.errors;
                let errorMessages = [];

                for (let field in errors) {
                    errors[field].forEach(msg => {
                        errorMessages.push(msg);
                    });
                }

                // Show first error with details
                if (errorMessages.length > 0) {
                    showError(errorMessages[0] + (errorMessages.length > 1 ? ` (and ${errorMessages.length - 1} more errors)` : ''));
                    console.error('Validation errors:', errors);
                }
            } else if (error.data && error.data.message) {
                showError('Error: ' + error.data.message);
            } else {
                showError('Error updating unit. Please try again.');
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Update Unit';
        });
    }

    // Add Unit
    function openAddUnitModal() {
        openModal('addUnitModal');
    }

    function submitAddUnit(event) {
        event.preventDefault();

        const btn = document.getElementById('addUnitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        const formData = new FormData(event.target);

        fetch('/api/units', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
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
                closeModal('addUnitModal');
                document.getElementById('addUnitForm').reset();
                document.querySelectorAll('.image-preview-container').forEach(el => el.style.display = 'none');
                showSuccess(result.message || 'Transport unit added successfully. Pending admin approval.');
                showUnitConfirmation();
            } else {
                showError('Error adding unit: ' + (result.message || 'Unknown error'));
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
                showError('Error adding unit. Please try again.');
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Add Unit';
        });
    }

    // Delete Unit
    function confirmDeleteUnit(unitId, unitName) {
        currentUnitId = unitId;
        document.getElementById('deleteUnitName').textContent = unitName;
        openModal('deleteUnitModal');

        document.getElementById('confirmDeleteBtn').onclick = function() {
            deleteUnit(unitId);
        };
    }

    function deleteUnit(unitId) {
        const btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

        fetch(`/api/units/${unitId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                closeModal('deleteUnitModal');
                showAlert('Unit deleted successfully!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('Error deleting unit: ' + (result.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting unit. Please try again.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash"></i> Yes, Delete';
        });
    }

    // Alert Function - Using Toastr notifications
    function showAlert(message, type) {
        if (type === 'success') {
            showSuccess(message);
        } else if (type === 'error') {
            showError(message);
        } else if (type === 'warning') {
            showWarning(message);
        } else {
            showInfo(message);
        }
    }

    // Allowed image extensions
    const allowedImageExtensions = ['png', 'jpg', 'jpeg'];

    // Validate file extension
    function validateFileExtension(file, allowedExtensions) {
        const fileName = file.name.toLowerCase();
        const extension = fileName.split('.').pop();
        return allowedExtensions.includes(extension);
    }

    // Image Preview Function with validation for PNG, JPG, JPEG only
    function validateAndPreviewImage(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        const previewImg = previewContainer.querySelector('img');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file extension - only PNG, JPG, JPEG allowed
            if (!validateFileExtension(file, allowedImageExtensions)) {
                showError('Invalid file type. Please upload a PNG, JPG, or JPEG image only.');
                input.value = ''; // Clear the input
                previewContainer.style.display = 'none';
                previewImg.src = '';
                return;
            }

            // Determine max size based on field name
            const fieldName = input.name;
            let maxSize, maxSizeMB;

            if (fieldName === 'unit_photo') {
                maxSize = 2 * 1024 * 1024; // 2MB
                maxSizeMB = 2;
            } else {
                // cr_photo, business_permit_photo, or_photo, cr_receipt_photo
                maxSize = 10 * 1024 * 1024; // 10MB
                maxSizeMB = 10;
            }

            // Validate file size
            if (file.size > maxSize) {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                showError(`File size (${fileSizeMB}MB) exceeds the maximum allowed size of ${maxSizeMB}MB. Please choose a smaller file.`);
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
    function previewUnitImage(input, previewId) {
        validateAndPreviewImage(input, previewId);
    }

    // ========================================
    // ASSIGN DRIVER FUNCTIONALITY
    // ========================================
    let currentAssignUnitId = null;
    let currentAssignDriverId = null;

    function openAssignDriverModal(unitId, plateNo, currentDriverId) {
        currentAssignUnitId = unitId;
        currentAssignDriverId = currentDriverId;

        // Update modal with unit info
        document.getElementById('assignModalPlateNo').textContent = plateNo;

        // Reset the select
        const driverSelect = document.getElementById('driverSelect');
        driverSelect.innerHTML = '<option value="">-- Loading drivers... --</option>';
        document.getElementById('driverSelectHelp').textContent = 'Loading available drivers...';

        // Hide current driver info initially
        document.getElementById('currentDriverInfo').style.display = 'none';

        openModal('assignDriverModal');

        // Load available drivers
        loadAvailableDrivers(unitId, currentDriverId);
    }

    function loadAvailableDrivers(unitId, currentDriverId) {
        const driverSelect = document.getElementById('driverSelect');
        const helpText = document.getElementById('driverSelectHelp');

        fetch(apiUrl(`units/${unitId}/available-drivers`))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const drivers = data.drivers || [];

                    driverSelect.innerHTML = '<option value="">-- Select a Driver --</option>';

                    if (drivers.length === 0 && !currentDriverId) {
                        helpText.textContent = 'No available drivers. All drivers are assigned to other units.';
                        helpText.style.color = '#dc3545';
                    } else {
                        helpText.textContent = 'Select a driver to assign to this unit.';
                        helpText.style.color = '#6c757d';

                        drivers.forEach(driver => {
                            const option = document.createElement('option');
                            option.value = driver.id;
                            option.textContent = `${driver.full_name} - ${driver.license_number || 'No License'}`;
                            if (driver.id === currentDriverId) {
                                option.selected = true;
                            }
                            driverSelect.appendChild(option);
                        });
                    }

                    // Show current driver info if there's one assigned
                    if (data.current_driver) {
                        document.getElementById('currentDriverInfo').style.display = 'block';
                        document.getElementById('currentDriverName').textContent = data.current_driver.full_name;
                    }
                } else {
                    helpText.textContent = 'Error loading drivers: ' + (data.message || 'Unknown error');
                    helpText.style.color = '#dc3545';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                helpText.textContent = 'Error loading drivers. Please try again.';
                helpText.style.color = '#dc3545';
            });
    }

    function submitAssignDriver() {
        const driverSelect = document.getElementById('driverSelect');
        const driverId = driverSelect.value;

        if (!driverId) {
            showAlert('Please select a driver to assign.', 'error');
            return;
        }

        const btn = document.getElementById('assignDriverBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';

        fetch(`/api/units/${currentAssignUnitId}/assign-driver`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                driver_id: driverId
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                closeModal('assignDriverModal');
                showAlert('Driver assigned successfully!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('Error assigning driver: ' + (result.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error assigning driver. Please try again.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-user-check"></i> Assign Driver';
        });
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('viewUnitModal');
            closeModal('editUnitModal');
            closeModal('deleteUnitModal');
            closeModal('addUnitModal');
            closeModal('assignDriverModal');
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

        // Check if we should show the pending tab after adding a unit
        if (sessionStorage.getItem('showPendingTab') === 'true') {
            sessionStorage.removeItem('showPendingTab');
            // Switch to pending tab
            const pendingTab = document.getElementById('pending-tab');
            if (pendingTab) {
                pendingTab.click();
            }
        }
    });
</script>
@endpush
