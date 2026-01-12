<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>General Information - {{ $generalInfo->cooperative_name ?? 'Transport Cooperative' }}</title>
    <style>
        @page {
            size: A4;
            margin-top: 1in;
            margin-right: 1in;
            margin-bottom: 1in;
            margin-left: 1in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
            background: white;
            padding: .2in .5in;
        }

        .pdf-container {
            width: 100%;
            background: white;
        }

        .pdf-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
        }

        .pdf-header h1 {
            font-size: 16pt;
            color: #000;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .pdf-header p {
            font-size: 11pt;
            color: #000;
            font-weight: bold;
        }

        .section-header {
            background-color: #d3d3d3;
            padding: 6px 8px;
            margin-bottom: 0;
            border: 1px solid #000;
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .field-label {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .field-value {
            font-size: 9pt;
            min-height: 18px;
            padding-top: 2px;
        }

        .field-value:empty::before {
            content: "";
            color: #fff;
        }

        .colspan-2 {
            width: 50%;
        }

        .colspan-3 {
            width: 33.33%;
        }

        .colspan-4 {
            width: 25%;
        }

        .full-width {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        <div class="pdf-header">
            <h1>{{ $generalInfo->cooperative_name ?? 'Transport Cooperative' }}</h1>
            <p>General Information Form</p>
        </div>

        <!-- Cooperative Information -->
        <div class="section-header">COOPERATIVE INFORMATION</div>
        <table>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Registration No.</div>
                    <div class="field-value">{{ $generalInfo->registration_no }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Name of Cooperative</div>
                    <div class="field-value">{{ $generalInfo->cooperative_name }}</div>
                </td>
            </tr>
        </table>

        <!-- Registered Address -->
        <div class="section-header">REGISTERED ADDRESS OF COOPERATIVE</div>
        <table>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Region</div>
                    <div class="field-value">{{ $generalInfo->reg_region }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Province</div>
                    <div class="field-value">{{ $generalInfo->reg_province }}</div>
                </td>
            </tr>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Municipality/City</div>
                    <div class="field-value">{{ $generalInfo->reg_municipality_city }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Barangay</div>
                    <div class="field-value">{{ $generalInfo->reg_barangay }}</div>
                </td>
            </tr>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Street</div>
                    <div class="field-value">{{ $generalInfo->reg_street }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">House/Lot & Blk No.</div>
                    <div class="field-value">{{ $generalInfo->reg_house_lot_blk_no }}</div>
                </td>
            </tr>
        </table>

        <!-- Present Address -->
        <div class="section-header">PRESENT ADDRESS OF COOPERATIVE</div>
        <table>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Region</div>
                    <div class="field-value">{{ $generalInfo->present_region }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Province</div>
                    <div class="field-value">{{ $generalInfo->present_province }}</div>
                </td>
            </tr>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Municipality/City</div>
                    <div class="field-value">{{ $generalInfo->present_municipality_city }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Barangay</div>
                    <div class="field-value">{{ $generalInfo->present_barangay }}</div>
                </td>
            </tr>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Street</div>
                    <div class="field-value">{{ $generalInfo->present_street }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">House/Lot & Blk No.</div>
                    <div class="field-value">{{ $generalInfo->present_house_lot_blk_no }}</div>
                </td>
            </tr>
        </table>

        <!-- Date Registered -->
        <div class="section-header">DATE REGISTERED</div>
        <table>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Date of Registration Prior to RA 9520</div>
                    <div class="field-value">{{ $generalInfo->date_registration_prior_ra9520?->format('F d, Y') }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Date of Registration Under RA 9520</div>
                    <div class="field-value">{{ $generalInfo->date_registration_under_ra9520?->format('F d, Y') }}</div>
                </td>
            </tr>
        </table>

        <!-- Business Permit -->
        <div class="section-header">BUSINESS PERMIT</div>
        <table>
            <tr>
                <td class="colspan-3">
                    <div class="field-label">Business Permit No.</div>
                    <div class="field-value">{{ $generalInfo->business_permit_no }}</div>
                </td>
                <td class="colspan-3">
                    <div class="field-label">Date Issued</div>
                    <div class="field-value">{{ $generalInfo->business_permit_date_issued?->format('F d, Y') }}</div>
                </td>
                <td class="colspan-3">
                    <div class="field-label">Amount Paid</div>
                    <div class="field-value">
                        @if($generalInfo->business_permit_amount_paid)
                            Php{{ number_format($generalInfo->business_permit_amount_paid, 2) }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Tax Identification Number -->
        <div class="section-header">TAX IDENTIFICATION NUMBER</div>
        <table>
            <tr>
                <td class="full-width">
                    <div class="field-label">TIN</div>
                    <div class="field-value">{{ $generalInfo->tax_identification_number }}</div>
                </td>
            </tr>
        </table>

        <!-- Classification -->
        <div class="section-header">CLASSIFICATION</div>
        <table>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Category of Cooperative</div>
                    <div class="field-value">{{ $generalInfo->category_of_cooperative }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Type of Cooperative</div>
                    <div class="field-value">{{ $generalInfo->type_of_cooperative }}</div>
                </td>
            </tr>
        </table>

        <!-- Additional Information -->
        <div class="section-header">ADDITIONAL INFORMATION</div>
        <table>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Asset Size of Cooperative</div>
                    <div class="field-value">{{ $generalInfo->asset_size }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Common Bond of Membership</div>
                    <div class="field-value">{{ $generalInfo->common_bond_membership }}</div>
                </td>
            </tr>
            <tr>
                <td class="colspan-2">
                    <div class="field-label">Date of General Assembly</div>
                    <div class="field-value">{{ $generalInfo->date_of_general_assembly?->format('F d, Y') }}</div>
                </td>
                <td class="colspan-2">
                    <div class="field-label">Area of Operation</div>
                    <div class="field-value">{{ $generalInfo->area_of_operation }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
