@php
use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Transport Coop System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    
    <!-- Custom Retractable Sidebar Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
            background: #f4f6f9;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Retractable Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            color: #334155;
            width: 70px;
            transition: width 0.3s ease;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.08);
            border-right: 1px solid #e2e8f0;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #224abe 0%, #1a3a8a 100%);
        }

        .sidebar.expanded {
            width: 250px;
        }

        .sidebar.pinned {
            width: 250px;
        }

        .brand-link {
            padding: 18px 20px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            white-space: nowrap;
            border-bottom: none;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }

        .brand-link:hover {
            color: white;
            text-decoration: none;
        }

        .brand-link i {
            font-size: 24px;
            min-width: 30px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .brand-text {
            margin-left: 10px;
            font-size: 17px;
            font-weight: 700;
            opacity: 0;
            transition: opacity 0.3s ease;
            letter-spacing: 0.5px;
        }

        .sidebar.expanded .brand-text,
        .sidebar.pinned .brand-text {
            opacity: 1;
        }

        .user-panel {
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            white-space: nowrap;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .user-panel i {
            font-size: 35px;
            min-width: 30px;
            color: #4e73df;
            filter: drop-shadow(0 2px 4px rgba(78, 115, 223, 0.2));
        }

        .user-info-sidebar {
            margin-left: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar.expanded .user-info-sidebar,
        .sidebar.pinned .user-info-sidebar {
            opacity: 1;
        }

        .user-info-sidebar a {
            color: #1e293b;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info-sidebar a:hover {
            color: #4e73df;
        }

        .user-info-sidebar small {
            color: #64748b;
            display: block;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .nav-sidebar {
            list-style: none;
            padding: 12px 0;
        }

        .nav-item {
            position: relative;
            margin: 2px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: nowrap;
            border-left: 3px solid transparent;
            border-radius: 8px;
            margin: 2px 0;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #4e73df;
            border-left-color: #4e73df;
            text-decoration: none;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border-left-color: transparent;
            color: white;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35);
        }

        .nav-link.active:hover {
            color: white;
        }

        .nav-icon {
            font-size: 18px;
            min-width: 30px;
            text-align: center;
        }

        .nav-link.active .nav-icon {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .nav-link p {
            margin: 0;
            margin-left: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar.expanded .nav-link p,
        .sidebar.pinned .nav-link p {
            opacity: 1;
        }

        /* Dropdown Menu Styles */
        .nav-item.has-dropdown {
            position: relative;
        }

        .nav-link.dropdown-toggle {
            cursor: pointer;
            position: relative;
        }

        .nav-link.dropdown-toggle .dropdown-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
            opacity: 0;
            font-size: 12px;
        }

        .sidebar.expanded .nav-link.dropdown-toggle .dropdown-arrow,
        .sidebar.pinned .nav-link.dropdown-toggle .dropdown-arrow {
            opacity: 1;
        }

        /* Open dropdown on hover when sidebar is expanded or pinned */
        .sidebar.expanded .nav-item.has-dropdown:hover .dropdown-arrow,
        .sidebar.pinned .nav-item.has-dropdown:hover .dropdown-arrow,
        .nav-item.has-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-menu-sidebar {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0 0 8px 8px;
            margin: 0 4px;
        }

        /* Open dropdown on hover when sidebar is expanded or pinned */
        .sidebar.expanded .nav-item.has-dropdown:hover .dropdown-menu-sidebar,
        .sidebar.pinned .nav-item.has-dropdown:hover .dropdown-menu-sidebar {
            max-height: 500px;
        }

        /* Keep dropdown open if a child is active and sidebar is expanded/pinned */
        .sidebar.expanded .nav-item.has-dropdown.open .dropdown-menu-sidebar,
        .sidebar.pinned .nav-item.has-dropdown.open .dropdown-menu-sidebar {
            max-height: 500px;
        }

        .dropdown-menu-sidebar .nav-link {
            padding-left: 50px;
            font-size: 13px;
            border-radius: 6px;
            margin: 2px 8px;
        }

        .dropdown-menu-sidebar .nav-link:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        }

        .dropdown-menu-sidebar .nav-link.active {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
        }

        /* Keep parent highlighted when child is active */
        .nav-item.has-dropdown .dropdown-menu-sidebar .nav-link.active ~ .dropdown-toggle,
        .nav-item.has-dropdown:has(.dropdown-menu-sidebar .nav-link.active) > .nav-link.dropdown-toggle {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4e73df;
            border-left-color: #4e73df;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 70px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar.expanded ~ .main-content,
        .sidebar.pinned ~ .main-content {
            margin-left: 250px;
        }

        /* Top Navbar */
        .main-header {
            background: white;
            padding: 0 30px;
            height: 60px;
            min-height: 60px;
            max-height: 60px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Mobile Menu Toggle Button */
        .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
        }

        .mobile-menu-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        .navbar-nav.navbar-right {
            display: flex;
            flex-direction: row;  /* ADD THIS */
            list-style: none;
            align-items: center;
            gap: 15px;  /* ADD THIS - adds spacing between items */
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .navbar-nav.navbar-right .nav-item {
            height: 100%;
            display: flex;
            align-items: center;
            margin: 0;
            position: relative;
        }

        .navbar-nav.navbar-right .nav-item.dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            color: #495057;
            text-decoration: none;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
            background: transparent;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .user-dropdown-toggle:hover {
            color: #4e73df;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            text-decoration: none;
        }

        .user-dropdown-toggle .fa-user {
            font-size: 16px;
        }

        .user-dropdown-toggle .user-name {
            font-weight: 500;
            font-size: 14px;
        }

        .user-dropdown-toggle .dropdown-caret {
            font-size: 10px;
            transition: transform 0.2s ease;
            color: #94a3b8;
        }

        .nav-item.dropdown:hover .user-dropdown-toggle .dropdown-caret {
            transform: rotate(180deg);
        }

        .navbar-nav.navbar-right .dropdown-menu {
            position: absolute;
            top: calc(100% + 5px);
            right: 0;
            left: auto;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            min-width: 200px;
            display: block;
            z-index: 9999;
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
            pointer-events: auto;
        }

        .navbar-nav.navbar-right .nav-item.dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        input.form-control,
        textarea.form-control {
            padding: 12px 16px;
            height: 44px;
            line-height: 20px;
        }

        select.form-control {
            height: 44px;              /* 🔑 This fixes clipping */
            padding: 0 14px;           /* No vertical padding */
            line-height: 44px;         /* Vertically centers text */
        }

        .dropdown-item {
            padding: 10px 16px;
            color: #475569;
            text-decoration: none;
            display: flex;
            align-items: center;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            gap: 10px;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #4e73df;
        }

        .dropdown-item i {
            width: 16px;
            color: #64748b;
            font-size: 14px;
        }

        .dropdown-item:hover i {
            color: #4e73df;
        }

        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 8px 12px;
        }

        /* Content Wrapper */
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .content-header {
            padding: 20px 30px;
            background: white;
            border-bottom: 1px solid #dee2e6;
        }

        .content-header h1 {
            font-size: 24px;
            color: #343a40;
            margin: 0;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            gap: 5px;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .modal-close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        // Password Strength Meter Styles
        .password-strength-meter {
            margin-top: 10px;
            display: none;
        }

        .password-strength-meter.active {
            display: block;
        }

        .strength-meter-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 4px;
        }

        .strength-meter-fill.weak {
            width: 33%;
            background: #dc3545;
        }

        .strength-meter-fill.medium {
            width: 66%;
            background: #ffc107;
        }

        .strength-meter-fill.strong {
            width: 100%;
            background: #28a745;
        }

        .strength-meter-text {
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }

        .strength-meter-text.weak {
            color: #dc3545;
        }

        .strength-meter-text.medium {
            color: #ffc107;
        }

        .strength-meter-text.strong {
            color: #28a745;
        }

        .password-requirements {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 13px;
            color: #6c757d;
        }

        .password-requirements ul {
            margin: 5px 0 0 0;
            padding-left: 20px;
        }

        .password-requirements li {
            margin: 3px 0;
            transition: color 0.3s;
        }

        .password-requirements li.met {
            color: #28a745;
        }

        .password-requirements li.met::before {
            content: '✓ ';
            font-weight: bold;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            /* Show mobile menu toggle button */
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar {
                width: 280px !important;
                position: fixed;
                left: -280px;
                z-index: 1050;
                transition: left 0.3s ease, box-shadow 0.3s ease, width 0s;
                height: 100vh;
                top: 0;
                overflow-y: auto;
                overflow-x: visible;
                background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
                box-shadow: none;
            }

            .sidebar.expanded,
            .sidebar.pinned {
                left: 0;
                box-shadow: 4px 0 25px rgba(78, 115, 223, 0.15);
            }

            /* Force sidebar to show all content on mobile */
            .sidebar .brand-text,
            .sidebar .user-info-sidebar,
            .sidebar .nav-text,
            .sidebar .nav-link p {
                opacity: 1 !important;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }

            /* Mobile overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(30, 41, 59, 0.5);
                backdrop-filter: blur(4px);
                z-index: 1040;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }

            .d-none.d-sm-inline-block {
                display: none !important;
            }

            /* Mobile nav items spacing */
            .nav-item {
                margin: 4px 10px;
            }

            .nav-link {
                padding: 14px 16px;
            }
        }


        /* Ensure password wrapper is positioned relative for absolute icon positioning */
        .password-input-wrapper {
            position: relative;
            width: 100%;
        }

        .password-input-wrapper input.form-control {
            width: 100%;
            padding-right: 44px; /* Make room for the eye icon */
        }

        /* FIX: Eye icon should be inside the input field */
        .toggle-password-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 16px;
            transition: color 0.2s ease;
            z-index: 10;
        }

        .toggle-password-icon:hover {
            color: #475569;
        }


        /* Global Modal Styles - Scrollable Body Only */
        .modal-container {
            display: flex;
            flex-direction: column;
        }

        .modal-body {
            overflow-y: auto;
            overflow-x: hidden;
            flex: 1;
            position: relative;
        }

        /* Custom scrollbar for modal body */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
            margin: 5px 0;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #4e73df;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #224abe;
        }

        .modal-footer {
            flex-shrink: 0;
        }

        .modal-header {
            flex-shrink: 0;
        }

        /* ================================
        Admin Settings Modal (Gray Theme)
        ================================ */

        #adminSettingsModal .modal-header {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }

        #adminSettingsModal .modal-title {
            font-weight: 600;
            font-size: 18px;
        }

        /* Section Card */
        #adminSettingsModal .form-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        #adminSettingsModal .section-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        #adminSettingsModal .section-header h4 {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Inputs */
        #adminSettingsModal input.form-control {
            height: 44px;
            padding: 12px 44px 12px 16px; /* room for icon */
            border-radius: 8px;
        }

        /* Password field wrapper */
        #adminSettingsModal .password-input-wrapper {
            position: relative;
        }

        /* Eye icon placement FIX */
        #adminSettingsModal .toggle-password-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 16px;
            transition: color 0.2s ease;
        }

        #adminSettingsModal .toggle-password-icon:hover {
            color: #475569;
        }

        /* Action buttons */
        #adminSettingsModal .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        /* Database section buttons */
        #adminSettingsModal .btn {
            border-radius: 8px;
        }

        /* ================================
        OPERATOR CHANGE PASSWORD MODAL
        ================================ */

        /* Make modal wider on desktop with spacing from screen edges */
        #changePasswordModal .modal-dialog {
            max-width: 750px; /* Modal width */
            margin: 80px auto; /* Vertical spacing (top/bottom) and center horizontally */
        }

        /* Add more spacing on larger screens */
        @media (min-width: 576px) {
            #changePasswordModal .modal-dialog {
                margin: 50px auto; /* More space on tablets and desktop */
            }
        }

        /* Ensure requirements box styling for operator modal */
        #changePasswordModal .password-requirements {
            background: #f8f9fc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            height: 100%;
            min-height: 200px;
        }

        #changePasswordModal .password-requirements strong {
            display: block;
            margin-bottom: 10px;
            color: #334155;
            font-size: 14px;
        }

        #changePasswordModal .password-requirements ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        #changePasswordModal .password-requirements li {
            padding: 5px 0;
            font-size: 13px;
            color: #6c757d;
            position: relative;
            padding-left: 20px;
        }

        #changePasswordModal .password-requirements li::before {
            content: '○';
            position: absolute;
            left: 0;
            color: #94a3b8;
            font-weight: bold;
        }

        #changePasswordModal .password-requirements li.met {
            color: #28a745;
        }

        #changePasswordModal .password-requirements li.met::before {
            content: '✓';
            color: #28a745;
        }

        /* Mobile responsive for operator modal */
        @media (max-width: 991px) {
            #changePasswordModal .col-lg-7,
            #changePasswordModal .col-lg-5 {
                padding-right: 15px;
                padding-left: 15px;
            }
            
            #changePasswordModal .password-requirements {
                margin-top: 20px;
                min-height: auto;
            }
        }

    </style>

    @stack('styles')
