<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class OperatorProfileController extends Controller
{
    /**
     * Get the authenticated operator's profile data
     */
    public function getProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user->isOperator()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the operator record with all relationships
        // Note: user_id in operators table is the database ID, not the user_id string
        $operator = Operator::with([
            'operatorDetail',
            'operatorIds',
            'user'
        ])->where('user_id', $user->id)->first();

        if (!$operator) {
            return response()->json(['error' => 'Operator profile not found'], 404);
        }

        $detail = $operator->operatorDetail;
        $operatorIds = $operator->operatorIds;

        // Build the profile data
        $profileData = [
            'operator' => [
                'business_name' => $operator->business_name,
                'contact_person' => $operator->contact_person,
                'phone' => $operator->phone,
                'email' => $operator->email,
                'address' => $operator->address,
                'status' => $operator->status,
            ],
            'detail' => $detail ? [
                'full_name' => $detail->full_name,
                'first_name' => $detail->first_name,
                'middle_name' => $detail->middle_name,
                'last_name' => $detail->last_name,
                'age' => $detail->age,
                'birthdate' => $detail->birthdate ? $detail->birthdate->format('F d, Y') : 'N/A',
                'birthplace' => $detail->birthplace,
                'sex' => ucfirst($detail->sex ?? 'N/A'),
                'civil_status' => ucfirst($detail->civil_status ?? 'N/A'),
                'citizenship' => $detail->citizenship,
                'religion' => $detail->religion ?? 'N/A',
                'occupation' => $detail->occupation ?? 'N/A',
                'indigenous_people' => $detail->indigenous_people === 'yes',
                'pwd' => $detail->pwd === 'yes',
                'senior_citizen' => $detail->senior_citizen === 'yes',
                'fourps_beneficiary' => $detail->fourps_beneficiary === 'yes',
                'profile_photo_url' => $detail->profile_photo_url,
            ] : null,
            'ids' => $operatorIds->map(function($id) {
                return [
                    'id_type' => $id->id_type,
                    'id_number' => $id->id_number,
                    'issue_date' => $id->issue_date ? $id->issue_date->format('M d, Y') : 'N/A',
                    'expiry_date' => $id->expiry_date ? $id->expiry_date->format('M d, Y') : 'N/A',
                    'issuing_authority' => $id->issuing_authority ?? 'N/A',
                    'is_expired' => $id->is_expired,
                    'is_expiring_soon' => $id->is_expiring_soon,
                ];
            })
        ];

        return response()->json($profileData);
    }

    /**
     * Render the profile modal content view
     */
    public function showProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user->isOperator()) {
            abort(403, 'Unauthorized');
        }

        // Get the operator record with all relationships
        // Note: user_id in operators table is the database ID, not the user_id string
        $operator = Operator::with([
            'operatorDetail',
            'operatorIds',
            'user'
        ])->where('user_id', $user->id)->first();

        if (!$operator) {
            abort(404, 'Operator profile not found');
        }

        return view('operator.profile-modal', compact('operator'));
    }

    /**
     * Update operator profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user->isOperator()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Validate input
        $validator = \Validator::make($request->all(), [
            'phone' => ['required', new \App\Rules\PhilippinePhoneNumber],
            'address' => 'required|string|max:500',
            'id_number' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'valid_id' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the operator record
        $operator = Operator::with(['operatorDetail'])->where('user_id', $user->id)->first();

        if (!$operator) {
            return response()->json(['success' => false, 'message' => 'Operator not found'], 404);
        }

        try {
            \DB::beginTransaction();

            // Capture original values before update
            $originalOperatorValues = $operator->getOriginal();
            $originalDetailValues = $operator->operatorDetail ? $operator->operatorDetail->getOriginal() : [];

            // Update operator table fields
            $operator->update([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Track changes for audit trail
            $changes = [];
            $changedFields = [];

            // Track operator changes
            if ($originalOperatorValues['phone'] != $request->phone) {
                $changedFields[] = 'phone';
                $changes['phone'] = [
                    'old' => $originalOperatorValues['phone'] ?? 'None',
                    'new' => $request->phone
                ];
            }

            if ($originalOperatorValues['address'] != $request->address) {
                $changedFields[] = 'address';
                $changes['address'] = [
                    'old' => $originalOperatorValues['address'] ?? 'None',
                    'new' => $request->address
                ];
            }

            // Update operator detail if exists
            if ($operator->operatorDetail) {
                $detailData = [];

                // Update ID number if provided
                if ($request->has('id_number')) {
                    $detailData['id_number'] = $request->id_number;

                    $oldIdNumber = $originalDetailValues['id_number'] ?? null;
                    if ($oldIdNumber != $request->id_number) {
                        $changedFields[] = 'id_number';
                        $changes['id_number'] = [
                            'old' => $oldIdNumber ?? 'None',
                            'new' => $request->id_number
                        ];
                    }
                }

                if ($request->hasFile('profile_photo')) {
                    $file = $request->file('profile_photo');
                    $filename = 'profile_' . $operator->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                    // Upload to S3 under 'operator_photos/'
                    $path = $file->storeAs('operator_photos', $filename, 's3');

                    $detailData['profile_photo_path'] = $path;

                    $changedFields[] = 'profile_photo';
                    $changes['profile_photo'] = [
                        'old' => $originalDetailValues['profile_photo_path'] ?? 'None',
                        'new' => 'Updated'
                    ];
                }

                if ($request->hasFile('valid_id')) {
                    $file = $request->file('valid_id');
                    $filename = 'valid_id_' . $operator->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                    // Upload to S3 under 'operator_ids/'
                    $path = $file->storeAs('operator_ids', $filename, 's3');

                    $detailData['valid_id_path'] = $path;

                    $changedFields[] = 'valid_id';
                    $changes['valid_id'] = [
                        'old' => $originalDetailValues['valid_id_path'] ?? 'None',
                        'new' => 'Updated'
                    ];
                }

                if (!empty($detailData)) {
                    $operator->operatorDetail->update($detailData);
                }
            }

            // Log profile update with changes
            if (!empty($changedFields)) {
                $description = "Updated profile information: {$operator->business_name}";
                $description .= " (Changed: " . implode(', ', $changedFields) . ")";

                AuditTrail::log(
                    'updated',
                    $description,
                    'Operator',
                    $operator->id,
                    $changes
                );
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Profile update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
/**
 * View application form (PDF)
//  *
//  * @param Request $request
//  * @return \Illuminate\Http\Response
//  */
// public function viewApplicationForm(Request $request)
// {
//     $user = Auth::user();

//     if (!$user->isOperator()) {
//         abort(403, 'Unauthorized');
//     }

//     // Get the operator record
//     $operator = Operator::where('user_id', $user->id)->first();

//     if (!$operator) {
//         abort(404, 'Operator not found');
//     }

//     // Check if membership form exists
//     if (!$operator->membership_form_path) {
//         abort(404, 'Application form not found. Please contact the administrator.');
//     }

//     // Get the full path to the PDF file
//     $filePath = storage_path('app/public/' . $operator->membership_form_path);

//     // Check if file exists
//     if (!file_exists($filePath)) {
//         abort(404, 'Application form file not found on server. Please contact the administrator.');
//     }

//     // Return the PDF file for viewing in browser
//     return response()->file($filePath, [
//         'Content-Type' => 'application/pdf',
//         'Content-Disposition' => 'inline; filename="application-form.pdf"'
//     ]);
// }

}
