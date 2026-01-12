@extends('layouts.app')

@section('hide-content-header', true)
@section('content')
<div class="content-header">
    <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions - Admin Role</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="active">FAQs</li>
    </ol>
</div>

<div class="content">
    <div class="faq-container">

        <!-- General Information Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-info-circle"></i> General Information Management
            </h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I update the cooperative's general information?</span>
                </div>
                <div class="faq-answer">
                    <p>To update general information:</p>
                    <ol>
                        <li>Navigate to <strong>General Information</strong> from the sidebar menu</li>
                        <li>Click the <strong>Edit</strong> button</li>
                        <li>Update the fields (Cooperative Name, Address, Contact Details, etc.)</li>
                        <li>Click <strong>Save Changes</strong></li>
                    </ol>
                    <p class="tip"><i class="fas fa-lightbulb"></i> <strong>Tip:</strong> All changes are logged in the Audit Trail for security purposes.</p>
                </div>
            </div>

        </div>

        <!-- Pending Registrations Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-user-clock"></i> Pending Registrations
            </h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I approve a new operator registration?</span>
                </div>
                <div class="faq-answer">
                    <p>To approve a registration:</p>
                    <ol>
                        <li>Go to <strong>Pending Registrations</strong></li>
                        <li>Click <strong>View</strong> on the registration to review details</li>
                        <li>Verify all submitted documents and information</li>
                        <li>Click <strong>Approve</strong> to activate the account</li>
                        <li>The operator will receive an email notification</li>
                    </ol>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>What if I need to reject a registration?</span>
                </div>
                <div class="faq-answer">
                    <p>If a registration doesn't meet requirements:</p>
                    <ol>
                        <li>Click <strong>Reject</strong> on the registration</li>
                        <li>Provide a clear reason for rejection</li>
                        <li>The applicant will be notified and can reapply</li>
                    </ol>
                    <p class="warning"><i class="fas fa-exclamation-triangle"></i> Always provide detailed rejection reasons to help applicants correct their submissions.</p>
                </div>
            </div>

            <!-- Q15 -->
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I approve pending registrations?</span>
                </div>
                <div class="faq-answer">
                    <p>Go to <strong>Pending Approvals</strong> from your dashboard. Review each application and click <strong>Approve</strong> or <strong>Reject</strong>. The applicant will be notified via email.</p>
                </div>
            </div>

        </div>

        <!-- Operators Management Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-building"></i> Operators Management
            </h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I manage system users and roles?</span>
                </div>
                <div class="faq-answer">
                    <p>Go to <strong>Operators Management</strong> or <strong>Officers Management</strong> to assign or update user roles and permissions. You can define access rights for each role to control what modules users can access.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I deactivate an operator's account?</span>
                </div>
                <div class="faq-answer">
                    <p>To deactivate an operator:</p>
                    <ol>
                        <li>Navigate to <strong>Operators Management</strong></li>
                        <li>Find the operator and click <strong>"View Details"</strong> then click <strong>"Edit Operator"</strong></li>
                        <li>and choose <strong>"Unregister Operator"</strong>.</li>
                        <li>Enter the password</li>
                        <li>Confirm the action</li>
                    </ol>
                    <p><strong>Note:</strong> Suspended operators cannot log in until reactivated.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I reactivate an operator's account?</span>
                </div>
                <div class="faq-answer">
                    <p>To reactivate an operator:</p>
                    <ol>
                        <li>Navigate to <strong>Operators Management</strong></li>
                        <li>click <strong>"Archived Operators"</strong> look for the operator then click <strong>"Restore"</strong></li>
                        <li>Confirm the action</li>
                    </ol>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I permamently delete an operator's account?</span>
                </div>
                <div class="faq-answer">
                    <p>To permamently delete an operator:</p>
                    <ol>
                        <li>Navigate to <strong>Operators Management</strong></li>
                        <li>click <strong>"Archived Operators"</strong> look for the operator then click <strong>"Delete"</strong></li>
                        <li>Enter the password</li>
                        <li>Confirm the action</li>
                    </ol>
                    <p><strong>Note:</strong> All related to operator will be deleted! take caution when doing this action!.</p>
                </div>
            </div>

            <!-- Q17 -->
        </div>

        <!-- Reports Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-file-alt"></i> Reports & Annual Progress
            </h2>

            <!-- Q16 -->
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How do I generate the Annual Progress Report for CDA?</span>
                </div>
                <div class="faq-answer">
                    <p>Navigate to <strong>Reports → Annual Progress Report</strong>. Fill in the required fields, then click <strong>Generate PDF</strong>. The system will auto-populate data from the database for the selected year.</p>
                </div>
            </div>
        </div>

        <!-- System Issues Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-server"></i> System Issues
            </h2>

            <!-- Q18 -->
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>What should I do if the system is down or slow?</span>
                </div>
                <div class="faq-answer">
                    <p>Ensure you have a stable internet connection. If problems persist, contact the development team via the <strong>Contact Developers</strong> section in the system.</p>
                </div>
            </div>
        </div>

        <!-- Audit Trail Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-history"></i> Audit Trail
            </h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>What is the Audit Trail and why is it important?</span>
                </div>
                <div class="faq-answer">
                    <p>The Audit Trail is a comprehensive log of all system activities including:</p>
                    <ul>
                        <li>User logins and logouts</li>
                        <li>Data modifications (who changed what and when)</li>
                        <li>Approvals and rejections</li>
                        <li>Financial transactions</li>
                    </ul>
                    <p><strong>Why it matters:</strong> Ensures accountability, tracks suspicious activity, and provides evidence for audits.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-chevron-right"></i>
                    <span>How long are audit records kept?</span>
                </div>
                <div class="faq-answer">
                    <p>Audit records are permanently stored and cannot be deleted. You can filter by:</p>
                    <ul>
                        <li>Date range</li>
                        <li>User</li>
                        <li>Action type</li>
                        <li>Module (Operators, Meetings, Financial, etc.)</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Existing styles remain unchanged */