</head>
<body>
<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-container">

    <!-- Retractable Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link">
            <i class="fas fa-bus"></i>
            <span class="brand-text">Transport Coop</span>
        </a>

        <!-- User Panel -->
        <div class="user-panel">
            <i class="fas fa-user-circle"></i>

            <div class="user-info-sidebar">
                <a href="#profile"
                class="d-block"
                onclick="openProfileModal(event)">
                    {{ Auth::user()->name }}
                </a>
                <small>{{ ucfirst(Auth::user()->role) }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav-sidebar">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                    <a href="{{ route('admin.general-info') }}" class="nav-link {{ request()->routeIs('admin.general-info') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-info-circle"></i>
                        <p>General Information</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('registrations.index') }}" class="nav-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-clock"></i>
                        <p>Pending Registrations</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('operators.index') }}" class="nav-link {{ request()->routeIs('operators.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Operators</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('meetings.index') }}" class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Meeting Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('officers.index') }}" class="nav-link {{ request()->routeIs('officers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Officers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('requirements.index') }}" class="nav-link {{ request()->routeIs('requirements.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-stamp"></i>
                        <p>Requirements</p>
                    </a>
                </li>
                <li class="nav-item has-dropdown {{ request()->routeIs('admin.cash-*') ? 'open' : '' }}">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.cash-*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Financial Books</p>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <div class="dropdown-menu-sidebar">
                        <a href="{{ route('admin.cash-treasurers-book') }}" class="nav-link {{ request()->routeIs('admin.cash-treasurers-book') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Cash Treasurer's Book</p>
                        </a>
                        <a href="{{ route('admin.cash-receipts-journal') }}" class="nav-link {{ request()->routeIs('admin.cash-receipts-journal') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Cash Receipts Journal</p>
                        </a>
                        <a href="{{ route('admin.cash-disbursement-book') }}" class="nav-link {{ request()->routeIs('admin.cash-disbursement-book') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>Cash Disbursement Book</p>
                        </a>
                        <a href="{{ route('admin.cash-book') }}" class="nav-link {{ request()->routeIs('admin.cash-book') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Cash Book</p>
                        </a>
                    </div>
                </li>
                                <li class="nav-item has-dropdown {{ request()->routeIs('admin.report', 'admin.annual-report') ? 'open' : '' }}">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.report', 'admin.annual-report') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports</p>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <div class="dropdown-menu-sidebar">
                        <a href="{{ route('admin.report') }}" class="nav-link {{ request()->routeIs('admin.report') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Report</p>
                        </a>
                        <a href="{{ route('admin.annual-report') }}" class="nav-link {{ request()->routeIs('admin.annual-report') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Annual Report</p>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.audit-trail') }}" class="nav-link {{ request()->routeIs('admin.audit-trail') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Audit Trail</p>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isOperator())
                <li class="nav-item">
                    <a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Drivers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bus"></i>
                        <p>Transport Units</p>
                    </a>
                </li>
                <li class="nav-item has-dropdown {{ request()->routeIs('operator.cash-*') ? 'open' : '' }}">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('operator.cash-*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Financial Books</p>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <div class="dropdown-menu-sidebar">
                        <a href="{{ route('operator.cash-receipts-journal') }}" class="nav-link {{ request()->routeIs('operator.cash-receipts-journal') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Cash Receipts Journal</p>
                        </a>
                        <a href="{{ route('operator.cash-disbursement-book') }}" class="nav-link {{ request()->routeIs('operator.cash-disbursement-book') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>Cash Disbursement Book</p>
                        </a>
                        <a href="{{ route('operator.cash-book') }}" class="nav-link {{ request()->routeIs('operator.cash-book') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Cash Book</p>
                        </a>
                    </div>
                </li>
                @endif

                @if(Auth::user()->role === 'treasurer')
                <li class="nav-item">
                    <a href="{{ route('treasurer.cash-treasurers-book') }}" class="nav-link {{ request()->routeIs('treasurer.cash-treasurers-book') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Cash Treasurer's Book</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('treasurer.cash-receipts-journal') }}" class="nav-link {{ request()->routeIs('treasurer.cash-receipts-journal') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Cash Receipts Journal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('treasurer.cash-disbursement-book') }}" class="nav-link {{ request()->routeIs('treasurer.cash-disbursement-book') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Cash Disbursement Book</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('treasurer.cash-book') }}" class="nav-link {{ request()->routeIs('treasurer.cash-book') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Cash Book</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.annual-report') }}" class="nav-link {{ request()->routeIs('admin.annual-report') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Annual Report</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('treasurer.particular-prices') }}" class="nav-link {{ request()->routeIs('treasurer.particular-prices*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Particular Prices</p>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isPresident())
                <li class="nav-item">
                    <a href="{{ route('president.operators') }}" class="nav-link {{ request()->routeIs('president.operators') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Operators Directory</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('president.meetings') }}" class="nav-link {{ request()->routeIs('president.meetings') || request()->routeIs('meetings.show') || request()->routeIs('meetings.take-attendance') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Meeting Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.annual-report') }}" class="nav-link {{ request()->routeIs('admin.annual-report') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Annual Report</p>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAuditor())
                <li class="nav-item">
                    <a href="{{ route('auditor.subsidiary-journal') }}" class="nav-link {{ request()->routeIs('auditor.subsidiary-journal') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Subsidiary Journal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('auditor.book-of-accounts') }}" class="nav-link {{ request()->routeIs('auditor.book-of-accounts') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Book of Accounts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('auditor.attendance-records') }}" class="nav-link {{ request()->routeIs('auditor.attendance-records') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Attendance Records</p>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>

        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="main-header">
            <div class="header-left">
                <!-- Mobile Menu Toggle Button -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="navbar-nav navbar-right">
                <!-- FAQs link appears FIRST (left side) -->
                <li class="nav-item">
                    <a href="{{ Auth::user()->faqRoute() }}" class="nav-link-faq">
                        <i class="fas fa-question-circle"></i> FAQs
                    </a>
                </li>

                <!-- User dropdown SECOND (right side) -->
                <li class="nav-item dropdown">
                    <a href="#" class="user-dropdown-toggle">
                        <i class="far fa-user"></i>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down dropdown-caret"></i>
                    </a>
                    <div class="dropdown-menu">

                        {{-- Operator --}}
                        @if(Auth::user()->isOperator())
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#operatorProfileModal">
                                <i class="fas fa-user"></i> Profile
                            </a>

                        {{-- Admin --}}
                        @elseif(Auth::user()->isAdmin())
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#adminSettingsModal">
                                <i class="fas fa-cogs"></i> Settings
                            </a>

                        {{-- Other roles --}}
                        @else
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>


        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            @if (!View::hasSection('hide-content-header'))
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <section class="content">
                @yield('content')
            </section>
        </div>
    </div>
</div>

<!-- Admin Settings Modal -->
@if(Auth::user()->isAdmin())
<div class="modal fade" id="adminSettingsModal" tabindex="-1" role="dialog" aria-labelledby="adminSettingsModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title" id="adminSettingsModalLabel">
                    <i class="fas fa-cogs"></i> Admin Settings
                </h5>
                <button type="button" class="modal-close-btn" data-dismiss="modal">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- LEFT: Change Password Section -->
                    <div class="col-lg-6 col-md-12">
                        <div class="form-section h-100">
                            <div class="section-header">
                                <h4><i class="fas fa-key"></i> Change Password</h4>
                            </div>

                            <form id="adminChangePasswordForm">
                                <div class="form-group">
                                    <label for="admin_current_password">
                                        <i class="fas fa-lock"></i> Current Password
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password"
                                            class="form-control"
                                            id="admin_current_password"
                                            name="current_password"
                                            required>
                                        <i class="fas fa-eye toggle-password-icon"
                                        onclick="togglePasswordVisibility('admin_current_password', this)"></i>
                                    </div>
                                    <small class="text-danger"
                                        id="admin_current_password_error"
                                        style="display:none;"></small>
                                </div>

                                <div class="form-group">
                                    <label for="admin_new_password">
                                        <i class="fas fa-key"></i> New Password
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password"
                                            class="form-control"
                                            id="admin_new_password"
                                            name="new_password"
                                            required
                                            minlength="8">
                                        <i class="fas fa-eye toggle-password-icon"
                                        onclick="togglePasswordVisibility('admin_new_password', this)"></i>
                                    </div>

                                    <!-- Password Strength Meter -->
                                    <div class="password-strength-meter" id="adminStrengthMeter">
                                        <div class="strength-meter-bar">
                                            <div class="strength-meter-fill" id="adminStrengthBar"></div>
                                        </div>
                                        <div class="strength-meter-text" id="adminStrengthText"></div>
                                    </div>

                                    <small class="text-danger"
                                        id="admin_new_password_error"
                                        style="display:none;"></small>
                                </div>

                                <div class="form-group">
                                    <label for="admin_new_password_confirmation">
                                        <i class="fas fa-check-circle"></i> Confirm New Password
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password"
                                            class="form-control"
                                            id="admin_new_password_confirmation"
                                            name="new_password_confirmation"
                                            required>
                                        <i class="fas fa-eye toggle-password-icon"
                                        onclick="togglePasswordVisibility('admin_new_password_confirmation', this)"></i>
                                    </div>
                                    <small class="text-danger"
                                        id="admin_password_match_error"
                                        style="display:none;">
                                        Passwords do not match
                                    </small>
                                </div>

                                <!-- Password Requirements -->
                                <div class="password-requirements">
                                    <strong><i class="fas fa-shield-alt"></i> Password Requirements:</strong>
                                    <ul>
                                        <li id="admin-req-length">At least 8 characters long</li>
                                        <li id="admin-req-uppercase">At least one uppercase letter</li>
                                        <li id="admin-req-lowercase">At least one lowercase letter</li>
                                        <li id="admin-req-number">At least one number</li>
                                        <li id="admin-req-special">At least one special character</li>
                                    </ul>
                                </div>

                                <!-- ONLY action inside the form -->
                                <div class="form-actions text-right">
                                    <button type="button"
                                            class="btn btn-warning"
                                            onclick="changePassword(this, 'admin')">
                                        <i class="fas fa-save"></i> Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIGHT: Automatic Database Backups -->
                    <div class="col-lg-6 col-md-12">
                        <div class="form-section h-100">
                            <div class="section-header">
                                <h4><i class="fas fa-shield-alt"></i> Database Backups</h4>
                            </div>

                            <p class="text-muted">
                                Database backups are handled automatically by the system.
                            </p>

                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Automatic Backups Enabled</strong>
                            </div>

                            <ul class="list-unstyled small">
                                <li>
                                    <i class="fas fa-clock text-primary"></i>
                                    <strong>Schedule:</strong> Daily (automated)
                                </li>
                                <li class="mt-2">
                                    <i class="fas fa-database text-primary"></i>
                                    <strong>Scope:</strong> All database tables
                                </li>
                                <li class="mt-2">
                                    <i class="fas fa-lock text-primary"></i>
                                    <strong>Access:</strong> Server-side only
                                </li>
                                <li class="mt-2">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                    <strong>Last Backup:</strong>
                                    <em id="lastBackupAt">{{ $lastBackupAt ?? 'Not yet available' }}</em>
                                </li>
                                <li class="mt-2">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    <strong>Status:</strong>
                                    <em id="backupStatus">{{ $backupStatus ?? 'Pending' }}</em>
                                </li>

                                <!-- Recent Backups -->
                                <li class="mt-3">
                                    <i class="fas fa-history text-primary"></i>
                                    <strong>Recent Backups:</strong>
                                    <ul id="recentBackups" class="list-unstyled small mt-2">
                                        @foreach($recentBackups ?? [] as $backup)
                                            <li>
                                                <em>{{ $backup->created_at->format('Y-m-d H:i:s') }}</em>
                                                - <strong>{{ ucfirst($backup->status) }}</strong>
                                                @if($backup->admin)
                                                    (by {{ $backup->admin->name }})
                                                @else
                                                    (automatic)
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>

                            <button 
                                class="btn btn-primary mt-3 w-100"
                                id="runManualBackupBtn"
                                onclick="runManualBackup(this)">
                                <i class="fas fa-play-circle"></i> Run Manual Backup
                            </button>

                            <small class="text-muted d-block mt-3">
                                <i class="fas fa-info-circle"></i>
                                Backup and restore operations are intentionally restricted to prevent accidental data loss.
                            </small>
                        </div>
                    </div>

                    <!-- MODAL FOOTER -->
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>

        </div>
    </div>
</div>
@endif

<!-- Operator Profile Modal -->
@if(Auth::user()->isOperator())
<div class="modal fade" id="operatorProfileModal" tabindex="-1" role="dialog" aria-labelledby="operatorProfileModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white;">
                <h5 class="modal-title" id="operatorProfileModalLabel">
                    <i class="fas fa-user-circle"></i> Operator Profile
                </h5>
                <button type="button" 
                        class="modal-close-btn" data-dismiss="modal">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body" id="operatorProfileContent">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                    <p class="mt-3">Loading profile...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); color: white;">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-key"></i> Change Password
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <!-- Use Bootstrap row/col system for responsive layout -->
                    <div class="row">
                        
                        <!-- Left Column: Password Fields -->
                        <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                            <div class="form-group">
                                <label for="operator_current_password"><i class="fas fa-lock"></i> Current Password</label>
                                <div class="password-input-wrapper">
                                    <input type="password" class="form-control" id="operator_current_password" name="current_password" required>
                                    <i class="fas fa-eye toggle-password-icon" onclick="togglePasswordVisibility('operator_current_password', this)"></i>
                                </div>
                                <small class="text-danger" id="operator_current_password_error" style="display: none;"></small>
                            </div>

                            <div class="form-group">
                                <label for="operator_new_password"><i class="fas fa-key"></i> New Password</label>
                                <div class="password-input-wrapper">
                                    <input type="password" class="form-control" id="operator_new_password" name="new_password" required minlength="8">
                                    <i class="fas fa-eye toggle-password-icon" onclick="togglePasswordVisibility('operator_new_password', this)"></i>
                                </div>

                                <!-- Password Strength Meter -->
                                <div class="password-strength-meter" id="operatorStrengthMeter">
                                    <div class="strength-meter-bar">
                                        <div class="strength-meter-fill" id="operatorStrengthBar"></div>
                                    </div>
                                    <div class="strength-meter-text" id="operatorStrengthText"></div>
                                </div>

                                <small class="text-danger" id="operator_new_password_error" style="display: none;"></small>
                            </div>

                            <div class="form-group">
                                <label for="operator_new_password_confirmation"><i class="fas fa-check-circle"></i> Confirm New Password</label>
                                <div class="password-input-wrapper">
                                    <input type="password" class="form-control" id="operator_new_password_confirmation" name="new_password_confirmation" required>
                                    <i class="fas fa-eye toggle-password-icon" onclick="togglePasswordVisibility('operator_new_password_confirmation', this)"></i>
                                </div>
                                <small class="text-danger" id="operator_password_match_error" style="display: none;">Passwords do not match</small>
                            </div>
                        </div>

                        <!-- Right Column: Requirements -->
                        <div class="col-lg-5 col-md-12">
                            <div class="password-requirements">
                                <strong><i class="fas fa-shield-alt"></i> Password Requirements:</strong>
                                <ul>
                                    <li id="operator-req-length">At least 8 characters long</li>
                                    <li id="operator-req-uppercase">At least one uppercase letter</li>
                                    <li id="operator-req-lowercase">At least one lowercase letter</li>
                                    <li id="operator-req-number">At least one number</li>
                                    <li id="operator-req-special">At least one special character</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-warning" onclick="changePassword(this, 'operator')">
                    <i class="fas fa-save"></i> Change Password
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>

// Limit configuration
const MANUAL_BACKUP_LIMIT = 3; // Max manual backups per day

// Passed from server-side via Blade
let manualBackupsToday = parseInt('{{ $manualBackupsToday ?? 0 }}');

function runManualBackup(button) {
    if (manualBackupsToday >= MANUAL_BACKUP_LIMIT) {
        alert('You have reached the daily manual backup limit.');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-ban"></i> Daily Limit Reached';
        return;
    }

    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Running...';

    fetch('{{ route("admin.database.run") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            storage: 's3' // backend decides what to do with this
        })
    })
    .then(res => res.json())
    .then(res => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-play-circle"></i> Run Manual Backup';

        const now = new Date();
        const formatted = now.toISOString().replace('T', ' ').substring(0, 19);

        if (res.success) {
            document.getElementById('lastBackupAt').textContent = formatted;
            document.getElementById('backupStatus').textContent = 'Success';

            const li = document.createElement('li');
            li.innerHTML = `
                <em>${formatted}</em> - 
                <strong>Success</strong> (manual, S3)
            `;
            document.getElementById('recentBackups').prepend(li);

            alert(res.message || 'Backup uploaded to S3 successfully.');

            manualBackupsToday++;
            if (manualBackupsToday >= MANUAL_BACKUP_LIMIT) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-ban"></i> Daily Limit Reached';
            }
        } else {
            document.getElementById('backupStatus').textContent = 'Failed';
            alert('Backup failed: ' + (res.message || 'Unknown error'));
        }
    })
    .catch(err => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-play-circle"></i> Run Manual Backup';
        document.getElementById('backupStatus').textContent = 'Error';
        alert('Error: ' + err.message);
    });
}


