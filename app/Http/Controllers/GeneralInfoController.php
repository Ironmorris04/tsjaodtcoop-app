<?php

namespace App\Http\Controllers;

use App\Models\GeneralInfo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneralInfoController extends Controller
{
    /**
     * Display the general info form
     */
    public function index()
    {
        $generalInfo = GeneralInfo::first() ?? new GeneralInfo();

        return view('admin.general-info', compact('generalInfo'));
    }

    /**
     * Store or update the general info
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_no' => 'nullable|string|max:255',
            'cooperative_name' => 'nullable|string|max:255',
            'reg_region' => 'nullable|string|max:255',
            'reg_province' => 'nullable|string|max:255',
            'reg_municipality_city' => 'nullable|string|max:255',
            'reg_barangay' => 'nullable|string|max:255',
            'reg_street' => 'nullable|string|max:255',
            'reg_house_lot_blk_no' => 'nullable|string|max:255',
            'present_region' => 'nullable|string|max:255',
            'present_province' => 'nullable|string|max:255',
            'present_municipality_city' => 'nullable|string|max:255',
            'present_barangay' => 'nullable|string|max:255',
            'present_street' => 'nullable|string|max:255',
            'present_house_lot_blk_no' => 'nullable|string|max:255',
            'date_registration_prior_ra9520' => 'nullable|date',
            'date_registration_under_ra9520' => 'nullable|date',
            'business_permit_no' => 'nullable|string|max:255',
            'business_permit_date_issued' => 'nullable|date',
            'business_permit_amount_paid' => 'nullable|numeric|min:0',
            'tax_identification_number' => 'nullable|string|max:255',
            'category_of_cooperative' => 'nullable|string|max:255',
            'type_of_cooperative' => 'nullable|string|max:255',
            'asset_size' => 'nullable|string|max:255',
            'common_bond_membership' => 'nullable|string|max:255',
            'date_of_general_assembly' => 'nullable|date',
            'area_of_operation' => 'nullable|string',
        ]);

        // Update or create the first record
        $generalInfo = GeneralInfo::first();
        if ($generalInfo) {
            $generalInfo->update($validated);
            $message = 'General information updated successfully.';
        } else {
            GeneralInfo::create($validated);
            $message = 'General information saved successfully.';
        }

        return redirect()->route('admin.general-info')
            ->with('success', $message);
    }

    /**
     * Generate and download PDF for general info
     */
    public function pdf()
    {
        $generalInfo = GeneralInfo::first() ?? new GeneralInfo();

        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('admin.general-info-pdf', compact('generalInfo'))
            ->setPaper('a4', 'portrait');

                    $pdf->setOption('isPhp7Compatible', true);
        $pdf->setOption('defaultFont', 'DejaVu Sans');
        // Download the PDF file
        return $pdf->download('general-information.pdf');
    }
}
