@extends('layouts.app')

@section('title', 'Take Attendance')

@section('page-title', 'Take Meeting Attendance')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Home</a></li>
    <li>Take Attendance</li>
@endsection

@push('styles')
<style>
    .attendance-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .meeting-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .meeting-info-card h2 {
        margin: 0 0 20px 0;
        font-size: 28px;
        font-weight: 700;
    }

    .meeting-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .meeting-detail-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .meeting-detail-item i {
        font-size: 20px;
        opacity: 0.9;
    }

    .meeting-detail-item .detail-label {
        font-weight: 600;
        margin-right: 5px;
    }

    .attendance-form-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
    }

    .form-header h3 {
        margin: 0;
        font-size: 22px;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-actions {
        display: flex;
        gap: 10px;
    }

    .btn-quick-action {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-mark-all-present {
        background: #1cc88a;
        color: white;
    }

    .btn-mark-all-present:hover {
        background: #13855c;
        transform: translateY(-2px);
    }

    .btn-mark-all-absent {
        background: #e74a3b;
        color: white;
    }

    .btn-mark-all-absent:hover {
        background: #be2617;
        transform: translateY(-2px);
    }

    .operators-list {
        display: grid;
        gap: 15px;
    }

    .operator-item {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .operator-item:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }

    .operator-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .operator-details h4 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: #2c3e50;
    }

    .operator-details p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }

    .operator-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }

    .attendance-controls {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .status-toggle {
        display: flex;
        gap: 10px;
    }

    .status-btn {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .status-btn:hover {
        transform: translateY(-2px);
    }

    .status-btn.present {
        border-color: #1cc88a;
        color: #1cc88a;
    }

    .status-btn.present.active {
        background: #1cc88a;
        color: white;
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.3);
    }

    .status-btn.absent {
        border-color: #e74a3b;
        color: #e74a3b;
    }

    .status-btn.absent.active {
        background: #e74a3b;
        color: white;
        box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);
    }

    .status-btn.excused {
        border-color: #f6c23e;
        color: #f6c23e;
    }

    .status-btn.excused.active {
        background: #f6c23e;
        color: white;
        box-shadow: 0 4px 12px rgba(246, 194, 62, 0.3);
    }

    .excuse-reason-input {
        grid-column: 1 / -1;
        display: none;
    }

    .excuse-reason-input.visible {
        display: block;
    }

    .excuse-reason-input input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #f6c23e;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fffbf0;
    }

    .excuse-reason-input input:focus {
        outline: none;
        border-color: #d4a012;
        box-shadow: 0 0 0 3px rgba(246, 194, 62, 0.2);
    }

    .remarks-input {
        grid-column: 1 / -1;
    }

    .remarks-input input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .remarks-input input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .attendance-summary {
        display: flex;
        gap: 20px;
        font-size: 14px;
        color: #6c757d;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .summary-item strong {
        color: #2c3e50;
    }

    .form-actions {
        display: flex;
        gap: 10px;
    }

    .btn-submit {
        padding: 14px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-cancel {
        padding: 14px 30px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .meeting-details {
            grid-template-columns: 1fr;
        }

        .operator-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .attendance-controls {
            grid-template-columns: 1fr;
        }

        .form-footer {
            flex-direction: column;
            gap: 20px;
        }

        .attendance-summary {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="attendance-container">
    <!-- Meeting Information Card -->
    <div class="meeting-info-card">
        <h2><i class="fas fa-calendar-check"></i> {{ $meeting->title }}</h2>
        <div class="meeting-details">
            <div class="meeting-detail-item">
                <i class="fas fa-calendar-alt"></i>
                <span>
                    <span class="detail-label">Date:</span>
                    {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('F d, Y') }}
                </span>
            </div>
            <div class="meeting-detail-item">
                <i class="fas fa-clock"></i>
                <span>
                    <span class="detail-label">Time:</span>
                    {{ \Carbon\Carbon::parse($meeting->start_time, 'UTC')->format('g:i A') }} -
                    {{ \Carbon\Carbon::parse($meeting->end_time, 'UTC')->format('g:i A') }}
                </span>
            </div>
            <div class="meeting-detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>
                    <span class="detail-label">Location:</span>
                    {{ $meeting->location }}
                </span>
            </div>
            <div class="meeting-detail-item">
                <i class="fas fa-building"></i>
                <span>
                    <span class="detail-label">Address:</span>
                    {{ $meeting->address }}
                </span>
            </div>
        </div>
        @if($meeting->description)
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
            <strong><i class="fas fa-info-circle"></i> Description:</strong>
            <p style="margin: 10px 0 0 0; opacity: 0.95;">{{ $meeting->description }}</p>
        </div>
        @endif
    </div>

    <!-- Attendance Form Card -->
    <div class="attendance-form-card">
        <div class="form-header">
            <h3>
                <i class="fas fa-users"></i>
                Mark Attendance for All Operators
            </h3>
            <div class="quick-actions">
                <button type="button" class="btn-quick-action btn-mark-all-present" onclick="markAllStatus('present')">
                    <i class="fas fa-check-circle"></i> Mark All Present
                </button>
                <button type="button" class="btn-quick-action btn-mark-all-absent" onclick="markAllStatus('absent')">
                    <i class="fas fa-times-circle"></i> Mark All Absent
                </button>
            </div>
        </div>

        <form id="attendanceForm" method="POST" action="{{ route('meetings.submit-attendance', $meeting) }}">
            @csrf

            <div class="operators-list">
                @foreach($operators as $index => $operator)
                <div class="operator-item">
                    <div class="operator-info">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div class="operator-number">{{ $index + 1 }}</div>
                            <div class="operator-details">
                                <h4>{{ $operator->full_name }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="attendance-controls">
                        <input type="hidden" name="attendances[{{ $index }}][operator_id]" value="{{ $operator->id }}">

                        <div class="status-toggle">
                            <button type="button"
                                    class="status-btn present {{ isset($existingAttendance[$operator->id]) && $existingAttendance[$operator->id]->status === 'present' ? 'active' : '' }}"
                                    onclick="setStatus({{ $index }}, 'present')">
                                <i class="fas fa-check-circle"></i> Present
                            </button>
                            <button type="button"
                                    class="status-btn absent {{ isset($existingAttendance[$operator->id]) && $existingAttendance[$operator->id]->status === 'absent' ? 'active' : '' }}"
                                    onclick="setStatus({{ $index }}, 'absent')">
                                <i class="fas fa-times-circle"></i> Absent
                            </button>
                            <button type="button"
                                    class="status-btn excused {{ isset($existingAttendance[$operator->id]) && $existingAttendance[$operator->id]->status === 'excused' ? 'active' : '' }}"
                                    onclick="setStatus({{ $index }}, 'excused')">
                                <i class="fas fa-user-clock"></i> Excused
                            </button>
                        </div>
                        <input type="hidden" name="attendances[{{ $index }}][status]" id="status-{{ $index }}" value="{{ $existingAttendance[$operator->id]->status ?? 'absent' }}">

                        <div class="excuse-reason-input {{ isset($existingAttendance[$operator->id]) && $existingAttendance[$operator->id]->status === 'excused' ? 'visible' : '' }}" id="excuse-reason-{{ $index }}">
                            <input type="text"
                                   name="attendances[{{ $index }}][excuse_reason]"
                                   id="excuse-reason-input-{{ $index }}"
                                   placeholder="Reason for excuse (required) *"
                                   value="{{ isset($existingAttendance[$operator->id]) && $existingAttendance[$operator->id]->status === 'excused' ? $existingAttendance[$operator->id]->remarks : '' }}">
                        </div>

                        <div class="remarks-input">
                            <input type="text"
                                   name="attendances[{{ $index }}][remarks]"
                                   placeholder="Remarks (optional)"
                                   value="{{ isset($existingAttendance[$operator->id]) && $existingAttendance[$operator->id]->status !== 'excused' ? $existingAttendance[$operator->id]->remarks : '' }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="form-footer">
                <div class="attendance-summary">
                    <div class="summary-item">
                        <i class="fas fa-users"></i>
                        <span><strong id="totalCount">{{ $operators->count() }}</strong> Total Operators</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-check-circle" style="color: #1cc88a;"></i>
                        <span><strong id="presentCount">0</strong> Present</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-times-circle" style="color: #e74a3b;"></i>
                        <span><strong id="absentCount">{{ $operators->count() }}</strong> Absent</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-user-clock" style="color: #f6c23e;"></i>
                        <span><strong id="excusedCount">0</strong> Excused</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('dashboard') }}'">
                        Cancel
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Save Attendance
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function setStatus(index, status) {
        // Update hidden input
        document.getElementById('status-' + index).value = status;

        // Update button states
        const operatorItem = document.querySelectorAll('.operator-item')[index];
        const presentBtn = operatorItem.querySelector('.status-btn.present');
        const absentBtn = operatorItem.querySelector('.status-btn.absent');
        const excusedBtn = operatorItem.querySelector('.status-btn.excused');
        const excuseReasonDiv = document.getElementById('excuse-reason-' + index);

        // Remove active class from all buttons
        presentBtn.classList.remove('active');
        absentBtn.classList.remove('active');
        excusedBtn.classList.remove('active');

        // Add active class to selected button
        if (status === 'present') {
            presentBtn.classList.add('active');
            excuseReasonDiv.classList.remove('visible');
        } else if (status === 'absent') {
            absentBtn.classList.add('active');
            excuseReasonDiv.classList.remove('visible');
        } else if (status === 'excused') {
            excusedBtn.classList.add('active');
            excuseReasonDiv.classList.add('visible');
            // Focus on the excuse reason input
            document.getElementById('excuse-reason-input-' + index).focus();
        }

        updateSummary();
    }

    function markAllStatus(status) {
        const operatorItems = document.querySelectorAll('.operator-item');
        operatorItems.forEach((item, index) => {
            setStatus(index, status);
        });
    }

    function updateSummary() {
        const statusInputs = document.querySelectorAll('[id^="status-"]:not([id^="status-btn"])');
        let presentCount = 0;
        let absentCount = 0;
        let excusedCount = 0;

        statusInputs.forEach(input => {
            if (input.tagName === 'INPUT' && input.type === 'hidden') {
                if (input.value === 'present') {
                    presentCount++;
                } else if (input.value === 'absent') {
                    absentCount++;
                } else if (input.value === 'excused') {
                    excusedCount++;
                }
            }
        });

        document.getElementById('presentCount').textContent = presentCount;
        document.getElementById('absentCount').textContent = absentCount;
        document.getElementById('excusedCount').textContent = excusedCount;
    }

    // Initialize summary on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSummary();
    });

    // Form validation
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        const statusInputs = document.querySelectorAll('[id^="status-"]');
        let hasStatus = false;
        let missingExcuseReason = false;

        statusInputs.forEach(input => {
            if (input.tagName === 'INPUT' && input.type === 'hidden' && input.value) {
                hasStatus = true;

                // Check if excused status has a reason
                if (input.value === 'excused') {
                    const index = input.id.replace('status-', '');
                    const excuseReasonInput = document.getElementById('excuse-reason-input-' + index);
                    if (!excuseReasonInput.value.trim()) {
                        missingExcuseReason = true;
                        excuseReasonInput.style.borderColor = '#e74a3b';
                        excuseReasonInput.focus();
                    }
                }
            }
        });

        if (!hasStatus) {
            e.preventDefault();
            alert('Please mark attendance for at least one operator.');
            return false;
        }

        if (missingExcuseReason) {
            e.preventDefault();
            alert('Please provide a reason for all operators marked as excused.');
            return false;
        }

        return confirm('Are you sure you want to submit the attendance? This will save the records.');
    });
</script>
@endpush
