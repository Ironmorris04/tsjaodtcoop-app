@extends('layouts.app')

@section('title', 'General Information')

@section('page-title', 'General Information')

@section('content')
<div class="general-info-container">
    <form action="{{ route('admin.general-info.store') }}" method="POST" class="general-info-form">
        @csrf

        <!-- Cooperative Information -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Cooperative Information
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid two-columns">
                    <div class="form-group">
                        <label for="registration_no">Registration No.</label>
                        <input type="text"
                               id="registration_no"
                               name="registration_no"
                               class="form-control"
                               value="{{ old('registration_no', $generalInfo->registration_no) }}"
                               placeholder="Enter registration number">
                        @error('registration_no')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="cooperative_name">Name of Cooperative</label>
                        <input type="text"
                               id="cooperative_name"
                               name="cooperative_name"
                               class="form-control"
                               value="{{ old('cooperative_name', $generalInfo->cooperative_name) }}"
                               placeholder="Enter cooperative name">
                        @error('cooperative_name')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Registered Address of Cooperative -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Registered Address of Cooperative
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid two-columns">
                    <div class="form-group">
                        <label for="reg_region">Region</label>
                        <input type="text"
                               id="reg_region"
                               name="reg_region"
                               class="form-control"
                               value="{{ old('reg_region', $generalInfo->reg_region) }}"
                               placeholder="Enter region">
                        @error('reg_region')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reg_province">Province</label>
                        <input type="text"
                               id="reg_province"
                               name="reg_province"
                               class="form-control"
                               value="{{ old('reg_province', $generalInfo->reg_province) }}"
                               placeholder="Enter province">
                        @error('reg_province')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reg_municipality_city">Municipality/City</label>
                        <input type="text"
                               id="reg_municipality_city"
                               name="reg_municipality_city"
                               class="form-control"
                               value="{{ old('reg_municipality_city', $generalInfo->reg_municipality_city) }}"
                               placeholder="Enter municipality or city">
                        @error('reg_municipality_city')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reg_barangay">Barangay</label>
                        <input type="text"
                               id="reg_barangay"
                               name="reg_barangay"
                               class="form-control"
                               value="{{ old('reg_barangay', $generalInfo->reg_barangay) }}"
                               placeholder="Enter barangay">
                        @error('reg_barangay')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reg_street">Street</label>
                        <input type="text"
                               id="reg_street"
                               name="reg_street"
                               class="form-control"
                               value="{{ old('reg_street', $generalInfo->reg_street) }}"
                               placeholder="Enter street">
                        @error('reg_street')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Present Address of Cooperative -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-home"></i>
                    Present Address of Cooperative
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid two-columns">
                    <div class="form-group">
                        <label for="present_region">Region</label>
                        <input type="text"
                               id="present_region"
                               name="present_region"
                               class="form-control"
                               value="{{ old('present_region', $generalInfo->present_region) }}"
                               placeholder="Enter region">
                        @error('present_region')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="present_province">Province</label>
                        <input type="text"
                               id="present_province"
                               name="present_province"
                               class="form-control"
                               value="{{ old('present_province', $generalInfo->present_province) }}"
                               placeholder="Enter province">
                        @error('present_province')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="present_municipality_city">Municipality/City</label>
                        <input type="text"
                               id="present_municipality_city"
                               name="present_municipality_city"
                               class="form-control"
                               value="{{ old('present_municipality_city', $generalInfo->present_municipality_city) }}"
                               placeholder="Enter municipality or city">
                        @error('present_municipality_city')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="present_barangay">Barangay</label>
                        <input type="text"
                               id="present_barangay"
                               name="present_barangay"
                               class="form-control"
                               value="{{ old('present_barangay', $generalInfo->present_barangay) }}"
                               placeholder="Enter barangay">
                        @error('present_barangay')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="present_street">Street</label>
                        <input type="text"
                               id="present_street"
                               name="present_street"
                               class="form-control"
                               value="{{ old('present_street', $generalInfo->present_street) }}"
                               placeholder="Enter street">
                        @error('present_street')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Registered -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-calendar-alt"></i>
                    Date Registered
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid two-columns">
                    <div class="form-group">
                        <label for="date_registration_prior_ra9520">Date of Registration Prior to RA 9520</label>
                        <input type="date"
                               id="date_registration_prior_ra9520"
                               name="date_registration_prior_ra9520"
                               class="form-control"
                               value="{{ old('date_registration_prior_ra9520', $generalInfo->date_registration_prior_ra9520?->format('Y-m-d')) }}">
                        @error('date_registration_prior_ra9520')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_registration_under_ra9520">Date of Registration Under RA 9520</label>
                        <input type="date"
                               id="date_registration_under_ra9520"
                               name="date_registration_under_ra9520"
                               class="form-control"
                               value="{{ old('date_registration_under_ra9520', $generalInfo->date_registration_under_ra9520?->format('Y-m-d')) }}">
                        @error('date_registration_under_ra9520')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Permit -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-file-contract"></i>
                    Business Permit
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid three-columns">
                    <div class="form-group">
                        <label for="business_permit_no">Business Permit No.</label>
                        <input type="text"
                               id="business_permit_no"
                               name="business_permit_no"
                               class="form-control"
                               value="{{ old('business_permit_no', $generalInfo->business_permit_no) }}"
                               placeholder="Enter permit number">
                        @error('business_permit_no')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="business_permit_date_issued">Date Issued</label>
                        <input type="date"
                               id="business_permit_date_issued"
                               name="business_permit_date_issued"
                               class="form-control"
                               value="{{ old('business_permit_date_issued', $generalInfo->business_permit_date_issued?->format('Y-m-d')) }}">
                        @error('business_permit_date_issued')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="business_permit_amount_paid">Amount Paid</label>
                        <input type="number"
                               id="business_permit_amount_paid"
                               name="business_permit_amount_paid"
                               class="form-control"
                               step="0.01"
                               min="0"
                               value="{{ old('business_permit_amount_paid', $generalInfo->business_permit_amount_paid) }}"
                               placeholder="0.00">
                        @error('business_permit_amount_paid')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Identification Number -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-receipt"></i>
                    Tax Identification Number
                </h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="tax_identification_number">TIN</label>
                    <input type="text"
                           id="tax_identification_number"
                           name="tax_identification_number"
                           class="form-control"
                           value="{{ old('tax_identification_number', $generalInfo->tax_identification_number) }}"
                           placeholder="Enter tax identification number">
                    @error('tax_identification_number')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Category and Type -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-sitemap"></i>
                    Classification
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid two-columns">
                    <div class="form-group">
                        <label for="category_of_cooperative">Category of Cooperative</label>
                        <input type="text"
                               id="category_of_cooperative"
                               name="category_of_cooperative"
                               class="form-control"
                               value="{{ old('category_of_cooperative', $generalInfo->category_of_cooperative) }}"
                               placeholder="e.g., Primary, Secondary, Tertiary">
                        @error('category_of_cooperative')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type_of_cooperative">Type of Cooperative</label>
                        <input type="text"
                               id="type_of_cooperative"
                               name="type_of_cooperative"
                               class="form-control"
                               value="{{ old('type_of_cooperative', $generalInfo->type_of_cooperative) }}"
                               placeholder="e.g., Transport, Multi-purpose">
                        @error('type_of_cooperative')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Size and Common Bond -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Additional Information
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid two-columns">
                    <div class="form-group">
                        <label for="asset_size">Asset Size of Cooperative</label>
                        <input type="text"
                               id="asset_size"
                               name="asset_size"
                               class="form-control"
                               value="{{ old('asset_size', $generalInfo->asset_size) }}"
                               placeholder="Enter asset size">
                        @error('asset_size')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="common_bond_membership">Common Bond of Membership</label>
                        <input type="text"
                               id="common_bond_membership"
                               name="common_bond_membership"
                               class="form-control"
                               value="{{ old('common_bond_membership', $generalInfo->common_bond_membership) }}"
                               placeholder="Enter common bond">
                        @error('common_bond_membership')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_of_general_assembly">Date of General Assembly</label>
                        <input type="date"
                               id="date_of_general_assembly"
                               name="date_of_general_assembly"
                               class="form-control"
                               value="{{ old('date_of_general_assembly', $generalInfo->date_of_general_assembly?->format('Y-m-d')) }}">
                        @error('date_of_general_assembly')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="area_of_operation">Area of Operation</label>
                        <textarea id="area_of_operation"
                                  name="area_of_operation"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Enter area of operation">{{ old('area_of_operation', $generalInfo->area_of_operation) }}</textarea>
                        @error('area_of_operation')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
            <a href="{{ route('admin.general-info.pdf') }}" class="btn btn-secondary">
                <i class="fas fa-file-pdf"></i>
                Download as PDF
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Save General Information
            </button>
        </div>
    </form>
</div>

<style>
    .general-info-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert i {
        font-size: 18px;
    }

    .general-info-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .info-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
    }

    .info-card .card-title {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-card .card-body {
        padding: 30px;
    }

    .form-grid {
        display: grid;
        gap: 20px;
    }

    .form-grid.two-columns {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-grid.three-columns {
        grid-template-columns: repeat(3, 1fr);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        font-size: 14px;
        color: #495057;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control::placeholder {
        color: #adb5bd;
    }

    textarea.form-control {
        resize: vertical;
        font-family: inherit;
    }

    .error-text {
        color: #e74a3b;
        font-size: 13px;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding: 20px 0;
    }

    .btn {
        padding: 12px 30px;
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

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(108, 117, 125, 0.3);
    }

    .btn i {
        font-size: 16px;
    }

    @media (max-width: 1024px) {
        .form-grid.two-columns,
        .form-grid.three-columns {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .general-info-container {
            padding: 15px;
        }

        .info-card .card-header {
            padding: 15px 20px;
        }

        .info-card .card-title {
            font-size: 16px;
        }

        .info-card .card-body {
            padding: 20px;
        }

        .form-grid {
            gap: 15px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection
