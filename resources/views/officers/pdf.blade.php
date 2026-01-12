<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Officers List - TSJAODTC</title>
    <style>
        @page {
            margin: 0.4in;
            size: A4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7.5pt;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 3px solid #000;
        }
        .header h1 {
            font-size: 13pt;
            margin: 0 0 4px 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 11pt;
            margin: 0 0 4px 0;
            font-weight: bold;
        }
        .header p {
            font-size: 9pt;
            margin: 2px 0;
            font-weight: bold;
        }
        .section-title {
            background: #34495e;
            color: white;
            padding: 5px 8px;
            font-size: 9pt;
            font-weight: bold;
            margin: 10px 0 6px 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 12px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background: #95a5a6;
            font-weight: bold;
            text-align: center;
            padding: 4px 3px;
            font-size: 7.5pt;
            line-height: 1.3;
        }
        td {
            padding: 4px 3px;
            font-size: 7pt;
            vertical-align: middle;
            line-height: 1.2;
        }
        .label-col {
            background: #ecf0f1;
            font-weight: bold;
            width: 16%;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .empty-position {
            color: #999;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Tacloban San Jose Airport Operators and Drivers</h1>
        <h2>Transport Cooperative (TSJAODTC)</h2>
        <p>OFFICERS LIST</p>
        <p style="font-size: 7.5pt; font-weight: normal;">Generated on {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y - h:i A') }}</p>
    </div>

    <!-- Executive Officers -->
    @if($singleOfficers['chairperson'] || $singleOfficers['vice_chairperson'] || $singleOfficers['secretary'] || $singleOfficers['treasurer'])
    <div class="section-title">Executive Officers</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'chairperson' => 'Chairperson',
                'vice_chairperson' => 'Vice Chairperson',
                'secretary' => 'Secretary',
                'treasurer' => 'Treasurer'
            ] as $key => $title)
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    @if($singleOfficers[$key])
                        @php
                            $operator = $singleOfficers[$key]->operator;
                            $detail = $operator->detail;
                        @endphp
                        <td>{{ $operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                        <td>{{ $operator->address ?? 'N/A' }}</td>
                        <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                        <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                        <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                    @else
                        <td class="empty-position" colspan="6">Position Vacant</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Other Officers -->
    @if($singleOfficers['general_manager'] || $singleOfficers['bookkeeper'])
    <div class="section-title">Other Officers</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'general_manager' => 'General Manager',
                'bookkeeper' => 'Bookkeeper'
            ] as $key => $title)
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    @if($singleOfficers[$key])
                        @php
                            $operator = $singleOfficers[$key]->operator;
                            $detail = $operator->detail;
                        @endphp
                        <td>{{ $operator->full_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                        <td>{{ $operator->address ?? 'N/A' }}</td>
                        <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                        <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                        <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                    @else
                        <td class="empty-position" colspan="6">Position Vacant</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Board of Directors -->
    @if($boardOfDirectors->count() > 0)
    <div class="section-title">Board of Directors</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boardOfDirectors as $officer)
            @php
                $operator = $officer->operator;
                $detail = $operator->detail;
            @endphp
            <tr>
                <td class="label-col">{{ str_replace('_', ' ', ucwords($officer->position, '_')) }}</td>
                <td>{{ $operator->full_name ?? 'N/A' }}</td>
                <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                <td>{{ $operator->address ?? 'N/A' }}</td>
                <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Audit Committee -->
    @if($auditCommittee['chairperson'] || $auditCommittee['vice_chairperson'] || $auditCommittee['secretary'] || $auditCommittee['member'])
    <div class="section-title">Audit Committee</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'vice_chairperson' => 'Vice Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                @if($auditCommittee[$key])
                @php
                    $operator = $auditCommittee[$key]->operator;
                    $detail = $operator->detail;
                @endphp
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    <td>{{ $operator->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                    <td>{{ $operator->address ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Election Committee -->
    @if($electionCommittee['chairperson'] || $electionCommittee['vice_chairperson'] || $electionCommittee['secretary'] || $electionCommittee['member'])
    <div class="section-title">Election Committee</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'vice_chairperson' => 'Vice Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                @if($electionCommittee[$key])
                @php
                    $operator = $electionCommittee[$key]->operator;
                    $detail = $operator->detail;
                @endphp
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    <td>{{ $operator->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                    <td>{{ $operator->address ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Mediation Committee -->
    @if($mediationCommittee['chairperson'] || $mediationCommittee['vice_chairperson'] || $mediationCommittee['secretary'] || $mediationCommittee['member'])
    <div class="section-title">Mediation & Conciliation Committee</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'vice_chairperson' => 'Vice Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                @if($mediationCommittee[$key])
                @php
                    $operator = $mediationCommittee[$key]->operator;
                    $detail = $operator->detail;
                @endphp
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    <td>{{ $operator->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                    <td>{{ $operator->address ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Ethics Committee -->
    @if($ethicsCommittee['chairperson'] || $ethicsCommittee['vice_chairperson'] || $ethicsCommittee['secretary'] || $ethicsCommittee['member'])
    <div class="section-title">Ethics Committee</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'vice_chairperson' => 'Vice Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                @if($ethicsCommittee[$key])
                @php
                    $operator = $ethicsCommittee[$key]->operator;
                    $detail = $operator->detail;
                @endphp
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    <td>{{ $operator->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                    <td>{{ $operator->address ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Gender & Development Committee -->
    @if($genderCommittee['chairperson'] || $genderCommittee['vice_chairperson'] || $genderCommittee['secretary'] || $genderCommittee['member'])
    <div class="section-title">Gender & Development Committee</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'vice_chairperson' => 'Vice Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                @if($genderCommittee[$key])
                @php
                    $operator = $genderCommittee[$key]->operator;
                    $detail = $operator->detail;
                @endphp
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    <td>{{ $operator->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                    <td>{{ $operator->address ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Education Committee -->
    @if($educationCommittee['chairperson'] || $educationCommittee['vice_chairperson'] || $educationCommittee['secretary'] || $educationCommittee['member'])
    <div class="section-title">Education Committee</div>
    <table>
        <thead>
            <tr>
                <th style="width: 16%;">Position</th>
                <th style="width: 18%;">Name</th>
                <th style="width: 8%;">Gender</th>
                <th style="width: 28%;">Address</th>
                <th style="width: 10%;">Indigenous People</th>
                <th style="width: 10%;">Persons with Disability</th>
                <th style="width: 10%;">Senior Citizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['chairperson' => 'Chairperson', 'vice_chairperson' => 'Vice Chairperson', 'secretary' => 'Secretary', 'member' => 'Member'] as $key => $title)
                @if($educationCommittee[$key])
                @php
                    $operator = $educationCommittee[$key]->operator;
                    $detail = $operator->detail;
                @endphp
                <tr>
                    <td class="label-col">{{ $title }}</td>
                    <td>{{ $operator->full_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ strtolower($detail->sex ?? '') === 'male' || strtolower($detail->sex ?? '') === 'm' ? 'Male' : 'Female' }}</td>
                    <td>{{ $operator->address ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail && $detail->isIndigenousPeople() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isPwd() ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $detail && $detail->isSeniorCitizen() ? 'Yes' : 'No' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

</body>
</html>