<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Operator;
use App\Models\OperatorDetail;
use App\Models\User;
use App\Models\AuditTrail;
use App\Notifications\OfficerElectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OfficerController extends Controller
{
    /**
     * Display a listing of officers
     */
    public function index()
    {
        $today = Carbon::today();
        $activeOfficers = Officer::with('operator.user')
            ->where('is_active', true)
            ->where('effective_to', '>=', $today) // Include current and future officers
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

        $boardOfDirectors = $activeOfficers->where('committee', 'Board of Directors');

        $otherOfficers = $activeOfficers->where('committee', 'Other Officers');

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

        $gadCommittee = [
            'chairperson' => $activeOfficers->where('position', 'gad_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'gad_vice_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'gad_secretary')->first(),
            'member' => $activeOfficers->where('position', 'gad_member')->first()
        ];

        $educationCommittee = [
            'chairperson' => $activeOfficers->where('position', 'education_chairperson')->first(),
            'secretary' => $activeOfficers->where('position', 'education_secretary')->first(),
            'member' => $activeOfficers->where('position', 'education_member')->first()
        ];

        // Get all approved operators
        $operators = Operator::with('user')
            ->where('approval_status', 'approved')
            ->get();

        return view('officers.index', compact(
            'singleOfficers',
            'boardOfDirectors',
            'otherOfficers',
            'auditCommittee',
            'electionCommittee',
            'mediationCommittee',
            'ethicsCommittee',
            'gadCommittee',
            'educationCommittee',
            'operators'
        ));
    }

    /**
     * Store a newly created officers assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Executive Officers
            'chairperson_id' => 'required|exists:operators,id',
            'chairperson_effective_from' => 'required|date',
            'chairperson_effective_to' => 'required|date|after:chairperson_effective_from',
            'vice_chairperson_id' => 'required|exists:operators,id',
            'vice_chairperson_effective_from' => 'required|date',
            'vice_chairperson_effective_to' => 'required|date|after:vice_chairperson_effective_from',
            'secretary_id' => 'required|exists:operators,id',
            'secretary_effective_from' => 'required|date',
            'secretary_effective_to' => 'required|date|after:secretary_effective_from',
            'treasurer_id' => 'required|exists:operators,id',
            'treasurer_effective_from' => 'required|date',
            'treasurer_effective_to' => 'required|date|after:treasurer_effective_from',
            'general_manager_id' => 'required|exists:operators,id',
            'general_manager_effective_from' => 'required|date',
            'general_manager_effective_to' => 'required|date|after:general_manager_effective_from',
            'bookkeeper_id' => 'required|exists:operators,id',
            'bookkeeper_effective_from' => 'required|date',
            'bookkeeper_effective_to' => 'required|date|after:bookkeeper_effective_from',
            'bod_members' => 'nullable|array',
            'bod_members.*' => 'nullable|exists:operators,id',
            'bod_effective_from' => 'nullable|date',
            'bod_effective_to' => 'nullable|date|after:bod_effective_from',
            // Committee Officers - Audit Committee
            'audit_chairperson_id' => 'nullable|exists:operators,id',
            'audit_chairperson_effective_from' => 'nullable|date',
            'audit_chairperson_effective_to' => 'nullable|date|after:audit_chairperson_effective_from',
            'audit_vice_chairperson_id' => 'nullable|exists:operators,id',
            'audit_vice_chairperson_effective_from' => 'nullable|date',
            'audit_vice_chairperson_effective_to' => 'nullable|date|after:audit_vice_chairperson_effective_from',
            'audit_secretary_id' => 'nullable|exists:operators,id',
            'audit_secretary_effective_from' => 'nullable|date',
            'audit_secretary_effective_to' => 'nullable|date|after:audit_secretary_effective_from',
            'audit_member_id' => 'nullable|exists:operators,id',
            'audit_member_effective_from' => 'nullable|date',
            'audit_member_effective_to' => 'nullable|date|after:audit_member_effective_from',
            // Committee Officers - Election Committee
            'election_chairperson_id' => 'nullable|exists:operators,id',
            'election_chairperson_effective_from' => 'nullable|date',
            'election_chairperson_effective_to' => 'nullable|date|after:election_chairperson_effective_from',
            'election_vice_chairperson_id' => 'nullable|exists:operators,id',
            'election_vice_chairperson_effective_from' => 'nullable|date',
            'election_vice_chairperson_effective_to' => 'nullable|date|after:election_vice_chairperson_effective_from',
            'election_secretary_id' => 'nullable|exists:operators,id',
            'election_secretary_effective_from' => 'nullable|date',
            'election_secretary_effective_to' => 'nullable|date|after:election_secretary_effective_from',
            'election_member_id' => 'nullable|exists:operators,id',
            'election_member_effective_from' => 'nullable|date',
            'election_member_effective_to' => 'nullable|date|after:election_member_effective_from',
            // Committee Officers - Mediation Committee
            'mediation_chairperson_id' => 'nullable|exists:operators,id',
            'mediation_chairperson_effective_from' => 'nullable|date',
            'mediation_chairperson_effective_to' => 'nullable|date|after:mediation_chairperson_effective_from',
            'mediation_vice_chairperson_id' => 'nullable|exists:operators,id',
            'mediation_vice_chairperson_effective_from' => 'nullable|date',
            'mediation_vice_chairperson_effective_to' => 'nullable|date|after:mediation_vice_chairperson_effective_from',
            'mediation_secretary_id' => 'nullable|exists:operators,id',
            'mediation_secretary_effective_from' => 'nullable|date',
            'mediation_secretary_effective_to' => 'nullable|date|after:mediation_secretary_effective_from',
            'mediation_member_id' => 'nullable|exists:operators,id',
            'mediation_member_effective_from' => 'nullable|date',
            'mediation_member_effective_to' => 'nullable|date|after:mediation_member_effective_from',
            // Committee Officers - Ethics Committee
            'ethics_chairperson_id' => 'nullable|exists:operators,id',
            'ethics_chairperson_effective_from' => 'nullable|date',
            'ethics_chairperson_effective_to' => 'nullable|date|after:ethics_chairperson_effective_from',
            'ethics_vice_chairperson_id' => 'nullable|exists:operators,id',
            'ethics_vice_chairperson_effective_from' => 'nullable|date',
            'ethics_vice_chairperson_effective_to' => 'nullable|date|after:ethics_vice_chairperson_effective_from',
            'ethics_secretary_id' => 'nullable|exists:operators,id',
            'ethics_secretary_effective_from' => 'nullable|date',
            'ethics_secretary_effective_to' => 'nullable|date|after:ethics_secretary_effective_from',
            'ethics_member_id' => 'nullable|exists:operators,id',
            'ethics_member_effective_from' => 'nullable|date',
            'ethics_member_effective_to' => 'nullable|date|after:ethics_member_effective_from',
            // Committee Officers - GAD Committee
            'gad_chairperson_id' => 'nullable|exists:operators,id',
            'gad_chairperson_effective_from' => 'nullable|date',
            'gad_chairperson_effective_to' => 'nullable|date|after:gad_chairperson_effective_from',
            'gad_vice_chairperson_id' => 'nullable|exists:operators,id',
            'gad_vice_chairperson_effective_from' => 'nullable|date',
            'gad_vice_chairperson_effective_to' => 'nullable|date|after:gad_vice_chairperson_effective_from',
            'gad_secretary_id' => 'nullable|exists:operators,id',
            'gad_secretary_effective_from' => 'nullable|date',
            'gad_secretary_effective_to' => 'nullable|date|after:gad_secretary_effective_from',
            'gad_member_id' => 'nullable|exists:operators,id',
            'gad_member_effective_from' => 'nullable|date',
            'gad_member_effective_to' => 'nullable|date|after:gad_member_effective_from',
            // Committee Officers - Education Committee
            'education_chairperson_id' => 'nullable|exists:operators,id',
            'education_secretary_id' => 'nullable|exists:operators,id',
            'education_member_id' => 'nullable|exists:operators,id',
            'education_chairperson_effective_from' => 'nullable|date',
            'education_chairperson_effective_to' => 'nullable|date|after:education_chairperson_effective_from',
            'education_secretary_effective_from' => 'nullable|date',
            'education_secretary_effective_to' => 'nullable|date|after:education_secretary_effective_from',
            'education_member_effective_from' => 'nullable|date',
            'education_member_effective_to' => 'nullable|date|after:education_member_effective_from',
        ]);

        // Build assignments array
        $singlePositions = [
            'chairperson' => $validated['chairperson_id'],
            'vice_chairperson' => $validated['vice_chairperson_id'],
            'secretary' => $validated['secretary_id'],
            'treasurer' => $validated['treasurer_id'],
            'general_manager' => $validated['general_manager_id'],
            'bookkeeper' => $validated['bookkeeper_id'],
        ];

        // Check for duplicate assignments in single positions
        $uniqueOperators = array_unique(array_values($singlePositions));
        if (count($uniqueOperators) !== count($singlePositions)) {
            return response()->json([
                'success' => false,
                'message' => 'The same operator cannot hold multiple single positions.'
            ], 422);
        }

        $allAssignedOperators = array_values($singlePositions);

        // Add committee officers
        $committeeOfficerKeys = [
            'audit_chairperson_id', 'audit_vice_chairperson_id', 'audit_secretary_id', 'audit_member_id',
            'election_chairperson_id', 'election_vice_chairperson_id', 'election_secretary_id', 'election_member_id',
            'mediation_chairperson_id', 'mediation_vice_chairperson_id', 'mediation_secretary_id', 'mediation_member_id',
            'ethics_chairperson_id', 'ethics_vice_chairperson_id', 'ethics_secretary_id', 'ethics_member_id',
            'gad_chairperson_id', 'gad_vice_chairperson_id', 'gad_secretary_id', 'gad_member_id',
            'education_chairperson_id', 'education_secretary_id', 'education_member_id'
        ];

        foreach ($committeeOfficerKeys as $key) {
            if (!empty($validated[$key])) {
                $allAssignedOperators[] = $validated[$key];
            }
        }

        // Add BOD members
        if (!empty($validated['bod_members'])) {
            foreach ($validated['bod_members'] as $bodMember) {
                if (!empty($bodMember)) {
                    $allAssignedOperators[] = $bodMember;
                }
            }
        }

        // Check for duplicates across ALL assignments
        $uniqueOperators = array_unique(array_filter($allAssignedOperators));
        if (count($allAssignedOperators) !== count($uniqueOperators)) {
            return response()->json([
                'success' => false,
                'message' => 'The same operator cannot hold multiple positions.'
            ], 422);
        }

        // Deactivate current officers for all positions
        $allPositions = [
            'chairperson', 'vice_chairperson', 'secretary', 'treasurer', 'general_manager', 'bookkeeper',
            'bod_member',
            'audit_chairperson', 'audit_vice_chairperson', 'audit_secretary',
            'election_chairperson', 'election_vice_chairperson', 'election_secretary',
            'mediation_chairperson', 'mediation_vice_chairperson', 'mediation_secretary',
            'ethics_chairperson', 'ethics_vice_chairperson', 'ethics_secretary',
            'gad_chairperson', 'gad_vice_chairperson', 'gad_secretary',
            'education_chairperson', 'education_secretary', 'education_member'
        ];
        Officer::whereIn('position', $allPositions)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Store operator IDs for easy reference
        $chairpersonId = $validated['chairperson_id'];
        $viceChairpersonId = $validated['vice_chairperson_id'];
        $secretaryId = $validated['secretary_id'];
        $treasurerId = $validated['treasurer_id'];
        $generalManagerId = $validated['general_manager_id'];
        $bookkeeperId = $validated['bookkeeper_id'];

        // Create new officer assignments for single positions with individual term dates
        foreach ($singlePositions as $position => $operatorId) {
            Officer::create([
                'operator_id' => $operatorId,
                'position' => $position,
                'committee' => null, // Will be assigned to committees below
                'effective_from' => $validated["{$position}_effective_from"],
                'effective_to' => $validated["{$position}_effective_to"],
                'is_active' => true,
            ]);
        }

        $committeeOfficers = [
            'audit' => ['chairperson' => 'audit_chairperson', 'vice_chairperson' => 'audit_vice_chairperson', 'secretary' => 'audit_secretary', 'member' => 'audit_member'],
            'election' => ['chairperson' => 'election_chairperson', 'vice_chairperson' => 'election_vice_chairperson', 'secretary' => 'election_secretary', 'member' => 'election_member'],
            'mediation' => ['chairperson' => 'mediation_chairperson', 'vice_chairperson' => 'mediation_vice_chairperson', 'secretary' => 'mediation_secretary', 'member' => 'mediation_member'],
            'ethics' => ['chairperson' => 'ethics_chairperson', 'vice_chairperson' => 'ethics_vice_chairperson', 'secretary' => 'ethics_secretary', 'member' => 'ethics_member'],
            'gad' => ['chairperson' => 'gad_chairperson', 'vice_chairperson' => 'gad_vice_chairperson', 'secretary' => 'gad_secretary', 'member' => 'gad_member']
        ];

        foreach ($committeeOfficers as $committee => $positions) {
            foreach ($positions as $position => $fieldName) {
                // Get individual dates for each position
                $effectiveFrom = $validated["{$fieldName}_effective_from"] ?? null;
                $effectiveTo = $validated["{$fieldName}_effective_to"] ?? null;

                if (!empty($validated["{$fieldName}_id"]) && $effectiveFrom && $effectiveTo) {
                    Officer::create([
                        'operator_id' => $validated["{$fieldName}_id"],
                        'position' => "{$committee}_{$position}",
                        'committee' => null, // Will be assigned below
                        'effective_from' => $effectiveFrom,
                        'effective_to' => $effectiveTo,
                        'is_active' => true,
                    ]);
                }
            }
        }

        $educationPositions = ['chairperson', 'secretary', 'member'];
        foreach ($educationPositions as $position) {
            $fieldName = "education_{$position}_id";
            $effectiveFromField = "education_{$position}_effective_from";
            $effectiveToField = "education_{$position}_effective_to";

            if (!empty($validated[$fieldName]) && !empty($validated[$effectiveFromField]) && !empty($validated[$effectiveToField])) {
                Officer::create([
                    'operator_id' => $validated[$fieldName],
                    'position' => "education_{$position}",
                    'committee' => null, // Will be assigned below
                    'effective_from' => $validated[$effectiveFromField],
                    'effective_to' => $validated[$effectiveToField],
                    'is_active' => true,
                ]);
            }
        }

        if (!empty($validated['bod_members']) && !empty($validated['bod_effective_from']) && !empty($validated['bod_effective_to'])) {
            foreach ($validated['bod_members'] as $bodMemberId) {
                if (!empty($bodMemberId)) {
                    Officer::create([
                        'operator_id' => $bodMemberId,
                        'position' => 'bod_member',
                        'committee' => null, // Will be assigned to Board of Directors below
                        'effective_from' => $validated['bod_effective_from'],
                        'effective_to' => $validated['bod_effective_to'],
                        'is_active' => true,
                    ]);
                }
            }
        }

        // Now assign officers to committees automatically
        $this->assignToCommittees($validated, $chairpersonId, $secretaryId);

        // Create user accounts and send notifications for key officer positions
        $this->createOfficerAccountsAndNotify($validated);

        return response()->json([
            'success' => true,
            'message' => 'Officers assigned successfully!'
        ]);
    }

    /**
     * Generate a unique email for officer account based on role
     * This avoids the unique email constraint while keeping emails recognizable
     */
    private function generateOfficerEmail($originalEmail, $role)
    {
        // Split email into local part and domain
        $parts = explode('@', $originalEmail);
        $localPart = $parts[0];
        $domain = $parts[1] ?? 'example.com';

        // Add role suffix to make it unique
        $roleSuffix = match($role) {
            'president' => '.president',
            'treasurer' => '.treasurer',
            'auditor' => '.auditor',
            default => '.officer'
        };

        return "{$localPart}{$roleSuffix}@{$domain}";
    }

    /**
     * Create user accounts for elected officers and send notifications
     */
    private function createOfficerAccountsAndNotify($validated)
    {
        // Define positions that should get user accounts with their corresponding roles
        $keyPositions = [
            'chairperson' => 'president',
            'secretary' => 'auditor',
            'treasurer' => 'treasurer',
        ];

        foreach ($keyPositions as $position => $role) {
            $operatorId = $validated["{$position}_id"];
            $effectiveFrom = $validated["{$position}_effective_from"];
            $effectiveTo = $validated["{$position}_effective_to"];

            // Get the operator with their user
            $operator = Operator::with('user')->find($operatorId);

            if (!$operator || !$operator->user) {
                continue; // Skip if operator or user not found
            }

            $operatorUser = $operator->user;

            // Generate the expected officer email for this role
            $officerEmail = $this->generateOfficerEmail($operatorUser->email, $role);

            // Check if an officer account already exists for this specific operator and role
            // We check by the generated officer email which is unique per operator per role
            $existingOfficerAccount = User::where('email', $officerEmail)
                ->where('role', $role)
                ->first();

            if ($existingOfficerAccount) {
                // If officer account already exists, just send notification
                $officer = Officer::where('operator_id', $operatorId)
                    ->where('position', $position)
                    ->where('is_active', true)
                    ->where('effective_from', $effectiveFrom)
                    ->first();

                if ($officer) {
                    $operatorUser->notify(new OfficerElectedNotification($officer, $existingOfficerAccount, false));
                }
                continue;
            }

            // Create new officer user account
            // (officerEmail already generated above)
            $officerUser = User::create([
                'name' => $operatorUser->name,
                'email' => $officerEmail, // Use role-specific email to avoid unique constraint
                'password' => $operatorUser->password, // Use same password hash as operator account
                'role' => $role,
                'user_id' => User::generateUserId($role),
            ]);

            // Get the newly created officer record
            $officer = Officer::where('operator_id', $operatorId)
                ->where('position', $position)
                ->where('is_active', true)
                ->where('effective_from', $effectiveFrom)
                ->first();

            if ($officer && $officerUser) {
                // Send notification email to the operator
                $operatorUser->notify(new OfficerElectedNotification($officer, $officerUser, true));

                // Log the account creation
                AuditTrail::log(
                    'created',
                    "Created officer user account for {$operatorUser->name} - Position: {$officer->formatted_position} (User ID: {$officerUser->user_id})",
                    'User',
                    $officerUser->id
                );
            }
        }
    }

    /**
     * Update officer status
     */
    public function updateStatus(Request $request, Officer $officer)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        // Capture original values before update
        $originalValues = $officer->getOriginal();

        $officer->update(['is_active' => $validated['is_active']]);

        // Track changes for audit trail
        if ($originalValues['is_active'] != $validated['is_active']) {
            $statusText = $validated['is_active'] ? 'activated' : 'deactivated';
            $operatorName = $officer->operator ? $officer->operator->full_name : 'Unknown';

            $changes = [
                'is_active' => [
                    'old' => $originalValues['is_active'] ? 'Active' : 'Inactive',
                    'new' => $validated['is_active'] ? 'Active' : 'Inactive'
                ]
            ];

            AuditTrail::log(
                'updated',
                "Officer status {$statusText}: {$operatorName} ({$officer->position})",
                'Officer',
                $officer->id,
                $changes
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Officer status updated successfully!'
        ]);
    }

    /**
     * Remove the specified officer
     */
    public function destroy(Officer $officer)
    {
        $officer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Officer removed successfully!'
        ]);
    }

    /**
     * Automatically assign officers to their respective committees
     */
    private function assignToCommittees($validated, $chairpersonId, $secretaryId)
    {
        $bodMembers = [];
        $bodMembers[] = ['id' => $chairpersonId, 'date' => $validated['chairperson_effective_from']];
        $bodMembers[] = ['id' => $validated['vice_chairperson_id'], 'date' => $validated['vice_chairperson_effective_from']];

        if (!empty($validated['bod_members']) && !empty($validated['bod_effective_from'])) {
            foreach (array_slice($validated['bod_members'], 0, 5) as $bodMemberId) {
                if (!empty($bodMemberId)) {
                    $bodMembers[] = ['id' => $bodMemberId, 'date' => $validated['bod_effective_from']];
                }
            }
        }

        foreach ($bodMembers as $member) {
            Officer::where('operator_id', $member['id'])
                ->where('is_active', true)
                ->where('effective_from', $member['date'])
                ->update(['committee' => 'Board of Directors']);
        }

        // Other Officers: Secretary, Treasurer, General Manager
        $otherOfficers = [
            ['id' => $secretaryId, 'date' => $validated['secretary_effective_from']],
            ['id' => $validated['treasurer_id'], 'date' => $validated['treasurer_effective_from']],
            ['id' => $validated['general_manager_id'], 'date' => $validated['general_manager_effective_from']]
        ];

        foreach ($otherOfficers as $officer) {
            Officer::where('operator_id', $officer['id'])
                ->where('is_active', true)
                ->where('effective_from', $officer['date'])
                ->update(['committee' => 'Other Officers']);
        }

        // Assign committee officers to their respective committees
        $committees = [
            'audit' => 'Audit Committee',
            'election' => 'Election Committee',
            'mediation' => 'Mediation and Conciliation Committee',
            'ethics' => 'Ethics Committee',
            'gad' => 'Gender and Development Committee'
        ];

        foreach ($committees as $committeeKey => $committeeName) {
            $officerTypes = ['chairperson', 'vice_chairperson', 'secretary', 'member'];
            foreach ($officerTypes as $officerType) {
                $fieldName = "{$committeeKey}_{$officerType}";
                $effectiveFrom = $validated["{$fieldName}_effective_from"] ?? null;

                if (!empty($validated["{$fieldName}_id"]) && $effectiveFrom) {
                    Officer::where('operator_id', $validated["{$fieldName}_id"])
                        ->where('is_active', true)
                        ->where('effective_from', $effectiveFrom)
                        ->update(['committee' => $committeeName]);
                }
            }
        }

        $educationOfficerTypes = ['chairperson', 'secretary', 'member'];
        foreach ($educationOfficerTypes as $officerType) {
            $fieldName = "education_{$officerType}_id";
            $effectiveFromField = "education_{$officerType}_effective_from";

            if (!empty($validated[$fieldName]) && !empty($validated[$effectiveFromField])) {
                Officer::where('operator_id', $validated[$fieldName])
                    ->where('is_active', true)
                    ->where('effective_from', $validated[$effectiveFromField])
                    ->update(['committee' => 'Education Committee']);
            }
        }
    }

    /**
     * Download Officers List as PDF
     */
    public function downloadPdf()
    {
        $today = Carbon::today();

        // ✅ Load operator.detail so Indigenous People & PWD are available
        $activeOfficers = Officer::with([
                'operator.user',
                'operator.detail'
            ])
            ->where('is_active', true)
            ->where('effective_to', '>=', $today)
            ->get();


        // ✅ Add this debug code temporarily
        foreach($activeOfficers as $officer) {
            if($officer->operator && $officer->operator->detail) {
                \Log::info('Officer Detail Check:', [
                    'name' => $officer->operator->full_name,
                    'indigenous_people_raw' => $officer->operator->detail->indigenous_people,
                    'pwd_raw' => $officer->operator->detail->pwd,
                    'senior_citizen_raw' => $officer->operator->detail->senior_citizen,
                    'indigenous_people_type' => gettype($officer->operator->detail->indigenous_people),
                    'pwd_type' => gettype($officer->operator->detail->pwd),
                    'senior_citizen_type' => gettype($officer->operator->detail->senior_citizen),
                ]);
            }
        }

        // Organize officers by position
        $singleOfficers = [
            'chairperson'       => $activeOfficers->where('position', 'chairperson')->first(),
            'vice_chairperson'  => $activeOfficers->where('position', 'vice_chairperson')->first(),
            'secretary'         => $activeOfficers->where('position', 'secretary')->first(),
            'treasurer'         => $activeOfficers->where('position', 'treasurer')->first(),
            'general_manager'   => $activeOfficers->where('position', 'general_manager')->first(),
            'bookkeeper'        => $activeOfficers->where('position', 'bookkeeper')->first(),
        ];

        // Board of Directors
        $boardOfDirectors = $activeOfficers->where('committee', 'Board of Directors');

        // Other Officers
        $otherOfficers = $activeOfficers->where('committee', 'Other Officers');

        // Committees
        $auditCommittee = [
            'chairperson'      => $activeOfficers->where('position', 'audit_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'audit_vice_chairperson')->first(),
            'secretary'        => $activeOfficers->where('position', 'audit_secretary')->first(),
            'member'           => $activeOfficers->where('position', 'audit_member')->first(),
        ];

        $electionCommittee = [
            'chairperson'      => $activeOfficers->where('position', 'election_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'election_vice_chairperson')->first(),
            'secretary'        => $activeOfficers->where('position', 'election_secretary')->first(),
            'member'           => $activeOfficers->where('position', 'election_member')->first(),
        ];

        $mediationCommittee = [
            'chairperson'      => $activeOfficers->where('position', 'mediation_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'mediation_vice_chairperson')->first(),
            'secretary'        => $activeOfficers->where('position', 'mediation_secretary')->first(),
            'member'           => $activeOfficers->where('position', 'mediation_member')->first(),
        ];

        $ethicsCommittee = [
            'chairperson'      => $activeOfficers->where('position', 'ethics_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'ethics_vice_chairperson')->first(),
            'secretary'        => $activeOfficers->where('position', 'ethics_secretary')->first(),
            'member'           => $activeOfficers->where('position', 'ethics_member')->first(),
        ];

        $genderCommittee = [
            'chairperson'      => $activeOfficers->where('position', 'gad_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'gad_vice_chairperson')->first(),
            'secretary'        => $activeOfficers->where('position', 'gad_secretary')->first(),
            'member'           => $activeOfficers->where('position', 'gad_member')->first(),
        ];

        $educationCommittee = [
            'chairperson'      => $activeOfficers->where('position', 'education_chairperson')->first(),
            'vice_chairperson' => $activeOfficers->where('position', 'education_vice_chairperson')->first(),
            'secretary'        => $activeOfficers->where('position', 'education_secretary')->first(),
            'member'           => $activeOfficers->where('position', 'education_member')->first(),
        ];

        $data = compact(
            'singleOfficers',
            'boardOfDirectors',
            'otherOfficers',
            'auditCommittee',
            'electionCommittee',
            'mediationCommittee',
            'ethicsCommittee',
            'genderCommittee',
            'educationCommittee'
        );

        // Generate PDF
        $pdf = Pdf::loadView('officers.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'     => true,
            ]);

        // Log PDF download
        AuditTrail::log(
            'download',
            'Downloaded Officers List PDF'
        );

        return $pdf->download('officers-list-' . date('Y-m-d') . '.pdf');
    }

}
