<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operator Registration - Transport Coop System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    overflow-x: hidden;
    max-width: 100%;
}

.register-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    min-height: 100vh;
    padding: 2rem 0;
    overflow-x: hidden;
}

.register-page::before {
    content: '';
    position: absolute;
    width: 500px;
    height: 500px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    top: -200px;
    right: -200px;
}

.register-page::after {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    bottom: -150px;
    left: -150px;
}

        .register-container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .form-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-upload-label {
            display: block;
            padding: 0.6rem 1rem;
            background: #f8f9fa;
            border: 2px dashed #ced4da;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
        }

        .file-upload-label:hover {
            background: #e9ecef;
            border-color: #667eea;
        }

        .file-upload-label i {
            margin-right: 0.5rem;
        }

        .file-name {
            font-size: 0.9rem;
            color: #28a745;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .dependent-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
            align-items: flex-start;
        }

        .dependent-row .form-group {
            margin-bottom: 0;
            flex: 1;
        }

        .btn-remove-dependent {
            margin-top: 0.5rem;
        }

        .terms-checkbox {
            text-align: center;
            margin: 2rem 0;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .profile-preview {
            width: 150px;
            height: 150px;
            border: 3px dashed #ced4da;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            overflow: hidden;
            background: #f8f9fa;
        }

        .profile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-preview i {
            font-size: 3rem;
            color: #ced4da;
        }

        .header-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-card h2 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header-card p {
            color: #6c757d;
            margin: 0;
        }

        @media (max-width: 768px) {
            .dependent-row {
                flex-direction: column;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body class="register-page">
    <div class="register-container">
        <!-- Header -->
        <div class="header-card">
            <h2><i class="fas fa-user-plus"></i> Operator Registration</h2>
            <p>Please fill out all required information to join our cooperative</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
            @csrf

            <!-- Personal Information Section -->
            <div class="form-section">
                <div class="section-header">
                    <h4><i class="fas fa-user"></i> Personal Information</h4>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                   id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                            @error('middle_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}" required>
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
                            <label for="birthplace">Birthplace <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('birthplace') is-invalid @enderror" 
                                   id="birthplace" name="birthplace" value="{{ old('birthplace') }}" required>
                            @error('birthplace')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                   id="religion" name="religion" value="{{ old('religion') }}">
                            @error('religion')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="citizenship">Citizenship <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('citizenship') is-invalid @enderror" 
                                   id="citizenship" name="citizenship" value="{{ old('citizenship', 'Filipino') }}" required>
                            @error('citizenship')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="occupation">Occupation</label>
                            <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                   id="occupation" name="occupation" value="{{ old('occupation') }}">
                            @error('occupation')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                class="form-control" 
                                id="contact_number" 
                                name="contact_number" 
                                value="{{ old('contact_number') }}" 
                                placeholder="0912-3456-789" 
                                data-type="numeric" 
                                required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Complete Address <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="alert alert-info" style="margin-top: 1rem;">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> You will set up your password after your registration is approved by the admin.
                </div>
            </div>

            <!-- Demographics Section -->
            <div class="form-section">
                <div class="section-header">
                    <h4><i class="fas fa-users"></i> Demographics</h4>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sex">Sex <span class="text-danger">*</span></label>
                            <select class="form-control @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                                <option value="">Select Sex</option>
                                <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="civil_status">Civil Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status" required>
                                <option value="">Select Status</option>
                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="separated" {{ old('civil_status') == 'separated' ? 'selected' : '' }}>Separated</option>
                            </select>
                            @error('civil_status')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="indigenous_people">Indigenous People</label>
                            <select class="form-control @error('indigenous_people') is-invalid @enderror" id="indigenous_people" name="indigenous_people">
                                <option value="no" {{ old('indigenous_people', 'no') == 'no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ old('indigenous_people') == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('indigenous_people')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pwd">Person with Disability (PWD)</label>
                            <select class="form-control @error('pwd') is-invalid @enderror" id="pwd" name="pwd">
                                <option value="no" {{ old('pwd', 'no') == 'no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ old('pwd') == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('pwd')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="senior_citizen">Senior Citizen</label>
                            <select class="form-control @error('senior_citizen') is-invalid @enderror" id="senior_citizen" name="senior_citizen">
                                <option value="no" {{ old('senior_citizen', 'no') == 'no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ old('senior_citizen') == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('senior_citizen')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fourps_beneficiary">4Ps Beneficiary</label>
                            <select class="form-control @error('fourps_beneficiary') is-invalid @enderror" id="fourps_beneficiary" name="fourps_beneficiary">
                                <option value="no" {{ old('fourps_beneficiary', 'no') == 'no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ old('fourps_beneficiary') == 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('fourps_beneficiary')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identification Section -->
            <div class="form-section">
                <div class="section-header">
                    <h4><i class="fas fa-id-card"></i> Identification</h4>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Profile Photo</label>
                            <div class="profile-preview" id="profilePreview">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="file-upload-wrapper">
                                <input type="file" id="profile_photo" name="profile_photo" accept=".png,.jpg,.jpeg" onchange="previewProfile(event)">
                                <label for="profile_photo" class="file-upload-label">
                                    <i class="fas fa-camera"></i> Upload Profile Photo
                                </label>
                            </div>
                            <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG | Max 2MB</small>
                            @error('profile_photo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_type">Type of ID <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_type') is-invalid @enderror" id="id_type" name="id_type" required>
                                <option value="">Select ID Type</option>
                                <option value="drivers_license" {{ old('id_type') == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                <option value="sss" {{ old('id_type') == 'sss' ? 'selected' : '' }}>SSS ID</option>
                                <option value="philhealth" {{ old('id_type') == 'philhealth' ? 'selected' : '' }}>PhilHealth ID</option>
                                <option value="voters" {{ old('id_type') == 'voters' ? 'selected' : '' }}>Voter's ID</option>
                                <option value="postal" {{ old('id_type') == 'postal' ? 'selected' : '' }}>Postal ID</option>
                                <option value="prc" {{ old('id_type') == 'prc' ? 'selected' : '' }}>PRC ID</option>
                                <option value="umid" {{ old('id_type') == 'umid' ? 'selected' : '' }}>UMID</option>
                                <option value="tinid" {{ old('id_type') == 'tinid' ? 'selected' : '' }}>TIN ID</option>
                                <option value="national_id" {{ old('id_type') == 'national_id' ? 'selected' : '' }}>National ID</option>
                            </select>
                            @error('id_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_number">ID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                   id="id_number" name="id_number" value="{{ old('id_number') }}" required>
                            @error('id_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Upload Valid ID <span class="text-danger">*</span></label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="valid_id" name="valid_id" accept=".png,.jpg,.jpeg,.pdf" required onchange="showFileName('valid_id', 'validIdName')">
                                <label for="valid_id" class="file-upload-label">
                                    <i class="fas fa-upload"></i> Choose File
                                </label>
                            </div>
                            <div class="file-name" id="validIdName"></div>
                            <small class="form-text text-muted">Accepted formats: PNG, JPG, JPEG, PDF | Max 5MB</small>
                            @error('valid_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Dependents Section -->
            <div class="form-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-people-roof"></i> List of Dependents</h4>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addDependent()">
                        <i class="fas fa-plus"></i> Add Dependent
                    </button>
                </div>

                <div id="dependentsContainer">
                    <div class="dependent-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="dependent_name[]" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" class="form-control" name="dependent_age[]" placeholder="Age" min="0">
                        </div>
                        <div class="form-group">
                            <label>Relation</label>
                            <input type="text" class="form-control" name="dependent_relation[]" placeholder="e.g., Son, Daughter">
                        </div>
                        <div>
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-remove-dependent" onclick="removeDependent(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <small class="text-muted">Add family members who depend on you financially</small>
            </div>

            <!-- Terms and Actions -->
            <div class="form-section">
                <div class="terms-checkbox">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a>
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('landing') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Close
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check"></i> Submit Registration
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <script>

        function validatePhoneNumber(input) {
            input.value = input.value.replace(/\D/g, '');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const phoneSelectors = [
                'input[type="tel"]',
                'input[name="phone"]',
                'input[name="contact_number"]',
                'input[name="emergency_contact"]',
                'input[id*="phone"]',
                'input[id*="contact"]'
            ];

            const phoneInputs = document.querySelectorAll(phoneSelectors.join(', '));

            phoneInputs.forEach(input => {
                const isNumericField = input.dataset.type === 'numeric' || input.name.toLowerCase().includes('phone');

                if (isNumericField) {
                    input.setAttribute('pattern', '\\d{11}');
                    input.setAttribute('maxlength', '11');
                    input.setAttribute('minlength', '11');
                    input.setAttribute('inputmode', 'numeric');

                    input.addEventListener('input', function() {
                        validatePhoneNumber(this);
                    });

                    input.addEventListener('paste', function() {
                        setTimeout(() => validatePhoneNumber(this), 10);
                    });
                }
            });
        });

        // Allowed image extensions
        const allowedImageExtensions = ['png', 'jpg', 'jpeg'];
        const allowedIdExtensions = ['png', 'jpg', 'jpeg', 'pdf'];

        // Validate file extension
        function validateFileExtension(file, allowedExtensions) {
            const fileName = file.name.toLowerCase();
            const extension = fileName.split('.').pop();
            return allowedExtensions.includes(extension);
        }

        // Preview profile photo
        function previewProfile(event) {
            const file = event.target.files[0];
            if (file) {
                if (!validateFileExtension(file, allowedImageExtensions)) {
                    alert('Invalid file type. Please upload a PNG, JPG, or JPEG image.');
                    event.target.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').innerHTML = '<img src="' + e.target.result + '" alt="Profile">';
                }
                reader.readAsDataURL(file);
            }
        }

        // Show uploaded file name
        function showFileName(inputId, displayId) {
            const input = document.getElementById(inputId);
            const display = document.getElementById(displayId);
            if (input.files.length > 0) {
                const file = input.files[0];
                // Validate file type for valid_id
                if (inputId === 'valid_id' && !validateFileExtension(file, allowedIdExtensions)) {
                    alert('Invalid file type. Please upload a PNG, JPG, JPEG, or PDF file.');
                    input.value = '';
                    display.textContent = '';
                    return;
                }
                display.textContent = '✓ ' + file.name;
            }
        }

        // Add dependent row
        function addDependent() {
            const container = document.getElementById('dependentsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'dependent-row';
            newRow.innerHTML = `
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="dependent_name[]" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" class="form-control" name="dependent_age[]" placeholder="Age" min="0">
                </div>
                <div class="form-group">
                    <label>Relation</label>
                    <input type="text" class="form-control" name="dependent_relation[]" placeholder="e.g., Son, Daughter">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-remove-dependent" onclick="removeDependent(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            updateRemoveButtons();
        }

        // Remove dependent row
        function removeDependent(button) {
            button.closest('.dependent-row').remove();
            updateRemoveButtons();
        }

        // Update visibility of remove buttons
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.dependent-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.btn-remove-dependent');
                if (rows.length > 1) {
                    removeBtn.style.display = 'block';
                } else {
                    removeBtn.style.display = 'none';
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
    </script>
</body>
</html>