/* ===============================
   MODAL OPENERS
================================ */
function openSettingsModal(e) {
    e.preventDefault();
    $('#adminSettingsModal').modal('show');
}

function openProfileModal(e) {
    e.preventDefault();
    $('#operatorProfileModal').modal('show');
}

/* ===============================
   PASSWORD VISIBILITY (GLOBAL SAFE)
================================ */
function togglePasswordVisibility(inputId, iconElement) {
    const passwordInput = document.getElementById(inputId);

    if (!passwordInput) {
        console.warn('togglePasswordVisibility: input not found ->', inputId);
        return;
    }

    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';

    iconElement.classList.toggle('fa-eye', !isHidden);
    iconElement.classList.toggle('fa-eye-slash', isHidden);
}

/* ===============================
   PASSWORD STRENGTH CHECKER
================================ */
document.addEventListener('DOMContentLoaded', function() {
    // Admin modal password strength
    const adminPasswordInput = document.getElementById('admin_new_password');
    if (adminPasswordInput) {
        adminPasswordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value, 'admin');
        });
    }

    // Operator modal password strength
    const operatorPasswordInput = document.getElementById('operator_new_password');
    if (operatorPasswordInput) {
        operatorPasswordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value, 'operator');
        });
    }
});

function checkPasswordStrength(password, context) {
    const strengthMeter = document.getElementById(`${context}StrengthMeter`);
    const strengthBar = document.getElementById(`${context}StrengthBar`);
    const strengthText = document.getElementById(`${context}StrengthText`);

    if (!strengthMeter || !strengthBar || !strengthText) return;

    if (password.length > 0) {
        strengthMeter.classList.add('active');
    } else {
        strengthMeter.classList.remove('active');
        return;
    }

    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };

    // Update requirement list
    const reqLength = document.getElementById(`${context}-req-length`);
    const reqUppercase = document.getElementById(`${context}-req-uppercase`);
    const reqLowercase = document.getElementById(`${context}-req-lowercase`);
    const reqNumber = document.getElementById(`${context}-req-number`);
    const reqSpecial = document.getElementById(`${context}-req-special`);

    if (reqLength) reqLength.classList.toggle('met', requirements.length);
    if (reqUppercase) reqUppercase.classList.toggle('met', requirements.uppercase);
    if (reqLowercase) reqLowercase.classList.toggle('met', requirements.lowercase);
    if (reqNumber) reqNumber.classList.toggle('met', requirements.number);
    if (reqSpecial) reqSpecial.classList.toggle('met', requirements.special);

    // Calculate strength score
    let strength = 0;
    if (requirements.length) strength += 20;
    if (requirements.uppercase) strength += 20;
    if (requirements.lowercase) strength += 20;
    if (requirements.number) strength += 20;
    if (requirements.special) strength += 20;

    // Update strength meter
    strengthBar.className = 'strength-meter-fill';
    strengthText.className = 'strength-meter-text';

    if (strength <= 40) {
        strengthBar.classList.add('weak');
        strengthText.classList.add('weak');
        strengthText.textContent = 'Weak Password';
    } else if (strength <= 80) {
        strengthBar.classList.add('medium');
        strengthText.classList.add('medium');
        strengthText.textContent = 'Medium Password';
    } else {
        strengthBar.classList.add('strong');
        strengthText.classList.add('strong');
        strengthText.textContent = 'Strong Password';
    }
}

