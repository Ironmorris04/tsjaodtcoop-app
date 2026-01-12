<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\Requirement;
use App\Services\BalanceService;

use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    /**
     * Get all operators
     * Enhanced to format data consistently for the dashboard modal
     */
    public function getOperators()
    {
        $operators = Operator::with(['user', 'operatorDetail'])
            ->where('approval_status', 'approved')
            ->get()
            ->map(function($operator) {
                return [
                    'id' => $operator->id,
                    'user_id' => $operator->user ? $operator->user->user_id : 'N/A',
                    'full_name' => $operator->full_name,
                    'address' => $operator->address ?? 'N/A',
                    'contact_person' => $operator->contact_person ?? 'N/A',
                    'phone' => $operator->phone ?? 'N/A',
                    'email' => $operator->email ?? ($operator->user ? $operator->user->email : 'N/A'),
                    'id_number' => $operator->operatorDetail ? $operator->operatorDetail->id_number : 'N/A',
                    'status' => $operator->status ?? 'active',
                    'created_at' => $operator->created_at,
                ];
            });

        return response()->json($operators);
    }

    /**
     * Get operator details
     * Already well-structured, just added some safety checks
     */
    public function getOperatorDetail($id)
    {
        try {
            $operator = Operator::with(['drivers', 'units', 'user'])->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Operator not found'
            ], 404);
        }

        $totalAbsences = 0;
        $absencePenalty = 0;
        $finePaid = 0;
        $remainingFine = 0;
        try {
            $totalAbsences = $operator->totalAbsences();
            $absencePenalty = $operator->totalFineOwed();
            $finePaid = $operator->totalFinePaid();
            $remainingFine = $operator->remainingFineBalance();
        } catch (\Exception $e) {
            // If method doesn't exist, default to 0
        }

        $documentsDue = 0;
        $allDocuments = collect();
        try {
            // Get all expiring documents for operator and their drivers/units
            $allDocuments = $operator->getAllExpiringDocuments(30);
            $documentsDue = $allDocuments->count();
        } catch (\Exception $e) {
            // If documents table doesn't exist or error occurs, default to 0
            $documentsDue = 0;
            $allDocuments = collect();
        }

        $data = [
            'success' => true,
            'operator' => [
                'id' => $operator->id,
                'full_name' => $operator->full_name,
                'contact_person' => $operator->contact_person ?? 'N/A',
                'email' => $operator->email ?? ($operator->user ? $operator->user->email : 'N/A'),
                'phone' => $operator->phone ?? 'N/A',
                'status' => $operator->status ?? 'active',
                'business_permit_no' => $operator->business_permit_no ?? 'N/A',
                'address' => $operator->address ?? 'N/A',
            ],
            'total_drivers' => $operator->drivers()->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })->count(),
            'total_units' => $operator->units()->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })->count(),
            'journal_total' => 85000.00, // TODO: Calculate from actual subsidiary journal
            'balance' => BalanceService::calculateOperatorBalance($operator->id),
            //'balance' => 42500.00, // TODO: Calculate from actual balance
            'absences' => $totalAbsences, // Total absences count
            'absence_penalty' => $absencePenalty, // Total fine owed (absences × ₱100)
            'fine_paid' => $finePaid, // Total fine payments made
            'remaining_fine' => $remainingFine, // Remaining fine balance
            'documents_due' => $documentsDue, // Documents expiring within 30 days
            'drivers' => $operator->drivers->where(function($driver) {
                return $driver->approval_status === 'approved' || $driver->approval_status === null;
            })->map(function($driver) {
                return [
                    'id' => $driver->id,
                    'first_name' => $driver->first_name,
                    'last_name' => $driver->last_name,
                    'license_number' => $driver->license_number ?? 'N/A',
                    'status' => $driver->status ?? 'active',
                ];
            }),
            'units' => $operator->units->where(function($unit) {
                return $unit->approval_status === 'approved' || $unit->approval_status === null;
            })->map(function($unit) {
                return [
                    'id' => $unit->id,
                    'plate_number' => $unit->plate_number,
                    'model' => $unit->model ?? 'N/A',
                    'status' => $unit->status ?? 'active',
                ];
            }),
            'documents' => $allDocuments->map(function($doc) {
                $daysUntilExpiry = $doc->expiry_date ? now()->diffInDays($doc->expiry_date, false) : null;
                return [
                    'id' => $doc->id,
                    'type' => $doc->type,
                    'formatted_type' => $doc->formatted_type,
                    'document_number' => $doc->document_number ?? 'N/A',
                    'expiry_date' => $doc->expiry_date ? $doc->expiry_date->format('M d, Y') : 'No expiry',
                    'expiry_date_raw' => $doc->expiry_date ? $doc->expiry_date->toDateString() : null,
                    'days_until_expiry' => $daysUntilExpiry,
                    'status' => $doc->status ?? 'active',
                    'owner_name' => $doc->owner_name,
                    'documentable_type' => $doc->documentable_type,
                ];
            })->values()->toArray()
        ];

        return response()->json($data);
    }

    /**
     * Get all drivers
     * Enhanced to include driver_id, unit plate number, and operator business name
     */
    public function getDrivers()
    {
        $drivers = Driver::with(['operator.user', 'operator.units'])
            ->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })
            ->get()->map(function($driver) {
            // Find the unit assigned to this driver
            $assignedUnit = null;
            if ($driver->operator && $driver->operator->units) {
                $assignedUnit = $driver->operator->units->where('driver_id', $driver->id)->first();
            }

            return [
                'id' => $driver->id,
                'driver_id' => $driver->driver_id ?? 'DRV-' . str_pad($driver->id, 4, '0', STR_PAD_LEFT),
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'full_name' => $driver->full_name,
                'license_number' => $driver->license_number ?? 'N/A',
                'status' => $driver->status ?? 'active',
                'plate_number' => $assignedUnit ? $assignedUnit->plate_number : 'Not Assigned',
                'operator' => $driver->operator ? [
                    'id' => $driver->operator->id,
                    'full_name' => $driver->operator->full_name
                ] : null,
                // Include original timestamps if needed
                'created_at' => $driver->created_at,
            ];
        });

        return response()->json($drivers);
    }

    /**
     * Get all units
     * Enhanced to include unit_id and current driver assignment
     */
    public function getUnits()
    {
        $units = Unit::with(['operator.user', 'driver'])
            ->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })
            ->get()->map(function($unit) {
            return [
                'id' => $unit->id,
                'unit_id' => $unit->unit_id ?? 'UNT-' . str_pad($unit->id, 4, '0', STR_PAD_LEFT),
                'plate_number' => $unit->plate_number,
                'model' => $unit->model ?? 'N/A',
                'status' => $unit->status ?? 'active',
                'business_permit_number' => $unit->business_permit_no ?? 'N/A',
                'body_number' => $unit->body_number ?? 'N/A',
                'franchise_case' => $unit->franchise_case ?? 'N/A',
                'coding_number' => $unit->coding_number ?? 'N/A',
                'police_number' => $unit->police_number ?? 'N/A',
                'operator' => $unit->operator ? [
                    'id' => $unit->operator->id,
                    'full_name' => $unit->operator->full_name
                ] : null,
                'driver' => $unit->driver ? [
                    'id' => $unit->driver->id,
                    'name' => $unit->driver->first_name . ' ' . $unit->driver->last_name,
                    'full_name' => $unit->driver->full_name,
                    'license_number' => $unit->driver->license_number ?? 'N/A'
                ] : null,
                // Include original timestamps if needed
                'created_at' => $unit->created_at,
            ];
        });

        return response()->json($units);
    }

    /**
     * Get operator's drivers (for logged-in operator)
     * No changes needed - works perfectly as is
     */
    public function getMyDrivers()
    {
        $user = auth()->user();
        $operator = $user->operator;

        if (!$operator) {
            return response()->json(['drivers' => []]);
        }

        $drivers = $operator->drivers()
            ->where('approval_status', 'approved')
            ->with('unit')
            ->get()
            ->map(function($driver) {
                // Get the assigned unit ID from the relationship
                $assignedUnit = Unit::where('driver_id', $driver->id)->first();

                return [
                    'id' => $driver->id,
                    'driver_id' => $driver->driver_id, // Driver User ID from database
                    'user_id' => $driver->driver_id, // Alias for compatibility
                    'first_name' => $driver->first_name,
                    'last_name' => $driver->last_name,
                    'full_name' => $driver->full_name,
                    'license_number' => $driver->license_number,
                    'license_type' => $driver->license_type,
                    'phone' => $driver->phone,
                    'status' => $driver->status,
                    'assigned_unit_id' => $assignedUnit ? $assignedUnit->id : null,
                    'assigned_unit_plate' => $assignedUnit ? $assignedUnit->plate_no : null,
                ];
            });

        return response()->json(['drivers' => $drivers]);
    }

    /**
     * Get operator's units (for logged-in operator)
     * No changes needed - works perfectly as is
     */
    public function getMyUnits()
    {
        $user = auth()->user();
        $operator = $user->operator;

        if (!$operator) {
            return response()->json(['units' => []]);
        }

        $units = $operator->units()
            ->where('approval_status', 'approved')
            ->with(['driver'])
            ->get()
            ->map(function($unit) {
                return [
                    'id' => $unit->id,
                    'user_id' => $unit->unit_id,
                    'plate_no' => $unit->plate_no,
                    'year_model' => $unit->year_model,
                    'franchise_case' => $unit->franchise_case,
                    'lto_or_number' => $unit->lto_or_number,
                    'lto_cr_number' => $unit->lto_cr_number,
                    'status' => $unit->status,
                    'driver_id' => $unit->driver_id,
                    'driver_name' => $unit->driver ? $unit->driver->full_name : null,
                ];
            });

        return response()->json(['units' => $units]);
    }

    /**
     * Get meeting attendance for the current operator
     * Returns all meetings with the operator's attendance status
     */
    public function getMyMeetingAttendance()
    {
        $user = auth()->user();
        $operator = $user->operator;

        if (!$operator) {
            return response()->json([
                'success' => true,
                'meetings' => [],
                'summary' => [
                    'total_meetings' => 0,
                    'present_count' => 0,
                    'absent_count' => 0,
                    'excused_count' => 0
                ]
            ]);
        }

        // Get all meetings with the operator's attendance record
        $meetings = \App\Models\Meeting::orderBy('meeting_date', 'desc')
            ->get()
            ->map(function($meeting) use ($operator) {
                // Get the attendance record for this operator
                $attendance = $meeting->attendances()
                    ->where('operator_id', $operator->id)
                    ->first();

                return [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'description' => $meeting->description,
                    'meeting_date' => $meeting->meeting_date ? $meeting->meeting_date->format('F d, Y') : 'N/A',
                    'meeting_date_raw' => $meeting->meeting_date ? $meeting->meeting_date->toDateString() : null,
                    'start_time' => $meeting->start_time,
                    'end_time' => $meeting->end_time,
                    'location' => $meeting->location ?? $meeting->address ?? 'N/A',
                    'type' => $meeting->type ?? 'regular',
                    'status' => $meeting->status ?? 'completed',
                    'attendance_status' => $attendance ? $attendance->status : 'no_record',
                    'remarks' => $attendance ? $attendance->remarks : null,
                    'checked_in_at' => $attendance && $attendance->checked_in_at
                        ? $attendance->checked_in_at->format('g:i A')
                        : null,
                ];
            });

        // Calculate summary statistics
        $presentCount = $meetings->where('attendance_status', 'present')->count();
        $absentCount = $meetings->where('attendance_status', 'absent')->count();
        $excusedCount = $meetings->where('attendance_status', 'excused')->count();

        return response()->json([
            'success' => true,
            'meetings' => $meetings,
            'summary' => [
                'total_meetings' => $meetings->count(),
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'excused_count' => $excusedCount
            ]
        ]);
    }

    /**
     * Get single driver details
     * Enhanced to include all driver information for the detailed modal view
     */
    public function getDriverDetail($id)
    {
        $driver = Driver::with(['operator.user', 'unit'])->findOrFail($id);

        // Calculate age from birthdate
        $age = null;
        if ($driver->birthdate) {
            try {
                $age = \Carbon\Carbon::parse($driver->birthdate)->age;
            } catch (\Exception $e) {
                $age = null;
            }
        }

        // Check license validity status
        $licenseStatus = 'valid';
        $daysUntilExpiry = null;
        if ($driver->license_expiry) {
            $daysUntilExpiry = now()->startOfDay()->diffInDays($driver->license_expiry, false);
            if ($daysUntilExpiry < 0) {
                $licenseStatus = 'expired';
            } elseif ($daysUntilExpiry <= 30) {
                $licenseStatus = 'expiring_soon';
            }
        }

        $data = [
            'id' => $driver->id,
            'driver_id' => $driver->driver_id,
            'first_name' => $driver->first_name,
            'last_name' => $driver->last_name,
            'full_name' => $driver->full_name,
            'birthdate' => $driver->birthdate ? $driver->birthdate->format('F d, Y') : null,
            'birthdate_raw' => $driver->birthdate ? $driver->birthdate->toDateString() : null,
            'age' => $age,
            'sex' => $driver->sex ? ucfirst($driver->sex) : null,
            'phone' => $driver->phone,
            'email' => $driver->email,
            'address' => $driver->address,
            'emergency_contact' => $driver->emergency_contact,
            'status' => $driver->status ?? 'active',
            'hire_date' => $driver->hire_date ? $driver->hire_date->format('F d, Y') : null,

            // Photo URLs
            'photo_url' => $driver->photo_url,
            'biodata_photo_url' => $driver->biodata_photo_url,
            'license_photo_url' => $driver->license_photo_url,

            // License Information
            'license_number' => $driver->license_number,
            'license_type' => $driver->license_type,
            'license_expiry' => $driver->license_expiry ? $driver->license_expiry->format('F d, Y') : null,
            'license_expiry_raw' => $driver->license_expiry ? $driver->license_expiry->toDateString() : null,
            'license_restrictions' => $driver->license_restrictions,
            'dl_codes' => $driver->dl_codes,
            'license_status' => $licenseStatus,
            'days_until_expiry' => $daysUntilExpiry,

            // Assigned unit info
            'assigned_unit' => $driver->unit ? [
                'id' => $driver->unit->id,
                'plate_no' => $driver->unit->plate_no,
                'body_number' => $driver->unit->body_number,
            ] : null,

            // Operator info
            'operator' => $driver->operator ? [
                'id' => $driver->operator->id,
                'full_name' => $driver->operator->full_name,
            ] : null
        ];

        return response()->json($data);
    }

    /**
     * Get expiring documents (within 30 days)
     * For the Expiring Soon modal on the dashboard
     * Includes: Driver Licenses, Business Permits, ORs, CRs, LTO CRs, LTO ORs, and Document model records
     */
    public function getExpiringDocuments()
    {
        $expiringDocuments = collect([]);
        $today = now()->startOfDay();
        $thirtyDaysFromNow = now()->addDays(30)->endOfDay();

        try {
            // ============================================
            // DRIVER DOCUMENTS
            // ============================================

            $drivers = \App\Models\Driver::with('operator.user')
                ->whereNotNull('license_expiry')
                ->where('license_expiry', '>=', $today)
                ->where('license_expiry', '<=', $thirtyDaysFromNow)
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })
                ->get();

            foreach ($drivers as $driver) {
                $daysRemaining = now()->startOfDay()->diffInDays($driver->license_expiry, false);
                $expiringDocuments->push([
                    'owner_name' => $driver->full_name,
                    'document_type' => "Driver's License",
                    'days_remaining' => $daysRemaining,
                    'expiry_date' => $driver->license_expiry->format('M d, Y')
                ]);
            }

            // ============================================
            // UNIT DOCUMENTS
            // ============================================

            // Get all approved units with any validity/expiry dates
            $units = \App\Models\Unit::with('operator.user')
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })
                ->get();

            foreach ($units as $unit) {
                $ownerName = 'Unit: ' . $unit->plate_no;

                // 1. Business Permit Validity
                if ($unit->business_permit_validity) {
                    $expiryDate = $unit->business_permit_validity;
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push([
                            'owner_name' => $ownerName,
                            'document_type' => 'Business Permit',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate->format('M d, Y')
                        ]);
                    }
                }

                // 2. OR Validity (or_date_issued + 1 year)
                if ($unit->or_date_issued) {
                    $expiryDate = $unit->or_date_issued->copy();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push([
                            'owner_name' => $ownerName,
                            'document_type' => 'OR (Official Receipt)',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate->format('M d, Y')
                        ]);
                    }
                }

                // 3. CR Validity
                if ($unit->cr_validity) {
                    $expiryDate = $unit->cr_validity;
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push([
                            'owner_name' => $ownerName,
                            'document_type' => 'CR (Certificate of Registration)',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate->format('M d, Y')
                        ]);
                    }
                }

                // 4. LTO CR (lto_cr_date_issued + 1 year)
                if ($unit->lto_cr_date_issued) {
                    $expiryDate = $unit->lto_cr_date_issued->copy()->addYear();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push([
                            'owner_name' => $ownerName,
                            'document_type' => 'LTO CR',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate->format('M d, Y')
                        ]);
                    }
                }

                // 5. LTO OR (lto_or_date_issued + 1 year)
                if ($unit->lto_or_date_issued) {
                    $expiryDate = $unit->lto_or_date_issued->copy()->addYear();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push([
                            'owner_name' => $ownerName,
                            'document_type' => 'LTO OR',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate->format('M d, Y')
                        ]);
                    }
                }

                // ============================================
                // REQUIREMENTS (CDA, BIR, TAX, BUSINESS PERMIT)
                // ============================================

                $requirementLabels = [
                    'cda_compliance'    => 'CDA Compliance',
                    'tax_exemption'    => 'Tax Exemption Certificate',
                    'bir_registration' => 'BIR Registration',
                    'business_permit'  => 'Business Permit',
                ];

                $requirements = Requirement::whereNotNull('expiry_date')
                    ->whereBetween('expiry_date', [
                        $today->toDateString(),
                        $thirtyDaysFromNow->toDateString()
                    ])
                    ->orderBy('expiry_date')
                    ->get()
                    ->groupBy('type');

                foreach ($requirements as $type => $items) {
                    $requirement = $items->first();

                    $daysRemaining = now()->startOfDay()
                        ->diffInDays($requirement->expiry_date, false);

                    $expiringDocuments->push([
                        'owner_name'     => 'Cooperative Requirement',
                        'document_type'  => $requirementLabels[$type] ?? $type,
                        'days_remaining'=> $daysRemaining,
                        'expiry_date'   => $requirement->expiry_date->format('M d, Y'),
                    ]);
                }


            }

            // ============================================
            // ============================================

            if (class_exists('App\Models\Document')) {
                $modelDocuments = \App\Models\Document::with(['documentable'])
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '>=', $today)
                    ->where('expiry_date', '<=', $thirtyDaysFromNow)
                    ->whereNotIn('status', ['expired', 'renewed'])
                    ->get();

                foreach ($modelDocuments as $doc) {
                    $daysRemaining = now()->startOfDay()->diffInDays($doc->expiry_date, false);
                    $expiringDocuments->push([
                        'owner_name' => $doc->owner_name,
                        'document_type' => $doc->formatted_type,
                        'days_remaining' => $daysRemaining,
                        'expiry_date' => $doc->expiry_date->format('M d, Y')
                    ]);
                }
            }

            $sortedDocuments = $expiringDocuments->sortBy('days_remaining')->values();

            return response()->json($sortedDocuments);

        } catch (\Exception $e) {
            \Log::error('Error fetching expiring documents API: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get full operator details for President dashboard
     */
    public function getOperatorDetails($id)
    {
        $operator = Operator::with(['user', 'drivers', 'units', 'operatorDetail', 'dependents'])->findOrFail($id);

        // Build operator detail data
        $operatorDetailData = null;
        if ($operator->operatorDetail) {
            $detail = $operator->operatorDetail;

            // Calculate age from birthdate
            $age = 'N/A';
            if ($detail->birthdate) {
                try {
                    $age = \Carbon\Carbon::parse($detail->birthdate)->age;
                } catch (\Exception $e) {
                    $age = 'N/A';
                }
            }

            $operatorDetailData = [
                'first_name' => $detail->first_name ?? 'N/A',
                'middle_name' => $detail->middle_name ?? '',
                'last_name' => $detail->last_name ?? 'N/A',
                'full_name' => $detail->full_name ?? 'N/A',
                'age' => $age,
                'birthdate' => $detail->birthdate ? $detail->birthdate->format('F d, Y') : 'N/A',
                'birthplace' => $detail->birthplace ?? 'N/A',
                'religion' => $detail->religion ?? 'N/A',
                'citizenship' => $detail->citizenship ?? 'N/A',
                'occupation' => $detail->occupation ?? 'N/A',
                'sex' => $detail->sex ? ucfirst($detail->sex) : 'N/A',
                'civil_status' => $detail->civil_status ? ucfirst($detail->civil_status) : 'N/A',
                'indigenous_people' => $detail->indigenous_people ? ucfirst($detail->indigenous_people) : 'No',
                'pwd' => $detail->pwd ? ucfirst($detail->pwd) : 'No',
                'senior_citizen' => $detail->senior_citizen ? ucfirst($detail->senior_citizen) : 'No',
                'fourps_beneficiary' => $detail->fourps_beneficiary ? ucfirst($detail->fourps_beneficiary) : 'No',
                'id_type' => $detail->id_type ?? 'N/A',
                'id_number' => $detail->id_number ?? 'N/A',
                'profile_photo_url' => $detail->profile_photo_url,
                'valid_id_url' => $detail->valid_id_url,
            ];
        }

        // Build dependents data
        $dependentsData = $operator->dependents->map(function($dependent) {
            return [
                'id' => $dependent->id,
                'name' => $dependent->name,
                'age' => $dependent->age ?? 'N/A',
                'relation' => $dependent->relation ?? 'N/A',
            ];
        })->values();

        $data = [
            'success' => true,
            'operator' => [
                'id' => $operator->id,
                'full_name' => $operator->full_name,
                'contact_person' => $operator->contact_person ?? $operator->user->name ?? 'N/A',
                'phone' => $operator->phone ?? 'N/A',
                'email' => $operator->email ?? $operator->user->email ?? 'N/A',
                'address' => $operator->address ?? 'N/A',
                'business_permit_no' => $operator->business_permit_no ?? 'N/A',
                'status' => $operator->status ?? 'active',
                'membership_form_url' => $operator->membership_form_url,
                'membership_form_preview_url' => $operator->membership_form_preview_url,
                'user' => [
                    'id' => $operator->user->id,
                    'user_id' => $operator->user->user_id,
                    'name' => $operator->user->name,
                    'email' => $operator->user->email,
                ],
                'operator_detail' => $operatorDetailData,
                'dependents' => $dependentsData,
                'drivers' => $operator->drivers->where(function($driver) {
                    return $driver->approval_status === 'approved' || $driver->approval_status === null;
                })->map(function($driver) {
                    return [
                        'id' => $driver->id,
                        'first_name' => $driver->first_name,
                        'last_name' => $driver->last_name,
                        'full_name' => $driver->full_name ?? ($driver->first_name . ' ' . $driver->last_name),
                        'sex' => $driver->sex ? ucfirst($driver->sex) : 'N/A',
                        'birthdate' => $driver->birthdate ? $driver->birthdate->toDateString() : null,
                        'phone' => $driver->phone ?? 'N/A',
                        'email' => $driver->email ?? 'N/A',
                        'address' => $driver->address ?? 'N/A',
                        'license_number' => $driver->license_number ?? 'N/A',
                        'license_type' => $driver->license_type ?? 'N/A',
                        'license_expiry' => $driver->license_expiry ? $driver->license_expiry->format('F d, Y') : 'N/A',
                        'approved_at' => $driver->approved_at ? $driver->approved_at->format('F d, Y') : 'N/A',
                        'emergency_contact' => $driver->emergency_contact ?? 'N/A',
                        'status' => $driver->status ?? 'active',
                        'photo_url' => $driver->photo_url,
                        'biodata_photo_url' => $driver->biodata_photo_url,
                        'license_photo_url' => $driver->license_photo_url,

                    ];
                })->values(),
                'units' => $operator->units->where(function($unit) {
                    return $unit->approval_status === 'approved' || $unit->approval_status === null;
                })->map(function($unit) {
                    return [
                        'id' => $unit->id,
                        'user_id' => $unit->user_id ?? 'N/A',
                        'plate_no' => $unit->plate_no,
                        'body_number' => $unit->body_number ?? 'N/A',
                        'engine_number' => $unit->engine_number ?? 'N/A',
                        'chassis_number' => $unit->chassis_number ?? 'N/A',
                        'coding_number' => $unit->coding_number ?? 'N/A',
                        'police_number' => $unit->police_number ?? 'N/A',
                        'color' => $unit->color ?? 'N/A',
                        'type' => ucfirst($unit->type ?? 'N/A'),
                        'brand' => $unit->brand ?? 'N/A',
                        'model' => $unit->model ?? 'N/A',
                        'year' => $unit->year ?? 'N/A',
                        'year_model' => $unit->year_model ?? 'N/A',
                        'capacity' => $unit->capacity ?? 'N/A',
                        'unit_or_number' => $unit->or_number ?? 'N/A',
                        'unit_or_date_validity' => $unit->or_date_issued ? $unit->or_date_issued->format('F d, Y') : 'N/A',
                        'unit_cr_number' => $unit->cr_number ?? 'N/A',
                        'unit_cr_date_validity' => $unit->cr_validity ? $unit->cr_validity->format('F d, Y') : 'N/A',
                        'lto_cr_number' => $unit->lto_cr_number ?? 'N/A',
                        'lto_cr_date_issued' => $unit->lto_cr_date_issued ? $unit->lto_cr_date_issued->format('F d, Y') : 'N/A',
                        'lto_or_number' => $unit->lto_or_number ?? 'N/A',
                        'lto_or_date_issued' => $unit->lto_or_date_issued ? $unit->lto_or_date_issued->format('F d, Y') : 'N/A',
                        'franchise_case' => $unit->franchise_case ?? 'N/A',
                        'mv_file' => $unit->mv_file ?? 'N/A',
                        'cr_receipt_photo_url' => $unit->cr_receipt_photo_url,
                        'unit_photo_url' => $unit->unit_photo_url,
                        'business_permit_number' => $unit->business_permit_no ?? 'N/A',
                        'business_permit_validity' => $unit->business_permit_validity ? $unit->business_permit_validity->format('F d, Y') : 'N/A',
                        'business_permit_photo_url' => $unit->business_permit_photo_url,
                        'or_photo_url' => $unit->or_photo_url,
                        'cr_photo_url' => $unit->cr_photo_url,
                        'status' => $unit->status ?? 'active',
                    ];
                })->values(),
            ]
        ];

        return response()->json($data);
    }

    /**
     * Save driver-unit assignments
     * Allows operators to assign their drivers to their units
     */
    public function saveDriverAssignments(Request $request)
    {
        try {
            $user = auth()->user();
            $operator = $user->operator;

            if (!$operator) {
                return response()->json([
                    'success' => false,
                    'message' => 'Operator not found'
                ], 403);
            }

            $assignments = $request->input('assignments', []);

            // Validate that all drivers and units belong to this operator
            foreach ($assignments as $assignment) {
                $driverId = $assignment['driver_id'] ?? null;
                $unitId = $assignment['unit_id'] ?? null;

                if (!$driverId) {
                    continue;
                }

                // Verify driver belongs to this operator
                $driver = Driver::where('id', $driverId)
                    ->where('operator_id', $operator->id)
                    ->first();

                if (!$driver) {
                    return response()->json([
                        'success' => false,
                        'message' => "Driver ID {$driverId} does not belong to your account"
                    ], 403);
                }

                // If unit is specified, verify it belongs to this operator
                if ($unitId) {
                    $unit = Unit::where('id', $unitId)
                        ->where('operator_id', $operator->id)
                        ->first();

                    if (!$unit) {
                        return response()->json([
                            'success' => false,
                            'message' => "Unit ID {$unitId} does not belong to your account"
                        ], 403);
                    }
                }
            }

            Unit::where('operator_id', $operator->id)->update(['driver_id' => null]);

            // Now assign units based on the assignments array
            foreach ($assignments as $assignment) {
                $driverId = $assignment['driver_id'] ?? null;
                $unitId = $assignment['unit_id'] ?? null;

                if ($driverId && $unitId) {
                    // Assign the unit to the driver
                    Unit::where('id', $unitId)
                        ->where('operator_id', $operator->id)
                        ->update(['driver_id' => $driverId]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Driver assignments saved successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving driver assignments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving assignments: ' . $e->getMessage()
            ], 500);
        }
    }
}