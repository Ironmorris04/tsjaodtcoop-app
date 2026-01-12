<?php
// app/Http/Controllers/DriverController.php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\AuditTrail;
use App\Models\DocumentRenewal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller
{
    public function index()
    {
        $operator = auth()->user()->operator;

        // Check if user has an operator profile
        if (!$operator) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to have an operator profile to access this page.');
        }

        // Get approved drivers with unit relationship
        $approvedDrivers = $operator->drivers()
            ->with('unit')
            ->where('approval_status', 'approved')
            ->latest()
            ->get();

        // Get pending drivers
        $pendingDrivers = $operator->drivers()
            ->where('approval_status', 'pending')
            ->latest()
            ->get();

        // Keep original pagination for backward compatibility
        $drivers = $operator->drivers()->with('unit')->latest()->paginate(10);

        $data = [
            'totalDrivers' => $operator->drivers()->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })->count(),
            'activeDrivers' => $operator->drivers()->where('status', 'active')
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })->count(),
            'totalUnits' => $operator->units()->where(function($query) {
                $query->where('approval_status', 'approved')
                      ->orWhereNull('approval_status');
            })->count(),
            'activeUnits' => $operator->units()->where('status', 'active')
                ->where(function($query) {
                    $query->where('approval_status', 'approved')
                          ->orWhereNull('approval_status');
                })->count()
        ];

        return view('drivers.index', compact('drivers', 'data', 'approvedDrivers', 'pendingDrivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        try {
            $operator = auth()->user()->operator;

            Log::info('Operator:', ['operator_id' => optional($operator)->id]);

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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'sex' => 'required|in:Male,Female',
                'date_of_birth' => 'nullable|date',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'required|string',
                'photo' => 'nullable|mimes:png,jpg,jpeg|max:2048', // PNG, JPG, JPEG only
                'biodata_photo' => 'nullable|mimes:png,jpg,jpeg|max:2048', // PNG, JPG, JPEG only
                'license_no' => 'required|string|unique:drivers,license_number',
                'license_type' => 'nullable|string',
                'license_expiry' => 'required|date',
                'license_photo' => 'nullable|mimes:png,jpg,jpeg|max:2048', // PNG, JPG, JPEG only
                'license_restrictions' => 'nullable|string|max:255',
                'dl_codes' => 'nullable|string|max:255',
                'status' => 'nullable|in:active,inactive',
                'hire_date' => 'nullable|date',
                'emergency_contact' => 'nullable|string|max:20',
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'birthdate.required' => 'Birthdate is required.',
                'birthdate.date' => 'Please enter a valid birthdate.',
                'sex.required' => 'Sex is required.',
                'sex.in' => 'Sex must be either Male or Female.',
                'phone.required' => 'Phone number is required.',
                'phone.max' => 'Phone number must not exceed 20 characters.',
                'email.email' => 'Please enter a valid email address.',
                'address.required' => 'Address is required.',
                'photo.mimes' => 'Driver photo must be a PNG, JPG, or JPEG image.',
                'photo.max' => 'Driver photo must not exceed 2MB.',
                'biodata_photo.mimes' => 'Biodata photo must be a PNG, JPG, or JPEG image.',
                'biodata_photo.max' => 'Biodata photo must not exceed 2MB.',
                'license_no.required' => 'License number is required.',
                'license_no.unique' => 'This license number is already registered.',
                'license_expiry.required' => 'License expiry date is required.',
                'license_expiry.date' => 'Please enter a valid license expiry date.',
                'license_photo.mimes' => 'License photo must be a PNG, JPG, or JPEG image.',
                'license_photo.max' => 'License photo must not exceed 2MB.',
                'status.in' => 'Status must be either active or inactive.',
            ]);

            // Handle file uploads
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('drivers/photos', 's3');
            }

            if ($request->hasFile('biodata_photo')) {
                $validated['biodata_photo'] = $request->file('biodata_photo')->store('drivers/biodata', 's3');
            }

            if ($request->hasFile('license_photo')) {
                $validated['license_photo'] = $request->file('license_photo')->store('drivers/licenses', 's3');
            }

            // Map license_no to license_number
            $validated['license_number'] = $validated['license_no'];
            unset($validated['license_no']);

            // Set approval status
            $validated['approval_status'] = 'pending';

            // Create the driver with all file paths already in $validated
            $driver = $operator->drivers()->create($validated);

            // Log driver creation
            AuditTrail::log(
                'created',
                "Added new driver: {$driver->first_name} {$driver->last_name} (License: {$driver->license_number})",
                'Driver',
                $driver->id
            );

            // Return JSON for API calls
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Driver added successfully. Pending admin approval.',
                    'driver' => $driver
                ]);
            }

            return redirect()->route('drivers.index')
                ->with('success', 'Driver added successfully. Pending admin approval.');
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
            Log::error('Driver store error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ], 500);
            }
            throw $e;
        }
    }

    public function show(Driver $driver)
    {
        // Ensure operator can only view their own drivers
        $user = auth()->user();
        if (!$user->operator || $driver->operator_id != $user->operator->id) {
            abort(403);
        }

        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        // Ensure operator can only edit their own drivers
        $user = auth()->user();
        if (!$user->operator || $driver->operator_id != $user->operator->id) {
            abort(403);
        }

        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        try {
            // Ensure operator can only update their own drivers
            $user = auth()->user();
            if (!$user->operator || $driver->operator_id != $user->operator->id) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
                abort(403);
            }

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
                'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
                'license_type' => 'nullable|string',
                'license_expiry' => 'nullable|date',
                'license_restrictions' => 'nullable|string|max:255',
                'dl_codes' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
                'hire_date' => 'nullable|date',
                'emergency_contact' => 'nullable|string|max:20',
                'photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'biodata_photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'license_photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'phone.required' => 'Phone number is required.',
                'phone.max' => 'Phone number must not exceed 20 characters.',
                'email.email' => 'Please enter a valid email address.',
                'license_number.required' => 'License number is required.',
                'license_number.unique' => 'This license number is already registered.',
                'status.required' => 'Status is required.',
                'status.in' => 'Status must be either active or inactive.',
                'photo.mimes' => 'Driver photo must be a PNG, JPG, or JPEG image.',
                'photo.max' => 'Driver photo must not exceed 2MB.',
                'biodata_photo.mimes' => 'Biodata photo must be a PNG, JPG, or JPEG image.',
                'biodata_photo.max' => 'Biodata photo must not exceed 2MB.',
                'license_photo.mimes' => 'License photo must be a PNG, JPG, or JPEG image.',
                'license_photo.max' => 'License photo must not exceed 2MB.',
            ]);

            // Handle file uploads
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($driver->photo) {
                    \Storage::disk('s3')->delete($driver->photo);
                }
                $validated['photo'] = $request->file('photo')->store('drivers/photos', 's3');
            }

            if ($request->hasFile('biodata_photo')) {
                // Delete old biodata photo if exists
                if ($driver->biodata_photo) {
                    \Storage::disk('s3')->delete($driver->biodata_photo);
                }
                $validated['biodata_photo'] = $request->file('biodata_photo')->store('drivers/biodata', 's3');
            }


            // Capture original DB values BEFORE update
            $originalValues = $driver->getOriginal();

            // Normalize DB expiry
            $dbExpiry = $driver->license_expiry
                ? Carbon::parse($driver->license_expiry)->toDateString()
                : null;

            // Normalize request expiry
            $requestExpiry = isset($validated['license_expiry']) && $validated['license_expiry']
                ? Carbon::parse($validated['license_expiry'])->toDateString()
                : null;

            $isExpiryChanged = $requestExpiry !== null && $requestExpiry !== $dbExpiry;
            $isPhotoBeingUpdated = $request->hasFile('license_photo');

            $licenseExpiryChanged = false;
            $newLicenseExpiry = null;
            $newLicensePhoto = null;

            // STEP 1: Trigger renewal ONLY if expiry is actually different
            if ($isExpiryChanged) {
                $licenseExpiryChanged = true;
                $newLicenseExpiry = $requestExpiry;

                if (!$isPhotoBeingUpdated) {
                    return response()->json([
                        'success' => false,
                        'message' => 'New license photo is required when updating the license expiry date.',
                        'errors' => [
                            'license_photo' => ['New license photo is required when updating the license expiry date.']
                        ]
                    ], 422);
                }

                // Store pending renewal data
                $newLicensePhoto = $request->file('license_photo')
                    ->store('drivers/licenses/pending', 's3');

                // Prevent direct update
                unset($validated['license_expiry'], $validated['license_photo']);
            }

            // STEP 2: Normal photo update (NO expiry change)
            if (!$licenseExpiryChanged && $isPhotoBeingUpdated) {
                if ($driver->license_photo) {
                    \Storage::disk('s3')->delete($driver->license_photo);
                }

                $validated['license_photo'] = $request->file('license_photo')
                    ->store('drivers/licenses', 's3');
            }

            // STEP 3: Update driver ONCE
            $driver->update($validated);

            // STEP 4: Create renewal request
            if ($licenseExpiryChanged) {
                $operator = auth()->user()->operator;

                DocumentRenewal::create([
                    'operator_id' => $operator->id,
                    'document_type' => 'driver_license',
                    'documentable_type' => 'App\Models\Driver',
                    'documentable_id' => $driver->id,
                    'original_expiry_date' => $originalValues['license_expiry'],
                    'new_expiry_date' => $newLicenseExpiry,
                    'document_photo' => $newLicensePhoto,
                    'status' => 'pending',
                ]);

                AuditTrail::log(
                    'submitted',
                    "Submitted driver license renewal request for: {$driver->first_name} {$driver->last_name} (New expiry: {$newLicenseExpiry})",
                    'DocumentRenewal',
                    null,
                    [
                        'driver_id' => $driver->id,
                        'driver_name' => $driver->full_name,
                        'original_expiry' => $originalValues['license_expiry'] ?? 'None',
                        'new_expiry' => $newLicenseExpiry,
                    ]
                );
            }

            $changes = [];
            $changedFields = [];

            foreach ($validated as $key => $newValue) {
                // Skip file fields as they are paths
                if (in_array($key, ['photo', 'biodata_photo', 'license_photo'])) {
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

            // Log driver update with changes
            if (!empty($changedFields)) {
                $description = "Updated driver information: {$driver->first_name} {$driver->last_name}";
                $description .= " (Changed: " . implode(', ', $changedFields) . ")";

                AuditTrail::log(
                    'updated',
                    $description,
                    'Driver',
                    $driver->id,
                    $changes
                );
            }

            // Prepare success message
            $successMessage = 'Driver updated successfully.';
            if ($licenseExpiryChanged) {
                $successMessage = 'Driver updated successfully. License expiry update has been submitted for admin approval.';
            }

            // Return JSON for API calls
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'driver' => $driver,
                    'renewal_submitted' => $licenseExpiryChanged
                ]);
            }

            return redirect()->route('drivers.index')
                ->with('success', $successMessage);
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

    public function destroy(Driver $driver)
    {
        try {
            // Ensure operator can only delete their own drivers
            $user = auth()->user();
            if (!$user->operator || $driver->operator_id != $user->operator->id) {
                if (request()->expectsJson() || request()->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
                abort(403);
            }

            $driverName = "{$driver->first_name} {$driver->last_name}";
            $driver->delete();

            // Log driver deletion
            AuditTrail::log(
                'deleted',
                "Deleted driver: {$driverName}",
                'Driver',
                $driver->id
            );

            // Return JSON for API calls
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Driver deleted successfully.'
                ]);
            }

            return redirect()->route('drivers.index')
                ->with('success', 'Driver deleted successfully.');
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
}