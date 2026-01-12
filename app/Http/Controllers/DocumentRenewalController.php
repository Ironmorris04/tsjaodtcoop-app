<?php

namespace App\Http\Controllers;

use App\Models\DocumentRenewal;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\AuditTrail;
use App\Helpers\TimeHelper;
use App\Notifications\DocumentRenewalApproved;
use App\Notifications\DocumentRenewalRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DocumentRenewalController extends Controller
{
    /**
     * Get renewal details for review modal
     */
    public function show($id)
    {
        try {
            $renewal = DocumentRenewal::with(['operator', 'documentable', 'reviewer'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'renewal' => [
                    'id' => $renewal->id,
                    'operator_name' => $renewal->operator ? $renewal->operator->full_name : 'Unknown',
                    'operator_id' => $renewal->operator_id,
                    'entity_name' => $renewal->entity_name,
                    'entity_identifier' => $renewal->entity_identifier,
                    'document_type' => $renewal->formatted_type,
                    'document_type_raw' => $renewal->document_type,
                    'original_expiry' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('M d, Y') : 'N/A',
                    'original_expiry_raw' => $renewal->original_expiry_date,
                    'new_expiry' => $renewal->new_expiry_date->format('M d, Y'),
                    'new_expiry_raw' => $renewal->new_expiry_date,
                    'original_document_number' => $renewal->original_document_number,
                    'new_document_number' => $renewal->new_document_number,
                    'document_photo_url' => $renewal->document_photo_url,
                    'status' => $renewal->status,
                    'status_badge_class' => $renewal->status_badge_class,
                    'submitted_at' => $renewal->created_at->format('M d, Y h:i A'),
                    'days_ago' => TimeHelper::timeAgo($renewal->created_at),
                    'rejection_reason' => $renewal->rejection_reason,
                    'reviewed_by' => $renewal->reviewer ? $renewal->reviewer->name : null,
                    'reviewed_at' => $renewal->reviewed_at ? $renewal->reviewed_at->format('M d, Y h:i A') : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch renewal details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a document renewal request
     */
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $renewal = DocumentRenewal::with(['operator.user', 'documentable'])
                ->where('id', $id)
                ->where('status', 'pending')
                ->firstOrFail();

            // Update the actual document based on type
            $this->applyRenewal($renewal);

            // Update renewal record
            $renewal->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Log approval
            AuditTrail::log(
                'approved',
                "Approved {$renewal->formatted_type} renewal for: {$renewal->entity_name} (New expiry: {$renewal->new_expiry_date->format('M d, Y')})",
                'DocumentRenewal',
                $renewal->id,
                [
                    'operator_name' => $renewal->operator->full_name ?? 'Unknown',
                    'entity_name' => $renewal->entity_name,
                    'document_type' => $renewal->formatted_type,
                    'original_expiry' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('Y-m-d') : 'N/A',
                    'new_expiry' => $renewal->new_expiry_date->format('Y-m-d'),
                ]
            );

            // Send email notification to operator
            if ($renewal->operator && $renewal->operator->user && $renewal->operator->user->email) {
                $renewal->operator->user->notify(new DocumentRenewalApproved($renewal));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Document renewal approved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Renewal request not found or already processed.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve renewal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a document renewal request
     */
    public function reject(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'rejection_reason' => 'required|string|max:500',
            ], [
                'rejection_reason.required' => 'Please provide a reason for rejection.',
                'rejection_reason.max' => 'Rejection reason must not exceed 500 characters.',
            ]);

            DB::beginTransaction();

            $renewal = DocumentRenewal::with(['operator.user', 'documentable'])
                ->where('id', $id)
                ->where('status', 'pending')
                ->firstOrFail();

            // Update renewal record
            $renewal->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Log rejection
            AuditTrail::log(
                'rejected',
                "Rejected {$renewal->formatted_type} renewal for: {$renewal->entity_name} (Reason: {$validated['rejection_reason']})",
                'DocumentRenewal',
                $renewal->id,
                [
                    'operator_name' => $renewal->operator->full_name ?? 'Unknown',
                    'entity_name' => $renewal->entity_name,
                    'document_type' => $renewal->formatted_type,
                    'requested_new_expiry' => $renewal->new_expiry_date->format('Y-m-d'),
                    'rejection_reason' => $validated['rejection_reason'],
                ]
            );

            if ($renewal->document_photo) {
                \Storage::disk('s3')->delete($renewal->document_photo);
            }

            // Send email notification to operator
            if ($renewal->operator && $renewal->operator->user && $renewal->operator->user->email) {
                $renewal->operator->user->notify(new DocumentRenewalRejected($renewal));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Document renewal rejected successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Renewal request not found or already processed.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject renewal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply the renewal to the actual document record
     */
    private function applyRenewal(DocumentRenewal $renewal)
    {
        switch ($renewal->document_type) {
            case 'driver_license':
                $driver = Driver::findOrFail($renewal->documentable_id);

                $updateData = [
                    'license_expiry' => $renewal->new_expiry_date,
                ];

                $changes = [
                    'license_expiry' => [
                        'old' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('Y-m-d') : 'None',
                        'new' => $renewal->new_expiry_date->format('Y-m-d'),
                    ]
                ];

                // If there's a new license photo, move it from pending to active and update driver
                if ($renewal->document_photo) {
                    // Delete old license photo if exists
                    if ($driver->license_photo) {
                        \Storage::disk('s3')->delete($driver->license_photo);
                    }

                    // Move from pending folder to active folder
                    $oldPath = $renewal->document_photo;
                    $newPath = str_replace('drivers/licenses/pending', 'drivers/licenses', $oldPath);

                    if (\Storage::disk('s3')->exists($oldPath)) {
                        \Storage::disk('s3')->move($oldPath, $newPath);
                        $updateData['license_photo'] = $newPath;

                        $changes['license_photo'] = [
                            'old' => $driver->license_photo ?? 'None',
                            'new' => 'Updated',
                        ];
                    }
                }

                $driver->update($updateData);

                // Log driver license update
                AuditTrail::log(
                    'updated',
                    "Updated driver license via approved renewal: {$driver->first_name} {$driver->last_name} (New expiry: {$renewal->new_expiry_date->format('M d, Y')})",
                    'Driver',
                    $driver->id,
                    $changes
                );
                break;

            case 'business_permit':
                $unit = Unit::findOrFail($renewal->documentable_id);
                $unit->update([
                    'business_permit_validity' => $renewal->new_expiry_date,
                ]);

                AuditTrail::log(
                    'updated',
                    "Updated business permit validity via approved renewal: Unit {$unit->plate_no} (New expiry: {$renewal->new_expiry_date->format('M d, Y')})",
                    'Unit',
                    $unit->id,
                    [
                        'business_permit_validity' => [
                            'old' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('Y-m-d') : 'None',
                            'new' => $renewal->new_expiry_date->format('Y-m-d'),
                        ]
                    ]
                );
                break;

            case 'unit_or':
                $unit = Unit::findOrFail($renewal->documentable_id);
                $unit->update([
                    'or_date_issued' => $renewal->new_expiry_date,
                ]);

                AuditTrail::log(
                    'updated',
                    "Updated unit OR date via approved renewal: Unit {$unit->plate_no} (New date: {$renewal->new_expiry_date->format('M d, Y')})",
                    'Unit',
                    $unit->id,
                    [
                        'or_date_issued' => [
                            'old' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('Y-m-d') : 'None',
                            'new' => $renewal->new_expiry_date->format('Y-m-d'),
                        ]
                    ]
                );
                break;

            case 'unit_cr':
                $unit = Unit::findOrFail($renewal->documentable_id);
                $unit->update([
                    'cr_validity' => $renewal->new_expiry_date,
                ]);

                AuditTrail::log(
                    'updated',
                    "Updated unit CR validity via approved renewal: Unit {$unit->plate_no} (New expiry: {$renewal->new_expiry_date->format('M d, Y')})",
                    'Unit',
                    $unit->id,
                    [
                        'cr_validity' => [
                            'old' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('Y-m-d') : 'None',
                            'new' => $renewal->new_expiry_date->format('Y-m-d'),
                        ]
                    ]
                );
                break;

            case 'lto_or':
                $unit = Unit::findOrFail($renewal->documentable_id);
                $unit->update([
                    'lto_or_date_issued' => $renewal->new_expiry_date,
                ]);

                AuditTrail::log(
                    'updated',
                    "Updated LTO OR date via approved renewal: Unit {$unit->plate_no} (New date: {$renewal->new_expiry_date->format('M d, Y')})",
                    'Unit',
                    $unit->id,
                    [
                        'lto_or_date_issued' => [
                            'old' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('Y-m-d') : 'None',
                            'new' => $renewal->new_expiry_date->format('Y-m-d'),
                        ]
                    ]
                );
                break;

            case 'lto_cr':
                $unit = Unit::findOrFail($renewal->documentable_id);
                $unit->update([
                    'lto_cr_date_issued' => $renewal->new_expiry_date,
                ]);

                AuditTrail::log(
                    'updated',
                    "Updated LTO CR date via approved renewal: Unit {$unit->plate_no} (New date: {$renewal->new_expiry_date->format('M d, Y')})",
                    'Unit',
                    $unit->id,
                    [
                        'lto_cr_date_issued' => [
                            'old' => $renewal->original_expiry_date ? $renewal->original_expiry_date->format('Y-m-d') : 'None',
                            'new' => $renewal->new_expiry_date->format('Y-m-d'),
                        ]
                    ]
                );
                break;

            default:
                throw new \Exception("Unsupported document type: {$renewal->document_type}");
        }
    }
}
