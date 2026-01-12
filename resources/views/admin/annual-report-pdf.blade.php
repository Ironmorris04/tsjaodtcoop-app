<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Annual Report - TSJAODTC</title>
    <style>
        @page {
            margin: 0.5in;
            size: A4 landscape;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
        }
        .header h1 {
            font-size: 14pt;
            margin: 0 0 5px 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .header p {
            font-size: 10pt;
            margin: 3px 0;
            font-weight: bold;
        }
        .cluster-title {
            background: #2c3e50;
            color: white;
            padding: 8px 12px;
            font-size: 11pt;
            font-weight: bold;
            margin: 15px 0 10px 0;
            text-align: center;
            text-transform: uppercase;
        }
        .section-title {
            background: #34495e;
            color: white;
            padding: 6px 10px;
            font-size: 10pt;
            font-weight: bold;
            margin: 12px 0 8px 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 15px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background: #95a5a6;
            font-weight: bold;
            text-align: center;
            padding: 6px 8px;
            font-size: 9pt;
        }
        td {
            padding: 5px 8px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .label-col {
            background: #ecf0f1;
            font-weight: bold;
            width: 35%;
        }
        .value-col {
            width: 65%;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-block {
            text-align: center;
            display: inline-block;
            width: 48%;
            vertical-align: bottom;
        }
        .signature-image {
            height: 50px;
            margin-bottom: 5px;
        }
        .signature-line {
            border-top: 2px solid #000;
            margin: 5px auto;
            width: 250px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 10pt;
            margin-top: 5px;
        }
        .signature-position {
            font-size: 9pt;
            font-style: italic;
        }
    </style>
</head>
<body>
    @php
        // Get year range from submitted data
        $yearStart = $data['year_range_start'] ?? (date('Y') - 2);
        $yearEnd = $data['year_range_end'] ?? date('Y');

        // Generate years array from the range
        $years = range($yearStart, $yearEnd);

        // Fallback: if no valid range, extract years from data
        if (empty($years)) {
            $years = [];
            foreach ($data as $key => $value) {
                if (preg_match('/drivers_(\d{4})_male/', $key, $matches)) {
                    $years[] = $matches[1];
                }
            }
            $years = array_unique($years);
            sort($years);
        }

        // Final fallback: use last 3 years
        if (empty($years)) {
            $years = range(date('Y') - 2, date('Y'));
        }
    @endphp

    <!-- PAGE 1: HEADER & CLUSTER 1 -->
    <div class="header">
        <h1>Tacloban San Jose Airport Operators and Drivers</h1>
        <h2>Transport Cooperative (TSJAODTC)</h2>
        <p>ANNUAL REPORT {{ $data['report_year'] ?? date('Y') }}</p>
    </div>

    <div class="cluster-title">CLUSTER 1: BASIC/PRIMARY INFORMATION</div>

    <table>
        <tr>
            <td class="label-col">Cooperative Name</td>
            <td class="value-col">{{ $data['tc_name'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Cooperative Address</td>
            <td class="value-col">{{ $data['business_address'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">CDA Registration Number</td>
            <td class="value-col">{{ $data['cda_registration'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Date Registered</td>
            <td class="value-col">{{ $data['cda_date_registered'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Common Bond of Membership</td>
            <td class="value-col">{{ $data['common_bond'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Contact Number</td>
            <td class="value-col">{{ $data['official_contact'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Email Address</td>
            <td class="value-col">{{ $data['official_email'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Contact Person</td>
            <td class="value-col">{{ $data['contact_person'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">TIN</td>
            <td class="value-col">{{ $data['bir_tin'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">OTC Accreditation No.</td>
            <td class="value-col">{{ $data['otc_accreditation'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Date Accredited</td>
            <td class="value-col">{{ $data['otc_date_accredited'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">Membership Fee</td>
            <td class="value-col">{{ $data['membership_fee'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">SSS Employer Registration No.</td>
            <td class="value-col">{{ $data['sss_number'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">No. of SSS Enrolled Employees</td>
            <td class="value-col">{{ $data['sss_employees'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">PAGIBIG Employer Registration No.</td>
            <td class="value-col">{{ $data['pagibig_number'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">No. of PAGIBIG Enrolled Employees</td>
            <td class="value-col">{{ $data['pagibig_employees'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">PHILHEALTH Employer Registration No.</td>
            <td class="value-col">{{ $data['philhealth_number'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">No. of PHILHEALTH Enrolled Employees</td>
            <td class="value-col">{{ $data['philhealth_employees'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">BIR Tax Exemption Number</td>
            <td class="value-col">{{ $data['bir_exemption_number'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label-col">BIR Tax Exemption Validity</td>
            <td class="value-col">{{ $data['bir_exemption_validity'] ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- PAGE 2: CLUSTER 2 - MEMBERSHIP PROFILE -->
    <div class="cluster-title">CLUSTER 2: MEMBERSHIP</div>

    <div class="section-title">Number of Members</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Type/Status</th>
                @foreach($years as $year)
                    <th colspan="2">{{ $year }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($years as $year)
                    <th>Male</th>
                    <th>Female</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Drivers</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['drivers_' . $year . '_male'] ?? '0' }}</td>
                    <td class="text-center">{{ $data['drivers_' . $year . '_female'] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Member-Operator</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['operator_' . $year . '_male'] ?? '0' }}</td>
                    <td class="text-center">{{ $data['operator_' . $year . '_female'] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Allied Workers</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['allied_' . $year . '_male'] ?? '0' }}</td>
                    <td class="text-center">{{ $data['allied_' . $year . '_female'] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Others</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['others_' . $year . '_male'] ?? '0' }}</td>
                    <td class="text-center">{{ $data['others_' . $year . '_female'] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>TOTAL</strong></td>
                @foreach($years as $year)
                    @php
                        $totalMale = ($data['drivers_' . $year . '_male'] ?? 0) +
                                    ($data['operator_' . $year . '_male'] ?? 0) +
                                    ($data['allied_' . $year . '_male'] ?? 0) +
                                    ($data['others_' . $year . '_male'] ?? 0);
                        $totalFemale = ($data['drivers_' . $year . '_female'] ?? 0) +
                                      ($data['operator_' . $year . '_female'] ?? 0) +
                                      ($data['allied_' . $year . '_female'] ?? 0) +
                                      ($data['others_' . $year . '_female'] ?? 0);
                    @endphp
                    <td class="text-center"><strong>{{ $totalMale }}</strong></td>
                    <td class="text-center"><strong>{{ $totalFemale }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="section-title">Status of Employment</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Type of Employees</th>
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
                <td><strong>Drivers</strong></td>
                <td class="text-center">{{ $data['emp_drivers_prob_male'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_drivers_prob_female'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_drivers_reg_male'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_drivers_reg_female'] ?? '0' }}</td>
            </tr>
            <tr>
                <td><strong>Management Staff</strong></td>
                <td class="text-center">{{ $data['emp_management_prob_male'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_management_prob_female'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_management_reg_male'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_management_reg_female'] ?? '0' }}</td>
            </tr>
            <tr>
                <td><strong>Allied Workers</strong></td>
                <td class="text-center">{{ $data['emp_allied_prob_male'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_allied_prob_female'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_allied_reg_male'] ?? '0' }}</td>
                <td class="text-center">{{ $data['emp_allied_reg_female'] ?? '0' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Special Status Categories</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                @foreach($years as $year)
                    <th class="text-center">{{ $year }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>PWD (Person with Disability)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['pwd_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Senior Citizen</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['senior_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>TOTAL</strong></td>
                @foreach($years as $year)
                    @php
                        $specialTotal = ($data['pwd_' . $year] ?? 0) + ($data['senior_' . $year] ?? 0);
                    @endphp
                    <td class="text-center"><strong>{{ $specialTotal }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- PAGE 3: CLUSTER 3 - UNITS AND FRANCHISE -->
    <div class="cluster-title">CLUSTER 3: UNITS AND FRANCHISE</div>

    <div class="section-title">Units by Type and Ownership</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Type of Units</th>
                @foreach($years as $year)
                    <th colspan="2" class="text-center">{{ $year }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($years as $year)
                    <th>Cooperatively Owned Units</th>
                    <th>Individually Owned Units</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>PUJ (Traditional)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['puj_trad_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['puj_trad_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>MPUV Class 1 (EURO)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['mpuv1_euro_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['mpuv1_euro_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>MPUV Class 1 (Electric)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['mpuv1_elec_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['mpuv1_elec_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>MPUV Class 2 (EURO)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['mpuv2_euro_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['mpuv2_euro_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>MPUV Class 2 (Electric)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['mpuv2_elec_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['mpuv2_elec_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>UV Express (Traditional)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['uv_trad_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['uv_trad_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>MPUV Class 3 (EURO)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['mpuv3_euro_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['mpuv3_euro_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>MPUV Class 3 (Electric)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['mpuv3_elec_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['mpuv3_elec_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>PUV Class 4 (Modernized)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['puv4_mod_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['puv4_mod_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>MPUV Class 4 (Electric)</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['mpuv4_elec_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['mpuv4_elec_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Tourist Service</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['tourist_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">0</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Taxi</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['taxi_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['taxi_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Multicab/Filcab</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['multicab_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['multicab_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Mini Bus</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['minibus_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['minibus_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Bus</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['bus_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['bus_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Tricycle / MCH</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['tricycle_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['tricycle_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Truck</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['truck_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['truck_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Banca</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['banca_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['banca_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Shuttle Service</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['shuttle_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['shuttle_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>{{ $data['other_unit_type'] ?? 'Others' }}</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['other_coop_' . $year] ?? '0' }}</td>
                    <td class="text-center">{{ $data['other_member_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>TOTAL UNITS</strong></td>
                @foreach($years as $year)
                    @php
                        $totalCoop = ($data['puj_trad_coop_' . $year] ?? 0) +
                                    ($data['mpuv1_euro_coop_' . $year] ?? 0) +
                                    ($data['mpuv1_elec_coop_' . $year] ?? 0) +
                                    ($data['mpuv2_euro_coop_' . $year] ?? 0) +
                                    ($data['mpuv2_elec_coop_' . $year] ?? 0) +
                                    ($data['uv_trad_coop_' . $year] ?? 0) +
                                    ($data['mpuv3_euro_coop_' . $year] ?? 0) +
                                    ($data['mpuv3_elec_coop_' . $year] ?? 0) +
                                    ($data['puv4_mod_coop_' . $year] ?? 0) +
                                    ($data['mpuv4_elec_coop_' . $year] ?? 0) +
                                    ($data['tourist_coop_' . $year] ?? 0) +
                                    ($data['taxi_coop_' . $year] ?? 0) +
                                    ($data['multicab_coop_' . $year] ?? 0) +
                                    ($data['minibus_coop_' . $year] ?? 0) +
                                    ($data['bus_coop_' . $year] ?? 0) +
                                    ($data['tricycle_coop_' . $year] ?? 0) +
                                    ($data['truck_coop_' . $year] ?? 0) +
                                    ($data['banca_coop_' . $year] ?? 0) +
                                    ($data['shuttle_coop_' . $year] ?? 0) +
                                    ($data['other_coop_' . $year] ?? 0);
                        $totalMember = ($data['puj_trad_member_' . $year] ?? 0) +
                                      ($data['mpuv1_euro_member_' . $year] ?? 0) +
                                      ($data['mpuv1_elec_member_' . $year] ?? 0) +
                                      ($data['mpuv2_euro_member_' . $year] ?? 0) +
                                      ($data['mpuv2_elec_member_' . $year] ?? 0) +
                                      ($data['uv_trad_member_' . $year] ?? 0) +
                                      ($data['mpuv3_euro_member_' . $year] ?? 0) +
                                      ($data['mpuv3_elec_member_' . $year] ?? 0) +
                                      ($data['puv4_mod_member_' . $year] ?? 0) +
                                      ($data['mpuv4_elec_member_' . $year] ?? 0) +
                                      ($data['taxi_member_' . $year] ?? 0) +
                                      ($data['multicab_member_' . $year] ?? 0) +
                                      ($data['minibus_member_' . $year] ?? 0) +
                                      ($data['bus_member_' . $year] ?? 0) +
                                      ($data['tricycle_member_' . $year] ?? 0) +
                                      ($data['truck_member_' . $year] ?? 0) +
                                      ($data['banca_member_' . $year] ?? 0) +
                                      ($data['shuttle_member_' . $year] ?? 0) +
                                      ($data['other_member_' . $year] ?? 0);
                    @endphp
                    <td class="text-center"><strong>{{ $totalCoop }}</strong></td>
                    <td class="text-center"><strong>{{ $totalMember }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="section-title">Franchise</div>
    <table>
        <thead>
            <tr>
                <th width="25%">Route/s</th>
                <th width="10%">No. of Units</th>
                <th width="20%">CPC Case Number (Franchise or P.A.)</th>
                <th width="20%">Type of Unit</th>
                <th width="10%">Validity</th>
                <th width="15%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $franchiseCount = 0;
                if (isset($data['franchise_route'])) {
                    if (is_array($data['franchise_route'])) {
                        $franchiseCount = count($data['franchise_route']);
                    }
                }
            @endphp
            @if($franchiseCount > 0)
                @foreach($data['franchise_route'] as $index => $route)
                    @if($route)
                    <tr>
                        <td>{{ $route }}</td>
                        <td class="text-center">{{ $data['franchise_units'][$index] ?? '0' }}</td>
                        <td>{{ $data['franchise_case'][$index] ?? 'N/A' }}</td>
                        <td>{{ $data['franchise_unit_type'][$index] ?? 'N/A' }}</td>
                        <td class="text-center">{{ $data['franchise_validity'][$index] ?? 'N/A' }}</td>
                        <td>{{ $data['franchise_remarks'][$index] ?? 'N/A' }}</td>
                    </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No franchise information available</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ $data['franchise_total_units'] ?? '0' }}</strong></td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    <div class="page-break"></div>

    <!-- PAGE 4: CLUSTER 4 - ORGANIZATIONAL STRUCTURE -->
    <div class="cluster-title">CLUSTER 4: GOVERNANCE</div>

    <div class="section-title">CGS Acquisition</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Type</th>
                @foreach($years as $year)
                    <th colspan="3" class="text-center">{{ $year }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($years as $year)
                    <th>CGS No.</th>
                    <th>Date Issued</th>
                    <th>Expiration</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>CGS Details</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['cgs_no_' . $year] ?? 'N/A' }}</td>
                    <td class="text-center">{{ $data['cgs_date_issued_' . $year] ?? 'N/A' }}</td>
                    <td class="text-center">{{ $data['cgs_expiration_' . $year] ?? 'N/A' }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="section-title">Executive Officers</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'chairperson' => 'Chairperson',
                'vice_chairperson' => 'Vice Chairperson',
                'secretary' => 'Secretary',
                'treasurer' => 'Treasurer',
                'general_manager' => 'General Manager',
                'bookkeeper' => 'Bookkeeper'
            ] as $key => $title)
                <tr>
                    <td><strong>{{ $title }}</strong></td>
                    @if(isset($data['officers']['singleOfficers'][$key]) && $data['officers']['singleOfficers'][$key])
                        @php
                            $officer = $data['officers']['singleOfficers'][$key];
                        @endphp
                        <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                        <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                        <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                    @else
                        <td>N/A</td>
                        <td class="text-center">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

        <div class="page-break"></div>

    <div class="section-title">Board of Directors</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data['officers']['boardOfDirectors']) && $data['officers']['boardOfDirectors']->count() > 0)
                @foreach($data['officers']['boardOfDirectors'] as $officer)
                <tr>
                    <td><strong>{{ str_replace('_', ' ', ucwords($officer->position, '_')) }}</strong></td>
                    <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                    <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                    <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No Board Members Assigned</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- PAGE 5: COMMITTEES -->
    <div class="section-title">Audit Committee</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                <tr>
                    <td><strong>{{ $title }}</strong></td>
                    @if(isset($data['officers']['auditCommittee'][$key]) && $data['officers']['auditCommittee'][$key])
                        @php
                            $officer = $data['officers']['auditCommittee'][$key];
                        @endphp
                        <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                        <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                        <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                    @else
                        <td>N/A</td>
                        <td class="text-center">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Election Committee</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                <tr>
                    <td><strong>{{ $title }}</strong></td>
                    @if(isset($data['officers']['electionCommittee'][$key]) && $data['officers']['electionCommittee'][$key])
                        @php
                            $officer = $data['officers']['electionCommittee'][$key];
                        @endphp
                        <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                        <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                        <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                    @else
                        <td>N/A</td>
                        <td class="text-center">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Mediation & Conciliation Committee</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                <tr>
                    <td><strong>{{ $title }}</strong></td>
                    @if(isset($data['officers']['mediationCommittee'][$key]) && $data['officers']['mediationCommittee'][$key])
                        @php
                            $officer = $data['officers']['mediationCommittee'][$key];
                        @endphp
                        <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                        <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                        <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                    @else
                        <td>N/A</td>
                        <td class="text-center">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Ethics Committee</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                <tr>
                    <td><strong>{{ $title }}</strong></td>
                    @if(isset($data['officers']['ethicsCommittee'][$key]) && $data['officers']['ethicsCommittee'][$key])
                        @php
                            $officer = $data['officers']['ethicsCommittee'][$key];
                        @endphp
                        <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                        <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                        <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                    @else
                        <td>N/A</td>
                        <td class="text-center">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Gender & Development Committee</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                <tr>
                    <td><strong>{{ $title }}</strong></td>
                    @if(isset($data['officers']['genderCommittee'][$key]) && $data['officers']['genderCommittee'][$key])
                        @php
                            $officer = $data['officers']['genderCommittee'][$key];
                        @endphp
                        <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                        <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                        <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                    @else
                        <td>N/A</td>
                        <td class="text-center">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Education Committee</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Position</th>
                <th width="25%">Name</th>
                <th width="15%">Term</th>
                <th width="18%">Mobile Number</th>
                <th width="22%">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                <tr>
                    <td><strong>{{ $title }}</strong></td>
                    @if(isset($data['officers']['educationCommittee'][$key]) && $data['officers']['educationCommittee'][$key])
                        @php
                            $officer = $data['officers']['educationCommittee'][$key];
                        @endphp
                        <td>{{ $officer->operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $officer->effective_from->format('M d, Y') }} - {{ $officer->effective_to->format('M d, Y') }}</td>
                        <td>{{ $officer->operator->phone ?? 'N/A' }}</td>
                        <td>{{ $officer->operator->user->email ?? 'N/A' }}</td>
                    @else
                        <td>N/A</td>
                        <td class="text-center">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- PAGE 6: CLUSTER 5 - FINANCIAL POSITION -->
    <div class="cluster-title">CLUSTER 5: FINANCIAL AND BUSINESS ASPECT</div>

    <div class="section-title">Financial Aspect</div>
    <table>
        <thead>
            <tr>
                <th width="40%">Financial Item</th>
                @foreach($years as $year)
                    <th width="{{ 60 / count($years) }}%" class="text-center">{{ $year }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Current Assets</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['current_assets_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Non-Current Assets</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['non_current_assets_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Total Assets</strong></td>
                @foreach($years as $year)
                    <td class="text-right"><strong>{{ $data['total_assets_' . $year] ?? '0.00' }}</strong></td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Liabilities</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['liabilities_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Members Equity</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['members_equity_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Total Gross Revenues</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['total_revenues_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Total Expenses</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['total_expenses_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Net Surplus/Loss</strong></td>
                @foreach($years as $year)
                    <td class="text-right"><strong>{{ $data['net_surplus_' . $year] ?? '0.00' }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="section-title">Capitalization</div>
    <table>
        <thead>
            <tr>
                <th width="40%">Capitalization Item</th>
                @foreach($years as $year)
                    <th width="{{ 60 / count($years) }}%" class="text-center">{{ $year }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Initial Authorized Capital Stock</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['init_auth_capital_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Present Authorized Capital Stock</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['pres_auth_capital_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Subscribed Capital</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['subscribed_capital_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Present Paid-up Capital</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['paid_up_capital_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Capital Buildup Program Scheme</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['capital_buildup_' . $year] ?? 'N/A' }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">Distribution of Net Surplus</div>
    <table>
        <thead>
            <tr>
                <th width="40%">Distribution Category</th>
                @foreach($years as $year)
                    <th width="{{ 60 / count($years) }}%" class="text-center">{{ $year }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>General Reserve Fund</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['surplus_reserve_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Education & Training Program</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['surplus_education_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Community Development Fund</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['surplus_community_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Optional Fund</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['surplus_optional_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Interest on Share Capital</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['surplus_interest_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Patronage Refund</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['surplus_patronage_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>{{ $data['surplus_others_label'] ?? 'Others' }}</strong></td>
                @foreach($years as $year)
                    <td class="text-right">{{ $data['surplus_others_' . $year] ?? '0.00' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>TOTAL</strong></td>
                @foreach($years as $year)
                    @php
                        $surplusTotal = ($data['surplus_reserve_' . $year] ?? 0) +
                                       ($data['surplus_education_' . $year] ?? 0) +
                                       ($data['surplus_community_' . $year] ?? 0) +
                                       ($data['surplus_optional_' . $year] ?? 0) +
                                       ($data['surplus_interest_' . $year] ?? 0) +
                                       ($data['surplus_patronage_' . $year] ?? 0) +
                                       ($data['surplus_others_' . $year] ?? 0);
                    @endphp
                    <td class="text-right"><strong>{{ number_format($surplusTotal, 2) }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="section-title">Grants/Donation</div>
    <table>
        <thead>
            <tr>
                <th width="15%">Date Acquired</th>
                <th width="20%">Amount</th>
                <th width="30%">Source</th>
                <th width="35%">Status/Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grantCount = 0;
                if (isset($data['grant_date']) && is_array($data['grant_date'])) {
                    $grantCount = count($data['grant_date']);
                }
            @endphp
            @if($grantCount > 0)
                @foreach($data['grant_date'] as $index => $date)
                    @if($date || ($data['grant_amount'][$index] ?? '') || ($data['grant_source'][$index] ?? ''))
                    <tr>
                        <td class="text-center">{{ $date ?? 'N/A' }}</td>
                        <td class="text-right">{{ isset($data['grant_amount'][$index]) ? number_format($data['grant_amount'][$index], 2) : 'N/A' }}</td>
                        <td>{{ $data['grant_source'][$index] ?? 'N/A' }}</td>
                        <td>{{ $data['grant_status'][$index] ?? 'N/A' }}</td>
                    </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">No grants or donations</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- PAGE 7: CLUSTER 6 - CAPACITY/CAPABILITY BUILDING PROGRAM -->
    <div class="cluster-title">CLUSTER 6: CAPACITY/CAPABILITY BUILDING PROGRAM</div>

    <div class="section-title">CETOS Monitoring</div>
    <table>
        <thead>
            <tr>
                <th width="40%">CETOS Status</th>
                @foreach($years as $year)
                    <th width="{{ 60 / count($years) }}%" class="text-center">{{ $year }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>With CETOS</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['cetos_with_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Without CETOS</strong></td>
                @foreach($years as $year)
                    <td class="text-center">{{ $data['cetos_without_' . $year] ?? '0' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>TOTAL</strong></td>
                @foreach($years as $year)
                    @php
                        $cetosTotal = ($data['cetos_with_' . $year] ?? 0) + ($data['cetos_without_' . $year] ?? 0);
                    @endphp
                    <td class="text-center"><strong>{{ $cetosTotal }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="section-title">Training/Seminars</div>
    <table>
        <thead>
            <tr>
                <th width="45%">Title</th>
                <th width="30%">Date</th>
                <th width="25%">No. of Attendees</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Fleet Management Seminar</strong></td>
                <td class="text-center">{{ $data['training_fleet_date'] ?? 'N/A' }}</td>
                <td class="text-center">{{ $data['training_fleet_attendees'] ?? '0' }}</td>
            </tr>
            <tr>
                <td><strong>Financial Management Seminar</strong></td>
                <td class="text-center">{{ $data['training_financial_date'] ?? 'N/A' }}</td>
                <td class="text-center">{{ $data['training_financial_attendees'] ?? '0' }}</td>
            </tr>
            <tr>
                <td><strong>Cooperative Management & Good Governance</strong></td>
                <td class="text-center">{{ $data['training_coop_mgmt_date'] ?? 'N/A' }}</td>
                <td class="text-center">{{ $data['training_coop_mgmt_attendees'] ?? '0' }}</td>
            </tr>
            <tr>
                <td><strong>Leadership & Values Orientation</strong></td>
                <td class="text-center">{{ $data['training_leadership_date'] ?? 'N/A' }}</td>
                <td class="text-center">{{ $data['training_leadership_attendees'] ?? '0' }}</td>
            </tr>
            <tr>
                <td><strong>Labor Laws</strong></td>
                <td class="text-center">{{ $data['training_labor_date'] ?? 'N/A' }}</td>
                <td class="text-center">{{ $data['training_labor_attendees'] ?? '0' }}</td>
            </tr>
            <tr>
                <td><strong>Gender & Development (GAD)</strong></td>
                <td class="text-center">{{ $data['training_gad_date'] ?? 'N/A' }}</td>
                <td class="text-center">{{ $data['training_gad_attendees'] ?? '0' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- PAGE 8: CLUSTER 7 - OTHER RELATED INFORMATION -->
    <div class="cluster-title">CLUSTER 7: OTHER RELATED INFORMATION</div>

    <div class="section-title">Articles of Cooperative and By-Laws Amendments</div>
    <table>
        <thead>
            <tr>
                <th width="50%">Amendment Type</th>
                <th width="50%">Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Amendment to Articles of Cooperative</strong></td>
                <td>{{ $data['articles_amendment'] ?? 'No amendments' }}</td>
            </tr>
            <tr>
                <td><strong>Amendment to By-Laws</strong></td>
                <td>{{ $data['bylaws_amendment'] ?? 'No amendments' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Awards and Recognition</div>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="30%">Award/Recognition</th>
                <th width="25%">Awarding Body</th>
                <th width="20%">Date Received</th>
                <th width="20%">Level</th>
            </tr>
        </thead>
        <tbody>
            @php
                $awardCount = 0;
                if (isset($data['award_name']) && is_array($data['award_name'])) {
                    $awardCount = count($data['award_name']);
                }
            @endphp
            @if($awardCount > 0)
                @foreach($data['award_name'] as $index => $name)
                    @if($name)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $data['award_body'][$index] ?? 'N/A' }}</td>
                        <td class="text-center">{{ $data['award_date'][$index] ?? 'N/A' }}</td>
                        <td class="text-center">{{ $data['award_level'][$index] ?? 'N/A' }}</td>
                    </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No awards or recognition</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- SIGNATURE SECTION -->
    <div style="margin-top: 40px;">
        <table style="border: none; width: 100%;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; text-align: center; vertical-align: bottom; padding: 20px;">
                    @if(isset($data['secretary_signature_data']) && $data['secretary_signature_data'])
                        <div style="margin-bottom: 5px;">
                            <img src="{{ $data['secretary_signature_data'] }}" style="max-width: 180px; height: 50px;" alt="Secretary Signature">
                        </div>
                    @else
                        <div style="height: 50px;"></div>
                    @endif
                    <div style="margin: 10px auto; width: 250px;">
                        <strong style="text-decoration: underline;">{{ $data['secretary_name'] ?? 'Secretary Name' }}</strong>
                    </div>
                    <div style="font-size: 9pt; margin-top: 3px;">Secretary</div>
                </td>
                <td style="border: none; width: 50%; text-align: center; vertical-align: bottom; padding: 20px;">
                    @if(isset($data['chairperson_signature_data']) && $data['chairperson_signature_data'])
                        <div style="margin-bottom: 5px;">
                            <img src="{{ $data['chairperson_signature_data'] }}" style="max-width: 180px; height: 50px;" alt="Chairperson Signature">
                        </div>
                    @else
                        <div style="height: 50px;"></div>
                    @endif
                    <div style="margin: 10px auto; width: 250px;">
                        <strong style="text-decoration: underline;">{{ $data['chairperson_name'] ?? 'Chairperson Name' }}</strong>
                    </div>
                    <div style="font-size: 9pt; margin-top: 3px;">Chairperson, Board of Directors</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
