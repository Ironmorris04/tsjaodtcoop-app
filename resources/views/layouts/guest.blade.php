<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TSJAODT Cooperative') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Responsive Enhancements */
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 1rem;
            }

            .auth-logo-container {
                margin-bottom: 2rem;
                text-align: center;
            }

            .auth-logo-container img,
            .auth-logo-container svg {
                width: 80px;
                height: 80px;
                filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            }

            .auth-logo-text {
                margin-top: 1rem;
                font-size: 1.5rem;
                font-weight: 700;
                color: white;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .auth-card {
                width: 100%;
                max-width: 28rem;
                background: white;
                border-radius: 1rem;
                padding: 2rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }

            /* Mobile Phones (up to 640px) */
            @media (max-width: 640px) {
                .auth-card {
                    padding: 1.5rem;
                    border-radius: 0.75rem;
                    max-width: 95%;
                }

                .auth-logo-container img,
                .auth-logo-container svg {
                    width: 60px;
                    height: 60px;
                }

                .auth-logo-text {
                    font-size: 1.25rem;
                }

                .auth-container {
                    padding: 0.5rem;
                }
            }

            /* Tablets (641px to 1024px) */
            @media (min-width: 641px) and (max-width: 1024px) {
                .auth-card {
                    max-width: 32rem;
                    padding: 2.25rem;
                }

                .auth-logo-container img,
                .auth-logo-container svg {
                    width: 70px;
                    height: 70px;
                }

                .auth-logo-text {
                    font-size: 1.375rem;
                }
            }

            /* Desktop (1025px and above) */
            @media (min-width: 1025px) {
                .auth-card {
                    max-width: 36rem;
                    padding: 2.5rem;
                }

                .auth-logo-container img,
                .auth-logo-container svg {
                    width: 90px;
                    height: 90px;
                }

                .auth-logo-text {
                    font-size: 1.75rem;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="auth-container">
            <div class="auth-logo-container">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-white" />
                </a>
                <div class="auth-logo-text">TSJAODT Cooperative</div>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>

        <!-- Phone Number Validation Script -->
        <script>
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
                    // Set attributes
                    input.setAttribute('pattern', '\\d{11}');
                    input.setAttribute('maxlength', '11');
                    input.setAttribute('minlength', '11');
                    input.setAttribute('inputmode', 'numeric');

                    // Add event listeners
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
                });
            });
        </script>
    </body>
</html>
