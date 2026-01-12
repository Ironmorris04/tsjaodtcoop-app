<?php
// app/Http/Controllers/OperatorController.php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        // Only show approved operators
        $query = Operator::with('user')
            ->where('approval_status', 'approved');

        // Handle search query
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('business_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('contact_person', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $operators = $query->latest()->paginate(10);

        return view('operators.index', compact('operators'));
    }

    public function create()
    {
        return view('operators.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:operators,email',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'citizenship' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,widowed,separated',
            'indigenous_people' => 'nullable|in:yes,no',
            'pwd' => 'nullable|in:yes,no',
            'senior_citizen' => 'nullable|in:yes,no',
            'fourps_beneficiary' => 'nullable|in:yes,no',
            'id_type' => 'required|string',
            'id_number' => 'required|string|max:255',
            'profile_photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'valid_id' => 'required|mimes:png,jpg,jpeg,pdf|max:5120',
        ]);

        // ===== NEW: Handle file uploads =====
        $validIdPath = null;
        $profilePhotoPath = null;

        if ($request->hasFile('valid_id')) {
            $validIdPath = $request->file('valid_id')->store('ids', 's3');
        }

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profiles', 's3');
        }
        // ===================================

        // Create user account without password
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make(uniqid()), // Temporary password
            'role' => 'operator',
        ]);

        // Generate user ID
        $userId = User::generateUserId('operator');
        $user->user_id = $userId;
        $user->save();

        // Generate setup token for password setup
        $setupToken = $user->generateSetupToken();

        // Create operator profile with pending status
        $operator = Operator::create([
            'user_id' => $user->id,
            'business_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'contact_person' => $user->name,
            'phone' => $validated['contact_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'approval_status' => 'pending',
            'status' => 'inactive',
        ]);

        // ===== NEW: Create operator_details record =====
        DB::table('operator_details')->insert([
            'operator_id' => $operator->id,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'birthdate' => $validated['birthdate'],
            'birthplace' => $validated['birthplace'],
            'religion' => $validated['religion'],
            'citizenship' => $validated['citizenship'],
            'occupation' => $validated['occupation'],
            'sex' => $validated['sex'],
            'civil_status' => $validated['civil_status'],
            'indigenous_people' => $validated['indigenous_people'] ?? 'no',
            'pwd' => $validated['pwd'] ?? 'no',
            'senior_citizen' => $validated['senior_citizen'] ?? 'no',
            'fourps_beneficiary' => $validated['fourps_beneficiary'] ?? 'no',
            'id_type' => $validated['id_type'],
            'id_number' => $validated['id_number'],
            'valid_id_path' => $validIdPath,
            'profile_photo_path' => $profilePhotoPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // =============================================

        // ===== NEW: Store dependents if provided =====
        if ($request->has('dependent_name')) {
            $names = $request->dependent_name;
            $ages = $request->dependent_age;
            $relations = $request->dependent_relation;

            for ($i = 0; $i < count($names); $i++) {
                // Only insert if name is not empty
                if (!empty($names[$i])) {
                    DB::table('operator_dependents')->insert([
                        'operator_id' => $operator->id,
                        'name' => $names[$i],
                        'age' => $ages[$i] ?? null,
                        'relation' => $relations[$i] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        // ============================================

        // Log operator creation
        AuditTrail::log(
            'created',
            "Created new operator registration: {$user->name}",
            'Operator',
            $operator->id
        );

        return redirect()->route('registrations.index')
            ->with('success', "Operator registration created successfully! The operator has been added to pending registrations for review.");
    }

    public function show(Operator $operator)
    {
        $operator->load('drivers', 'units');
        return view('operators.show', compact('operator'));
    }

    public function edit(Operator $operator)
    {
        return view('operators.edit', compact('operator'));
    }

    public function update(Request $request, Operator $operator)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'unique:operators,email,' . $operator->id,
                'unique:users,email,' . $operator->user_id
            ],
            'address' => 'required|string',
            'business_permit_no' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Capture original values before update
        $originalValues = $operator->getOriginal();

        // Update operator
        $operator->update($validated);

        // Track changes for audit trail
        $changes = [];
        $changedFields = [];

        foreach ($validated as $key => $newValue) {
            $oldValue = $originalValues[$key] ?? null;

            // Only log if value actually changed
            if ($oldValue != $newValue) {
                $changedFields[] = $key;
                $changes[$key] = [
                    'old' => $oldValue ?? 'None',
                    'new' => $newValue ?? 'None'
                ];
            }
        }

        // Also update user email if changed
        if ($operator->user && $validated['email'] !== $operator->user->email) {
            $operator->user->update(['email' => $validated['email']]);
        }

        // Log operator update with changes
        $description = "Updated operator information: {$operator->business_name}";
        if (!empty($changedFields)) {
            $description .= " (Changed: " . implode(', ', $changedFields) . ")";
        }

        AuditTrail::log(
            'updated',
            $description,
            'Operator',
            $operator->id,
            $changes
        );

        return redirect()->route('operators.index')
            ->with('success', 'Operator updated successfully.');
    }

    public function destroy(Request $request, Operator $operator): RedirectResponse
    {
        // 1️⃣ Validate admin password
        $request->validate([
            'admin_password' => ['required'],
        ]);

        $admin = Auth::user();

        // 2️⃣ Verify admin password
        if (! Hash::check($request->admin_password, $admin->password)) {
            return back()->withErrors([
                'admin_password' => 'The admin password is incorrect.',
            ]);
        }

        $operatorName = $operator->business_name;

        // 3️⃣ Disable operator user account (DO NOT DELETE USER)
        if ($operator->user) {
            $operator->user->update([
                'status' => 'inactive', // if you have this column
            ]);
        }

        // 4️⃣ Unregister operator (soft delete)
        $operator->delete();

        // 5️⃣ Log audit trail
        AuditTrail::log(
            'unregistered',
            "Unregistered operator: {$operatorName}",
            'Operator',
            $operator->id
        );

        return redirect()
            ->route('operators.index')
            ->with('success', 'Operator has been successfully unregistered.');
    }

    // View archived operators
    public function archived()
    {
        $operators = Operator::onlyTrashed()->get();
        return view('operators.archived', compact('operators'));
    }

    // Restore an operator
    //public function restore($id)
    //{
    //    $operator = Operator::onlyTrashed()->findOrFail($id);
    //    $operator->restore();
        
    //    return redirect()->route('operators.archived')
    //        ->with('success', 'Operator restored successfully');
    //}

    // Permanently delete
    //public function forceDestroy($id)
    //{
    //    $operator = Operator::onlyTrashed()->findOrFail($id);
    //    $operator->forceDelete();
        
    //    return redirect()->route('operators.archived')
    //        ->with('success', 'Operator permanently deleted');
    //}

    public function restore($id)
    {
        $operator = Operator::onlyTrashed()->findOrFail($id);
        $operator->restore();
        
        // Reactivate user account if exists
        if ($operator->user) {
            $operator->user->update(['status' => 'active']);
        }
        
        AuditTrail::log(
            'restored',
            "Restored operator: {$operator->business_name}",
            'Operator',
            $operator->id
        );
        
        return redirect()
            ->route('operators.index')
            ->with('success', 'Operator has been successfully restored.');
    }

    public function forceDestroy(Request $request, $id)
    {
        $request->validate([
            'admin_password' => ['required'],
        ]);

        $admin = Auth::user();

        if (!Hash::check($request->admin_password, $admin->password)) {
            return redirect()
                ->route('operators.index')
                ->with('warning', 'The admin password is incorrect.');
        }

        $operator = Operator::onlyTrashed()->findOrFail($id);
        $operatorName = $operator->business_name;

        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECKS (only unpaid penalties now)
        |--------------------------------------------------------------------------
        */

        if ($operator->total_unpaid_penalties > 0) {
            return redirect()
                ->route('operators.index')
                ->with('warning', "Operator '{$operatorName}' has unpaid penalties. Cannot permanently delete.");
        }

        /*
        |--------------------------------------------------------------------------
        | PERMANENT DELETE
        |--------------------------------------------------------------------------
        */

        if ($operator->user) {
            $operator->user->forceDelete();
        }

        $operator->forceDelete();

        AuditTrail::log(
            'permanently_deleted',
            "Permanently deleted operator: {$operatorName}",
            'Operator',
            $operator->id
        );

        return redirect()
            ->route('operators.index')
            ->with('success', "Operator '{$operatorName}' has been permanently deleted.");
    }

}
