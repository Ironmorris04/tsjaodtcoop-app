@extends('layouts.app')

@section('title', 'Requirements Management')

@section('page-title', 'Requirements Management')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Home</a></li>
    <li>Requirements</li>
@endsection

@push('styles')
<style>
    .requirements-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .requirements-header h2 {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: 600;
    }

    .requirements-header p {
        margin: 0;
        opacity: 0.9;
    }

    .requirements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .requirement-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 3px solid transparent;
    }

    .requirement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-color: var(--card-color);
    }

    .requirement-card.cda {
        --card-color: #667eea;
    }

    .requirement-card.tax {
        --card-color: #1cc88a;
    }

    .requirement-card.bir {
        --card-color: #36b9cc;
    }

    .requirement-card.permit {
        --card-color: #f6c23e;
    }

    .requirement-card-header {
        padding: 25px;
        background: var(--card-color);
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .requirement-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .requirement-title {
        flex: 1;
    }

    .requirement-title h3 {
        margin: 0 0 5px 0;
        font-size: 18px;
        font-weight: 700;
    }

    .requirement-title p {
        margin: 0;
        font-size: 12px;
        opacity: 0.9;
    }

    .requirement-card-body {
        padding: 25px;
    }

    .requirement-status {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.uploaded {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.not-uploaded {
        background: #f8d7da;
        color: #721c24;
    }

    .requirement-info {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .requirement-info div {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .requirement-info i {
        width: 16px;
        text-align: center;
    }

    .requirement-actions {
        display: flex;
        gap: 10px;
    }

    .btn-view, .btn-upload {
        flex: 1;
        padding: 10px 15px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 13px;
    }

    .btn-view {
        background: #e9ecef;
        color: #495057;
    }

    .btn-view:hover {
        background: #dee2e6;
    }

    .btn-upload {
        background: var(--card-color);
        color: white;
    }

    .btn-upload:hover {
        opacity: 0.9;
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
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background: white;
        border-radius: 15px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
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

    .modal-header {
        background: var(--modal-color, linear-gradient(135deg, #667eea 0%, #764ba2 100%));
        color: white;
        padding: 25px 30px;
        border-radius: 15px 15px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    /* Modal color variations */
    .modal-container.modal-cda .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .modal-container.modal-tax .modal-header {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .modal-container.modal-bir .modal-header {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .modal-container.modal-permit .modal-header {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }

    .modal-container.modal-cda .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .modal-container.modal-tax .btn-submit {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .modal-container.modal-bir .btn-submit {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .modal-container.modal-permit .btn-submit {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }

    .modal-container.modal-cda .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .modal-container.modal-tax .form-control:focus {
        border-color: #1cc88a;
        box-shadow: 0 0 0 3px rgba(28, 200, 138, 0.1);
    }

    .modal-container.modal-bir .form-control:focus {
        border-color: #36b9cc;
        box-shadow: 0 0 0 3px rgba(54, 185, 204, 0.1);
    }

    .modal-container.modal-permit .form-control:focus {
        border-color: #f6c23e;
        box-shadow: 0 0 0 3px rgba(246, 194, 62, 0.1);
    }

    .modal-container.modal-cda .file-upload-area:hover,
    .modal-container.modal-cda .file-upload-area.dragover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .modal-container.modal-tax .file-upload-area:hover,
    .modal-container.modal-tax .file-upload-area.dragover {
        border-color: #1cc88a;
        background: rgba(28, 200, 138, 0.05);
    }

    .modal-container.modal-bir .file-upload-area:hover,
    .modal-container.modal-bir .file-upload-area.dragover {
        border-color: #36b9cc;
        background: rgba(54, 185, 204, 0.05);
    }

    .modal-container.modal-permit .file-upload-area:hover,
    .modal-container.modal-permit .file-upload-area.dragover {
        border-color: #f6c23e;
        background: rgba(246, 194, 62, 0.05);
    }

    .modal-container.modal-cda .file-upload-icon,
    .modal-container.modal-cda .file-preview-icon {
        color: #667eea;
    }

    .modal-container.modal-tax .file-upload-icon,
    .modal-container.modal-tax .file-preview-icon {
        color: #1cc88a;
    }

    .modal-container.modal-bir .file-upload-icon,
    .modal-container.modal-bir .file-preview-icon {
        color: #36b9cc;
    }

    .modal-container.modal-permit .file-upload-icon,
    .modal-container.modal-permit .file-preview-icon {
        color: #f6c23e;
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
    }

    .form-control:focus {
        outline: none;
    }

    .file-upload-area {
        border: 3px dashed #dee2e6;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .file-upload-text h4 {
        margin: 0 0 5px 0;
        color: #343a40;
        font-size: 16px;
    }

    .file-upload-text p {
        margin: 0;
        color: #6c757d;
        font-size: 13px;
    }

    .file-preview {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        display: none;
    }

    .file-preview.active {
        display: block;
    }

    .file-preview-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .file-preview-icon {
        font-size: 32px;
    }

    .file-preview-details h5 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #343a40;
    }

    .file-preview-details p {
        margin: 0;
        font-size: 12px;
        color: #6c757d;
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
    }

    .btn-submit {
        padding: 12px 30px;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    @media (max-width: 768px) {
        .requirements-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="requirements-header">
    <div>
        <h2><i class="fas fa-file-certificate"></i> Requirements Management</h2>
        <p>Manage cooperative certificates and compliance documents</p>
    </div>
</div>

<div class="requirements-grid">
    @foreach($requirements as $type => $data)
        @php
            $cardClass = match($type) {
                'cda_compliance' => 'cda',
                'tax_exemption' => 'tax',
                'bir_registration' => 'bir',
                'business_permit' => 'permit',
                default => 'cda'
            };
            $iconClass = match($type) {
                'cda_compliance' => 'fa-certificate',
                'tax_exemption' => 'fa-file-invoice-dollar',
                'bir_registration' => 'fa-landmark',
                'business_permit' => 'fa-building',
                default => 'fa-file'
            };
            $latest = $data['latest'];
        @endphp

        <div class="requirement-card {{ $cardClass }}" onclick="openUploadModal('{{ $type }}', '{{ $data['label'] }}')">
            <div class="requirement-card-header">
                <div class="requirement-icon">
                    <i class="fas {{ $iconClass }}"></i>
                </div>
                <div class="requirement-title">
                    <h3>{{ $data['label'] }}</h3>
                    <p>Click to upload or view</p>
                </div>
            </div>
            <div class="requirement-card-body">
                <div class="requirement-status">
                    @if($latest)
                        <span class="status-badge uploaded">
                            <i class="fas fa-check-circle"></i> Uploaded
                        </span>
                    @else
                        <span class="status-badge not-uploaded">
                            <i class="fas fa-exclamation-circle"></i> Not Uploaded
                        </span>
                    @endif
                </div>

                @if($latest)
                    <div class="requirement-info">
                        @if($latest->issue_date)
                            <div><i class="fas fa-calendar"></i> Issued: {{ $latest->issue_date->format('M d, Y') }}</div>
                        @endif
                        @if($latest->expiry_date)
                            <div><i class="fas fa-calendar-times"></i> Expires: {{ $latest->expiry_date->format('M d, Y') }}</div>
                        @endif
                        @if($latest->document_number)
                            <div><i class="fas fa-hashtag"></i> No: {{ $latest->document_number }}</div>
                        @endif
                        <div><i class="fas fa-user"></i> By: {{ $latest->uploader->name ?? 'Unknown' }}</div>
                    </div>
                    <div class="requirement-actions">
                        <button class="btn-view" onclick="event.stopPropagation(); viewDocument('{{ $type }}')">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn-upload" onclick="event.stopPropagation(); openUploadModal('{{ $type }}', '{{ $data['label'] }}')">
                            <i class="fas fa-upload"></i> Update
                        </button>
                    </div>
                @else
                    <div class="requirement-actions">
                        <button class="btn-upload" onclick="event.stopPropagation(); openUploadModal('{{ $type }}', '{{ $data['label'] }}')">
                            <i class="fas fa-upload"></i> Upload Document
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Upload Modal -->
<div class="modal-overlay" id="uploadModal">
    <div class="modal-container" id="modalContainer">
        <div class="modal-header">
            <h3 id="modalTitle"><i class="fas fa-upload"></i> Upload Document</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="requirementType" name="type">

                <!-- File Upload Area -->
                <div class="form-group">
                    <label>Document Image <span class="required">*</span></label>
                    <div class="file-upload-area" id="fileUploadArea" onclick="document.getElementById('fileInput').click()">
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="file-upload-text">
                            <h4>Click to upload or drag and drop</h4>
                            <p>PNG, JPG, or JPEG only (MAX. 5MB)</p>
                        </div>
                    </div>
                    <input type="file" id="fileInput" name="file" accept=".png,.jpg,.jpeg" style="display: none;" required>

                    <div class="file-preview" id="filePreview">
                        <div class="file-preview-info">
                            <div class="file-preview-icon">
                                <i class="fas fa-file-image"></i>
                            </div>
                            <div class="file-preview-details">
                                <h5 id="fileName">filename.jpg</h5>
                                <p id="fileSize">0 KB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Issue Date -->
                <div class="form-group">
                    <label for="issue_date">Issue Date</label>
                    <input type="date" id="issue_date" name="issue_date" class="form-control">
                </div>

                <!-- Expiry Date -->
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" id="expiry_date" name="expiry_date" class="form-control">
                </div>

                <!-- Document Number -->
                <div class="form-group">
                    <label for="document_number">Document Number</label>
                    <input type="text" id="document_number" name="document_number" class="form-control" placeholder="Enter document number">
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Additional notes or remarks"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-submit" id="submitBtn" onclick="submitUpload()">
                <i class="fas fa-save"></i> Upload Document
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedFile = null;

    function openUploadModal(type, label) {
        const modalContainer = document.getElementById('modalContainer');

        // Remove all modal color classes
        modalContainer.classList.remove('modal-cda', 'modal-tax', 'modal-bir', 'modal-permit');

        // Add appropriate color class based on type
        const modalClass = {
            'cda_compliance': 'modal-cda',
            'tax_exemption': 'modal-tax',
            'bir_registration': 'modal-bir',
            'business_permit': 'modal-permit'
        };

        if (modalClass[type]) {
            modalContainer.classList.add(modalClass[type]);
        }

        document.getElementById('uploadModal').classList.add('active');
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-upload"></i> Upload ' + label;
        document.getElementById('requirementType').value = type;
        document.getElementById('uploadForm').reset();
        document.getElementById('filePreview').classList.remove('active');
        selectedFile = null;
    }

    function closeModal() {
        document.getElementById('uploadModal').classList.remove('active');
    }

    // Allowed image extensions
    const allowedImageExtensions = ['png', 'jpg', 'jpeg'];

    // Validate file extension
    function validateFileExtension(file) {
        const fileName = file.name.toLowerCase();
        const extension = fileName.split('.').pop();
        return allowedImageExtensions.includes(extension);
    }

    // File input change handler
    document.getElementById('fileInput').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];

            // Validate file extension - only PNG, JPG, JPEG allowed
            if (!validateFileExtension(file)) {
                alert('Invalid file type. Please upload a PNG, JPG, or JPEG image only.');
                e.target.value = ''; // Clear the input
                document.getElementById('filePreview').classList.remove('active');
                selectedFile = null;
                return;
            }

            selectedFile = file;
            showFilePreview(selectedFile);
        }
    });

    // Drag and drop handlers
    const uploadArea = document.getElementById('fileUploadArea');

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        if (e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            // Validate file extension - only PNG, JPG, JPEG allowed
            if (validateFileExtension(file)) {
                document.getElementById('fileInput').files = e.dataTransfer.files;
                selectedFile = file;
                showFilePreview(file);
            } else {
                alert('Invalid file type. Please upload a PNG, JPG, or JPEG image only.');
            }
        }
    });

    function showFilePreview(file) {
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(2) + ' KB';
        document.getElementById('filePreview').classList.add('active');
    }

    async function submitUpload() {
        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);

        if (!selectedFile) {
            alert('Please select a file to upload');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

        try {
            const response = await fetch('{{ route("requirements.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                closeModal();
                window.location.reload();
            } else {
                alert('Error: ' + (result.message || 'Failed to upload document'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while uploading the document');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Upload Document';
        }
    }

    function viewDocument(type) {
        window.open('/requirements/' + type, '_blank');
    }

    // Close modal when clicking outside
    document.getElementById('uploadModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush
