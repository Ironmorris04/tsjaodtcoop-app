<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Social Development Program Report - TSJAODTC</title>
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
        .intro-text {
            font-size: 8pt;
            margin: 8px 0 12px 0;
            text-align: justify;
            line-height: 1.4;
            padding: 6px;
            background: #f8f9fa;
            border-left: 3px solid #34495e;
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
        .fund-info {
            font-size: 7pt;
            color: #555;
            font-style: italic;
            margin: -3px 0 6px 8px;
        }
        .section-container {
            page-break-inside: avoid; /* prevent breaking section */
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 12px 0;
            page-break-inside: avoid; /* prevent breaking table */
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
        tr {
            page-break-inside: avoid; /* prevent breaking rows */
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .empty-state {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 12px;
        }
        .photo-cell img {
            width: 100%;
            height: auto;
            display: block;
            margin-bottom: 4px;
        }
        .summary-box {
            background: #ecf0f1;
            padding: 6px 8px;
            margin: 8px 0;
            border-left: 3px solid #3498db;
            font-size: 7.5pt;
        }
        .summary-box strong {
            font-size: 8pt;
        }
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 2px solid #000;
            font-size: 7pt;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Tacloban San Jose Airport Operators and Drivers</h1>
        <h2>Transport Cooperative (TSJAODTC)</h2>
        <p>SOCIAL DEVELOPMENT PROGRAM REPORT</p>
        <p style="font-size: 7.5pt; font-weight: normal;">
            Generated on {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y - h:i A') }}
        </p>
        @if($month || $year)
            <p style="font-size: 7.5pt; font-weight: normal;">
                Filter: 
                @if($month)
                    {{ \Carbon\Carbon::createFromFormat('m', $month)->format('F') }}
                @endif
                @if($year)
                    {{ $year }}
                @endif
            </p>
        @endif
    </div>

    <!-- Introduction -->
    <div class="intro-text">
        The social development program of the cooperative focuses on two (2) areas: a) for the cooperative itself and b) for the community.
        The source of fund for the first area may vary from Cooperative Education and Training Fund (CETF), optional fund or outright expense,
        while the second area is exclusive from the Community Development Fund (CDF).
    </div>

    <!-- Section A: For Cooperative Itself -->
    <div class="section-container">
        @if($cooperativeActivities->count() > 0)
            <div class="section-title">A. For Cooperative Itself</div>
            <div class="fund-info">Source of Fund: CETF, Optional Fund or Outright Expense</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Name of Activity</th>
                        <th style="width: 12%;">Date Conducted</th>
                        <th style="width: 10%;">Participants</th>
                        <th style="width: 13%;">Amount Utilized</th>
                        <th style="width: 15%;">Source of Fund</th>
                        <th style="width: 20%;">Photos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cooperativeActivities as $index => $activity)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $activity->activity_name }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($activity->date_conducted)->format('M d, Y') }}</td>
                        <td class="text-center">{{ number_format($activity->participants_count) }}</td>
                        <td class="text-right">₱{{ number_format($activity->amount_utilized, 2) }}</td>
                        <td class="text-center">{{ $activity->fund_source }}</td>
                        <td class="photo-cell">
                            @if(!empty($activity->cached_photos) && count($activity->cached_photos) > 0)
                                @foreach($activity->cached_photos as $photo)
                                    @if($photo)
                                        <img src="{{ $photo }}" alt="Activity Photo">
                                    @endif
                                @endforeach
                            @else
                                No photos
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="section-title">A. For Cooperative Itself</div>
            <div class="fund-info">Source of Fund: CETF, Optional Fund or Outright Expense</div>
            <div class="empty-state">No cooperative activities recorded for the selected period.</div>
        @endif
    </div>

    <!-- Section B: For The Community -->
    <div class="section-container">
        @if($communityActivities->count() > 0)
            <div class="section-title">B. For The Community</div>
            <div class="fund-info">Source of Fund: CDF or Outright Expense</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Name of Activity</th>
                        <th style="width: 12%;">Date Conducted</th>
                        <th style="width: 10%;">Participants</th>
                        <th style="width: 13%;">Amount Utilized</th>
                        <th style="width: 15%;">Source of Fund</th>
                        <th style="width: 20%;">Photos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($communityActivities as $index => $activity)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $activity->activity_name }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($activity->date_conducted)->format('M d, Y') }}</td>
                        <td class="text-center">{{ number_format($activity->participants_count) }}</td>
                        <td class="text-right">₱{{ number_format($activity->amount_utilized, 2) }}</td>
                        <td class="text-center">{{ $activity->fund_source }}</td>
                        <td class="photo-cell">
                            @if(!empty($activity->cached_photos) && count($activity->cached_photos) > 0)
                                @foreach($activity->cached_photos as $photo)
                                    @if($photo)
                                        <img src="{{ $photo }}" alt="Activity Photo">
                                    @endif
                                @endforeach
                            @else
                                No photos
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="section-title">B. For The Community</div>
            <div class="fund-info">Source of Fund: CDF or Outright Expense</div>
            <div class="empty-state">No community activities recorded for the selected period.</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is a system-generated report from TSJAODTC Management System</p>
        <p>For inquiries, please contact the cooperative office</p>
    </div>

</body>
</html>