/* ===============================
   CHANGE PASSWORD (Admin / Operator)
================================ */
function changePassword(buttonEl, context) {
    const btn = buttonEl || document.activeElement;

    if (!btn || !btn.innerHTML) {
        console.error('changePassword: button element not found');
        return;
    }

    // Clear previous errors
    document.querySelectorAll('.text-danger').forEach(el => {
        el.style.display = 'none';
        el.textContent = '';
    });

    // Get values based on context (admin_ or operator_ prefix)
    const prefix = context === 'admin' ? 'admin_' : 'operator_';
    const currentPassword = document.getElementById(`${prefix}current_password`)?.value;
    const newPassword = document.getElementById(`${prefix}new_password`)?.value;
    const confirmPassword = document.getElementById(`${prefix}new_password_confirmation`)?.value;

    // Simple validation
    if (!currentPassword || !newPassword || !confirmPassword) {
        showWarning('Please fill in all fields');
        return;
    }

    if (newPassword !== confirmPassword) {
        const err = document.getElementById(`${prefix}password_match_error`);
        if (err) {
            err.style.display = 'block';
            err.textContent = 'Passwords do not match';
        }
        showError('Passwords do not match');
        return;
    }

    if (newPassword.length < 8) {
        const err = document.getElementById(`${prefix}new_password_error`);
        if (err) {
            err.style.display = 'block';
            err.textContent = 'Password must be at least 8 characters';
        }
        return;
    }

    // Show loading state
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing...';

    // Use the same endpoint for both Admin and Operator
    fetch(appUrl('user/change-password'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.appConfig.csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            current_password: currentPassword,
            new_password: newPassword,
            new_password_confirmation: confirmPassword
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccess('Password changed successfully!');
            
            // Close appropriate modal and reset form
            if (context === 'admin') {
                $('#adminSettingsModal').modal('hide');
                document.getElementById('adminChangePasswordForm')?.reset();
            } else {
                $('#changePasswordModal').modal('hide');
                document.getElementById('changePasswordForm')?.reset();
            }
        } else {
            // Display validation or error messages
            if (data.errors) {
                for (let key in data.errors) {
                    const errEl = document.getElementById(`${prefix}${key}_error`);
                    if (errEl) {
                        errEl.style.display = 'block';
                        errEl.textContent = data.errors[key][0];
                    }
                }
            }
            showError(data.message || 'Failed to change password');
        }
    })
    .catch(() => showError('An error occurred while changing password'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

/* ===============================
   MODAL RESET HANDLERS
================================ */
$('#changePasswordModal').on('show.bs.modal', function () {
    document.getElementById('changePasswordForm')?.reset();
    document.querySelectorAll('.text-danger').forEach(el => {
        el.style.display = 'none';
        el.textContent = '';
    });

    document.querySelectorAll('.toggle-password-icon').forEach(icon => {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    });
});

$('#changePasswordModal').on('hidden.bs.modal', function () {
    const form = document.getElementById('changePasswordForm');
    if (form) form.reset();
    
    // Reset strength meter
    const meter = document.getElementById('operatorStrengthMeter');
    if (meter) meter.classList.remove('active');
    
    // Reset requirement checks
    ['operator-req-length', 'operator-req-uppercase', 'operator-req-lowercase', 
     'operator-req-number', 'operator-req-special'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('met');
    });

    // Re-open operator profile modal if it was open
    if (!$('#operatorProfileModal').hasClass('show')) {
        $('#operatorProfileModal').modal('show');
    }
});

$('#adminSettingsModal').on('show.bs.modal', function () {
    document.getElementById('adminChangePasswordForm')?.reset();
    document.querySelectorAll('.text-danger').forEach(el => {
        el.style.display = 'none';
        el.textContent = '';
    });

    document.querySelectorAll('.toggle-password-icon').forEach(icon => {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    });
});

$('#adminSettingsModal').on('hidden.bs.modal', function () {
    const form = document.getElementById('adminChangePasswordForm');
    if (form) form.reset();
    
    // Reset strength meter
    const meter = document.getElementById('adminStrengthMeter');
    if (meter) meter.classList.remove('active');
    
    // Reset requirement checks
    ['admin-req-length', 'admin-req-uppercase', 'admin-req-lowercase', 
     'admin-req-number', 'admin-req-special'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('met');
    });
});

