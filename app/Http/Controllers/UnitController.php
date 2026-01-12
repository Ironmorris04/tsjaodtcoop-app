<?php
// app/Http/Controllers/UnitController.php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Driver;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $operator = auth()->user()->operator;

        // Check if user has an operator profile
        if (!$operator) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to have an operator profile to access this page.');
        }

        // Get approved units with driver relationship
        $approvedUnits = $operator->units()
            ->with('driver')
            ->where('approval_status', 'approved')
            ->latest()
            ->get();

        // Get pending units
        $pendingUnits = $operator->units()
            ->where('approval_status', 'pending')
            ->latest()
            ->get();

        // Keep original pagination for backward compatibility
        $units = $operator->units()->with('driver')->latest()->paginate(10);

        return view('units.index', compact('units', 'approvedUnits', 'pendingUnits'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        try {
            $operator = auth()->user()->operator;

            // Check if user has an operator profile
            if (!$operator) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You need to have an operator profile to perform this action.'
                    ], 403);
                }
                return redirect()->route('dashboard')
                    ->with('error', 'You need to have an operator profile to perform this action.');
            }

            $validated = $request->validate([
                'plate_no' => 'required|string|unique:units,plate_no',
                'body_number' => 'nullable|string|max:255',
                'engine_number' => 'nullable|string|max:255',
                'chassis_number' => 'nullable|string|max:255',
                'coding_number' => 'nullable|string|max:255',
                'police_number' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:255',
                'lto_cr_number' => 'nullable|string|max:255',
                'lto_cr_date_issued' => 'nullable|date',
                'lto_or_number' => 'nullable|string|max:255',
                'lto_or_date_issued' => 'nullable|date',
                'unit_cr_number' => 'nullable|string|max:255',
                'unit_or_number' => 'nullable|string|max:255',
                'franchise_case' => 'nullable|string|max:255',
                'mv_file' => 'nullable|string|max:255',
                'mbp_no_prev_year' => 'nullable|string|max:255',
                'mch_no_prev_year' => 'nullable|string|max:255',
                'year_model' => 'nullable|string|max:255',
                'type' => 'nullable|in:bus,jeepney,van,taxi',
                'model' => 'nullable|string|max:255',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'capacity' => 'nullable|integer|min:1',
                'status' => 'nullable|in:active,maintenance,inactive',
                'cr_receipt_photo' => 'nullable|mimes:png,jpg,jpeg|max:10240', // Max 10MB - PNG, JPG, JPEG only
                'cr_photo' => 'required|mimes:png,jpg,jpeg|max:10240', // Max 10MB - PNG, JPG, JPEG only
                'unit_photo' => 'nullable|mimes:png,jpg,jpeg|max:2048', // Max 2MB - PNG, JPG, JPEG only
                'business_permit_photo' => 'nullable|mimes:png,jpg,jpeg|max:10240', // Max 10MB - PNG, JPG, JPEG only
                'business_permit_no' => 'nullable|string|max:255', // Business permit number
                'business_permit_validity' => 'nullable|date', // Business permit validity date
                'or_photo' => 'nullable|mimes:png,jpg,jpeg|max:10240', // Max 10MB - PNG, JPG, JPEG only
                'or_number' => 'nullable|string|max:255', // OR number
                'or_date_issued' => 'nullable|date', // OR validity (renamed from date issued)
                'cr_number' => 'nullable|string|max:255', // CR number
                'cr_validity' => 'nullable|date', // CR validity date
            ], [
                'plate_no.required' => 'Plate number is required.',
                'plate_no.unique' => 'This plate number is already registered.',
                'year.integer' => 'Year must be a valid number.',
                'year.min' => 'Year must be 1900 or later.',
                'year.max' => 'Year cannot be in the future.',
                'capacity.integer' => 'Capacity must be a valid number.',
                'capacity.min' => 'Capacity must be at least 1.',
                'type.in' => 'Type must be bus, jeepney, van, or taxi.',
                'status.in' => 'Status must be active, maintenance, or inactive.',
                'cr_receipt_photo.mimes' => 'CR receipt photo must be a PNG, JPG, or JPEG image.',
                'cr_receipt_photo.max' => 'CR receipt photo must not exceed 10MB.',
                'cr_photo.required' => 'CR photo is required.',
                'cr_photo.mimes' => 'CR photo must be a PNG, JPG, or JPEG image.',
                'cr_photo.max' => 'CR photo must not exceed 10MB.',
                'unit_photo.mimes' => 'Unit photo must be a PNG, JPG, or JPEG image.',
                'unit_photo.max' => 'Unit photo must not exceed 2MB.',
                'business_permit_photo.mimes' => 'Business permit photo must be a PNG, JPG, or JPEG image.',
                'business_permit_photo.max' => 'Business permit photo must not exceed 10MB.',
                'or_photo.mimes' => 'OR photo must be a PNG, JPG, or JPEG image.',
                'or_photo.max' => 'OR photo must not exceed 10MB.',
            ]);

            // Handle CR receipt photo upload
            if ($request->hasFile('cr_receipt_photo')) {
                $path = $request->file('cr_receipt_photo')->store('cr_receipts', 's3');
                $validated['cr_receipt_photo'] = $path;
            }

            // Handle CR photo upload
            if ($request->hasFile('cr_photo')) {
                $path = $request->file('cr_photo')->store('unit_documents/cr_photos', 's3');
                $validated['cr_photo'] = $path;
            }

            // Handle unit photo upload
            if ($request->hasFile('unit_photo')) {
                $path = $request->file('unit_photo')->store('units/photos', 's3');
                $validated['unit_photo'] = $path;
            }

            // Handle business permit photo upload
            if ($request->hasFile('business_permit_photo')) {
                $path = $request->file('business_permit_photo')->store('unit_documents/business_permits', 's3');
                $validated['business_permit_photo'] = $path;
            }

            // Handle OR photo upload
            if ($request->hasFile('or_photo')) {
                $path = $request->file('or_photo')->store('unit_documents/or_photos', 's3');
                $validated['or_photo'] = $path;
            }


            // Set approval status to pending for new units
            $validated['approval_status'] = 'pending';

            $unit = $operator->units()->create($validated);

            // Log unit creation
            AuditTrail::log(
                'created',
                "Added new transport unit: {$unit->plate_no} ({$unit->type})",
                'Unit',
                $unit->id
            );

            // Return JSON for API calls
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transport unit added successfully. Pending admin approval.',
                    'unit' => $unit
                ]);
            }

            return redirect()->route('units.index')
                ->with('success', 'Transport unit added successfully. Pending admin approval.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function show(Unit $unit)
    {
        try {
            // Ensure operator can only view their own units
            $user = auth()->user();
            if (!$user->operator || $unit->operator_id != $user->operator->id) {
                if (request()->expectsJson() || request()->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
                abort(403);
            }

            // Load the driver relationship for full details
            $unit->load('driver');

            // Return JSON for API calls
            if (request()->expectsJson() || request()->is('api/*')) {
                // Build comprehensive response with formatted data and photo URLs
                $data = [
                    'id' => $unit->id,
                    'unit_id' => $unit->unit_id,
                    'plate_no' => $unit->plate_no,
                    'body_number' => $unit->body_number,
                    'engine_number' => $unit->engine_number,
                    'chassis_number' => $unit->chassis_number,
                    'coding_number' => $unit->coding_number,
                    'police_number' => $unit->police_number,
                    'color' => $unit->color,
                    'year_model' => $unit->year_model,
                    'model' => $unit->model,
                    'capacity' => $unit->capacity,
                    'type' => $unit->type,
                    'status' => $unit->status,

                    // LTO CR Information
                    'lto_cr_number' => $unit->lto_cr_number,
                    'lto_cr_date_issued' => $unit->lto_cr_date_issued ? $unit->lto_cr_date_issued->format('F d, Y') : null,
                    'lto_cr_date_issued_raw' => $unit->lto_cr_date_issued ? $unit->lto_cr_date_issued->toDateString() : null,

                    // LTO OR Information
                    'lto_or_number' => $unit->lto_or_number,
                    'lto_or_date_issued' => $unit->lto_or_date_issued ? $unit->lto_or_date_issued->format('F d, Y') : null,
                    'lto_or_date_issued_raw' => $unit->lto_or_date_issued ? $unit->lto_or_date_issued->toDateString() : null,

                    // Franchise & MV Information
                    'franchise_case' => $unit->franchise_case,
                    'mv_file' => $unit->mv_file,
                    'mbp_no_prev_year' => $unit->mbp_no_prev_year,
                    'mch_no_prev_year' => $unit->mch_no_prev_year,

                    // Photo URLs
                    'unit_photo_url' => $unit->unit_photo_url ?? null,
                    'cr_photo_url' => $unit->cr_photo_url ?? null,
                    'cr_receipt_photo_url' => $unit->cr_receipt_photo_url ?? null,
                    'business_permit_photo_url' => $unit->business_permit_photo_url ?? null,
                    'or_photo_url' => $unit->or_photo_url ?? null,

                    'unit_photo' => $unit->unit_photo,
                    'cr_photo' => $unit->cr_photo,
                    'cr_receipt_photo' => $unit->cr_receipt_photo,
                    'business_permit_photo' => $unit->business_permit_photo,
                    'or_photo' => $unit->or_photo,

                    // Business Permit Information
                    'business_permit_number' => $unit->business_permit_no,
                    'business_permit_no' => $unit->business_permit_no, // For edit form compatibility
                    'business_permit_validity' => $unit->business_permit_validity ? $unit->business_permit_validity->format('F d, Y') : null,
                    'business_permit_validity_raw' => $unit->business_permit_validity ? $unit->business_permit_validity->toDateString() : null,

                    // OR Information
                    'or_number' => $unit->or_number ?? $unit->lto_or_number,
                    'or_date_issued' => $unit->or_date_issued ? $unit->or_date_issued->format('F d, Y') : ($unit->lto_or_date_issued ? $unit->lto_or_date_issued->format('F d, Y') : null),
                    'or_date_issued_raw' => $unit->or_date_issued ? $unit->or_date_issued->toDateString() : null,

                    // CR Information
                    'cr_number' => $unit->cr_number ?? $unit->lto_cr_number,
                    'cr_validity' => $unit->cr_validity ? $unit->cr_validity->format('F d, Y') : null,
                    'cr_validity_raw' => $unit->cr_validity ? $unit->cr_validity->toDateString() : null,

                    // Approval Information
                    'approval_status' => $unit->approval_status,
                    'approved_at' => $unit->approved_at ? $unit->approved_at->format('F d, Y h:i A') : null,
                    'rejection_reason' => $unit->rejection_reason,

                    // Assigned Driver
                    'driver' => $unit->driver ? [
                        'id' => $unit->driver->id,
                        'full_name' => $unit->driver->first_name . ' ' . $unit->driver->last_name,
                        'license_number' => $unit->driver->license_number,
                        'phone' => $unit->driver->phone,
                    ] : null,
                ];

                return response()->json($data);
            }

            return view('units.show', compact('unit'));
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function edit(Unit $unit)
    {
        // Ensure operator can only edit their own units
        $user = auth()->user();
        if (!$user->operator || $unit->operator_id != $user->operator->id) {
            abort(403);
        }

        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        try {
            // Ensure operator can only update their own units
            $user = auth()->user();
            if (!$user->operator || $unit->operator_id != $user->operator->id) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
                abort(403);
            }

            $validated = $request->validate([
                'plate_no' => 'required|string|unique:units,plate_no,' . $unit->id,
                'body_number' => 'nullable|string|max:255',
                'engine_number' => 'nullable|string|max:255',
                'chassis_number' => 'nullable|string|max:255',
                'coding_number' => 'nullable|string|max:255',
                'police_number' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:255',
                'lto_cr_number' => 'nullable|string|max:255',
                'lto_cr_date_issued' => 'nullable|date',
                'lto_or_number' => 'nullable|string|max:255',
                'lto_or_date_issued' => 'nullable|date',
                'unit_cr_number' => 'nullable|string|max:255',
                'unit_or_number' => 'nullable|string|max:255',
                'franchise_case' => 'nullable|string|max:255',
                'mv_file' => 'nullable|string|max:255',
                'mbp_no_prev_year' => 'nullable|string|max:255',
                'mch_no_prev_year' => 'nullable|string|max:255',
                'year_model' => 'nullable|string|max:255',
                'type' => 'nullable|in:bus,jeepney,van,taxi',
                'model' => 'nullable|string|max:255',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'capacity' => 'nullable|integer|min:1',
                'status' => 'required|in:active,maintenance,inactive',
                'business_permit_no' => 'nullable|string|max:255',
                'business_permit_validity' => 'nullable|date',
                'or_number' => 'nullable|string|max:255',
                'or_date_issued' => 'nullable|date',
                'cr_number' => 'nullable|string|max:255',
                'cr_validity' => 'nullable|date',
                'unit_photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'business_permit_photo' => 'nullable|mimes:png,jpg,jpeg|max:10240',
                'or_photo' => 'nullable|mimes:png,jpg,jpeg|max:10240',
                'cr_photo' => 'nullable|mimes:png,jpg,jpeg|max:10240',
            ], [
                'plate_no.required' => 'Plate number is required.',
                'plate_no.unique' => 'This plate number is already registered.',
                'status.required' => 'Status is required.',
                'status.in' => 'Status must be active, maintenance, or inactive.',
                'unit_photo.mimes' => 'Unit photo must be a PNG, JPG, or JPEG image.',
                'unit_photo.max' => 'Unit photo must not exceed 2MB.',
                'business_permit_photo.mimes' => 'Business permit photo must be a PNG, JPG, or JPEG image.',
                'business_permit_photo.max' => 'Business permit photo must not exceed 10MB.',
                'or_photo.mimes' => 'OR photo must be a PNG, JPG, or JPEG image.',
                'or_photo.max' => 'OR photo must not exceed 10MB.',
                'cr_photo.mimes' => 'CR photo must be a PNG, JPG, or JPEG image.',
                'cr_photo.max' => 'CR photo must not exceed 10MB.',
            ]);

            // Handle file uploads
            if ($request->hasFile('unit_photo')) {
                // Delete old photo if exists
                if ($unit->unit_photo) {
                    \Storage::disk('s3')->delete($unit->unit_photo);
                }
                $validated['unit_photo'] = $request->file('unit_photo')->store('units/photos', 's3');
            }

            if ($request->hasFile('business_permit_photo')) {
                // Delete old photo if exists
                if ($unit->business_permit_photo) {
                    \Storage::disk('s3')->delete($unit->business_permit_photo);
                }
                $validated['business_permit_photo'] = $request->file('business_permit_photo')->store('unit_documents/business_permits', 's3');
            }

            if ($request->hasFile('or_photo')) {
                // Delete old photo if exists
                if ($unit->or_photo) {
                    \Storage::disk('s3')->delete($unit->or_photo);
                }
                $validated['or_photo'] = $request->file('or_photo')->store('unit_documents/or_photos', 's3');
            }

            if ($request->hasFile('cr_photo')) {
                // Delete old photo if exists
                if ($unit->cr_photo) {
                    \Storage::disk('s3')->delete($unit->cr_photo);
                }
                $validated['cr_photo'] = $request->file('cr_photo')->store('unit_documents/cr_photos', 's3');
            }

            // Capture original values before update
            $originalValues = $unit->getOriginal();

            $unit->update($validated);

            // Track changes for audit trail
            $changes = [];
            $changedFields = [];

            foreach ($validated as $key => $newValue) {
                // Skip file fields as they are paths
                if (in_array($key, ['unit_photo', 'business_permit_photo', 'or_photo', 'cr_photo'])) {
                    if ($request->hasFile($key)) {
                        $changedFields[] = $key;
                        $changes[$key] = [
                            'old' => $originalValues[$key] ?? 'None',
                            'new' => 'Updated'
                        ];
                    }
                    continue;
                }

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

            // Log unit update with changes
            $description = "Updated transport unit: {$unit->plate_no}";
            if (!empty($changedFields)) {
                $description .= " (Changed: " . implode(', ', $changedFields) . ")";
            }

            AuditTrail::log(
                'updated',
                $description,
                'Unit',
                $unit->id,
                $changes
            );

            // Return JSON for API calls
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transport unit updated successfully.',
                    'unit' => $unit
                ]);
            }

            return redirect()->route('units.index')
                ->with('success', 'Transport unit updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function destroy(Unit $unit)
    {
        try {
            // Ensure operator can only delete their own units
            $user = auth()->user();
            if (!$user->operator || $unit->operator_id != $user->operator->id) {
                if (request()->expectsJson() || request()->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
                abort(403);
            }

            $plateNo = $unit->plate_no;
            $unit->delete();

            // Log unit deletion
            AuditTrail::log(
                'deleted',
                "Deleted transport unit: {$plateNo}",
                'Unit',
                $unit->id
            );

            // Return JSON for API calls
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transport unit deleted successfully.'
                ]);
            }

            return redirect()->route('units.index')
                ->with('success', 'Transport unit deleted successfully.');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Get available drivers for assignment to a unit
     * Returns drivers from the same operator that are NOT assigned to another unit
     * OR are currently assigned to this unit (for reassignment display)
     */
    public function getAvailableDrivers(Unit $unit)
    {
        try {
            $user = auth()->user();

            // Ensure the unit belongs to the authenticated operator
            if (!$user->operator || $unit->operator_id != $user->operator->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $operatorId = $user->operator->id;

            // Get all approved drivers from the same operator
            // that are either not assigned to any unit OR assigned to this unit
            $drivers = Driver::where('operator_id', $operatorId)
                ->where('approval_status', 'approved')
                ->where(function ($query) use ($unit) {
                    $query->whereDoesntHave('unit')
                          ->orWhereHas('unit', function ($q) use ($unit) {
                              $q->where('id', $unit->id);
                          });
                })
                ->get()
                ->map(function ($driver) {
                    return [
                        'id' => $driver->id,
                        'full_name' => $driver->full_name,
                        'first_name' => $driver->first_name,
                        'last_name' => $driver->last_name,
                        'license_number' => $driver->license_number,
                        'status' => $driver->status,
                    ];
                });

            // Get current driver info if assigned
            $currentDriver = null;
            if ($unit->driver_id) {
                $driver = Driver::find($unit->driver_id);
                if ($driver) {
                    $currentDriver = [
                        'id' => $driver->id,
                        'full_name' => $driver->full_name,
                        'license_number' => $driver->license_number,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'drivers' => $drivers,
                'current_driver' => $currentDriver
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign a driver to a unit
     */
    public function assignDriver(Request $request, Unit $unit)
    {
        try {
            $user = auth()->user();

            // Ensure the unit belongs to the authenticated operator
            if (!$user->operator || $unit->operator_id != $user->operator->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validated = $request->validate([
                'driver_id' => 'required|exists:drivers,id'
            ]);

            $driver = Driver::find($validated['driver_id']);

            // Validate that the driver belongs to the same operator
            if ($driver->operator_id != $user->operator->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This driver does not belong to your account.'
                ], 403);
            }

            // Check if the driver is approved
            if ($driver->approval_status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved drivers can be assigned to units.'
                ], 400);
            }

            // Check if the driver is already assigned to another unit
            $existingUnit = Unit::where('driver_id', $driver->id)
                ->where('id', '!=', $unit->id)
                ->first();

            if ($existingUnit) {
                return response()->json([
                    'success' => false,
                    'message' => "This driver is already assigned to unit {$existingUnit->plate_no}. Please unassign them first."
                ], 400);
            }

            // Store old driver info for audit log
            $oldDriverName = $unit->driver ? $unit->driver->full_name : 'None';

            // Assign the driver to the unit
            $unit->driver_id = $driver->id;
            $unit->save();

            // Log the assignment
            AuditTrail::log(
                'updated',
                "Assigned driver {$driver->full_name} to unit {$unit->plate_no} (previously: {$oldDriverName})",
                'Unit',
                $unit->id
            );

            return response()->json([
                'success' => true,
                'message' => "Driver {$driver->full_name} has been assigned to unit {$unit->plate_no}.",
                'unit' => $unit->load('driver')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}