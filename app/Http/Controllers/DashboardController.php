<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\User;
use App\Models\Document;
use App\Models\Activity;
use App\Models\Meeting;
use App\Models\Transaction;
use App\Models\AuditTrail;
use App\Models\DocumentRenewal;
use App\Models\Requirement;

use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isPresident()) {
            return $this->presidentDashboard();
        } elseif ($user->isTreasurer()) {
            return $this->treasurerDashboard();
        } else {
            return $this->operatorDashboard($user);
        }
    }

    protected function adminDashboard()
    {
        // Main statistics cards - only count approved operators, drivers, and units
        $totalOperators = Operator::where('approval_status', 'approved')->count();
        $totalDrivers = Driver::where(function($query) {
            $query->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
        })->count();
        $totalUnits = Unit::where(function($query) {
            $query->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
        })->count();

        $renewedDocuments = $this->getRenewedDocumentsCount();

        $expiringSoon = $this->getExpiringSoonCount();

        // NEW: Get detailed expiring documents for the section
        $expiringDocuments = $this->getExpiringDocuments();

        // NEW: Get detailed renewed documents for the section
        $renewedDocumentsList = $this->getRenewedDocumentsList();

        // Monthly attendance data for chart
        $attendanceData = $this->getMonthlyAttendanceData();

        // Annual collections data for chart
        $collectionsData = $this->getAnnualCollectionsData();

        // Calculate cash on hand and cash in bank from annual collection totals (same as treasurer dashboard)
        // Cash on Hand = Total Receipts (from annual collection)
        // Cash in Bank = Total Disbursements (from annual collection)
        $cashOnHand = array_sum($collectionsData['receipts'] ?? []);
        // $cashInBank = array_sum($collectionsData['disbursements'] ?? []);
        // Calculate running balance (total receipts - total disbursements)
        $totalReceipts = Transaction::where('type', 'receipt')->sum('amount');
        $totalDisbursements = Transaction::where('type', 'disbursement')->sum('amount');
        $cashInBank = abs($totalReceipts - $totalDisbursements);

        // NEW: Gender count data for pie chart
        $genderData = $this->getGenderCountData();

        // NEW: Age bracket data for bar chart
        $ageBracketData = $this->getAgeBracketData();

        // Recent activities - unified activity feed
        $recentActivities = Activity::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalOperators',
            'totalDrivers',
            'totalUnits',
            'cashOnHand',
            'cashInBank',
            'renewedDocuments',        // NEW
            'expiringSoon',            // NEW
            'expiringDocuments',       // NEW
            'renewedDocumentsList',    // NEW
            'attendanceData',
            'collectionsData',
            'genderData',              // NEW
            'ageBracketData',          // NEW
            'recentActivities'         // NEW - unified activity feed
        ));
    }

    protected function operatorDashboard($user)
    {
        $operator = $user->operator;

        if (!$operator) {
            return view('operator.dashboard', [
                'totalDrivers' => 0,
                'totalUnits' => 0,
                'subsidiaryJournalTotal' => 0,
                'balance' => 0,
                'absents' => 0,
                'notifications' => collect([]),
                'monthlySpending' => ['labels' => [], 'data' => []],
                'recentDrivers' => collect([]),
                'recentUnits' => collect([]),
                'totalUnpaidPenalties' => 0,
                'totalPaidPenalties' => 0,
                'penalties' => collect([]),
                'totalSubscriptionCapital' => 0 // Add this
            ]);
        }

        $totalDrivers = $operator->drivers()->where(function($query) {
            $query->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
        })->count();
        $totalUnits = $operator->units()->where(function($query) {
            $query->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
        })->count();

        // Financial data - Calculate from actual transactions
        $subsidiaryJournalTotal = \App\Models\Transaction::where('operator_id', $operator->id)->sum('amount');
        $balance = $this->calculateOperatorBalance($operator->Id);
        //$balance = $this->calculateOperatorBalance($operator->id);

        // ADD THIS: Calculate total subscription capital for this operator
        $totalSubscriptionCapital = \App\Models\Transaction::where('operator_id', $operator->id)
        ->where('particular', 'subscription_capital')
        ->sum('amount');

        // Meeting Attendance data - Count of meetings the operator was absent from
        $absents = $operator->totalAbsences();

        // Penalty data
        $totalUnpaidPenalties = $operator->total_unpaid_penalties;
        $totalPaidPenalties = $operator->total_paid_penalties;
        $penalties = $operator->penalties()
            ->with(['meeting', 'payments'])
            ->orderBy('due_date', 'asc')
            ->get();

        $currentYear = now()->year;
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $total = \App\Models\Transaction::where('operator_id', $operator->id)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->sum('amount');
            $monthlyData[] = (float) $total;
        }

        $monthlySpending = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $monthlyData
        ];

        // Notifications for expiring permits and alerts
        $notifications = collect([]);

        $today = now();
        $thirtyDaysFromNow = now()->addDays(30);

        $expiringLicenses = $operator->drivers()
            ->whereNotNull('license_expiry')
            ->where('license_expiry', '>=', $today->toDateString())
            ->where('license_expiry', '<=', $thirtyDaysFromNow->toDateString())
            ->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })
            ->get();

        foreach ($expiringLicenses as $driver) {

            // Calculate total seconds left
            $secondsLeft = $driver->license_expiry->timestamp - now()->timestamp;

            // Convert to full days and remaining hours
            $days = floor($secondsLeft / 86400); // seconds in a day
            $hours = round(($secondsLeft % 86400) / 3600);

            $timeLeft = $hours > 0
                ? "{$days} days, {$hours} hours"
                : "{$days} days";

            $notifications->push([
                'type'  => 'warning',
                'icon'  => 'fa-id-card',
                'title' => 'License Expiring Soon',
                'message' => "{$driver->first_name} {$driver->last_name}'s license expires in {$timeLeft}",
                'time'  => $driver->license_expiry->format('M d, Y'),
                'color' => 'orange',
            ]);
        }


        // Reset dates for units check
        $today = now();
        $thirtyDaysFromNow = now()->addDays(30);

        $expiringRegistrations = $operator->units()
            ->whereNotNull('registration_expiry')
            ->where('registration_expiry', '>=', $today->toDateString())
            ->where('registration_expiry', '<=', $thirtyDaysFromNow->toDateString())
            ->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })
            ->get();

        foreach ($expiringRegistrations as $unit) {
            // Calculate total seconds left
            $secondsLeft = $unit->registration_expiry->timestamp - now()->timestamp;

            // Convert to full days and remaining hours
            $days = floor($secondsLeft / 86400); // 86400 seconds in a day
            $hours = round(($secondsLeft % 86400) / 3600); // remainder hours

            $timeLeft = $hours > 0 ? "{$days} days, {$hours} hours" : "{$days} days";

            $notifications->push([
                'type' => 'warning',
                'icon' => 'fa-bus',
                'title' => 'Registration Expiring',
                'message' => "Unit {$unit->plate_number} registration expires in {$timeLeft}",
                'time' => $unit->registration_expiry->format('M d, Y'),
                'color' => 'red'
            ]);
        }

        // Add meeting attendance notification if operator has absences
        if ($absents > 0) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'fa-calendar-times',
                'title' => 'Meeting Attendance',
                'message' => "You have {$absents} recorded absence(s) from cooperative meetings",
                'time' => now()->format('M d, Y'),
                'color' => 'blue'
            ]);
        }

        $recentDrivers = $operator->drivers()
            ->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })
            ->latest()->take(5)->get();
        $recentUnits = $operator->units()
            ->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })
            ->latest()->take(5)->get();

        return view('operator.dashboard', compact(
            'totalDrivers',
            'totalUnits',
            'subsidiaryJournalTotal',
            'balance',
            'totalSubscriptionCapital', // Add this
            'absents',
            'notifications',
            'monthlySpending',
            'recentDrivers',
            'recentUnits',
            'totalUnpaidPenalties',
            'totalPaidPenalties',
            'penalties'
        ));
    }

    /**
     * Get count of renewed documents pending approval
     */
    private function getRenewedDocumentsCount()
    {
        try {
            // Use the same filtering logic as getRenewedDocumentsList to ensure count matches
            return DocumentRenewal::with(['operator', 'documentable'])
                ->where('status', 'pending')
                ->get()
                ->filter(function($renewal) {
                    // Filter out renewals where documentable doesn't exist
                    if (!$renewal->documentable) {
                        return false;
                    }

                    // Filter out renewals where documentable doesn't belong to the operator anymore
                    if (!isset($renewal->documentable->operator_id) || $renewal->documentable->operator_id != $renewal->operator_id) {
                        return false;
                    }

                    return true;
                })
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get count of documents expiring within 30 days
     * Includes driver licenses, unit registrations and cooperative requirements
     */
    private function getExpiringSoonCount()
    {
        $count = 0;
        $today = now()->startOfDay();
        $thirtyDaysFromNow = now()->addDays(30)->endOfDay();

        try {
            $count += Driver::whereNotNull('license_expiry')
                ->where('license_expiry', '>=', $today)
                ->where('license_expiry', '<=', $thirtyDaysFromNow)
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })
                ->count();

            // Get all approved units to check various expiring documents
            $units = Unit::where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })->get();

            foreach ($units as $unit) {
                // Count Business Permit Validity
                if ($unit->business_permit_validity) {
                    $expiryDate = $unit->business_permit_validity;
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $count++;
                    }
                }

                if ($unit->or_date_issued) {
                    $expiryDate = $unit->or_date_issued->copy();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $count++;
                    }
                }

                // Count CR Validity
                if ($unit->cr_validity) {
                    $expiryDate = $unit->cr_validity;
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $count++;
                    }
                }

                if ($unit->lto_cr_date_issued) {
                    $expiryDate = $unit->lto_cr_date_issued->copy()->addYear();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $count++;
                    }
                }

                if ($unit->lto_or_date_issued) {
                    $expiryDate = $unit->lto_or_date_issued->copy()->addYear();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $count++;
                    }
                }

                // ============================================
                // COUNT REQUIREMENTS (CDA, BIR, TAX, BUSINESS PERMIT)
                // ============================================

                $requirementTypes = [
                    'cda_compliance',
                    'tax_exemption',
                    'bir_registration',
                    'business_permit',
                ];

                foreach ($requirementTypes as $type) {
                    $requirement = Requirement::where('type', $type)
                        ->latest()
                        ->first();

                    if (!$requirement || !$requirement->expiry_date) {
                        continue;
                    }

                    if (
                        $requirement->expiry_date >= $today &&
                        $requirement->expiry_date <= $thirtyDaysFromNow
                    ) {
                        $count++;
                    }
                }

            }

            // Also count Document model documents if it exists
            if (class_exists('App\Models\Document')) {
                $count += Document::whereNotNull('expiry_date')
                    ->where('expiry_date', '>=', $today)
                    ->where('expiry_date', '<=', $thirtyDaysFromNow)
                    ->whereNotIn('status', ['expired', 'renewed'])
                    ->count();
            }

            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get expiring documents with owner information
     * Includes driver licenses and unit documents from actual database
     */
    private function getExpiringDocuments()
    {
        $expiringDocuments = collect([]);
        $today = now()->startOfDay();
        $thirtyDaysFromNow = now()->addDays(30)->endOfDay();

        try {
            // ============================================
            // DRIVER DOCUMENTS
            // ============================================

            $drivers = Driver::with('operator.user')
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
                $expiringDocuments->push((object)[
                    'owner_name' => $driver->full_name,
                    'document_type' => "Driver's License",
                    'days_remaining' => $daysRemaining,
                    'expiry_date' => $driver->license_expiry
                ]);
            }

            // ============================================
            // UNIT DOCUMENTS
            // ============================================

            // Get all approved units with any validity/expiry dates
            $units = Unit::with('operator.user')
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })
                ->get();

            foreach ($units as $unit) {
                $ownerName = 'Unit: ' . ($unit->plate_number ?? 'Unknown');

                // 1. Business Permit Validity
                if ($unit->business_permit_validity) {
                    $expiryDate = $unit->business_permit_validity;
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push((object)[
                            'owner_name' => $ownerName,
                            'document_type' => 'Business Permit',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate
                        ]);
                    }
                }

                // 2. OR Validity (or_date_issued + 1 year)
                if ($unit->or_date_issued) {
                    $expiryDate = $unit->or_date_issued->copy();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push((object)[
                            'owner_name' => $ownerName,
                            'document_type' => 'OR (Official Receipt)',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate
                        ]);
                    }
                }

                // 3. CR Validity
                if ($unit->cr_validity) {
                    $expiryDate = $unit->cr_validity;
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push((object)[
                            'owner_name' => $ownerName,
                            'document_type' => 'CR (Certificate of Registration)',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate
                        ]);
                    }
                }

                // 4. LTO CR (lto_cr_date_issued + 1 year)
                if ($unit->lto_cr_date_issued) {
                    $expiryDate = $unit->lto_cr_date_issued->copy()->addYear();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push((object)[
                            'owner_name' => $ownerName,
                            'document_type' => 'LTO CR',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate
                        ]);
                    }
                }

                // 5. LTO OR (lto_or_date_issued + 1 year)
                if ($unit->lto_or_date_issued) {
                    $expiryDate = $unit->lto_or_date_issued->copy()->addYear();
                    if ($expiryDate >= $today && $expiryDate <= $thirtyDaysFromNow) {
                        $daysRemaining = now()->startOfDay()->diffInDays($expiryDate, false);
                        $expiringDocuments->push((object)[
                            'owner_name' => $ownerName,
                            'document_type' => 'LTO OR',
                            'days_remaining' => $daysRemaining,
                            'expiry_date' => $expiryDate
                        ]);
                    }
                }
            
                // ============================================
                // COOPERATIVE REQUIREMENTS
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
                    $requirement = $items->first(); // earliest expiring per type

                    $daysRemaining = now()->startOfDay()
                        ->diffInDays($requirement->expiry_date, false);

                    $expiringDocuments->push((object)[
                        'owner_name'      => 'Cooperative Requirement',
                        'document_type'   => $requirementLabels[$type] ?? $type,
                        'days_remaining' => $daysRemaining,
                        'expiry_date'    => $requirement->expiry_date,
                    ]);
                }
            
            }

            // ============================================
            // ============================================

            // Also include Document model documents if it exists
            if (class_exists('App\Models\Document')) {
                $modelDocuments = Document::with(['documentable'])
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '>=', $today)
                    ->where('expiry_date', '<=', $thirtyDaysFromNow)
                    ->whereNotIn('status', ['expired', 'renewed'])
                    ->get();

                foreach ($modelDocuments as $doc) {
                    $daysRemaining = now()->startOfDay()->diffInDays($doc->expiry_date, false);
                    $expiringDocuments->push((object)[
                        'owner_name' => $doc->owner_name ?? ($doc->documentable ? $this->getDocumentOwnerName($doc) : 'Unknown'),
                        'document_type' => $doc->formatted_type ?? ($doc->type ?? 'Unknown Document'),
                        'days_remaining' => $daysRemaining,
                        'expiry_date' => $doc->expiry_date
                    ]);
                }
            }

            return $expiringDocuments->sortBy('days_remaining')->values();

        } catch (\Exception $e) {
            \Log::error('Error fetching expiring documents: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    /**
     * Get document owner name based on documentable type
     */
    private function getDocumentOwnerName($document)
    {
        if (!$document->documentable) {
            return 'Unknown';
        }
        
        // Handle different document owner types
        if ($document->documentable_type === 'App\Models\Driver') {
            return ($document->documentable->first_name ?? '') . ' ' . ($document->documentable->last_name ?? '');
        }

        if ($document->documentable_type === 'App\Models\Operator') {
            return $document->documentable->business_name ??
                   ($document->documentable->user ?
                       ($document->documentable->user->first_name ?? '') . ' ' . ($document->documentable->user->last_name ?? '')
                       : 'Unknown Operator');
        }

        if ($document->documentable_type === 'App\Models\Unit') {
            return 'Unit: ' . ($document->documentable->plate_number ?? 'Unknown');
        }
        
        return 'Unknown';
    }
    
    /**
     * Format document type for display
     */
    private function formatDocumentType($type)
    {
        $types = [
            'drivers_license' => "Driver's License",
            'license' => "Driver's License",
            'vehicle_registration' => 'Vehicle Registration',
            'registration' => 'Vehicle Registration',
            'or_cr' => 'OR/CR',
            'franchise' => 'Franchise',
            'insurance' => 'Insurance',
            'ltms_portal' => 'LTMS Portal',
            'smoke_test' => 'Smoke Emission Test',
            'emission_test' => 'Emission Test',
        ];

        return $types[$type] ?? ucwords(str_replace('_', ' ', $type));
    }

    /**
     * Get renewed documents pending approval with details
     */
    private function getRenewedDocumentsList()
    {
        try {
            $renewals = DocumentRenewal::with(['operator', 'documentable'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get()
                ->filter(function($renewal) {
                    // Filter out renewals where documentable doesn't exist
                    if (!$renewal->documentable) {
                        return false;
                    }

                    // Filter out renewals where documentable doesn't belong to the operator anymore
                    if (!isset($renewal->documentable->operator_id) || $renewal->documentable->operator_id != $renewal->operator_id) {
                        return false;
                    }

                    return true;
                })
                ->map(function($renewal) {
                    return (object)[
                        'id' => $renewal->id ?? null,
                        'operator_name' => $renewal->operator ? ($renewal->operator->full_name ?? 'Unknown') : 'Unknown',
                        'entity_identifier' => $renewal->entity_identifier ?? 'N/A',
                        'document_type' => $renewal->formatted_type ?? 'Unknown',
                        'original_expiry' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('M d, Y') : 'N/A',
                        'new_expiry' => $renewal->new_expiry_date ? $renewal->new_expiry_date->format('M d, Y') : 'N/A',
                        'submitted_at' => $renewal->created_at ? $renewal->created_at->format('M d, Y') : 'N/A',
                        'days_ago' => $renewal->created_at ? TimeHelper::timeAgo($renewal->created_at) : 'N/A'
                    ];
                });

            return $renewals;
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * President Dashboard - Meeting attendance management
     */
    protected function presidentDashboard()
    {
        // Get all past meetings with attendance records
        $pastMeetings = Meeting::with(['attendances.operator'])
            ->where('meeting_date', '<=', now())
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->take(20)
            ->get();

        // Get all operators with their details
        $operators = Operator::with(['user', 'drivers', 'units'])
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalOperators = $operators->count();
        $totalDrivers = $operators->sum(function($operator) {
            return $operator->drivers->where(function($driver) {
                return $driver->approval_status === 'approved' || $driver->approval_status === null;
            })->count();
        });
        $totalUnits = $operators->sum(function($operator) {
            return $operator->units->where(function($unit) {
                return $unit->approval_status === 'approved' || $unit->approval_status === null;
            })->count();
        });

        // Get total meetings count
        $totalMeetings = Meeting::count();

        $renewedDocuments = $this->getRenewedDocumentsCount();

        $expiringSoon = $this->getExpiringSoonCount();

        // NEW: Get detailed expiring documents for the section
        $expiringDocuments = $this->getExpiringDocuments();

        // Monthly attendance data for chart
        $attendanceData = $this->getMonthlyAttendanceData();

        // Annual collections data for chart
        $collectionsData = $this->getAnnualCollectionsData();

        return view('president.dashboard', compact(
            'pastMeetings',
            'operators',
            'totalOperators',
            'totalDrivers',
            'totalUnits',
            'totalMeetings',
            'renewedDocuments',
            'expiringSoon',
            'expiringDocuments',
            'attendanceData',
            'collectionsData'
        ));
    }

    /**
     * Treasurer Dashboard - Financial overview
     */
    protected function treasurerDashboard()
    {
        // Total operators - only count approved operators
        $totalOperators = Operator::where('approval_status', 'approved')->count();

        // Get monthly receipts and disbursements for the current year
        $currentYear = date('Y');
        $monthlyReceipts = [];
        $monthlyDisbursements = [];

        for ($month = 1; $month <= 12; $month++) {
            $receipts = Transaction::where('type', 'receipt')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->sum('amount');

            $disbursements = Transaction::where('type', 'disbursement')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->sum('amount');

            $monthlyReceipts[] = (float) $receipts;
            $monthlyDisbursements[] = (float) $disbursements;
        }

        $collectionsData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'receipts' => $monthlyReceipts,
            'disbursements' => $monthlyDisbursements
        ];

        // Calculate cash on hand and cash in bank from annual collection totals
        // Cash on Hand = Total Receipts (from annual collection)
        // Cash in Bank = Total Disbursements (from annual collection)
        $cashOnHand = array_sum($monthlyReceipts);
        //$cashInBank = array_sum($monthlyDisbursements);
        // Calculate running balance (total receipts - total disbursements)
        $totalReceipts = Transaction::where('type', 'receipt')->sum('amount');
        $totalDisbursements = Transaction::where('type', 'disbursement')->sum('amount');
        $cashInBank = abs($totalReceipts - $totalDisbursements);

        return view('treasurer.dashboard', compact(
            'totalOperators',
            'cashOnHand',
            'cashInBank',
            'collectionsData'
        ));
    }

    /**
     * President - Operators Directory Page
     */
    public function operatorsDirectory()
    {
        $operators = Operator::with([
                'user',
                'drivers' => function($query) {
                    $query->where(function($q) {
                        $q->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                    });
                },
                'units' => function($query) {
                    $query->where(function($q) {
                        $q->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                    });
                }
            ])
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 10 operators per page

        $totalOperators = Operator::where('approval_status', 'approved')->count();
        $totalDrivers = Driver::where(function($query) {
            $query->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
        })->count();
        $totalUnits = Unit::where(function($query) {
            $query->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
        })->count();

        return view('president.operators', compact('operators', 'totalOperators', 'totalDrivers', 'totalUnits'));
    }

    /**
     * President - Meeting Attendance Page
     */
    public function meetingAttendance()
    {
        // Get all past meetings with attendance records
        $pastMeetings = Meeting::with(['attendances.operator'])
            ->where('meeting_date', '<=', now())
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->get();

        // Get all operators for statistics
        $operators = Operator::with(['user'])
            ->where('approval_status', 'approved')
            ->get();

        $totalOperators = $operators->count();

        return view('president.attendance', compact('pastMeetings', 'operators', 'totalOperators'));
    }

    /**
     * Admin - Report Page
     */
    public function adminReport()
    {
        return view('admin.report');
    }

    /**
     * Admin - Annual Report Page
     */
    public function adminAnnualReport(\Illuminate\Http\Request $request)
    {
        $generalInfo = \App\Models\GeneralInfo::first();

        // Get selected year from request or default to current year
        $selectedYear = $request->get('year', date('Y'));
        $currentYear = date('Y');

        // Get all available years from database
        $availableYears = \App\Models\AnnualReport::orderBy('report_year', 'desc')
            ->pluck('report_year')
            ->toArray();

        // Ensure current year is in the list
        if (!in_array($currentYear, $availableYears)) {
            array_unshift($availableYears, $currentYear);
        }

        $today = \Carbon\Carbon::today();
        $officers = \App\Models\Officer::with('operator.user')
            ->where('is_active', true)
            ->where('effective_to', '>=', $today)
            ->get();

        // Organize officers by position for easy access
        $executiveOfficers = [
            'chairperson' => $officers->firstWhere('position', 'chairperson'),
            'vice_chairperson' => $officers->firstWhere('position', 'vice_chairperson'),
            'secretary' => $officers->firstWhere('position', 'secretary'),
            'treasurer' => $officers->firstWhere('position', 'treasurer'),
            'general_manager' => $officers->firstWhere('position', 'general_manager'),
            'bookkeeper' => $officers->firstWhere('position', 'bookkeeper'),
        ];

        // Organize committees by their actual committee names
        $committees = [
            'board' => $officers->where('committee', 'Board of Directors'),
            'audit' => $officers->where('committee', 'Audit Committee'),
            'election' => $officers->where('committee', 'Election Committee'),
            'mediation' => $officers->where('committee', 'Mediation and Conciliation Committee'),
            'ethics' => $officers->where('committee', 'Ethics Committee'),
            'gender' => $officers->where('committee', 'Gender and Development Committee'),
            'education' => $officers->where('committee', 'Education Committee'),
        ];

        $boardMembers = $committees['board'];

        // Load saved report data for selected year
        $savedReport = \App\Models\AnnualReport::where('report_year', $selectedYear)->first();
        $savedData = $savedReport ? $savedReport->report_data : [];

        // Auto-fill Cluster 2: Membership data from database
        $membershipData = $this->getCluster2MembershipData($currentYear);

        // Merge auto-filled data with saved data (saved data takes priority)
        $savedData = array_merge($membershipData, $savedData);

        return view('admin.annual-report', compact('generalInfo', 'executiveOfficers', 'boardMembers', 'officers', 'committees', 'savedData', 'selectedYear', 'availableYears', 'currentYear'));
    }

    /**
     * Get Cluster 2: Membership data from database
     */
    private function getCluster2MembershipData($currentYear)
    {
        $data = [];

        // Calculate data for current year and previous years (up to 5 years back)
        $startYear = $currentYear - 5;

        for ($year = $startYear; $year <= $currentYear; $year++) {
            // Count approved drivers by gender for the year
            // Use sex field primarily (Male/Female), fallback to gender if available
            $driversQuery = \App\Models\Driver::where('approval_status', 'approved')
                ->whereYear('created_at', '<=', $year);

            $driversMale = (clone $driversQuery)->where(function($q) {
                $q->where('sex', 'Male')
                  ->orWhere('sex', 'male');
            })->count();

            $driversFemale = (clone $driversQuery)->where(function($q) {
                $q->where('sex', 'Female')
                  ->orWhere('sex', 'female');
            })->count();

            // Count approved operators by gender for the year
            // Operators use gender field (male/female)
            $operatorsQuery = \App\Models\Operator::where('approval_status', 'approved')
                ->whereYear('created_at', '<=', $year);

            $operatorsMale = (clone $operatorsQuery)->where(function($q) {
                $q->where('gender', 'male')
                  ->orWhere('gender', 'Male');
            })->count();

            $operatorsFemale = (clone $operatorsQuery)->where(function($q) {
                $q->where('gender', 'female')
                  ->orWhere('gender', 'Female');
            })->count();

            // Store the data
            $data["drivers_{$year}_male"] = $driversMale;
            $data["drivers_{$year}_female"] = $driversFemale;
            $data["operator_{$year}_male"] = $operatorsMale;
            $data["operator_{$year}_female"] = $operatorsFemale;

            // Calculate totals
            $data["total_{$year}_male"] = $driversMale + $operatorsMale;
            $data["total_{$year}_female"] = $driversFemale + $operatorsFemale;

            // Count special status categories
            $operatorDetailsQuery = \App\Models\OperatorDetail::whereHas('operator', function($q) use ($year) {
                $q->where('approval_status', 'approved')
                  ->whereYear('created_at', '<=', $year);
            });

            $pwdCount = (clone $operatorDetailsQuery)->where('pwd', 'yes')->count();
            $seniorCount = (clone $operatorDetailsQuery)->where('senior_citizen', 'yes')->count();

            $data["pwd_{$year}"] = $pwdCount;
            $data["senior_{$year}"] = $seniorCount;
            $data["special_total_{$year}"] = $pwdCount + $seniorCount;
        }

        return $data;
    }

    /**
     * Save Annual Report (Admin, President, Treasurer)
     */
    public function saveAnnualReport(Request $request)
    {
        $currentYear = date('Y');
        $user = auth()->user();

        // Get all form data except CSRF token and files
        $reportData = $request->except(['_token', '_method']);

        // Update or create annual report for current year
        $annualReport = \App\Models\AnnualReport::updateOrCreate(
            ['report_year' => $currentYear],
            [
                'report_data' => $reportData,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ]
        );

        // Determine redirect route based on user role
        $redirectRoute = 'admin.annual-report';
        if ($user->isPresident()) {
            $redirectRoute = 'president.annual-report';
        } elseif ($user->isTreasurer()) {
            $redirectRoute = 'treasurer.annual-report';
        }

        return redirect()->route($redirectRoute)->with('success', 'Annual Report saved successfully!');
    }

    /**
     * President - Annual Report Page
     */
    public function presidentAnnualReport(\Illuminate\Http\Request $request)
    {
        return $this->adminAnnualReport($request);
    }

    /**
     * Treasurer - Annual Report Page
     */
    public function treasurerAnnualReport(\Illuminate\Http\Request $request)
    {
        return $this->adminAnnualReport($request);
    }

    /**
     * Generate Annual Report PDF (Admin, President, Treasurer)
     */
    public function generateAnnualReportPDF(Request $request)
    {
        $data = $request->all();

        $today = \Carbon\Carbon::today();
        $activeOfficers = \App\Models\Officer::with('operator.user')
            ->where('is_active', true)
            ->where('effective_to', '>=', $today)
            ->get();

        // Organize officers by position and committee
        $singleOfficers = [
            'chairperson' => $activeOfficers->where('position', 'chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'secretary')->first(),
            'treasurer' => $activeOfficers->where('position', 'treasurer')->first(),
            'general_manager' => $activeOfficers->where('position', 'general_manager')->first(),
            'bookkeeper' => $activeOfficers->where('position', 'bookkeeper')->first(),
        ];

        // Board of Directors
        $boardOfDirectors = $activeOfficers->where('committee', 'Board of Directors');

        // Committee structures
        $auditCommittee = [
            'chairperson' => $activeOfficers->where('position', 'audit_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'audit_vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'audit_secretary')->first(),
            'member' => $activeOfficers->where('position', 'audit_member')->first()
        ];

        $electionCommittee = [
            'chairperson' => $activeOfficers->where('position', 'election_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'election_vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'election_secretary')->first(),
            'member' => $activeOfficers->where('position', 'election_member')->first()
        ];

        $mediationCommittee = [
            'chairperson' => $activeOfficers->where('position', 'mediation_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'mediation_vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'mediation_secretary')->first(),
            'member' => $activeOfficers->where('position', 'mediation_member')->first()
        ];

        $ethicsCommittee = [
            'chairperson' => $activeOfficers->where('position', 'ethics_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'ethics_vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'ethics_secretary')->first(),
            'member' => $activeOfficers->where('position', 'ethics_member')->first()
        ];

        $genderCommittee = [
            'chairperson' => $activeOfficers->where('position', 'gad_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'gad_vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'gad_secretary')->first(),
            'member' => $activeOfficers->where('position', 'gad_member')->first()
        ];

        $educationCommittee = [
            'chairperson' => $activeOfficers->where('position', 'education_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'education_vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'education_secretary')->first(),
            'member' => $activeOfficers->where('position', 'education_member')->first()
        ];

        // Add officers data to the data array
        $data['officers'] = [
            'singleOfficers' => $singleOfficers,
            'boardOfDirectors' => $boardOfDirectors,
            'auditCommittee' => $auditCommittee,
            'electionCommittee' => $electionCommittee,
            'mediationCommittee' => $mediationCommittee,
            'ethicsCommittee' => $ethicsCommittee,
            'genderCommittee' => $genderCommittee,
            'educationCommittee' => $educationCommittee,
        ];

        $pdf = \PDF::loadView('admin.annual-report-pdf', compact('data'))
            ->setPaper('a4', 'landscape');

        // Log PDF download
        AuditTrail::log(
            'download',
            'Downloaded Annual Report PDF for year ' . date('Y')
        );

        return $pdf->download('Annual_Report_' . date('Y') . '.pdf');
    }

    /**
     * Get gender count data for pie chart
     * Counts ALL members (both operators AND drivers) in the cooperative
     */
    private function getGenderCountData()
    {
        try {
            $driverMaleCount = Driver::where('sex', 'Male')
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })
                ->where(function($query) {
                    $query->where('status', 'active')
                          ->orWhereNull('status');
                })
                ->count();

            $driverFemaleCount = Driver::where('sex', 'Female')
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })
                ->where(function($query) {
                    $query->where('status', 'active')
                          ->orWhereNull('status');
                })
                ->count();

            // The gender field is stored in operator_details.sex, not operators.gender
            $operatorMaleCount = Operator::where('approval_status', 'approved')
                ->where(function($query) {
                    $query->where('status', 'active')
                          ->orWhereNull('status');
                })
                ->whereHas('operatorDetail', function($query) {
                    $query->where('sex', 'male');
                })
                ->count();

            $operatorFemaleCount = Operator::where('approval_status', 'approved')
                ->where(function($query) {
                    $query->where('status', 'active')
                          ->orWhereNull('status');
                })
                ->whereHas('operatorDetail', function($query) {
                    $query->where('sex', 'female');
                })
                ->count();

            $maleCount = $driverMaleCount + $operatorMaleCount;
            $femaleCount = $driverFemaleCount + $operatorFemaleCount;
            $totalCount = $maleCount + $femaleCount;

            // Calculate percentages
            $malePercentage = $totalCount > 0 ? round(($maleCount / $totalCount) * 100, 1) : 0;
            $femalePercentage = $totalCount > 0 ? round(($femaleCount / $totalCount) * 100, 1) : 0;

            return [
                'labels' => ['Male', 'Female'],
                'counts' => [$maleCount, $femaleCount],
                'percentages' => [$malePercentage, $femalePercentage],
                'colors' => ['#4e73df', '#e83e8c']
            ];
        } catch (\Exception $e) {
            \Log::error('Error calculating gender data: ' . $e->getMessage());
            return [
                'labels' => ['Male', 'Female'],
                'counts' => [0, 0],
                'percentages' => [0, 0],
                'colors' => ['#4e73df', '#e83e8c']
            ];
        }
    }

    /**
     * Get age bracket data for bar chart
     * Calculate age brackets from date of birth for ALL members (operators AND drivers)
     */
    private function getAgeBracketData()
    {
        try {
            // Initialize brackets
            $brackets = [
                '18-25' => 0,
                '26-35' => 0,
                '36-45' => 0,
                '46-55' => 0,
                '56-65' => 0,
                '66+' => 0
            ];

            $drivers = Driver::whereNotNull('birthdate')
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })
                ->where(function($query) {
                    $query->where('status', 'active')
                          ->orWhereNull('status');
                })
                ->get();

            // Count drivers by age bracket
            foreach ($drivers as $driver) {
                $age = Carbon::parse($driver->birthdate)->age;

                if ($age >= 18 && $age <= 25) {
                    $brackets['18-25']++;
                } elseif ($age >= 26 && $age <= 35) {
                    $brackets['26-35']++;
                } elseif ($age >= 36 && $age <= 45) {
                    $brackets['36-45']++;
                } elseif ($age >= 46 && $age <= 55) {
                    $brackets['46-55']++;
                } elseif ($age >= 56 && $age <= 65) {
                    $brackets['56-65']++;
                } elseif ($age >= 66) {
                    $brackets['66+']++;
                }
            }

            $operators = Operator::with('operatorDetail')
                ->where('approval_status', 'approved')
                ->where(function($query) {
                    $query->where('status', 'active')
                          ->orWhereNull('status');
                })
                ->whereHas('operatorDetail', function($query) {
                    $query->whereNotNull('birthdate');
                })
                ->get();

            // Count operators by age bracket
            foreach ($operators as $operator) {
                if (isset($operator->operatorDetail) && isset($operator->operatorDetail->birthdate) && $operator->operatorDetail->birthdate) {
                    $age = Carbon::parse($operator->operatorDetail->birthdate)->age;

                    if ($age >= 18 && $age <= 25) {
                        $brackets['18-25']++;
                    } elseif ($age >= 26 && $age <= 35) {
                        $brackets['26-35']++;
                    } elseif ($age >= 36 && $age <= 45) {
                        $brackets['36-45']++;
                    } elseif ($age >= 46 && $age <= 55) {
                        $brackets['46-55']++;
                    } elseif ($age >= 56 && $age <= 65) {
                        $brackets['56-65']++;
                    } elseif ($age >= 66) {
                        $brackets['66+']++;
                    }
                }
            }

            return [
                'labels' => array_keys($brackets),
                'data' => array_values($brackets),
                'colors' => [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(133, 135, 150, 0.8)'
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Error calculating age bracket data: ' . $e->getMessage());
            return [
                'labels' => ['18-25', '26-35', '36-45', '46-55', '56-65', '66+'],
                'data' => [0, 0, 0, 0, 0, 0],
                'colors' => [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(133, 135, 150, 0.8)'
                ]
            ];
        }
    }

    /**
     * Display audit trail / recent activities
     */
    public function auditTrail(Request $request)
    {
        // Use AuditTrail table to show all system activities
        $query = AuditTrail::query()->with('user');

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model')) {
            // Filter by model
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        // Order by most recent first
        $auditTrails = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique action types for filter dropdown
        $actionTypes = AuditTrail::select('action')
            ->distinct()
            ->whereNotNull('action')
            ->orderBy('action')
            ->pluck('action');

        // Get unique models for filter dropdown
        $modelTypes = AuditTrail::select('model')
            ->distinct()
            ->whereNotNull('model')
            ->orderBy('model')
            ->pluck('model');

        // Get all users for filter dropdown
        $users = User::select('id', 'name', 'role')
            ->orderBy('name')
            ->get();

        // Determine which view to return based on user role
        $user = auth()->user();
        if ($user->isAdmin()) {
            return view('admin.audit-trail', compact('auditTrails', 'actionTypes', 'modelTypes', 'users'));
        } elseif ($user->isPresident()) {
            return view('president.audit-trail', compact('auditTrails', 'actionTypes', 'modelTypes', 'users'));
        } elseif ($user->isTreasurer()) {
            return view('treasurer.audit-trail', compact('auditTrails', 'actionTypes', 'modelTypes', 'users'));
        }
    }

    /**
     * Get monthly attendance data for the current year
     */
    private function getMonthlyAttendanceData()
    {
        $currentYear = now()->year;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $attendancePercentages = [];

        // Get total approved operators count
        $totalOperators = Operator::where('approval_status', 'approved')->count();

        if ($totalOperators == 0) {
            // Return zero data if no operators
            return [
                'labels' => $months,
                'data' => array_fill(0, 12, 0)
            ];
        }

        // For each month, calculate attendance percentage
        for ($month = 1; $month <= 12; $month++) {
            $meetings = Meeting::whereYear('meeting_date', $currentYear)
                ->whereMonth('meeting_date', $month)
                ->get();

            if ($meetings->isEmpty()) {
                // No meetings in this month
                $attendancePercentages[] = 0;
            } else {
                $totalPresentCount = 0;
                $totalPossibleAttendees = 0;

                foreach ($meetings as $meeting) {
                    $presentCount = $meeting->attendances()->where('status', 'present')->count();
                    $totalPresentCount += $presentCount;
                    $totalPossibleAttendees += $totalOperators;
                }

                // Calculate percentage
                $percentage = $totalPossibleAttendees > 0
                    ? round(($totalPresentCount / $totalPossibleAttendees) * 100, 1)
                    : 0;

                $attendancePercentages[] = $percentage;
            }
        }

        return [
            'labels' => $months,
            'data' => $attendancePercentages
        ];
    }

    /**
     * Get annual collections data for the current year
     */
    private function getAnnualCollectionsData()
    {
        $currentYear = now()->year;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyReceipts = [];
        $monthlyDisbursements = [];

        // For each month, calculate total receipts and disbursements
        for ($month = 1; $month <= 12; $month++) {
            // Get total receipts for the month
            $receipts = Transaction::where('type', 'receipt')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->sum('amount');

            // Get total disbursements for the month
            $disbursements = Transaction::where('type', 'disbursement')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->sum('amount');

            $monthlyReceipts[] = $receipts;
            $monthlyDisbursements[] = $disbursements;
        }

        return [
            'labels' => $months,
            'receipts' => $monthlyReceipts,
            'disbursements' => $monthlyDisbursements
        ];
    }

    /**
     * Calculate operator's current balance using the same logic as monthly breakdown
     * Positive = Available / Overpaid
     * Negative = Outstanding / Unpaid
     */
    private function calculateOperatorBalance($operatorId)
    {
        $operator = \App\Models\Operator::find($operatorId);
        if (!$operator) {
            return 0;
        }

        // Monthly particulars that count as obligations
        $monthlyParticulars = [
            'subscription_capital',
            'management_fee',
            'monthly_dues',
            'office_rental'
        ];

        // Total obligations
        $totalObligations = \App\Models\Transaction::where('operator_id', $operatorId)
            ->whereIn('particular', $monthlyParticulars)
            ->sum('amount');

        // Total payments (match modal logic)
        $totalPayments = \App\Models\Transaction::where('operator_id', $operatorId)
            ->where('particular', 'monthly_dues_payment')
            ->sum('amount');

        // Overall balance = payments - obligations
        return (float) ($totalPayments - $totalObligations);
    }

    /**
     * Get monthly balance breakdown for an operator
     * Returns an array of months with their respective obligations, payments, balance, and status
     * Covers all years with transaction data
     */
    public function getMonthlyBalances($operatorId = null)
    {
        // 1️⃣ Resolve operator ID
        if ($operatorId) {
            $operator = \App\Models\Operator::findOrFail($operatorId);
        } else {
            $operator = auth()->user()->operator;

            if (!$operator) {
                return response()->json([], 200);
            }
        }

        $operatorId = $operator->id;

        $monthlyParticulars = [
            'subscription_capital',
            'management_fee',
            'monthly_dues',
            'office_rental'
        ];

        $paymentParticular = 'monthly_dues_payment';

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Get all transactions
        $allTransactions = \App\Models\Transaction::where('operator_id', $operatorId)
            ->whereIn('particular', array_merge($monthlyParticulars, [$paymentParticular]))
            ->get();

        // Parse all transactions and expand ranges into individual months
        $expandedData = [];

        foreach ($allTransactions as $transaction) {
            $monthString = $transaction->month;
            
            // Parse month string formats:
            // "January 2024" or "January - March 2024"
            if (preg_match('/^(\w+)\s*-\s*(\w+)\s+(\d{4})$/', $monthString, $matches)) {
                // Range format: "January - March 2024"
                $fromMonth = $matches[1];
                $toMonth = $matches[2];
                $year = (int) $matches[3];
                
                $fromIndex = array_search($fromMonth, $months);
                $toIndex = array_search($toMonth, $months);
                
                if ($fromIndex !== false && $toIndex !== false) {
                    $monthCount = $toIndex - $fromIndex + 1;
                    $amountPerMonth = $transaction->amount / $monthCount;
                    
                    // Split into individual months
                    for ($i = $fromIndex; $i <= $toIndex; $i++) {
                        $key = $months[$i] . ' ' . $year;
                        
                        if (!isset($expandedData[$key])) {
                            $expandedData[$key] = [
                                'month' => $months[$i],
                                'year' => $year,
                                'month_number' => $i + 1,
                                'obligations' => 0,
                                'payments' => 0
                            ];
                        }
                        
                        if (in_array($transaction->particular, $monthlyParticulars)) {
                            $expandedData[$key]['obligations'] += $amountPerMonth;
                        } elseif ($transaction->particular === $paymentParticular) {
                            $expandedData[$key]['payments'] += $amountPerMonth;
                        }
                    }
                }
            } elseif (preg_match('/^(\w+)\s+(\d{4})$/', $monthString, $matches)) {
                // Single month format: "January 2024"
                $monthName = $matches[1];
                $year = (int) $matches[2];
                $key = $monthString;
                
                if (!isset($expandedData[$key])) {
                    $monthIndex = array_search($monthName, $months);
                    $expandedData[$key] = [
                        'month' => $monthName,
                        'year' => $year,
                        'month_number' => $monthIndex !== false ? $monthIndex + 1 : 0,
                        'obligations' => 0,
                        'payments' => 0
                    ];
                }
                
                if (in_array($transaction->particular, $monthlyParticulars)) {
                    $expandedData[$key]['obligations'] += $transaction->amount;
                } elseif ($transaction->particular === $paymentParticular) {
                    $expandedData[$key]['payments'] += $transaction->amount;
                }
            }
        }

        // Calculate balance and status for each month
        $monthlyBalances = [];
        
        foreach ($expandedData as $data) {
            $balance = $data['payments'] - $data['obligations'];
            
            // Status
            $status = 'paid';
            
            if ($data['obligations'] > 0) {
                if ($data['payments'] == 0) {
                    $status = 'unpaid';
                } elseif ($balance < 0) {
                    $status = 'partial';
                } elseif ($balance > 0) {
                    $status = 'overpaid';
                } else {
                    $status = 'paid';
                }
            }
            
            // Include only relevant months
            if ($data['obligations'] > 0 || $data['payments'] > 0) {
                $monthlyBalances[] = [
                    'month'        => $data['month'],
                    'year'         => $data['year'],
                    'month_number' => $data['month_number'],
                    'obligations'  => (float) $data['obligations'],
                    'payments'     => (float) $data['payments'],
                    'balance'      => (float) $balance,
                    'status'       => $status
                ];
            }
        }

        // Sort by year and month (newest first)
        usort($monthlyBalances, function($a, $b) {
            if ($a['year'] === $b['year']) {
                return $b['month_number'] - $a['month_number'];
            }
            return $b['year'] - $a['year'];
        });

        return response()->json($monthlyBalances);
    }


}