@extends('layouts.app')

@section('hide-content-header', true)
@section('content')
<div class="content-header">
    <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions - Operator Role</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="active">FAQs</li>
    </ol>
</div>

<div class="content">
    <div class="faq-container">

        <!-- Getting Started Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-rocket"></i> Getting Started
            </h2>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I complete my profile after registration?</span>
                </div>
                <div class="faq-answer">
                    <p>After your account is approved by the admin:</p>
                    <ol>
                        <li>Log in with your credentials</li>
                        <li>Click your name in the top-right corner</li>
                        <li>Select <strong>Profile</strong></li>
                        <li>Complete all required fields (Business info, Contact details)</li>
                        <li>Upload required documents</li>
                        <li>Click <strong>Save Changes</strong></li>
                    </ol>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>What documents do I need to submit?</span>
                </div>
                <div class="faq-answer">
                    <p>Required documents typically include:</p>
                    <ul>
                        <li>Business Permit</li>
                        <li>Official Receipt of Unit</li>
                        <li>Certificate of Registration</li>
                        <li>Driver License</li>
                        <li>Biodata for Driver</li>
                        <li>Driver License</li>
                        <li>Your Valid ID</li>
                    </ul>
                    <p class="tip"><i class="fas fa-lightbulb"></i> <strong>Tip:</strong> Scan documents clearly and save as PDF for best results.</p>
                </div>
            </div>
        </div>

        <!-- Drivers Management Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-id-card"></i> Drivers Management
            </h2>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I add a new driver?</span>
                </div>
                <div class="faq-answer">
                    <p>To add a driver to your roster:</p>
                    <ol>
                        <li>Go to <strong>Drivers</strong> from the sidebar</li>
                        <li>Click <strong>Add New Driver</strong></li>
                        <li>Fill in driver details (Name, License No., Contact)</li>
                        <li>Upload driver's license (front and back)</li>
                        <li>Click <strong>Save Driver</strong></li>
                    </ol>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I update driver information?</span>
                </div>
                <div class="faq-answer">
                    <p>To update driver details:</p>
                    <ol>
                        <li>Find the driver in your drivers list</li>
                        <li>Click <strong>Edit</strong></li>
                        <li>Update necessary information</li>
                        <li>Upload new documents if needed (expired license, etc.)</li>
                        <li>Click <strong>Update</strong></li>
                    </ol>
                    <p class="warning"><i class="fas fa-exclamation-triangle"></i> Keep license and certificate expiry dates updated to avoid compliance issues!</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>Can I deactivate a driver temporarily?</span>
                </div>
                <div class="faq-answer">
                    <p>Yes! If a driver is on leave or suspended:</p>
                    <ol>
                        <li>Go to the driver's profile</li>
                        <li>Click <strong>Deactivate</strong></li>
                        <li>Select reason (Leave, Suspension, Other)</li>
                        <li>Confirm action</li>
                    </ol>
                    <p>You can reactivate the driver anytime from their profile.</p>
                </div>
            </div>
        </div>

        <!-- Transport Units Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-bus"></i> Transport Units Management
            </h2>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I register a new transport unit?</span>
                </div>
                <div class="faq-answer">
                    <p>To add a vehicle to your fleet:</p>
                    <ol>
                        <li>Navigate to <strong>Transport Units</strong></li>
                        <li>Click <strong>Add New Unit</strong></li>
                        <li>Enter vehicle details (Plate No., Make, Model, Year)</li>
                        <li>Upload OR/CR (Official Receipt and Certificate of Registration)</li>
                        <li>Assign a driver to the unit</li>
                        <li>Click <strong>Register Unit</strong></li>
                    </ol>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I change the assigned driver for a unit?</span>
                </div>
                <div class="faq-answer">
                    <p>To reassign drivers:</p>
                    <ol>
                        <li>Open the transport unit details</li>
                        <li>Click <strong>Change Driver</strong></li>
                        <li>Select new driver from dropdown</li>
                        <li>Specify effective date</li>
                        <li>Click <strong>Update Assignment</strong></li>
                    </ol>
                    <p>The system maintains a history of all driver assignments.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>What if my unit's registration documents expire?</span>
                </div>
                <div class="faq-answer">
                    <p>Before documents expire:</p>
                    <ol>
                        <li>Go to the unit's profile</li>
                        <li>Click <strong>Update Documents</strong></li>
                        <li>Upload renewed OR/CR</li>
                        <li>Update expiry dates</li>
                        <li>Submit for verification</li>
                    </ol>
                    <p class="warning"><i class="fas fa-exclamation-triangle"></i> <strong>Important:</strong> Units with expired documents may be flagged and suspended from operations!</p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.faq-container {
    max-width: 1000px;
    margin: 0 auto;
}

.faq-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.faq-section-title {
    font-size: 20px;
    color: #4e73df;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.faq-item {
    margin-bottom: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.faq-question {
    padding: 15px 20px;
    background: #f8f9fc;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    color: #334155;
    transition: all 0.3s ease;
}

.faq-question:hover {
    background: #e2e8f0;
    color: #4e73df;
}

.faq-question i {
    color: #4e73df;
    transition: transform 0.3s ease;
    font-size: 12px;
}

.faq-item.active .faq-question i {
    transform: rotate(90deg);
}

.faq-answer {
    padding: 0 20px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.faq-item.active .faq-answer {
    padding: 20px;
    max-height: 1000px;
}

.faq-answer p {
    margin-bottom: 12px;
    color: #475569;
    line-height: 1.6;
}

.faq-answer ol, .faq-answer ul {
    margin-left: 20px;
    color: #475569;
    line-height: 1.8;
}

.faq-answer ol li, .faq-answer ul li {
    margin-bottom: 8px;
}

.faq-answer .tip {
    background: #ecfdf5;
    border-left: 4px solid #10b981;
    padding: 12px 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.faq-answer .tip i {
    color: #10b981;
}

.faq-answer .warning {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 12px 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.faq-answer .warning i {
    color: #f59e0b;
}

.faq-help-section {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    padding: 40px;
    border-radius: 12px;
    text-align: center;
    margin-top: 40px;
}

.faq-help-section i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.9;
}

.faq-help-section h3 {
    font-size: 24px;
    margin-bottom: 10px;
}

.faq-help-section p {
    font-size: 16px;
    margin-bottom: 20px;
    opacity: 0.95;
}

.faq-help-section .btn {
    background: white;
    color: #4e73df;
    border: none;
    padding: 12px 30px;
    font-weight: 600;
}

.faq-help-section .btn:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ accordion functionality
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');

            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // Optional search functionality if you add an input with id="faqSearch"
    const searchInput = document.getElementById('faqSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    if (searchTerm.length > 2) {
                        item.classList.add('active');
                    }
                } else {
                    item.style.display = 'none';
                }
            });

            document.querySelectorAll('.faq-section').forEach(section => {
                const visibleItems = section.querySelectorAll('.faq-item[style="display: block"]');
                section.style.display = visibleItems.length > 0 ? 'block' : 'none';
            });
        });
    }
});
</script>
@endsection
