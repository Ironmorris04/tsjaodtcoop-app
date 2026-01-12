@extends('layouts.app')

@section('title', 'Add Transport Unit')
@section('page-title', 'Add New Transport Unit')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('units.index') }}">Transport Units</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<style>
.form-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.form-header-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2.5rem;
    backdrop-filter: blur(10px);
}

.form-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.form-header p {
    opacity: 0.9;
    margin: 0;
}

.form-body {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    font-size: 1.25rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    display: block;
}

.form-group label .text-danger {
    color: #f44336;
}

.form-control {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.form-control.is-invalid {
    border-color: #f44336;
}

.invalid-feedback {
    color: #f44336;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.form-actions {
    padding: 1.5rem 2rem;
    background: #f8f9fa;
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.btn-custom {
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-secondary-custom {
    background: #e0e0e0;
    color: #666;
}

.btn-secondary-custom:hover {
    background: #d0d0d0;
    color: #333;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }

    .btn-custom {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-header">
                <div class="form-header-icon">
                    <i class="fas fa-bus"></i>
                </div>
                <h2>Add New Transport Unit</h2>
                <p>Fill in the vehicle's information below</p>
            </div>

            <form action="{{ route('units.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-body">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Basic Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="plate_no">Plate Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('plate_no') is-invalid @enderror"
                                           id="plate_no" name="plate_no" value="{{ old('plate_no') }}"
                                           placeholder="ABC 1234" required>
                                    @error('plate_no')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Vehicle Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="bus" {{ old('type') == 'bus' ? 'selected' : '' }}>Bus</option>
                                        <option value="jeepney" {{ old('type') == 'jeepney' ? 'selected' : '' }}>Jeepney</option>
                                        <option value="van" {{ old('type') == 'van' ? 'selected' : '' }}>Van</option>
                                        <option value="taxi" {{ old('type') == 'taxi' ? 'selected' : '' }}>Taxi</option>
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="unit_photo">Unit Photo</label>
                                    <input type="file" class="form-control @error('unit_photo') is-invalid @enderror"
                                           id="unit_photo" name="unit_photo" accept=".png,.jpg,.jpeg" onchange="validateImageFile(this)">
                                    <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                                    @error('unit_photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Details -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-car"></i>
                            Vehicle Details
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand">Brand <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                           id="brand" name="brand" value="{{ old('brand') }}"
                                           placeholder="e.g., Toyota, Nissan" required>
                                    @error('brand')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="model">Model <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror"
                                           id="model" name="model" value="{{ old('model') }}"
                                           placeholder="e.g., Hiace, Coaster" required>
                                    @error('model')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year">Year <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror"
                                           id="year" name="year" value="{{ old('year') }}"
                                           min="1900" max="{{ date('Y') + 1 }}"
                                           placeholder="{{ date('Y') }}" required>
                                    @error('year')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacity">Passenger Capacity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                           id="capacity" name="capacity" value="{{ old('capacity') }}"
                                           min="1" placeholder="e.g., 15" required>
                                    @error('capacity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LTO OR/CR Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-file-alt"></i>
                            LTO Documents (OR & CR)
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lto_or_number">LTO OR Number</label>
                                    <input type="text" class="form-control @error('lto_or_number') is-invalid @enderror"
                                           id="lto_or_number" name="lto_or_number" value="{{ old('lto_or_number') }}"
                                           placeholder="Enter LTO OR Number">
                                    @error('lto_or_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lto_cr_number">LTO CR Number</label>
                                    <input type="text" class="form-control @error('lto_cr_number') is-invalid @enderror"
                                           id="lto_cr_number" name="lto_cr_number" value="{{ old('lto_cr_number') }}"
                                           placeholder="Enter LTO CR Number">
                                    @error('lto_cr_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lto_cr_validity">LTO CR Validity</label>
                                    <input type="date" class="form-control @error('lto_cr_validity') is-invalid @enderror"
                                           id="lto_cr_validity" name="lto_cr_validity" value="{{ old('lto_cr_validity') }}">
                                    @error('lto_cr_validity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit OR/CR Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-bus"></i>
                            Unit Documents (OR & CR)
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_or_number">Unit OR Number</label>
                                    <input type="text" class="form-control @error('unit_or_number') is-invalid @enderror"
                                           id="unit_or_number" name="unit_or_number" value="{{ old('unit_or_number') }}"
                                           placeholder="Enter Unit OR Number">
                                    @error('unit_or_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_cr_number">Unit CR Number</label>
                                    <input type="text" class="form-control @error('unit_cr_number') is-invalid @enderror"
                                           id="unit_cr_number" name="unit_cr_number" value="{{ old('unit_cr_number') }}"
                                           placeholder="Enter Unit CR Number">
                                    @error('unit_cr_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('units.index') }}" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="fas fa-save"></i> Add Unit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Allowed image extensions
    const allowedImageExtensions = ['png', 'jpg', 'jpeg'];

    // Validate file extension
    function validateFileExtension(file, allowedExtensions) {
        const fileName = file.name.toLowerCase();
        const extension = fileName.split('.').pop();
        return allowedExtensions.includes(extension);
    }

    // Validate image file on change
    function validateImageFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file extension - only PNG, JPG, JPEG allowed
            if (!validateFileExtension(file, allowedImageExtensions)) {
                alert('Invalid file type. Please upload a PNG, JPG, or JPEG image only.');
                input.value = ''; // Clear the input
                return false;
            }
        }
        return true;
    }
</script>
@endpush
@endsection