</script>

<!-- Retractable Sidebar Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const menuItems = document.querySelectorAll('.nav-sidebar .nav-link');
    let isPinned = false;

    // Expand sidebar on hover
    sidebar.addEventListener('mouseenter', function() {
        if (!isPinned) {
            sidebar.classList.add('expanded');
        }
    });

    // Collapse sidebar when mouse leaves
    sidebar.addEventListener('mouseleave', function() {
        if (!isPinned) {
            sidebar.classList.remove('expanded');
        }
    });

    // Pin sidebar when clicking menu items (except logout)
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't pin on logout
            if (this.textContent.includes('Logout')) {
                return;
            }

            isPinned = true;
            sidebar.classList.add('pinned');
            sidebar.classList.remove('expanded');
        });
    });

    // Unpin sidebar when clicking main content
    mainContent.addEventListener('click', function() {
        if (isPinned) {
            isPinned = false;
            sidebar.classList.remove('pinned');
        }
    });

    // Prevent clicks inside sidebar from triggering mainContent click
    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Close sidebar when clicking overlay on mobile
    const overlay = document.getElementById('sidebarOverlay');
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('expanded');
            sidebar.classList.remove('pinned');
            isPinned = false;
            document.body.style.overflow = '';
            overlay.classList.remove('active');
        });
    }

    // Handle window resize to clean up states
    window.addEventListener('resize', function() {
        const isMobile = window.innerWidth <= 768;
        if (!isMobile) {
            // Clean up mobile states when switching to desktop
            document.body.style.overflow = '';
            const overlay = document.getElementById('sidebarOverlay');
            if (overlay) overlay.classList.remove('active');
        }
    });
});

