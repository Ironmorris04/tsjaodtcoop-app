<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AuditTrail;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegistrationApprovalController extends Controller
{
    /**
     * Display a list of pending registrations.
     */
    public function index()
    {
        $pendingRegistrations = Operator::with(['user'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'operators_page');

        $pendingDrivers = Driver::with(['operator.user'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'drivers_page');

        $pendingUnits = Unit::with(['operator.user'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'units_page');

        return view('admin.registrations.index', compact('pendingRegistrations', 'pendingDrivers', 'pendingUnits'));
    }

    /**
     * Show the registration details for review.
     */
    public function show($id)
    {
        $operator = Operator::with(['user', 'operatorDetail'])->findOrFail($id);

        // Get operator details using Eloquent model (to access accessors like formatted_id_type)
        $details = \App\Models\OperatorDetail::where('operator_id', $id)->first();

        $dependents = DB::table('operator_dependents')
            ->where('operator_id', $id)
            ->get();

        return view('admin.registrations.review', compact('operator', 'details', 'dependents'));
    }

    /**
     * Approve a registration.
     */
   public function approve(Request $request, $id)
    {
        $operator = Operator::findOrFail($id);

        // Check if membership form is uploaded
        if (!$operator->membership_form_path) {
            return redirect()->back()
                ->withErrors(['membership_form' => 'Please upload the operator\'s membership form before approving the application.'])
                ->withInput();
        }

        /**
         * ✅ Generate PNG preview if the uploaded membership form is a PDF
         */
        $s3PdfPath = $operator->membership_form_path;
        $extension = strtolower(pathinfo($s3PdfPath, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {

            // S3 directories (logical folders)
            $previewDir = 'membership_forms/previews';

            // Filenames
            $pdfFilename = pathinfo($s3PdfPath, PATHINFO_FILENAME);
            $previewFilename = $pdfFilename . '.png';
            $s3PreviewPath = $previewDir . '/' . $previewFilename;

            // TEMP paths (required for binary execution, not persistence)
            $tempPdf = '/tmp/' . basename($s3PdfPath);
            $tempPng = '/tmp/' . $previewFilename;

            // Pull PDF from S3
            file_put_contents($tempPdf, Storage::disk('s3')->get($s3PdfPath));

            $gs = env('GHOSTSCRIPT_PATH', 'gs');

            // Generate first-page PNG
            $cmd = sprintf(
                '%s -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -r150 -dFirstPage=1 -dLastPage=1 -sOutputFile="%s" "%s"',
                $gs,
                $tempPng,
                $tempPdf
            );

            exec($cmd, $output, $code);

            if ($code === 0 && file_exists($tempPng)) {

                // Push preview to S3 (membership_forms/previews/)
                Storage::disk('s3')->put(
                    $s3PreviewPath,
                    file_get_contents($tempPng),
                    'public'
                );

                // Save S3 preview path
                $operator->membership_form_preview_path = $s3PreviewPath;
                $operator->save();
            }

            // Cleanup (always)
            @unlink($tempPdf);
            @unlink($tempPng);
        }

        /**
         * ✅ Continue with the normal approval process
         */
        $user = $operator->user;

        // Generate unique user ID
        $userId = User::generateUserId('operator');
        $user->user_id = $userId;
        $user->save();

        // Generate setup token
        $setupToken = $user->generateSetupToken();

        // Update operator approval status
        $operator->update([
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Log the approval activity
        Activity::log(
            'operator_approved',
            'Operator ' . ($operator->full_name ?? $operator->contact_person) . ' (ID: ' . $userId . ') was approved',
            auth()->id(),
            $operator,
            ['user_id' => $userId, 'operator_name' => $operator->full_name ?? $operator->contact_person]
        );

        // Send approval email
        $setupUrl = route('password.setup', ['token' => $setupToken]);

        try {
            Mail::send('emails.registration-approved', [
                'operatorName' => $operator->contact_person,
                'userId' => $userId,
                'setupUrl' => $setupUrl,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Registration Approved - TSJAODTCooperative System');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        return redirect()->route('registrations.index')
            ->with('success', "Registration approved! User ID: {$userId}. An email has been sent to the operator with password setup instructions.");
    }

    /**
     * Reject a registration.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $operator = Operator::findOrFail($id);
        $user = $operator->user;

        $operator->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        // Log the rejection activity
        Activity::log(
            'operator_rejected',
            'Operator ' . ($operator->full_name ?? $operator->contact_person) . ' registration was rejected',
            auth()->id(),
            $operator,
            ['operator_name' => $operator->full_name ?? $operator->contact_person, 'rejection_reason' => $request->rejection_reason]
        );

        // Send rejection email to operator
        try {
            Mail::send('emails.registration-rejected', [
                'operatorName' => $operator->contact_person ?? $operator->full_name,
                'rejectionReason' => $request->rejection_reason,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Registration Update - TSJAODTCooperative System');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return redirect()->route('registrations.index')
            ->with('success', 'Registration rejected. The operator has been notified via email.');
    }

    /**
     * Upload membership form for an operator
     */
    public function uploadMembershipForm(Request $request, $id)
    {
        $request->validate([
            'membership_form' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $operator = Operator::findOrFail($id);

        // Delete old membership form if exists
        if ($operator->membership_form_path) {
            Storage::disk('s3')->delete($operator->membership_form_path);
        }

        // Store the new file
        $path = $request->file('membership_form')->store('membership_forms', 's3');

        // Update operator
        $operator->update([
            'membership_form_path' => $path
        ]);

        return back()->with('success', 'Membership form uploaded successfully!');
    }
    
   /**
     * View membership form for an operator (S3-ready)
     */
    public function viewMembershipForm(Operator $operator)
    {
        // Abort if no membership form path
        abort_if(!$operator->membership_form_path, 404);

        $path = $operator->membership_form_path;

        // Abort if file does not exist on S3
        abort_if(!Storage::disk('s3')->exists($path), 404);

        // Stream the file inline from S3
        return Storage::disk('s3')->response($path, null, [
            'Content-Disposition' => 'inline',
        ]);
    }


   /**
     * Download membership form for an operator (S3-ready)
     */
    public function downloadMembershipForm(Operator $operator)
    {
        // Abort if no membership form path
        abort_if(!$operator->membership_form_path, 404);

        $path = $operator->membership_form_path;

        // Abort if file does not exist in S3
        abort_if(!Storage::disk('s3')->exists($path), 404);

        // Download the file from S3
        return Storage::disk('s3')->download(
            $path,
            'MembershipForm.' . pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Show the driver details for review.
     */
    public function showDriver($id)
    {
        $driver = Driver::with(['operator.user'])->findOrFail($id);
        return view('admin.registrations.review-driver', compact('driver'));
    }

    /**
     * Approve a driver.
     */
    public function approveDriver(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        // Generate next driver ID
        $lastDriver = Driver::whereNotNull('driver_id')
            ->orderBy('driver_id', 'desc')
            ->first();

        if ($lastDriver && $lastDriver->driver_id) {
            // Extract number from DRV-XXXX format
            $lastNumber = (int) substr($lastDriver->driver_id, 4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $driverId = 'DRV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Update driver
        $driver->update([
            'driver_id' => $driverId,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Log the driver approval activity
        Activity::log(
            'driver_approved',
            'Driver ' . $driver->full_name . ' (ID: ' . $driverId . ') was approved',
            auth()->id(),
            $driver,
            ['driver_id' => $driverId, 'driver_name' => $driver->full_name]
        );

        // Log to audit trail
        AuditTrail::log(
            'approved',
            "Approved driver application: {$driver->full_name} - Assigned Driver ID: {$driverId}",
            'Driver',
            $driver->id,
            [
                'driver_id' => $driverId,
                'driver_name' => $driver->full_name,
                'operator_id' => $driver->operator_id,
                'operator_name' => $driver->operator->full_name ?? null,
                'approval_status' => 'approved',
            ]
        );

        // Send approval email to operator
        try {
            $operator = $driver->operator;
            $user = $operator->user;

            Mail::send('emails.driver-approved', [
                'operatorName' => $operator->contact_person,
                'driverName' => $driver->full_name,
                'driverId' => $driverId,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Driver Approved - TSJAODTCooperative System');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send driver approval email: ' . $e->getMessage());
        }

        return redirect()->route('registrations.index')
            ->with('success', "Driver approved! Driver ID: {$driverId}");
    }

    /**
     * Reject a driver.
     */
    public function rejectDriver(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $driver = Driver::findOrFail($id);

        $driver->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        // Log the driver rejection activity
        Activity::log(
            'driver_rejected',
            'Driver ' . $driver->full_name . ' application was rejected',
            auth()->id(),
            $driver,
            ['driver_name' => $driver->full_name, 'rejection_reason' => $request->rejection_reason]
        );

        // Log to audit trail
        AuditTrail::log(
            'rejected',
            "Rejected driver application: {$driver->full_name} - Reason: {$request->rejection_reason}",
            'Driver',
            $driver->id,
            [
                'driver_name' => $driver->full_name,
                'operator_id' => $driver->operator_id,
                'operator_name' => $driver->operator->full_name ?? null,
                'rejection_reason' => $request->rejection_reason,
                'approval_status' => 'rejected',
            ]
        );

        // Send rejection email to operator
        try {
            $operator = $driver->operator;
            $user = $operator->user;

            Mail::send('emails.driver-rejected', [
                'operatorName' => $operator->contact_person ?? $operator->full_name,
                'driverName' => $driver->full_name,
                'rejectionReason' => $request->rejection_reason,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Driver Application Update - TSJAODTCooperative System');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send driver rejection email: ' . $e->getMessage());
        }

        return redirect()->route('registrations.index')
            ->with('success', 'Driver application rejected. The operator has been notified via email.');
    }

    /**
     * Show the unit details for review.
     */
    public function showUnit($id)
    {
        $unit = Unit::with(['operator.user'])->findOrFail($id);
        return view('admin.registrations.review-unit', compact('unit'));
    }

    /**
     * Approve a unit.
     */
    public function approveUnit(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        // Generate next unit ID
        $lastUnit = Unit::whereNotNull('unit_id')
            ->orderBy('unit_id', 'desc')
            ->first();

        if ($lastUnit && $lastUnit->unit_id) {
            // Extract number from UNT-XXXX format
            $lastNumber = (int) substr($lastUnit->unit_id, 4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $unitId = 'UNT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Update unit
        $unit->update([
            'unit_id' => $unitId,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Log the unit approval activity
        Activity::log(
            'unit_approved',
            'Unit ' . ($unit->plate_no ?? 'N/A') . ' (ID: ' . $unitId . ') was approved',
            auth()->id(),
            $unit,
            ['unit_id' => $unitId, 'plate_no' => $unit->plate_no]
        );

        // Log to audit trail
        AuditTrail::log(
            'approved',
            "Approved unit application: Plate No. {$unit->plate_no} - Assigned Unit ID: {$unitId}",
            'Unit',
            $unit->id,
            [
                'unit_id' => $unitId,
                'plate_no' => $unit->plate_no,
                'operator_id' => $unit->operator_id,
                'operator_name' => $unit->operator->full_name ?? null,
                'approval_status' => 'approved',
            ]
        );

        // Send approval email to operator
        try {
            $operator = $unit->operator;
            $user = $operator->user;

            Mail::send('emails.unit-approved', [
                'operatorName' => $operator->contact_person,
                'plateNo' => $unit->plate_no,
                'unitId' => $unitId,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Unit Approved - TSJAODTCooperative System');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send unit approval email: ' . $e->getMessage());
        }

        return redirect()->route('registrations.index')
            ->with('success', "Unit approved! Unit ID: {$unitId}");
    }

    /**
     * Reject a unit.
     */
    public function rejectUnit(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $unit = Unit::findOrFail($id);

        $unit->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        // Log the unit rejection activity
        Activity::log(
            'unit_rejected',
            'Unit ' . ($unit->plate_no ?? 'N/A') . ' application was rejected',
            auth()->id(),
            $unit,
            ['plate_no' => $unit->plate_no, 'rejection_reason' => $request->rejection_reason]
        );

        // Log to audit trail
        AuditTrail::log(
            'rejected',
            "Rejected unit application: Plate No. {$unit->plate_no} - Reason: {$request->rejection_reason}",
            'Unit',
            $unit->id,
            [
                'plate_no' => $unit->plate_no,
                'operator_id' => $unit->operator_id,
                'operator_name' => $unit->operator->full_name ?? null,
                'rejection_reason' => $request->rejection_reason,
                'approval_status' => 'rejected',
            ]
        );

        // Send rejection email to operator
        try {
            $operator = $unit->operator;
            $user = $operator->user;

            Mail::send('emails.unit-rejected', [
                'operatorName' => $operator->contact_person ?? $operator->full_name,
                'plateNo' => $unit->plate_no,
                'rejectionReason' => $request->rejection_reason,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Unit Application Update - TSJAODTCooperative System');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send unit rejection email: ' . $e->getMessage());
        }

        return redirect()->route('registrations.index')
            ->with('success', 'Unit application rejected. The operator has been notified via email.');
    }
}
