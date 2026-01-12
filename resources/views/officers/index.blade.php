@extends('layouts.app')

@section('title', 'Officers Management')

@section('page-title', 'Officers Management')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Home</a></li>
    <li>Officers</li>
@endsection

@push('styles')
<style>
    .officers-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .officers-header h2 {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: 600;
    }

    .officers-header p {
        margin: 0;
        opacity: 0.9;
    }

    .btn-assign-officers {
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

    .btn-assign-officers:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(245, 87, 108, 0.5);
    }

    .btn-download-officers {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-download-officers:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(17, 153, 142, 0.5);
        color: white;
        text-decoration: none;
    }

    .btn-assign-officers i {
        margin-right: 8px;
    }

    .officers-table-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .officers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .officers-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .officers-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .officers-table th:first-child {
        border-top-left-radius: 8px;
    }

    .officers-table th:last-child {
        border-top-right-radius: 8px;
    }

    .officers-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .officers-table tbody tr:hover {
        background: #f8f9ff;
    }

    .officers-table td {
        padding: 15px;
        color: #495057;
    }

    .position-badge {
        padding: 6px 14px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .position-badge.chairperson {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .position-badge.vice_chairperson {
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        color: white;
    }

    .position-badge.treasurer {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        color: white;
    }

    .position-badge.secretary {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        color: white;
    }

    .position-badge.general_manager {
        background: linear-gradient(135deg, #f6c23e 0%, #d4a017 100%);
        color: white;
    }

    .position-badge.member {
        background: linear-gradient(135deg, #858796 0%, #60616f 100%);
        color: white;
    }

    .position-badge.bookkeeper {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        color: white;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.active {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-deactivate {
        background: #ffc107;
        color: #000;
    }

    .btn-deactivate:hover {
        background: #ffb300;
        transform: translateY(-2px);
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-2px);
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
        max-width: 900px;
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

    .date-range-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    .date-range-title {
        font-size: 14px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .date-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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
        height: auto !important;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .officer-position-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .officer-position-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
    }

    .position-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .position-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
    }

    .position-icon.chairperson {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .position-icon.vice-chairperson {
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
    }

    .position-icon.treasurer {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .position-icon.secretary {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .position-icon.general-manager {
        background: linear-gradient(135deg, #f6c23e 0%, #d4a017 100%);
    }

    .position-icon.bookkeeper {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
    }

    .position-icon.member {
        background: linear-gradient(135deg, #858796 0%, #60616f 100%);
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

    /* Officers Section Styles */
    .officers-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-header i {
        font-size: 24px;
        color: #667eea;
    }

    .section-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #2d3748;
    }

    /* Officer Cards Grid (for Executive Officers) */
    .officers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .officer-card {
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .officer-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        transition: all 0.5s ease;
    }

    .officer-card:hover::before {
        top: -60%;
        right: -60%;
    }

    .officer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    /* Position-specific card colors */
    .officer-card.chairperson {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .officer-card.vice_chairperson {
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
    }

    .officer-card.secretary {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .officer-card.treasurer {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .officer-card.general_manager {
        background: linear-gradient(135deg, #f6c23e 0%, #d4a017 100%);
    }

    .officer-card.bookkeeper {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
    }

    .officer-icon {
        font-size: 36px;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .officer-position {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        margin-bottom: 10px;
    }

    .officer-name {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .officer-owner {
        font-size: 14px;
        opacity: 0.85;
        margin-bottom: 15px;
    }

    .officer-term {
        font-size: 12px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        opacity: 0.9;
    }

    .officer-term i {
        margin-right: 5px;
    }

    /* Officer List Items (for committees) */
    .officers-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .officer-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
        gap: 15px;
    }

    .officer-list-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .officer-list-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .officer-list-icon.chairperson {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .officer-list-icon.vice-chairperson {
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
    }

    .officer-list-icon.secretary {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .officer-list-icon.treasurer {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .officer-list-icon.general-manager {
        background: linear-gradient(135deg, #f6c23e 0%, #d4a017 100%);
    }

    .officer-list-icon.member {
        background: linear-gradient(135deg, #858796 0%, #60616f 100%);
    }

    .officer-info {
        flex: 1;
    }

    .officer-name-list {
        font-size: 16px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .officer-owner-list {
        font-size: 13px;
        color: #6c757d;
    }

    .officer-position-badge {
        padding: 6px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }

    .officer-actions {
        display: flex;
        gap: 8px;
        margin-left: 12px;
    }

    .btn-officer-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-edit-officer {
        background: #3498db;
        color: white;
    }

    .btn-edit-officer:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }

    .btn-remove-officer {
        background: #e74c3c;
        color: white;
    }

    .btn-remove-officer:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
    }

    .empty-list {
        text-align: center;
        padding: 30px;
        color: #6c757d;
        font-style: italic;
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

    /* Members Section Styles */
    .members-section {
        border-top: 2px dashed #e9ecef;
        padding-top: 20px;
        margin-top: 20px;
    }

    .members-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .btn-add-member {
        padding: 8px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-add-member:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .member-item {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 12px;
    }

    .member-item .form-control {
        flex: 1;
    }

    .btn-remove-member {
        padding: 10px 16px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-remove-member:hover {
        background: #c82333;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .date-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="officers-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2><i class="fas fa-user-tie"></i> Officers Management</h2>
            <p>Manage cooperative officers and their designations</p>
        </div>
        <div style="display: flex; gap: 15px;">
            <a href="{{ route('officers.download-pdf') }}" class="btn-download-officers" target="_blank">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            <button class="btn-assign-officers" onclick="openAssignModal()">
                <i class="fas fa-user-plus"></i> Assign New Officers
            </button>
        </div>
    </div>
</div>

@if($singleOfficers['chairperson'] || $singleOfficers['vice_chairperson'] || $singleOfficers['secretary'])
    {{-- Single Officer Positions --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-crown"></i>
            <h3>Executive Officers</h3>
        </div>
        <div class="officers-grid">
            @foreach($singleOfficers as $position => $officer)
                @if($officer)
                    <div class="officer-card {{ $position }}">
                        @php
                            $icons = [
                                'chairperson' => 'fa-crown',
                                'vice_chairperson' => 'fa-user-shield',
                                'secretary' => 'fa-file-alt',
                                'treasurer' => 'fa-money-bill-wave',
                                'general_manager' => 'fa-briefcase',
                                'bookkeeper' => 'fa-calculator'
                            ];
                        @endphp
                        <div class="officer-icon">
                            <i class="fas {{ $icons[$position] ?? 'fa-user-tie' }}"></i>
                        </div>
                        <div class="officer-position">{{ str_replace('_', ' ', ucwords($position, '_')) }}</div>
                        <div class="officer-name">{{ $officer->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner">{{ $officer->operator->phone ?? 'No Contact' }}</div>
                        <div class="officer-term">
                            <i class="far fa-calendar"></i>
                            {{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Board of Directors --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-users"></i>
            <h3>Board of Directors</h3>
        </div>
        <div class="officers-list">
            @forelse($boardOfDirectors as $officer)
                @php
                    $positionClass = str_replace('_', '-', $officer->position);
                    $positionIcons = [
                        'chairperson' => 'fa-crown',
                        'vice-chairperson' => 'fa-user-shield',
                        'bod-member' => 'fa-user-tie'
                    ];
                    $icon = $positionIcons[$positionClass] ?? 'fa-user';
                @endphp
                <div class="officer-list-item">
                    <div class="officer-list-icon {{ $positionClass }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $officer->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $officer->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">
                        {{ str_replace('_', ' ', ucwords($officer->position, '_')) }}
                    </div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $officer->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $officer->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-list">No members assigned</div>
            @endforelse
        </div>
    </div>

    {{-- Other Officers --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-user-tie"></i>
            <h3>Other Officers</h3>
        </div>
        <div class="officers-list">
            @forelse($otherOfficers as $officer)
                @php
                    $positionClass = str_replace('_', '-', $officer->position);
                    $positionIcons = [
                        'secretary' => 'fa-file-alt',
                        'treasurer' => 'fa-money-bill-wave',
                        'general-manager' => 'fa-briefcase'
                    ];
                    $icon = $positionIcons[$positionClass] ?? 'fa-user';
                @endphp
                <div class="officer-list-item">
                    <div class="officer-list-icon {{ $positionClass }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $officer->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $officer->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">
                        {{ str_replace('_', ' ', ucwords($officer->position, '_')) }}
                    </div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $officer->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $officer->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-list">No members assigned</div>
            @endforelse
        </div>
    </div>

    {{-- Audit Committee --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-search-dollar"></i>
            <h3>Audit Committee</h3>
        </div>
        <div class="officers-list">
            @if($auditCommittee['chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon chairperson">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $auditCommittee['chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $auditCommittee['chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $auditCommittee['chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $auditCommittee['chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($auditCommittee['vice_chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon vice-chairperson">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $auditCommittee['vice_chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $auditCommittee['vice_chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Vice Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $auditCommittee['vice_chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $auditCommittee['vice_chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($auditCommittee['secretary'])
                <div class="officer-list-item">
                    <div class="officer-list-icon secretary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $auditCommittee['secretary']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $auditCommittee['secretary']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Secretary</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $auditCommittee['secretary']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $auditCommittee['secretary']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($auditCommittee['member'])
                <div class="officer-list-item">
                    <div class="officer-list-icon member">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $auditCommittee['member']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $auditCommittee['member']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Member</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $auditCommittee['member']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $auditCommittee['member']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if(!$auditCommittee['chairperson'] && !$auditCommittee['vice_chairperson'] && !$auditCommittee['secretary'] && !$auditCommittee['member'])
                <p style="text-align: center; color: #6c757d; padding: 20px;">No committee officers assigned</p>
            @endif
        </div>
    </div>

    {{-- Election Committee --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-vote-yea"></i>
            <h3>Election Committee</h3>
        </div>
        <div class="officers-list">
            @if($electionCommittee['chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon chairperson">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $electionCommittee['chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $electionCommittee['chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $electionCommittee['chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $electionCommittee['chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($electionCommittee['vice_chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon vice-chairperson">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $electionCommittee['vice_chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $electionCommittee['vice_chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Vice Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $electionCommittee['vice_chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $electionCommittee['vice_chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($electionCommittee['secretary'])
                <div class="officer-list-item">
                    <div class="officer-list-icon secretary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $electionCommittee['secretary']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $electionCommittee['secretary']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Secretary</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $electionCommittee['secretary']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $electionCommittee['secretary']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($electionCommittee['member'])
                <div class="officer-list-item">
                    <div class="officer-list-icon member">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $electionCommittee['member']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $electionCommittee['member']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Member</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $electionCommittee['member']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $electionCommittee['member']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if(!$electionCommittee['chairperson'] && !$electionCommittee['vice_chairperson'] && !$electionCommittee['secretary'] && !$electionCommittee['member'])
                <p style="text-align: center; color: #6c757d; padding: 20px;">No committee officers assigned</p>
            @endif
        </div>
    </div>

    {{-- Mediation and Conciliation Committee --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-handshake"></i>
            <h3>Mediation and Conciliation Committee</h3>
        </div>
        <div class="officers-list">
            @if($mediationCommittee['chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon chairperson">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $mediationCommittee['chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $mediationCommittee['chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $mediationCommittee['chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $mediationCommittee['chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($mediationCommittee['vice_chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon vice-chairperson">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $mediationCommittee['vice_chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $mediationCommittee['vice_chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Vice Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $mediationCommittee['vice_chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $mediationCommittee['vice_chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($mediationCommittee['secretary'])
                <div class="officer-list-item">
                    <div class="officer-list-icon secretary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $mediationCommittee['secretary']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $mediationCommittee['secretary']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Secretary</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $mediationCommittee['secretary']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $mediationCommittee['secretary']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($mediationCommittee['member'])
                <div class="officer-list-item">
                    <div class="officer-list-icon member">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $mediationCommittee['member']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $mediationCommittee['member']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Member</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $mediationCommittee['member']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $mediationCommittee['member']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if(!$mediationCommittee['chairperson'] && !$mediationCommittee['vice_chairperson'] && !$mediationCommittee['secretary'] && !$mediationCommittee['member'])
                <p style="text-align: center; color: #6c757d; padding: 20px;">No committee officers assigned</p>
            @endif
        </div>
    </div>

    {{-- Ethics Committee --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-balance-scale"></i>
            <h3>Ethics Committee</h3>
        </div>
        <div class="officers-list">
            @if($ethicsCommittee['chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon chairperson">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $ethicsCommittee['chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $ethicsCommittee['chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $ethicsCommittee['chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $ethicsCommittee['chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($ethicsCommittee['vice_chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon vice-chairperson">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $ethicsCommittee['vice_chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $ethicsCommittee['vice_chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Vice Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $ethicsCommittee['vice_chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $ethicsCommittee['vice_chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($ethicsCommittee['secretary'])
                <div class="officer-list-item">
                    <div class="officer-list-icon secretary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $ethicsCommittee['secretary']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $ethicsCommittee['secretary']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Secretary</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $ethicsCommittee['secretary']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $ethicsCommittee['secretary']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($ethicsCommittee['member'])
                <div class="officer-list-item">
                    <div class="officer-list-icon member">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $ethicsCommittee['member']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $ethicsCommittee['member']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Member</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $ethicsCommittee['member']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $ethicsCommittee['member']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if(!$ethicsCommittee['chairperson'] && !$ethicsCommittee['vice_chairperson'] && !$ethicsCommittee['secretary'] && !$ethicsCommittee['member'])
                <p style="text-align: center; color: #6c757d; padding: 20px;">No committee officers assigned</p>
            @endif
        </div>
    </div>

    {{-- Gender and Development Committee --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-venus-mars"></i>
            <h3>Gender and Development Committee</h3>
        </div>
        <div class="officers-list">
            @if($gadCommittee['chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon chairperson">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $gadCommittee['chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $gadCommittee['chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $gadCommittee['chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $gadCommittee['chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($gadCommittee['vice_chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon vice-chairperson">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $gadCommittee['vice_chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $gadCommittee['vice_chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Vice Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $gadCommittee['vice_chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $gadCommittee['vice_chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($gadCommittee['secretary'])
                <div class="officer-list-item">
                    <div class="officer-list-icon secretary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $gadCommittee['secretary']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $gadCommittee['secretary']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Secretary</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $gadCommittee['secretary']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $gadCommittee['secretary']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($gadCommittee['member'])
                <div class="officer-list-item">
                    <div class="officer-list-icon member">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $gadCommittee['member']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $gadCommittee['member']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Member</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $gadCommittee['member']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $gadCommittee['member']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if(!$gadCommittee['chairperson'] && !$gadCommittee['vice_chairperson'] && !$gadCommittee['secretary'] && !$gadCommittee['member'])
                <p style="text-align: center; color: #6c757d; padding: 20px;">No committee officers assigned</p>
            @endif
        </div>
    </div>

    {{-- Education Committee --}}
    <div class="officers-section">
        <div class="section-header">
            <i class="fas fa-graduation-cap"></i>
            <h3>Education Committee</h3>
        </div>
        <div class="officers-list">
            @if($educationCommittee['chairperson'])
                <div class="officer-list-item">
                    <div class="officer-list-icon chairperson">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $educationCommittee['chairperson']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $educationCommittee['chairperson']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Chairperson</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $educationCommittee['chairperson']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $educationCommittee['chairperson']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($educationCommittee['secretary'])
                <div class="officer-list-item">
                    <div class="officer-list-icon secretary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $educationCommittee['secretary']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $educationCommittee['secretary']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Secretary</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $educationCommittee['secretary']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $educationCommittee['secretary']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if($educationCommittee['member'])
                <div class="officer-list-item">
                    <div class="officer-list-icon member">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="officer-info">
                        <div class="officer-name-list">{{ $educationCommittee['member']->operator->full_name ?? 'N/A' }}</div>
                        <div class="officer-owner-list">{{ $educationCommittee['member']->operator->phone ?? 'No Contact' }}</div>
                    </div>
                    <div class="officer-position-badge">Member</div>
                    <div class="officer-actions">
                        <button class="btn-officer-action btn-edit-officer" onclick="editOfficer({{ $educationCommittee['member']->id }})" title="Change Position">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-officer-action btn-remove-officer" onclick="removeOfficer({{ $educationCommittee['member']->id }})" title="Remove Officer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if(!$educationCommittee['chairperson'] && !$educationCommittee['secretary'] && !$educationCommittee['member'])
                <p style="text-align: center; color: #6c757d; padding: 20px;">No committee officers assigned</p>
            @endif
        </div>
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-user-tie"></i>
        <h3>No Officers Assigned</h3>
        <p>Click the "Assign New Officers" button to assign officers to the cooperative</p>
    </div>
@endif

<!-- Assign Officers Modal -->
<div class="modal-overlay" id="assignModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Assign New Officers</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="assignForm">
                @csrf

                <!-- Chairperson -->
                <div class="officer-position-card">
                    <div class="position-title">
                        <div class="position-icon chairperson">
                            <i class="fas fa-crown"></i>
                        </div>
                        Chairperson
                    </div>
                    <div class="form-group">
                        <label for="chairperson_id">Select Operator <span class="required">*</span></label>
                        <select id="chairperson_id" name="chairperson_id" class="form-control" onchange="updateAllDropdowns()" required>
                            <option value="">-- Choose Operator --</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="date-row">
                        <div class="form-group">
                            <label for="chairperson_effective_from">Effective From <span class="required">*</span></label>
                            <input type="date" id="chairperson_effective_from" name="chairperson_effective_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="chairperson_effective_to">Effective To <span class="required">*</span></label>
                            <input type="date" id="chairperson_effective_to" name="chairperson_effective_to" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Vice-Chairperson -->
                <div class="officer-position-card">
                    <div class="position-title">
                        <div class="position-icon vice-chairperson">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        Vice-Chairperson
                    </div>
                    <div class="form-group">
                        <label for="vice_chairperson_id">Select Operator <span class="required">*</span></label>
                        <select id="vice_chairperson_id" name="vice_chairperson_id" class="form-control" onchange="updateAllDropdowns()" required>
                            <option value="">-- Choose Operator --</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="date-row">
                        <div class="form-group">
                            <label for="vice_chairperson_effective_from">Effective From <span class="required">*</span></label>
                            <input type="date" id="vice_chairperson_effective_from" name="vice_chairperson_effective_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="vice_chairperson_effective_to">Effective To <span class="required">*</span></label>
                            <input type="date" id="vice_chairperson_effective_to" name="vice_chairperson_effective_to" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Secretary -->
                <div class="officer-position-card">
                    <div class="position-title">
                        <div class="position-icon secretary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        Secretary
                    </div>
                    <div class="form-group">
                        <label for="secretary_id">Select Operator <span class="required">*</span></label>
                        <select id="secretary_id" name="secretary_id" class="form-control" onchange="updateAllDropdowns()" required>
                            <option value="">-- Choose Operator --</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="date-row">
                        <div class="form-group">
                            <label for="secretary_effective_from">Effective From <span class="required">*</span></label>
                            <input type="date" id="secretary_effective_from" name="secretary_effective_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="secretary_effective_to">Effective To <span class="required">*</span></label>
                            <input type="date" id="secretary_effective_to" name="secretary_effective_to" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Treasurer -->
                <div class="officer-position-card">
                    <div class="position-title">
                        <div class="position-icon treasurer">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        Treasurer
                    </div>
                    <div class="form-group">
                        <label for="treasurer_id">Select Operator <span class="required">*</span></label>
                        <select id="treasurer_id" name="treasurer_id" class="form-control" onchange="updateAllDropdowns()" required>
                            <option value="">-- Choose Operator --</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="date-row">
                        <div class="form-group">
                            <label for="treasurer_effective_from">Effective From <span class="required">*</span></label>
                            <input type="date" id="treasurer_effective_from" name="treasurer_effective_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="treasurer_effective_to">Effective To <span class="required">*</span></label>
                            <input type="date" id="treasurer_effective_to" name="treasurer_effective_to" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- General Manager -->
                <div class="officer-position-card">
                    <div class="position-title">
                        <div class="position-icon general-manager">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        General Manager
                    </div>
                    <div class="form-group">
                        <label for="general_manager_id">Select Operator <span class="required">*</span></label>
                        <select id="general_manager_id" name="general_manager_id" class="form-control" onchange="updateAllDropdowns()" required>
                            <option value="">-- Choose Operator --</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="date-row">
                        <div class="form-group">
                            <label for="general_manager_effective_from">Effective From <span class="required">*</span></label>
                            <input type="date" id="general_manager_effective_from" name="general_manager_effective_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="general_manager_effective_to">Effective To <span class="required">*</span></label>
                            <input type="date" id="general_manager_effective_to" name="general_manager_effective_to" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Bookkeeper -->
                <div class="officer-position-card">
                    <div class="position-title">
                        <div class="position-icon bookkeeper">
                            <i class="fas fa-book"></i>
                        </div>
                        Bookkeeper
                    </div>
                    <div class="form-group">
                        <label for="bookkeeper_id">Select Operator <span class="required">*</span></label>
                        <select id="bookkeeper_id" name="bookkeeper_id" class="form-control" onchange="updateAllDropdowns()" required>
                            <option value="">-- Choose Operator --</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="date-row">
                        <div class="form-group">
                            <label for="bookkeeper_effective_from">Effective From <span class="required">*</span></label>
                            <input type="date" id="bookkeeper_effective_from" name="bookkeeper_effective_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="bookkeeper_effective_to">Effective To <span class="required">*</span></label>
                            <input type="date" id="bookkeeper_effective_to" name="bookkeeper_effective_to" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Committee Sections -->
                <div style="margin-top: 30px; border-top: 3px solid #e9ecef; padding-top: 30px;">
                    <h3 style="margin: 0 0 20px 0; color: #667eea; font-size: 20px; font-weight: 700;">
                        <i class="fas fa-users-cog"></i> Committee Officers & Members
                    </h3>

                    <!-- Board of Directors -->
                    <div class="officer-position-card">
                        <div class="position-title">
                            <div class="position-icon member">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            Board of Directors
                        </div>
                        <p style="margin-bottom: 15px; color: #6c757d; font-size: 14px;">
                            <i class="fas fa-info-circle"></i> Board consists of Chairperson (from Executive Officers), Vice-Chairperson (from Executive Officers), and 5 BOD Members
                        </p>

                        <!-- BOD Term Duration -->
                        <div class="date-row">
                            <div class="form-group">
                                <label for="bod_effective_from">Term Effective From</label>
                                <input type="date" id="bod_effective_from" name="bod_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="bod_effective_to">Term Effective To</label>
                                <input type="date" id="bod_effective_to" name="bod_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- BOD Member 1 -->
                        <div class="form-group">
                            <label for="bod_member_1">BOD Member 1</label>
                            <select id="bod_member_1" name="bod_members[]" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- BOD Member 2 -->
                        <div class="form-group">
                            <label for="bod_member_2">BOD Member 2</label>
                            <select id="bod_member_2" name="bod_members[]" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- BOD Member 3 -->
                        <div class="form-group">
                            <label for="bod_member_3">BOD Member 3</label>
                            <select id="bod_member_3" name="bod_members[]" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- BOD Member 4 -->
                        <div class="form-group">
                            <label for="bod_member_4">BOD Member 4</label>
                            <select id="bod_member_4" name="bod_members[]" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- BOD Member 5 -->
                        <div class="form-group">
                            <label for="bod_member_5">BOD Member 5</label>
                            <select id="bod_member_5" name="bod_members[]" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Audit Committee -->
                    <div class="officer-position-card">
                        <div class="position-title">
                            <div class="position-icon member">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            Audit Committee
                        </div>
                        <!-- Audit Committee Chairperson -->
                        <div class="form-group">
                            <label for="audit_chairperson_id">Chairperson</label>
                            <select id="audit_chairperson_id" name="audit_chairperson_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="audit_chairperson_effective_from">Effective From</label>
                                <input type="date" id="audit_chairperson_effective_from" name="audit_chairperson_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="audit_chairperson_effective_to">Effective To</label>
                                <input type="date" id="audit_chairperson_effective_to" name="audit_chairperson_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Audit Committee Secretary -->
                        <div class="form-group">
                            <label for="audit_secretary_id">Secretary</label>
                            <select id="audit_secretary_id" name="audit_secretary_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="audit_secretary_effective_from">Effective From</label>
                                <input type="date" id="audit_secretary_effective_from" name="audit_secretary_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="audit_secretary_effective_to">Effective To</label>
                                <input type="date" id="audit_secretary_effective_to" name="audit_secretary_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Audit Committee Member -->
                        <div class="form-group">
                            <label for="audit_member_id">Member</label>
                            <select id="audit_member_id" name="audit_member_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="audit_member_effective_from">Effective From</label>
                                <input type="date" id="audit_member_effective_from" name="audit_member_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="audit_member_effective_to">Effective To</label>
                                <input type="date" id="audit_member_effective_to" name="audit_member_effective_to" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Election Committee -->
                    <div class="officer-position-card">
                        <div class="position-title">
                            <div class="position-icon member">
                                <i class="fas fa-vote-yea"></i>
                            </div>
                            Election Committee
                        </div>
                        <!-- Election Committee Chairperson -->
                        <div class="form-group">
                            <label for="election_chairperson_id">Chairperson</label>
                            <select id="election_chairperson_id" name="election_chairperson_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="election_chairperson_effective_from">Effective From</label>
                                <input type="date" id="election_chairperson_effective_from" name="election_chairperson_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="election_chairperson_effective_to">Effective To</label>
                                <input type="date" id="election_chairperson_effective_to" name="election_chairperson_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Election Committee Secretary -->
                        <div class="form-group">
                            <label for="election_secretary_id">Secretary</label>
                            <select id="election_secretary_id" name="election_secretary_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="election_secretary_effective_from">Effective From</label>
                                <input type="date" id="election_secretary_effective_from" name="election_secretary_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="election_secretary_effective_to">Effective To</label>
                                <input type="date" id="election_secretary_effective_to" name="election_secretary_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Election Committee Member -->
                        <div class="form-group">
                            <label for="election_member_id">Member</label>
                            <select id="election_member_id" name="election_member_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="election_member_effective_from">Effective From</label>
                                <input type="date" id="election_member_effective_from" name="election_member_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="election_member_effective_to">Effective To</label>
                                <input type="date" id="election_member_effective_to" name="election_member_effective_to" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Mediation and Conciliation Committee -->
                    <div class="officer-position-card">
                        <div class="position-title">
                            <div class="position-icon member">
                                <i class="fas fa-handshake"></i>
                            </div>
                            Mediation and Conciliation Committee
                        </div>
                        <!-- Mediation Committee Chairperson -->
                        <div class="form-group">
                            <label for="mediation_chairperson_id">Chairperson</label>
                            <select id="mediation_chairperson_id" name="mediation_chairperson_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="mediation_chairperson_effective_from">Effective From</label>
                                <input type="date" id="mediation_chairperson_effective_from" name="mediation_chairperson_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="mediation_chairperson_effective_to">Effective To</label>
                                <input type="date" id="mediation_chairperson_effective_to" name="mediation_chairperson_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Mediation Committee Secretary -->
                        <div class="form-group">
                            <label for="mediation_secretary_id">Secretary</label>
                            <select id="mediation_secretary_id" name="mediation_secretary_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="mediation_secretary_effective_from">Effective From</label>
                                <input type="date" id="mediation_secretary_effective_from" name="mediation_secretary_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="mediation_secretary_effective_to">Effective To</label>
                                <input type="date" id="mediation_secretary_effective_to" name="mediation_secretary_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Mediation Committee Member -->
                        <div class="form-group">
                            <label for="mediation_member_id">Member</label>
                            <select id="mediation_member_id" name="mediation_member_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="mediation_member_effective_from">Effective From</label>
                                <input type="date" id="mediation_member_effective_from" name="mediation_member_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="mediation_member_effective_to">Effective To</label>
                                <input type="date" id="mediation_member_effective_to" name="mediation_member_effective_to" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Ethics Committee -->
                    <div class="officer-position-card">
                        <div class="position-title">
                            <div class="position-icon member">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            Ethics Committee
                        </div>
                        <!-- Ethics Committee Chairperson -->
                        <div class="form-group">
                            <label for="ethics_chairperson_id">Chairperson</label>
                            <select id="ethics_chairperson_id" name="ethics_chairperson_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="ethics_chairperson_effective_from">Effective From</label>
                                <input type="date" id="ethics_chairperson_effective_from" name="ethics_chairperson_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="ethics_chairperson_effective_to">Effective To</label>
                                <input type="date" id="ethics_chairperson_effective_to" name="ethics_chairperson_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Ethics Committee Secretary -->
                        <div class="form-group">
                            <label for="ethics_secretary_id">Secretary</label>
                            <select id="ethics_secretary_id" name="ethics_secretary_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="ethics_secretary_effective_from">Effective From</label>
                                <input type="date" id="ethics_secretary_effective_from" name="ethics_secretary_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="ethics_secretary_effective_to">Effective To</label>
                                <input type="date" id="ethics_secretary_effective_to" name="ethics_secretary_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Ethics Committee Member -->
                        <div class="form-group">
                            <label for="ethics_member_id">Member</label>
                            <select id="ethics_member_id" name="ethics_member_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="ethics_member_effective_from">Effective From</label>
                                <input type="date" id="ethics_member_effective_from" name="ethics_member_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="ethics_member_effective_to">Effective To</label>
                                <input type="date" id="ethics_member_effective_to" name="ethics_member_effective_to" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Gender and Development Committee -->
                    <div class="officer-position-card">
                        <div class="position-title">
                            <div class="position-icon member">
                                <i class="fas fa-users"></i>
                            </div>
                            Gender and Development Committee
                        </div>
                        <!-- GAD Committee Chairperson -->
                        <div class="form-group">
                            <label for="gad_chairperson_id">Chairperson</label>
                            <select id="gad_chairperson_id" name="gad_chairperson_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="gad_chairperson_effective_from">Effective From</label>
                                <input type="date" id="gad_chairperson_effective_from" name="gad_chairperson_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="gad_chairperson_effective_to">Effective To</label>
                                <input type="date" id="gad_chairperson_effective_to" name="gad_chairperson_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- GAD Committee Secretary -->
                        <div class="form-group">
                            <label for="gad_secretary_id">Secretary</label>
                            <select id="gad_secretary_id" name="gad_secretary_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="gad_secretary_effective_from">Effective From</label>
                                <input type="date" id="gad_secretary_effective_from" name="gad_secretary_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="gad_secretary_effective_to">Effective To</label>
                                <input type="date" id="gad_secretary_effective_to" name="gad_secretary_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- GAD Committee Member -->
                        <div class="form-group">
                            <label for="gad_member_id">Member</label>
                            <select id="gad_member_id" name="gad_member_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="gad_member_effective_from">Effective From</label>
                                <input type="date" id="gad_member_effective_from" name="gad_member_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="gad_member_effective_to">Effective To</label>
                                <input type="date" id="gad_member_effective_to" name="gad_member_effective_to" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Education Committee -->
                    <div class="officer-position-card">
                        <div class="position-title">
                            <div class="position-icon member">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            Education Committee
                        </div>
                        <!-- Education Committee Chairperson -->
                        <div class="form-group">
                            <label for="education_chairperson_id">Chairperson</label>
                            <select id="education_chairperson_id" name="education_chairperson_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="education_chairperson_effective_from">Effective From</label>
                                <input type="date" id="education_chairperson_effective_from" name="education_chairperson_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="education_chairperson_effective_to">Effective To</label>
                                <input type="date" id="education_chairperson_effective_to" name="education_chairperson_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Education Committee Secretary -->
                        <div class="form-group">
                            <label for="education_secretary_id">Secretary</label>
                            <select id="education_secretary_id" name="education_secretary_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="education_secretary_effective_from">Effective From</label>
                                <input type="date" id="education_secretary_effective_from" name="education_secretary_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="education_secretary_effective_to">Effective To</label>
                                <input type="date" id="education_secretary_effective_to" name="education_secretary_effective_to" class="form-control">
                            </div>
                        </div>

                        <!-- Education Committee Member -->
                        <div class="form-group">
                            <label for="education_member_id">Member</label>
                            <select id="education_member_id" name="education_member_id" class="form-control" onchange="updateAllDropdowns()">
                                <option value="">-- Choose Operator --</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="date-row">
                            <div class="form-group">
                                <label for="education_member_effective_from">Effective From</label>
                                <input type="date" id="education_member_effective_from" name="education_member_effective_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="education_member_effective_to">Effective To</label>
                                <input type="date" id="education_member_effective_to" name="education_member_effective_to" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-submit" onclick="submitAssignment()">
                <i class="fas fa-save"></i> Assign Officers
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let memberCounts = {
        bod: 0,
        audit: 0,
        election: 0,
        mediation: 0,
        ethics: 0,
        gad: 0,
        education: 0
    };
    const positionSelects = [
        'chairperson_id', 'vice_chairperson_id', 'secretary_id', 'treasurer_id', 'general_manager_id', 'bookkeeper_id',
        // Committee officers
        'audit_chairperson_id', 'audit_vice_chairperson_id', 'audit_secretary_id', 'audit_member_id',
        'election_chairperson_id', 'election_vice_chairperson_id', 'election_secretary_id', 'election_member_id',
        'mediation_chairperson_id', 'mediation_vice_chairperson_id', 'mediation_secretary_id', 'mediation_member_id',
        'ethics_chairperson_id', 'ethics_vice_chairperson_id', 'ethics_secretary_id', 'ethics_member_id',
        'gad_chairperson_id', 'gad_vice_chairperson_id', 'gad_secretary_id', 'gad_member_id',
        'education_chairperson_id', 'education_secretary_id', 'education_member_id'
    ];

    function openAssignModal() {
        document.getElementById('assignModal').classList.add('active');
        document.getElementById('assignForm').reset();

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('effective_from').min = today;
        document.getElementById('effective_to').min = today;

        // Reset member counts
        memberCounts = {
            bod: 0,
            audit: 0,
            election: 0,
            mediation: 0,
            ethics: 0,
            gad: 0,
            education: 0
        };

        // Clear all dynamically added member fields
        const committeeTypes = ['bod', 'audit', 'election', 'mediation', 'ethics', 'gad'];
        committeeTypes.forEach(type => {
            const container = document.getElementById(`${type}-members-container`);
            if (container) {
                container.innerHTML = '';
            }
        });

        // Reset all dropdowns to show all operators
        updateAllDropdowns();
    }

    function closeModal() {
        document.getElementById('assignModal').classList.remove('active');
    }

    // Function to update all dropdowns based on current selections
    function updateAllDropdowns() {
        const selectedOperators = new Set();

        // Collect all selected operator IDs from main positions
        positionSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select && select.value) {
                selectedOperators.add(String(select.value));
            }
        });

        // Collect all selected operator IDs from ALL committee members
        const committeeTypes = ['bod', 'audit', 'election', 'mediation', 'ethics', 'gad'];
        committeeTypes.forEach(type => {
            const committeeSelects = document.querySelectorAll(`select[name="${type}_members[]"]`);
            committeeSelects.forEach(select => {
                if (select.value) {
                    selectedOperators.add(String(select.value));
                }
            });
        });

        // Collect selections from BOD member dropdowns (with IDs)
        for (let i = 1; i <= 5; i++) {
            const bodSelect = document.getElementById(`bod_member_${i}`);
            if (bodSelect && bodSelect.value) {
                selectedOperators.add(String(bodSelect.value));
            }
        }

        // Update each officer position dropdown
        positionSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (!select) return;

            const currentValue = String(select.value);
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                const optionValue = String(option.value);
                if (optionValue === '') {
                    // Keep the placeholder option always visible
                    option.disabled = false;
                    option.style.display = '';
                } else if (optionValue === currentValue) {
                    // Keep the current selection visible and enabled
                    option.disabled = false;
                    option.style.display = '';
                } else if (selectedOperators.has(optionValue)) {
                    // Hide options that are selected in other dropdowns
                    option.disabled = true;
                    option.style.display = 'none';
                } else {
                    // Show all other options
                    option.disabled = false;
                    option.style.display = '';
                }
            });
        });

        // Update BOD member dropdowns
        for (let i = 1; i <= 5; i++) {
            const bodSelect = document.getElementById(`bod_member_${i}`);
            if (!bodSelect) continue;

            const currentValue = String(bodSelect.value);
            const options = bodSelect.querySelectorAll('option');

            options.forEach(option => {
                const optionValue = String(option.value);
                if (optionValue === '') {
                    // Keep the placeholder option always visible
                    option.disabled = false;
                    option.style.display = '';
                } else if (optionValue === currentValue) {
                    // Keep the current selection visible and enabled
                    option.disabled = false;
                    option.style.display = '';
                } else if (selectedOperators.has(optionValue)) {
                    // Hide options that are selected in other dropdowns
                    option.disabled = true;
                    option.style.display = 'none';
                } else {
                    // Show all other options
                    option.disabled = false;
                    option.style.display = '';
                }
            });
        }

        // Also update all committee member dropdowns
        updateAllCommitteeDropdowns();
    }

    // Add event listeners to all position selects
    document.addEventListener('DOMContentLoaded', function() {
        positionSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                select.addEventListener('change', updateAllDropdowns);
            }
        });

        // Close modal when clicking outside
        const assignModal = document.getElementById('assignModal');
        if (assignModal) {
            assignModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }
    });

    function addMemberField(committeeType) {
        memberCounts[committeeType]++;
        const container = document.getElementById(`${committeeType}-members-container`);
        const count = memberCounts[committeeType];

        // Get all selected operators from ALL officer positions and ALL committee members
        const selectedOperators = new Set();

        // Add all officer position selections
        positionSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select && select.value) {
                selectedOperators.add(String(select.value));
            }
        });

        // Add all committee member selections from ALL committees
        const committeeTypes = ['bod', 'audit', 'election', 'mediation', 'ethics', 'gad'];
        committeeTypes.forEach(type => {
            const committeeSelects = document.querySelectorAll(`select[name="${type}_members[]"]`);
            committeeSelects.forEach(select => {
                if (select.value) {
                    selectedOperators.add(String(select.value));
                }
            });
        });

        // Add BOD member selections (with IDs)
        for (let i = 1; i <= 5; i++) {
            const bodSelect = document.getElementById(`bod_member_${i}`);
            if (bodSelect && bodSelect.value) {
                selectedOperators.add(String(bodSelect.value));
            }
        }

        // Build the select options, hiding already selected operators
        const operators = [
            @foreach($operators as $operator)
            {
                id: String({{ $operator->id }}),
                name: '{{ addslashes($operator->full_name) }}'
            },
            @endforeach
        ];

        let optionsHtml = '<option value="">-- Choose Member --</option>';
        operators.forEach(op => {
            if (!selectedOperators.has(op.id)) {
                optionsHtml += `<option value="${op.id}">${op.name}</option>`;
            }
        });

        const memberHtml = `
            <div class="member-item" id="${committeeType}-member-${count}">
                <select name="${committeeType}_members[]" class="form-control member-select committee-member-select" data-committee="${committeeType}">
                    ${optionsHtml}
                </select>
                <button type="button" class="btn-remove-member" onclick="removeMemberField('${committeeType}', ${count})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', memberHtml);

        // Add change event listener to update ALL dropdowns when selection changes
        const newSelect = container.querySelector(`#${committeeType}-member-${count} select`);
        if (newSelect) {
            newSelect.addEventListener('change', updateAllDropdowns);
        }
    }

    function removeMemberField(committeeType, id) {
        const element = document.getElementById(`${committeeType}-member-${id}`);
        if (element) {
            element.remove();
            // Update all dropdowns after removing a member
            updateAllCommitteeDropdowns();
        }
    }

    function updateAllCommitteeDropdowns() {
        // Get all currently selected operators across ALL positions and committees
        const selectedOperators = new Set();

        // Add all officer position selections
        positionSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select && select.value) {
                selectedOperators.add(String(select.value));
            }
        });

        // Add all committee member selections from ALL committees
        const committeeTypes = ['bod', 'audit', 'election', 'mediation', 'ethics', 'gad'];
        committeeTypes.forEach(type => {
            const committeeSelects = document.querySelectorAll(`select[name="${type}_members[]"]`);
            committeeSelects.forEach(select => {
                if (select.value) {
                    selectedOperators.add(String(select.value));
                }
            });
        });

        // Add BOD member selections (with IDs)
        for (let i = 1; i <= 5; i++) {
            const bodSelect = document.getElementById(`bod_member_${i}`);
            if (bodSelect && bodSelect.value) {
                selectedOperators.add(String(bodSelect.value));
            }
        }

        // Get all operators
        const operators = [
            @foreach($operators as $operator)
            {
                id: String({{ $operator->id }}),
                name: '{{ addslashes($operator->full_name) }}'
            },
            @endforeach
        ];

        // Update each committee member dropdown
        const allCommitteeSelects = document.querySelectorAll('.committee-member-select');
        allCommitteeSelects.forEach(select => {
            const currentValue = String(select.value);

            // Rebuild options, keeping currently selected value available
            let optionsHtml = '<option value="">-- Choose Member --</option>';
            operators.forEach(op => {
                // Show option if it's not selected elsewhere OR if it's the current selection
                if (!selectedOperators.has(op.id) || op.id === currentValue) {
                    optionsHtml += `<option value="${op.id}" ${op.id === currentValue ? 'selected' : ''}>${op.name}</option>`;
                }
            });

            select.innerHTML = optionsHtml;
        });
    }

    async function submitAssignment() {
        const form = document.getElementById('assignForm');
        const formData = new FormData(form);
        const data = {};

        // Get all form data
        const committeeTypes = ['bod', 'audit', 'election', 'mediation', 'ethics', 'gad'];
        committeeTypes.forEach(type => {
            data[`${type}_members`] = [];
        });

        for (let [key, value] of formData.entries()) {
            if (key.endsWith('_members[]')) {
                const committeeType = key.replace('_members[]', '');
                if (value) data[`${committeeType}_members`].push(value);
            } else {
                data[key] = value;
            }
        }

        // Check for duplicate selections
        const selections = [
            data.chairperson_id,
            data.vice_chairperson_id,
            data.secretary_id,
            data.treasurer_id,
            data.general_manager_id,
            data.bookkeeper_id
        ];
        const uniqueSelections = new Set(selections);
        if (uniqueSelections.size !== selections.length) {
            alert('Error: The same operator cannot hold multiple positions.');
            return;
        }

        try {
            const response = await fetch('{{ route("officers.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                closeModal();
                window.location.reload();
            } else {
                alert('Error: ' + (result.message || 'Failed to assign officers'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while assigning officers');
        }
    }

    async function toggleStatus(officerId, status) {
        if (!confirm('Are you sure you want to change the status of this officer?')) {
            return;
        }

        try {
            const response = await fetch(`/officers/${officerId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ is_active: status })
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Error: Failed to update officer status');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating officer status');
        }
    }

    async function deleteOfficer(officerId) {
        if (!confirm('Are you sure you want to remove this officer? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/officers/${officerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Error: Failed to delete officer');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting officer');
        }
    }

    function editOfficer(officerId) {
        // Redirect to edit page or open edit modal
        // For now, we'll just inform the user to use the "Assign New Officers" button
        alert('To change an officer position, please use the "Assign New Officers" button to create a new officer assignment. The previous assignment will be automatically deactivated.');
    }

    async function removeOfficer(officerId) {
        if (!confirm('Are you sure you want to remove this officer from their position? This action will deactivate their current assignment.')) {
            return;
        }

        try {
            const response = await fetch(`/officers/${officerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Error: Failed to remove officer');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while removing officer');
        }
    }
</script>
@endpush
