@extends('layouts.app')

@section('title', 'Auditor Dashboard')

@section('page-title', 'Auditor Dashboard')

@push('styles')
<style>
    /* Dashboard Container */
    .auditor-dashboard {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-title {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .welcome-title i {
        font-size: 40px;
    }

    .welcome-subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
        line-height: 1.6;
    }

    /* Section Title */
    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: #667eea;
    }

    /* Navigation Cards Grid */
    .nav-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .nav-card {
        background: white;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
        border-top: 4px solid var(--card-color);
    }

    .nav-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--card-color) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .nav-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .nav-card:hover::before {
        opacity: 0.05;
    }

    .nav-card.blue {
        --card-color: #4e73df;
    }

    .nav-card.green {
        --card-color: #1cc88a;
    }

    .nav-card.orange {
        --card-color: #f6c23e;
    }

    .nav-card-content {
        position: relative;
        z-index: 1;
    }

    .nav-card-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--card-color) 0%, var(--card-color) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .nav-card-title {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 12px 0;
    }

    .nav-card-description {
        font-size: 15px;
        color: #7f8c8d;
        margin: 0 0 20px 0;
        line-height: 1.6;
    }

    .nav-card-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--card-color);
        font-weight: 600;
        font-size: 15px;
        transition: gap 0.3s ease;
    }

    .nav-card:hover .nav-card-action {
        gap: 12px;
    }

    .nav-card-action i {
        transition: transform 0.3s ease;
    }

    .nav-card:hover .nav-card-action i {
        transform: translateX(3px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 30px 25px;
        }

        .welcome-title {
            font-size: 24px;
        }

        .welcome-title i {
            font-size: 30px;
        }

        .coming-soon-section {
            padding: 40px 25px;
        }

        .coming-soon-icon {
            font-size: 60px;
        }

        .section-title {
            font-size: 20px;
        }

        .nav-cards-grid {
            grid-template-columns: 1fr;
        }

        .nav-card {
            padding: 25px;
        }

        .nav-card-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .nav-card-title {
            font-size: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="auditor-dashboard">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-content">
            <h1 class="welcome-title">
                <i class="fas fa-clipboard-check"></i>
                Welcome, Auditor!
            </h1>
            <p class="welcome-subtitle">
                Access and review financial records, attendance logs, and compliance documents. Your audit tools are ready to ensure transparency and accountability across the cooperative.
            </p>
        </div>
    </div>

    <!-- Section Title -->
    <h2 class="section-title">
        <i class="fas fa-th-large"></i>
        Audit Management
    </h2>

    <!-- Navigation Cards Grid -->
    <div class="nav-cards-grid">
        <!-- Subsidiary Journal Card -->
        <a href="{{ route('auditor.subsidiary-journal') }}" class="nav-card blue">
            <div class="nav-card-content">
                <div class="nav-card-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3 class="nav-card-title">Subsidiary Journal</h3>
                <p class="nav-card-description">
                    Review and audit all financial transactions, cash flows, and detailed subsidiary journal entries with comprehensive reports.
                </p>
                <span class="nav-card-action">
                    View Journal <i class="fas fa-arrow-right"></i>
                </span>
            </div>
        </a>

        <!-- Book of Accounts Card -->
        <a href="{{ route('auditor.book-of-accounts') }}" class="nav-card green">
            <div class="nav-card-content">
                <div class="nav-card-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="nav-card-title">Book of Accounts</h3>
                <p class="nav-card-description">
                    Access the complete book of accounts including ledgers, trial balances, and financial statements for thorough audit review.
                </p>
                <span class="nav-card-action">
                    View Book of Accounts <i class="fas fa-arrow-right"></i>
                </span>
            </div>
        </a>

        <!-- Attendance Records Card -->
        <a href="{{ route('auditor.attendance-records') }}" class="nav-card orange">
            <div class="nav-card-content">
                <div class="nav-card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="nav-card-title">Attendance Records</h3>
                <p class="nav-card-description">
                    Audit meeting attendance records, track member absences, and verify penalty calculations for compliance monitoring.
                </p>
                <span class="nav-card-action">
                    View Attendance <i class="fas fa-arrow-right"></i>
                </span>
            </div>
        </a>
    </div>
</div>
@endsection
