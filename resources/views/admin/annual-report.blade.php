@extends('layouts.app')

@section('title', 'Annual Report')

@section('page-title', 'Annual Report')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
    $isPresident = $user->isPresident();
    $isTreasurer = $user->isTreasurer();
@endphp
<div class="annual-report-container">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Annual Report - {{ $selectedYear }}</h1>
            <p class="page-subtitle">TSJAODTC Transport Cooperative - Comprehensive Annual Report</p>
            <div style="margin-top: 10px; display: flex; align-items: center; gap: 15px;">
                <div>
                    <label for="yearSelector" style="font-weight: bold; margin-right: 10px;">
                        <i class="fas fa-calendar-alt"></i> Select Year:
                    </label>
                    <select id="yearSelector" class="form-control" style="display: inline-block; width: auto; min-width: 150px;" onchange="changeYear(this.value)">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }}{{ $year == $currentYear ? ' (Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($selectedYear != $currentYear)
                    <span class="badge badge-warning" style="font-size: 13px; padding: 6px 12px;">
                        <i class="fas fa-eye"></i> Viewing Past Year
                    </span>
                @else
                    <span class="badge badge-success" style="font-size: 13px; padding: 6px 12px;">
                        <i class="fas fa-edit"></i> Current Year - Editable
                    </span>
                @endif
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-success" onclick="saveReport()" {{ $selectedYear != $currentYear ? 'disabled' : '' }}>
                <i class="fas fa-save"></i> Save Report
            </button>
            <button type="button" class="btn btn-primary" onclick="downloadPDF()">
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
    </div>

    @php
        $saveRoute = 'admin.annual-report.save';
        if ($isPresident) {
            $saveRoute = 'president.annual-report.save';
        } elseif ($isTreasurer) {
            $saveRoute = 'treasurer.annual-report.save';
        }
        $isViewingPastYear = $selectedYear != $currentYear;
        $readonlyAttr = $isViewingPastYear ? 'readonly' : '';
        $disabledAttr = $isViewingPastYear ? 'disabled' : '';
    @endphp
    <form id="annualReportForm" method="POST" action="{{ route($saveRoute) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="report_year" value="{{ $selectedYear }}">

        <!-- CLUSTER 1: BASIC/PRIMARY INFORMATION -->
        <div class="info-card" data-cluster="1">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Cluster 1: Basic/Primary Information
                    @if($isTreasurer || $isPresident)
                        <span class="badge badge-secondary ml-2">Read Only</span>
                    @endif
                </h2>
            </div>
            <div class="card-body">
                <div class="form-grid two-columns">
                    <div class="form-group full-width">
                        <label>Name of TC (IN FULL)</label>
                        <input type="text" class="form-control" name="tc_name" value="{{ old('tc_name', $savedData['tc_name'] ?? ($generalInfo->cooperative_name ?? 'Tacloban San Jose Airport Operators Drivers Transport Cooperative')) }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Business Address</label>
                        <textarea class="form-control" name="business_address" rows="2">{{ old('business_address', $savedData['business_address'] ?? ($generalInfo ? $generalInfo->present_house_lot_blk_no . ' ' . $generalInfo->present_street . ', ' . $generalInfo->present_barangay . ', ' . $generalInfo->present_municipality_city . ', ' . $generalInfo->present_province . ', ' . $generalInfo->present_region : '')) }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Official Email Address (Required)</label>
                        <input type="email" class="form-control" name="official_email" value="{{ old('official_email', $savedData['official_email'] ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Official Contact No.</label>
                        <input type="text" class="form-control" name="official_contact" value="{{ old('official_contact', $savedData['official_contact'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $savedData['contact_person'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>OTC ACCREDITATION NO. (RA9520)</label>
                        <input type="text" class="form-control" name="otc_accreditation" value="{{ old('otc_accreditation', $savedData['otc_accreditation'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Date Accredited</label>
                        <input type="date" class="form-control" name="otc_date_accredited" value="{{ old('otc_date_accredited', $savedData['otc_date_accredited'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>CDA REGISTRATION NO. (RA9520)</label>
                        <input type="text" class="form-control" name="cda_registration" value="{{ old('cda_registration', $savedData['cda_registration'] ?? ($generalInfo->registration_no ?? '')) }}">
                    </div>

                    <div class="form-group">
                        <label>DATE REGISTERED</label>
                        <input type="date" class="form-control" name="cda_date_registered" value="{{ old('cda_date_registered', $savedData['cda_date_registered'] ?? ($generalInfo && $generalInfo->date_registration_under_ra9520 ? $generalInfo->date_registration_under_ra9520->format('Y-m-d') : '')) }}">
                    </div>

                    <div class="form-group">
                        <label>COMMON BOND OF MEMBERSHIP</label>
                        <input type="text" class="form-control" name="common_bond" value="{{ old('common_bond', $savedData['common_bond'] ?? ($generalInfo->common_bond_membership ?? '')) }}">
                    </div>

                    <div class="form-group">
                        <label>MEMBERSHIP FEE PER BY LAWS</label>
                        <input type="text" class="form-control" name="membership_fee" value="{{ old('membership_fee', $savedData['membership_fee'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>SSS EMPLOYER REGISTRATION NUMBER</label>
                        <input type="text" class="form-control" name="sss_number" value="{{ old('sss_number', $savedData['sss_number'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>NO. OF SSS ENROLLED EMPLOYEES</label>
                        <input type="number" min="0" class="form-control" name="sss_employees" value="{{ old('sss_employees', $savedData['sss_employees'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>PAGIBIG EMPLOYER REGISTRATION NUMBER</label>
                        <input type="text" class="form-control" name="pagibig_number" value="{{ old('pagibig_number', $savedData['pagibig_number'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>NO. OF PAGIBIG ENROLLED EMPLOYEES</label>
                        <input type="number" min="0" class="form-control" name="pagibig_employees" value="{{ old('pagibig_employees', $savedData['pagibig_employees'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>PHILHEALTH EMPLOYER REGISTRATION NUMBER</label>
                        <input type="text" class="form-control" name="philhealth_number" value="{{ old('philhealth_number', $savedData['philhealth_number'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>NO. OF PHILHEALTH ENROLLED EMPLOYEES</label>
                        <input type="number" min="0" class="form-control" name="philhealth_employees" value="{{ old('philhealth_employees', $savedData['philhealth_employees'] ?? '') }}">
                    </div>

                    <div class="form-group full-width">
                        <label>BIR TIN NUMBER</label>
                        <input type="text" class="form-control" name="bir_tin" value="{{ old('bir_tin', $savedData['bir_tin'] ?? ($generalInfo->tax_identification_number ?? '')) }}">
                    </div>

                    <div class="form-group">
                        <label>BIR TAX EXEMPTION NUMBER</label>
                        <input type="text" class="form-control" name="bir_exemption_number" value="{{ old('bir_exemption_number', $savedData['bir_exemption_number'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>VALIDITY</label>
                        <input type="date" class="form-control" name="bir_exemption_validity" value="{{ old('bir_exemption_validity', $savedData['bir_exemption_validity'] ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- CLUSTER 2: MEMBERSHIP -->
        <div class="info-card" data-cluster="2">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-users"></i>
                    Cluster 2: Membership
                    @if($isTreasurer || $isPresident)
                        <span class="badge badge-secondary ml-2">Read Only</span>
                    @endif
                </h2>
            </div>
            <div class="card-body">
                <h6 class="section-subtitle">Number of Members</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="members_year_from" onchange="updateYearColumns('members')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="members_year_to" onchange="updateYearColumns('members')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="membersTable">
                        <thead id="membersTableHead">
                            <tr>
                                <th rowspan="2" class="align-middle">Type/Status</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th colspan="2" class="text-center year-col" data-year="{{ $year }}">{{ $year }}</th>
                                @endfor
                            </tr>
                            <tr>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th class="year-col" data-year="{{ $year }}">Male</th>
                                    <th class="year-col" data-year="{{ $year }}">Female</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Drivers</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="drivers_{{ $year }}_male" value="{{ old('drivers_' . $year . '_male', $savedData['drivers_' . $year . '_male'] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="drivers_{{ $year }}_female" value="{{ old('drivers_' . $year . '_female', $savedData['drivers_' . $year . '_female'] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Member-Operator</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="operator_{{ $year }}_male" value="{{ old('operator_' . $year . '_male', $savedData['operator_' . $year . '_male'] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="operator_{{ $year }}_female" value="{{ old('operator_' . $year . '_female', $savedData['operator_' . $year . '_female'] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Allied Workers</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="allied_{{ $year }}_male" value="{{ old('allied_' . $year . '_male', $savedData['allied_' . $year . '_male'] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="allied_{{ $year }}_female" value="{{ old('allied_' . $year . '_female', $savedData['allied_' . $year . '_female'] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Others</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="others_{{ $year }}_male" value="{{ old('others_' . $year . '_male', $savedData['others_' . $year . '_male'] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="others_{{ $year }}_female" value="{{ old('others_' . $year . '_female', $savedData['others_' . $year . '_female'] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr class="table-total">
                                <td><strong>TOTAL</strong></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="total_{{ $year }}_male" value="{{ old('total_' . $year . '_male', $savedData['total_' . $year . '_male'] ?? '') }}" readonly></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="total_{{ $year }}_female" value="{{ old('total_' . $year . '_female', $savedData['total_' . $year . '_female'] ?? '') }}" readonly></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Status of Employment</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle">Type of Employees</th>
                                <th colspan="2" class="text-center">Probationary</th>
                                <th colspan="2" class="text-center">Regular</th>
                            </tr>
                            <tr>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Drivers</td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_drivers_prob_male" value="{{ old('emp_drivers_prob_male', $savedData['emp_drivers_prob_male'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_drivers_prob_female" value="{{ old('emp_drivers_prob_female', $savedData['emp_drivers_prob_female'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_drivers_reg_male" value="{{ old('emp_drivers_reg_male', $savedData['emp_drivers_reg_male'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_drivers_reg_female" value="{{ old('emp_drivers_reg_female', $savedData['emp_drivers_reg_female'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>Management Staff</td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_management_prob_male" value="{{ old('emp_management_prob_male', $savedData['emp_management_prob_male'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_management_prob_female" value="{{ old('emp_management_prob_female', $savedData['emp_management_prob_female'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_management_reg_male" value="{{ old('emp_management_reg_male', $savedData['emp_management_reg_male'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_management_reg_female" value="{{ old('emp_management_reg_female', $savedData['emp_management_reg_female'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>Allied Workers</td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_allied_prob_male" value="{{ old('emp_allied_prob_male', $savedData['emp_allied_prob_male'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_allied_prob_female" value="{{ old('emp_allied_prob_female', $savedData['emp_allied_prob_female'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_allied_reg_male" value="{{ old('emp_allied_reg_male', $savedData['emp_allied_reg_male'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="emp_allied_reg_female" value="{{ old('emp_allied_reg_female', $savedData['emp_allied_reg_female'] ?? '') }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Special Status Categories</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="specialStatus_year_from" onchange="updateYearColumns('specialStatus')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="specialStatus_year_to" onchange="updateYearColumns('specialStatus')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered annual-table" id="specialStatusTable">
                        <thead>
                            <tr>
                                <th>Category</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th class="text-center year-col" data-year="{{ $year }}">{{ $year }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PWD (Person with Disability)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="pwd_{{ $year }}" value="{{ old('pwd_' . $year . '', $savedData['pwd_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Senior Citizen</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="senior_{{ $year }}" value="{{ old('senior_' . $year . '', $savedData['senior_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr class="table-total">
                                <td><strong>TOTAL</strong></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="special_total_{{ $year }}" value="{{ old('special_total_' . $year . '', $savedData['special_total_' . $year . ''] ?? '') }}" readonly></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CLUSTER 3: UNITS AND FRANCHISE -->
        <div class="info-card" data-cluster="3">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-bus"></i>
                    Cluster 3: Units and Franchise
                    @if($isTreasurer || $isPresident)
                        <span class="badge badge-secondary ml-2">Read Only</span>
                    @endif
                </h2>
            </div>
            <div class="card-body">
                <h6 class="section-subtitle">Units by Type and Ownership</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="units_year_from" onchange="updateYearColumns('units')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="units_year_to" onchange="updateYearColumns('units')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="unitsTable">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle">Type of Units</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th colspan="2" class="text-center year-col" data-year="{{ $year }}">{{ $year }}</th>
                                @endfor
                            </tr>
                            <tr>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th class="year-col" data-year="{{ $year }}">Cooperatively Owned Units</th>
                                    <th class="year-col" data-year="{{ $year }}">Individually Owned Units</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PUJ (Traditional)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="puj_trad_coop_{{ $year }}" value="{{ old('puj_trad_coop_' . $year . '', $savedData['puj_trad_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="puj_trad_member_{{ $year }}" value="{{ old('puj_trad_member_' . $year . '', $savedData['puj_trad_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>MPUV Class 1 (EURO)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="mpuv1_euro_coop_{{ $year }}" value="{{ old('mpuv1_euro_coop_' . $year . '', $savedData['mpuv1_euro_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="mpuv1_euro_member_{{ $year }}" value="{{ old('mpuv1_euro_member_' . $year . '', $savedData['mpuv1_euro_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>MPUV Class 1 (Electric)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="mpuv1_elec_coop_{{ $year }}" value="{{ old('mpuv1_elec_coop_' . $year . '', $savedData['mpuv1_elec_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="mpuv1_elec_member_{{ $year }}" value="{{ old('mpuv1_elec_member_' . $year . '', $savedData['mpuv1_elec_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>MPUV Class 2 (EURO)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="mpuv2_euro_coop_{{ $year }}" value="{{ old('mpuv2_euro_coop_' . $year . '', $savedData['mpuv2_euro_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="mpuv2_euro_member_{{ $year }}" value="{{ old('mpuv2_euro_member_' . $year . '', $savedData['mpuv2_euro_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>MPUV Class 2 (Electric)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="mpuv2_elec_coop_{{ $year }}" value="{{ old('mpuv2_elec_coop_' . $year . '', $savedData['mpuv2_elec_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="mpuv2_elec_member_{{ $year }}" value="{{ old('mpuv2_elec_member_' . $year . '', $savedData['mpuv2_elec_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>UV Express (Traditional)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="uv_trad_coop_{{ $year }}" value="{{ old('uv_trad_coop_' . $year . '', $savedData['uv_trad_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="uv_trad_member_{{ $year }}" value="{{ old('uv_trad_member_' . $year . '', $savedData['uv_trad_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>MPUV Class 3 (EURO)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="mpuv3_euro_coop_{{ $year }}" value="{{ old('mpuv3_euro_coop_' . $year . '', $savedData['mpuv3_euro_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="mpuv3_euro_member_{{ $year }}" value="{{ old('mpuv3_euro_member_' . $year . '', $savedData['mpuv3_euro_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>MPUV Class 3 (Electric)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="mpuv3_elec_coop_{{ $year }}" value="{{ old('mpuv3_elec_coop_' . $year . '', $savedData['mpuv3_elec_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="mpuv3_elec_member_{{ $year }}" value="{{ old('mpuv3_elec_member_' . $year . '', $savedData['mpuv3_elec_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>PUV Class 4 (Modernized)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="puv4_mod_coop_{{ $year }}" value="{{ old('puv4_mod_coop_' . $year . '', $savedData['puv4_mod_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="puv4_mod_member_{{ $year }}" value="{{ old('puv4_mod_member_' . $year . '', $savedData['puv4_mod_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>MPUV Class 4 (Electric)</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="mpuv4_elec_coop_{{ $year }}" value="{{ old('mpuv4_elec_coop_' . $year . '', $savedData['mpuv4_elec_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="mpuv4_elec_member_{{ $year }}" value="{{ old('mpuv4_elec_member_' . $year . '', $savedData['mpuv4_elec_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Tourist Service</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="tourist_coop_{{ $year }}" value="{{ old('tourist_coop_' . $year . '', $savedData['tourist_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" value="0"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Taxi</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="taxi_coop_{{ $year }}" value="{{ old('taxi_coop_' . $year . '', $savedData['taxi_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="taxi_member_{{ $year }}" value="{{ old('taxi_member_' . $year . '', $savedData['taxi_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Multicab/Filcab</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="multicab_coop_{{ $year }}" value="{{ old('multicab_coop_' . $year . '', $savedData['multicab_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="multicab_member_{{ $year }}" value="{{ old('multicab_member_' . $year . '', $savedData['multicab_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Mini Bus</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="minibus_coop_{{ $year }}" value="{{ old('minibus_coop_' . $year . '', $savedData['minibus_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="minibus_member_{{ $year }}" value="{{ old('minibus_member_' . $year . '', $savedData['minibus_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Bus</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="bus_coop_{{ $year }}" value="{{ old('bus_coop_' . $year . '', $savedData['bus_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="bus_member_{{ $year }}" value="{{ old('bus_member_' . $year . '', $savedData['bus_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Tricycle / MCH</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="tricycle_coop_{{ $year }}" value="{{ old('tricycle_coop_' . $year . '', $savedData['tricycle_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="tricycle_member_{{ $year }}" value="{{ old('tricycle_member_' . $year . '', $savedData['tricycle_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Truck</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="truck_coop_{{ $year }}" value="{{ old('truck_coop_' . $year . '', $savedData['truck_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="truck_member_{{ $year }}" value="{{ old('truck_member_' . $year . '', $savedData['truck_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Banca</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="banca_coop_{{ $year }}" value="{{ old('banca_coop_' . $year . '', $savedData['banca_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="banca_member_{{ $year }}" value="{{ old('banca_member_' . $year . '', $savedData['banca_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Shuttle Service</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="shuttle_coop_{{ $year }}" value="{{ old('shuttle_coop_' . $year . '', $savedData['shuttle_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="shuttle_member_{{ $year }}" value="{{ old('shuttle_member_' . $year . '', $savedData['shuttle_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" class="form-control-table" name="other_unit_type" placeholder="Others (specify)" value="{{ old('other_unit_type', $savedData['other_unit_type'] ?? '') }}">
                                </td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-coop-{{ $year }}" name="other_coop_{{ $year }}" value="{{ old('other_coop_' . $year . '', $savedData['other_coop_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table units-input units-member-{{ $year }}" name="other_member_{{ $year }}" value="{{ old('other_member_' . $year . '', $savedData['other_member_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr class="table-total">
                                <td><strong>TOTAL</strong></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col text-center" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="units_total_coop_{{ $year }}" value="{{ old('units_total_coop_' . $year . '', $savedData['units_total_coop_' . $year . ''] ?? '') }}" readonly></td>
                                    <td class="year-col text-center" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="units_total_member_{{ $year }}" value="{{ old('units_total_member_' . $year . '', $savedData['units_total_member_' . $year . ''] ?? '') }}" readonly></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Franchise</h6>
                <div class="table-responsive">
                    <table class="table table-bordered annual-table" id="franchiseTable">
                        <thead>
                            <tr>
                                <th width="25%">Route/s</th>
                                <th width="10%">No. of Units</th>
                                <th width="20%">CPC Case Number (Franchise or P.A.)</th>
                                <th width="20%">Type of Unit</th>
                                <th width="10%">Validity</th>
                                <th width="10%">Remarks</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $franchiseRoutes = $savedData['franchise_route'] ?? [''];
                                $franchiseUnits = $savedData['franchise_units'] ?? [''];
                                $franchiseCases = $savedData['franchise_case'] ?? [''];
                                $franchiseUnitTypes = $savedData['franchise_unit_type'] ?? [''];
                                $franchiseValidities = $savedData['franchise_validity'] ?? [''];
                                $franchiseRemarks = $savedData['franchise_remarks'] ?? [''];
                                $franchiseCount = max(count($franchiseRoutes), 1);
                            @endphp
                            @for($i = 0; $i < $franchiseCount; $i++)
                            <tr>
                                <td><input type="text" class="form-control-table" name="franchise_route[]" value="{{ old('franchise_route.' . $i, $franchiseRoutes[$i] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="franchise_units[]" onchange="calculateFranchiseTotalUnits()" value="{{ old('franchise_units.' . $i, $franchiseUnits[$i] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="franchise_case[]" value="{{ old('franchise_case.' . $i, $franchiseCases[$i] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="franchise_unit_type[]" placeholder="e.g., Euro, Electric, Solar" value="{{ old('franchise_unit_type.' . $i, $franchiseUnitTypes[$i] ?? '') }}"></td>
                                <td><input type="date" class="form-control-table" name="franchise_validity[]" value="{{ old('franchise_validity.' . $i, $franchiseValidities[$i] ?? '') }}"></td>
                                <td>
                                    <select class="form-control-table" name="franchise_remarks[]">
                                        <option value="">Select</option>
                                        <option value="Consolidated" {{ (old('franchise_remarks.' . $i, $franchiseRemarks[$i] ?? '') == 'Consolidated') ? 'selected' : '' }}>Consolidated</option>
                                        <option value="Individual" {{ (old('franchise_remarks.' . $i, $franchiseRemarks[$i] ?? '') == 'Individual') ? 'selected' : '' }}>Individual</option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                            </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr class="table-total">
                                <td><strong>TOTAL</strong></td>
                                <td><input type="number" min="0" class="form-control-table" name="franchise_total_units" value="{{ old('franchise_total_units', $savedData['franchise_total_units'] ?? '') }}" readonly></td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn-add" onclick="addFranchiseRow()">
                        <i class="fas fa-plus"></i> Add Franchise
                    </button>
                </div>
            </div>
        </div>

        <!-- CLUSTER 4: GOVERNANCE -->
        <div class="info-card" data-cluster="4">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-gavel"></i>
                    Cluster 4: Governance
                    @if($isTreasurer || $isPresident)
                        <span class="badge badge-secondary ml-2">Read Only</span>
                    @endif
                </h2>
            </div>
            <div class="card-body">
                <h6 class="section-subtitle">CGS Acquisition</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="cgs_year_from" onchange="updateYearColumns('cgs')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="cgs_year_to" onchange="updateYearColumns('cgs')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="cgsTable">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle">Year</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th colspan="3" class="text-center year-col" data-year="{{ $year }}">CGS Details</th>
                                @endfor
                            </tr>
                            <tr>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th class="year-col" data-year="{{ $year }}">CGS No.</th>
                                    <th class="year-col" data-year="{{ $year }}">Date Issued</th>
                                    <th class="year-col" data-year="{{ $year }}">Expiration Date</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>OTC</strong></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="text" class="form-control-table" name="cgs_no_{{ $year }}" value="{{ old('cgs_no_' . $year . '', $savedData['cgs_no_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="date" class="form-control-table" name="cgs_date_issued_{{ $year }}" value="{{ old('cgs_date_issued_' . $year . '', $savedData['cgs_date_issued_' . $year . ''] ?? '') }}"></td>
                                    <td class="year-col" data-year="{{ $year }}"><input type="date" class="form-control-table" name="cgs_expiration_{{ $year }}" value="{{ old('cgs_expiration_' . $year . '', $savedData['cgs_expiration_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Officers and Committees</h6>

                <!-- Officers and Committees List -->
                <div class="governance-list-container mb-4">
                    <!-- Executive Officers -->
                    <div class="governance-section mb-4">
                        <h6 class="governance-subtitle"><i class="fas fa-user-tie"></i> Executive Officers</h6>
                        <table class="table table-bordered governance-table">
                            <thead>
                                <tr>
                                    <th width="15%">Position</th>
                                    <th width="20%">Name</th>
                                    <th width="15%">Term</th>
                                    <th width="15%">Mobile Number</th>
                                    <th width="35%">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Chairperson</strong></td>
                                    <td>{{ $executiveOfficers['chairperson']?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($executiveOfficers['chairperson']) ? $executiveOfficers['chairperson']->effective_from->format('Y').'-'.$executiveOfficers['chairperson']->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $executiveOfficers['chairperson']?->operator->phone ?? '-' }}</td>
                                    <td>{{ $executiveOfficers['chairperson']?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Vice-Chairperson</strong></td>
                                    <td>{{ $executiveOfficers['vice_chairperson']?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($executiveOfficers['vice_chairperson']) ? $executiveOfficers['vice_chairperson']->effective_from->format('Y').'-'.$executiveOfficers['vice_chairperson']->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $executiveOfficers['vice_chairperson']?->operator->phone ?? '-' }}</td>
                                    <td>{{ $executiveOfficers['vice_chairperson']?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>General Manager</strong></td>
                                    <td>{{ $executiveOfficers['general_manager']?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($executiveOfficers['general_manager']) ? $executiveOfficers['general_manager']->effective_from->format('Y').'-'.$executiveOfficers['general_manager']->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $executiveOfficers['general_manager']?->operator->phone ?? '-' }}</td>
                                    <td>{{ $executiveOfficers['general_manager']?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Secretary</strong></td>
                                    <td>{{ $executiveOfficers['secretary']?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($executiveOfficers['secretary']) ? $executiveOfficers['secretary']->effective_from->format('Y').'-'.$executiveOfficers['secretary']->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $executiveOfficers['secretary']?->operator->phone ?? '-' }}</td>
                                    <td>{{ $executiveOfficers['secretary']?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Treasurer</strong></td>
                                    <td>{{ $executiveOfficers['treasurer']?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($executiveOfficers['treasurer']) ? $executiveOfficers['treasurer']->effective_from->format('Y').'-'.$executiveOfficers['treasurer']->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $executiveOfficers['treasurer']?->operator->phone ?? '-' }}</td>
                                    <td>{{ $executiveOfficers['treasurer']?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bookkeeper</strong></td>
                                    <td>{{ $executiveOfficers['bookkeeper']?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($executiveOfficers['bookkeeper']) ? $executiveOfficers['bookkeeper']->effective_from->format('Y').'-'.$executiveOfficers['bookkeeper']->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $executiveOfficers['bookkeeper']?->operator->phone ?? '-' }}</td>
                                    <td>{{ $executiveOfficers['bookkeeper']?->operator->user->email ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Hidden inputs for PDF generation -->
                        <input type="hidden" name="exec_chairperson_name" value="{{ $executiveOfficers['chairperson']?->operator->user->name ?? '' }}">
                        <input type="hidden" name="exec_chairperson_mobile" value="{{ $executiveOfficers['chairperson']?->operator->phone ?? '' }}">
                        <input type="hidden" name="exec_chairperson_email" value="{{ $executiveOfficers['chairperson']?->operator->user->email ?? '' }}">
                        <input type="hidden" name="exec_chairperson_term" value="{{ isset($executiveOfficers['chairperson']) ? $executiveOfficers['chairperson']->effective_from->format('Y').'-'.$executiveOfficers['chairperson']->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="exec_vice_chairperson_name" value="{{ $executiveOfficers['vice_chairperson']?->operator->user->name ?? '' }}">
                        <input type="hidden" name="exec_vice_chairperson_mobile" value="{{ $executiveOfficers['vice_chairperson']?->operator->phone ?? '' }}">
                        <input type="hidden" name="exec_vice_chairperson_email" value="{{ $executiveOfficers['vice_chairperson']?->operator->user->email ?? '' }}">
                        <input type="hidden" name="exec_vice_chairperson_term" value="{{ isset($executiveOfficers['vice_chairperson']) ? $executiveOfficers['vice_chairperson']->effective_from->format('Y').'-'.$executiveOfficers['vice_chairperson']->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="exec_general_manager_name" value="{{ $executiveOfficers['general_manager']?->operator->user->name ?? '' }}">
                        <input type="hidden" name="exec_general_manager_mobile" value="{{ $executiveOfficers['general_manager']?->operator->phone ?? '' }}">
                        <input type="hidden" name="exec_general_manager_email" value="{{ $executiveOfficers['general_manager']?->operator->user->email ?? '' }}">
                        <input type="hidden" name="exec_general_manager_term" value="{{ isset($executiveOfficers['general_manager']) ? $executiveOfficers['general_manager']->effective_from->format('Y').'-'.$executiveOfficers['general_manager']->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="exec_secretary_name" value="{{ $executiveOfficers['secretary']?->operator->user->name ?? '' }}">
                        <input type="hidden" name="exec_secretary_mobile" value="{{ $executiveOfficers['secretary']?->operator->phone ?? '' }}">
                        <input type="hidden" name="exec_secretary_email" value="{{ $executiveOfficers['secretary']?->operator->user->email ?? '' }}">
                        <input type="hidden" name="exec_secretary_term" value="{{ isset($executiveOfficers['secretary']) ? $executiveOfficers['secretary']->effective_from->format('Y').'-'.$executiveOfficers['secretary']->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="exec_treasurer_name" value="{{ $executiveOfficers['treasurer']?->operator->user->name ?? '' }}">
                        <input type="hidden" name="exec_treasurer_mobile" value="{{ $executiveOfficers['treasurer']?->operator->phone ?? '' }}">
                        <input type="hidden" name="exec_treasurer_email" value="{{ $executiveOfficers['treasurer']?->operator->user->email ?? '' }}">
                        <input type="hidden" name="exec_treasurer_term" value="{{ isset($executiveOfficers['treasurer']) ? $executiveOfficers['treasurer']->effective_from->format('Y').'-'.$executiveOfficers['treasurer']->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="exec_bookkeeper_name" value="{{ $executiveOfficers['bookkeeper']?->operator->user->name ?? '' }}">
                        <input type="hidden" name="exec_bookkeeper_mobile" value="{{ $executiveOfficers['bookkeeper']?->operator->phone ?? '' }}">
                        <input type="hidden" name="exec_bookkeeper_email" value="{{ $executiveOfficers['bookkeeper']?->operator->user->email ?? '' }}">
                        <input type="hidden" name="exec_bookkeeper_term" value="{{ isset($executiveOfficers['bookkeeper']) ? $executiveOfficers['bookkeeper']->effective_from->format('Y').'-'.$executiveOfficers['bookkeeper']->effective_to->format('Y') : '' }}">
                    </div>

                    <!-- Committees -->
                    <div class="governance-section">
                        <h6 class="governance-subtitle"><i class="fas fa-users-cog"></i> Committees</h6>
                        <table class="table table-bordered governance-table">
                            <thead>
                                <tr>
                                    <th width="15%">Committee</th>
                                    <th width="12%">Position</th>
                                    <th width="18%">Name</th>
                                    <th width="12%">Term</th>
                                    <th width="13%">Mobile Number</th>
                                    <th width="30%">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Board of Directors
                                    $bodChair = $committees['board']->firstWhere('position', 'chairperson');
                                    $bodViceChair = $committees['board']->firstWhere('position', 'vice_chairperson');
                                    // Get BOD members - filter by position 'bod_member'
                                    $bodMembers = $committees['board']->where('position', 'bod_member')->values();

                                    // Audit Committee
                                    $auditChair = $committees['audit']->firstWhere('position', 'audit_chairperson');
                                    $auditViceChair = $committees['audit']->firstWhere('position', 'audit_vice_chairperson');
                                    $auditSecretary = $committees['audit']->firstWhere('position', 'audit_secretary');
                                    $auditMember = $committees['audit']->firstWhere('position', 'audit_member');

                                    // Election Committee
                                    $electionChair = $committees['election']->firstWhere('position', 'election_chairperson');
                                    $electionViceChair = $committees['election']->firstWhere('position', 'election_vice_chairperson');
                                    $electionSecretary = $committees['election']->firstWhere('position', 'election_secretary');
                                    $electionMember = $committees['election']->firstWhere('position', 'election_member');

                                    // Mediation Committee
                                    $mediationChair = $committees['mediation']->firstWhere('position', 'mediation_chairperson');
                                    $mediationViceChair = $committees['mediation']->firstWhere('position', 'mediation_vice_chairperson');
                                    $mediationSecretary = $committees['mediation']->firstWhere('position', 'mediation_secretary');
                                    $mediationMember = $committees['mediation']->firstWhere('position', 'mediation_member');

                                    // Ethics Committee
                                    $ethicsChair = $committees['ethics']->firstWhere('position', 'ethics_chairperson');
                                    $ethicsViceChair = $committees['ethics']->firstWhere('position', 'ethics_vice_chairperson');
                                    $ethicsSecretary = $committees['ethics']->firstWhere('position', 'ethics_secretary');
                                    $ethicsMember = $committees['ethics']->firstWhere('position', 'ethics_member');

                                    // GAD Committee
                                    $genderChair = $committees['gender']->firstWhere('position', 'gad_chairperson');
                                    $genderViceChair = $committees['gender']->firstWhere('position', 'gad_vice_chairperson');
                                    $genderSecretary = $committees['gender']->firstWhere('position', 'gad_secretary');
                                    $genderMember = $committees['gender']->firstWhere('position', 'gad_member');

                                    // Education Committee
                                    $educationChair = $committees['education']->firstWhere('position', 'education_chairperson');
                                    $educationSecretary = $committees['education']->firstWhere('position', 'education_secretary');
                                    $educationMember = $committees['education']->firstWhere('position', 'education_member');
                                @endphp

                                <!-- Board of Directors -->
                                @php
                                    $bodRowspan = 2 + $bodMembers->count();
                                @endphp
                                <tr>
                                    <td rowspan="{{ $bodRowspan }}"><strong>Board of Directors</strong></td>
                                    <td>Chairperson</td>
                                    <td>{{ $bodChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($bodChair) ? $bodChair->effective_from->format('Y').'-'.$bodChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $bodChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $bodChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Vice-Chairperson</td>
                                    <td>{{ $bodViceChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($bodViceChair) ? $bodViceChair->effective_from->format('Y').'-'.$bodViceChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $bodViceChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $bodViceChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                @foreach($bodMembers as $index => $member)
                                <tr>
                                    <td>Member</td>
                                    <td>{{ $member->operator->user->name ?? '-' }}</td>
                                    <td>{{ $member->effective_from->format('Y').'-'.$member->effective_to->format('Y') }}</td>
                                    <td>{{ $member->operator->phone ?? '-' }}</td>
                                    <td>{{ $member->operator->user->email ?? '-' }}</td>
                                </tr>
                                @endforeach

                                <!-- Audit Committee -->
                                <tr>
                                    <td rowspan="3"><strong>Audit Committee</strong></td>
                                    <td>Chairperson</td>
                                    <td>{{ $auditChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($auditChair) ? $auditChair->effective_from->format('Y').'-'.$auditChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $auditChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $auditChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Secretary</td>
                                    <td>{{ $auditSecretary?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($auditSecretary) ? $auditSecretary->effective_from->format('Y').'-'.$auditSecretary->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $auditSecretary?->operator->phone ?? '-' }}</td>
                                    <td>{{ $auditSecretary?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Member</td>
                                    <td>{{ $auditMember?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($auditMember) ? $auditMember->effective_from->format('Y').'-'.$auditMember->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $auditMember?->operator->phone ?? '-' }}</td>
                                    <td>{{ $auditMember?->operator->user->email ?? '-' }}</td>
                                </tr>

                                <!-- Election Committee -->
                                <tr>
                                    <td rowspan="3"><strong>Election Committee</strong></td>
                                    <td>Chairperson</td>
                                    <td>{{ $electionChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($electionChair) ? $electionChair->effective_from->format('Y').'-'.$electionChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $electionChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $electionChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Secretary</td>
                                    <td>{{ $electionSecretary?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($electionSecretary) ? $electionSecretary->effective_from->format('Y').'-'.$electionSecretary->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $electionSecretary?->operator->phone ?? '-' }}</td>
                                    <td>{{ $electionSecretary?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Member</td>
                                    <td>{{ $electionMember?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($electionMember) ? $electionMember->effective_from->format('Y').'-'.$electionMember->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $electionMember?->operator->phone ?? '-' }}</td>
                                    <td>{{ $electionMember?->operator->user->email ?? '-' }}</td>
                                </tr>

                                <!-- Mediation Committee -->
                                <tr>
                                    <td rowspan="3"><strong>Mediation & Conciliation</strong></td>
                                    <td>Chairperson</td>
                                    <td>{{ $mediationChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($mediationChair) ? $mediationChair->effective_from->format('Y').'-'.$mediationChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $mediationChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $mediationChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Secretary</td>
                                    <td>{{ $mediationSecretary?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($mediationSecretary) ? $mediationSecretary->effective_from->format('Y').'-'.$mediationSecretary->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $mediationSecretary?->operator->phone ?? '-' }}</td>
                                    <td>{{ $mediationSecretary?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Member</td>
                                    <td>{{ $mediationMember?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($mediationMember) ? $mediationMember->effective_from->format('Y').'-'.$mediationMember->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $mediationMember?->operator->phone ?? '-' }}</td>
                                    <td>{{ $mediationMember?->operator->user->email ?? '-' }}</td>
                                </tr>

                                <!-- Ethics Committee -->
                                <tr>
                                    <td rowspan="3"><strong>Ethics Committee</strong></td>
                                    <td>Chairperson</td>
                                    <td>{{ $ethicsChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($ethicsChair) ? $ethicsChair->effective_from->format('Y').'-'.$ethicsChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $ethicsChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $ethicsChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Secretary</td>
                                    <td>{{ $ethicsSecretary?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($ethicsSecretary) ? $ethicsSecretary->effective_from->format('Y').'-'.$ethicsSecretary->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $ethicsSecretary?->operator->phone ?? '-' }}</td>
                                    <td>{{ $ethicsSecretary?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Member</td>
                                    <td>{{ $ethicsMember?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($ethicsMember) ? $ethicsMember->effective_from->format('Y').'-'.$ethicsMember->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $ethicsMember?->operator->phone ?? '-' }}</td>
                                    <td>{{ $ethicsMember?->operator->user->email ?? '-' }}</td>
                                </tr>

                                <!-- GAD Committee -->
                                <tr>
                                    <td rowspan="3"><strong>Gender & Development</strong></td>
                                    <td>Chairperson</td>
                                    <td>{{ $genderChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($genderChair) ? $genderChair->effective_from->format('Y').'-'.$genderChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $genderChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $genderChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Secretary</td>
                                    <td>{{ $genderSecretary?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($genderSecretary) ? $genderSecretary->effective_from->format('Y').'-'.$genderSecretary->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $genderSecretary?->operator->phone ?? '-' }}</td>
                                    <td>{{ $genderSecretary?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Member</td>
                                    <td>{{ $genderMember?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($genderMember) ? $genderMember->effective_from->format('Y').'-'.$genderMember->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $genderMember?->operator->phone ?? '-' }}</td>
                                    <td>{{ $genderMember?->operator->user->email ?? '-' }}</td>
                                </tr>

                                <!-- Education Committee -->
                                <tr>
                                    <td rowspan="3"><strong>Education Committee</strong></td>
                                    <td>Chairperson</td>
                                    <td>{{ $educationChair?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($educationChair) ? $educationChair->effective_from->format('Y').'-'.$educationChair->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $educationChair?->operator->phone ?? '-' }}</td>
                                    <td>{{ $educationChair?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Secretary</td>
                                    <td>{{ $educationSecretary?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($educationSecretary) ? $educationSecretary->effective_from->format('Y').'-'.$educationSecretary->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $educationSecretary?->operator->phone ?? '-' }}</td>
                                    <td>{{ $educationSecretary?->operator->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Member</td>
                                    <td>{{ $educationMember?->operator->user->name ?? 'To be Assigned' }}</td>
                                    <td>{{ isset($educationMember) ? $educationMember->effective_from->format('Y').'-'.$educationMember->effective_to->format('Y') : '-' }}</td>
                                    <td>{{ $educationMember?->operator->phone ?? '-' }}</td>
                                    <td>{{ $educationMember?->operator->user->email ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Hidden inputs for PDF generation -->
                        <input type="hidden" name="bod_chairperson_name" value="{{ $bodChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="bod_chairperson_mobile" value="{{ $bodChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="bod_chairperson_email" value="{{ $bodChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="bod_chairperson_term" value="{{ isset($bodChair) && $bodChair ? $bodChair->effective_from->format('Y').'-'.$bodChair->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="bod_vice_chairperson_name" value="{{ $bodViceChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="bod_vice_chairperson_mobile" value="{{ $bodViceChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="bod_vice_chairperson_email" value="{{ $bodViceChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="bod_vice_chairperson_term" value="{{ isset($bodViceChair) ? $bodViceChair->effective_from->format('Y').'-'.$bodViceChair->effective_to->format('Y') : '' }}">
                        @foreach($bodMembers as $index => $member)
                            <input type="hidden" name="bod_member{{ $index + 1 }}_name" value="{{ $member->operator->user->name ?? '' }}">
                            <input type="hidden" name="bod_member{{ $index + 1 }}_mobile" value="{{ $member->operator->phone ?? '' }}">
                            <input type="hidden" name="bod_member{{ $index + 1 }}_email" value="{{ $member->operator->user->email ?? '' }}">
                            <input type="hidden" name="bod_member{{ $index + 1 }}_term" value="{{ $member->effective_from->format('Y').'-'.$member->effective_to->format('Y') }}">
                        @endforeach
                        {{-- Audit Committee --}}
                        <input type="hidden" name="audit_chairperson_name" value="{{ $auditChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="audit_chairperson_mobile" value="{{ $auditChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="audit_chairperson_email" value="{{ $auditChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="audit_chairperson_term" value="{{ isset($auditChair) ? $auditChair->effective_from->format('Y').'-'.$auditChair->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="audit_secretary_name" value="{{ $auditSecretary?->operator->user->name ?? '' }}">
                        <input type="hidden" name="audit_secretary_mobile" value="{{ $auditSecretary?->operator->phone ?? '' }}">
                        <input type="hidden" name="audit_secretary_email" value="{{ $auditSecretary?->operator->user->email ?? '' }}">
                        <input type="hidden" name="audit_secretary_term" value="{{ isset($auditSecretary) ? $auditSecretary->effective_from->format('Y').'-'.$auditSecretary->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="audit_member_name" value="{{ $auditMember?->operator->user->name ?? '' }}">
                        <input type="hidden" name="audit_member_mobile" value="{{ $auditMember?->operator->phone ?? '' }}">
                        <input type="hidden" name="audit_member_email" value="{{ $auditMember?->operator->user->email ?? '' }}">
                        <input type="hidden" name="audit_member_term" value="{{ isset($auditMember) ? $auditMember->effective_from->format('Y').'-'.$auditMember->effective_to->format('Y') : '' }}">
                        {{-- Election Committee --}}
                        <input type="hidden" name="election_chairperson_name" value="{{ $electionChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="election_chairperson_mobile" value="{{ $electionChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="election_chairperson_email" value="{{ $electionChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="election_chairperson_term" value="{{ isset($electionChair) ? $electionChair->effective_from->format('Y').'-'.$electionChair->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="election_secretary_name" value="{{ $electionSecretary?->operator->user->name ?? '' }}">
                        <input type="hidden" name="election_secretary_mobile" value="{{ $electionSecretary?->operator->phone ?? '' }}">
                        <input type="hidden" name="election_secretary_email" value="{{ $electionSecretary?->operator->user->email ?? '' }}">
                        <input type="hidden" name="election_secretary_term" value="{{ isset($electionSecretary) ? $electionSecretary->effective_from->format('Y').'-'.$electionSecretary->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="election_member_name" value="{{ $electionMember?->operator->user->name ?? '' }}">
                        <input type="hidden" name="election_member_mobile" value="{{ $electionMember?->operator->phone ?? '' }}">
                        <input type="hidden" name="election_member_email" value="{{ $electionMember?->operator->user->email ?? '' }}">
                        <input type="hidden" name="election_member_term" value="{{ isset($electionMember) ? $electionMember->effective_from->format('Y').'-'.$electionMember->effective_to->format('Y') : '' }}">
                        {{-- Mediation Committee --}}
                        <input type="hidden" name="mediation_chairperson_name" value="{{ $mediationChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="mediation_chairperson_mobile" value="{{ $mediationChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="mediation_chairperson_email" value="{{ $mediationChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="mediation_chairperson_term" value="{{ isset($mediationChair) ? $mediationChair->effective_from->format('Y').'-'.$mediationChair->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="mediation_secretary_name" value="{{ $mediationSecretary?->operator->user->name ?? '' }}">
                        <input type="hidden" name="mediation_secretary_mobile" value="{{ $mediationSecretary?->operator->phone ?? '' }}">
                        <input type="hidden" name="mediation_secretary_email" value="{{ $mediationSecretary?->operator->user->email ?? '' }}">
                        <input type="hidden" name="mediation_secretary_term" value="{{ isset($mediationSecretary) ? $mediationSecretary->effective_from->format('Y').'-'.$mediationSecretary->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="mediation_member_name" value="{{ $mediationMember?->operator->user->name ?? '' }}">
                        <input type="hidden" name="mediation_member_mobile" value="{{ $mediationMember?->operator->phone ?? '' }}">
                        <input type="hidden" name="mediation_member_email" value="{{ $mediationMember?->operator->user->email ?? '' }}">
                        <input type="hidden" name="mediation_member_term" value="{{ isset($mediationMember) ? $mediationMember->effective_from->format('Y').'-'.$mediationMember->effective_to->format('Y') : '' }}">
                        {{-- Ethics Committee --}}
                        <input type="hidden" name="ethics_chairperson_name" value="{{ $ethicsChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="ethics_chairperson_mobile" value="{{ $ethicsChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="ethics_chairperson_email" value="{{ $ethicsChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="ethics_chairperson_term" value="{{ isset($ethicsChair) ? $ethicsChair->effective_from->format('Y').'-'.$ethicsChair->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="ethics_secretary_name" value="{{ $ethicsSecretary?->operator->user->name ?? '' }}">
                        <input type="hidden" name="ethics_secretary_mobile" value="{{ $ethicsSecretary?->operator->phone ?? '' }}">
                        <input type="hidden" name="ethics_secretary_email" value="{{ $ethicsSecretary?->operator->user->email ?? '' }}">
                        <input type="hidden" name="ethics_secretary_term" value="{{ isset($ethicsSecretary) ? $ethicsSecretary->effective_from->format('Y').'-'.$ethicsSecretary->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="ethics_member_name" value="{{ $ethicsMember?->operator->user->name ?? '' }}">
                        <input type="hidden" name="ethics_member_mobile" value="{{ $ethicsMember?->operator->phone ?? '' }}">
                        <input type="hidden" name="ethics_member_email" value="{{ $ethicsMember?->operator->user->email ?? '' }}">
                        <input type="hidden" name="ethics_member_term" value="{{ isset($ethicsMember) ? $ethicsMember->effective_from->format('Y').'-'.$ethicsMember->effective_to->format('Y') : '' }}">
                        {{-- GAD Committee --}}
                        <input type="hidden" name="gender_chairperson_name" value="{{ $genderChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="gender_chairperson_mobile" value="{{ $genderChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="gender_chairperson_email" value="{{ $genderChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="gender_chairperson_term" value="{{ isset($genderChair) ? $genderChair->effective_from->format('Y').'-'.$genderChair->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="gender_secretary_name" value="{{ $genderSecretary?->operator->user->name ?? '' }}">
                        <input type="hidden" name="gender_secretary_mobile" value="{{ $genderSecretary?->operator->phone ?? '' }}">
                        <input type="hidden" name="gender_secretary_email" value="{{ $genderSecretary?->operator->user->email ?? '' }}">
                        <input type="hidden" name="gender_secretary_term" value="{{ isset($genderSecretary) ? $genderSecretary->effective_from->format('Y').'-'.$genderSecretary->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="gender_member_name" value="{{ $genderMember?->operator->user->name ?? '' }}">
                        <input type="hidden" name="gender_member_mobile" value="{{ $genderMember?->operator->phone ?? '' }}">
                        <input type="hidden" name="gender_member_email" value="{{ $genderMember?->operator->user->email ?? '' }}">
                        <input type="hidden" name="gender_member_term" value="{{ isset($genderMember) ? $genderMember->effective_from->format('Y').'-'.$genderMember->effective_to->format('Y') : '' }}">
                        {{-- Education Committee --}}
                        <input type="hidden" name="education_chairperson_name" value="{{ $educationChair?->operator->user->name ?? '' }}">
                        <input type="hidden" name="education_chairperson_mobile" value="{{ $educationChair?->operator->phone ?? '' }}">
                        <input type="hidden" name="education_chairperson_email" value="{{ $educationChair?->operator->user->email ?? '' }}">
                        <input type="hidden" name="education_chairperson_term" value="{{ isset($educationChair) ? $educationChair->effective_from->format('Y').'-'.$educationChair->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="education_secretary_name" value="{{ $educationSecretary?->operator->user->name ?? '' }}">
                        <input type="hidden" name="education_secretary_mobile" value="{{ $educationSecretary?->operator->phone ?? '' }}">
                        <input type="hidden" name="education_secretary_email" value="{{ $educationSecretary?->operator->user->email ?? '' }}">
                        <input type="hidden" name="education_secretary_term" value="{{ isset($educationSecretary) ? $educationSecretary->effective_from->format('Y').'-'.$educationSecretary->effective_to->format('Y') : '' }}">
                        <input type="hidden" name="education_member_name" value="{{ $educationMember?->operator->user->name ?? '' }}">
                        <input type="hidden" name="education_member_mobile" value="{{ $educationMember?->operator->phone ?? '' }}">
                        <input type="hidden" name="education_member_email" value="{{ $educationMember?->operator->user->email ?? '' }}">
                        <input type="hidden" name="education_member_term" value="{{ isset($educationMember) ? $educationMember->effective_from->format('Y').'-'.$educationMember->effective_to->format('Y') : '' }}">
                    </div>
                </div>

            </div>
        </div>

        <!-- CLUSTER 5: FINANCIAL AND BUSINESS ASPECT -->
        <div class="info-card" data-cluster="5">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Cluster 5: Financial and Business Aspect
                    @if($isAdmin)
                        <span class="badge badge-secondary ml-2">Read Only</span>
                    @endif
                    @if($isPresident)
                        <span class="badge badge-secondary ml-2">Read Only</span>
                    @endif
                </h2>
            </div>
            <div class="card-body">
                <h6 class="section-subtitle">Financial Aspect</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="financial_year_from" onchange="updateYearColumns('financial')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="financial_year_to" onchange="updateYearColumns('financial')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="financialTable">
                        <thead>
                            <tr>
                                <th width="40%">Financial Item</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th width="20%" class="text-center year-col" data-year="{{ $year }}">{{ $year }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Current Assets</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="current_assets_{{ $year }}" value="{{ old('current_assets_' . $year . '', $savedData['current_assets_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Non-Current Assets</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="non_current_assets_{{ $year }}" value="{{ old('non_current_assets_' . $year . '', $savedData['non_current_assets_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td><strong>Total Assets</strong></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="total_assets_{{ $year }}" value="{{ old('total_assets_' . $year . '', $savedData['total_assets_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Liabilities</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="liabilities_{{ $year }}" value="{{ old('liabilities_' . $year . '', $savedData['liabilities_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Members Equity</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="members_equity_{{ $year }}" value="{{ old('members_equity_' . $year . '', $savedData['members_equity_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Total Gross Revenues</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="total_revenues_{{ $year }}" value="{{ old('total_revenues_' . $year . '', $savedData['total_revenues_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Total Expenses</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="total_expenses_{{ $year }}" value="{{ old('total_expenses_' . $year . '', $savedData['total_expenses_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Net Surplus/Loss</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="net_surplus_{{ $year }}" value="{{ old('net_surplus_' . $year . '', $savedData['net_surplus_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Capitalization</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="capitalization_year_from" onchange="updateYearColumns('capitalization')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="capitalization_year_to" onchange="updateYearColumns('capitalization')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="capitalizationTable">
                        <thead>
                            <tr>
                                <th width="40%">Capitalization Item</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th width="20%" class="text-center year-col" data-year="{{ $year }}">{{ $year }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Initial Authorized Capital Stock</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="init_auth_capital_{{ $year }}" value="{{ old('init_auth_capital_' . $year . '', $savedData['init_auth_capital_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Present Authorized Capital Stock</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="pres_auth_capital_{{ $year }}" value="{{ old('pres_auth_capital_' . $year . '', $savedData['pres_auth_capital_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Subscribed Capital</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="subscribed_capital_{{ $year }}" value="{{ old('subscribed_capital_' . $year . '', $savedData['subscribed_capital_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Present Paid-up Capital</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="paid_up_capital_{{ $year }}" value="{{ old('paid_up_capital_' . $year . '', $savedData['paid_up_capital_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Capital Buildup Program Scheme</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="text" class="form-control-table" name="capital_buildup_{{ $year }}" value="{{ old('capital_buildup_' . $year . '', $savedData['capital_buildup_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Distribution of Net Surplus</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="surplus_year_from" onchange="updateYearColumns('surplus')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="surplus_year_to" onchange="updateYearColumns('surplus')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="surplusTable">
                        <thead>
                            <tr>
                                <th width="40%">Distribution Category</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th width="20%" class="text-center year-col" data-year="{{ $year }}">{{ $year }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>General Reserve Fund</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input surplus-{{ $year }}" name="surplus_reserve_{{ $year }}" value="{{ old('surplus_reserve_' . $year . '', $savedData['surplus_reserve_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Education & Training Program</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input surplus-{{ $year }}" name="surplus_education_{{ $year }}" value="{{ old('surplus_education_' . $year . '', $savedData['surplus_education_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Community Development Fund</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input surplus-{{ $year }}" name="surplus_community_{{ $year }}" value="{{ old('surplus_community_' . $year . '', $savedData['surplus_community_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Optional Fund</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input surplus-{{ $year }}" name="surplus_optional_{{ $year }}" value="{{ old('surplus_optional_' . $year . '', $savedData['surplus_optional_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Interest on Share Capital</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input surplus-{{ $year }}" name="surplus_interest_{{ $year }}" value="{{ old('surplus_interest_' . $year . '', $savedData['surplus_interest_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Patronage Refund</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input surplus-{{ $year }}" name="surplus_patronage_{{ $year }}" value="{{ old('surplus_patronage_' . $year . '', $savedData['surplus_patronage_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control-table" name="surplus_others_label" placeholder="Others (specify)" value="{{ old('surplus_others_label', $savedData['surplus_others_label'] ?? '') }}"></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input surplus-{{ $year }}" name="surplus_others_{{ $year }}" value="{{ old('surplus_others_' . $year . '', $savedData['surplus_others_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr class="table-total">
                                <td><strong>TOTAL</strong></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="surplus_total_{{ $year }}" value="{{ old('surplus_total_' . $year . '', $savedData['surplus_total_' . $year . ''] ?? '') }}" readonly></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Grants/Donation</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="grantsTable">
                        <thead>
                            <tr>
                                <th width="15%">Date Acquired</th>
                                <th width="20%">Amount</th>
                                <th width="30%">Source</th>
                                <th width="30%">Status/Remarks</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grantDates = $savedData['grant_date'] ?? [''];
                                $grantAmounts = $savedData['grant_amount'] ?? [''];
                                $grantSources = $savedData['grant_source'] ?? [''];
                                $grantStatuses = $savedData['grant_status'] ?? [''];
                                $grantCount = max(count($grantDates), 1);
                            @endphp
                            @for($i = 0; $i < $grantCount; $i++)
                            <tr>
                                <td><input type="date" class="form-control-table" name="grant_date[]" value="{{ old('grant_date.' . $i, $grantDates[$i] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="grant_amount[]" value="{{ old('grant_amount.' . $i, $grantAmounts[$i] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="grant_source[]" value="{{ old('grant_source.' . $i, $grantSources[$i] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="grant_status[]" value="{{ old('grant_status.' . $i, $grantStatuses[$i] ?? '') }}"></td>
                                <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                    <button type="button" class="btn-add" onclick="addGrantRow()">
                        <i class="fas fa-plus"></i> Add Grant
                    </button>
                </div>

                <h6 class="section-subtitle">Scholarship Program</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table">
                        <thead>
                            <tr>
                                <th width="40%">Program</th>
                                <th width="30%">Course Taken</th>
                                <th width="30%">No. of Beneficiaries</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TESDA Tsuper Iskolar</td>
                                <td><input type="text" class="form-control-table" name="scholarship_tesda_course" value="{{ old('scholarship_tesda_course', $savedData['scholarship_tesda_course'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="scholarship_tesda_beneficiaries" value="{{ old('scholarship_tesda_beneficiaries', $savedData['scholarship_tesda_beneficiaries'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>DTI/BSMED/GO NEGOSYO</td>
                                <td><input type="text" class="form-control-table" name="scholarship_dti_course" value="{{ old('scholarship_dti_course', $savedData['scholarship_dti_course'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="scholarship_dti_beneficiaries" value="{{ old('scholarship_dti_beneficiaries', $savedData['scholarship_dti_beneficiaries'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control-table" name="scholarship_others_label" placeholder="Others (specify)" value="{{ old('scholarship_others_label', $savedData['scholarship_others_label'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="scholarship_others_course" value="{{ old('scholarship_others_course', $savedData['scholarship_others_course'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="scholarship_others_beneficiaries" value="{{ old('scholarship_others_beneficiaries', $savedData['scholarship_others_beneficiaries'] ?? '') }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Loans Availed</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table">
                        <thead>
                            <tr>
                                <th width="20%">Financing Institution</th>
                                <th width="15%">Date Acquired</th>
                                <th width="20%">Amount</th>
                                <th width="20%">Utilization</th>
                                <th width="25%">Status/Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>DBP</td>
                                <td><input type="date" class="form-control-table" name="loan_dbp_date" value="{{ old('loan_dbp_date', $savedData['loan_dbp_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="loan_dbp_amount" value="{{ old('loan_dbp_amount', $savedData['loan_dbp_amount'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="loan_dbp_utilization" value="{{ old('loan_dbp_utilization', $savedData['loan_dbp_utilization'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="loan_dbp_status" value="{{ old('loan_dbp_status', $savedData['loan_dbp_status'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>LBP</td>
                                <td><input type="date" class="form-control-table" name="loan_lbp_date" value="{{ old('loan_lbp_date', $savedData['loan_lbp_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="loan_lbp_amount" value="{{ old('loan_lbp_amount', $savedData['loan_lbp_amount'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="loan_lbp_utilization" value="{{ old('loan_lbp_utilization', $savedData['loan_lbp_utilization'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="loan_lbp_status" value="{{ old('loan_lbp_status', $savedData['loan_lbp_status'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control-table" name="loan_other_label" placeholder="Other (specify)" value="{{ old('loan_other_label', $savedData['loan_other_label'] ?? '') }}"></td>
                                <td><input type="date" class="form-control-table" name="loan_other_date" value="{{ old('loan_other_date', $savedData['loan_other_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="loan_other_amount" value="{{ old('loan_other_amount', $savedData['loan_other_amount'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="loan_other_utilization" value="{{ old('loan_other_utilization', $savedData['loan_other_utilization'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="loan_other_status" value="{{ old('loan_other_status', $savedData['loan_other_status'] ?? '') }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Existing Business</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="existingBusinessTable">
                        <thead>
                            <tr>
                                <th width="20%">Nature of Business</th>
                                <th width="15%">Starting Capital</th>
                                <th width="15%">Capital to Date 2022</th>
                                <th width="15%">Capital to Date 2023</th>
                                <th width="15%">Capital to Date 2024</th>
                                <th width="10%">Years of Existence</th>
                                <th width="10%">Status</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $existingBusinessNatures = $savedData['existing_business_nature'] ?? [''];
                                $existingBusinessStartingCapital = $savedData['existing_business_starting_capital'] ?? [''];
                                $existingBusinessCapital2022 = $savedData['existing_business_capital_2022'] ?? [''];
                                $existingBusinessCapital2023 = $savedData['existing_business_capital_2023'] ?? [''];
                                $existingBusinessCapital2024 = $savedData['existing_business_capital_2024'] ?? [''];
                                $existingBusinessYears = $savedData['existing_business_years'] ?? [''];
                                $existingBusinessStatuses = $savedData['existing_business_status'] ?? [''];
                                $existingBusinessCount = max(count($existingBusinessNatures), 1);
                            @endphp
                            @for($i = 0; $i < $existingBusinessCount; $i++)
                            <tr>
                                <td><input type="text" class="form-control-table" name="existing_business_nature[]" value="{{ old('existing_business_nature.' . $i, $existingBusinessNatures[$i] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_starting_capital[]" value="{{ old('existing_business_starting_capital.' . $i, $existingBusinessStartingCapital[$i] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_capital_2022[]" value="{{ old('existing_business_capital_2022.' . $i, $existingBusinessCapital2022[$i] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_capital_2023[]" value="{{ old('existing_business_capital_2023.' . $i, $existingBusinessCapital2023[$i] ?? '') }}"></td>
                                <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_capital_2024[]" value="{{ old('existing_business_capital_2024.' . $i, $existingBusinessCapital2024[$i] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="existing_business_years[]" value="{{ old('existing_business_years.' . $i, $existingBusinessYears[$i] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="existing_business_status[]" value="{{ old('existing_business_status.' . $i, $existingBusinessStatuses[$i] ?? '') }}"></td>
                                <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                    <button type="button" class="btn-add" onclick="addExistingBusinessRow()">
                        <i class="fas fa-plus"></i> Add Business
                    </button>
                </div>

                <h6 class="section-subtitle">Proposed Business</h6>
                <div id="proposedBusinessContainer">
                    @php
                        $proposedBusinesses = $savedData['proposed_business'] ?? [''];
                        $proposedBusinessCount = max(count($proposedBusinesses), 1);
                    @endphp
                    @for($i = 0; $i < $proposedBusinessCount; $i++)
                    <div class="form-group proposed-business-item" data-index="{{ $i + 1 }}">
                        <div class="row">
                            <div class="col-md-11">
                                <label><strong>{{ $i + 1 }}.</strong></label>
                                <textarea class="form-control" name="proposed_business[]" rows="2" placeholder="Enter proposed business details">{{ old('proposed_business.' . $i, $proposedBusinesses[$i] ?? '') }}</textarea>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeProposedBusinessItem(this)" style="{{ $i == 0 ? 'display: none;' : '' }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
                <button type="button" class="btn-add mb-3" onclick="addProposedBusinessItem()">
                    <i class="fas fa-plus"></i> Add Another Proposed Business
                </button>
            </div>
        </div>

        <!-- CLUSTER 6: CAPACITY/CAPABILITY BUILDING PROGRAM -->
        <div class="info-card" data-cluster="6">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-graduation-cap"></i>
                    Cluster 6: Capacity/Capability Building Program
                    @if($isTreasurer || $isPresident)
                        <span class="badge badge-secondary ml-2">Read Only</span>
                    @endif
                </h2>
            </div>
            <div class="card-body">
                <h6 class="section-subtitle">CETOS Monitoring</h6>
                <div class="mb-3">
                    <label class="form-label"><strong>Select Year Range:</strong></label>
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="cetos_year_from" onchange="updateYearColumns('cetos')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == (date('Y') - 1) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">From Year</small>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="cetos_year_to" onchange="updateYearColumns('cetos')">
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <small class="text-muted">To Year</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="cetosTable">
                        <thead>
                            <tr>
                                <th width="40%">CETOS Status</th>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <th width="20%" class="text-center year-col" data-year="{{ $year }}">{{ $year }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>With CETOS</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table cetos-{{ $year }}" name="cetos_with_{{ $year }}" value="{{ old('cetos_with_' . $year . '', $savedData['cetos_with_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr>
                                <td>Without CETOS</td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table cetos-{{ $year }}" name="cetos_without_{{ $year }}" value="{{ old('cetos_without_' . $year . '', $savedData['cetos_without_' . $year . ''] ?? '') }}"></td>
                                @endfor
                            </tr>
                            <tr class="table-total">
                                <td><strong>TOTAL</strong></td>
                                @for($year = 2020; $year <= date('Y'); $year++)
                                    <td class="year-col" data-year="{{ $year }}"><input type="number" min="0" class="form-control-table" name="cetos_total_{{ $year }}" value="{{ old('cetos_total_' . $year . '', $savedData['cetos_total_' . $year . ''] ?? '') }}" readonly></td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-subtitle">Training/Seminars</h6>
                <div class="table-responsive">
                    <table class="table table-bordered annual-table">
                        <thead>
                            <tr>
                                <th width="45%">Title</th>
                                <th width="30%">Date</th>
                                <th width="25%">No. of Attendees</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Fleet Management Seminar</td>
                                <td><input type="date" class="form-control-table" name="training_fleet_date" value="{{ old('training_fleet_date', $savedData['training_fleet_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="training_fleet_attendees" value="{{ old('training_fleet_attendees', $savedData['training_fleet_attendees'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>Financial Management Seminar</td>
                                <td><input type="date" class="form-control-table" name="training_financial_date" value="{{ old('training_financial_date', $savedData['training_financial_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="training_financial_attendees" value="{{ old('training_financial_attendees', $savedData['training_financial_attendees'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>Cooperative Management & Good Governance</td>
                                <td><input type="date" class="form-control-table" name="training_coop_mgmt_date" value="{{ old('training_coop_mgmt_date', $savedData['training_coop_mgmt_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="training_coop_mgmt_attendees" value="{{ old('training_coop_mgmt_attendees', $savedData['training_coop_mgmt_attendees'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>Leadership & Values Orientation</td>
                                <td><input type="date" class="form-control-table" name="training_leadership_date" value="{{ old('training_leadership_date', $savedData['training_leadership_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="training_leadership_attendees" value="{{ old('training_leadership_attendees', $savedData['training_leadership_attendees'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td>Labor Laws</td>
                                <td><input type="date" class="form-control-table" name="training_labor_date" value="{{ old('training_labor_date', $savedData['training_labor_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="training_labor_attendees" value="{{ old('training_labor_attendees', $savedData['training_labor_attendees'] ?? '') }}"></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control-table" name="training_others_title" placeholder="Others (specify)" value="{{ old('training_others_title', $savedData['training_others_title'] ?? '') }}"></td>
                                <td><input type="date" class="form-control-table" name="training_others_date" value="{{ old('training_others_date', $savedData['training_others_date'] ?? '') }}"></td>
                                <td><input type="number" min="0" class="form-control-table" name="training_others_attendees" value="{{ old('training_others_attendees', $savedData['training_others_attendees'] ?? '') }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CLUSTER 7: OTHER RELATED INFORMATION -->
        <div class="info-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-info"></i>
                    Cluster 7: Other Related Information
                </h2>
            </div>
            <div class="card-body">
                <h6 class="section-subtitle">Articles of Cooperative and By-Laws Amendments</h6>
                <div class="form-group mb-4">
                    <label>Amendment to Articles of Cooperative</label>
                    <textarea class="form-control" name="articles_amendment" rows="3" placeholder="Describe any amendments to the Articles of Cooperative">{{ old('articles_amendment', $savedData['articles_amendment'] ?? '') }}</textarea>
                </div>
                <div class="form-group mb-4">
                    <label>Amendment to By-Laws</label>
                    <textarea class="form-control" name="bylaws_amendment" rows="3" placeholder="Describe any amendments to the By-Laws">{{ old('bylaws_amendment', $savedData['bylaws_amendment'] ?? '') }}</textarea>
                </div>

                <h6 class="section-subtitle">File Attachments</h6>
                <div class="form-group mb-4">
                    <label>Articles of Cooperative Amendment Attachment</label>
                    <input type="file" class="form-control" name="articles_attachment" accept=".pdf,.doc,.docx">
                    <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX (Max 5MB)</small>
                </div>
                <div class="form-group mb-4">
                    <label>By-Laws Amendment Attachment</label>
                    <input type="file" class="form-control" name="bylaws_attachment" accept=".pdf,.doc,.docx">
                    <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX (Max 5MB)</small>
                </div>
                <div class="form-group mb-4">
                    <label>Other Supporting Documents</label>
                    <input type="file" class="form-control" name="other_attachments" accept=".pdf,.doc,.docx" multiple>
                    <small class="form-text text-muted">You can select multiple files. Accepted formats: PDF, DOC, DOCX (Max 5MB each)</small>
                </div>

                <h6 class="section-subtitle">Awards and Recognition</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered annual-table" id="awardsTable">
                        <thead>
                            <tr>
                                <th width="30%">Award/Recognition</th>
                                <th width="25%">Awarding Body</th>
                                <th width="15%">Date Received</th>
                                <th width="20%">Level</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control-table" name="award_name[]" placeholder="Award name" value="{{ old('award_name[]', $savedData['award_name[]'] ?? '') }}"></td>
                                <td><input type="text" class="form-control-table" name="award_body[]" placeholder="Awarding body" value="{{ old('award_body[]', $savedData['award_body[]'] ?? '') }}"></td>
                                <td><input type="date" class="form-control-table" name="award_date[]" value="{{ old('award_date[]', $savedData['award_date[]'] ?? '') }}"></td>
                                <td>
                                    <select class="form-control-table" name="award_level[]">
                                        <option value="">Select</option>
                                        <option value="Local">Local</option>
                                        <option value="Regional">Regional</option>
                                        <option value="National">National</option>
                                        <option value="International">International</option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn-add" onclick="addAwardRow()">
                        <i class="fas fa-plus"></i> Add Award
                    </button>
                </div>

                <h6 class="section-subtitle">Certification and Signatures</h6>
                <div class="signature-section mt-5 mb-4">
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <p><strong>Prepared By:</strong></p>
                            <div class="signature-line mt-5 mb-2">
                                <input type="text" class="form-control text-center border-0 border-bottom" name="secretary_name" placeholder="Name of Secretary" style="background: transparent;" value="{{ old('secretary_name', $savedData['secretary_name'] ?? '') }}">
                            </div>
                            <p class="mt-2"><strong>Secretary</strong></p>
                        </div>
                        <div class="col-md-6 text-center">
                            <p><strong>Noted By:</strong></p>
                            <div class="signature-line mt-5 mb-2">
                                <input type="text" class="form-control text-center border-0 border-bottom" name="chairperson_name" placeholder="Name of Chairperson" style="background: transparent;" value="{{ old('chairperson_name', $savedData['chairperson_name'] ?? '') }}">
                            </div>
                            <p class="mt-2"><strong>Chairperson</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="info-card mt-4" data-cluster="signatures">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-file-signature"></i>
                    Authorized Signatures
                </h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Secretary Signature -->
                    <div class="col-md-6 mb-4">
                        <div class="signature-box">
                            <h6 class="signature-title">
                                <i class="fas fa-user-edit"></i> Secretary E-Signature
                                @if($isPresident || $isTreasurer)
                                    <span class="badge badge-secondary ml-2">Read Only</span>
                                @endif
                            </h6>
                            <div class="form-group">
                                <div class="signature-upload-area" id="secretary_signature_area">
                                    <input type="file" class="form-control" name="secretary_signature" id="secretary_signature"
                                           accept="image/png,image/jpeg,image/jpg" onchange="previewSignature(this, 'secretary')" {{ ($isPresident || $isTreasurer) ? 'disabled' : '' }}>
                                    <div class="upload-placeholder" id="secretary_placeholder">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                        <p>{{ ($isPresident || $isTreasurer) ? 'Upload disabled' : 'Click to upload signature' }}</p>
                                        <small class="text-muted">PNG, JPG, JPEG (Max 2MB)</small>
                                    </div>
                                    <div class="signature-preview" id="secretary_preview" style="display: none;">
                                        <img id="secretary_preview_img" src="" alt="Secretary Signature">
                                        @if(!($isPresident || $isTreasurer))
                                        <button type="button" class="btn btn-sm btn-danger remove-signature" onclick="removeSignature('secretary')">
                                            <i class="fas fa-times"></i> Remove
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chairperson Signature -->
                    <div class="col-md-6 mb-4">
                        <div class="signature-box">
                            <h6 class="signature-title">
                                <i class="fas fa-user-tie"></i> Chairperson E-Signature
                                @if($isAdmin || $isTreasurer)
                                    <span class="badge badge-secondary ml-2">Read Only</span>
                                @endif
                            </h6>
                            <div class="form-group">
                                <div class="signature-upload-area" id="chairperson_signature_area">
                                    <input type="file" class="form-control" name="chairperson_signature" id="chairperson_signature"
                                           accept="image/png,image/jpeg,image/jpg" onchange="previewSignature(this, 'chairperson')" {{ ($isAdmin || $isTreasurer) ? 'disabled' : '' }}>
                                    <div class="upload-placeholder" id="chairperson_placeholder">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                        <p>{{ ($isAdmin || $isTreasurer) ? 'Upload disabled' : 'Click to upload signature' }}</p>
                                        <small class="text-muted">PNG, JPG, JPEG (Max 2MB)</small>
                                    </div>
                                    <div class="signature-preview" id="chairperson_preview" style="display: none;">
                                        <img id="chairperson_preview_img" src="" alt="Chairperson Signature">
                                        @if(!($isAdmin || $isTreasurer))
                                        <button type="button" class="btn btn-sm btn-danger remove-signature" onclick="removeSignature('chairperson')">
                                            <i class="fas fa-times"></i> Remove
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> The names and signatures provided here will appear at the bottom of the generated PDF report.
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('dashboard') }}'">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn btn-success" onclick="saveReport()">
                <i class="fas fa-save"></i> Save Report
            </button>
            <button type="button" class="btn btn-primary" onclick="downloadPDF()">
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
    </form>
</div>

<style>
    .annual-report-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 25px;
        background: #f8f9fa;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 40px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .page-subtitle {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        margin: 8px 0 0 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
        border: none;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        border-bottom: none;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-title i {
        font-size: 20px;
    }

    .card-body {
        padding: 30px;
    }

    .section-subtitle {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e3e6f0;
    }

    .form-grid {
        display: grid;
        gap: 20px;
        margin-bottom: 10px;
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
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
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

    /* Fix for select dropdowns - ensure text is visible */
    select.form-control {
        color: #495057;
        background-color: white;
        appearance: auto;
        -webkit-appearance: menulist;
        -moz-appearance: menulist;
            height: auto !important; /* Allow natural height */
    min-height: 46px; /* Set minimum height */
    padding: 10px 15px !important; /* Adjust padding */
    line-height: normal; /* Reset line height */
    }

    select.form-control option {
        color: #495057;
        background-color: white;
    }

    select.form-control option:checked,
    select.form-control option:hover {
        color: white;
        background-color: #667eea;
    }

    textarea.form-control {
        resize: vertical;
        font-family: inherit;
    }

    .table-responsive {
        overflow-x: auto;
        margin-bottom: 15px;
    }

    .annual-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .annual-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .annual-table th {
        padding: 12px 10px;
        text-align: center;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .annual-table td {
        padding: 8px;
        border: 1px solid #e3e6f0;
        vertical-align: middle;
    }

    .annual-table tbody tr:hover {
        background-color: #f8f9fc;
    }

    .table-total {
        background-color: #f8f9fc;
        font-weight: 600;
    }

    /* Organizational Chart Styles */
    /* Governance List Styles */
    .governance-list-container {
        background: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .governance-section {
        margin-bottom: 25px;
    }

    .governance-subtitle {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #667eea;
    }

    .governance-subtitle i {
        margin-right: 8px;
        color: #667eea;
    }

    .governance-table {
        font-size: 14px;
        margin-bottom: 0;
    }

    .governance-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 12px;
    }

    .governance-table tbody td {
        padding: 10px 12px;
        vertical-align: middle;
    }

    .governance-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Signature Section Styles */
    .signature-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        color: white;
    }

    .signature-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(255,255,255,0.3);
    }

    .signature-box .form-label {
        color: white;
        font-weight: 500;
    }

    .signature-box .form-control {
        background: rgba(255,255,255,0.9);
        border: none;
        color: #333;
        font-weight: 500;
    }

    .signature-box .form-control:focus {
        background: white;
        box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
    }

    .signature-upload-area {
        position: relative;
        min-height: 200px;
    }

    .signature-upload-area input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .upload-placeholder {
        background: rgba(255,255,255,0.15);
        border: 3px dashed rgba(255,255,255,0.5);
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .upload-placeholder:hover {
        background: rgba(255,255,255,0.25);
        border-color: rgba(255,255,255,0.8);
    }

    .upload-placeholder i {
        color: white;
        opacity: 0.8;
    }

    .upload-placeholder p {
        color: white;
        font-weight: 500;
        margin: 10px 0 5px 0;
    }

    .signature-preview {
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        position: relative;
    }

    .signature-preview img {
        max-width: 100%;
        max-height: 180px;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 10px;
        background: white;
    }

    .remove-signature {
        margin-top: 10px;
        width: 100%;
    }

    .form-control-table {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #e3e6f0;
        border-radius: 4px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .form-control-table:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
    }

    textarea.form-control-table {
        resize: vertical;
        font-family: inherit;
    }

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
        gap: 8px;
        text-decoration: none;
        color: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(67, 233, 123, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(108, 117, 125, 0.3);
    }

    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-remove {
        background: #e74a3b;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 12px;
    }

    .btn-remove:hover {
        background: #c0392b;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding: 20px 0;
    }

    @media print {
        .page-header,
        .header-actions,
        .btn,
        .btn-add,
        .btn-remove,
        .form-actions {
            display: none !important;
        }

        .annual-report-container {
            padding: 0;
            background: white;
        }

        .info-card {
            box-shadow: none;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .card-header {
            background: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 2px solid #000;
        }

        input,
        textarea,
        select {
            border: none !important;
            border-bottom: 1px solid #000 !important;
            border-radius: 0 !important;
        }

        .annual-table th {
            background: #f8f9fa !important;
            color: #000 !important;
            border: 1px solid #000 !important;
        }

        .annual-table td {
            border: 1px solid #000 !important;
        }
    }

    @media (max-width: 1024px) {
        .form-grid.two-columns,
        .form-grid.three-columns {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .annual-report-container {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .card-header {
            padding: 15px 20px;
        }

        .card-title {
            font-size: 16px;
        }

        .card-body {
            padding: 20px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
// Get dynamic years from the page
function getDynamicYears() {
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let year = 2020; year <= currentYear; year++) {
        years.push(year.toString());
    }
    return years;
}

// Role-based access control
const userRole = {
    isAdmin: {{ $isAdmin ? 'true' : 'false' }},
    isPresident: {{ $isPresident ? 'true' : 'false' }},
    isTreasurer: {{ $isTreasurer ? 'true' : 'false' }}
};

// Apply readonly access based on user role
function applyRoleBasedAccess() {
    // Treasurer: Can only edit Cluster 5, rest are readonly
    if (userRole.isTreasurer) {
        ['1', '2', '3', '4', '6'].forEach(cluster => {
            makeClusterReadonly(cluster);
        });
    }

    // President: Can only upload e-signature (Chairperson), rest are readonly
    if (userRole.isPresident) {
        ['1', '2', '3', '4', '5', '6'].forEach(cluster => {
            makeClusterReadonly(cluster);
        });
    }

    // Admin: Can edit all except Cluster 5
    if (userRole.isAdmin) {
        makeClusterReadonly('5');
    }
}

function makeClusterReadonly(clusterNumber) {
    const cluster = document.querySelector(`[data-cluster="${clusterNumber}"]`);
    if (!cluster) return;

    // Make all inputs, textareas, and selects readonly/disabled
    cluster.querySelectorAll('input:not([type="file"]), textarea, select').forEach(element => {
        element.setAttribute('readonly', 'readonly');
        element.style.backgroundColor = '#f5f5f5';
        element.style.cursor = 'not-allowed';
    });

    // Disable file inputs
    cluster.querySelectorAll('input[type="file"]').forEach(element => {
        element.setAttribute('disabled', 'disabled');
    });

    // Disable all buttons in readonly clusters
    cluster.querySelectorAll('button').forEach(button => {
        button.setAttribute('disabled', 'disabled');
        button.style.cursor = 'not-allowed';
    });
}

// Auto-calculate totals on page load
document.addEventListener('DOMContentLoaded', function() {
    // Apply role-based access control first
    applyRoleBasedAccess();

    const years = getDynamicYears();
    const types = ['drivers', 'operator', 'allied', 'others'];

    // Cluster 2: Number of Members
    years.forEach(year => {
        ['male', 'female'].forEach(gender => {
            types.forEach(type => {
                const input = document.querySelector(`input[name="${type}_${year}_${gender}"]`);
                if (input) {
                    input.addEventListener('input', () => calculateMemberTotals(year));
                }
            });
        });
    });

    // Cluster 2: Special status totals
    years.forEach(year => {
        ['pwd', 'senior'].forEach(category => {
            const input = document.querySelector(`input[name="${category}_${year}"]`);
            if (input) {
                input.addEventListener('input', calculateSpecialStatusTotals);
            }
        });
    });

    // Cluster 3: Units totals
    years.forEach(year => {
        const coopInputs = document.querySelectorAll(`.units-coop-${year}`);
        const memberInputs = document.querySelectorAll(`.units-member-${year}`);

        coopInputs.forEach(input => {
            input.addEventListener('input', () => calculateUnitsTotals(year));
        });

        memberInputs.forEach(input => {
            input.addEventListener('input', () => calculateUnitsTotals(year));
        });
    });

    // Cluster 3: Franchise total units
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('franchise_units[')) {
            calculateFranchiseTotalUnits();
        }
    });

    // Calculate franchise total on page load
    calculateFranchiseTotalUnits();

    // Cluster 5: Surplus Distribution totals for each year
    years.forEach(year => {
        const inputs = document.querySelectorAll(`.surplus-${year}`);
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateSurplusTotal(year));
        });
    });

    // Cluster 7: CETOS totals for each year
    years.forEach(year => {
        const inputs = document.querySelectorAll(`.cetos-${year}`);
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateCetosTotal(year));
        });
    });
});

// Cluster 2: Calculate member totals
function calculateMemberTotals(year) {
    const types = ['drivers', 'operator', 'allied', 'others'];
    let totalMale = 0;
    let totalFemale = 0;

    types.forEach(type => {
        const male = parseInt(document.querySelector(`input[name="${type}_${year}_male"]`)?.value) || 0;
        const female = parseInt(document.querySelector(`input[name="${type}_${year}_female"]`)?.value) || 0;
        totalMale += male;
        totalFemale += female;
    });

    const totalMaleInput = document.querySelector(`input[name="total_${year}_male"]`);
    const totalFemaleInput = document.querySelector(`input[name="total_${year}_female"]`);

    if (totalMaleInput) totalMaleInput.value = totalMale;
    if (totalFemaleInput) totalFemaleInput.value = totalFemale;
}

// Cluster 2: Calculate special status totals
function calculateSpecialStatusTotals() {
    const years = getDynamicYears();

    years.forEach(year => {
        const pwd = parseInt(document.querySelector(`input[name="pwd_${year}"]`)?.value) || 0;
        const senior = parseInt(document.querySelector(`input[name="senior_${year}"]`)?.value) || 0;
        const total = pwd + senior;

        const totalInput = document.querySelector(`input[name="special_total_${year}"]`);
        if (totalInput) totalInput.value = total;
    });
}

// Cluster 3: Calculate units totals
function calculateUnitsTotals(year) {
    const coopInputs = document.querySelectorAll(`.units-coop-${year}`);
    const memberInputs = document.querySelectorAll(`.units-member-${year}`);

    let totalCoop = 0;
    let totalMember = 0;

    coopInputs.forEach(input => {
        totalCoop += parseInt(input.value) || 0;
    });

    memberInputs.forEach(input => {
        totalMember += parseInt(input.value) || 0;
    });

    const totalCoopInput = document.querySelector(`input[name="units_total_coop_${year}"]`);
    const totalMemberInput = document.querySelector(`input[name="units_total_member_${year}"]`);

    if (totalCoopInput) totalCoopInput.value = totalCoop;
    if (totalMemberInput) totalMemberInput.value = totalMember;
}

// Cluster 3: Calculate franchise total units
function calculateFranchiseTotalUnits() {
    const inputs = document.querySelectorAll('input[name="franchise_units[]"]');
    let total = 0;

    inputs.forEach(input => {
        total += parseInt(input.value) || 0;
    });

    const totalInput = document.querySelector('input[name="franchise_total_units"]');
    if (totalInput) totalInput.value = total;
}

// Cluster 5: Calculate surplus distribution totals
function calculateSurplusTotal(year) {
    const inputs = document.querySelectorAll(`.surplus-${year}`);
    let total = 0;

    inputs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    const totalInput = document.querySelector(`input[name="surplus_total_${year}"]`);
    if (totalInput) {
        totalInput.value = total.toFixed(2);
    }
}

// Cluster 6: Calculate CETOS totals
function calculateCetosTotal(year) {
    const withCetos = parseInt(document.querySelector(`input[name="cetos_with_${year}"]`)?.value) || 0;
    const withoutCetos = parseInt(document.querySelector(`input[name="cetos_without_${year}"]`)?.value) || 0;
    const total = withCetos + withoutCetos;

    const totalInput = document.querySelector(`input[name="cetos_total_${year}"]`);
    if (totalInput) totalInput.value = total;
}

// Dynamic row functions - General remove function
function removeRow(btn) {
    btn.closest('tr').remove();
    // Recalculate totals if needed
    calculateFranchiseTotalUnits();
}

// Cluster 3: Add franchise row
function addFranchiseRow() {
    const table = document.getElementById('franchiseTable').querySelector('tbody');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" class="form-control-table" name="franchise_route[]" value="{{ old('franchise_route[]', $savedData['franchise_route[]'] ?? '') }}"></td>
        <td><input type="number" min="0" class="form-control-table" name="franchise_units[]" onchange="calculateFranchiseTotalUnits()" value="{{ old('franchise_units[]', $savedData['franchise_units[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="franchise_case[]" value="{{ old('franchise_case[]', $savedData['franchise_case[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="franchise_unit_type[]" placeholder="e.g., Euro, Electric, Solar" value="{{ old('franchise_unit_type[]', $savedData['franchise_unit_type[]'] ?? '') }}"></td>
        <td><input type="date" class="form-control-table" name="franchise_validity[]" value="{{ old('franchise_validity[]', $savedData['franchise_validity[]'] ?? '') }}"></td>
        <td>
            <select class="form-control-table" name="franchise_remarks[]">
                <option value="">Select</option>
                <option value="Consolidated">Consolidated</option>
                <option value="Individual">Individual</option>
            </select>
        </td>
        <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
}

// Cluster 4: Add executive officer row
function addExecutiveOfficerRow() {
    const table = document.getElementById('executiveOfficersTable').querySelector('tbody');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" class="form-control-table" name="exec_officer_name[]" placeholder="Name" value="{{ old('exec_officer_name[]', $savedData['exec_officer_name[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="exec_officer_mobile[]" placeholder="Mobile No." value="{{ old('exec_officer_mobile[]', $savedData['exec_officer_mobile[]'] ?? '') }}"></td>
        <td><input type="email" class="form-control-table" name="exec_officer_email[]" placeholder="email@example.com" value="{{ old('exec_officer_email[]', $savedData['exec_officer_email[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="exec_officer_term[]" placeholder="e.g., 2022-2024" value="{{ old('exec_officer_term[]', $savedData['exec_officer_term[]'] ?? '') }}"></td>
        <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
}

// Cluster 4: Add committee member row
function addCommitteeRow() {
    const table = document.getElementById('committeesTable').querySelector('tbody');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" class="form-control-table" name="committee_name[]" placeholder="Name" value="{{ old('committee_name[]', $savedData['committee_name[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="committee_mobile[]" placeholder="Mobile No." value="{{ old('committee_mobile[]', $savedData['committee_mobile[]'] ?? '') }}"></td>
        <td><input type="email" class="form-control-table" name="committee_email[]" placeholder="email@example.com" value="{{ old('committee_email[]', $savedData['committee_email[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="committee_term[]" placeholder="e.g., 2022-2024" value="{{ old('committee_term[]', $savedData['committee_term[]'] ?? '') }}"></td>
        <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
}

// Cluster 5: Add grant row
function addGrantRow() {
    const table = document.getElementById('grantsTable').querySelector('tbody');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="date" class="form-control-table" name="grant_date[]" value="{{ old('grant_date[]', $savedData['grant_date[]'] ?? '') }}"></td>
        <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="grant_amount[]" value="{{ old('grant_amount[]', $savedData['grant_amount[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="grant_source[]" value="{{ old('grant_source[]', $savedData['grant_source[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="grant_status[]" value="{{ old('grant_status[]', $savedData['grant_status[]'] ?? '') }}"></td>
        <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
}

// Cluster 5: Add existing business row
function addExistingBusinessRow() {
    const table = document.getElementById('existingBusinessTable').querySelector('tbody');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" class="form-control-table" name="existing_business_nature[]" value="{{ old('existing_business_nature[]', $savedData['existing_business_nature[]'] ?? '') }}"></td>
        <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_starting_capital[]" value="{{ old('existing_business_starting_capital[]', $savedData['existing_business_starting_capital[]'] ?? '') }}"></td>
        <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_capital_2022[]" value="{{ old('existing_business_capital_2022[]', $savedData['existing_business_capital_2022[]'] ?? '') }}"></td>
        <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_capital_2023[]" value="{{ old('existing_business_capital_2023[]', $savedData['existing_business_capital_2023[]'] ?? '') }}"></td>
        <td><input type="number" min="0" step="0.01" class="form-control-table currency-input" name="existing_business_capital_2024[]" value="{{ old('existing_business_capital_2024[]', $savedData['existing_business_capital_2024[]'] ?? '') }}"></td>
        <td><input type="number" min="0" class="form-control-table" name="existing_business_years[]" value="{{ old('existing_business_years[]', $savedData['existing_business_years[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="existing_business_status[]" value="{{ old('existing_business_status[]', $savedData['existing_business_status[]'] ?? '') }}"></td>
        <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
}

// Cluster 7: Add award row
function addAwardRow() {
    const table = document.getElementById('awardsTable').querySelector('tbody');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" class="form-control-table" name="award_name[]" placeholder="Award name" value="{{ old('award_name[]', $savedData['award_name[]'] ?? '') }}"></td>
        <td><input type="text" class="form-control-table" name="award_body[]" placeholder="Awarding body" value="{{ old('award_body[]', $savedData['award_body[]'] ?? '') }}"></td>
        <td><input type="date" class="form-control-table" name="award_date[]" value="{{ old('award_date[]', $savedData['award_date[]'] ?? '') }}"></td>
        <td>
            <select class="form-control-table" name="award_level[]">
                <option value="">Select</option>
                <option value="Local">Local</option>
                <option value="Regional">Regional</option>
                <option value="National">National</option>
                <option value="International">International</option>
            </select>
        </td>
        <td><button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
    `;
}

// Form submission and printing
function saveReport() {
    const form = document.getElementById('annualReportForm');
    if (form.checkValidity()) {
        form.submit();
    } else {
        alert('Please fill in all required fields.');
        form.reportValidity();
    }
}

// Change year function
function changeYear(year) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('year', year);
    window.location.href = currentUrl.toString();
}

// Download PDF function
// Signature preview and management functions
function previewSignature(input, type) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a PNG, JPG, or JPEG image');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(type + '_preview_img').src = e.target.result;
            document.getElementById(type + '_placeholder').style.display = 'none';
            document.getElementById(type + '_preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function removeSignature(type) {
    document.getElementById(type + '_signature').value = '';
    document.getElementById(type + '_preview_img').src = '';
    document.getElementById(type + '_placeholder').style.display = 'block';
    document.getElementById(type + '_preview').style.display = 'none';
}

function downloadPDF() {
    const form = document.getElementById('annualReportForm');
    const formData = new FormData(form);

    // Capture year range selections from all tables
    const membersYearFrom = document.getElementById('members_year_from')?.value;
    const membersYearTo = document.getElementById('members_year_to')?.value;

    if (membersYearFrom) formData.append('year_range_start', membersYearFrom);
    if (membersYearTo) formData.append('year_range_end', membersYearTo);

    // Determine PDF route based on user role
    @php
        $pdfRoute = 'admin.annual-report.pdf';
        if ($isPresident) {
            $pdfRoute = 'president.annual-report.pdf';
        } elseif ($isTreasurer) {
            $pdfRoute = 'treasurer.annual-report.pdf';
        }
    @endphp

    // Create a temporary form to submit to PDF route
    const pdfForm = document.createElement('form');
    pdfForm.method = 'POST';
    pdfForm.action = '{{ route($pdfRoute) }}';
    pdfForm.style.display = 'none';

    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    pdfForm.appendChild(csrfInput);

    // Add signature images as base64
    const secretaryImg = document.getElementById('secretary_preview_img');
    const chairpersonImg = document.getElementById('chairperson_preview_img');

    if (secretaryImg && secretaryImg.src && secretaryImg.src.startsWith('data:')) {
        const secretarySigInput = document.createElement('input');
        secretarySigInput.type = 'hidden';
        secretarySigInput.name = 'secretary_signature_data';
        secretarySigInput.value = secretaryImg.src;
        pdfForm.appendChild(secretarySigInput);
    }

    if (chairpersonImg && chairpersonImg.src && chairpersonImg.src.startsWith('data:')) {
        const chairpersonSigInput = document.createElement('input');
        chairpersonSigInput.type = 'hidden';
        chairpersonSigInput.name = 'chairperson_signature_data';
        chairpersonSigInput.value = chairpersonImg.src;
        pdfForm.appendChild(chairpersonSigInput);
    }

    // Add all form data as hidden inputs
    for (let [key, value] of formData.entries()) {
        if (value instanceof File) continue; // Skip file inputs
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        pdfForm.appendChild(input);
    }

    document.body.appendChild(pdfForm);
    pdfForm.submit();
    document.body.removeChild(pdfForm);
}

// Add/Remove functions for Proposed Business
function addProposedBusinessItem() {
    const container = document.getElementById('proposedBusinessContainer');
    const items = container.querySelectorAll('.proposed-business-item');
    const newIndex = items.length + 1;

    const newItem = document.createElement('div');
    newItem.className = 'form-group proposed-business-item';
    newItem.setAttribute('data-index', newIndex);
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-11">
                <label><strong>${newIndex}.</strong></label>
                <textarea class="form-control" name="proposed_business[]" rows="2" placeholder="Enter proposed business details">{{ old('proposed_business[]', $savedData['proposed_business[]'] ?? '') }}</textarea>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeProposedBusinessItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);

    // Update numbering
    updateProposedBusinessNumbering();
}

function removeProposedBusinessItem(button) {
    const container = document.getElementById('proposedBusinessContainer');
    const items = container.querySelectorAll('.proposed-business-item');

    if (items.length > 1) {
        button.closest('.proposed-business-item').remove();
        updateProposedBusinessNumbering();
    } else {
        alert('You must have at least one proposed business item.');
    }
}

function updateProposedBusinessNumbering() {
    const container = document.getElementById('proposedBusinessContainer');
    const items = container.querySelectorAll('.proposed-business-item');

    items.forEach((item, index) => {
        const newIndex = index + 1;
        item.setAttribute('data-index', newIndex);
        item.querySelector('label strong').textContent = newIndex + '.';

        // Show/hide delete button
        const deleteBtn = item.querySelector('.btn-danger');
        if (items.length > 1) {
            deleteBtn.style.display = 'block';
        } else {
            deleteBtn.style.display = 'none';
        }
    });
}

// Year range table update function - Show/Hide columns based on selected range
function updateYearColumns(tablePrefix) {
    const fromYear = parseInt(document.getElementById(`${tablePrefix}_year_from`).value);
    const toYear = parseInt(document.getElementById(`${tablePrefix}_year_to`).value);

    // Validate that fromYear is not greater than toYear
    if (fromYear > toYear) {
        alert('From Year cannot be greater than To Year');
        document.getElementById(`${tablePrefix}_year_from`).value = toYear;
        return;
    }

    // Get the table
    const table = document.getElementById(`${tablePrefix}Table`);
    if (!table) return;

    // Get all year columns
    const yearCols = table.querySelectorAll('.year-col');

    // Show/hide columns based on year range
    yearCols.forEach(col => {
        const year = parseInt(col.getAttribute('data-year'));
        if (year >= fromYear && year <= toYear) {
            col.style.display = '';
        } else {
            col.style.display = 'none';
        }
    });
}

// Initialize year columns on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tables with default year range (last 2 years)
    updateYearColumns('members');
    updateYearColumns('specialStatus');
    updateYearColumns('units');
    updateYearColumns('cgs');
    updateYearColumns('financial');

    // Make all form fields readonly if viewing past year
    @if($isViewingPastYear)
    const form = document.getElementById('annualReportForm');
    if (form) {
        // Disable all input fields
        const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea, select');
        inputs.forEach(input => {
            input.setAttribute('readonly', 'readonly');
            input.style.backgroundColor = '#f5f5f5';
            input.style.cursor = 'not-allowed';
        });

        // Disable all buttons except Download PDF
        const buttons = form.querySelectorAll('button:not([onclick*="downloadPDF"])');
        buttons.forEach(button => {
            button.setAttribute('disabled', 'disabled');
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
        });

        // Show notification
        const notification = document.createElement('div');
        notification.className = 'alert alert-info';
        notification.style.position = 'sticky';
        notification.style.top = '10px';
        notification.style.zIndex = '1000';
        notification.innerHTML = '<i class="fas fa-info-circle"></i> <strong>Read-Only Mode:</strong> You are viewing data from a past year ({{ $selectedYear }}). Only current year ({{ $currentYear }}) data can be edited.';
        form.insertBefore(notification, form.firstChild);
    }
    @endif
    updateYearColumns('capitalization');
    updateYearColumns('surplus');
    updateYearColumns('cetos');
});
</script>
@endsection