.faq-container { max-width: 1000px; margin: 0 auto; }
.faq-search-box { position: relative; margin-bottom: 30px; }
.faq-search-box input { padding-left: 45px; height: 50px; border-radius: 25px; border: 2px solid #e2e8f0; font-size: 16px; }
.faq-search-box i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 18px; }
.faq-section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.faq-section-title { font-size: 20px; color: #4e73df; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center; gap: 10px; }
.faq-item { margin-bottom: 15px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
.faq-question { padding: 15px 20px; background: #f8f9fc; cursor: pointer; display: flex; align-items: center; gap: 12px; font-weight: 600; color: #334155; transition: all 0.3s ease; }
.faq-question:hover { background: #e2e8f0; color: #4e73df; }
.faq-question i { color: #4e73df; transition: transform 0.3s ease; font-size: 12px; }
.faq-item.active .faq-question i { transform: rotate(90deg); }
.faq-answer { padding: 0 20px; max-height: 0; overflow: hidden; transition: all 0.3s ease; background: white; }
.faq-item.active .faq-answer { padding: 20px; max-height: 1000px; }
.faq-answer p { margin-bottom: 12px; color: #475569; line-height: 1.6; }
.faq-answer ol, .faq-answer ul { margin-left: 20px; color: #475569; line-height: 1.8; }
.faq-answer ol li, .faq-answer ul li { margin-bottom: 8px; }
.faq-answer .tip { background: #ecfdf5; border-left: 4px solid #10b981; padding: 12px 15px; border-radius: 6px; margin-top: 15px; }
.faq-answer .tip i { color: #10b981; }
.faq-answer .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 15px; border-radius: 6px; margin-top: 15px; }
.faq-answer .warning i { color: #f59e0b; }
.faq-help-section { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; padding: 40px; border-radius: 12px; text-align: center; margin-top: 40px; }
.faq-help-section i { font-size: 48px; margin-bottom: 15px; opacity: 0.9; }
.faq-help-section h3 { font-size: 24px; margin-bottom: 10px; }
.faq-help-section p { font-size: 16px; margin-bottom: 20px; opacity: 0.95; }
.faq-help-section .btn { background: white; color: #4e73df; border: none; padding: 12px 30px; font-weight: 600; }
.faq-help-section .btn:hover { background: #f1f5f9; transform: translateY(-2px); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(item => item.classList.remove('active'));
            if (!isActive) faqItem.classList.add('active');
        });
    });

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
                    if (searchTerm.length > 2) item.classList.add('active');
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
