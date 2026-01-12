<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About the System</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem;
        }

        .page-title {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .page-title h1 {
            color: #0284c7;
            font-size: 2.2rem;
        }

        /* Accordion Styles */
        .bylaws-accordion {
            max-width: 1000px;
            margin: 0 auto;
        }

        .accordion-item {
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .accordion-header {
            width: 100%;
            background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .accordion-header i {
            transition: transform 0.3s ease;
        }

        .accordion-item.active .accordion-header i {
            transform: rotate(180deg);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
            padding: 0 2rem;
        }

        .accordion-item.active .accordion-content {
            max-height: 3000px;
            padding: 2rem;
        }

        .accordion-content p,
        .accordion-content li {
            color: #334155;
            line-height: 1.8;
        }

        .accordion-content ul {
            margin-left: 1.5rem;
        }

        .accordion-content strong {
            color: #0284c7;
        }

        /* Footer Info */
        .footer-note {
            margin-top: 3rem;
            text-align: center;
            color: #475569;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="page-title">
    <h1><i class="fas fa-building"></i> About the System</h1>
</div>

<div class="bylaws-accordion">

    <!-- System Overview -->
    <div class="accordion-item active">
        <button class="accordion-header">
            <span>System Overview</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <p>
                The <strong>Web-Based Annual Progress Report System for TSJAODTC</strong>
                is a digital platform designed to streamline cooperative operations,
                automate CDA-compliant reporting, and improve transparency and efficiency
                in managing cooperative members, vehicles, and finances.
            </p>
        </div>
    </div>

    <!-- Key Features -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Key Features</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <ul>
                <li><strong>Automated Reporting:</strong> CDA-compliant PDF reports</li>
                <li><strong>Document Expiration Alerts:</strong> Email notifications</li>
                <li><strong>Role-Based Dashboards:</strong> Admin, President, Treasurer, Operators</li>
                <li><strong>Forecasting Module:</strong> Financial and document trends</li>
                <li><strong>Centralized Database:</strong> Secure cooperative records</li>
            </ul>
        </div>
    </div>

    <!-- Security & Compliance -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Security & Compliance</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <ul>
                <li><strong>RBAC:</strong> Role-based access control</li>
                <li><strong>Data Encryption:</strong> Protection of sensitive information</li>
                <li><strong>CDA Compliance:</strong> Standardized reporting</li>
                <li><strong>Audit Trail:</strong> Logged system activities</li>
            </ul>
        </div>
    </div>

    <!-- Development Team -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Development Team</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <p>
                Developed as a <strong>Capstone Project (2025)</strong> by
                <strong>BS Information Technology students</strong> from
                <strong>Eastern Visayas State University (EVSU)</strong>.
            </p>

            <ul>
                <li>Merie Joy P. Delampasig</li>
                <li>Iron Morris P. Distrajo</li>
                <li>Mavil D. Mabute</li>
            </ul>
        </div>
    </div>

    <!-- Technical Specs -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Technical Specifications</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <ul>
                <li><strong>Framework:</strong> Laravel (PHP)</li>
                <li><strong>Database:</strong> MySQL</li>
                <li><strong>Frontend:</strong> HTML, CSS, JS, Bootstrap 5</li>
                <li><strong>Hosting:</strong> Render</li>
            </ul>
        </div>
    </div>

    <!-- Version -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Version Information</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <ul>
                <li><strong>Version:</strong> 1.2</li>
                <li><strong>Release:</strong> December 2025</li>
                <li><strong>Last Updated:</strong> January 2026</li>
            </ul>
        </div>
    </div>

</div>

<div class="footer-note">
    <i class="fas fa-info-circle"></i>
    Built to support transparent, secure, and efficient cooperative management.
</div>

<script>
    document.querySelectorAll('.accordion-header').forEach(button => {
        button.addEventListener('click', () => {
            const item = button.parentElement;
            item.classList.toggle('active');
        });
    });
</script>

</body>
</html>
