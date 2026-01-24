<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Frequently Asked Questions</title>

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
            text-align: left;
        }

        .accordion-header i {
            margin-left: 1rem;  
            flex-shrink: 0;  
            transition: transform 0.3s ease;
        }

        .accordion-item.active .accordion-header i {
            transform: rotate(180deg);
        }

        .accordion-header span {
            flex: 1;  /* ADD THIS - allows text to take available space */
            text-align: left;  /* ADD THIS */
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
            padding: 0 2rem;
        }

        .accordion-item.active .accordion-content {
            max-height: 2000px;
            padding: 2rem;
        }

        .accordion-content p {
            color: #334155;
            line-height: 1.8;
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

        /* Mobile Responsive */
    @media (max-width: 768px) {
        body {
            padding: 1rem;
        }
        
        .page-title h1 {
            font-size: 1.6rem;
        }
        
        .accordion-header {
            padding: 1rem 1.2rem;
            font-size: 0.95rem;
        }
        
        .accordion-content {
            padding: 0 1.2rem;
        }
        
        .accordion-item.active .accordion-content {
            padding: 1.2rem;
        }
        
        .footer-note {
            font-size: 0.9rem;
            padding: 0 1rem;
        }
    }

    @media (max-width: 480px) {
        .page-title h1 {
            font-size: 1.4rem;
        }
        
        .accordion-header {
            padding: 0.9rem 1rem;
            font-size: 0.9rem;
        }
        
        .accordion-content p {
            font-size: 0.9rem;
        }
    }

    </style>
</head>
<body>

<div class="page-title">
    <h1><i class="fas fa-circle-question"></i> Frequently Asked Questions</h1>
</div>

<div class="bylaws-accordion">

    <!-- Q1 -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Q1: How do I log in to the system?</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <p>
                Go to the system landing page and click <strong>“Access Portal.”</strong>
                Enter your <strong>User ID</strong> and <strong>password</strong> provided by the Administrator.
                If you forgot your password, click <strong>“Forgot Password”</strong> to reset it via email.
            </p>
        </div>
    </div>

    <!-- Q2 -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Q2: What should I do if I forget my User ID?</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <p>
                Please contact the system <strong>Administrator (Secretary)</strong>
                to retrieve your User ID.
            </p>
        </div>
    </div>

    <!-- Q3 -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Q3: Can I access the system on my mobile phone?</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <p>
                Yes. The system is <strong>mobile-responsive</strong> and can be accessed
                using any modern web browser on smartphones or tablets.
            </p>
        </div>
    </div>

    <!-- Q4 -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Q4: How do I update my profile information?</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <p>
                Navigate to your <strong>Dashboard</strong>, click on <strong>“My Profile,”</strong>
                edit the necessary fields, and then save your changes.
            </p>
        </div>
    </div>

    <!-- Q5 -->
    <div class="accordion-item">
        <button class="accordion-header">
            <span>Q5: What browsers are supported?</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="accordion-content">
            <p>
                The system works best on the latest versions of
                <strong>Google Chrome</strong>, <strong>Mozilla Firefox</strong>,
                and <strong>Microsoft Edge</strong>.
            </p>
        </div>
    </div>

</div>

<div class="footer-note">
    <i class="fas fa-info-circle"></i>
    This FAQ section is provided to help users navigate and use the system efficiently.
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
