@extends('layouts.app')

@section('title', 'Edit Operator')
@section('page-title', 'Edit Operator')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('operators.index') }}">Operators</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@push('styles')
<style>
    .edit-operator-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .edit-operator-header h2 {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: 600;
    }

    .edit-operator-header p {
        margin: 0;
        opacity: 0.9;
    }

    .form-section {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .section-header {
        border-bottom: 3px solid #667eea;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }

    .section-header h4 {
        color: #667eea;
        font-weight: 600;
        margin: 0;
        font-size: 18px;
    }

    .section-header i {
        margin-right: 8px;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        color: white;
    }

    .btn-unregister {
        background: linear-gradient(135deg, #ff5f6d 0%, #ff0000 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-unregister:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(255, 0, 0, 0.5);
        color: white;
    }

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-update, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: none;
    }

    .modal-header {
        border-radius: 10px 10px 0 0;
        padding: 20px 30px;
    }

    .modal-header .modal-title {
        font-weight: 600;
        font-size: 18px;
    }

    .modal-body {
        padding: 30px;
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e9ecef;
    }

    .modal-footer .btn {
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
    }

    .modal-footer .btn-danger {
        background: linear-gradient(135deg, #ff5f6d 0%, #ff0000 100%);
        border: none;
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.4);
    }

    .modal-footer .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(255, 0, 0, 0.5);
    }
    
</style>
@endpush

@section('content')
<!-- Header -->
<div class="edit-operator-header">
    <h2><i class="fas fa-edit"></i> Edit Operator</h2>
    <p>Update operator information and details</p>
</div>

<form action="{{ route('operators.update', $operator) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Business Information Section -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-building"></i> Business Information</h4>
        </div>

        <div class="row">

           <div class="col-md-6">
                <div class="form-group">
                    <label for="business_name">Operator Name <span class="text-danger">*</span></label>
                    <input type="text" 
                        class="form-control @error('business_name') is-invalid @enderror"
                        id="business_name" 
                        name="business_name"
                        value="{{ old('business_name', $operator->business_name) }}"
                        required
                        readonly>
                    @error('business_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact_person">Contact Person <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                           id="contact_person" name="contact_person" value="{{ old('contact_person', $operator->contact_person) }}" data-type="text" required>
                    @error('contact_person')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="business_permit_no">Business Permit No.</label>
                    <input type="text" class="form-control @error('business_permit_no') is-invalid @enderror"
                           id="business_permit_no" name="business_permit_no" value="{{ old('business_permit_no', $operator->business_permit_no) }}">
                    @error('business_permit_no')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status', $operator->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $operator->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Section -->
    <div class="form-section">
        <div class="section-header">
            <h4><i class="fas fa-address-book"></i> Contact Information</h4>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Phone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" value="{{ old('phone', $operator->phone) }}" required>
                    @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Business Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email', $operator->email) }}" required>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address <span class="text-danger">*</span></label>
            <textarea class="form-control @error('address') is-invalid @enderror"
                      id="address" name="address" rows="3" required>{{ old('address', $operator->address) }}</textarea>
            @error('address')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
        <a href="{{ route('operators.index') }}" class="btn-cancel">
            <i class="fas fa-times"></i> Cancel
        </a>

        <button type="submit" class="btn-update">
            <i class="fas fa-save"></i> Update Operator
        </button>

        {{-- Unregister Operator --}}
        @if(auth()->user()->isAdmin())
            <button type="button"
                    class="btn-unregister"
                    data-toggle="modal"
                    data-target="#unregisterOperatorModal">
                <i class="fas fa-user-slash"></i> Unregister Operator
            </button>
        @endif
    </div>
</form>

<!-- Unregister Operator Modal -->
<div class="modal fade" id="unregisterOperatorModal" tabindex="-1" data-backdrop="static" 
     data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Unregister Operator
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('operators.destroy', $operator) }}">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p class="text-danger font-weight-bold mb-2">
                        Deactivate <strong>{{ $operator->business_name }}</strong>?
                    </p>

                    <p class="text-muted mb-3">
                        This operator can be restored later if needed.
                    </p>

                    <div class="form-group">
                        <label>Enter password to unregister operator</label>

                        <div class="input-group">
                            <input type="password"
                                id="adminPasswordInput"
                                name="admin_password"
                                class="form-control @error('admin_password') is-invalid @enderror"
                                required>

                            <div class="input-group-append">
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="toggleAdminPassword()">
                                    <i class="fas fa-eye" id="adminPasswordIcon"></i>
                                </button>
                            </div>

                            @error('admin_password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn-unregister">
                        <i class="fas fa-trash"></i> Unregister Operator
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleAdminPassword() {
        const input = document.getElementById('adminPasswordInput');
        const icon = document.getElementById('adminPasswordIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Auto-open modal if there's an admin_password error
    @if($errors->has('admin_password'))
        $(document).ready(function() {
            $('#unregisterOperatorModal').modal('show');
        });
    @endif
</script>
@endpush