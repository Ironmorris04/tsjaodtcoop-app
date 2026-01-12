@extends('layouts.app')

@section('title', 'Review Registration')

@section('page-title', 'Review Registration - ' . $operator->contact_person)

@push('styles')
<style>
    .review-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-section {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .section-header {
        border-bottom: 3px solid #667eea;
        padding-bottom: 0.8rem;
        margin-bottom: 1.5rem;
    }

    .section-header h4 {
        color: #667eea;
        font-weight: 600;
        margin: 0;
    }

    .section-header i {
        margin-right: 0.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        border-left: 3px solid #4e73df;
        padding-left: 15px;
    }

    .info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        word-wrap: break-word;
    }

    .info-value.empty {
        color: #bdc3c7;
        font-style: italic;
    }

    .profile-section {
        text-align: center;
        padding: 20px;
    }

    .profile-photo-display {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin: 0 auto 15px;
        overflow: hidden;
        border: 4px solid #667eea;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .profile-photo-display img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo-display .no-photo {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 48px;
    }

    .document-preview {
        background: #f8f9fa;
        border: 2px dashed #ced4da;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }

    .document-preview a {
        color: #4e73df;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .document-preview a:hover {
        text-decoration: underline;
    }

    .dependents-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .dependents-table thead {
        background: #f8f9fa;
    }

    .dependents-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }

    .dependents-table td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        color: #2c3e50;
    }

    .actions-section {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-top: 30px;
    }

    .actions-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .actions-header h4 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .actions-header p {
        color: #6c757d;
        margin: 0;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 35px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-approve {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        color: white;
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    /* Modal styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }

    .modal-title {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modal-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2c3e50;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 25px;
        border-top: 1px solid #e9ecef;
    }

    /* Membership Form Upload Section */
    .upload-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 20px;
    }

    .upload-column {
        display: flex;
        flex-direction: column;
    }

    .upload-column .info-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #667eea;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .upload-column .info-label i {
        font-size: 14px;
    }

    .upload-form-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .file-input-group {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .file-input-wrapper {
        flex: 1;
        position: relative;
    }

    .file-input-wrapper .form-control {
        padding: 10px 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .file-input-wrapper .form-control:hover {
        border-color: #667eea;
    }

    .file-input-wrapper .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .file-hint {
        font-size: 12px;
        color: #6c757d;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .file-hint i {
        font-size: 11px;
    }

    .btn-upload {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-upload:active {
        transform: translateY(0);
    }

    .btn-upload i {
        font-size: 14px;
    }

    .document-preview.with-document {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: 2px solid #667eea;
        position: relative;
    }

    .document-preview.with-document::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 8px;
        opacity: 0.1;
        pointer-events: none;
    }

    .document-preview a i {
        font-size: 18px;
    }

    .document-preview .empty {
        color: #bdc3c7;
        font-style: italic;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .document-preview .empty i {
        font-size: 24px;
        color: #ced4da;
    }

    @media (max-width: 768px) {
        .review-container {
            padding: 10px;
        }

        .form-section {
            padding: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .upload-section {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .file-input-group {
            flex-direction: column;
        }

        .btn-upload {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="review-container">
    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Error:</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Personal Information Section -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-user"></i> Personal Information</h4>
        </div>

        @if($details)
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">First Name</div>
                <div class="info-value">{{ $details->first_name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Middle Name</div>
                <div class="info-value {{ !$details->middle_name ? 'empty' : '' }}">
                    {{ $details->middle_name ?: 'Not provided' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Last Name</div>
                <div class="info-value">{{ $details->last_name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Birthdate</div>
                <div class="info-value">
                    {{ $details->birthdate ? \Carbon\Carbon::parse($details->birthdate)->format('F d, Y') : 'N/A' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Age</div>
                <div class="info-value">
                    {{ $details->birthdate ? \Carbon\Carbon::parse($details->birthdate)->age . ' years old' : 'N/A' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Birthplace</div>
                <div class="info-value">{{ $details->birthplace ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Religion</div>
                <div class="info-value {{ !$details->religion ? 'empty' : '' }}">
                    {{ $details->religion ?: 'Not provided' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Citizenship</div>
                <div class="info-value">{{ $details->citizenship ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Occupation</div>
                <div class="info-value {{ !$details->occupation ? 'empty' : '' }}">
                    {{ $details->occupation ?: 'Not provided' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ $operator->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Contact Number</div>
                <div class="info-value">{{ $operator->phone }}</div>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <div class="info-item">
                <div class="info-label">Complete Address</div>
                <div class="info-value">{{ $operator->address }}</div>
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 40px; color: #e74c3c;">
            <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
            <h4>No Extended Personal Information Available</h4>
            <p style="color: #6c757d; margin-top: 10px;">This operator registration does not include detailed personal information. Only basic account information is available.</p>
        </div>

        <div style="margin-top: 20px;">
            <div class="info-label" style="margin-bottom: 10px;">Available Basic Information:</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Contact Person</div>
                    <div class="info-value">{{ $operator->contact_person }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $operator->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Contact Number</div>
                    <div class="info-value">{{ $operator->phone }}</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <div class="info-item">
                <div class="info-label">Complete Address</div>
                <div class="info-value">{{ $operator->address }}</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Demographics Section -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-users"></i> Demographics</h4>
        </div>

        @if($details)
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Sex</div>
                <div class="info-value">{{ ucfirst($details->sex ?? 'N/A') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Civil Status</div>
                <div class="info-value">{{ ucfirst($details->civil_status ?? 'N/A') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Indigenous People</div>
                <div class="info-value">{{ ucfirst($details->indigenous_people ?? 'no') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Person with Disability</div>
                <div class="info-value">{{ ucfirst($details->pwd ?? 'no') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Senior Citizen</div>
                <div class="info-value">{{ ucfirst($details->senior_citizen ?? 'no') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">4Ps Beneficiary</div>
                <div class="info-value">{{ ucfirst($details->fourps_beneficiary ?? 'no') }}</div>
            </div>
        </div>
        @else
        <p style="text-align: center; color: #6c757d; padding: 20px;">
            <i class="fas fa-info-circle"></i> No demographic information available
        </p>
        @endif
    </div>

    <!-- Identification Section -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-id-card"></i> Identification</h4>
        </div>

        @if($details)
        <div class="row">
            <div class="col-md-6">
                <div class="profile-section">
                    <div class="info-label">Profile Photo</div>
                    <div class="profile-photo-display">
                       @if($details->profile_photo_url)
                            <img src="{{ $details->profile_photo_url }}" alt="Profile Photo">
                        @else
                            <div class="no-photo">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">ID Type</div>
                        <div class="info-value">{{ $details->formatted_id_type ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ID Number</div>
                        <div class="info-value">{{ $details->id_number ?? 'N/A' }}</div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <div class="info-label">Valid ID Document</div>
                    <div class="document-preview">
                        @if($details->valid_id_url)
                            <a href="{{ $details->valid_id_url }}" target="_blank">
                                <i class="fas fa-file-download"></i>
                                View/Download ID Document
                            </a>
                        @else
                            <span class="empty">No document uploaded</span>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        @else
        <p style="text-align: center; color: #6c757d; padding: 20px;">
            <i class="fas fa-info-circle"></i> No identification information available
        </p>
        @endif
    </div>

    <!-- Dependents Section -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-people-roof"></i> List of Dependents</h4>
        </div>

        @if($dependents && count($dependents) > 0)
            <table class="dependents-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Relation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dependents as $dependent)
                        <tr>
                            <td>{{ $dependent->name }}</td>
                            <td>{{ $dependent->age ?? 'N/A' }}</td>
                            <td>{{ $dependent->relation ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #6c757d; padding: 20px;">
                <i class="fas fa-info-circle"></i> No dependents listed
            </p>
        @endif
    </div>

    <!-- Membership Form Section -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-file-upload"></i> Submitted Membership Form</h4>
        </div>

        <div class="upload-section">
            <!-- Current Document Display -->
            <div class="upload-column">
                <div class="info-label">
                    <i class="fas fa-file-alt"></i>
                    Current Membership Form
                </div>
                <div class="document-preview {{ $operator->membership_form_path ? 'with-document' : '' }}">
                    @if($operator->membership_form_path)
                        @php
                            $extension = pathinfo($operator->membership_form_path, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($extension), ['jpg','jpeg','png']))
                            <a href="{{ $operator->membership_form_url }}" target="_blank">
                                <i class="fas fa-file-image"></i>
                                View Membership Form (Image)
                            </a>
                            <a href="{{ $operator->membership_form_url }}" download class="ml-2">
                                <i class="fas fa-download"></i>
                                Download
                            </a>
                        @elseif(strtolower($extension) === 'pdf')
                            <a href="{{ route('registrations.view-membership-form', $operator->id) }}" target="_blank">
                                <i class="fas fa-file-pdf"></i>
                                View Membership Form (PDF)
                            </a>
                            <a href="{{ route('registrations.download-membership-form', $operator->id) }}" class="ml-2">
                                <i class="fas fa-download"></i>
                                Download
                            </a>
                        @endif
                    @else
                        <span class="empty">
                            <i class="fas fa-inbox"></i>
                            No membership form uploaded yet
                        </span>
                    @endif
                </div>

            </div>

            <!-- Upload Form -->
            <div class="upload-column">
                <div class="info-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Upload New Membership Form (Scanned via Adobe Scan)
                </div>
                <form action="{{ route('registrations.upload-membership-form', $operator->id) }}" method="POST" enctype="multipart/form-data" id="uploadMembershipForm" class="upload-form-wrapper">
                    @csrf
                    <div class="file-input-group">
                        <div class="file-input-wrapper">
                            <input type="file" name="membership_form" id="membership_form" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-hint">
                                <i class="fas fa-info-circle"></i>
                                Accepted: PDF, JPG, PNG (PDF recommended for best quality, Max 10MB)
                            </div>
                        </div>
                        <button type="submit" class="btn-upload">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Action Buttons Section -->
    <div class="actions-section">
        <div class="actions-header">
            <h4><i class="fas fa-clipboard-check"></i> Review Decision</h4>
            <p>Please review all information carefully before making a decision</p>
        </div>

        <div class="action-buttons">
            <a href="{{ route('registrations.index') }}" class="btn-action btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>

            <button type="button" class="btn-action btn-reject" onclick="openRejectModal()">
                <i class="fas fa-times-circle"></i>
                Reject Application
            </button>

            @if($operator->membership_form_path)
                <form action="{{ route('registrations.approve', $operator->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-action btn-approve"
                            onclick="return confirm('Are you sure you want to approve this registration? The operator will be able to log in to the system.')">
                        <i class="fas fa-check-circle"></i>
                        Approve Application
                    </button>
                </form>
            @else
                <button type="button" class="btn-action btn-approve" style="opacity: 0.5; cursor: not-allowed;"
                        onclick="alert('Please upload the operator\'s membership form before approving the application.')"
                        title="Membership form is required">
                    <i class="fas fa-exclamation-circle"></i>
                    Approve Application (Form Required)
                </button>
            @endif
        </div>

        @if(!$operator->membership_form_path)
            <div style="text-align: center; margin-top: 15px; padding: 12px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px;">
                <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                <strong style="color: #856404;">Important:</strong>
                <span style="color: #856404;">Please upload the operator's membership form before approving this application.</span>
            </div>
        @endif
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal-overlay" id="rejectModal" onclick="closeRejectModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-times-circle"></i>
                Reject Application
            </h3>
            <button class="modal-close" onclick="closeRejectModal()">&times;</button>
        </div>

        <form action="{{ route('registrations.reject', $operator->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="rejection_reason">
                        Reason for Rejection <span style="color: #dc3545;">*</span>
                    </label>
                    <textarea
                        id="rejection_reason"
                        name="rejection_reason"
                        class="form-control"
                        rows="5"
                        placeholder="Please provide a detailed reason for rejecting this application..."
                        required></textarea>
                    <small style="color: #6c757d;">This reason will be sent to the applicant.</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">
                    Cancel
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times-circle"></i>
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openRejectModal() {
        document.getElementById('rejectModal').classList.add('active');
    }

    function closeRejectModal(event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById('rejectModal').classList.remove('active');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRejectModal();
        }
    });
</script>
@endpush