// Mobile Sidebar Toggle Function
function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const isMobile = window.innerWidth <= 768;

    if (isMobile) {
        // Toggle sidebar visibility
        const isOpen = sidebar.classList.contains('expanded') || sidebar.classList.contains('pinned');

        if (isOpen) {
            // Close sidebar
            sidebar.classList.remove('expanded');
            sidebar.classList.remove('pinned');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            // Open sidebar
            sidebar.classList.add('expanded');
            sidebar.classList.add('pinned');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
}
</script>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap Bundle (includes Popper) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Global App Configuration for AJAX/Fetch requests -->
<script>
    // Base URL for the application - works with both localhost and tunnels
    window.appConfig = {
        baseUrl: '{{ url('/') }}',
        apiUrl: '{{ url('/api') }}',
        csrfToken: '{{ csrf_token() }}'
    };

    // Helper function to build API URLs
    window.apiUrl = function(path) {
        // Remove leading slash if present
        path = path.replace(/^\//, '');
        // Remove /api/ prefix if present (we'll add it back)
        path = path.replace(/^api\//, '');
        return window.appConfig.apiUrl + '/' + path;
    };

    // Helper function to build app URLs
    window.appUrl = function(path) {
        // Remove leading slash if present
        path = path.replace(/^\//, '');
        return window.appConfig.baseUrl + '/' + path;
    };

    // Configure jQuery AJAX defaults
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': window.appConfig.csrfToken,
            'Accept': 'application/json'
        }
    });

    // Configure Toastr notifications
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    /**
     * Global Notification Helper Functions
     */
    window.showSuccess = function(message, title = 'Success') {
        toastr.success(message, title);
    };

    window.showError = function(message, title = 'Error') {
        toastr.error(message, title);
    };

    window.showWarning = function(message, title = 'Warning') {
        toastr.warning(message, title);
    };

    window.showInfo = function(message, title = 'Info') {
        toastr.info(message, title);
    };

    /**
     * Global Phone Number Validation
     * Ensures all phone/contact number inputs accept exactly 11 digits
     */
    function validatePhoneNumber(input) {
        // Remove all non-numeric characters
        let value = input.value.replace(/\D/g, '');

        // Limit to 11 digits
        if (value.length > 11) {
            value = value.substring(0, 11);
        }

        input.value = value;

        // Visual feedback
        if (value.length === 11) {
            input.style.borderColor = '#28a745';
            input.setCustomValidity('');
        } else if (value.length > 0) {
            input.style.borderColor = '#dc3545';
            input.setCustomValidity('Phone number must be exactly 11 digits');
        } else {
            input.style.borderColor = '';
            input.setCustomValidity('');
        }
    }

    // Auto-apply phone validation to all phone inputs
    document.addEventListener('DOMContentLoaded', function() {
        // Find all phone/contact number inputs
        const phoneSelectors = [
            'input[type="tel"]',
            'input[name="phone"]',
            'input[name="contact_number"]',
            'input[name="emergency_contact"]',
            'input[id*="phone"]',
            'input[id*="contact"]'
        ];

        const phoneInputs = document.querySelectorAll(phoneSelectors.join(', '));

        phoneInputs.forEach(input => {
            // Detect field type: number or text
            const isNumericField = input.dataset.type === 'numeric' || input.name.toLowerCase().includes('phone');

            if (isNumericField) {
                // Set numeric-specific attributes
                input.setAttribute('pattern', '\\d{11}');
                input.setAttribute('maxlength', '11');
                input.setAttribute('minlength', '11');
                input.setAttribute('inputmode', 'numeric');

                // Add numeric validation
                input.addEventListener('input', function() {
                    validatePhoneNumber(this);
                });

                input.addEventListener('paste', function(e) {
                    setTimeout(() => validatePhoneNumber(this), 10);
                });

                // Add placeholder if not exists
                if (!input.placeholder) {
                    input.placeholder = 'e.g., 09123456789';
                }

            } else {
                // Text field attributes (e.g., contact person)
                input.setAttribute('type', 'text');
                input.setAttribute('maxlength', '255');

                // Optional: you can add your own text validation function
                input.addEventListener('input', function() {
                    // For example: remove numbers if you want only letters
                    // this.value = this.value.replace(/\d/g, '');
                });

                if (!input.placeholder) {
                    input.placeholder = 'Enter contact person';
                }
            }
        });
    });

    // Display Laravel session flash messages
    @if(session('success'))
        showSuccess('{{ session('success') }}');
    @endif

    @if(session('error'))
        showError('{{ session('error') }}');
    @endif

    @if(session('warning'))
        showWarning('{{ session('warning') }}');
    @endif

    @if(session('info'))
        showInfo('{{ session('info') }}');
    @endif
</script>

@if(Auth::user()->isOperator())
<!-- Operator Profile Modal Script -->
<script>
$(document).ready(function() {
    // Load profile data when modal is shown
    $('#operatorProfileModal').on('show.bs.modal', function (e) {
        var modalContent = $('#operatorProfileContent');

        // Show loading state
        modalContent.html(`
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                <p class="mt-3">Loading profile...</p>
            </div>
        `);

        // Fetch profile data via AJAX
        $.ajax({
            url: '{{ route("operator.profile.view") }}',
            method: 'GET',
            success: function(response) {
                modalContent.html(response);
            },
            error: function(xhr, status, error) {
                modalContent.html(`
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Error loading profile:</strong> ${error}
                        <br>
                        <small>Please try again or contact support if the problem persists.</small>
                    </div>
                `);
            }
        });
    });
});
</script>
@endif

@stack('scripts')
</body>
</html>