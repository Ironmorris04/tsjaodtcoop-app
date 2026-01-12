<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tacloban San Jose Airport Operators Drivers Transport Cooperative</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/css/hero.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #047857 0%, #0891b2 50%, #0284c7 100%);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Navigation Bar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 1rem 0;
            animation: slideDown 0.5s ease-out;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.2rem;
            font-weight: 700;
            color: #0284c7;
        }

        .logo i {
            font-size: 1.8rem;
            margin-right: 0.5rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #334155;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #0284c7;
        }

        /* Hero Section */
/* Hero Section with Road Network and Aerial View Cars */
.hero {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0284c7 0%, #0891b2 50%, #06b6d4 100%);
            position: relative;
            overflow: hidden;
        }

        /* Grid system - Clean realistic roads */
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 85px,
                    #2d2d2d 85px,
                    #323232 92px,
                    #343434 100px,
                    #323232 108px,
                    #2d2d2d 115px,
                    transparent 115px,
                    transparent 200px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 85px,
                    #2d2d2d 85px,
                    #323232 92px,
                    #343434 100px,
                    #323232 108px,
                    #2d2d2d 115px,
                    transparent 115px,
                    transparent 200px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent 0px,
                    transparent 95px,
                    rgba(200, 180, 80, 0.3) 99px,
                    rgba(200, 180, 80, 0.3) 101px,
                    transparent 105px,
                    transparent 125px
                ),
                repeating-linear-gradient(
                    0deg,
                    transparent 0px,
                    transparent 95px,
                    rgba(200, 180, 80, 0.3) 99px,
                    rgba(200, 180, 80, 0.3) 101px,
                    transparent 105px,
                    transparent 125px
                );
            background-size: 
                200px 200px,
                200px 200px,
                30px 200px,
                200px 30px;
            background-position: 
                0 0,
                0 0,
                100px 0,
                0 100px;
            opacity: 1;
            z-index: 0;
        }

        .car {
            position: absolute;
            width: 24px;
            height: 36px;
            border-radius: 6px 6px 3px 3px;
            z-index: 1;
            box-shadow:
                0 4px 8px rgba(0, 0, 0, 0.5),
                inset 0 1px 3px rgba(255, 255, 255, 0.4);
            transform-origin: center center;
            transition: none;
            will-change: transform, left, top;
            pointer-events: none;
        }

        .car::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 4px;
            right: 4px;
            height: 7px;
            background: linear-gradient(180deg, rgba(255, 255, 220, 0.95) 0%, rgba(255, 255, 180, 0.8) 100%);
            border-radius: 3px 3px 1px 1px;
            box-shadow:
                0 0 10px rgba(255, 255, 200, 0.6),
                0 0 5px rgba(255, 255, 255, 0.4);
        }

        .car::after {
            content: '';
            position: absolute;
            bottom: 3px;
            left: 4px;
            right: 4px;
            height: 4px;
            background: linear-gradient(180deg, rgba(255, 50, 50, 0.8) 0%, rgba(200, 0, 0, 0.9) 100%);
            border-radius: 1px 1px 3px 3px;
            box-shadow:
                0 0 8px rgba(255, 0, 0, 0.5),
                0 0 4px rgba(255, 50, 50, 0.3);
        }

        .hero-content {
            position: relative;
            text-align: center;
            color: white;
            z-index: 5;
            max-width: 900px;
            padding: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            font-weight: 300;
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: white;
    color: #0284c7;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
        }

        /* About Section - BY-LAWS */
        .about-section {
            padding: 5rem 2rem;
            background: #f8fafc;
        }

        .about-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeIn 1s ease-out;
        }

        .about-title {
            font-size: 2.5rem;
            color: #0284c7;
            margin-bottom: 0.5rem;
        }

        .about-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            font-weight: 400;
        }

        /* BY-LAWS Introduction */
        .bylaws-intro {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 2rem;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }

        .bylaws-intro h3 {
            color: #0284c7;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .bylaws-intro h4 {
            color: #1e293b;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .intro-text {
            color: #64748b;
            line-height: 1.6;
            font-size: 1.05rem;
        }

        /* Accordion Styles */
        .bylaws-accordion {
            margin-top: 2rem;
        }

        .accordion-item {
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out;
        }

        .accordion-item:hover {
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.1);
        }

        .accordion-header {
            width: 100%;
            background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .accordion-header:hover {
            background: linear-gradient(135deg, #0369a1 0%, #0891b2 100%);
        }

        .article-number {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 700;
            min-width: 90px;
            text-align: center;
        }

        .article-title {
            flex: 1;
            font-weight: 600;
        }

        .accordion-header i {
            transition: transform 0.3s ease;
            font-size: 1rem;
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

        .accordion-content p {
            color: #1e293b;
            line-height: 1.8;
            margin-bottom: 1.2rem;
        }

        .accordion-content p:last-child {
            margin-bottom: 0;
        }

        .accordion-content ul,
        .accordion-content ol {
            margin: 1rem 0 1.5rem 1.5rem;
            color: #475569;
            line-height: 1.8;
        }

        .accordion-content li {
            margin-bottom: 0.8rem;
        }

        .accordion-content strong {
            color: #0284c7;
            font-weight: 600;
        }

        /* Cooperative Identity Section */
        .cooperative-identity {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-top: 3rem;
            animation: fadeInUp 1s ease-out;
        }

        .cooperative-identity > h3 {
            color: #0284c7;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: center;
            justify-content: center;
        }

        .identity-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 4px solid #0284c7;
            transition: all 0.3s ease;
        }

        .identity-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.15);
            border-left-width: 6px;
        }

        .identity-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.2rem;
        }

        .identity-header i {
            font-size: 1.5rem;
            color: #0284c7;
        }

        .identity-header h4 {
            color: #1e293b;
            font-size: 1.5rem;
            margin: 0;
            font-weight: 700;
        }

        .identity-text {
            color: #475569;
            font-size: 1.05rem;
            line-height: 1.8;
            margin: 0;
        }

        .identity-text strong {
            color: #0284c7;
            font-weight: 600;
        }

        .identity-intro {
            color: #475569;
            font-size: 1.05rem;
            line-height: 1.8;
            margin: 0 0 1.5rem 0;
            font-weight: 500;
        }

        .principles-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.2rem;
        }

        .principle-item {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.1);
            transition: all 0.3s ease;
        }

        .principle-item:hover {
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
        }

        .principle-number {
            background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(2, 132, 199, 0.3);
        }

        .principle-content {
            flex: 1;
        }

        .principle-content h5 {
            color: #0284c7;
            font-size: 1.2rem;
            margin: 0 0 0.8rem 0;
            font-weight: 700;
        }

        .principle-content p {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.7;
            margin: 0;
        }

        /* Founding Members Section */
        .founding-members {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-top: 3rem;
            animation: fadeInUp 1s ease-out;
        }

        .founding-members h3 {
            color: #0284c7;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .founding-intro {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .member-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 1.2rem;
            border-radius: 10px;
            border-left: 4px solid #0284c7;
            color: #1e293b;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }

        .member-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.15);
            border-left-width: 6px;
        }

        .certification-note {
            text-align: center;
            color: #0891b2;
            font-style: italic;
            font-weight: 500;
            padding-top: 1.5rem;
            border-top: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0284c7 0%, #0891b2 50%, #06b6d4 100%);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2.5fr 1.5fr 1.5fr;
            gap: 3rem;
            padding: 4rem 2rem;
        }

        .footer-section {
            animation: fadeInUp 1s ease-out;
        }

        .footer-about .footer-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            background: white;
            border-radius: 12px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }

        .footer-about .footer-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .footer-section h3 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #06b6d4;
            font-weight: 700;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #0284c7, #06b6d4);
            border-radius: 2px;
        }

        .footer-description {
            color: #cbd5e1;
            line-height: 1.8;
            font-size: 0.95rem;
            margin-top: 1rem;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.2rem;
            color: #cbd5e1;
            line-height: 1.6;
        }

        .footer-contact-item i {
            color: #0891b2;
            font-size: 1.2rem;
            margin-top: 0.2rem;
            min-width: 20px;
        }

        .footer-contact-item span {
            flex: 1;
        }

        .footer-section a {
            color: #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .footer-section a i {
            font-size: 0.7rem;
            color: #0891b2;
            transition: transform 0.3s ease;
        }

        .footer-section a:hover {
            color: #06b6d4;
            padding-left: 0.5rem;
        }

        .footer-section a:hover i {
            transform: translateX(5px);
        }

        .footer-highlights {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(6, 182, 212, 0.2);
        }

        .highlight-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(6, 182, 212, 0.08);
            border-radius: 10px;
            border-left: 3px solid #0891b2;
            transition: all 0.3s ease;
        }

        .highlight-item:hover {
            background: rgba(6, 182, 212, 0.15);
            transform: translateX(5px);
            border-left-width: 4px;
        }

        .highlight-item i {
            font-size: 1.8rem;
            color: #06b6d4;
            min-width: 35px;
            text-align: center;
        }

        .highlight-item div {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .highlight-item strong {
            color: #06b6d4;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .highlight-item span {
            color: #cbd5e1;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-top: 1px solid rgba(6, 182, 212, 0.2);
        }

        .footer-bottom-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-bottom p {
            color: #94a3b8;
            font-size: 0.9rem;
            margin: 0.3rem 0;
        }

        .footer-tagline {
            color: #0891b2;
            font-weight: 600;
            font-style: italic;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }

        /* Logo Styles */
        .navbar-logo {
            height: 50px;
            width: auto;
            margin-right: 0.75rem;
            object-fit: contain;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.75rem 0;
            }

            .navbar-container {
                padding: 0 1rem;
                gap: 0.75rem;
            }

            .logo {
                font-size: 0.95rem;
                display: flex;
                align-items: center;
            }

            .navbar-logo {
                height: 40px;
            }

            .nav-links {
                gap: 0.75rem;
                font-size: 0.85rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .hero {
                padding: 0 1rem;
                min-height: calc(100vh - 80px);
            }

            .hero-content {
                padding: 1rem;
                margin-top: 70px;
            }

            .hero-title {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .hero-subtitle {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }

            .cta-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .about-section {
                padding: 3rem 1rem;
            }

            .about-title {
                font-size: 1.8rem;
            }

            .about-subtitle {
                font-size: 1rem;
            }

            .bylaws-intro {
                padding: 1.5rem;
            }

            .bylaws-intro h3 {
                font-size: 1.4rem;
                flex-direction: column;
            }

            .bylaws-intro h4 {
                font-size: 1.1rem;
            }

            .accordion-header {
                padding: 1rem 1.2rem;
                flex-wrap: wrap;
                font-size: 1rem;
            }

            .article-number {
                min-width: 80px;
                font-size: 0.85rem;
            }

            .article-title {
                width: 100%;
                margin-top: 0.5rem;
                font-size: 0.95rem;
            }

            .accordion-content {
                padding: 0 1.2rem;
            }

            .accordion-item.active .accordion-content {
                padding: 1.5rem 1.2rem;
            }

            .cooperative-identity {
                padding: 1.5rem;
            }

            .cooperative-identity > h3 {
                font-size: 1.5rem;
            }

            .identity-card {
                padding: 1.5rem;
            }

            .identity-header h4 {
                font-size: 1.3rem;
            }

            .identity-text,
            .identity-intro {
                font-size: 1rem;
            }

            .principle-item {
                padding: 1.2rem;
                gap: 1rem;
            }

            .principle-number {
                width: 50px;
                height: 50px;
                font-size: 1rem;
            }

            .principle-content h5 {
                font-size: 1.1rem;
            }

            .principle-content p {
                font-size: 0.95rem;
            }

            .founding-members {
                padding: 1.5rem;
            }

            .founding-members h3 {
                font-size: 1.5rem;
            }

            .members-grid {
                grid-template-columns: 1fr;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 3rem 1.5rem;
            }

            .footer-about .footer-logo {
                width: 60px;
                height: 60px;
            }

            .footer-section h3 {
                font-size: 1.2rem;
            }

            .footer-highlights {
                margin-top: 1.5rem;
                padding-top: 1.5rem;
            }

            .highlight-item {
                padding: 0.8rem;
            }

            .highlight-item i {
                font-size: 1.5rem;
                min-width: 30px;
            }

            .footer-bottom {
                padding: .5rem 1rem;
            }

            .footer-bottom p {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="logo">
            <img src="{{ asset('images/tsjaodt-logo.png') }}" alt="TSJAODT Logo" class="navbar-logo">
            <span>TSJAODT Coop</span>
        </div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="{{ route('about.system') }}">About System</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
</nav>

    <!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content">
        <h1 class="hero-title">TACLOBAN SAN JOSE AIRPORT OPERATORS DRIVERS TRANSPORT COOPERATIVE</h1>
            <p class="hero-subtitle">Building a stronger community through reliable, safe, and professional transportation services</p>
            <div class="cta-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Access Portal
                </a>
                <a href="#" onclick="openInstructionModal(event)" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Join Cooperative
                </a>
            </div>
    </div>
</section>


    <!-- About Section - BY-LAWS -->
    <section class="about-section" id="about">
        <div class="about-container">
            <div class="about-header">
                <h2 class="about-title">About Our Cooperative</h2>
                <p class="about-subtitle">Established June 20, 2019 | Certified April 24, 2022</p>
            </div>

            <!-- Introduction -->
            <div class="bylaws-intro">
                <h3><i class="fas fa-book"></i> BY-LAWS</h3>
                <h4>TACLOBAN SAN JOSE AIRPORT OPERATORS AND DRIVERS TRANSPORT COOPERATIVE (TSJAODTC)</h4>
                <p class="intro-text">These By-Laws govern the operation and management of our cooperative, ensuring fair and democratic practices for all members.</p>
            </div>

            <!-- Articles Accordion -->
            <div class="bylaws-accordion">

                <!-- Article I -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article I</span>
                        <span class="article-title">Purposes and Goals</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Purpose</strong> - To undertake any and all kinds of business and/or activities that will enhance the economic and social well-being of the members and their dependents, and promote their collective self-interests.</p>
                        <p><strong>Section 2. Goals</strong> - The goals of TSJAODTC are as follows:</p>
                        <ul>
                            <li>To organize under one management and administration such transport utilities so as to promote the most effective and economical operation thereof</li>
                            <li>To promote fellowship, cooperation, and coordination among the operators, and between the operators and their drivers</li>
                            <li>To promote and improve the maintenance of the unit(s) of the members</li>
                            <li>To generate and increase revenue</li>
                            <li>To consolidate franchise(s) for common use</li>
                            <li>To acquire loan(s) to improve and buy new PUV(s) (Public Utility Vehicles)</li>
                            <li>To acquire or rent place or lot for the terminal facilities</li>
                            <li>To perform all other things related to, or in connection with the foregoing objectives of the Cooperative</li>
                        </ul>
                    </div>
                </div>

                <!-- Article II -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article II</span>
                        <span class="article-title">Membership</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Kinds of Members</strong> - The Cooperative shall have two kinds of members:</p>
                        <ul>
                            <li><strong>Regular Members:</strong> Those who have signified their intention to join the cooperative and have made the required payments</li>
                            <li><strong>Associate Members:</strong> Those who do not have the qualifications as regular members but desire to join the cooperative</li>
                        </ul>

                        <p><strong>Section 2. Qualifications of Regular Members:</strong></p>
                        <ul>
                            <li>Natural person</li>
                            <li>Of legal age</li>
                            <li>Of good moral character</li>
                            <li>Has fully paid his/her share capital contribution</li>
                            <li>Actively patronizes the economic services and programs of the cooperative</li>
                        </ul>

                        <p><strong>Section 3. Membership Requirements:</strong></p>
                        <ul>
                            <li>Duly accomplished membership application form</li>
                            <li>Payment of membership fee</li>
                            <li>Share capital contribution as determined by the Board</li>
                            <li>Submission of all required documents</li>
                        </ul>

                        <p><strong>Section 4. Membership Duties and Responsibilities:</strong></p>
                        <ul>
                            <li>Actively patronize cooperative services</li>
                            <li>Attend all meetings of the cooperative</li>
                            <li>Pay all financial obligations to the cooperative</li>
                            <li>Support all programs and activities</li>
                            <li>Maintain good standing in the cooperative</li>
                        </ul>

                        <p><strong>Section 5. Rights and Privileges:</strong></p>
                        <ul>
                            <li>Vote and be voted upon in elections</li>
                            <li>Avail of services offered by the cooperative</li>
                            <li>Receive patronage refunds and dividends</li>
                            <li>Access cooperative facilities and benefits</li>
                            <li>Participate in decision-making processes</li>
                        </ul>

                        <p><strong>Section 6. Termination of Membership:</strong></p>
                        <ul>
                            <li>Voluntary withdrawal</li>
                            <li>Death of member</li>
                            <li>Expulsion for cause</li>
                            <li>Failure to pay financial obligations</li>
                            <li>Loss of qualification as member</li>
                        </ul>
                    </div>
                </div>

                <!-- Article III -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article III</span>
                        <span class="article-title">Administration - General Assembly</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Powers of the General Assembly</strong> - The General Assembly shall have the supreme authority in the Cooperative and shall exercise all powers, including but not limited to:</p>
                        <ul>
                            <li>Determine and approve plans, programs, and policies</li>
                            <li>Elect and remove members of the Board of Directors</li>
                            <li>Approve annual budgets and financial reports</li>
                            <li>Authorize loans and credit lines</li>
                            <li>Amend the Articles of Cooperation and By-Laws</li>
                            <li>Decide on dissolution of the cooperative</li>
                        </ul>

                        <p><strong>Section 2. General Assembly Meetings:</strong></p>
                        <ul>
                            <li><strong>Regular Meeting:</strong> Held annually within ninety (90) days after the close of the fiscal year</li>
                            <li><strong>Special Meeting:</strong> May be called by the Board or upon written request of at least twenty percent (20%) of the members</li>
                        </ul>

                        <p><strong>Section 3. Quorum</strong> - At least twenty-five percent (25%) of all members entitled to vote shall constitute a quorum for any General Assembly meeting.</p>

                        <p><strong>Section 4. Voting Rights</strong> - Each regular member in good standing shall be entitled to one vote. Voting may be done in person or by authorized representative.</p>
                    </div>
                </div>

                <!-- Article IV -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article IV</span>
                        <span class="article-title">Board of Directors</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Composition</strong> - The Board of Directors shall be composed of not less than five (5) but not more than fifteen (15) members elected by the General Assembly.</p>

                        <p><strong>Section 2. Qualifications:</strong></p>
                        <ul>
                            <li>Regular member in good standing</li>
                            <li>At least twenty-five (25) years of age</li>
                            <li>Good moral character and has not been convicted of any crime</li>
                            <li>Actively engaged in business covered by the cooperative</li>
                            <li>Not related within fourth civil degree to another director</li>
                        </ul>

                        <p><strong>Section 3. Powers and Functions:</strong></p>
                        <ul>
                            <li>Exercise all powers of the General Assembly when not in session</li>
                            <li>Appoint and remove officers and employees</li>
                            <li>Approve operational plans and budgets</li>
                            <li>Authorize contracts and agreements</li>
                            <li>Create committees as necessary</li>
                            <li>Issue rules and regulations for cooperative operations</li>
                        </ul>

                        <p><strong>Section 4. Term of Office</strong> - Directors shall serve for a term of two (2) years and until their successors are elected and qualified.</p>

                        <p><strong>Section 5. Board Meetings</strong> - The Board shall meet regularly at least once a month. Special meetings may be called by the Chairman or upon request of majority of the directors.</p>

                        <p><strong>Section 6. Removal</strong> - Any director may be removed from office for cause by vote of two-thirds (2/3) of the members present at a General Assembly meeting.</p>
                    </div>
                </div>

                <!-- Article V -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article V</span>
                        <span class="article-title">Committees</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>The cooperative shall maintain the following committees to ensure effective governance and operations:</p>

                        <p><strong>1. Audit and Inventory Committee</strong></p>
                        <ul>
                            <li>Conducts annual audit of cooperative accounts</li>
                            <li>Reviews financial statements and records</li>
                            <li>Recommends improvements to financial management</li>
                            <li>Reports findings to the General Assembly</li>
                        </ul>

                        <p><strong>2. Election Committee</strong></p>
                        <ul>
                            <li>Supervises and conducts all elections</li>
                            <li>Ensures fair and democratic election process</li>
                            <li>Resolves election disputes</li>
                            <li>Proclaims winning candidates</li>
                        </ul>

                        <p><strong>3. Education and Training Committee</strong></p>
                        <ul>
                            <li>Develops and implements training programs</li>
                            <li>Conducts cooperative education for members</li>
                            <li>Promotes cooperative values and principles</li>
                            <li>Coordinates capacity building activities</li>
                        </ul>

                        <p><strong>4. Conciliation and Mediation Committee</strong></p>
                        <ul>
                            <li>Mediates disputes among members</li>
                            <li>Resolves conflicts through dialogue</li>
                            <li>Prevents escalation of disputes</li>
                            <li>Recommends solutions to the Board</li>
                        </ul>

                        <p><strong>5. Ethics Committee</strong></p>
                        <ul>
                            <li>Ensures compliance with ethical standards</li>
                            <li>Investigates complaints against members or officers</li>
                            <li>Recommends sanctions for violations</li>
                            <li>Promotes integrity and accountability</li>
                        </ul>

                        <p><strong>6. Gender and Development (GAD) Committee</strong></p>
                        <ul>
                            <li>Promotes gender equality and women empowerment</li>
                            <li>Ensures gender-responsive programs</li>
                            <li>Addresses gender-based concerns</li>
                            <li>Monitors GAD compliance</li>
                        </ul>
                    </div>
                </div>

                <!-- Article VI -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article VI</span>
                        <span class="article-title">Officers and Management</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Officers of the Cooperative</strong> - The officers shall be elected by the Board from among themselves and shall consist of:</p>
                        <ul>
                            <li><strong>Chairperson:</strong> Presides over all meetings and exercises general supervision</li>
                            <li><strong>Vice-Chairperson:</strong> Performs duties of Chairperson in their absence</li>
                            <li><strong>Secretary:</strong> Keeps records of all meetings and official documents</li>
                            <li><strong>Treasurer:</strong> Manages financial records and reports</li>
                            <li><strong>Manager:</strong> Oversees day-to-day operations</li>
                        </ul>

                        <p><strong>Section 2. Term of Office</strong> - Officers shall serve for a term of one (1) year or until their successors are elected.</p>

                        <p><strong>Section 3. Management Staff</strong> - The Board may appoint management staff to assist in the daily operations of the cooperative, including but not limited to accountant, bookkeeper, and administrative personnel.</p>

                        <p><strong>Section 4. Compensation</strong> - Officers and committee members may receive reasonable compensation and per diems as determined by the Board and approved by the General Assembly.</p>
                    </div>
                </div>

                <!-- Article VII -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article VII</span>
                        <span class="article-title">Capital Structure</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Sources of Capital:</strong></p>
                        <ul>
                            <li>Share capital contributions of members</li>
                            <li>Membership fees</li>
                            <li>Loans from financial institutions</li>
                            <li>Donations and grants</li>
                            <li>Retained earnings</li>
                            <li>Interest on deposits and investments</li>
                        </ul>

                        <p><strong>Section 2. Share Capital</strong> - Each member shall contribute share capital as determined by the Board and approved by the General Assembly. Share capital is withdrawable only upon termination of membership.</p>

                        <p><strong>Section 3. Membership Fee</strong> - A non-refundable membership fee shall be paid upon admission to the cooperative in an amount determined by the Board.</p>

                        <p><strong>Section 4. Borrowing Powers</strong> - The cooperative may borrow funds from any source to finance its operations, provided such loans are authorized by the Board and approved by the General Assembly when required by law.</p>

                        <p><strong>Section 5. Investment of Funds</strong> - Surplus funds may be invested in accordance with cooperative principles and subject to Board approval, ensuring safety and liquidity of investments.</p>
                    </div>
                </div>

                <!-- Article VIII -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article VIII</span>
                        <span class="article-title">Operations</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Vehicle Ownership and Management</strong> - Members shall retain ownership of their vehicles while operating under the cooperative's consolidated franchise and management system.</p>

                        <p><strong>Section 2. Franchise Consolidation</strong> - The cooperative shall consolidate all member franchises for common use and management, ensuring efficient utilization and compliance with regulatory requirements.</p>

                        <p><strong>Section 3. Revenue Sharing</strong> - Revenue generated from operations shall be distributed according to policies established by the Board and approved by the General Assembly, ensuring fair compensation for all members.</p>

                        <p><strong>Section 4. Maintenance Standards</strong> - All vehicles must meet safety and maintenance standards set by the cooperative and regulatory authorities. Regular inspections shall be conducted to ensure compliance.</p>

                        <p><strong>Section 5. Route and Schedule Management</strong> - The cooperative shall establish and manage routes, schedules, and dispatch systems to optimize service delivery and member income.</p>

                        <p><strong>Section 6. Terminal Facilities</strong> - The cooperative shall acquire or rent terminal facilities for the convenience of members, drivers, and passengers.</p>
                    </div>
                </div>

                <!-- Article IX -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article IX</span>
                        <span class="article-title">Distribution of Net Surplus</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Allocation of Net Surplus</strong> - The net surplus for any fiscal year shall be allocated in the following order:</p>
                        <ol>
                            <li><strong>Reserve Fund:</strong> Ten percent (10%) to the Reserve Fund until it equals the total share capital paid-up</li>
                            <li><strong>Education and Training Fund:</strong> Ten percent (10%) for education and training programs</li>
                            <li><strong>Community Development Fund:</strong> As determined by the General Assembly</li>
                            <li><strong>Optional Funds:</strong> As approved by the General Assembly</li>
                            <li><strong>Interest on Share Capital:</strong> Not exceeding the normal rate of return on capital</li>
                            <li><strong>Patronage Refund:</strong> Remaining surplus distributed to members based on patronage</li>
                        </ol>

                        <p><strong>Section 2. Patronage Refund</strong> - Members shall receive patronage refund proportionate to their business transactions with the cooperative during the fiscal year.</p>

                        <p><strong>Section 3. Payment Terms</strong> - Distribution of dividends and patronage refunds shall be made within ninety (90) days after approval by the General Assembly.</p>
                    </div>
                </div>

                <!-- Article X -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article X</span>
                        <span class="article-title">Settlement of Disputes</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Conciliation and Mediation</strong> - All disputes arising from cooperative operations shall first be submitted to the Conciliation and Mediation Committee for amicable settlement.</p>

                        <p><strong>Section 2. Arbitration</strong> - If conciliation fails, the dispute shall be submitted to arbitration in accordance with the Cooperative Code and its implementing rules.</p>

                        <p><strong>Section 3. Jurisdiction</strong> - The cooperative agrees to submit to the voluntary arbitration process of the Cooperative Development Authority for all disputes that cannot be resolved internally.</p>

                        <p><strong>Section 4. Good Faith</strong> - All parties shall act in good faith during dispute resolution proceedings and comply with decisions reached through proper channels.</p>
                    </div>
                </div>

                <!-- Article XI -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article XI</span>
                        <span class="article-title">Miscellaneous Provisions</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Fiscal Year</strong> - The fiscal year of the cooperative shall commence on January 1 and end on December 31 of each year.</p>

                        <p><strong>Section 2. Accounting System</strong> - The cooperative shall maintain a proper accounting system in accordance with generally accepted accounting principles and cooperative accounting standards.</p>

                        <p><strong>Section 3. Annual Audit</strong> - The books of accounts and financial statements shall be audited annually by an independent certified public accountant.</p>

                        <p><strong>Section 4. Reports and Compliance</strong> - The cooperative shall submit all required reports to the Cooperative Development Authority and other government agencies in accordance with law.</p>

                        <p><strong>Section 5. Seal</strong> - The cooperative shall have an official seal bearing its name and the year of registration.</p>

                        <p><strong>Section 6. Gender Sensitivity</strong> - The cooperative shall ensure gender-fair language and practices in all its operations and programs.</p>
                    </div>
                </div>

                <!-- Article XII -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span class="article-number">Article XII</span>
                        <span class="article-title">Amendments</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p><strong>Section 1. Amendment Process</strong> - These By-Laws may be amended, modified, or repealed by a vote of two-thirds (2/3) of the members present and constituting a quorum at any General Assembly meeting.</p>

                        <p><strong>Section 2. Notice Requirement</strong> - Notice of proposed amendments shall be sent to all members at least thirty (30) days before the General Assembly meeting.</p>

                        <p><strong>Section 3. CDA Approval</strong> - All amendments shall be submitted to the Cooperative Development Authority for approval and shall take effect only upon such approval.</p>

                        <p><strong>Section 4. Documentation</strong> - Amended By-Laws shall be properly documented and made available to all members.</p>
                    </div>
                </div>

            </div>

            <!-- Cooperative Identity Section -->
            <div class="cooperative-identity">
                <h3><i class="fas fa-fingerprint"></i> Statement on the Cooperative Identity</h3>

                <!-- Definition -->
                <div class="identity-card">
                    <div class="identity-header">
                        <i class="fas fa-info-circle"></i>
                        <h4>Definition</h4>
                    </div>
                    <p class="identity-text">
                        A cooperative is an autonomous united association of persons with common economic, social and cultural needs and aspirations through a jointly-owned and democratically controlled enterprise.
                    </p>
                </div>

                <!-- Values -->
                <div class="identity-card">
                    <div class="identity-header">
                        <i class="fas fa-heart"></i>
                        <h4>Values</h4>
                    </div>
                    <p class="identity-text">
                        Cooperatives are based on the values of <strong>self-help, responsibility, democracy, equality, equity and solidarity</strong>. In the tradition of their founders, cooperative members believe in the ethical values of <strong>honesty, openness, social responsibility and caring for others</strong>.
                    </p>
                </div>

                <!-- Principles -->
                <div class="identity-card">
                    <div class="identity-header">
                        <i class="fas fa-compass"></i>
                        <h4>Principles</h4>
                    </div>
                    <p class="identity-intro">
                        The cooperative principles are guidelines by which cooperatives put their values into practice:
                    </p>
                    <div class="principles-grid">
                        <div class="principle-item">
                            <div class="principle-number">1st</div>
                            <div class="principle-content">
                                <h5>Voluntary and Open Membership</h5>
                                <p>Cooperatives are voluntary organizations, open to all persons able to use their services and willing to accept the responsibilities of membership.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <div class="principle-number">2nd</div>
                            <div class="principle-content">
                                <h5>Democratic Member Control</h5>
                                <p>Cooperatives are democratic organizations controlled by their members, who actively participate in setting policies and making decisions.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <div class="principle-number">3rd</div>
                            <div class="principle-content">
                                <h5>Member Economic Participation</h5>
                                <p>Members contribute equitably to, and democratically control, the capital of their cooperative.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <div class="principle-number">4th</div>
                            <div class="principle-content">
                                <h5>Autonomy and Independence</h5>
                                <p>Cooperatives are autonomous, self-help organizations controlled by their members.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <div class="principle-number">5th</div>
                            <div class="principle-content">
                                <h5>Education, Training and Information</h5>
                                <p>Cooperatives provide education and training for their members, elected representatives, managers, and employees.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <div class="principle-number">6th</div>
                            <div class="principle-content">
                                <h5>Cooperation Among Cooperatives</h5>
                                <p>Cooperatives serve their members most effectively and strengthen the cooperative movement by working together.</p>
                            </div>
                        </div>
                        <div class="principle-item">
                            <div class="principle-number">7th</div>
                            <div class="principle-content">
                                <h5>Concern for Community</h5>
                                <p>Cooperatives work for the sustainable development of their communities through policies approved by their members.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Founding Members Section -->
            <div class="founding-members">
                <h3><i class="fas fa-users"></i> Founding Members</h3>
                <p class="founding-intro">This cooperative was established on <strong>June 20, 2019</strong> by the following founding members:</p>
                <div class="members-grid">
                    <div class="member-card">ARNOLD PELAYO</div>
                    <div class="member-card">AMADO R. BRINGAS</div>
                    <div class="member-card">RODOLFO T. REBATO</div>
                    <div class="member-card">MYRNA E. QUIBAN</div>
                    <div class="member-card">ARNOLD Y. FERNANDEZ</div>
                    <div class="member-card">MANUEL P. FERNANDEZ</div>
                    <div class="member-card">REX M. DEMAFELIZ</div>
                    <div class="member-card">ROLANDO E. MAHINAY</div>
                    <div class="member-card">RICHARD B. AMAGO</div>
                    <div class="member-card">MARLON S. TIRO</div>
                    <div class="member-card">DIONISIO M. CASIPE</div>
                    <div class="member-card">ROBERTO A. BALANSAG</div>
                    <div class="member-card">BERNADITO V. FLORES</div>
                    <div class="member-card">RIZALDO S. SY</div>
                    <div class="member-card">CRISPIN B. BALDONO</div>
                </div>
                <p class="certification-note"><i class="fas fa-certificate"></i> Certified by Board of Directors on April 24, 2022</p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-container">
            <div class="footer-section footer-about">
                <div class="footer-logo">
                    <img src="{{ asset('images/tsjaodt-logo.png') }}" alt="TSJAODT Logo">
                </div>
                <h3>TSJAODTC</h3>
                <p class="footer-description">Tacloban San Jose Airport Operators and Drivers Transport Cooperative - Building a stronger community through reliable, safe, and professional transportation services since 2019.</p>
            </div>

            <div class="footer-section">
                <h3>Contact Us</h3>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Tacloban San Jose Airport<br>Tacloban City, Leyte</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i>
                    <span>+63 123 456 7890</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>tsjaodtcooperative@gmail.com</span>
                </div>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="#home"><i class="fas fa-chevron-right"></i> Home</a>
                <a href="#about"><i class="fas fa-chevron-right"></i> About Us</a>
                <a href="#contact"><i class="fas fa-chevron-right"></i> Contact</a>
                <a href="{{ route('login') }}"><i class="fas fa-chevron-right"></i> Member Portal</a>
                <a href="{{ route('register') }}"><i class="fas fa-chevron-right"></i> Join Cooperative</a>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-container">
                <p> Tacloban San Jose Airport Operators and Drivers Transport Cooperative.</p>
                <!-- <p class="footer-tagline">Empowering Transport, Strengthening Communities</p> -->
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, starting car animation...');

            // Grid and traffic configuration
            const GRID_SIZE = 200;
            const ROAD_CENTER = 100;
            const CAR_SPEED = 2.0; // Normal speed
            const CAR_SPEED_SLOW = 0.8; // Slow speed when at intersection
            const CAR_SPEED_VERY_SLOW = 0.4; // Very slow when car ahead
            const CAR_SPEED_BOOST = 2.8; // Speed boost when crossing intersection
            const NUM_CARS = 5; // Number of cars (reduced to 5 for less intersection conflicts)
            const COLLISION_DETECTION_DISTANCE = 150; // Distance to detect other cars (increased)
            const INTERSECTION_SLOW_DISTANCE = 40; // Distance from intersection to start slowing (increased)
            const INTERSECTION_DETECTION_RANGE = 100; // Range to detect cars at upcoming intersection (increased)

            // Track which lanes are in use (prevent opposite direction spawning)
            const activeLanes = {
                // Format: 'x_100': 'DOWN' or 'UP', 'y_100': 'LEFT' or 'RIGHT'
            };

            // Track recent spawn directions to bias parallel spawns
            const recentSpawns = {
                vertical: 0,   // Count of UP/DOWN spawns
                horizontal: 0  // Count of LEFT/RIGHT spawns
            };

            // Last spawn orientation ('vertical' or 'horizontal')
            let lastSpawnOrientation = null;

            const heroElement = document.querySelector('.hero');
            console.log('Hero element:', heroElement);

            // Car color variations
            const colors = [
                'linear-gradient(180deg, #ff4444 0%, #cc0000 50%, #ff4444 100%)',
                'linear-gradient(180deg, #4444ff 0%, #0000cc 50%, #4444ff 100%)',
                'linear-gradient(180deg, #44ff44 0%, #00cc00 50%, #44ff44 100%)',
                'linear-gradient(180deg, #ffff44 0%, #cccc00 50%, #ffff44 100%)',
                'linear-gradient(180deg, #ff44ff 0%, #cc00cc 50%, #ff44ff 100%)',
                'linear-gradient(180deg, #44ffff 0%, #00cccc 50%, #44ffff 100%)',
                'linear-gradient(180deg, #ff8844 0%, #cc5500 50%, #ff8844 100%)',
                'linear-gradient(180deg, #88ff44 0%, #55cc00 50%, #88ff44 100%)',
            ];

            // Directions: adjusted for car sprite orientation (sprite points up by default)
            // UP = 0deg, RIGHT = 90deg, DOWN = 180deg, LEFT = 270deg
            const DIRECTIONS = {
                UP: 0,      // Move up: angle = 0° (sprite points up)
                RIGHT: 90,  // Move right: angle = 90° (sprite rotated right)
                DOWN: 180,  // Move down: angle = 180° (sprite points down)
                LEFT: 270   // Move left: angle = 270° (sprite rotated left)
            };

            class Car {
                constructor(allCars) {
                    this.element = document.createElement('div');
                    this.element.className = 'car';
                    this.element.style.background = colors[Math.floor(Math.random() * colors.length)];
                    const hero = document.querySelector('.hero');
                    if (hero) {
                        hero.appendChild(this.element);
                    } else {
                        console.error('Hero element not found!');
                    }

                    this.currentSpeed = CAR_SPEED;
                    this.allCars = allCars;

                    // Randomize behavior for this car
                    this.slowsAtIntersections = Math.random() < 0.6; // 60% of cars slow down at intersections
                    this.intersectionSlowSpeed = CAR_SPEED_SLOW + (Math.random() * 0.4 - 0.2); // Randomize slow speed

                    this.spawn();
                }

                spawn() {
                    // Get hero section dimensions
                    const hero = document.querySelector('.hero');
                    const heroRect = hero.getBoundingClientRect();
                    const viewportWidth = heroRect.width;
                    const viewportHeight = heroRect.height;

                    // Calculate number of grid lines visible
                    const numHorizontalLines = Math.ceil(viewportWidth / GRID_SIZE);
                    const numVerticalLines = Math.ceil(viewportHeight / GRID_SIZE);

                    // Try to find an available lane (avoid spawning opposite direction on same lane)
                    let attempts = 0;
                    let validSpawn = false;
                    const MIN_SPAWN_DISTANCE = 300; // Minimum distance from other cars when spawning

                    while (!validSpawn && attempts < 30) {
                        attempts++;

                        // Choose random grid line
                        const gridLineIndex = Math.floor(Math.random() * Math.max(numHorizontalLines, numVerticalLines));

                        // Biased direction selection based on last spawn
                        let direction;

                        // Adaptive parallel bias: increases with consecutive spawns in same orientation
                        // Base 70% chance, increases to 85% if 2+ cars in same orientation
                        const verticalCount = recentSpawns.vertical;
                        const horizontalCount = recentSpawns.horizontal;
                        let parallelBias = 0.70;

                        if (lastSpawnOrientation === 'vertical' && verticalCount >= 2) {
                            parallelBias = 0.85; // Strong bias to continue vertical traffic
                        } else if (lastSpawnOrientation === 'horizontal' && horizontalCount >= 2) {
                            parallelBias = 0.85; // Strong bias to continue horizontal traffic
                        }

                        // Reset bias if one orientation is dominating too much (balance traffic)
                        if (Math.abs(verticalCount - horizontalCount) >= 3) {
                            parallelBias = 0.30; // Reduce bias to balance traffic flow
                        }

                        if (lastSpawnOrientation && Math.random() < parallelBias) {
                            // Spawn parallel to last car
                            if (lastSpawnOrientation === 'vertical') {
                                // Choose UP (0) or DOWN (2)
                                direction = Math.random() < 0.5 ? 0 : 2;
                            } else {
                                // Choose RIGHT (1) or LEFT (3)
                                direction = Math.random() < 0.5 ? 1 : 3;
                            }
                        } else {
                            // Random direction (fallback or first spawn)
                            direction = Math.floor(Math.random() * 4);
                        }

                        switch(direction) {
                            case 0: // Coming from TOP, moving DOWN
                                this.x = (gridLineIndex % numHorizontalLines) * GRID_SIZE + ROAD_CENTER;
                                this.y = -50;
                                this.direction = DIRECTIONS.DOWN;
                                this.laneKey = 'x_' + this.x; // Vertical lane
                                this.orientation = 'vertical';

                                // Check if this lane is free or has same direction
                                if (!activeLanes[this.laneKey] || activeLanes[this.laneKey] === 'DOWN') {
                                    activeLanes[this.laneKey] = 'DOWN';
                                    validSpawn = true;
                                }
                                break;

                            case 1: // Coming from RIGHT, moving LEFT
                                this.x = viewportWidth + 50;
                                this.y = (gridLineIndex % numVerticalLines) * GRID_SIZE + ROAD_CENTER;
                                this.direction = DIRECTIONS.LEFT;
                                this.laneKey = 'y_' + this.y; // Horizontal lane
                                this.orientation = 'horizontal';

                                if (!activeLanes[this.laneKey] || activeLanes[this.laneKey] === 'LEFT') {
                                    activeLanes[this.laneKey] = 'LEFT';
                                    validSpawn = true;
                                }
                                break;

                            case 2: // Coming from BOTTOM, moving UP
                                this.x = (gridLineIndex % numHorizontalLines) * GRID_SIZE + ROAD_CENTER;
                                this.y = viewportHeight + 50;
                                this.direction = DIRECTIONS.UP;
                                this.laneKey = 'x_' + this.x; // Vertical lane
                                this.orientation = 'vertical';

                                if (!activeLanes[this.laneKey] || activeLanes[this.laneKey] === 'UP') {
                                    activeLanes[this.laneKey] = 'UP';
                                    validSpawn = true;
                                }
                                break;

                            case 3: // Coming from LEFT, moving RIGHT
                                this.x = -50;
                                this.y = (gridLineIndex % numVerticalLines) * GRID_SIZE + ROAD_CENTER;
                                this.direction = DIRECTIONS.RIGHT;
                                this.laneKey = 'y_' + this.y; // Horizontal lane
                                this.orientation = 'horizontal';

                                if (!activeLanes[this.laneKey] || activeLanes[this.laneKey] === 'RIGHT') {
                                    activeLanes[this.laneKey] = 'RIGHT';
                                    validSpawn = true;
                                }
                                break;
                        }

                        // Additional check: ensure minimum distance from other cars
                        if (validSpawn) {
                            let tooClose = false;
                            for (let otherCar of this.allCars) {
                                if (otherCar === this) continue;
                                const distance = Math.sqrt(
                                    Math.pow(this.x - otherCar.x, 2) +
                                    Math.pow(this.y - otherCar.y, 2)
                                );
                                if (distance < MIN_SPAWN_DISTANCE) {
                                    tooClose = true;
                                    validSpawn = false;
                                    break;
                                }
                            }
                        }
                    }

                    // Update global spawn tracking after successful spawn
                    if (validSpawn) {
                        lastSpawnOrientation = this.orientation;
                        if (this.orientation === 'vertical') {
                            recentSpawns.vertical++;
                        } else {
                            recentSpawns.horizontal++;
                        }
                    }

                    this.currentSpeed = CAR_SPEED;
                    console.log('🚗 Car spawned:', this.x.toFixed(0), this.y.toFixed(0), 'Direction:', this.direction, 'Orientation:', this.orientation, 'Lane:', this.laneKey);
                }

                // Calculate distance to nearest intersection
                getDistanceToNearestIntersection() {
                    // Find the nearest grid intersection coordinates
                    const gridXIndex = Math.round((this.x - ROAD_CENTER) / GRID_SIZE);
                    const gridYIndex = Math.round((this.y - ROAD_CENTER) / GRID_SIZE);

                    const intersectionX = gridXIndex * GRID_SIZE + ROAD_CENTER;
                    const intersectionY = gridYIndex * GRID_SIZE + ROAD_CENTER;

                    // Calculate distance based on direction of travel
                    let distance = 0;
                    switch(this.direction) {
                        case DIRECTIONS.UP:
                        case DIRECTIONS.DOWN:
                            // Traveling vertically - distance to nearest Y intersection
                            distance = Math.abs(this.y - intersectionY);
                            break;
                        case DIRECTIONS.LEFT:
                        case DIRECTIONS.RIGHT:
                            // Traveling horizontally - distance to nearest X intersection
                            distance = Math.abs(this.x - intersectionX);
                            break;
                    }

                    return distance;
                }

                // Check if there's a car ahead in the same lane and return distance
                checkCarAhead() {
                    let closestDistance = Infinity;
                    let foundCar = false;

                    for (let otherCar of this.allCars) {
                        if (otherCar === this) continue;

                        // Check if cars are on the same road and moving in same direction
                        if (this.direction === otherCar.direction) {
                            let distance = 0;
                            let isAhead = false;
                            let inSameLane = false;

                            switch(this.direction) {
                                case DIRECTIONS.UP:
                                    isAhead = otherCar.y < this.y;
                                    distance = this.y - otherCar.y;
                                    inSameLane = Math.abs(this.x - otherCar.x) < 30;
                                    break;
                                case DIRECTIONS.DOWN:
                                    isAhead = otherCar.y > this.y;
                                    distance = otherCar.y - this.y;
                                    inSameLane = Math.abs(this.x - otherCar.x) < 30;
                                    break;
                                case DIRECTIONS.LEFT:
                                    isAhead = otherCar.x < this.x;
                                    distance = this.x - otherCar.x;
                                    inSameLane = Math.abs(this.y - otherCar.y) < 30;
                                    break;
                                case DIRECTIONS.RIGHT:
                                    isAhead = otherCar.x > this.x;
                                    distance = otherCar.x - this.x;
                                    inSameLane = Math.abs(this.y - otherCar.y) < 30;
                                    break;
                            }

                            if (inSameLane && isAhead && distance > 0 && distance < closestDistance) {
                                closestDistance = distance;
                                foundCar = true;
                            }
                        }
                    }

                    return foundCar ? closestDistance : null;
                }

                // Check if there's a car at the upcoming intersection (crossing path)
                checkIntersectionConflict() {
                    // Find nearest intersection ahead
                    const gridXIndex = Math.round((this.x - ROAD_CENTER) / GRID_SIZE);
                    const gridYIndex = Math.round((this.y - ROAD_CENTER) / GRID_SIZE);

                    let nextIntersectionX, nextIntersectionY;

                    // Calculate next intersection based on direction
                    switch(this.direction) {
                        case DIRECTIONS.UP:
                            nextIntersectionX = gridXIndex * GRID_SIZE + ROAD_CENTER;
                            nextIntersectionY = (gridYIndex - 1) * GRID_SIZE + ROAD_CENTER;
                            break;
                        case DIRECTIONS.DOWN:
                            nextIntersectionX = gridXIndex * GRID_SIZE + ROAD_CENTER;
                            nextIntersectionY = (gridYIndex + 1) * GRID_SIZE + ROAD_CENTER;
                            break;
                        case DIRECTIONS.LEFT:
                            nextIntersectionX = (gridXIndex - 1) * GRID_SIZE + ROAD_CENTER;
                            nextIntersectionY = gridYIndex * GRID_SIZE + ROAD_CENTER;
                            break;
                        case DIRECTIONS.RIGHT:
                            nextIntersectionX = (gridXIndex + 1) * GRID_SIZE + ROAD_CENTER;
                            nextIntersectionY = gridYIndex * GRID_SIZE + ROAD_CENTER;
                            break;
                    }

                    // Check if any other car is at or near this intersection
                    for (let otherCar of this.allCars) {
                        if (otherCar === this) continue;
                        if (otherCar.direction === this.direction) continue; // Same direction, already handled by checkCarAhead

                        // Calculate distance from other car to the intersection
                        const distToIntersection = Math.sqrt(
                            Math.pow(otherCar.x - nextIntersectionX, 2) +
                            Math.pow(otherCar.y - nextIntersectionY, 2)
                        );

                        // If other car is at or very close to the intersection
                        if (distToIntersection < INTERSECTION_DETECTION_RANGE) {
                            return {
                                hasConflict: true,
                                otherCarDistance: distToIntersection,
                                otherCar: otherCar
                            };
                        }
                    }

                    return { hasConflict: false };
                }

                update() {
                    // Check for car ahead in same lane (returns distance or null)
                    const distanceToCarAhead = this.checkCarAhead();

                    // Check distance to nearest intersection
                    const distToIntersection = this.getDistanceToNearestIntersection();
                    const approachingIntersection = distToIntersection < INTERSECTION_SLOW_DISTANCE;
                    const veryCloseToIntersection = distToIntersection < 15;
                    const atIntersection = distToIntersection < 5;
                    const passedIntersection = this.wasAtIntersection && !atIntersection;

                    // Check for intersection conflict (car crossing at upcoming intersection)
                    const intersectionConflict = this.checkIntersectionConflict();

                    // Determine if this car should yield or speed up
                    let shouldYield = false;
                    let shouldSpeedUp = false;

                    if (intersectionConflict.hasConflict) {
                        // Another car is at the intersection
                        if (veryCloseToIntersection) {
                            // This car is very close to intersection - SPEED UP to cross quickly
                            shouldSpeedUp = true;
                        } else if (approachingIntersection) {
                            // This car is approaching but not at intersection yet - YIELD (slow down)
                            shouldYield = true;
                        }
                    }

                    // Adjust speed based on conditions - priority order
                    if (distanceToCarAhead !== null) {
                        // HIGHEST PRIORITY: Car ahead in same lane - adjust speed based on distance
                        if (distanceToCarAhead < 50) {
                            this.currentSpeed = 0.15; // Very close - almost stop (more cautious)
                        } else if (distanceToCarAhead < 100) {
                            this.currentSpeed = CAR_SPEED_VERY_SLOW; // Close - slow down significantly
                        } else if (distanceToCarAhead < 150) {
                            this.currentSpeed = CAR_SPEED_SLOW; // Moderate distance - slight slowdown
                        } else {
                            this.currentSpeed = CAR_SPEED; // Far enough - normal speed
                        }
                    } else if (shouldSpeedUp) {
                        // SECOND PRIORITY: Speed up to clear intersection when another car is waiting
                        this.currentSpeed = CAR_SPEED_BOOST;
                    } else if (shouldYield) {
                        // THIRD PRIORITY: Yield to car at intersection
                        this.currentSpeed = CAR_SPEED_VERY_SLOW;
                    } else if (approachingIntersection && this.slowsAtIntersections && !passedIntersection) {
                        // FOURTH PRIORITY: Normal intersection slowing (randomized per car)
                        this.currentSpeed = this.intersectionSlowSpeed;
                    } else {
                        // DEFAULT: Normal speed
                        this.currentSpeed = CAR_SPEED;
                    }

                    // Track if we were at intersection
                    this.wasAtIntersection = atIntersection;

                    // Move car in current direction at current speed (NO TURNS - just straight)
                    switch(this.direction) {
                        case DIRECTIONS.UP:
                            this.y -= this.currentSpeed;
                            break;
                        case DIRECTIONS.RIGHT:
                            this.x += this.currentSpeed;
                            break;
                        case DIRECTIONS.DOWN:
                            this.y += this.currentSpeed;
                            break;
                        case DIRECTIONS.LEFT:
                            this.x -= this.currentSpeed;
                            break;
                    }

                    // Get hero dimensions for boundary checking
                    const hero = document.querySelector('.hero');
                    const heroRect = hero.getBoundingClientRect();
                    const viewportWidth = heroRect.width;
                    const viewportHeight = heroRect.height;

                    // Respawn from a new edge when car leaves the screen
                    if (this.x < -100 || this.x > viewportWidth + 100 ||
                        this.y < -100 || this.y > viewportHeight + 100) {
                        this.spawn();
                        return;
                    }

                    // Update car visual position
                    if (!this.element) return;

                    this.element.style.left = this.x + 'px';
                    this.element.style.top = this.y + 'px';
                    this.element.style.transform = `translate(-50%, -50%) rotate(${this.direction}deg)`;
                }

                destroy() {
                    this.element.remove();
                }
            }

            // Create and manage cars
            const cars = [];

            // Spawn cars with staggered timing
            console.log('Spawning', NUM_CARS, 'cars...');
            for (let i = 0; i < NUM_CARS; i++) {
                setTimeout(() => {
                    console.log('Spawning car', i + 1);
                    cars.push(new Car(cars)); // Pass cars array for collision detection
                }, i * 1200); // Stagger spawn by 1200ms (increased from 600ms for better spacing)
            }

            // Animation loop
            function animate() {
                cars.forEach(car => car.update());
                requestAnimationFrame(animate);
            }

            // Start animation
            console.log('Starting animation loop...');
            animate();

            // Handle window resize - respawn cars to adjust to new grid
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    cars.forEach(car => car.spawn());
                }, 500);
            });
        });
    </script>

    <!-- Instruction Modal -->
    <div id="instructionModal" class="instruction-modal">
        <div class="instruction-modal-content">
            <div class="instruction-modal-header">
                <h2><i class="fas fa-list-alt"></i> INSTRUCTIONS</h2>
                <button class="instruction-close-btn" onclick="closeInstructionModal()">&times;</button>
            </div>
            <div class="instruction-modal-body">
                <div class="instruction-section">
                    <h3><i class="fas fa-user-plus"></i> Operator Membership Registration</h3>
                    <p>Complete the form to apply for membership. You'll need to visit our office to finalize your application</p>
                </div>

                <div class="instruction-section">
                    <h3><i class="fas fa-users"></i> Registration Process</h3>
                    <ol class="process-list">
                        <li>Complete the registration form</li>
                        <li>Membership application form will automatically download</li>
                        <li>Visit our office to pay membership fees and submit requirements</li>
                        <li>Wait for admin approval to access your account</li>
                    </ol>
                </div>
            </div>
            <div class="instruction-modal-footer">
                <button class="instruction-btn-secondary" onclick="closeInstructionModal()">Close</button>
                <button class="instruction-btn-primary" onclick="proceedToRegistration()">
                    Proceed <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Instruction Modal Styles */
        .instruction-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease-in-out;
        }

        .instruction-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .instruction-modal-content {
            background: white;
            border-radius: 16px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease-out;
        }

        .instruction-modal-header {
            background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
            color: white;
            padding: 24px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .instruction-modal-header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .instruction-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 32px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .instruction-close-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .instruction-modal-body {
            padding: 30px;
            overflow-y: auto;
            max-height: calc(90vh - 200px);
        }

        .instruction-section {
            margin-bottom: 30px;
        }

        .instruction-section:last-child {
            margin-bottom: 0;
        }

        .instruction-section h3 {
            color: #0284c7;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .instruction-section p {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        .process-list {
            list-style: none;
            counter-reset: process-counter;
            padding-left: 0;
            margin: 0;
        }

        .process-list li {
            counter-increment: process-counter;
            position: relative;
            padding-left: 40px;
            margin-bottom: 16px;
            color: #475569;
            line-height: 1.6;
        }

        .process-list li:last-child {
            margin-bottom: 0;
        }

        .process-list li::before {
            content: counter(process-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #0284c7, #0891b2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
        }

        .instruction-modal-footer {
            padding: 20px 30px;
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .instruction-btn-secondary,
        .instruction-btn-primary {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .instruction-btn-secondary {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .instruction-btn-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .instruction-btn-primary {
            background: linear-gradient(135deg, #0284c7, #0891b2);
            color: white;
        }

        .instruction-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Custom scrollbar for modal body */
        .instruction-modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .instruction-modal-body::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .instruction-modal-body::-webkit-scrollbar-thumb {
            background: #0284c7;
            border-radius: 10px;
        }

        .instruction-modal-body::-webkit-scrollbar-thumb:hover {
            background: #0369a1;
        }

        /* Responsive styles for instruction modal */
        @media (max-width: 768px) {
            .instruction-modal-content {
                width: 95%;
                max-height: 95vh;
                margin: 1rem;
            }

            .instruction-modal-header {
                padding: 20px;
            }

            .instruction-modal-header h2 {
                font-size: 18px;
            }

            .instruction-close-btn {
                font-size: 28px;
                width: 32px;
                height: 32px;
            }

            .instruction-modal-body {
                padding: 20px;
                max-height: calc(95vh - 180px);
            }

            .instruction-section h3 {
                font-size: 16px;
            }

            .instruction-section p {
                font-size: 14px;
            }

            .process-list li {
                padding-left: 35px;
                margin-bottom: 14px;
                font-size: 14px;
            }

            .process-list li::before {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }

            .instruction-modal-footer {
                padding: 16px 20px;
                flex-direction: column;
            }

            .instruction-btn-secondary,
            .instruction-btn-primary {
                width: 100%;
                justify-content: center;
                padding: 14px 20px;
            }
        }
    </style>

    <script>
        function openInstructionModal(event) {
            event.preventDefault();
            document.getElementById('instructionModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeInstructionModal() {
            document.getElementById('instructionModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function proceedToRegistration() {
            window.location.href = "{{ route('register') }}";
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('instructionModal');
            if (event.target === modal) {
                closeInstructionModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeInstructionModal();
            }
        });
    </script>

    <!-- Accordion JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordionHeaders = document.querySelectorAll('.accordion-header');

            accordionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const accordionItem = this.parentElement;
                    const isActive = accordionItem.classList.contains('active');

                    // Close all accordion items
                    document.querySelectorAll('.accordion-item').forEach(item => {
                        item.classList.remove('active');
                    });

                    // Toggle the clicked item
                    if (!isActive) {
                        accordionItem.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>