<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RequirementController extends Controller
{
    /**
     * Display a listing of requirements
     */
    public function index()
    {
        $requirementTypes = [
            'cda_compliance' => 'Certificate of Compliance (CDA)',
            'tax_exemption' => 'Certificate of Tax Exemption',
            'bir_registration' => 'Annual Registration with the BIR',
            'business_permit' => 'Business Permit (From the LGU)'
        ];

        $requirements = [];
        foreach ($requirementTypes as $key => $label) {
            $requirements[$key] = [
                'label' => $label,
                'latest' => Requirement::getLatestByType($key)
            ];
        }

        return view('requirements.index', compact('requirements', 'requirementTypes'));
    }

    /**
     * Store a newly uploaded requirement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:cda_compliance,tax_exemption,bir_registration,business_permit',
            'file' => 'required|mimes:png,jpg,jpeg|max:5120', // 5MB max - PNG, JPG, JPEG only
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'document_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $validated['type'] . '.' . $file->getClientOriginalExtension();

        // Store the file in S3 under 'requirements/' folder
        $path = $file->storeAs('requirements', $filename, 's3');

        // Create requirement record
        $requirement = Requirement::create([
            'type' => $validated['type'],
            'file_path' => $path,
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'document_number' => $validated['document_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Requirement uploaded successfully!',
            'requirement' => $requirement
        ]);
    }

    /**
     * Get the latest requirement for a specific type
     */
    public function show($type)
    {
        $requirement = Requirement::where('type', $type)
            ->with('uploader')
            ->latest()
            ->first();

        if (!$requirement) {
            abort(404, 'No requirement found');
        }

        // Check if file exists in S3
        if (!Storage::disk('s3')->exists($requirement->file_path)) {
            abort(404, 'File not found in storage');
        }

        $filename = basename($requirement->file_path);
        $extension = strtolower(pathinfo($requirement->file_path, PATHINFO_EXTENSION));
        
        // For images, wrap in HTML to display properly
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            $imageUrl = $requirement->file_url; // Use the S3 URL from your model accessor
            
            return response()->make('
                <!DOCTYPE html>
                <html>
                <head>
                    <title>' . htmlspecialchars($filename) . '</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body { 
                            margin: 0; 
                            padding: 0; 
                            display: flex; 
                            justify-content: center; 
                            align-items: center;
                            min-height: 100vh;
                            background: #1a1a1a;
                        }
                        img { 
                            max-width: 100%; 
                            max-height: 100vh; 
                            width: auto;
                            height: auto;
                            object-fit: contain;
                        }
                    </style>
                </head>
                <body>
                    <img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($filename) . '">
                </body>
                </html>
            ', 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        }
        
        // For PDFs and other files, redirect to S3 URL
        return redirect($requirement->file_url);
    }

    /**
     * Delete a requirement
     */
    public function destroy(Requirement $requirement)
    {
        // Delete the file
        if (Storage::disk('s3')->exists($requirement->file_path)) {
            Storage::disk('s3')->delete($requirement->file_path);
        }

        $requirement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Requirement deleted successfully!'
        ]);
    }
}
