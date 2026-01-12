<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MembershipFormController extends Controller
{
    /**
     * Download the membership application form PDF with pre-filled data
     *
     * This method generates a PDF with operator registration data pre-filled
     * when an operator_id is provided. Otherwise, it serves a blank form.
     */
    public function download(Request $request)
    {
        $data = [];

        if ($request->has('operator_id')) {
            $operatorId = $request->input('operator_id');

            // Fetch operator details from database
            $operatorDetails = DB::table('operator_details')
                ->where('operator_id', $operatorId)
                ->first();

            if ($operatorDetails) {
                // Prepare data for PDF pre-filling
                $data = [
                    'firstName' => $operatorDetails->first_name ?? '',
                    'middleName' => $operatorDetails->middle_name ?? '',
                    'lastName' => $operatorDetails->last_name ?? '',
                    'birthdate' => $operatorDetails->birthdate ? \Carbon\Carbon::parse($operatorDetails->birthdate)->format('F d, Y') : '',
                    'birthplace' => $operatorDetails->birthplace ?? '',
                    'sex' => $operatorDetails->sex ?? '',
                    'civilStatus' => ucfirst($operatorDetails->civil_status ?? ''),
                    'citizenship' => $operatorDetails->citizenship ?? '',
                    'religion' => $operatorDetails->religion ?? '',
                    'occupation' => $operatorDetails->occupation ?? '',
                ];

                // Get operator contact information
                $operator = DB::table('operators')
                    ->where('id', $operatorId)
                    ->first();

                if ($operator) {
                    $data['address'] = $operator->address ?? '';
                    $data['contactNumber'] = $operator->phone ?? '';
                    $data['email'] = $operator->email ?? '';
                    $data['businessName'] = $operator->business_name ?? '';
                    $data['businessAddress'] = $operator->address ?? '';
                    $data['businessPermitNo'] = $operator->business_permit_no ?? '';
                }

                // Add ID information
                $data['idType'] = $operatorDetails->id_type ?? '';
                $data['idNumber'] = $operatorDetails->id_number ?? '';

                // Add special classifications
                $data['indigenousPeople'] = $operatorDetails->indigenous_people ?? 'no';
                $data['pwd'] = $operatorDetails->pwd ?? 'no';
                $data['seniorCitizen'] = $operatorDetails->senior_citizen ?? 'no';
                $data['fourpsBeneficiary'] = $operatorDetails->fourps_beneficiary ?? 'no';

                $dependents = DB::table('operator_dependents')
                    ->where('operator_id', $operatorId)
                    ->get()
                    ->map(function($dependent) {
                        return [
                            'name' => $dependent->name,
                            'age' => $dependent->age,
                            'relation' => $dependent->relation,
                        ];
                    })
                    ->toArray();

                $data['dependents'] = $dependents;
            }
        }

        // Generate PDF using DomPDF with pre-filled data
        $pdf = Pdf::loadView('membership-form-pdf', $data)
            ->setPaper('a4', 'portrait');

        $pdf->setOption('isPhp7Compatible', true);
        $pdf->setOption('defaultFont', 'DejaVu Sans');

        // Generate filename with operator name if available
        $filename = 'TSJAODTC_Membership_Application_Form';
        if (isset($data['lastName']) && isset($data['firstName'])) {
            $filename .= '_' . $data['lastName'] . '_' . $data['firstName'];
        }
        $filename .= '.pdf';

        // Download the PDF file
        return $pdf->download($filename);
    }

    /**
     * Download blank membership form (legacy support)
     * This is kept for backward compatibility with static PDF downloads
     */
    public function downloadBlank()
    {
        $path = 'documents/membership_application_form.pdf';

        if (!Storage::disk('s3')->exists($path)) {
            return $this->download(request());
        }

        return Storage::disk('s3')->download(
            $path,
            'TSJAODTC_Membership_Application_Form.pdf',
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
}
