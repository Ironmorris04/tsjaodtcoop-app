@extends('layouts.app')

@section('title', 'Review Driver Application')

@section('page-title', 'Review Driver - ' . $driver->full_name)

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
        border-bottom: 3px solid #28a745;
        padding-bottom: 0.8rem;
        margin-bottom: 1.5rem;
    }

    .section-header h4 {
        color: #28a745;
        font-weight: 600;
        margin: 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        border-left: 3px solid #28a745;
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
    }

    .info-value.empty {
        color: #bdc3c7;
        font-style: italic;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .btn-approve {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        color: white;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    /* Enhanced Rejection Textarea Styling */
    .rejection-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Source Sans Pro', sans-serif;
        line-height: 1.6;
        resize: vertical;
        min-height: 120px;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .rejection-textarea:focus {
        outline: none;
        border: 2px solid transparent;
        background-image: linear-gradient(white, white), linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-origin: border-box;
        background-clip: padding-box, border-box;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .rejection-textarea::placeholder {
        color: #999;
        font-style: italic;
        opacity: 0.8;
    }

    .rejection-textarea:hover:not(:focus) {
        border-color: #c0c0c0;
    }

    /* Modal Enhancements */
    .modal-header {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
    }

    .modal-header .modal-title {
        font-weight: 600;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 15px 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .text-danger {
        color: #dc3545;
    }

    /* Image Display Styles */
    .image-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .image-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .image-card img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        cursor: pointer;
        transition: opacity 0.3s ease;
    }

    .image-card img:hover {
        opacity: 0.9;
    }

    .image-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        margin-bottom: 10px;
        text-align: center;
    }

    .no-image-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #6c757d;
    }

    .no-image-placeholder i {
        font-size: 3rem;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .driver-photo-large {
        height: 300px;
        object-fit: cover;
    }

    .document-photo {
        height: 250px;
        object-fit: contain;
    }

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        overflow: auto;
    }

    .image-modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .image-modal-close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .image-modal-close:hover {
        color: #bbb;
    }

    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="review-container">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Driver Information -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-id-card"></i> Driver Information</h4>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- Driver Photo -->
                <div class="image-card">
                    <div class="image-label">Driver Photo</div>
                    @if($driver->photo_url)
                        <img src="{{ $driver->photo_url }}"
                            alt="Driver Photo"
                            class="driver-photo-large"
                            onclick="openImageModal(this.src)">
                    @else
                        <div class="no-image-placeholder">
                            <i class="fas fa-user"></i>
                            <span>No Photo Available</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

            <div class="col-md-8">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $driver->full_name }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Age</div>
                        <div class="info-value">
                            @if($driver->birthdate)
                                {{ \Carbon\Carbon::parse($driver->birthdate)->age }} years old
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Sex</div>
                        <div class="info-value">{{ $driver->sex ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value">
                            {{ $driver->birthdate ? \Carbon\Carbon::parse($driver->birthdate)->format('M d, Y') : 'N/A' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value">{{ $driver->phone }}</div>
                    </div>

                    <div class="info-item" style="grid-column: span 2;">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $driver->address ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biodata Photo -->
        @if($driver->biodata_photo_url)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="image-card">
                    <div class="image-label">Biodata Document</div>
                    <img src="{{ $driver->biodata_photo_url }}"
                        alt="Biodata"
                        class="document-photo"
                        onclick="openImageModal(this.src)">
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- License Information -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-credit-card"></i> License Information</h4>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- License Photo -->
                <div class="image-card">
                    <div class="image-label">Driver's License Photo</div>
                    @if($driver->license_photo_url)
                        <img src="{{ $driver->license_photo_url }}"
                            alt="License Photo"
                            class="document-photo"
                            onclick="openImageModal(this.src)">
                    @else
                        <div class="no-image-placeholder">
                            <i class="fas fa-id-card"></i>
                            <span>No License Photo</span>
                        </div>
                    @endif
                </div>

            </div>

            <div class="col-md-8">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">License Number</div>
                        <div class="info-value">{{ $driver->license_number ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Restrictions</div>
                        <div class="info-value">{{ $driver->license_restrictions ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">DL Codes</div>
                        <div class="info-value">{{ $driver->dl_codes ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Validity (License Expiry)</div>
                        <div class="info-value">
                            @if($driver->license_expiry)
                                {{ \Carbon\Carbon::parse($driver->license_expiry)->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Emergency Contact</div>
                        <div class="info-value">{{ $driver->emergency_contact ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operator Information -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-building"></i> Operator Information</h4>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Operator Name</div>
                <div class="info-value">{{ $driver->operator->contact_person }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $driver->operator->user->email }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $driver->operator->phone }}</div>
            </div>
        </div>
    </div>

    <!-- Application Details -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-info-circle"></i> Application Details</h4>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Application Date</div>
                <div class="info-value">{{ $driver->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="badge badge-warning">{{ ucfirst($driver->approval_status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('registrations.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>

        <button type="button" class="btn-reject" data-toggle="modal" data-target="#rejectModal">
            <i class="fas fa-times"></i> Reject
        </button>

        <form action="{{ route('registrations.drivers.approve', $driver->id) }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-approve">
                <i class="fas fa-check"></i> Approve Driver
            </button>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Driver Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('registrations.drivers.reject', $driver->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="rejection-textarea" id="rejection_reason" name="rejection_reason" rows="4"
                                  placeholder="Please provide a clear reason for rejecting this application..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
    <img class="image-modal-content" id="modalImage">
</div>

@push('scripts')
<script>
    function openImageModal(src) {
        document.getElementById('imageModal').style.display = 'block';
        document.getElementById('modalImage').src = src;
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endpush
@endsection
