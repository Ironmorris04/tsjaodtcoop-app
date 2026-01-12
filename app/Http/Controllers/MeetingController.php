<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Operator;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    /**
     * Display a listing of the meetings.
     */
    public function index()
    {
        $meetings = Meeting::orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->paginate(15);

        return view('meetings.index', compact('meetings'));
    }

    /**
     * Display meetings for president (read-only, can only take attendance)
     */
    public function presidentIndex()
    {
        $meetings = Meeting::with(['meetingAttendances.penalty', 'meetingAttendances.operator.user'])
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->paginate(15);

        return view('president.meetings', compact('meetings'));
    }

    /**
     * Store a newly created meeting by president
     */
    public function presidentStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:general_assembly,board_of_directors',
            'meeting_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string',
        ]);

        // Combine date and time for meeting_time
        $meetingDateTime = $validated['meeting_date'] . ' ' . $validated['start_time'];

        $meeting = Meeting::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'meeting_date' => $validated['meeting_date'],
            'meeting_time' => $meetingDateTime,
            'location' => $validated['location'],
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
            'status' => 'scheduled',
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        // Log meeting creation
        AuditTrail::log(
            'created',
            "Created new meeting: {$meeting->title}",
            'Meeting',
            $meeting->id,
            $meeting->toArray()
        );

        return response()->json([
            'success' => true,
            'message' => 'Meeting created successfully',
            'meeting' => $meeting
        ]);
    }

    /**
     * Store a newly created meeting in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:general_assembly,board_of_directors',
            'meeting_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string',
        ]);

        // Combine date and time for meeting_time
        $meetingDateTime = $validated['meeting_date'] . ' ' . $validated['start_time'];

        $meeting = Meeting::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'meeting_date' => $validated['meeting_date'],
            'meeting_time' => $meetingDateTime,
            'location' => $validated['location'],
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
            'status' => 'scheduled',
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        // Log meeting creation
        AuditTrail::log(
            'created',
            "Created new meeting: {$meeting->title}",
            'Meeting',
            $meeting->id,
            $meeting->toArray()
        );

        return response()->json([
            'success' => true,
            'message' => 'Meeting created successfully',
            'meeting' => $meeting
        ]);
    }

    /**
     * Display the specified meeting.
     */
    public function show(Meeting $meeting)
    {
        $meeting->load(['meetingAttendances.operator.user', 'meetingAttendances.penalty']);

        return response()->json([
            'success' => true,
            'meeting' => [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'type' => $meeting->type,
                'meeting_date' => $meeting->meeting_date->format('Y-m-d'),
                'start_time' => $meeting->start_time,
                'end_time' => $meeting->end_time,
                'location' => $meeting->location,
                'address' => $meeting->address,
                'description' => $meeting->description,
                'status' => $meeting->status,
            ]
        ]);
    }

    /**
     * Update the specified meeting in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:general_assembly,board_of_directors',
            'meeting_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string',
        ]);

        // Combine date and time for meeting_time
        $meetingDateTime = $validated['meeting_date'] . ' ' . $validated['start_time'];

        // Capture original values before update
        $originalValues = $meeting->getOriginal();

        $meeting->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'meeting_date' => $validated['meeting_date'],
            'meeting_time' => $meetingDateTime,
            'location' => $validated['location'],
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        // Track changes for audit trail
        $changes = [];
        $changedFields = [];
        $fieldsToCheck = ['title', 'type', 'meeting_date', 'location', 'address', 'description', 'start_time', 'end_time'];

        foreach ($fieldsToCheck as $field) {
            $oldValue = $originalValues[$field] ?? null;
            $newValue = $meeting->$field;

            if ($oldValue != $newValue) {
                $changedFields[] = $field;
                $changes[$field] = [
                    'old' => $oldValue ?? 'None',
                    'new' => $newValue ?? 'None'
                ];
            }
        }

        if (!empty($changedFields)) {
            $description = "Updated meeting: {$meeting->title}";
            $description .= " (Changed: " . implode(', ', $changedFields) . ")";

            AuditTrail::log(
                'updated',
                $description,
                'Meeting',
                $meeting->id,
                $changes
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Meeting updated successfully',
            'meeting' => $meeting
        ]);
    }

    /**
     * Remove the specified meeting from storage.
     */
    public function destroy(Meeting $meeting)
    {
        $meetingTitle = $meeting->title;
        $meetingId = $meeting->id;
        
        $meeting->delete();

        // Log meeting deletion
        AuditTrail::log(
            'deleted',
            "Deleted meeting: {$meetingTitle}",
            'Meeting',
            $meetingId
        );

        return response()->json([
            'success' => true,
            'message' => 'Meeting deleted successfully'
        ]);
    }

    /**
     * Show the take attendance page for president
     */
    public function showTakeAttendance(Meeting $meeting)
    {
        $meetingDate = $meeting->meeting_date;

        // Build base query for approved and active operators who were members before or on the meeting date
        $operatorsQuery = Operator::where('approval_status', 'approved')
            ->where('status', 'active')
            ->where(function($query) use ($meetingDate) {
                $query->whereDate('created_at', '<=', $meetingDate)
                      ->orWhere(function($q) use ($meetingDate) {
                          $q->whereNotNull('approved_at')
                            ->whereDate('approved_at', '<=', $meetingDate);
                      });
            });

        // Filter by meeting type
        if ($meeting->type === 'board_of_directors') {
            $operatorsQuery->whereHas('officers', function($query) use ($meetingDate) {
                $query->where('is_active', true)
                      ->where('committee', 'board_of_directors')
                      ->where('effective_from', '<=', $meetingDate)
                      ->where('effective_to', '>=', $meetingDate);
            });
        }

        $operators = $operatorsQuery->with('user')
            ->orderBy('business_name', 'asc')
            ->get();

        // Get existing attendance records if any
        $existingAttendance = $meeting->meetingAttendances()
            ->with('operator')
            ->get()
            ->keyBy('operator_id');

        return view('meetings.take-attendance', compact('meeting', 'operators', 'existingAttendance'));
    }

    /**
     * Submit attendance records
     */
    public function submitAttendance(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.operator_id' => 'required|exists:operators,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
            'attendances.*.remarks' => 'nullable|string',
            'attendances.*.excuse_reason' => 'nullable|string|required_if:attendances.*.status,excused',
        ]);

        DB::transaction(function () use ($meeting, $validated) {
            // Delete existing attendance records and related penalties
            $meeting->meetingAttendances()->delete();
            \App\Models\Penalty::where('meeting_id', $meeting->id)->delete();

            // Get penalty settings
            $penaltyAmount = (float) \App\Models\Setting::get('penalty_amount_per_absence', 100.00);
            $penaltyDueDays = (int) \App\Models\Setting::get('penalty_due_days', 30);

            $presentCount = 0;
            $absentCount = 0;
            $excusedCount = 0;

            // Insert new attendance records
            foreach ($validated['attendances'] as $attendance) {
                $remarks = $attendance['remarks'] ?? null;
                if ($attendance['status'] === 'excused' && !empty($attendance['excuse_reason'])) {
                    $remarks = $attendance['excuse_reason'];
                }

                $attendanceRecord = $meeting->meetingAttendances()->create([
                    'operator_id' => $attendance['operator_id'],
                    'status' => $attendance['status'],
                    'remarks' => $remarks,
                    'checked_in_at' => $attendance['status'] === 'present' ? now() : null,
                ]);

                // Count attendance statuses
                if ($attendance['status'] === 'present') {
                    $presentCount++;
                } elseif ($attendance['status'] === 'absent') {
                    $absentCount++;
                    
                    // Create penalty for absent operators
                    \App\Models\Penalty::create([
                        'operator_id' => $attendance['operator_id'],
                        'meeting_id' => $meeting->id,
                        'meeting_attendance_id' => $attendanceRecord->id,
                        'amount' => $penaltyAmount,
                        'paid_amount' => 0,
                        'remaining_amount' => $penaltyAmount,
                        'status' => 'unpaid',
                        'reason' => 'Absent from meeting: ' . $meeting->title,
                        'due_date' => now()->addDays($penaltyDueDays),
                    ]);
                } elseif ($attendance['status'] === 'excused') {
                    $excusedCount++;
                }
            }

            // Update meeting status to ongoing
            if ($meeting->status === 'scheduled') {
                $meeting->update(['status' => 'ongoing']);
            }

            // Log attendance submission
            AuditTrail::log(
                'created',
                "Recorded attendance for meeting: {$meeting->title} (Present: {$presentCount}, Absent: {$absentCount}, Excused: {$excusedCount})",
                'MeetingAttendance',
                $meeting->id
            );
        });

        return redirect()->route('president.meetings')->with('success', 'Attendance recorded successfully!');
    }

    /**
     * Check for upcoming meeting (today or future)
     */
    public function checkUpcomingMeeting()
    {
        $today = now()->startOfDay();

        $upcomingMeeting = Meeting::where('meeting_date', '>=', $today)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->orderBy('meeting_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->first();

        if ($upcomingMeeting) {
            return response()->json([
                'hasUpcomingMeeting' => true,
                'meetingId' => $upcomingMeeting->id,
                'meeting' => $upcomingMeeting
            ]);
        }

        return response()->json([
            'hasUpcomingMeeting' => false,
            'message' => 'No upcoming meeting scheduled'
        ]);
    }

    /**
     * Get meeting attendance data for modal
     */
    public function getAttendanceData(Meeting $meeting)
    {
        $meetingDate = $meeting->meeting_date;

        // Build base query for approved and active operators who were members before or on the meeting date
        $operatorsQuery = Operator::where('approval_status', 'approved')
            ->where('status', 'active')
            ->where(function($query) use ($meetingDate) {
                $query->whereDate('created_at', '<=', $meetingDate)
                      ->orWhere(function($q) use ($meetingDate) {
                          $q->whereNotNull('approved_at')
                            ->whereDate('approved_at', '<=', $meetingDate);
                      });
            });

        // Filter by meeting type
        if ($meeting->type === 'board_of_directors') {
            $operatorsQuery->whereHas('officers', function($query) use ($meetingDate) {
                $query->where('is_active', true)
                      ->where('committee', 'board_of_directors')
                      ->where('effective_from', '<=', $meetingDate)
                      ->where('effective_to', '>=', $meetingDate);
            });
        }

        $operators = $operatorsQuery->with('user')
            ->orderBy('business_name', 'asc')
            ->get();

        // Get existing attendance records if any
        $existingAttendance = $meeting->meetingAttendances()
            ->with('operator')
            ->get()
            ->keyBy('operator_id')
            ->map(function($attendance) {
                $penalty = null;
                $penaltyStatus = null;

                if ($attendance->status === 'absent') {
                    $penalty = \App\Models\Penalty::where('meeting_attendance_id', $attendance->id)
                        ->first();

                    if ($penalty) {
                        $penaltyStatus = $penalty->status;
                    }
                }

                return [
                    'status' => $attendance->status,
                    'remarks' => $attendance->remarks,
                    'excuse_reason' => $attendance->status === 'excused' ? $attendance->remarks : null,
                    'penalty_status' => $penaltyStatus
                ];
            });

        return response()->json([
            'success' => true,
            'meeting' => $meeting,
            'operators' => $operators,
            'existingAttendance' => $existingAttendance
        ]);
    }

    /**
     * Get meeting details with attendance for viewing in modal
     */
    public function getMeetingDetails(Meeting $meeting)
    {
        $meeting->load(['meetingAttendances.operator.user', 'meetingAttendances.penalty']);

        $meetingData = [
            'id' => $meeting->id,
            'title' => $meeting->title,
            'type' => $meeting->type === 'general_assembly' ? 'General Assembly' : 'Board of Directors',
            'date' => $meeting->meeting_date->format('F d, Y'),
            'time' => ($meeting->start_time && $meeting->end_time)
                ? date('h:i A', strtotime($meeting->start_time)) . ' - ' . date('h:i A', strtotime($meeting->end_time))
                : $meeting->meeting_time->format('h:i A'),
            'location' => $meeting->location,
            'address' => $meeting->address,
            'status' => ucfirst($meeting->status),
            'description' => $meeting->description ?? 'No description provided',
        ];

        $attendances = $meeting->meetingAttendances->map(function($attendance) {
            return [
                'operator_name' => $attendance->operator->full_name ?? $attendance->operator->user->name ?? 'N/A',
                'business_name' => $attendance->operator->business_name ?? 'N/A',
                'status' => $attendance->status,
                'remarks' => $attendance->remarks ?? '-',
                'penalty' => $attendance->penalty
            ];
        });

        return response()->json([
            'success' => true,
            'meeting' => $meetingData,
            'attendances' => $attendances
        ]);
    }
}