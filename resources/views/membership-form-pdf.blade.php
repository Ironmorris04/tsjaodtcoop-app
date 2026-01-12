<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TSJAODTC Membership Application Form</title>
    <style>
        @page {
            margin: 20mm 25mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.0;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        .header .coop-name {
            font-size: 9.5pt;
            font-weight: normal;
            margin: 0;
            padding-bottom: 5px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .header .label {
            font-size: 9.5pt;
            margin: 1px 0;
            padding: 0;
        }
        .header .address {
            font-size: 9.5pt;
            font-weight: normal;
            margin: 2px 0 0 0;
            padding-top: 10px;
            padding-bottom: 5px;
            text-decoration: underline;
        }
        .title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 8px 0 6px 0;
            text-transform: uppercase;
        }
        .content-text {
            font-size: 12pt;
            margin: 4px 0;
            text-align: justify;
            line-height: 1.0;
            text-indent: 40px;
        }
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 100px;
            padding: 2px 4px;
            text-align: center;
        }
        .signature-section {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: right;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            width: 220px;
            margin: 0 0 0 auto;
            height: 20px;
            padding-top: o;
        }
        .signature-label {
            text-align: right;
            font-size: 12pt;
            margin-top: 3px;
            padding-right: 30px;
        }
        .section-title {
            font-size: 15pt;
            font-weight: bold;
            margin: 8px 0 5px 0;
            text-align: center;
            text-transform: uppercase;
            padding: 10;
        }
        .data-line {
            font-size: 12pt;
            margin: 3px 0;
            line-height: 1.0;
        }
        .data-underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 250px;
            padding: 2px 4px;
        }
        .data-underline-short {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 150px;
            padding: 2px 4px;
        }
        .data-underline-long {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 550px;
            padding: 2px 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 3px 6px;
            text-align: center;
            font-size: 12pt;
        }
        th {
            font-weight: normal;
        }
        td {
            height: 12px;
        }
        .footer-section {
            margin-top: 10px;
            font-size: 12pt;
        }
        .footer-sig {
            margin-top: 20px;
        }
        .sig-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 200px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="coop-name">TACLOBAN SAN JOSE AIRPORT OPERATORS AND DRIVERS TRANSPORT COOPEARATIVE (TSJAODTC)</div>
        <div class="label">Name of the Cooperative</div>
        <div class="address">Brgy. 88, COSTABRAVA SAN JOSE, TACLOBAN CITY</div>
        <div class="label">Address</div>
    </div>

    <div class="title" style="padding-top: 10px;">APPLICATION FOR MEMBERSHIP</div>
    <br>

    <div class="content-text">
        I hereby apply for membership in the <span class="underline" style="padding-left: 0; padding-right: 20px;">TSJAODTC</span> and agree to faithfully obey its rules and regulations as set down in its by-laws and amendments thereof, or elsewhere, and the decisions of the genera, general membership as well as those of the board of directors.
    </div>

    <div class="content-text" style="margin-top: 10px;">
        I have paid the required membership fee of  <span class="data-underline-short"></span>Php
    </div>

    <div class="content-text" style="margin-top: 10px;">
        I also, hereby pledge to subscribe initially for <span class="data-underline-short" style="min-width: 60px;"></span> share/shares (common stock / preferred stock) with par value of <span class="data-underline" style="min-width: 180px;"></span>Php of the Share Capital of said cooperative, and to pay the amount of  <span class="data-underline" style="min-width: 180px;"></span>Php equivalent to <span class="data-underline-short" style="min-width: 60px;"></span> share / shares as my initial paid up capital. I promise to pay the balance of my subscription in weekly/semi-monthly/quarterly/semi-annually installments of <span class="data-underline" style="min-width: 170px;"></span>Php
    </div>

    <div class="signature-section">
        <div class="signature-line" style="text-align: center; padding-top: 5px; padding-bottom: 0;">@if(isset($firstName) || isset($middleName) || isset($lastName)){{ ucwords(strtolower(trim(($firstName ?? '') . ' ' . ($middleName ?? '') . ' ' . ($lastName ?? '')))) }}@endif</div>
        <div class="signature-label">Signature of Applicant</div>
    </div>

    <br>
    <div class="section-title" style="margin-top: 5px; padding-bottom: 10px;">PERSONAL DATA</div>

    <div class="data-line">
        Name: <span class="data-underline" style="min-width: 270px;">@if(isset($firstName) || isset($middleName) || isset($lastName)){{ ucwords(strtolower(trim(($lastName ?? '') . ', ' . ($firstName ?? '') . ' ' . ($middleName ?? '')))) }}@endif</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Civil Status: <span class="data-underline-short" style="min-width: 150px;">{{ $civilStatus ?? '' }}</span>
    </div>

    <div class="data-line">
        Birthplace: <span class="data-underline" style="min-width: 243px;">{{ $birthplace ?? '' }}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Date of Birth: <span class="data-underline-short" style="min-width: 140px;">@if(isset($birthdate) && $birthdate){{ \Carbon\Carbon::parse($birthdate)->format('m/d/Y') }}@endif</span>
    </div>

    <div class="data-line">
        Present Address: <span class="data-underline-long" style="min-width: 483px;">{{ $address ? ucwords(strtolower($address)) : '' }}</span>
    </div>

    <div class="data-line">
        Occupation: <span class="data-underline-long" style="min-width: 513px;">{{ $occupation ? ucwords(strtolower($occupation)) : '' }}</span>
    </div>

    <div class="data-line">
        Present Employer: <span class="data-underline-long" style="min-width: 473px;"></span>
    </div>

    <div class="data-line">
        Salary: <span class="data-underline" style="min-width: 175px;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Other Source of Income: <span class="data-underline-short" style="min-width: 163px;"></span>
    </div>

    <div class="data-line">
        Nearest Relative: <span class="data-underline-long" style="min-width: 480px;"></span>
    </div>

    <div class="data-line">
        Number of Dependents: <span class="data-underline" style="min-width: 100px;">@if(isset($dependents)){{ count($dependents) }}@endif</span>
    </div>

    <div class="section-title" style="margin-top: 15px; padding-top: 5px; padding-bottom: 10px;">LIST OF DEPENDENTS</div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">NAME</th>
                <th style="width: 20%;">AGE</th>
                <th style="width: 30%;">RELATIONSHIP</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($dependents) && count($dependents) > 0)
                @foreach($dependents as $dependent)
                    <tr>
                        <td>{{ $dependent['name'] ?? '' }}</td>
                        <td>{{ $dependent['age'] ?? '' }}</td>
                        <td>{{ $dependent['relation'] ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-section">
        This application was approved / disapproved by the Board of Directors in its meeting held on
        <span class="data-underline" style="min-width: 100px;"></span>, 20___.
    </div>

    <div class="footer-sig">
        <div style="float: right; width: 220px;">
            <div class="sig-line"></div>
            <div style="text-align: center; font-size: 11pt; margin-top: -10px;">Secretary</div>
        </div>
    </div>

    <div style="clear: both; margin-top: 40px;">
        <div style="font-size: 11pt;">Noted by:</div>
        <div style="margin-top: 35px;">
            <div style="display: inline-block; width: 48%; vertical-align: bottom;">
                <div class="sig-line"></div>
            </div>
            <div style="display: inline-block; width: 48%; margin-left: 3%; vertical-align: bottom; text-align: right;">
                Membership No. <span class="data-underline-short" style="min-width: 100px;"></span>
            </div>
        </div>
    </div>

</body>
</html>
