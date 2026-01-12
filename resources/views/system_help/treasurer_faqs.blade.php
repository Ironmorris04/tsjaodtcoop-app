@extends('layouts.app')

@section('hide-content-header', true)
@section('content')
<div class="content-header">
    <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions - Treasurer Role</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="active">FAQs</li>
    </ol>
</div>

<div class="content">
    <div class="faq-container">

        <!-- Financial Transactions Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-money-bill-wave"></i> Financial Transactions
            </h2>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I record a transaction for cash in and cash out?</span>
                </div>
                <div class="faq-answer">
                    <p><strong>Cash In:</strong> Go to the <strong>"Total Operators" </strong> card, click <strong>"Action"</strong>, then select <strong>"Add Transaction"</strong> or <strong>"Unpaid Balance"</strong>. Fill in the required details.</p>
                    <p><strong>Cash Out:</strong> Go to the <strong>Cash Treasurer's Book </strong> and click <strong>"Add New Entry"</strong>.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>Can I generate financial reports in PDF?</span>
                </div>
                <div class="faq-answer">
                    <p><strong>No</strong>. Treasurer users have read-only access to financial reports. Only the Administrator is authorized to generate and download PDF reports.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I set or update particular prices (fees)?</span>
                </div>
                <div class="faq-answer">
                    <p>Navigate to <strong>Particular Prices Management</strong> from your dashboard. You can add, edit, or deactivate fee items as needed.</p>
                </div>
            </div>
        </div>

        <!-- Financial Reports Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-file-invoice-dollar"></i> Financial Reports
            </h2>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I verify payments received from operators?</span>
                </div>
                <div class="faq-answer">
                    <p>Go to the <strong>Cash Receipts Journal</strong> to view all payments. Each payment includes:</p>
                    <ul>
                        <li>Operator name</li>
                        <li>Amount received</li>
                        <li>Payment date</li>
                        <li>Receipt number</li>
                    </ul>
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
});
</script>
@endsection
