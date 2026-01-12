@extends('layouts.app')

@section('title', 'Add Driver')
@section('page-title', 'Add New Driver')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('drivers.index') }}">Drivers</a></li>
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
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>Add New Driver</h2>
                <p>Fill in the driver's information below</p>
            </div>

            <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-body">
                    <!-- Personal Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-user"></i>
                            Personal Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name') }}"
                                           placeholder="Enter first name" required>
                                    @error('first_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name') }}"
                                           placeholder="Enter last name" required>
                                    @error('last_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birthdate">Birthdate <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                           id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                                    @error('birthdate')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sex">Sex <span class="text-danger">*</span></label>
                                    <select class="form-control @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                                        <option value="">Select Sex</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('sex')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}"
                                   placeholder="+63 912 345 6789" required>
                            @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3"
                                      placeholder="Enter complete address" required>{{ old('address') }}</textarea>
                            @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">Driver Photo</label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                           id="photo" name="photo" accept=".png,.jpg,.jpeg" onchange="validateImageFile(this)">
                                    <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                                    @error('photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="biodata_photo">Biodata Photo</label>
                                    <input type="file" class="form-control @error('biodata_photo') is-invalid @enderror"
                                           id="biodata_photo" name="biodata_photo" accept=".png,.jpg,.jpeg" onchange="validateImageFile(this)">
                                    <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                                    @error('biodata_photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- License Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-id-card"></i>
                            License Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="license_no">License Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('license_no') is-invalid @enderror"
                                           id="license_no" name="license_no" value="{{ old('license_no') }}"
                                           placeholder="Enter license number" required>
                                    @error('license_no')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="license_expiry">License Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('license_expiry') is-invalid @enderror"
                                           id="license_expiry" name="license_expiry" value="{{ old('license_expiry') }}" required>
                                    @error('license_expiry')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="license_restrictions">Restrictions</label>
                                    <input type="text" class="form-control @error('license_restrictions') is-invalid @enderror"
                                           id="license_restrictions" name="license_restrictions" value="{{ old('license_restrictions') }}"
                                           placeholder="e.g., 1, 2, 3">
                                    @error('license_restrictions')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dl_codes">DL Codes</label>
                                    <input type="text" class="form-control @error('dl_codes') is-invalid @enderror"
                                           id="dl_codes" name="dl_codes" value="{{ old('dl_codes') }}"
                                           placeholder="e.g., A, B, C">
                                    @error('dl_codes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="license_photo">License Photo</label>
                            <input type="file" class="form-control @error('license_photo') is-invalid @enderror"
                                   id="license_photo" name="license_photo" accept=".png,.jpg,.jpeg" onchange="validateImageFile(this)">
                            <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                            @error('license_photo')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('drivers.index') }}" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="fas fa-save"></i> Add Driver
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
