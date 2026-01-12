@extends('layouts.app')

@section('content')
<div style="padding: 0;">
    <!-- Page Header -->
    <div style="margin-bottom: 20px;">
        <h2 style="color: #343a40; font-size: 24px; font-weight: 700; margin: 0 0 8px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-clipboard-list" style="color: #667eea;"></i>
            Attendance Records
        </h2>
        <p style="color: #6c757d; margin: 0; font-size: 14px;">
            Comprehensive view of all meeting attendance records and analytics
        </p>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Total Meetings</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50; line-height: 1;">{{ number_format($totalMeetings) }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-users"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Active Operators</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50; line-height: 1;">{{ number_format($totalOperators) }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; flex-direction: column;">
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Avg Attendance</div>
                    <div style="font-size: 28px; font-weight: 700; color: #2c3e50; line-height: 1;">{{ $averageAttendanceRate }}%</div>
                </div>
            </div>
            <div style="height: 8px; background: #e9ecef; border-radius: 10px; overflow: hidden;">
                <div style="height: 100%; width: {{ $averageAttendanceRate }}%; background: linear-gradient(90deg, {{ $averageAttendanceRate >= 80 ? '#2ecc71, #27ae60' : ($averageAttendanceRate >= 60 ? '#f39c12, #e67e22' : '#e74c3c, #c0392b') }}); transition: width 0.3s ease;"></div>
            </div>
        </div>
    </div>

    <!-- Meetings Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow: hidden;">
        <div style="padding: 20px 25px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #343a40; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-list-ul"></i> Complete Meeting Records
            </h3>
            <span style="background: #f8f9fa; color: #495057; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                {{ $meetings->count() }} Meetings
            </span>
        </div>
        <div style="padding: 25px;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="meetingsTable" style="width: 100%; margin-bottom: 0;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th style="padding: 15px; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-clipboard"></i> Meeting
                            </th>
                            <th style="padding: 15px; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-calendar"></i> Date & Time
                            </th>
                            <th style="padding: 15px; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-map-marker-alt"></i> Location
                            </th>
                            <th style="padding: 15px; text-align: center; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-check" style="color: #2ecc71;"></i> Present
                            </th>
                            <th style="padding: 15px; text-align: center; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-times" style="color: #e74c3c;"></i> Absent
                            </th>
                            <th style="padding: 15px; text-align: center; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-percentage"></i> Rate
                            </th>
                            <th style="padding: 15px; text-align: center; font-weight: 700; font-size: 13px; text-transform: uppercase; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">
                                <i class="fas fa-eye"></i> Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($meetings as $meeting)
                        @php
                            $presentCount = $meeting->attendances->where('status', 'present')->count();
                            $absentCount = $meeting->attendances->where('status', 'absent')->count();
                            $totalAttendees = $meeting->attendances->count();
                            $attendanceRate = $totalAttendees > 0 ? round(($presentCount / $totalAttendees) * 100, 1) : 0;
                        @endphp
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s ease;">
                            <td style="padding: 15px; color: #495057; font-size: 14px;">
                                <strong style="color: #2c3e50;">{{ $meeting->title }}</strong>
                                @if($meeting->description)
                                    <br><small style="color: #6c757d;">{{ Str::limit($meeting->description, 60) }}</small>
                                @endif
                            </td>
                            <td style="padding: 15px; color: #495057; font-size: 14px;">
                                <strong>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('M d, Y') }}</strong>
                                <br><small style="color: #6c757d;">
                                    <i class="fas fa-clock"></i>
                                    {{ $meeting->start_time ? \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') : 'N/A' }}
                                </small>
                            </td>
                            <td style="padding: 15px; color: #495057; font-size: 14px;">
                                <div style="font-weight: 600;">{{ $meeting->location ?? 'N/A' }}</div>
                                @if($meeting->address)
                                    <small style="color: #6c757d;">{{ $meeting->address }}</small>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="display: inline-block; min-width: 40px; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; background: #d4edda; color: #155724;">
                                    {{ $presentCount }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="display: inline-block; min-width: 40px; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; background: #f8d7da; color: #721c24;">
                                    {{ $absentCount }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 5px;">
                                    <span style="font-weight: 700; color: {{ $attendanceRate >= 80 ? '#2ecc71' : ($attendanceRate >= 60 ? '#f39c12' : '#e74c3c') }};">
                                        {{ $attendanceRate }}%
                                    </span>
                                    <div style="width: 60px; height: 4px; background: #e9ecef; border-radius: 2px; overflow: hidden;">
                                        <div style="height: 100%; width: {{ $attendanceRate }}%; background: {{ $attendanceRate >= 80 ? '#2ecc71' : ($attendanceRate >= 60 ? '#f39c12' : '#e74c3c') }};"></div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <button onclick="viewAttendanceDetails({{ $meeting->id }})" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 14px; border-radius: 8px; border: none; font-weight: 600; font-size: 12px; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center;">
                                <i class="fas fa-clipboard-list" style="font-size: 48px; color: #bdc3c7; margin-bottom: 15px;"></i>
                                <p style="color: #7f8c8d; margin: 0;">No meeting records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Details Modal -->
<div id="attendanceModal" class="modal-overlay">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modalTitle" style="margin: 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clipboard-list"></i>
                <span>Attendance Details</span>
            </h3>
            <button onclick="closeModal()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; font-size: 24px; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.3s ease;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="modalContent">
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #667eea;"></i>
                <p style="color: #6c757d; margin-top: 15px;">Loading...</p>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background: #f8f9fc !important;
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-container {
    background: white;
    border-radius: 15px;
    width: 90%;
    max-width: 1000px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.modal-body {
    padding: 30px;
    overflow-y: auto;
    flex: 1;
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 4px;
}
</style>

@push('scripts')
<script>
function viewAttendanceDetails(meetingId) {
    const modal = document.getElementById('attendanceModal');
    const modalContent = document.getElementById('modalContent');

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Fetch attendance details
    fetch(apiUrl(`meeting/${meetingId}/attendance-details`))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAttendanceDetails(data.meeting, data.attendances);
            } else {
                modalContent.innerHTML = '<p style="text-align: center; color: #e74c3c;">Failed to load attendance details</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = '<p style="text-align: center; color: #e74c3c;">Error loading attendance details</p>';
        });
}

function displayAttendanceDetails(meeting, attendances) {
    const modalContent = document.getElementById('modalContent');
    const modalTitle = document.getElementById('modalTitle');

    modalTitle.innerHTML = `<i class="fas fa-clipboard-list"></i> <span>${meeting.title}</span>`;

    const present = attendances.filter(a => a.status === 'present').length;
    const absent = attendances.filter(a => a.status === 'absent').length;
    const total = attendances.length;
    const rate = total > 0 ? Math.round((present / total) * 100) : 0;

    let html = `
        <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 25px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                <div style="text-align: center;">
                    <div style="font-size: 12px; color: #6c757d; text-transform: uppercase; margin-bottom: 5px;">Total</div>
                    <div style="font-size: 24px; font-weight: 700; color: #2c3e50;">${total}</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 12px; color: #6c757d; text-transform: uppercase; margin-bottom: 5px;">Present</div>
                    <div style="font-size: 24px; font-weight: 700; color: #2ecc71;">${present}</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 12px; color: #6c757d; text-transform: uppercase; margin-bottom: 5px;">Absent</div>
                    <div style="font-size: 24px; font-weight: 700; color: #e74c3c;">${absent}</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 12px; color: #6c757d; text-transform: uppercase; margin-bottom: 5px;">Rate</div>
                    <div style="font-size: 24px; font-weight: 700; color: ${rate >= 80 ? '#2ecc71' : (rate >= 60 ? '#f39c12' : '#e74c3c')};">${rate}%</div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover" style="margin-bottom: 0;">
            <thead style="background: #f8f9fc;">
                <tr>
                    <th style="padding: 12px; font-weight: 700; font-size: 13px; color: #2c3e50;">#</th>
                    <th style="padding: 12px; font-weight: 700; font-size: 13px; color: #2c3e50;">Operator</th>
                    <th style="padding: 12px; font-weight: 700; font-size: 13px; color: #2c3e50;">Business</th>
                    <th style="padding: 12px; text-align: center; font-weight: 700; font-size: 13px; color: #2c3e50;">Status</th>
                    <th style="padding: 12px; font-weight: 700; font-size: 13px; color: #2c3e50;">Remarks</th>
                </tr>
            </thead>
            <tbody>
    `;

    attendances.forEach((attendance, index) => {
        const statusColor = attendance.status === 'present' ? '#d4edda' : '#f8d7da';
        const statusTextColor = attendance.status === 'present' ? '#155724' : '#721c24';
        const statusIcon = attendance.status === 'present' ? 'check' : 'times';

        html += `
            <tr style="transition: background 0.2s ease;">
                <td style="padding: 12px;">${index + 1}</td>
                <td style="padding: 12px;"><strong>${attendance.operator_name}</strong></td>
                <td style="padding: 12px; text-align: center;">
                    <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: ${statusColor}; color: ${statusTextColor};">
                        <i class="fas fa-${statusIcon}"></i> ${attendance.status.charAt(0).toUpperCase() + attendance.status.slice(1)}
                    </span>
                </td>
                <td style="padding: 12px; color: #6c757d;">${attendance.remarks || '-'}</td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    modalContent.innerHTML = html;
}

function closeModal() {
    const modal = document.getElementById('attendanceModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('attendanceModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// DataTables initialization
$(document).ready(function() {
    $('#meetingsTable').DataTable({
        "order": [[1, "desc"]],
        "pageLength": 25,
        "language": {
            "search": "Search meetings:",
            "lengthMenu": "Show _MENU_ meetings per page"
        }
    });
});
</script>
@endpush
@endsection
